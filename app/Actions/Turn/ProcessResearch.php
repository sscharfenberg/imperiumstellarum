<?php

namespace App\Actions\Turn;

use App\Http\Traits\Game\UsesTotalPopulation;
use App\Models\Game;
use App\Models\Player;
use App\Models\Research;
use App\Services\ResourceService;
use Illuminate\Support\Facades\Log;


class ProcessResearch
{

    use UsesTotalPopulation;

    /**
     * @function re-order research jobs by decrementing them by one.
     * @param Player $player
     * @param Game $game
     * @param string $turnSlug
     */
    private function reOrderResearchJobs (Player $player, Game $game, string $turnSlug)
    {
        $researchJobs = Research::where('game_id', '=', $game->id)
            ->where('player_id', '=', $player->id)
            ->decrement('order');
        Log::channel('turn')
            ->info("$turnSlug - re-ordered $researchJobs research jobs from Empire $player->ticker by decrementing by one.");
    }

    /**
     * @function process research
     * @param Game $game
     * @param string $turnSlug
     * @return void
     */
    public function handle(Game $game, string $turnSlug)
    {
        $researches = $game->researches->where('order', 1);
        $r = new ResourceService;

        // loop all researchable jobs
        foreach($researches as $job) {
            $player = $job->player;
            $priority = $player->research_priority;
            $population = $this->getTotalPopulation($player);
            $research = ceil($priority * $population);
            $costs = ['research' => $research];
            if ($r->playerCanAfford($player, $costs)) {
                // subtract resources
                $r->subtractResources($player, $costs);
                // update job
                $job->work += $research;
                $job->remaining -= $research;
                if ($job->remaining <= 0) {
                    // increase tech level by one.
                    $techLevel = $player->techLevels->where('type', $job->type)->first();
                    $techLevel->level += 1;
                    $techLevel->save();
                    // delete the research job
                    try {
                        $job->delete();
                        Log::channel('turn')->info("$turnSlug - Empire $player->ticker has increased the $job->type TL to $techLevel->level");
                        $this->reOrderResearchJobs($job->player, $game, $turnSlug);
                    } catch(\Exception $e) {
                        Log::channel('turn')->error("$turnSlug - Exception while attempting to delete a finished research job:\n". $e->getMessage());
                    }
                } else {
                    $job->save();
                    Log::channel('turn')
                        ->info(
                            "$turnSlug - Empire $player->ticker research for $job->type TL$job->level "
                            ."invested $research research points, $job->remaining remaining."
                        );
                }
            } else {
                Log::channel('turn')->info("$turnSlug - Empire $player->ticker can\'t afford the assigned research, skipping.");
            }
        }

        Log::channel('turn')->notice("$turnSlug - Looped all players for research processing.");

    }

}
