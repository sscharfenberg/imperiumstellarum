<?php

namespace App\Actions\Turn\Encounter;

use App\Models\Harvester;
use App\Models\Player;
use App\Models\PlayerResource;
use App\Models\Raid;
use App\Models\RaidPlayer;
use App\Models\Star;

use App\Services\EncounterService;
use App\Services\ResourceService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProcessEncounterRaid
{

    /**
     * do database updates or not.
     * set this to "true" if you want to test something and not actually write to db
     * @var bool
     */
    private $dryRun = true;


    /**
     * @function calculate the resources that will be transfered from defender => attacker
     * @param Star $star
     * @return Collection
     */
    private function calculateResourceTransfer(Star $star): Collection
    {
        $starHarvesters = Harvester::where('game_id', '=', $star->game_id)
            ->whereIn('planet_id',$star->planets->map(function ($planet) {
                return $planet->id;
            }))
            ->where('until_complete','=', 0)
            ->get();
        $raidResources = collect(array_fill_keys(array_keys(config('rules.player.resourceTypes')), 0));
        foreach($starHarvesters as $harvester) {
            $raidResources[$harvester->resource_type] += $harvester->production * config('rules.encounters.raidFactor');
        }
        return $raidResources->map( function ($res) {
            return intval(round($res));
        });
    }


    /**
     * @function transfer resources from defender to raiders
     * @param Player $owner
     * @param Collection $attackers
     * @param Collection $raidResources
     * @param string $turnSlug
     * @return Collection
     */
    private function transferResources (Player $owner, Collection $attackers, Collection $raidResources, string $turnSlug): Collection
    {
        // subtract resources from attacker
        $r = new ResourceService;
        if (!$this->dryRun) {
            $r->subtractResources($owner, $raidResources->toArray());
        }
        Log::channel('encounter')
            ->info(
                "$turnSlug Encounter raid: subtracted resources from owner [$owner->ticker] $owner->name:"
                .json_encode($raidResources, JSON_PRETTY_PRINT)
            );
        // add resources to attackers
        $attackerResources = [];
        $numAttackers = $attackers->count();
        $raiderResources = $raidResources->map( function ($val) use ($numAttackers) {
            // divide resources evenly by (rounded) number of attackers.
            return intval(round($val * (1 / $numAttackers)));
        });
        // loop all attackers and assign resources to be added to each
        foreach($attackers as $attacker) {
            $attackerResources[$attacker] = $raiderResources;
        }
        // loop attacking players again and add resources while enforcing storage max.
        foreach($attackerResources as $attackerId => $resources) {
            $playerResources = PlayerResource::where('game_id', '=', $owner->game_id)
                ->where('player_id', '=', $attackerId)
                ->get();
            if (!$this->dryRun) {
                $r->addResources($playerResources, $raidResources->toArray());
            }
            Log::channel('encounter')
                ->info(
                    "$turnSlug Encounter raid: added resources to raiding player [$owner->ticker] $owner->name:"
                    .json_encode($resources, JSON_PRETTY_PRINT)
                );
        }
        return $raiderResources;
    }


    /**
     * @function create a new raid
     * @param Star $star
     * @param Player $owner
     * @param int $turnNumber
     * @return Raid
     */
    private function createNewRaid (Star $star, Player $owner, int $turnNumber): Raid
    {
        return Raid::create([
            'game_id' => $star->game_id,
            'star_owner_id' => $owner->id,
            'star_id' => $star->id,
            'start_turn' => $turnNumber,
            'end_turn' => $turnNumber
        ]);
    }


    /**
     * @function create raid players
     * @param Raid $raid
     * @param Collection $attackerIds
     * @param Player $starOwner
     * @param Collection $raidedResources
     * @param Collection $raiderResources
     * @param string $turnSlug
     */
    private function createRaidPlayers (Raid $raid, Collection $attackerIds, Player $starOwner, Collection $raidedResources, Collection $raiderResources, string $turnSlug)
    {
        foreach ($attackerIds as $playerId) {
            $raidPlayer = RaidPlayer::create([
                'game_id' => $raid->game_id,
                'raid_id' => $raid->id,
                'player_id' => $playerId,
                'raider' => true,
                'energy' => $raiderResources['energy'],
                'minerals' => $raiderResources['minerals'],
                'food' => $raiderResources['food'],
                'research' => $raiderResources['research']
            ]);
            Log::channel('encounter')
                ->debug("created raid player #$raidPlayer->id with resources +$raiderResources");
        }
        $raidedPlayer = RaidPlayer::create([
            'game_id' => $raid->game_id,
            'raid_id' => $raid->id,
            'player_id' => $starOwner->id,
            'raider' => false,
            'energy' => $raidedResources['energy'],
            'minerals' => $raidedResources['minerals'],
            'food' => $raidedResources['food'],
            'research' => $raidedResources['research']
        ]);
        Log::channel('encounter')
            ->debug("created raided player #$raidedPlayer->id with resources -$raidedResources");
    }

    /**
     * @function process raid of hostile fleet at empty star
     * @param Star $star
     * @param Collection $encounter
     * @param string $turnSlug
     * @param int $turnNumber
     */
    public function handle (Star $star, Collection $encounter, string $turnSlug, int $turnNumber)
    {
        $e = new EncounterService;
        Log::channel('encounter')
            ->info("handle raid at star #$star->id $star->name ($star->coord_x / $star->coord_y)");
        $raidResources = $this->calculateResourceTransfer($star);
        $starOwner = Player::where('game_id', '=', $star->game_id)
            ->where('id', '=', $star->player_id)
            ->first();
        $attackingPlayers = $e->getAttackers($encounter)->map(function ($fleet) {
            return $fleet['player_id'];
        })->unique();
        $raiderResources = $this->transferResources($starOwner, $attackingPlayers, $raidResources, $turnSlug);
        // TODO: find out if it is a new raid or an existing raid.
        $raid = $this->createNewRaid($star, $starOwner, $turnNumber);
        Log::channel('encounter')
            ->debug("created new raid #$raid->id");
        $this->createRaidPlayers($raid, $attackingPlayers, $starOwner, $raidResources, $raiderResources, $turnSlug);
    }


}
