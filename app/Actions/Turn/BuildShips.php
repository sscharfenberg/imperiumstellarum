<?php

namespace App\Actions\Turn;

use App\Models\ConstructionContract;
use App\Models\Game;
use App\Models\Player;
use App\Models\Ship;
use App\Services\FormatApiResponseService;
use App\Services\MessageService;
use App\Services\ResourceService;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Services\ShipService;


class BuildShips
{

    /**
     * @function get user locale from contract
     * @param ConstructionContract $contract
     * @return string
     */
    private function getContractLocale (ConstructionContract $contract): string
    {
        return $contract->player->user->locale;
    }

    /**
     * @function get costs of a contract
     * @param ConstructionContract $contract
     * @return array
     */
    private function getContractCosts (ConstructionContract $contract): array
    {
        return [
            'minerals' => $contract->costs_minerals,
            'energy' => $contract->costs_energy,
            'population' => $contract->costs_population
        ];
    }

    /**
     * @function get costs of a contract
     * @param ConstructionContract $contract
     * @return array
     */
    private function getContractPayments (ConstructionContract $contract): array
    {
        return [
            'minerals' => $contract->paid_minerals,
            'energy' => $contract->paid_energy,
            'population' => $contract->paid_population
        ];
    }

    /**
     * @function verify if all costs have been paid
     * @param array $costs
     * @param array $paid
     * @return bool
     */
    private function verifyCostsArePaid (array $costs, array $paid): bool
    {
        return is_array($costs)
            && is_array($paid)
            && count($costs) === count($paid)
            && array_diff($costs, $paid) === array_diff($paid, $costs);
    }

    /**
     * @function pay the costs for the next ship in the batch
     * @param ConstructionContract $contract
     * @param array $costs
     * @param array $paid
     * @param string $turnSlug
     * @return array
     */
    private function payNextShipCosts (ConstructionContract $contract, array $costs, array &$paid, string $turnSlug): array
    {
        $r = new ResourceService;
        $s = new ShipService;
        $player = $contract->player;
        $shipyard = $contract->shipyard;

        // pay resources if they have not already been paid
        if (
            $r->playerCanAfford($player, $costs)
            && $contract->costs_energy > $contract->paid_energy
            && $contract->costs_minerals > $contract->paid_minerals
        ) {
            // pay for the next ship
            $r->subtractResources($player, $costs);
            $paid["energy"] = $costs["energy"];
            $paid["minerals"] = $costs["minerals"];
        }

        // pay population if it has not already been paid
        if (
            $contract->costs_population > 0
            && $contract->costs_population > $contract->paid_population
            && $s->shipyardHasSufficientPopulation($shipyard, $contract->costs_population)
        ) {
            $s->subtractPopulation($shipyard, $contract->costs_population);
            // reset turns clock so we start building the next ship
            $paid["population"] = $costs["population"];
        }

        return $paid;
    }

    /**
     * @function finalize a contract
     * @param ConstructionContract $contract
     * @param string $turnSlug
     * @throws Exception
     */
    private function contractFinished(ConstructionContract $contract, string $turnSlug)
    {
        $m = new MessageService;
        $f = new FormatApiResponseService;
        try {
            ConstructionContract::find($contract->id)->delete();
            $messageLocale = $this->getContractLocale($contract);
            $planet = $contract->shipyard->planet;
            $star = $planet->star;
            $m->sendSystemMessage(
                $contract->game_id,
                [$contract->player_id],
                __('game.messages.sys.shipyards.contractFinished.subject', [], $messageLocale),
                __('game.messages.sys.shipyards.contractFinished.body', [
                    'type' => __('game.common.hulls.'.$contract->shipyard->type, [], $messageLocale),
                    'name' => $star->name." - ".$f->convertLatinToRoman($planet->orbital_index),
                    'construction' => $contract->amount." x ".$contract->blueprint->name
                ], $messageLocale)
            );
            Log::notice("TURN PROCESSING $turnSlug - contract deleted since it was finished.");
        } catch(Exception $e) {
            Log::error("TURN PROCESSING $turnSlug - failed to delete a finished contract: ".$e->getMessage());
        }
    }

