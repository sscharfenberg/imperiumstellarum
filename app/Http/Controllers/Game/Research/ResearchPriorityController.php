<?php

namespace App\Http\Controllers\Game\Research;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ResearchPriorityController extends Controller
{


    /**
     * @function verify research priority is within bounds
     * @param float $priority
     * @return bool
     */
    private function isPriorityValid (float $priority)
    {
        $rules = config('rules.tech.researchPriority');
        if (!is_float($priority)) return false;
        if ($priority < $rules['min'] || $priority > $rules['max']) return false;
        return true;
    }


    /**
     * @function handle research priority change request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function change (Request $request)
    {
        $priority = $request->input(['researchPriority']);

        // verify
        if(!$this->isPriorityValid($priority)) {
            return response()
                ->json(['error' => __('game.research.errors.priority.invalid')], 419);
        }

        // update player research priority
        $player = Player::find(Auth::user()->selected_player);
        $player->research_priority = $priority;
        $player->save();

        Log::info("Empire $player->ticker has changed research priority to $priority");

        return response()->json([
            'message' => __('game.research.researchPriorityChanged')
        ]);

    }

}
