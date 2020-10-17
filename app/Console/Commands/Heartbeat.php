<?php

namespace App\Console\Commands;

use App\Models\Game;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
     */
    public function handle()
    {
        Log::info('HEARTBEAT: startup.');
        $game = Game::where('active',true)->get();
        Log::info('HEARTBEAT: found '.count($game).' games to check.');
        return 0;
    }
}
