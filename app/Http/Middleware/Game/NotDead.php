<?php

namespace App\Http\Middleware\Game;

use App\Models\Game;
use App\Models\Player;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotDead
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $player = Player::find(Auth::user()->selected_player);

        if (!$player || $player->dead) {
            if ($request->wantsJson()) {
                return response()->json(['error' => __('game.common.errors.dead')], 419);
            } else {
                return redirect()->back()
                    ->with('status', __('game.common.errors.dead'))
                    ->with('severity', 'error');
            }
        }

        return $next($request);
    }
}
