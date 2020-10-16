<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSuspension
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return RedirectResponse|JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (auth()->check() && auth()->user()->isSuspended()) {
            $user = Auth::user();
            $until = now();
            // get the longest ban.
            foreach($user->suspensions as $suspension) {
                $until = $suspension->until->gte($until) ? $suspension->until : $until;
            }
            // log user out.
            Auth::logout();
            // response
            if ($request->wantsJson()) {
                return response()->json(['error' => __('app.user.suspended', [
                    'until' => $until->format('d.m.Y H:i:s')
                ])]);
            } else {
                return redirect()->route('login')
                    ->with('status', __('app.user.suspended', [
                        'until' => $until->format('d.m.Y H:i:s')
                    ]))
                    ->with('severity', 'error');
            }
        }

        return $next($request);
    }
}
