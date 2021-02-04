<?php

namespace App\Services;

use App\Models\Blueprint;
use App\Models\ConstructionContract;
use App\Models\Fleet;
use App\Models\FleetMovement;
use App\Models\Message;
use App\Models\MessageSent;
use App\Models\Player;
use App\Models\PlayerRelationChange;
use App\Models\Research;
use App\Models\Ship;
use App\Models\Star;
use App\Models\Planet;
use App\Models\StorageUpgrade;
use App\Models\PlayerResource;
use App\Models\Harvester;
use App\Models\Shipyard;
use App\Models\TechLevel;

class FormatApiResponseService {

    /**
     * @function convert latin number to roman number string
     * @param int $latin
     * @return string
     */
    public function convertLatinToRoman (int $latin): string
    {
        $map = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
        $returnValue = '';
        $number = intval($latin);
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    /**
     * @function format api response for a Star
     * @param Star $star
     * @return array
     */
    public function formatStar (Star $star): array
    {
        return [
            'id' => $star->id,
            'ownerId' => $star->player_id ?? "",
            'x' => $star->coord_x,
            'y' => $star->coord_y,
            'spectral' => $star->spectral,
            'name' => $star->name
        ];
    }

    /**
     * @function format api response for a Destination star system including travel time
     * @param Star $star
     * @param int $travelTime
     * @return array
     */
    public function formatDestinationStar (Star $star, int $travelTime): array
    {
        return [
            'id' => $star->id,
            'ownerId' => $star->player_id ?? "",
            'x' => $star->coord_x,
            'y' => $star->coord_y,
            'spectral' => $star->spectral,
            'name' => $star->name,
            'travelTime' => $travelTime
        ];
    }


    /**
     * @function format api response for a Planet
     * @param Planet $planet
     * @return array
     */
    public function formatPlanet (Planet $planet): array
    {
        return [
            'id' => $planet->id,
            'starId' => $planet->star_id,
            'orbitalIndex' => $planet->orbital_index,
            'type' => $planet->type,
            'population' => $planet->population,
            'foodConsumption' => $planet->food_consumption,
            'resources' => $planet->resources
        ];
    }

    /**
     * @function format api response for a StorageUpgrade
     * @param StorageUpgrade $upgrade
     * @return array
     */
    public function formatStorageUpgrades (StorageUpgrade $upgrade): array
    {
        return [
            'resourceType' => $upgrade->resource_type,
            'newLevel' => $upgrade->new_level,
            'untilComplete' => $upgrade->until_complete
        ];
    }

    /**
     * @function format api response for a PlayerResource
     * @param PlayerResource $res
     * @return array
     */
    public function formatPlayerResource (PlayerResource $res): array
    {
        return [
            'type' => $res->resource_type,
            'amount' => $res->storage,
            'max' => config(
                'rules.player.resourceTypes.'.$res->resource_type.'.'.$res->storage_level.'.amount'
            ),
            'level' => $res->storage_level,
            'maxLevel' => array_key_last(config('rules.player.resourceTypes.'.$res->resource_type))
        ];
    }

    /**
     * @function format api response for a harvester
     * @param Harvester $harvester
     * @return array
     */
    public function formatHarvester (Harvester $harvester): array
    {
        return [
            'id' => $harvester->id,
            'planetId' => $harvester->planet_id,
            'resourceType' => $harvester->resource_type,
            'production' => $harvester->production,
            'untilComplete' => $harvester->until_complete
        ];
    }

    /**
     * @function format api response for a shipyard
     * @param Shipyard $shipyard
     * @return array
     */
    public function formatShipyard (Shipyard $shipyard): array
    {
        $planet = Planet::find($shipyard->planet_id);
        $star = Star::find($planet->star_id);
        return [
            'id' => $shipyard->id,
            'planetId' => $shipyard->planet_id,
            'starId' => $planet->star_id,
            'planetName' => $star->name." - ".$this->convertLatinToRoman($planet->orbital_index),
            'type' => $shipyard->type,
            'untilComplete' => $shipyard->until_complete,
            'population' => $planet->population
        ];
    }

    /**
     * @function format api response for a techLevel
     * @param TechLevel $techLevel
     * @return array
     */
    public function formatTechLevel (TechLevel $techLevel): array
    {
        return [
            'id' => $techLevel->id,
            'type' => $techLevel->type,
            'level' => $techLevel->level
        ];
    }

    /**
     * @function format api response for a research job
     * @param Research $research
     * @return array
     */
    public function formatResearchJob (Research $research): array
    {
        return [
            'id' => $research->id,
            'techLevelId' => $research->tech_level_id,
            'type' => $research->type,
            'level' => $research->level,
            'remaining' => $research->remaining,
            'work' => $research->work,
            'order' => $research->order
        ];
    }

    /**
     * @function format api response for a player (that isn't the current player)
     * @param Player $player
     * @return array
     */
    public function formatPlayer (Player $player): array
    {
        return [
            'id' => $player->id,
            'ticker' => $player->ticker,
            'name' => $player->name,
            'colour' => $player->colour
        ];
    }

    /**
     * @function format api response for a blueprint
     * @param Blueprint $blueprint
     * @return array
     */
    public function formatBlueprint (Blueprint $blueprint): array
    {
        $tls = [];
        foreach($blueprint->techLevels as $tl) {
            $tls[$tl->type] = $tl->level; // this is shorter than { type: laser, level: 3 }
        }
        return [
            'id' => $blueprint->id,
            'hullType' => $blueprint->hull_type,
            'modules' => explode("  ", $blueprint->modules),
            'techLevels' => $tls,
            'name' => $blueprint->name
        ];
    }

    /**
     * @function format api response for a construction contract
     * @param ConstructionContract $contract
     * @return array
     */
    public function formatConstructionContract (ConstructionContract $contract): array
    {
        return [
            'id' => $contract->id,
            'blueprintId' => $contract->blueprint_id,
            'shipyardId' => $contract->shipyard_id,
            'hullType' => $contract->hull_type,
            'amount' => $contract->amount,
            'amountLeft' => $contract->amount_left,
            'turnsPerShip' => $contract->turns_per_ship,
            'turnsLeft' => $contract->turns_left,
            'costs' => [
                'minerals' => $contract->costs_minerals,
                'energy' => $contract->costs_energy,
            ]
        ];
    }

    /**
     * @function format api response for a fleet
     * @param Fleet $fleet
     * @return array
     */
    public function formatFleet (Fleet $fleet): array
    {
        $ftl = count($fleet->ships->where('ftl', false)) === 0
            && count($fleet->ships) > 0;
        return [
            'id' => $fleet->id,
            'playerId' => $fleet->player_id,
            'starId' => $fleet->star_id,
            'name' => $fleet->name,
            'ftl' => $ftl
        ];
    }

    /**
     * @function format api response for players fleet movements
     * @param FleetMovement $movement
     * @return array
     */
    public function formatFleetMovement (FleetMovement $movement): array
    {
        $destination = $movement->star;
        return [
            'id' => $movement->id,
            'fleetId' => $movement->fleet_id,
            'destinationId' => $movement->star_id,
            'untilArrival' => $movement->until_arrival,
            'x' => $destination->coord_x,
            'y' => $destination->coord_y,
            'name' => $destination->name
        ];
    }

    /**
     * @function format api response for a ship
     * @param Ship $ship
     * @return array
     */
    public function formatShip (Ship $ship): array
    {
        return [
            'id' => $ship->id,
            'playerId' => $ship->player_id,
            'fleetId' => $ship->fleet_id,
            'shipyardId' => $ship->shipyard_id,
            'hullType' => $ship->hull_type,
            'name' => $ship->name,
            'className' => $ship->class_name,
            'dmg' => [
                'plasma' => $ship->dmg_plasma,
                'railgun' => $ship->dmg_railgun,
                'missile' => $ship->dmg_missile,
                'laser' => $ship->dmg_laser,
            ],
            'hp' => [
                'structure' => [
                    'current' => $ship->hp_structure_current,
                    'max' => $ship->hp_structure_max
                ],
                'armour' => [
                    'current' => $ship->hp_armour_current,
                    'max' => $ship->hp_armour_max
                ],
                'shields' => [
                    'current' => $ship->hp_shields_current,
                    'max' => $ship->hp_shields_max
                ]
            ],
            'ftl' => $ship->ftl,
            'colony' => $ship->colony,
            'acceleration' => $ship->acceleration
        ];
    }

    /**
     * @function format api response for a player relation
     * this function only serves as a central formatting function and contains no logic.
     * @param string $playerId
     * @param int $set
     * @param int $recipientSet
     * @param int $effective
     * @return array
     */
    public function formatPlayerRelation (string $playerId, int $set, int $recipientSet, int $effective): array
    {
        return [
            'playerId' => $playerId,
            'set' => $set,
            'recipientSet' => $recipientSet,
            'effective' => $effective
        ];
    }

    /**
     * @function format api response for a player relation change
     * @param PlayerRelationChange $relationChange
     * @return array
     */
    public function formatPlayerRelationChange (PlayerRelationChange $relationChange): array
    {
        return [
            'playerId' => $relationChange->recipient_id,
            'set' => $relationChange->status,
            'untilDone' => $relationChange->until_done
        ];
    }

    /**
     * @function format api respone for a message (inbox)
     * @param Message $message
     * @return array
     */
    public function formatMessage (Message $message): array
    {
        return [
            'id' => $message->id,
            'senderId' => $message->sender_id,
            'repliesToId' => $message->message_id,
            'subject' => $message->subject,
            'body' => $message->body,
            'read' => $message->read
        ];
    }

    /**
     * @function format api respone for a sent message (outbox)
     * @param MessageSent $message
     * @return array
     */
    public function formatMessageSent (MessageSent $message): array
    {
        return [
            'id' => $message->id,
            'recipientId' => $message->sender_id,
            'repliesToId' => $message->message_id,
            'subject' => $message->subject,
            'body' => $message->body,
        ];
    }

}
