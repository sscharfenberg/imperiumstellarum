<?php

namespace App\Http\Controllers\Game\Research;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Research;
use App\Services\FormatApiResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DeleteController extends Controller
{


    /**
     * @function verify the research jobs exists and is owned by the player
     * @param string $jobId
     * @param Player $player
     * @return bool
     */
    private function jobIsPlayerOwned (string $jobId, Player $player)
    {
        return $player->researches->containsStrict('id', $jobId);
    }

    /**
     * @function make sure there is no lower level of the same area in queue
     * @param Research $job
     * @param Player $player
     * @return bool
     */
    private function hasNoHigherLevelEnqueued(Research $job, Player $player)
    {
        $queue = $player->researches->filter(function ($queueJob) use ($job) {
            return $queueJob->type == $job->type;
        });
        foreach ($queue as $queueJob) {
            if ($queueJob->level > $job->level) return false;
        }
        return true;
    }


    public function handle(Request $request)
    {
        $player = Player::find(Auth::user()->selected_player);
        $jobId = $request->input("id");
        $job = Research::find($jobId);

        // verification
        if(!$this->jobIsPlayerOwned($jobId, $player)) {
            return response()
                ->json(['error' => __('game.research.errors.delete.owned')], 419);
        }
        if(!$this->hasNoHigherLevelEnqueued($job, $player)) {
            return response()
                ->json(['error' => __('game.research.errors.delete.order')], 419);
        }

        // do delete
        try {
            $job->delete();
            Log::info("Empire $player->ticker has deleted research job $job->type TL $job->level");
        } catch (\Exception $e) {
            Log::error('Exception while attempting to delete a research job:\n'. $e->getMessage());
        }

        // send update research jobs to client
        $updatedPlayer = Player::find(Auth::user()->selected_player);
        $f = new FormatApiResponseService;
        return response()->json([
            'researchJobs' => $updatedPlayer->researches->map(function ($research) use ($f) {
                return $f->formatResearchJob($research);
            })
        ]);
    }

}
