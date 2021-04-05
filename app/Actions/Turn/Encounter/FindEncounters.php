<?php

namespace App\Actions\Turn\Encounter;

use App\Models\Fleet;
use App\Models\PlayerRelation;
use App\Models\Shipyard;
use App\Models\Star;
use App\Models\Game;

use App\Services\EncounterService;
use App\Services\FleetService;
use App\Services\FormatApiResponseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Exception;
use Ramsey\Uuid\Uuid;

use App\Services\PlayerRelationService;

class FindEncounters
{

    /**
     * @function default encounter model
     * @param Star $star
     * @param int $turn
     * @return Collection
     */
    private function createEncounter (Star $star, int $turn): Collection
    {
        return collect([
            'id' => Uuid::uuid4()->toString(),
            'turn' => $turn,
            'game_id' => $star->game_id,
            'star' => [
                'id' => $star->id,
                'name' => $star->name,
                'spectral' => $star->spectral,
                'owner' => $star->owner
            ],
            'fleets' => collect(),
            'turns' => collect()
        ]);
    }

    /**
     * @function format a fleet for data handling
     * @param Fleet $fleet
     * @param array $ships
     * @param int $col
     * @param bool $attacker
     * @return array
     */
    private function formatFleet (Fleet $fleet, array $ships, int $col, bool $attacker): array
    {
        $f = new FleetService;
        return [
            'id' => $fleet->id,
            'player_id' => $fleet->player_id,
            'name' => '['.$fleet->player->ticker.'] '.$fleet->name,
            'col' => $col,
            'row' => 0,
            'preferred_range' => $f->getFleetPreferredRange($ships),
            'ships' => collect($ships),
            'is_shipyard' => false,
            'attacker' => $attacker,
            'turn_acceleration' => collect($ships)->min('acceleration')
        ];
    }

    /**
     * @function format a fleet for data handling
     * @param Shipyard $shipyard
     * @param array $ships
     * @param int $col
     * @return array
     */
    private function formatShipyard (Shipyard $shipyard, array $ships, int $col): array
    {
        $fl = new FleetService;
        $f = new FormatApiResponseService;
        return [
            'id' => $shipyard->id,
            'player_id' => $shipyard->player_id,
            'name' => '['.$shipyard->player->ticker.'] '.$shipyard->planet->star->name." - ".$f->convertLatinToRoman($shipyard->planet->orbital_index),
            'col' => $col,
            'row' => 0,
            'preferred_range' => $fl->getFleetPreferredRange($ships),
            'ships' => collect($ships),
            'is_shipyard' => true,
            'attacker' => false,
            'turn_acceleration' => collect($ships)->min('acceleration')
        ];
    }

    /**
     * @function assign Rows to fleets
     * @param Collection $encounter
     * @return Collection
     */
    private function assignRows (Collection $encounter): Collection
    {
        return $encounter['fleets']->map(function ($fleet, $index) {
            $fleet['row'] = $index;
            return $fleet;
        });
    }

