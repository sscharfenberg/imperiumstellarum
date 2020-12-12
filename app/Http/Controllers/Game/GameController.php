<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class GameController extends Controller
{

    /**
     * @param Request $request
     * @param string $gameId
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Request $request, string $gameId): \Illuminate\Contracts\View\View
    {
        $gameNumber = Game::find($gameId)->number;
        return View::make('game.game', compact(['gameId', 'gameNumber']));
    }

}
