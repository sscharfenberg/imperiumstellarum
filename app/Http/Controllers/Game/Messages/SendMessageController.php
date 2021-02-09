<?php

namespace App\Http\Controllers\Game\Messages;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Player;
use App\Models\MessageSent;

use App\Services\FormatApiResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Services\MessageService;
use App\Http\Traits\Game\UsesMessageVerification;

class SendMessageController extends Controller
{

    use UsesMessageVerification;

    /**
     * @function send fleet to destination
     * @param Request $request
     * @return JsonResponse
     */
    public function handle (Request $request): JsonResponse
    {
        $m = new MessageService;
        $f = new FormatApiResponseService;
        $player = Player::find(Auth::user()->selected_player);
        $gameId = $request->route('game');
        $game = Game::find($gameId);
        $recipientIds = $request->input(["recipients"]);
        $subject = htmlspecialchars($request->input(["subject"]));
        $body = htmlspecialchars($request->input(["body"]));
        $repliesTo = $request->input(["repliesTo"]);

        // verification
        if (!$this->recipientsContainOnlyValidUuids($recipientIds)) {
            return response()
                ->json(['error' => __('game.messages.errors.uuids')], 419);
        }
        if (!$this->recipientConstraintsMet($recipientIds)) {
            return response()
                ->json(['error' => __('game.messages.errors.recipients', [
                    'min' => config('rules.messages.recipients.min'),
                    'max' => config('rules.messages.recipients.max')
                ])], 419);
        }
        if (!$this->recipientsAreEnlisted($recipientIds, $game)) {
            return response()
                ->json(['error' => __('game.messages.errors.players')], 419);
        }
        if (!$this->subjectConstraintsMet($subject)) {
            return response()
                ->json(['error' => __('game.messages.errors.subject', [
                    'min' => config('rules.messages.subject.min'),
                    'max' => config('rules.messages.subject.max')
                ])], 419);
        }
        if (!$this->bodyConstraintsMet($body)) {
            return response()
                ->json(['error' => __('game.messages.errors.message', [
                    'min' => config('rules.messages.body.min'),
                    'max' => config('rules.messages.body.max')
                ])], 419);
        }

        // create inbox messages
        $m->createMessages($gameId, $player->id, $recipientIds, $subject, $body);

        // send response to client
        $outbox = MessageSent::where('game_id', $gameId)
            ->where('player_id', $player->id)
            ->get();
        return response()->json([
            'outbox' => $outbox->map(function ($message) use ($f) {
                return $f->formatMessageSent($message);
            }),
            'message' => trans_choice('game.messages.messageSent', count($recipientIds), [
                'num' => count($recipientIds)
            ])
        ]);

    }

}
