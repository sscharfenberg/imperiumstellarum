<?php

namespace App\Actions\Turn\Encounter;

use App\Models\Encounter;
use App\Models\EncounterTurn;

use App\Models\Fleet;
use App\Models\Player;
use App\Models\Ship;
use App\Models\Star;
use App\Services\EncounterService;

use App\Services\MessageService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Exception;

class PersistEncounter
{


    /**
     * actually do database updates or not
     * @var bool
     */
    private $dryRun = true;


    /**
     * @function delete dead ships from database
     * @param Collection $encounter
     * @param string $turnSlug
     * @throw Exception
     * @return void
     */
    private function deleteDeadShips (Collection $encounter, string $turnSlug)
    {
        // no dry-run, really delete from database
        if (!$this->dryRun) {
            try {
                $numDeleted = Ship::where('game_id', '=', $encounter['game_id'])
                    ->whereIn('id', $encounter['dead_ships'])
                    ->delete();
                Log::channel('encounter')
                    ->notice("$turnSlug #".$encounter['id']." successfully deleted $numDeleted ships from database.");
            } catch (Exception $e) {
                Log::channel('encounter')
                    ->error(
                        "$turnSlug #".$encounter['id']." Exception while attempting to delete ships:\n"
                        .$e->getMessage()."\n".$e->getTraceAsString()
                    );
            }
        }
        // dry-run for testing
        else {
            echo "remove these ships from database:\n";
            dump($encounter['dead_ships']);
        }
    }


    /**
     * @function delete dead fleets from database
     * @param Collection $encounter
     * @param string $turnSlug
     * @throw Exception
     * @return void
     */
    private function deleteDeadFleets (Collection $encounter, string $turnSlug)
    {
        // no dry-run, really delete from database
        if (!$this->dryRun) {
            try {
                $numDeleted = Fleet::where('game_id', '=', $encounter['game_id'])
                    ->whereIn('id', $encounter['dead_fleets'])
                    ->delete();
                Log::channel('encounter')
                    ->notice("$turnSlug #".$encounter['id']." successfully deleted $numDeleted fleets from database.");
            } catch (Exception $e) {
                Log::channel('encounter')
                    ->error(
                        "$turnSlug #".$encounter['id']." Exception while attempting to delete fleets:\n"
                        .$e->getMessage()."\n".$e->getTraceAsString()
                    );
            }
        }
        // dry-run for testing
        else {
            echo "remove these fleets from database:\n";
            dump($encounter['dead_fleets']);
        }
    }


