<?php
namespace App\Services;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class ApiService {

    /**
     * @param Player $player
     * @return Collection
     */
    public function getResources (Player $player)
    {
        return $player->resources->map(function ($store) {
            return [
                'type' => $store->resource_type,
                'amount' => $store->storage,
                'max' => config(
                    'rules.player.resourceTypes.'.$store->resource_type.'.'.$store->storage_level.'.amount'
                ),
                'level' => $store->storage_level,
                'maxLevel' => array_key_last(config('rules.player.resourceTypes.'.$store->resource_type))
            ];
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
            'resources' => $this->getResources($player)
        ];
    }

}
