<?php

namespace App\Services;

use App\Models\EncounterParticipant;
use App\Models\FinishedGame;
use App\Models\MessageRecipient;
use App\Models\Player;
use App\Models\RaidPlayer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\FormatApiResponseService;

class ApiService {


    /**
     * @function get and format storage upgrades in construction for a player
     * @param Player $player
     * @return mixed
     */
    public function storageUpgrades (Player $player)
    {
        $f = new FormatApiResponseService;
        return $player->storageUpgrades->map(function($upgrade) use ($f) {
            return $f->formatStorageUpgrades($upgrade);
        });
    }

    /**
     * @function count number of unread messages
     * @param string $playerId
     * @param string $gameId
     * @return int
     */
    public function unreadMessages (string $playerId, string $gameId): int
    {
        return count(
            MessageRecipient::where('game_id', '=', $gameId)
                ->where('recipient_id', '=', $playerId)
                ->where('deleted', '=', false)
                ->where('read', false)
                ->get()
        );
    }

    /**
     * @function count number of unread encounters (that are processed!)
     * @param string $playerId
     * @param string $gameId
     * @return int
     */
    public function unreadEncounters (string $playerId, string $gameId): int
    {
        return count(
            EncounterParticipant::where('game_id', '=', $gameId)
                ->where('player_id', '=', $playerId)
                ->where('read', false)
                ->whereHas('encounter', function (Builder $query) use ($playerId) {
                    $query->whereNotNull('processed_at');
                })
                ->get()
        );
    }

    /**
     * @function get number of unread raids
     * @param string $playerId
     * @param string $gameId
     * @return int
     */
    public function unreadRaids (string $playerId, string $gameId): int
    {
        return count(
            RaidPlayer::where('game_id', '=', $gameId)
                ->where('player_id', '=', $playerId)
                ->where('read', false)
                ->get()
        );
    }

    /**
     * @function create default stores of a player
     * @param Request $request
     * @return array
     */
    public function defaultData (Request $request): array
    {
        $user = Auth::user();
        $game = $user->selectedGame();
        $player = Player::find($user->selected_player);
        $r = new ResourceService;

        // games that are not finished calculated turn due and current turn
        if (!$game->finished) {
            $currentTurn = $game->turns->filter(function($turn) {
                return $turn->processed === null;
            })->first();
            // absolute=false does not work for diffInSeconds on php7.3
            // https://github.com/briannesbitt/Carbon/issues/1503
            // so, we'll work around this for now.
            $turnDue = $currentTurn->due->diffInSeconds(Carbon::now());
            if (now() > $currentTurn->due) {
                $turnDue = -$turnDue;
            }
            $turn = $currentTurn->number;
        }
        // finished games do not have a due turn and use the highest turn as the current turn.
        else {
            $turn = $game->turns->max('number');
            $turnDue = null;
        }

        $returnData = [
            'game' => [
                'number' => $game->number,
                'turn' => $turn,
                'turnDue' => $turnDue,
                'finished' => $game->finished,
            ],
            'player' => [
                'empireName' => $player->name,
                'empireTicker' => $player->ticker,
                'researchPriority' => $player->research_priority,
                'id' => $player->id,
                'colour' => $player->colour,
                'dead' => !!$player->dead
            ],
            'resources' => $r->getResources($player),
            'storageUpgrades' => $this->storageUpgrades($player),
            'unreadMessages' => $this->unreadMessages($player->id, $game->id),
            'unreadEncounters' => $this->unreadEncounters($player->id, $game->id),
            'unreadRaids' => $this->unreadRaids($player->id, $game->id)
        ];

        if ($game->finished) {
            $winner = FinishedGame::where('number', '=', $game->number)
                ->first()
                ->winner;
            $returnData['winner'] = [
                'ticker' => $winner->ticker,
                'name' => $winner->name,
                'colour' => $winner->colour
            ];
        }

        return $returnData;
    }


}
