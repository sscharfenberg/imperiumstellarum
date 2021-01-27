<?php
namespace App\Services;

use Illuminate\Support\Collection;

use App\Models\Player;

use App\Services\FormatApiResponseService;

class PlayerRelationService {


    /**
     * @function get two effective relations between two players.
     * @param Player $player1
     * @param Player $player2
     * @param Collection $gameRelations
     * @return int
     */
    public function getEffectiveRelation (Player $player1, Player $player2, Collection $gameRelations): int
    {
        $player1ToPlayer2 = $gameRelations->where('player_id', $player1->id)
            ->where('recipient_id', $player2->id)->first();
        $player2ToPlayer1 = $gameRelations->where('player_id', $player2->id)
            ->where('recipient_id', $player1->id)->first();

        // if there are no relations set from both players, return the default (1) early.
        if (!$player1ToPlayer2 && !$player2ToPlayer1) return 1;

        // status from 1to2 and 2to1. If it does not exist, use default (1)
        $player1Set = $player1ToPlayer2 ? $player1ToPlayer2->status : 1;
        $player2Set = $player2ToPlayer1 ? $player2ToPlayer1->status : 1;

        // base logic - use the lower relation
        return min($player1Set, $player2Set);
    }


    /**
     * @function format all relations of a player
     * @param string $playerId
     * @param Collection $gameRelations
     * @param Collection $players
     * @return array
     */
    public function formatAllPlayerRelations (string $playerId, Collection $gameRelations, Collection $players): array
    {
        $f = new FormatApiResponseService;
        $relations = collect();

        $playerRelations = $gameRelations->where('player_id', $playerId);
        $player = $players->where('id', $playerId)->first();

        // first, get all relations where the player has a relation to the recipient
        foreach($playerRelations as $relation) {
            $recipient = $players->where('id', $relation->recipient_id)->first();
            $effectiveRelation = $this->getEffectiveRelation($player, $recipient, $gameRelations);
            $relations->push($f->formatPlayerRelation(
                $relation->recipient_id, // playerId of recipient
                $relation->status, // what the player has set to the recipient
                $effectiveRelation // effective relation, taking into account what recipient has set to player.
            ));
        }

        // now, add the relations where the recipient has set a relation to the player
        $recipientRelations = $gameRelations->where('recipient_id', $playerId);
        foreach($recipientRelations as $relation) {
            $recipient = $players->where('id', $relation->player_id)->first();
            if (count($relations->where('playerId', $recipient->id)) === 0) {
                // this relation does not already exist, add it.
                $effectiveRelation = $this->getEffectiveRelation($player, $recipient, $gameRelations);
                $relations->push($f->formatPlayerRelation(
                    $relation->player_id, // playerId of recipient
                    9, // player has not set anything to the recipient, so we use 9 as a placeholder.
                    $effectiveRelation // effective relation, taking into account what recipient has set to player.
                ));
            }
        }

        return $relations->toArray();
    }


}
