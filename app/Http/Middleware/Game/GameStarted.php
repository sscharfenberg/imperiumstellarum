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

        if (!$game) {
            if ($request->wantsJson()) {
                return response()->json(['error' => __('game.common.errors.gameNotFound')], 419);
            } else {
                return redirect()->back()
                    ->with('status', __('game.common.errors.gameNotFound'))
                    ->with('severity', 'error');
            }
        }

        if (count($game->turns) === 0) {
            if ($request->wantsJson()) {
                return response()->json(['error' => __('game.common.errors.notStarted')], 419);
            } else {
                return redirect()->back()
                    ->with('status', __('game.common.errors.notStarted'))
                    ->with('severity', 'error');
            }
        }

        return $next($request);

    }
}
