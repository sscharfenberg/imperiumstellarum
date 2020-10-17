<?php

namespace App\Console\Commands;

use App\Models\Game;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Actions\Game\StartGame;

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
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
        Log::notice('HEARTBEAT: startup.');

        // only check games that are active and not processing
        $games = Game::where('active',true)->where('processing', false)->get();
        Log::notice('HEARTBEAT: found '.count($games).' games to check.');

        foreach($games as $game) {
            Log::notice('HEARTBEAT: checking g'.$game->number);
            $dueTurn = $game->turns->whereNull('processed')->where('due', '<=', now())->first();

            // check if game needs to be started
            if (now() > $game->start_date && count($game->turns) === 0) {
                Log::notice('HEARTBEAT: starting game g'.$game->number);
                $g = new StartGame;
                $g->handle($game);
            }

            // check if we need to process a turn.
            elseif (count($game->turns) > 0 && $dueTurn) {
                Log::info('HEARTBEAT: processing turn for g'.$game->number);
            }

        }
        return 0;
    }
}
