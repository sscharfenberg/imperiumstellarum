<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ApiService {

    /**
     * @function create default stores of a player
     * @param Request $request
     * @return array
     */
    public function defaultData (Request $request)
    {
        $user = Auth::user();
        $game = $user->selectedGame();
        $player = $user->players->find($user->selected_player);
        $currentTurn = $game->turns->filter(function($turn) {
            return $turn->processed === null;
        })->first();

        return [
            'game' => [
                'number' => $game->number,
                'turn' => $currentTurn->number,
                'turnDue' => $currentTurn->due->diffInSeconds(Carbon::now()),
            ],
            'player' => [
                'name' => $player->name,
                'ticker' => $player->ticker,
                'researchPriority' => $player->research_priority
            ]
        ];
    }



}
