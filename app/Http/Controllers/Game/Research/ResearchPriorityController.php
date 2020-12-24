<?php

namespace App\Http\Controllers\Game\Research;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Http\Traits\Game\UsesResearchVerification;

class ResearchPriorityController extends Controller
{

    use UsesResearchVerification;

    /**
     * @function handle research priority change request
     * @param Request $request
     * @return JsonResponse
     */
    public function change (Request $request): JsonResponse
    {
        $priority = $request->input(['researchPriority']);

        // verify
        if(!$this->isResearchPriorityValid($priority)) {
            return response()
                ->json(['error' => __('game.research.errors.priority.invalid')], 419);
        }

        // update player research priority
        $player = Player::find(Auth::user()->selected_player);
        $player->research_priority = $priority;
        $player->save();

        Log::info("Empire $player->ticker has changed research priority to $priority");

        return response()->json([]);

    }

}
