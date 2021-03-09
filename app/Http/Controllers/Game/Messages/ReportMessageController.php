<?php

namespace App\Http\Controllers\Game\Messages;

use App\Http\Controllers\Controller;
use App\Http\Traits\Game\UsesMessageVerification;
use App\Models\Message;
use App\Models\MessageReport;
use App\Models\Player;
use App\Services\ApiService;
use App\Services\FormatApiResponseService;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportMessageController extends Controller
{

    use UsesMessageVerification;

    /**
     * @function delete a message
     * @param Request $request
     * @return JsonResponse
     */
    public function handle (Request $request): JsonResponse
    {
        $a = new ApiService;
        $f = new FormatApiResponseService;
        $m = new MessageService;
        $player = Player::find(Auth::user()->selected_player);
        $messageId = $request->input(['messageId']);
        $gameId = $request->route('game');
        $comment = $request->input(['comment']);

        // check if this message is in the submitting players inbox.
        // verify comment constraints are met.

        // verification
        if (!$this->commentConstraintsMet($comment)) {
            return response()
                ->json(['error' => __('game.messages.errors.comment', [
                    'min' => config('rules.messages.reportComment.min'),
                    'max' => config('rules.messages.reportComment.max')
                ])], 419);
        }
        if (!$this->playerOwnsInboxMessages([$messageId], $player)) {
            return response()
                ->json(['error' => __('game.messages.errors.messageOwner')], 419);
        }
        if (!$this->playerHasNoReportForMessage($messageId, $player)) {
            return response()
                ->json(['error' => __('game.messages.errors.alreadyReported')], 419);
        }

        // verification done, create report
        $message = Message::where('id', '=', $messageId)
            ->where('game_id', '=', $gameId)
            ->first();
        MessageReport::create([
            'game_id' => $gameId,
            'message_id' => $messageId,
            'reporter_id' => $player->id,
            'reportee_id' => $message->sender_id,
            'comment' => $comment
        ]);

        // get fresh data
        $reports = MessageReport::where('game_id', '=', $gameId)
            ->where('reporter_id', '=', $player->id)
            ->get();
        $outbox = $m->getPlayerOutbox($player->id, $player->game_id);
        $inbox = $m->getPlayerInbox($player->id, $player->game_id);

        // return response to client
        return response()->json([
            'reports' => $reports->map(function ($report) use ($f) {
                return $f->formatMessageReport($report);
            }),
            'inbox' => $inbox->map(function ($message) use ($f, $player) {
                return $f->formatInboxMessage($message, $player->id);
            }),
            'outbox' => $outbox->map(function ($message) use ($f) {
                return $f->formatOutboxMessage($message);
            }),
            'unreadMessages' => $a->unreadMessages($player->id, $player->game_id),
            'message' => __('game.messages.reportRecieved')
        ]);

    }

}