    /**
     * @function create the ship
     * @param Player $player
     * @param array $shipData
     * @param string $turnSlug
     * @return Ship
     * @throws Exception
     */
    private function createShip(Player $player, array $shipData, string $turnSlug): Ship
    {
        $s = new ShipService;
        $ship = Ship::create([
            'game_id' => $shipData['game_id'],
            'player_id' => $shipData['player_id'],
            'fleet_id' => null,
            'shipyard_id' => $shipData['shipyard_id'],
            'hull_type' => $shipData['hull_type'],
            'name' => $s->randomShipName(),
            'class_name' => $shipData['class_name'],
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
        Log::notice("TURN PROCESSING $turnSlug - Empire $player->ticker has created a new ship, $ship->name");
        return $ship;
    }


    /**
     * @function process handling of the next ship in the construction contract:
     * pay resources if possible, send message and put on hold if not.
     * @param ConstructionContract $contract
     * @param Player $player
     * @param string $turnSlug
     * @throws Exception
     */
     private function prepareNextShip (ConstructionContract $contract, Player $player, string $turnSlug)
    {
        $f = new FormatApiResponseService;
        $m = new MessageService;
        $costs = $this->getContractCosts($contract);
        $paid = $this->getContractPayments($contract);

        // if all costs are paid, eject ship
        if ($this->verifyCostsArePaid($costs, $paid)) {
            $this->createShip($player, $contract->cached_ship, $turnSlug);
            // since the ship was created, decrement amount_left.
            $contract->amount_left -= 1;
            // reset the amount paid for the next ship-
            $contract->paid_energy = $paid["energy"] = 0;
            $contract->paid_minerals = $paid["minerals"] = 0;
            $contract->paid_population = $paid["population"] = 0;
            $contract->save();
        }

        // procced with payment for the next ship in the batch.
        $paid = $this->payNextShipCosts($contract, $costs, $paid, $turnSlug);

        // check if all costs have been paid
        if ($this->verifyCostsArePaid($costs, $paid)) {
            // all costs where paid. reset "notified", change turns_left for the next ship in the batch.
            Log::notice("TURN PROCESSING $turnSlug - Empire $player->ticker has paid all costs for the next ship.");
            $contract->notified = false;
            $contract->turns_left = $contract->turns_per_ship;
        } else {
            // some costs have not been paid ^.^
            Log::notice("TURN PROCESSING $turnSlug - Empire $player->ticker could not pay all of the costs for the next ship in the contract. missing:\n".json_encode(array_diff($costs, $paid), JSON_PRETTY_PRINT));
            if (!$contract->notified) { // notify the user _once_
                $messageLocale = $this->getContractLocale($contract);
                $planet = $contract->shipyard->planet;
                $star = $planet->star;
                $m->sendSystemMessage(
                    $contract->game_id,
                    [$player->id],
                    __('game.messages.sys.shipyards.costsNotPaid.subject', [], $messageLocale),
                    __('game.messages.sys.shipyards.costsNotPaid.body', [
                        'type' => __('game.common.hulls.'.$contract->shipyard->type, [], $messageLocale),
                        'name' => $star->name." - ".$f->convertLatinToRoman($planet->orbital_index),
                        'shipclass' => $contract->blueprint->name,
                        'missing' => json_encode(array_diff($costs, $paid), JSON_PRETTY_PRINT)
                    ], $messageLocale)
                );
                $contract->notified = true;
            }
        }

        // update the contract with what was paid.
        $contract->paid_energy = $paid["energy"];
        $contract->paid_minerals = $paid["minerals"];
        $contract->paid_population = $paid["population"];
        // save contract.
        $contract->save();
    }

    /**
     * @function create ship from construction contract
     * @param Collection $contracts
     * @param string $turnSlug
     * @throws Exception
     */
    private function ejectShips(Collection $contracts, string $turnSlug)
    {
        foreach($contracts as $contract) {
            $player = $contract->player;
            // is the contract finished?
            if ($contract->amount_left === 1) {
                $this->createShip($player, $contract->cached_ship, $turnSlug);
                $this->contractFinished($contract, $turnSlug);
            } else {
                // no, proceed with next ship in the contract.
                $this->prepareNextShip($contract, $player, $turnSlug);
            }
        }
    }


    /**
     * @function build ships
     * @param Game $game
     * @param string $turnSlug
     * @throws Exception
     * @return void
     */
    public function handle(Game $game, string $turnSlug)
    {
        // decrement 'turns_left'
        $decrementedContracts = ConstructionContract::where('game_id', $game->id)
            ->where('turns_left', '>', '0')
            ->decrement('turns_left');
        if ($decrementedContracts) {
            Log::notice("TURN PROCESSING $turnSlug - Decreased 'turns_left' for $decrementedContracts contracts.");
        } else {
            Log::notice("TURN PROCESSING $turnSlug - No construction contracts where turns_left was decremented.");
        }

        // check if any ships are ready to be ejected.
        $contractsWithShipsReady = ConstructionContract::where('game_id', $game->id)
            ->where('turns_left', '=', '0')
            ->get();
        if(count($contractsWithShipsReady) > 0) {
            $num = count($contractsWithShipsReady);
            Log::notice("TURN PROCESSING $turnSlug - Trying to eject $num ships.");
            $this->ejectShips($contractsWithShipsReady, $turnSlug);
        } else {
            Log::notice("TURN PROCESSING $turnSlug - No ships are ready to be ejected.");
        }

        // all done.
        Log::info("TURN PROCESSING $turnSlug - done processing ship construction / construction contract handling.");
    }

}
