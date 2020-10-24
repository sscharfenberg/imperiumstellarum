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
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Request $request, string $gameId)
    {
        return View::make('game.game', compact('gameId'));
    }

}
