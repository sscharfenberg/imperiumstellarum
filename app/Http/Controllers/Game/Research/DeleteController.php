<?php

namespace App\Http\Controllers\Game\Research;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Research;
use App\Services\FormatApiResponseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\Game\UsesResearchVerification;

class DeleteController extends Controller
{

    use UsesResearchVerification;

    /**
     * @function create a new blueprint from xhr request
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function handle(Request $request): JsonResponse
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
            }),
            'message' => __('game.research.researchJobDeleted')
        ]);
    }

}
