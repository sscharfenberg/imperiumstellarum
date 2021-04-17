<?php

namespace App\Actions\Turn\Encounter;

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
    private function handleAttackersWon (Collection $encounter, string $turnSlug): Collection
    {
        echo "============================== ATTACKERS WIN ==============================\n";
        Log::channel('encounter')
            ->notice(
                "\n\n============================== ATTACKERS WIN ==============================\n"
                ."$turnSlug #".$encounter['id']." ends with a attacker win.\n"
            );
        $encounter['winner'] = 'attacker';
        return $encounter;
    }


    /**
     * @function handle what happens when defenders have won.
     * @param Collection $encounter
     * @param string $turnSlug
     */
    private function handleDefendersWon (Collection $encounter, string $turnSlug): Collection
    {
        echo "============================== DEFENDERS WON ==============================\n";
        Log::channel('encounter')
            ->notice(
                "\n\n============================== DEFENDERS WIN ==============================\n"
                ."$turnSlug #".$encounter['id']." ends with a defender win.\n"
            );
        $encounter['winner'] = 'defender';
        return $encounter;
    }


    /**
     * @function handle what happens in case of a draw
     * @param Collection $encounter
     * @param string $turnSlug
     */
    private function handleDraw (Collection $encounter, string $turnSlug): Collection
    {
        echo "============================== DRAW ==============================\n";
        Log::channel('encounter')
            ->notice(
                "\n\n============================== DRAW ==============================\n"
                ."$turnSlug #".$encounter['id']." ends in a draw.\n"
            );
        $encounter['winner'] = 'draw';
        return $encounter;
    }


    /**
     * @function handle cleanup step: remove dead ships/fleets, update target queues if needed.
     * @param Collection $encounter
     * @param string $turnSlug
     * @param int $turn
     * @return Collection
     */
    public function handle (Collection $encounter, string $turnSlug, int $turn): Collection
    {
        $e = new EncounterService;
        Log::channel('encounter')
            ->info("$turnSlug #".$encounter['id']." TURN $turn STEP 5: check if encounter ends.");

        $attackers = $e->getAttackers($encounter);
        $attackerShips = 0;
        $attackers->each(function ($fleet) use (&$attackerShips) {
            $attackerShips += $fleet['ships']->count();
        });
        $defenders = $e->getDefenders($encounter);
        $defenderShips = 0;
        $defenders->each(function ($fleet) use (&$defenderShips) {
            $defenderShips += $fleet['ships']->count();;
        });

        // no attackers left, defender won.
        if ($attackers->count() === 0 || $attackerShips === 0) {
            $encounter['resolved'] = true;
            $encounter = $this->handleDefendersWon($encounter, $turnSlug);
        }
        // no defenders left, attacker won.
        if ($defenders->count() === 0 || $defenderShips === 0) {
            $encounter['resolved'] = true;
            $encounter = $this->handleAttackersWon($encounter, $turnSlug);
        }
        // turn limit reached, draw.
        if ($turn >= config('rules.encounters.maxTurns')) {
            $encounter['resolved'] = true;
            $encounter = $this->handleDraw($encounter, $turnSlug);
        }

        // return encounter
        return $encounter;
    }

}