    /**
     * @function check the surviving ships in the encounter and update them if they are different from db values
     * @param Collection $encounter
     * @param string $turnSlug
     * @return void
     */
    private function updateDamagedShips (Collection $encounter, string $turnSlug)
    {
        $e = new EncounterService;
        $survivingShips = collect();
        $encounter['fleets']->each(function ($fleet) use (&$survivingShips) {
            $survivingShips = $survivingShips->concat(
                // filter ships so we only look at surviving ships that do not have full shields, armour & structure
                $fleet['ships']->filter(function($ship) {
                    return $ship['hp_structure_current'] !== $ship['hp_structure_max']
                        || $ship['hp_armour_current'] !== $ship['hp_armour_max']
                        || $ship['hp_shields_current'] !== $ship['hp_shields_max'];
                })
            );
        });
        Log::channel('encounter')
            ->notice(
                "$turnSlug #".$encounter['id']." comparing "
                .$survivingShips->count()." damaged ships with db entries"
            );
        // map to IDs for QueryBuilder
        $survivingShipIds = $survivingShips->map(function ($ship) {
            return $ship['id'];
        });
        // get current DB values, which is the state before the encounter began.
        $dbShips = Ship::where('game_id', '=', $encounter['game_id'])
            ->whereIn('id', $survivingShipIds)
            ->get();
        // loop surviving ships, compare with DB and update if necessary
        $dbShips->each(function($ship) use ($survivingShips, $encounter, $turnSlug, $e) {
            $updated = collect();
            // get encounter ship with data after encounter is resolved.
            $encounterShip = $survivingShips->where('id', $ship->id)->first();
            if ($this->dryRun) echo "checking ".$ship->name."\n";
            // compare structure
            if ($ship->hp_structure_current !== $encounterShip['hp_structure_current']) {
                $ship->hp_structure_current = $encounterShip['hp_structure_current'];
                if ($this->dryRun) echo "structure is different => ".$encounterShip['hp_structure_current']."\n";
                $updated->push('structure');
            }
            // compare armour
            if ($ship->hp_armour_current !== $encounterShip['hp_armour_current']) {
                $ship->hp_armour_current = $encounterShip['hp_armour_current'];
                if ($this->dryRun) echo "armour is different => ".$encounterShip['hp_armour_current']."\n";
                $updated->push('armour');
            }
            // compare shields
            if ($ship->hp_shields_current !== $encounterShip['hp_shields_current']) {
                $ship->hp_shields_current = $encounterShip['hp_shields_current'];
                if ($this->dryRun) echo "shields is different => ".$encounterShip['hp_shields_current']."\n";
                $updated->push('shields');
            }
            // if there where differences, update db entry
            if ($updated->count() > 0 && !$this->dryRun) {
                $ship->save();
                Log::channel('encounter')
                    ->notice(
                        "$turnSlug #".$encounter['id']." successfully updated damaged ship '"
                        .$e->getFleetFullName($encounterShip)."' in db. changed: "
                        .json_encode($updated)
                    );
            }
            if ($updated->count() > 0 && $this->dryRun) {
                echo "updated ship in database ";
                dump($encounterShip);
            }
        });
    }


    /**
     * @function update encounter in database
     * @param Collection $encounter
     * @param string $turnSlug
     */
    private function updateDbEncounter (Collection $encounter, string $turnSlug)
    {
        $dbEncounter = Encounter::where('id', '=', $encounter['id'])->first();
        $dbEncounter->winner = $encounter['winner'];
        $dbEncounter->processed_at = now();
        $dbEncounter->save();
        Log::channel('encounter')
            ->notice("$turnSlug #".$encounter['id']." successfully updated encounter with winner and set to processed.");
    }


    /**
     * @function notify players of the owner change.
     * @param string $gameId
     * @param string $losingPlayer
     * @param string $winningPlayer
     * @param Star $star
     * @return void
     */
    private function notifyOwnerChange (string $gameId, string $losingPlayer, string $winningPlayer, Star $star)
    {
        $m = new MessageService;
        $playerLost = Player::where('game_id', '=', $gameId)
            ->where('id', '=', $losingPlayer)
            ->first();
        $playerGained = Player::where('game_id', '=', $gameId)
            ->where('id', '=', $winningPlayer)
            ->first();
        $numShipyards = $star->shipyards_count;
        $numHarvesters = 0;
        $star->planets->each(function ($planet) use (&$numHarvesters) {
            $numHarvesters += $planet->harvesters_count;
        });
        // send notification to the player that has lost the system.
        $m->sendNotification(
            $playerLost,
            'game.messages.sys.encounterStar.lost.subject',
            'game.messages.sys.encounterStar.lost.body',
            [
                'star' => $star->name." (".$star->coord_x."/".$star->coord_y.")",
                'empire' => "[$playerGained->ticker] $playerGained->name",
                'numShipyards' => $numShipyards,
                'numHarvesters' => $numHarvesters
            ]
        );
        // send notification to the player that has won the system.
        $m->sendNotification(
            $playerGained,
            'game.messages.sys.encounterStar.gained.subject',
            'game.messages.sys.encounterStar.gained.body',
            [
                'star' => $star->name." (".$star->coord_x."/".$star->coord_y.")",
                'empire' => "[$playerLost->ticker] $playerLost->name",
                'numShipyards' => $numShipyards,
                'numHarvesters' => $numHarvesters
            ]
        );
    }


