<?php

namespace App\Http\Controllers\Game\Research;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Research;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\Game\UsesResearchVerification;

class EnqueueController extends Controller
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
        $type = $request->input('type');
        $level = $request->input('level');

        // verification
        if (!$this->researchAreaExists($type)) {
            return response()
                ->json(['error' => __('game.research.errors.enqueue.area')], 419);
        }
        if (!$this->isLevelWithinBounds($level)) {
            return response()
                ->json(['error' => __('game.research.errors.enqueue.bounds')], 419);
        }
        if (!$this->isJobResearchable($player, $type, $level)) {
            return response()
                ->json(['error' => __('game.research.errors.enqueue.alreadyResearched')], 419);
        }
        if (!$this->verifyPlayerQueueMax($player)) {
            return response()
                ->json(['error' => __('game.research.errors.enqueue.max')], 419);
        }

        // create research job
        $jobOrder = 1;
        if (count($player->researches) > 0) {
            $jobOrder = $player->researches->sortByDesc('order')->first()->order + 1;
        }
        $job = Research::create([
            'game_id' => $player->game->id,
            'player_id' => $player->id,
            'tech_level_id' => $player->techLevels->where('type', $type)->first()->id,
            'type' => $type,
            'level' => $level,
            'remaining' => config('rules.tech.areas.'.$type.'.costs')[$level - 1],
            'work' => 0,
            'order' => $jobOrder
        ]);

        Log::channel('api')
            ->info("Empire $player->ticker has queued research job $job->type TL $job->level");

        $f = new FormatApiResponseService;
        return response()->json([
            'researchJob' => $f->formatResearchJob($job),
            'message' => __('game.research.jobQueued')
        ]);

    }

}
