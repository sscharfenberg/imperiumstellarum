<?php

namespace App\Http\Controllers\Game\Encounters;

use App\Http\Controllers\Controller;
use App\Models\EncounterParticipant;
use App\Models\Player;
use App\Services\ApiService;
use App\Services\EncounterService;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadEncounterController extends Controller
{

    /**
     * @function mark message as read/unread
     * @param Request $request
     * @return JsonResponse
     */
    public function handle (Request $request): JsonResponse
    {
        $a = new ApiService;
        $e = new EncounterService;
        $f = new FormatApiResponseService;
        $gameId = $request->route('game');
        $encounterId = $request->route('encounter');
        $status = $request->input(['status']);
        $player = Player::find(Auth::user()->selected_player);
        $encounters = $e->getPlayerEncounters($player, $gameId);
        $encounter = $encounters->where('id', '=', $encounterId)->first();

        // verification
        if (count($encounters) === 0 || !$encounter) {
            return response()
                ->json(['error' => __('game.encounters.errors.noEncounter')], 419);
        }

        $participant = EncounterParticipant::where('game_id', '=', $gameId)
            ->where('encounter_id', '=', $encounter->id)
            ->where('player_id', '=', $player->id)
            ->first();

        if ($participant->read !== $status) {
            $participant->read = $status;
            $participant->save();
        }

        $updatedEncounters = $e->getPlayerEncounters($player, $gameId);

        $returnData = [
            'encounters' => $updatedEncounters->map(function ($encounter) use ($f, $player) {
                return $f->formatPlayerEncounter($encounter, $player);
            }),
        ];

        return response()->json(array_merge($a->defaultData($request), $returnData));

    }

}
