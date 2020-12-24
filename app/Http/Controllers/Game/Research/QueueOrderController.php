<?php

namespace App\Http\Controllers\Game\Research;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Research;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\Game\UsesResearchVerification;

class QueueOrderController extends Controller
{

    use UsesResearchVerification;

    /**
     * @function handle enqueue research job request
     * @param Request $request
     * @return JsonResponse
     */
    public function handle(Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $newOrder = $request->input();

        // verification
        if (!$this->verifyResearchQueueMax($newOrder)) {
            return response()
                ->json(['error' => __('game.research.errors.order.max')], 419);
        }
        if (!$this->researchJobsArePlayerOwned($newOrder, $player)) {
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
