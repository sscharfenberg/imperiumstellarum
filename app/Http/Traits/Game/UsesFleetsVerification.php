<?php

namespace App\Http\Traits\Game;

use App\Models\Game;
use App\Models\Player;
use App\Models\Fleet;
use App\Models\Ship;
use App\Models\Shipyard;
use App\Models\Star;

trait UsesFleetsVerification
{

    /**
     * @function verify the player owns the star with the supplied id
     * @param Player $player
     * @param string $starId
     * @return bool
     */
    public function playerOwnsStar (Player $player, string $starId): bool
    {
        return $player->stars->containsStrict('id', $starId);
    }

    /**
     * @function validate fleet name
     * @param string $name
     * @return bool
     */
    public function isFleetNameValid (string $name): bool
    {
        return is_string($name)
            && strlen($name) >= config('rules.fleets.name.min')
            && strlen($name) <= config('rules.fleets.name.max');
    }

    /**
     * @function validate ship name
     * @param string $name
     * @return bool
     */
    public function isShipNameValid (string $name): bool
    {
        return is_string($name)
            && strlen($name) >= config('rules.ships.name.min')
            && strlen($name) <= config('rules.ships.name.max');
    }

    /**
     * @function verify the player owns the fleet with the supplied id
     * @param Player $player
     * @param string $fleetId
     * @return bool
     */
    public function playerOwnsFleet (Player $player, string $fleetId): bool
    {
        return $player->fleets->containsStrict('id', $fleetId);
    }

    /**
     * @function verify the player owns the ship with the supplied id
     * @param Player $player
     * @param string $shipId
     * @return bool
     */
    public function playerOwnsShip (Player $player, string $shipId): bool
    {
        return $player->ships->containsStrict('id', $shipId);
    }

    /**
     * @function verify that the fleet has no ships assigned.
     * @param Fleet $fleet
     * @return bool
     */
    public function isFleetEmpty(Fleet $fleet): bool
    {
        return count($fleet->ships) === 0;
    }

    /**
     * @function verify the fleet name is unique.
     * @param Player $player
     * @param string $name
     * @return bool
     */
    public function isFleetNameUnique(Player $player, string $name): bool
    {
        $fleets = $player->fleets;
        return !$fleets->containsStrict('name', $name);
    }

    /**
     * @function verify both source and target holder (fleet or shipyard) belong to the player
     * and share the same location (star_id)
     * @param Player $player
     * @param string $sourceId
     * @param string $targetId
     * @param string $sourceType
     * @param string $targetType
     * @return bool
     */
    public function holdersBelongToPlayer(
        Player $player,
        string $sourceId,
        string $targetId,
        string $sourceType,
        string $targetType
    ): bool
    {
        $source = null;
        $target = null;
        $sourceStarId = null;
        $targetStarId = null;
        if ($sourceType === 'fleet') {
            $source = Fleet::find($sourceId);
            $sourceStarId = $source->star_id;
        } else if ($sourceType === 'shipyard') {
            $source = Shipyard::find($sourceId);
            $sourceStarId = $source->planet->star_id;
        }
        if ($targetType === 'fleet') {
            $target = Fleet::find($targetId);
            $targetStarId = $target->star_id;
        } else if ($targetType === 'shipyard') {
            $target = Shipyard::find($targetId);
            $targetStarId = $target->planet->star_id;
        }
        return
            $source &&
            $target &&
            $source->player_id === $player->id &&
            $target->player_id === $player->id &&
            $sourceStarId &&
            $targetStarId &&
            $sourceStarId === $targetStarId;
    }

    /**
     * @function verify that the supplied shipIds are unique
     * @param array $source
     * @param array $target
     * @return bool
     */
    public function areShipIdsUnique(array $source, array $target): bool
    {
        $ids = array_merge($source, $target);
        // count of ids and count of unique ids needs to be the same.
        return count($ids) === count(array_unique($ids));
    }

    /**
     * @function verify that all supplied shipIds are owned by the player
     * @param Player $player
     * @param array $ids
     * @return bool
     */
    public function playerOwnsShips(Player $player, array $ids): bool
    {
        return count($ids) === count($player->ships->whereIn('id', $ids));
    }

    /**
     * @function verify that all shipIds belong to one of the holders (prevent cross-transferring)
     * @param array $holderIds
     * @param array $shipIds
     * @return bool
     */
    public function shipsBelongToHolders(array $holderIds, array $shipIds): bool
    {
        $ships = Ship::whereIn('id', $shipIds)->get()->map(function($ship) {
            return [
                'fleet_id' => $ship->fleet_id,
                'shipyard_id' => $ship->shipyard_id
            ];
        });
        foreach($ships as $ship) {
            if (!in_array($ship['fleet_id'], $holderIds) && !in_array($ship['shipyard_id'], $holderIds)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @function verify that the supplied coordinates are valid
     * @param Game $game
     * @param int $x
     * @param int $y
     * @return bool
     */
    public function coordsValid(Game $game, int $x, int $y): bool
    {
        return $game &&
            $x < $game->dimensions &&
            $y < $game->dimensions;
    }

    /**
     * @function ensure that start and end system are not identical
     * @param Star $start
     * @param Star $end
     * @return bool
     */
    public function startNotEqualsEnd(Star $start, Star $end): bool
    {
        return $start->id !== $end->id;
    }

    /**
     * @function ensure the supplied ticker is valid
     * @param string $ticker
     * @return bool
     */
    public function tickerIsValid(string $ticker): bool
    {
        return is_string($ticker) &&
            strlen($ticker) >= config('rules.player.ticker.min') &&
            strlen($ticker) <= config('rules.player.ticker.max') &&
            $ticker === strtoupper($ticker);
    }

}
