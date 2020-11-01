<?php

namespace App\Http\Middleware\Game;

use App\Models\Game;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Enlisted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $game = Game::find($request->route('game'));
        $player = $game->players->find(Auth::user()->selected_player);
        if (!$player) {
            if ($request->wantsJson()) {
                return response()->json(['error' => __('game.common.errors.noPlayer')], 419);
            } else {
                return redirect()->back()
                    ->with('status', __('game.common.errors.noPlayer'))
                    ->with('severity', 'error');
            }
        } else {
            return $next($request);
        }
    }
}
