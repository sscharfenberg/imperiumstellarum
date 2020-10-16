<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Models\Game;

class GamesController extends Controller
{
    /**
     * Show games overview
     * @param Request $request
     * @return View
     */
    public function show(Request $request)
    {
        $sortBy = $request->input(['sortBy']) ? $request->input(['sortBy']) : 'number';
        $order = $request->input(['order']) ? $request->input(['order']) : 'desc';
        $perPage = $request->input(['perPage']) ? $request->input(['perPage']) : '20';
        $games = Game::orderBy($sortBy, $order)->paginate($perPage);
        return view('admin.game.list', compact(
            'games', 'sortBy', 'order', 'perPage'
        ));
    }

    /**
     * Show games overview
     * @param Request $request
     * @return View
     */
    public function sortFilter(Request $request)
    {
        $formInput = $request->input(['sort']);
        $exploded = explode("--", $formInput);
        $sortBy = $exploded[0];
        $order = $exploded[1];
        $perPage = $request->input(['perPage']);
        $games = Game::orderBy($sortBy, $order)->paginate($perPage);
        return view('admin.game.list', compact(
            'games', 'sortBy', 'order', 'perPage'
        ));
    }

}
