<?php

namespace App\Actions\Turn\Encounter;

use App\Models\Encounter;
use App\Models\EncounterParticipant;
use App\Models\Turn;

use App\Services\EncounterService;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Exception;
use Ramsey\Uuid\Uuid;

class ProcessEncounter
{

    /**
     * @function create db entry for encounter
     * @param Collection $encounter
     * @return void
     */
    private function createDbEncounter (Collection $encounter)
    {
        // find the current turn
        $turnId = Turn::where('game_id', '=', $encounter['game_id'])
            ->whereNull('processed')
            ->first()
            ->id;
        // find all unique players involved in this encounter
        $participantIds = $encounter['fleets']->map(function ($fleet) {
            return $fleet['player_id'];
        })->concat([$encounter['star']['owner']['id']])
            ->unique();
        $participants = $participantIds->map(function($participantId) use ($encounter) {
            return [
                'id' => Uuid::uuid4(),
                'game_id' => $encounter['game_id'],
                'encounter_id' => $encounter['id'],
                'player_id' => $participantId,
                'created_at' => now(),
                'updated_at' => now()
            ];
        })->toArray();

        // create the encounter entry
        Encounter::create([
            'id' => $encounter['id'],
            'game_id' => $encounter['game_id'],
            'turn_id' => $turnId,
            'star_id' => $encounter['star']['id']
        ]);

        // create the encounter participants
        EncounterParticipant::insert($participants);
    }

    /**
     * @function main function for processing encounters
     * @param Collection $encounter
     * @param string $turnSlug
     */
    private function processEncounter (Collection $encounter, string $turnSlug)
    {
        $turn = 0;
        $t = new \App\Actions\Turn\Encounter\PersistTurn;
        $t->handle($encounter, $turnSlug, $turn);

        // main turn loop
        while(!$encounter['resolved']) {
            $turn++;
            Log::channel('encounter')
                ->notice("\n\n$turnSlug #".$encounter['id']." start processing turn $turn.\n");

            echo "\nTurn $turn\n\n";

            // 1) determine turn order
            $o = new \App\Actions\Turn\Encounter\ProcessEncounterTurnOrder;
            $encounter = $o->handle($encounter, $turnSlug, $turn);

            // 2) Move Fleets
            $m = new \App\Actions\Turn\Encounter\ProcessEncounterMovement;
            $encounter = $m->handle($encounter, $turnSlug, $turn);

            // 3) Process Damage
            $d = new \App\Actions\Turn\Encounter\ProcessEncounterDamage;
            $encounter = $d->handle($encounter, $turnSlug, $turn);

            // 4) Cleanup: remove dead ships/fleets, update target queues if targets are dead.
            $k = new \App\Actions\Turn\Encounter\ProcessEncounterCleanup;
            $encounter = $k->handle($encounter, $turnSlug, $turn);

            // 5) check if encounter ends (no attacker or defender ships). this sets $encounter['resolved'] if needed.
            $e = new \App\Actions\Turn\Encounter\ProcessEncounterEndCheck;
            $encounter = $e->handle($encounter, $turnSlug, $turn);

            // 6) update encounter turn data in database
            $encounterTurn = $t->handle($encounter, $turnSlug, $turn);
            $encounter['turns']->push([
                $encounterTurn->turn => $encounterTurn->id
            ]);

            // 7) persist encounter if it is resolved.
            if ($encounter['resolved']) {
                $p = new \App\Actions\Turn\Encounter\PersistEncounter;
                $p->handle($encounter, $turnSlug, $encounterTurn);
            }
        }

        Log::channel('encounter')
            ->notice(
                "$turnSlug end processing of #".$encounter['id']." at ["
                .$encounter['star']['owner']->ticker."] "
                .$encounter['star']['name']
            );

        $encounter = null;

    }


    /**
     * @function find out if any encounters need to be processed and trigger processing.
     * @param Collection $encounter
     * @param string $turnSlug
     * @throws Exception
     * @return void
     */
    public function handle(Collection $encounter, string $turnSlug)
    {
        $e = new EncounterService;
        // tons of logging.
        Log::channel('encounter')
            ->notice(
                "$turnSlug start processing of #".$encounter['id']." at ["
                .$encounter['star']['owner']->ticker."] "
                .$encounter['star']['name']
            );
        Log::channel('encounter')
            ->debug("#".$encounter['id']." star: ".json_encode($encounter['star'], JSON_PRETTY_PRINT));
        Log::channel('encounter')
            ->info("#".$encounter['id']." ".count($e->getAttackers($encounter))." attacking fleets:");
        Log::channel('encounter')
            ->debug("#".$encounter['id']." attacker: ".json_encode($e->getAttackers($encounter), JSON_PRETTY_PRINT));
        Log::channel('encounter')
            ->info("#".$encounter['id']." ".count($e->getDefenders($encounter))." defending fleets:");
        Log::channel('encounter')
            ->debug("#".$encounter['id']." defender: ".json_encode($e->getDefenders($encounter), JSON_PRETTY_PRINT));

        // create db entry for encounter and participants
        $this->createDbEncounter($encounter);

        // start processing.
        $this->processEncounter($encounter, $turnSlug);
    }

}
