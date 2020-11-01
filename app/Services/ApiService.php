<?php

namespace App\Services;

use App\Models\Player;
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
        $r = new ResourceService();

        return [
            'game' => [
                'number' => $game->number,
                'turn' => $currentTurn->number,
                'turnDue' => $currentTurn->due->diffInSeconds(Carbon::now()),
            ],
            'player' => [
                'empireName' => $player->name,
                'empireTicker' => $player->ticker,
                'researchPriority' => $player->research_priority
            ],
            'resources' => $r->getResources($player),
            'storageUpgrades' => $this->storageUpgrades($player)
        ];
    }


}
