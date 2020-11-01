<?php

namespace App\Http\Middleware\Game;

use App\Models\Game;
use Closure;
use Illuminate\Http\Request;

class NotProcessing
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
        if ($game->processing) {
            return response()->json(['error' => __('game.common.errors.processing')], 419);
        } else {
            return $next($request);
        }
    }
}
