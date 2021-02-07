<?php

namespace App\Http\Controllers\Game\Messages;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\MessageRecipient;
use App\Models\Player;
use App\Models\Message;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

use App\Http\Traits\Game\UsesMessageVerification;

class SendMessageController extends Controller
{

    use UsesMessageVerification;

    /**
     * size of chunks for database operations
     * @var int
     */
    private $chunkSize = 10;

    /**
     * @function create message entries for inbox
     * @param string $gameId
     * @param string $senderId
     * @param array $recipientIds
     * @param string $subject
     * @param string $body
     * @return void
     */
    public function createMessages (string $gameId, string $senderId, array $recipientIds, string $subject, string $body)
    {
        $messages = array_map(function($recipientId) use ($gameId, $senderId, $recipientIds, $subject, $body) {
            return [
                'id' => Uuid::uuid4(),
                'game_id' => $gameId,
                'player_id' => $recipientId,
                'sender_id' => $senderId,
                'message_id' => null,
                'subject' => $subject,
                'body' => $body,
                'recipient_ids' => json_encode($recipientIds),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }, $recipientIds);
        $chunks = array_chunk($messages, $this->chunkSize);
        foreach($chunks as $chunk) {
            Message::insert($chunk);
        }
    }

    /**
     * @function send fleet to destination
     * @param Request $request
     * @return JsonResponse
     */
    public function handle (Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $gameId = $request->route('game');
        $game = Game::find($gameId);
        $recipientIds = $request->input(["recipients"]);
        $subject = htmlspecialchars($request->input(["subject"]));
        $body = htmlspecialchars($request->input(["body"]));
        $repliesTo = $request->input(["repliesTo"]);

        // verify subject constraints
        // verify body constraints

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
        $this->createMessages($gameId, $player->id, $recipientIds, $subject, $body);

        var_dump($body);
        dd($body);

    }

}
