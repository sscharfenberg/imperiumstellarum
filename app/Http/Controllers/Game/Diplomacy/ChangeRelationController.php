<?php

namespace App\Http\Controllers\Game\Diplomacy;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Player;
use App\Models\PlayerRelation;
use App\Models\PlayerRelationChange;
use App\Services\FormatApiResponseService;
use App\Services\PlayerRelationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Traits\Game\UsesDiplomacyVerification;
use Illuminate\Support\Facades\Auth;

class ChangeRelationController extends Controller
{

    use UsesDiplomacyVerification;

    /**
     * @function handle incoming "change relation" request
     * @param Request $request
     * @return JsonResponse
     */
    public function handle(Request $request):JsonResponse
    {
        $p = new PlayerRelationService;
        $f = new FormatApiResponseService;
        $player = Player::find(Auth::user()->selected_player);
        $recipientId = $request->input(["recipient"]);
        $game = Game::find($player->game_id);
        $recipient = Player::where('game_id', $game->id)
            ->where('dead', false)
            ->where('id', $recipientId)
            ->first();
        $setStatus = $request->input(["set"]);
        $currentRelation = PlayerRelation::where('game_id', $game->id)
            ->where('player_id', $player->id)
            ->where('recipient_id', $recipientId)
            ->first(); // could be null if there is no relation set currently.

        // verification
        if (!$recipient) {
            return response()
                ->json(['error' => __('game.diplomacy.errors.recipientInvalid')], 419);
        }
        if (!$this->isStatusValid($setStatus, $currentRelation)) {
            return response()
                ->json(['error' => __('game.diplomacy.errors.statusInvalid')], 419);
        }
        if (!$this->hasNoRelationChangePending($game->id, $player->id, $recipientId)) {
            return response()
                ->json(['error' => __('game.diplomacy.errors.relationChangePending')], 419);
        }

        // create PlayerRelationChange
        PlayerRelationChange::create([
            'game_id' => $game->id,
            'player_id' => $player->id,
            'recipient_id' => $recipientId,
            'status' => $setStatus
        ]);

        $updatedPlayer = Player::find(Auth::user()->selected_player);
        $gameRelations = PlayerRelation::where('game_id', $game->id)->get();
        $players = Player::where('game_id', $game->id)
            ->where('dead', false)
            ->get();
        $turns = config('rules.diplomacy.turnsUntilEffective');
        return response()->json([
            'relations' => $p->formatAllPlayerRelations($player, $gameRelations, $players),
            'relationChanges' => $updatedPlayer->relationChanges->map(function ($relationChange) use ($f) {
                return $f->formatPlayerRelationChange($relationChange);
            }),
            'message' => __('game.diplomacy.relationChangeAccepted', ['num' => $turns])
        ]);


    }

}
