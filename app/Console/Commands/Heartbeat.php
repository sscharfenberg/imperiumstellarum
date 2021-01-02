<?php

namespace App\Console\Commands;

use App\Models\Game;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Actions\Game\StartGame;
use App\Actions\Game\ProcessTurn;

class Heartbeat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:heartbeat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron heartbeat of the game - check if actions need to be taken.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws \Exception
     * @return void
     */
    public function handle()
    {
        $start = hrtime(true);
        Log::info('HEARTBEAT: startup.');

        /**
         * #1 - start games
         */
        Log::info("HEARTBEAT: #1 Checking games if any need to be started.");
        $gamesToStart = Game::where('processing', false)
            ->where('finished', false)
            ->where('active', false)
            ->with('turns')
            ->get();
        foreach($gamesToStart as $game) {
            Log::notice('HEARTBEAT: checking g'.$game->number);
            // check if game needs to be started
            if (now() > $game->start_date && count($game->turns) === 0) {
                // skip if the game has no players.
                if (count($game->players) === 0) {
                    Log::notice("HEARTBEAT: Game g$game->number has no players, skipping.");
                    break;
                } else {
                    Log::notice('HEARTBEAT: starting game g'.$game->number);
                    $g = new StartGame;
                    $g->handle($game);
                }
            }
        }

        /**
         * #2 process turns
         */
        Log::info("HEARTBEAT: #2 Checking if any turns need to be processed.");
        $gamesToProcessTurns = Game::where('processing', false)
            ->where('finished', false)
            ->where('active',true)
            ->with('turns')
            ->get();
        foreach($gamesToProcessTurns as $game) {
            Log::notice('HEARTBEAT: checking g'.$game->number);
            $dueTurn = $game->turns
                ->whereNull('processed')
                ->where('due', '<=', now())->first();
            // check if we need to process a turn.
            if (count($game->turns) > 0 && $dueTurn) {
                Log::info('HEARTBEAT: g'.$game->number.'t'.$dueTurn->number.' needs to be processed.');
                $t = new ProcessTurn;
                $t->handle($game, $dueTurn);
            }
        }

        // log execution time of heartbeat process.
        $execution = hrtime(true) - $start;
        Log::info('HEARTBEAT finished in '.$execution/1e+9.' seconds.');

    }
}
