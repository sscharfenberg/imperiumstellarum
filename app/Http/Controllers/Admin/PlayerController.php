<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Player;
use App\Models\Suspension;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class PlayerController extends Controller
{

    /**
     * @function show players of a game
     * @param Request $request
     * @param string $id
     * @return View|RedirectResponse
     */
    public function show(Request $request, string $id)
    {
        $game = Game::find($id);
        if(!$game) {
            return redirect()->back()
                ->with('status', __('admin.game.notfound'))
                ->with('severity', 'error');
        }
        $sortBy = $request->input(['sortBy']) ? $request->input(['sortBy']) : 'created_at';
        $order = $request->input(['order']) ? $request->input(['order']) : 'desc';
        $perPage = $request->input(['perPage']) ? $request->input(['perPage']) : '20';
        $players = Player::where('game_id', $id)
            ->orderBy($sortBy, $order)
            ->paginate($perPage);
        $suspensions = [];
        foreach (Suspension::all() as $suspension) {
            if ($suspension->isActive() && !in_array($suspension->user_id, $suspensions)) {
                $suspensions[] = $suspension->user_id;
            }
        }
        return view('admin.game.players', compact(
            'players', 'game', 'suspensions', 'sortBy', 'order', 'perPage'
        ));
    }

    /**
     * @function filter players of a game
     * @param Request $request
     * @param string $id
     * @return View|RedirectResponse
     */
    public function sortFilter(Request $request, string $id)
    {
        $game = Game::find($id);
        if(!$game) {
            return redirect()->back()
                ->with('status', __('admin.game.notfound'))
                ->with('severity', 'error');
        }
        $formInput = $request->input(['sort']);
        $exploded = explode("--", $formInput);
        $sortBy = $exploded[0];
        $order = $exploded[1];
        $perPage = $request->input(['perPage']);
        $players = Player::where('game_id', $id)
            ->orderBy($sortBy, $order)
            ->paginate($perPage);
        $suspensions = [];
        foreach (Suspension::all() as $suspension) {
            if ($suspension->isActive() && !in_array($suspension->user_id, $suspensions)) {
                $suspensions[] = $suspension->user_id;
            }
        }
        return view('admin.game.players', compact(
            'players', 'game', 'suspensions', 'sortBy', 'order', 'perPage'
        ));
    }

}
