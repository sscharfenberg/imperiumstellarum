<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Jenssegers\Agent\Agent;
use App\Models\Game;

class DashboardController extends Controller
{

    use PasswordValidationRules;

    /**
     * Show the dashboard
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Request $request): \Illuminate\Contracts\View\View
    {
        $sessions = DB::Table('sessions')
            ->where('user_id', Auth::user()->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) use ($request) {
                $agent = tap(new Agent, function ($agent) use ($session) {
                    $agent->setUserAgent($session->user_agent);
                });
                return (object) [
                    'agent' => [
                        'is_desktop' => $agent->isDesktop(),
                        'platform' => $agent->platform(),
                        'browser' => $agent->browser(),
                    ],
                    'ip_address' => $session->ip_address,
                    'is_current_device' => $session->id === $request->session()->getId(),
                    'last_active' => $session->last_activity,
                ];
            });;
        $availableGames = Game::where('can_enlist', true)
            ->where('start_date', '>', now())
            ->where('active', 'false')
            ->with('stars')
            ->with('players')
            ->get()
            ->reject( function($game) {
                return count($game->players) >= $game->max_players
                    || $game->players->contains('user_id', Auth::user()->id);
            });
        $players = Auth::user()->players;
        return View::make('app.dashboard', compact('sessions', 'availableGames', 'players'));
    }

    /**
     * Update the user's password
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function password(Request $request): RedirectResponse
    {
        $input = $request->input();
        $user = Auth::user();
        $validator = Validator::make($input, [
            'current-password' => ['required', 'string'],
            'new-password' => $this->passwordRules()
        ])->after(function ($passwordValidator) use ($user, $input) {
            if (! Hash::check($input['current-password'], $user->password)) {
                $passwordValidator->errors()->add('current-password', __('passwords.update'));
            }
        });
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $user->password = Hash::make($input['new-password']);
            $user->save();
            return back()
                ->with('status', __('app.dashboard.password.success'))->with('severity', 'success');
        }
    }

    /**
     * Update the user's mail notification settings
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function notification(Request $request): RedirectResponse
    {
        $input = $request->input();
        $user = Auth::user();
        $user->game_mail_optin = isset($input['dashboard-optin']);
        $user->save();
        return redirect()
            ->back()
            ->with('status', __('app.dashboard.notifications.success'))->with('severity', 'success');
    }

    /**
     * Update the user's email
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function email(Request $request): RedirectResponse
    {
        $input = $request->input();
        $user = Auth::user();
        $validator = Validator::make($input, [
            'email-email' => ['required', 'string', 'max:120']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $user->email = $input["email-email"];
            $user->email_verified_at = null;
            $user->save();

            $user->sendEmailVerificationNotification();
            Auth::logout();

            return redirect()
                ->route('welcome')
                ->with('status', __('app.dashboard.email.success'))->with('severity', 'success');
        }
    }

    /**
     * Delete user account
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $user->delete();
        Auth::logout();

        return redirect()
            ->route('welcome')
            ->with('status', __('app.dashboard.delete.success'))->with('severity', 'success');
    }

}
