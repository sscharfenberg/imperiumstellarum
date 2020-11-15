<?php

namespace App\Http\Controllers\Game\Research;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Research;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QueueOrderController extends Controller
{

    /**
     * @function verify the number of research jobs is not > max
     * @param array $jobIds
     * @param Player $player
     * @return bool
     */
    private function verifyResearchQueueMax (array $jobIds)
    {
        return count($jobIds) <= config('rules.tech.queue');
    }

    /**
     * @function verify all research jobs exist and are owned by the player
     * @param array $jobIds
     * @param Player $player
     * @return bool
     */
    private function jobsArePlayerOwned (array $jobIds, Player $player)
    {
        $jobs = $player->researches;
        foreach($jobIds as $jobId) {
            if (!$jobs->containsStrict('id', $jobId)) return false;
        }
        return true;
    }

    /**
     * @function verify the research jobs are in the expected order.
     * @param array $jobIds
     * @param Player $player
     * @return bool
     */
    private function isSortOrderCorrect (array $jobIds, Player $player)
    {
        $tls = $player->techLevels;
        $jobs = $player->researches;
        // prepare array with all areas and the expected levels.
        $expectedLevels = array_flip(array_keys(config('rules.tech.areas')));
        foreach($expectedLevels as $key => $value) {
            $expectedLevels[$key] = $tls->where('type', $key)->first()->level + 1;
        }
        // loop research jobs in the order indicated by the client
        foreach($jobIds as $jobId) {
            $job = $jobs->find($jobId);
            // is the level unexpected?
            if ($expectedLevels[$job->type] !== $job->level) {
                return false;
            } else { // if the level is expected, increase by 1 for next check.
                $expectedLevels[$job->type] = $job->level + 1;
            }
        }
        return true;
    }


    /**
     * @function handle enqueue research job request
     * @param Request $request
     * @return JsonResponse
     */
    public function handle(Request $request)
    {
        $player = Player::find(Auth::user()->selected_player);
        $newOrder = $request->input();

        // verification
        if (!$this->verifyResearchQueueMax($newOrder)) {
            return response()
                ->json(['error' => __('game.research.errors.order.max')], 419);
        }
        if (!$this->jobsArePlayerOwned($newOrder, $player)) {
            return response()
                ->json(['error' => __('game.research.errors.order.owned')], 419);
        }
        if (!$this->isSortOrderCorrect($newOrder, $player)) {
            return response()
                ->json(['error' => __('game.research.errors.order.order')], 419);
        }

        // change actual sort order
        // TODO: make this more efficient, this is 5 database updates ¯\_(ツ)_/¯
        $i = 1;
        foreach ($newOrder as $job) {
            $job = Research::find($job);
            $job->order = $i;
            $job->save();
            $i++;
        }

        // return new sort order to client
        $updatedPlayer = Auth::user()->players->find(Auth::user()->selected_player);
        $f = new FormatApiResponseService;
        return response()->json([
            'researchJobs' => $updatedPlayer->researches->map(function ($research) use ($f) {
                return $f->formatResearchJob($research);
            })
        ]);
    }


}
