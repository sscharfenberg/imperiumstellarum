<?php

namespace App\Http\Controllers\Game\Research;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Research;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnqueueController extends Controller
{

    /**
     * @function ensure the area exists
     * @param string $type
     * @return bool
     */
    private function areaExists (string $type)
    {
        return array_key_exists($type, config('rules.tech.areas'));
    }

    /**
     * @function ensure the level is within bounds
     * @param int $level
     * @return bool
     */
    private function isWithinBounds (int $level)
    {
        if (!is_numeric($level)) return false;
        return $level >= config('rules.tech.bounds.min')
            && $level < config('rules.tech.bounds.max');
    }

    /**
     * @function verify the new level is not already researched or enqueued
     * @param Player $player
     * @param string $type
     * @param int $level
     * @return bool
     */
    private function isResearchable (Player $player, string $type, int $level)
    {
        $currentLevel = $player->techLevels->where('type', $type)->first()->level;
        $researches = $player->researches->where('type', $type)
            ->sortByDesc('level')->first();
        $nextLevel = $currentLevel + 1;
        if ($researches) $nextLevel = $researches->level + 1;
        if ($nextLevel !== $level) return false;
        return true;
    }

    /**
     * @function verify the number of research jobs is not > max
     * @param Player $player
     * @return bool
     */
    private function verifyResearchQueueMax (Player $player)
    {
        $numResearchJobs = count($player->researches);
        return $numResearchJobs < config('rules.tech.queue');
    }

    /**
     * @function handle enqueue research job request
     * @param Request $request
     * @return JsonResponse
     */
    public function handle(Request $request)
    {
        $player = Player::find(Auth::user()->selected_player);
        $type = $request->input('type');
        $level = $request->input('level');

        // verification
        if (!$this->areaExists($type)) {
            return response()
                ->json(['error' => __('game.research.errors.enqueue.area')], 419);
        }
        if (!$this->isWithinBounds($level)) {
            return response()
                ->json(['error' => __('game.research.errors.enqueue.bounds')], 419);
        }
        if (!$this->isResearchable($player, $type, $level)) {
            return response()
                ->json(['error' => __('game.research.errors.enqueue.alreadyResearched')], 419);
        }
        if (!$this->verifyResearchQueueMax($player)) {
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

        //$jobs = $player->researches();

        $f = new FormatApiResponseService;
        return response()->json([
            'researchJob' => $f->formatResearchJob($job)
        ]);

        //

    }

}
