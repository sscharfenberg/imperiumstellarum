<?php

namespace App\Actions\Turn\Encounter;

use App\Models\EncounterTurn;

use App\Services\EncounterService;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProcessEncounterEndCheck
{


    /**
     * @function handle what happens when attackers have won.
     * @param Collection $encounter
     * @param string $turnSlug
     */
    private function handleAttackersWon (Collection $encounter, string $turnSlug)
    {
        echo "============================== ATTACKERS WIN ==============================";
        Log::channel('encounter')
            ->notice(
                "\n\n============================== ATTACKERS WIN ==============================\n"
                ."$turnSlug #".$encounter['id']." ends with a attacker win.\n"
            );
        // TODO: change ownership, determine new owner of star.
        // TODO: send system notifications
    }


    /**
     * @function handle what happens when defenders have won.
     * @param Collection $encounter
     * @param string $turnSlug
     */
    private function handleDefendersWon (Collection $encounter, string $turnSlug)
    {
        echo "============================== DEFENDERS WON ==============================";
        Log::channel('encounter')
            ->notice(
                "\n\n============================== DEFENDERS WIN ==============================\n"
                ."$turnSlug #".$encounter['id']." ends with a defender win.\n"
            );
        // TODO: send system notifications
    }


    /**
     * @function handle what happens in case of a draw
     * @param Collection $encounter
     * @param string $turnSlug
     */
    private function handleDraw (Collection $encounter, string $turnSlug)
    {
        echo "============================== DRAW ==============================";
        Log::channel('encounter')
            ->notice(
                "\n\n============================== DRAW ==============================\n"
                ."$turnSlug #".$encounter['id']." ends in a draw.\n"
            );
        // TODO: attacker fleets jump back to own systems.
        // TODO: send system notifications
    }


    /**
     * @function handle cleanup step: remove dead ships/fleets, update target queues if needed.
     * @param Collection $encounter
     * @param string $turnSlug
     * @param EncounterTurn $encounterTurn
     * @return Collection
     */
    public function handle (Collection $encounter, string $turnSlug, EncounterTurn $encounterTurn): Collection
    {
        $e = new EncounterService;
        $turn = $encounterTurn->turn;
        Log::channel('encounter')
            ->info("$turnSlug #".$encounter['id']." TURN $turn STEP 5: check if encounter ends.");

        $attackers = $e->getAttackers($encounter);
        $defenders = $e->getDefenders($encounter);

        // no attackers left, defender won.
        if ($attackers->count() === 0) {
            $encounter['resolved'] = true;
            $this->handleDefendersWon($encounter, $turnSlug);
        }
        // no defenders left, attacker won.
        if ($defenders->count() === 0) {
            $encounter['resolved'] = true;
            $this->handleAttackersWon($encounter, $turnSlug);
        }
        // turn limit reached, draw.
        if ($turn >= config('rules.encounters.maxTurns')) {
            $encounter['resolved'] = true;
            $this->handleDraw($encounter, $turnSlug);
        }

        // return encounter
        return $encounter;
    }

}