    /**
     * @function get the encounters for a star
     * @param Star $star
     * @param Collection $gameRelations
     * @param Collection $shipyards
     * @param string $turnSlug
     * @param int $turn
     * @return Collection
     */
    private function getStarEncounter (Star $star, Collection $gameRelations, Collection $shipyards, string $turnSlug, int $turn): Collection
    {
        $p = new PlayerRelationService;

        Log::channel('turn')
            ->notice("$turnSlug checking star [".$star->owner->ticker."] $star->name.");
        // filter shipyards so we find out how many shipyards are located at this star
        $starShipyards = $shipyards->filter(function($shipyard) use ($star) {
            return $shipyard->star_id === $star->id;
        })->values();
        Log::channel('turn')
            ->notice(
                "$turnSlug found ".count($star->fleets)
                ." fleets and ".count($starShipyards)." shipyards in system."
            );
        $encounter = $this->createEncounter($star, $turn);

        // assign fleet ships to attacker/defender
        foreach($star->fleets as $fleet) {
            // check fleet owner
            if ($star->owner->id !== $fleet->player_id) {
                $effectiveRelation = $p->getEffectiveRelation($fleet->player, $star->owner, $gameRelations);
                // hostile fleet, add to attacker collection
                if ($effectiveRelation === 0 && count($fleet->ships) > 0) {
                    Log::channel('turn')
                        ->notice("$turnSlug found hostile fleet [".$fleet->player->ticker."] $fleet->name with ".count($fleet->ships)." ships.");
                    $encounter['fleets']->push($this->formatFleet($fleet, $fleet->ships->toArray(), 10, true));
                }
                // allied fleet, add to defender collection
                if ($effectiveRelation === 2 && count($fleet->ships) > 0) {
                    Log::channel('turn')
                        ->notice("$turnSlug found allied fleet [".$fleet->player->ticker."] $fleet->name with ".count($fleet->ships)." ships.");
                    $encounter['fleets']->push($this->formatFleet($fleet, $fleet->ships->toArray(), 0, false));
                }
            } else if (count($fleet->ships) > 0) {
                // star owner fleet, add to defender
                Log::channel('turn')
                    ->notice("$turnSlug found fleet of star owner [".$fleet->player->ticker."] $fleet->name with ".count($fleet->ships)." ships.");
                $encounter['fleets']->push($this->formatFleet($fleet, $fleet->ships->toArray(), 0, false));
            }
        }

        // assign shipyard ships to defender
        foreach ($starShipyards as $shipyard) {
            if (count($shipyard->ships) > 0) {
                $encounter['fleets']->push($this->formatShipyard($shipyard, $shipyard->ships->toArray(), 0));
                Log::channel('turn')
                    ->notice("$turnSlug found shipyard @ ".$shipyard->planet->star->name." with ".count($shipyard->ships)." ships.");
            }
        }

        return $encounter;
    }

    /**
     * @function find out if any encounters need to be processed and trigger processing.
     * @param Game $game
     * @param string $turnSlug
     * @throws Exception
     * @return void
     */
    public function handle(Game $game, string $turnSlug)
    {
        $p = new \App\Actions\Turn\Encounter\ProcessEncounter;
        $e = new EncounterService;
        $gameRelations = PlayerRelation::where('game_id', $game->id)
            ->get();
        $stars = Star::where('game_id', '=', $game->id)
            ->whereNotNull('player_id')
            ->with('fleets')
            ->with('owner')
            ->get()
            ->filter(function($star) {
                // only check stars that have a fleet with a different owner than the star owner
                return $star->fleets->filter(function ($fleet) {
                    return $fleet->star->player_id !== $fleet->player_id;
                })->count() > 0;
            });
        $shipyards = Shipyard::where('game_id', $game->id)
            ->where('until_complete', '=', 0)
            ->with('ships')
            ->get();
        $turn = $game->turns
            ->whereNull('processed')
            ->first();

        Log::channel('turn')
            ->notice($turnSlug.' - found '.count($stars).' stars that have a potential encounter.');

        // main loop - check each star and see if there is an encounter.
        foreach($stars as $star) {
            // create encounter with all necessary data
            $encounter = $this->getStarEncounter($star, $gameRelations, $shipyards, $turnSlug, $turn->number);
            // if there are attacking ships, handle encounter processing
            if (count($e->getAttackers($encounter)) > 0 && count($e->getDefenders($encounter)) > 0) {
                Log::channel('turn')
                    ->notice("$turnSlug found valid encounter ".$encounter['id']." @ [".$star->owner->ticker."] $star->name.");
                $encounter['fleets'] = $this->assignRows($encounter);
                $p->handle($encounter, $turnSlug);
            } else {
                Log::channel('turn')
                    ->notice("$turnSlug no encounter @ [".$star->owner->ticker."] $star->name.");
            }
        }
    }

}
