<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Http\RedirectResponse;

class GameController extends Controller
{

    /**
     * @param Request $request
     * @param string $gameId
     * @return \Illuminate\Contracts\View\View|RedirectResponse
     */
    public function show(Request $request, string $gameId)
    {
        $game = Game::find($gameId);
        $user = Auth::user();
        if (!$game) {
            return redirect()->back()
                ->with('status', __('admin.game.notfound'))
                ->with('severity', 'error');
        } else {
            $selectedPlayer = $user->players->filter(function ($player) use ($user) {
                return $player['id'] === $user->selected_player;
            })->first();
            return View::make('game.game', compact('game', 'selectedPlayer'));
        }
    }

}
