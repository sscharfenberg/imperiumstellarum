<?php

namespace App\Http\Middleware\Game;

use App\Models\Game;
use Closure;
use Illuminate\Http\Request;

class GameOver
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $game = Game::find($request->route('game'));

        if ($game->finished) {
            if ($request->wantsJson()) {
                return response()->json(['error' => __('game.common.errors.gameOver')], 419);
            } else {
                return redirect()->back()
                    ->with('status', __('game.common.errors.gameOver'))
                    ->with('severity', 'error');
            }
        }

        return $next($request);
    }
}
