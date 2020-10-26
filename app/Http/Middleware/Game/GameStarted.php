<?php

namespace App\Http\Middleware\Game;

use App\Models\Game;
use Closure;
use Illuminate\Http\Request;

class GameStarted
{
    /**
     * @function this middleware ensures that the game has already started.
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $game = Game::find($request->route('game'));
        if (count($game->turns) === 0) {
            if ($request->wantsJson()) {
                return response()->json(['error' => __('game.empire.error.notStarted')], 419);
            } else {
                return redirect()->back()
                    ->with('status', __('game.empire.error.notStarted'))
                    ->with('severity', 'error');
            }
        } else {
            return $next($request);
        }
    }
}
