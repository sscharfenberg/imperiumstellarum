<?php

namespace App\Actions\Turn;

use App\Models\ConstructionContract;
use App\Models\Game;
use App\Models\Player;
use App\Models\Ship;
use App\Services\ResourceService;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Services\ShipService;


class BuildShips
{

    /**
     * @function finalize a contract
     * @param ConstructionContract $contract
     * @throws Exception
     */
    private function contractFinished(ConstructionContract $contract)
    {
        try {
            ConstructionContract::find($contract->id)->delete();
            // TODO: send system message to player that a contract has finished.
            Log::notice('contract deleted since it was finished.');
        } catch(Exception $e) {
            Log::error('failed to delete a finished contract: '.$e->getMessage());
        }
    }

    /**
     * @function create the ship
     * @param Player $player
     * @param array $shipData
     * @return Ship
     * @throws Exception
     */
    private function createShip(Player $player, array $shipData): Ship
    {
        $s = new ShipService;
        $ship = Ship::create([
            'game_id' => $shipData['game_id'],
            'fleet_id' => $shipData['fleet_id'],
            'player_id' => $shipData['player_id'],
            'hull_type' => $shipData['hull_type'],
            'name' => $s->randomShipName(),
            'dmg_plasma' => $shipData['dmg_plasma'],
            'dmg_railgun' => $shipData['dmg_railgun'],
            'dmg_missile' => $shipData['dmg_missile'],
            'dmg_laser' => $shipData['dmg_laser'],
            'hp_shields_max' => $shipData['hp_shields'],
            'hp_shields_current' => $shipData['hp_shields'],
            'hp_armour_max' => $shipData['hp_armour'],
            'hp_armour_current' => $shipData['hp_armour'],
            'hp_structure_max' => $shipData['hp_structure'],
            'hp_structure_current' => $shipData['hp_structure'],
            'ftl' => $shipData['ftl'],
            'colony' => $shipData['colony'],
            'acceleration' => $shipData['acceleration']
        ]);
        Log::notice("Empire $player->ticker has created a new ship, $ship->name");
        return $ship;
    }


    /**
     * @function create ship from construction contract
     * @param Collection $contracts
     * @throws Exception
     */
    private function ejectShips(Collection $contracts)
    {
        $s = new ShipService;
        $r = new ResourceService;
        foreach($contracts as $contract) {
            $player = $contract->player;
            // is the contract finished?
            if ($contract->amount_left === 1) {
                $this->createShip($player, $contract->cached_ship);
                $this->contractFinished($contract);
            } else {
                // no, proceed with next ship in the contract.

                // if we didn't fail to pay the resource costs for the ship the last time, eject it.
                if (!$contract->hold) {
                    // create ship
                    $this->createShip($player, $contract->cached_ship);
                    // since the ship was created, decrement amount_left.
                    $contract->amount_left -= 1;
                }

                $resourceCosts = [
                    'minerals' => $contract->costs_minerals,
                    'energy' => $contract->costs_energy
                ];

                // can the player afford the costs for the next ship in the batch?
                if ($r->playerCanAfford($player, $resourceCosts)) {
                    // pay for the next ship
                    $r->subtractResources($player, $resourceCosts);
                    // reset turns clock so we start building the next ship
                    $contract->turns_left = $contract->turns_per_ship;
                    $contract->hold = false;
                    Log::notice("Empire $player->ticker has paid the resource costs for the next ship.");
                } else {
                    Log::notice("Empire $player->ticker can't afford the resource costs of the next ship in the construction contract.");
                    $contract->hold = true;
                    // TODO: message to player - can't afford the next ship, on hold.
                }

                // does the ship cost population (ark), and can the player afford the population costs?
                if ($contract->costs_population > 0) {
                    $shipyard = $contract->shipyard;
                    if ($s->shipyardHasSufficientPopulation($shipyard, $contract->costs_population)) {
                        // pay population for next ship
                        $s->subtractPopulation($shipyard, $contract->costs_population);
                        // reset turns clock so we start building the next ship
                        $contract->turns_left = $contract->turns_per_ship;
                        $contract->hold = false;
                        Log::notice("Empire $player->ticker has paid the population costs for the next ship.");
                    } else {
                        Log::notice("Empire $player->ticker can't afford the population costs of the next ship in the construction contract.");
                        $contract->turns_left = 0; // reset turns_left since the player might have enough resources but not enough population.
                        $contract->hold = true;
                        // TODO: message to player - can't afford the next ship, on hold.
                    }
                }

            }

            // save the contract
            $contract->save();
        }
    }


    /**
     * @function build ships
     * @param Game $game
     * @throws Exception
     * @return void
     */
    public function handle(Game $game)
    {
        // decrement 'turns_left'
        $decrementedContracts = ConstructionContract::where('game_id', $game->id)
            ->where('turns_left', '>', '0')
            ->decrement('turns_left');
        if ($decrementedContracts) {
            Log::notice("Decreased 'turns_left' for $decrementedContracts contracts.");
        } else {
            Log::notice("No construction contracts where turns_left was decremented.");
        }

        // check if any ships are ready to be ejected.
        $contractsWithShipsReady = ConstructionContract::where('game_id', $game->id)
            ->where('turns_left', '=', '0')
            ->get();
        if(count($contractsWithShipsReady) > 0) {
            $num = count($contractsWithShipsReady);
            Log::notice("Trying to eject $num ships.");
            $this->ejectShips($contractsWithShipsReady);
        } else {
            Log::notice("No ships are ready to be ejected.");
        }

        // all done.
        Log::info('done processing ship construction / construction contract handling.');
    }

}
