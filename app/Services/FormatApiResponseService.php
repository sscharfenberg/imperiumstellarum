<?php

namespace App\Services;

use App\Models\Blueprint;
use App\Models\ConstructionContract;
use App\Models\Encounter;
use App\Models\EncounterTurn;
use App\Models\Fleet;
use App\Models\FleetMovement;
use App\Models\Message;
use App\Models\MessageReport;
use App\Models\Player;
use App\Models\PlayerRelationChange;
use App\Models\Raid;
use App\Models\Research;
use App\Models\Ship;
use App\Models\Star;
use App\Models\Planet;
use App\Models\StorageUpgrade;
use App\Models\PlayerResource;
use App\Models\Harvester;
use App\Models\Shipyard;
use App\Models\TechLevel;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

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
        $f = new FleetService;
        return [
            'id' => $shipyard->id,
            'planetId' => $shipyard->planet_id,
            'starId' => $planet->star_id,
            'planetName' => $star->name." - ".$this->convertLatinToRoman($planet->orbital_index),
            'type' => $shipyard->type,
            'untilComplete' => $shipyard->until_complete,
            'population' => $planet->population,
            'preferredRange' => $f->getFleetPreferredRange($shipyard->ships->toArray())
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
     * @param bool $withLocale
     * @return array
     */
    public function formatPlayer (Player $player, bool $withLocale = false): array
    {
        $formattedPlayer = [
            'id' => $player->id,
            'ticker' => $player->ticker,
            'name' => $player->name,
            'colour' => $player->colour,
            'dead' => $player->dead
        ];
        if ($withLocale) $formattedPlayer['locale'] = $player->user->locale;
        return $formattedPlayer;
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
                'population' => $contract->costs_population
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
        $f = new FleetService;
        return [
            'id' => $fleet->id,
            'playerId' => $fleet->player_id,
            'starId' => $fleet->star_id,
            'name' => $fleet->name,
            'ftl' => $ftl,
            'preferredRange' => $f->getFleetPreferredRange($fleet->ships->toArray())
        ];
    }

    /**
     * @function format api response for a foreign fleet.
     * The response needs to be different since the amount of information needs to be different (ftl, preferredRange)
     * @param Fleet $fleet
     * @param Player $player
     * @param Collection $gameRelations
     * @return array
     */
    public function formatForeignFleet (Player $player, Fleet $fleet, Collection $gameRelations): array
    {
        $p = new PlayerRelationService;
        $star = $fleet->star;
        $foreignPlayer = $fleet->player;
        $ships = $fleet->ships;
        return [
            'id' => $fleet->id,
            'playerId' => $fleet->player_id,
            'playerTicker' => $foreignPlayer->ticker,
            'playerColour' => $foreignPlayer->colour,
            'starId' => $fleet->star_id,
            'name' => $fleet->name,
            'starName' => $star->name,
            'coordX' => $star->coord_x,
            'coordY' => $star->coord_y,
            'playerRelation' => $p->getEffectiveRelation($player, $foreignPlayer, $gameRelations),
            'numShips' => [
                'ark' => $ships->filter(function($ship) { return $ship->hull_type === 'ark'; })->count(),
                'small' => $ships->filter(function($ship) { return $ship->hull_type === 'small'; })->count(),
                'medium' => $ships->filter(function($ship) { return $ship->hull_type === 'medium'; })->count(),
                'large' => $ships->filter(function($ship) { return $ship->hull_type === 'large'; })->count(),
                'xlarge' => $ships->filter(function($ship) { return $ship->hull_type === 'xlarge'; })->count()
            ]
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
     * @param string $playerId
     * @return array
     */
    public function formatInboxMessage (Message $message, string $playerId): array
    {
        // absolute=false does not work for diffInSeconds on php7.3
        // https://github.com/briannesbitt/Carbon/issues/1503
        // so, we'll work around this for now.
        $createdAt = $message->created_at->diffInMilliseconds(Carbon::now());
        if (now() > $message->created_at) {
            $createdAt = -$createdAt;
        }
        // get read status from recipient
        $recipient = $message->recipients->where('message_id', '=', $message->id)
            ->where('game_id', '=', $message->game_id)
            ->where('recipient_id', '=', $playerId)
            ->first();
        return [
            'id' => $message->id,
            'senderId' => $message->sender_id,
            'repliesToId' => $message->message_id,
            'recipientIds' => $message->recipients->map(function($recipient) {
                return $recipient->recipient_id;
            })->toArray(),
            'subject' => $message->subject,
            'body' => $message->body,
            'read' => $recipient->read ? true : false,
            'timestamp' => $createdAt
        ];
    }

    /**
     * @function format api respone for a sent message (outbox)
     * @param Message $message
     * @return array
     */
    public function formatOutboxMessage (Message $message): array
    {
        // absolute=false does not work for diffInSeconds on php7.3
        // https://github.com/briannesbitt/Carbon/issues/1503
        // so, we'll work around this for now.
        $createdAt = $message->created_at->diffInMilliseconds(Carbon::now());
        if (now() > $message->created_at) {
            $createdAt = -$createdAt;
        }
        return [
            'id' => $message->id,
            'senderId' => $message->sender_id,
            'repliesToId' => $message->message_id,
            'recipientIds' => $message->recipients->map(function($recipient) {
                return $recipient->recipient_id;
            })->toArray(),
            'subject' => $message->subject,
            'body' => $message->body,
            'timestamp' => $createdAt
        ];
    }

    /**
     * @function format api reponse for a message report
     * @param MessageReport $report
     * @return array
     */
    public function formatMessageReport (MessageReport $report): array
    {
        return [
            'id' => $report->id,
            'messageId' => $report->message_id,
            'comment' => $report->comment,
            'resolved' => !!$report->resolved_admin,
            'adminMsgId' => $report->admin_reporter_message_id
        ];
    }

    /**
     * @function format api response for a player's encounter
     * @param Encounter $encounter
     * @param Player $player
     * @return array
     */
    public function formatPlayerEncounter (Encounter $encounter, Player $player): array
    {
        $participant = $encounter->participants->filter( function($participant) use ($player) {
            return $participant->player_id === $player->id;
        })->first();
        return [
            'id' => $encounter->id,
            'turn' => ($encounter->gameTurn->number + 1),
            'starId' => $encounter->star_id,
            'ownerId' => $encounter->original_owner_id,
            'starName' => $encounter->original_name,
            'participantIds' => $encounter->participants->map( function ($participant) {
                return $participant->player_id;
            }),
            'read' => !!$participant->read
        ];
    }

    /**
     * @function format api response for an encounter turn
     * @param EncounterTurn $turn
     * @return array
     *
     */
    public function formatEncounterTurn (EncounterTurn $turn): array
    {
        return [
            'turn' => $turn->turn,
            'attacker' => $turn->attacker,
            'defender' => $turn->defender,
            'damage' => $turn->damage
        ];
    }

    /**
     * @function format api response for the encounter details, including turns
     * @param Encounter $encounter
     * @param Player $player
     * @return array
     */
    public function formatEncounterDetails (Encounter $encounter, Player $player): array
    {
        $participant = $encounter->participants->filter( function($participant) use ($player) {
            return $participant->player_id === $player->id;
        })->first();
        return [
            'id' => $encounter->id,
            'turn' => $encounter->gameTurn->number,
            'starId' => $encounter->star_id,
            'ownerId' => $encounter->original_owner_id,
            'starName' => $encounter->original_name,
            'participantIds' => $encounter->participants->map( function ($participant) {
                return $participant->player_id;
            }),
            'read' => !!$participant->read,
            'turns' => $encounter->encounterTurns->map(function ($turn) {
                return $this->formatEncounterTurn($turn);
            })
        ];
    }

    /**
     * @function format api response for a raid
     * @param Raid $raid
     * @param Player $player
     * @return array
     */
    public function formatPlayerRaid (Raid $raid, Player $player): array
    {
        $raidPlayer = $raid->players->filter (function($raidPlayer) use ($player) {
            return $raidPlayer->player_id === $player->id;
        })->first();
        return [
            'id' => $raid->id,
            'startTurn' => $raid->start_turn,
            'endTurn' => $raid->end_turn,
            'starId' => $raid->star_id,
            'starOwnerId' => $raid->star->owner->id,
            'raider' => $raidPlayer->raider,
            'players' => $raid->players->map( function ($raidPlayer) {
                return [
                    'playerId' => $raidPlayer->player_id,
                    'energy' => $raidPlayer->energy,
                    'minerals' => $raidPlayer->minerals,
                    'food' => $raidPlayer->food,
                    'research' => $raidPlayer->research,
                    'ark' => $raidPlayer->ark,
                    'small' => $raidPlayer->small,
                    'medium' => $raidPlayer->medium,
                    'large' => $raidPlayer->large,
                    'xlarge' => $raidPlayer->xlarge,
                ];
            })
        ];
    }

}
