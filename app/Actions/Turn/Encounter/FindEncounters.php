<?php

namespace App\Actions\Turn\Encounter;

use App\Models\Fleet;
use App\Models\PlayerRelation;
use App\Models\Ship;
use App\Models\Shipyard;
use App\Models\Star;
use App\Models\Game;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Exception;
use PhpParser\Node\Expr\Array_;
use Ramsey\Uuid\Uuid;

use App\Services\PlayerRelationService;

class FindEncounters
{

    /**
     * @function default encounter model
     * @param Star $star
     * @param Collection $shipyards
     * @param int $game
     * @param int $turn
     * @return Collection
     */
    private function createEncounter (Star $star, Collection $shipyards, int $game, int $turn): Collection
    {
        return collect([
            'id' => Uuid::uuid4(),
            'game' => $game,
            'turn' => $turn,
            'game_id' => $star->game_id,
            'star' => [
                'id' => $star->id,
                'name' => $star->name,
                'spectral' => $star->spectral,
                'owner' => $star->owner
            ],
            'defender' => collect(),
            'shipyards' => $shipyards,
            'attacker' => collect(),
        ]);
    }

    /**
     * @function static damage ranges setup
     * @return array
     */
    private function damageRanges (): array
    {
        return [
            'small.laser' => 0,
            'small.missile' => 0,
            'small.railgun' => 0,
            'small.plasma' => 0,
            'medium.laser' => 0,
            'medium.missile' => 0,
            'medium.railgun' => 0,
            'medium.plasma' => 0,
            'large.laser' => 0,
            'large.missile' => 0,
            'large.railgun' => 0,
            'large.plasma' => 0,
            'xlarge.laser' => 0,
            'xlarge.missile' => 0,
            'xlarge.railgun' => 0,
            'xlarge.plasma' => 0,
        ];
    }

    /**
     * @function format a fleet for data handling
     * @param Fleet $fleet
     * @param array $ships
     * @param int $col
     * @return array
     */
    private function formatFleet (Fleet $fleet, array $ships, int $col): array
    {
        $dmg = $this->damageRanges();
        $preferedRange = 10;
        foreach($ships as $ship) {
            $hullType = $ship['hull_type'];
            if ($ship['dmg_laser'] > 0) $dmg[$hullType.".laser"] += $ship['dmg_laser'];
            if ($ship['dmg_missile'] > 0) $dmg[$hullType.".missile"] += $ship['dmg_missile'];
            if ($ship['dmg_railgun'] > 0) $dmg[$hullType.".railgun"] += $ship['dmg_railgun'];
            if ($ship['dmg_plasma'] > 0) $dmg[$hullType.".plasma"] += $ship['dmg_plasma'];
        }
        $maxDmg = max($dmg);
        if ($maxDmg > 0) {
            $maxDmgWpnType = array_search(max($dmg), $dmg);
            $preferedRange = collect(config('rules.modules'))->filter(function($mod) use ($maxDmgWpnType) {
                return $mod['hullType'] === explode('.', $maxDmgWpnType)[0]
                    && $mod['techType'] === explode('.', $maxDmgWpnType)[1];
            })->first()['range'];
        }
        return [
            'id' => $fleet->id,
            'player_id' => $fleet->player_id,
            'name' => '['.$fleet->player->ticker.'] '.$fleet->name,
            'col' => $col,
            'prefered_range' => $preferedRange,
            'ships' => collect($ships)
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
        return [
            'id' => $shipyard->id,
            'player_id' => $shipyard->player_id,
            'starName' => $shipyard->planet->star->name,
            'planetOrbitalIndex' => $shipyard->planet->orbital_index,
            'col' => $col,
            'ships' => collect($ships)
        ];
    }

    /**
     * @function get the encounters for a star
     * @param Star $star
     * @param Collection $gameRelations
     * @param Collection $shipyards
     * @param string $turnSlug
     * @param int $game
     * @param int $turn
     * @return Collection
     */
    private function getStarEncounter (
        Star $star,
        Collection $gameRelations,
        Collection $shipyards,
        string $turnSlug,
        int $game,
        int $turn
    ): Collection
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
        $encounter = $this->createEncounter($star, $starShipyards, $game, $turn);

        // assign fleet ships to attacker/defender
        foreach($star->fleets as $fleet) {
            // check fleet owner
            if ($star->owner->id !== $fleet->player_id) {
                $effectiveRelation = $p->getEffectiveRelation($fleet->player, $star->owner, $gameRelations);
                // hostile fleet, add to attacker collection
                if ($effectiveRelation === 0 && count($fleet->ships) > 0) {
                    Log::channel('turn')
                        ->notice("$turnSlug found hostile fleet [".$fleet->player->ticker."] $fleet->name with ".count($fleet->ships)." ships.");
                    $encounter['attacker']->push($this->formatFleet($fleet, $fleet->ships->toArray(), 10));
                }
                // allied fleet, add to defender collection
                if ($effectiveRelation === 2 && count($fleet->ships) > 0) {
                    Log::channel('turn')
                        ->notice("$turnSlug found allied fleet [".$fleet->player->ticker."] $fleet->name with ".count($fleet->ships)." ships.");
                    $encounter['defender']->push($this->formatFleet($fleet, $fleet->ships->toArray(), 0));
                }
            } else if (count($fleet->ships) > 0) {
                // star owner fleet, add to defender
                Log::channel('turn')
                    ->notice("$turnSlug found fleet of star owner [".$fleet->player->ticker."] $fleet->name with ".count($fleet->ships)." ships.");
                $encounter['defender']->push($this->formatFleet($fleet, $fleet->ships->toArray(), 0));
            }
        }

        // assign shipyard ships to defender
        foreach ($starShipyards as $shipyard) {
            if (count($shipyard->ships) > 0)
            $encounter['defender']->push($this->formatShipyard($shipyard, $shipyard->ships->toArray(), 0));
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
            $encounter = $this->getStarEncounter($star, $gameRelations, $shipyards, $turnSlug, $game->number, $turn->number);
            // if there are attacking ships, handle encounter processing
            if (count($encounter['attacker']) > 0) {
                Log::channel('turn')
                    ->notice("$turnSlug found valid encounter ".$encounter['id']." @ [".$star->owner->ticker."] $star->name.");
                $p->handle($encounter, $turnSlug);
            } else {
                Log::channel('turn')
                    ->notice("$turnSlug no encounter @ [".$star->owner->ticker."] $star->name.");
            }
        }

    }

}
