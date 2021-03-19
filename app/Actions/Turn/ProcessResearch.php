<?php

namespace App\Actions\Turn;

use App\Http\Traits\Game\UsesTotalPopulation;
use App\Models\Game;
use App\Services\ResourceService;
use Illuminate\Support\Facades\Log;


class ProcessResearch
{

    use UsesTotalPopulation;

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
                        Log::channel('turn')->error("$turnSlug - Empire $player->ticker has increased the $job->type TL to $techLevel->level");
                    } catch(\Exception $e) {
                        Log::channel('turn')->error("$turnSlug - Exception while attempting to delete a finished research job:\n". $e->getMessage());
                    }
                } else {
                    $job->save();
                }
            } else {
                Log::channel('turn')->info("$turnSlug - Empire $player->ticker can\'t afford the assigned research, skipping.");
            }
        }

        Log::channel('turn')->notice("$turnSlug - Looped all players for research processing.");

    }

}