    /**
     * @function handle owner change of star
     * @param Collection $encounter
     * @param string $turnSlug
     * @return void
     */
    private function ownerChange (Collection $encounter, string $turnSlug)
    {
        $e = new EncounterService;
        // find out which attacking player gets star ownership
        $attackerFleets = $e->getAttackers($encounter);
        $attackingPlayers = $attackerFleets->pluck('player_id')->countBy()->map(function ($player) { return 0; });
        $attackerFleets->each(function ($fleet) use (&$attackingPlayers) {
            $currentCount = $attackingPlayers->get($fleet['player_id']);
            $currentCount += config('rules.encounters.ownerChange.ark') * count($fleet['ships']->filter(function($ship) {
                return $ship['hull_type'] === 'ark';
            }));
            $currentCount += config('rules.encounters.ownerChange.small') * count($fleet['ships']->filter(function($ship) {
                return $ship['hull_type'] === 'small';
            }));
            $currentCount += config('rules.encounters.ownerChange.medium') * count($fleet['ships']->filter(function($ship) {
                return $ship['hull_type'] === 'medium';
            }));
            $currentCount += config('rules.encounters.ownerChange.large') * count($fleet['ships']->filter(function($ship) {
                return $ship['hull_type'] === 'large';
            }));
            $currentCount += config('rules.encounters.ownerChange.xlarge') * count($fleet['ships']->filter(function($ship) {
                return $ship['hull_type'] === 'xlarge';
            }));
            $attackingPlayers->put($fleet['player_id'], $currentCount);
        });
        $newOwnerId = $attackingPlayers->sortDesc()->take(1)->keys()->first();
        if ($newOwnerId) {
            $star = Star::where('game_id', '=', $encounter['game_id'])
                ->where('id', '=', $encounter['star']['id'])
                ->first();
            $this->notifyOwnerChange($encounter['game_id'], $star->player_id, $newOwnerId, $star);
            if (!$this->dryRun) {
                $star->player_id = $newOwnerId;
                $star->save();
                $shipyards = $star->shipyards;
                $harvesters = $star->planets->harvesters;
                $constructionContract = $star->shipyards->constructionContract;
                if ($shipyards->count() > 0) {
                    $shipyards->update(['player_id' => $newOwnerId]);
                }
                if ($harvesters->count() > 0) {
                    $harvesters->update(['player_id' => $newOwnerId]);
                }
                if ($constructionContract) {
                    $constructionContract->delete();
                }
            } else {
                echo "changing ownership of star to player ".$newOwnerId."\n";
            }
        }
        // TODO: TEST THIS.
    }

    /**
     * @function handle persisting encounter - update/delete database entries.
     * @param Collection $encounter
     * @param string $turnSlug
     * @param int $turn
     * @return void
     */
    public function handle (Collection $encounter, string $turnSlug, int $turn)
    {

        echo "\n\nPERSISTING ENCOUNTER TO DATABASE\n";
        Log::channel('encounter')
            ->info("$turnSlug #".$encounter['id']." TURN $turn STEP 8: persist results in database.");

        // 1) delete the ships that died during the encounter.
        $this->deleteDeadShips($encounter, $turnSlug);

        // 2) delete the fleets that died during the encounter.
        $this->deleteDeadFleets($encounter, $turnSlug);

        // 3) update damaged ships
        $this->updateDamagedShips($encounter, $turnSlug);

        // 4) change ownership
        if ($encounter['winner'] === 'attacker') {
            $this->ownerChange($encounter, $turnSlug);
        }

        // 6) if draw, attacking fleets return home.
        if ($encounter['winner'] === 'draw') {
            echo "handle draw, send attacking fleets home.\n";
        }

        // 7) update encounter
        $this->updateDbEncounter($encounter, $turnSlug);


    }

}
