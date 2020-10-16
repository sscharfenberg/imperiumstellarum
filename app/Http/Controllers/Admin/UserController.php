<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;

class UserController extends Controller
{

    /**
     * edit/view user
     * @param  \Illuminate\Http\Request  $request
     * @param int $userId
     * @return \Illuminate\View\View
     */
    public function details(Request $request, int $userId)
    {
        $sessions = [];
        if (config('session.driver') == 'database') {
            $sessions = DB::Table('sessions')
                ->where('user_id', $userId)
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
                });
        }
        $user = User::find($userId);
        return view('admin.user', compact ('user', 'sessions'));
    }

    /**
     * Change specific user
     * @param Request $request
     * @param int $userId
     * @return View|RedirectResponse
     */
    public function change(Request $request, int $userId)
    {
        $user = User::find($userId);
        $hasChanges = false;

        // check if email changed.
        if ($user->email !== $request->input(['email'])) {
            $validator = Validator::make($request->input(), [
                'email' => ['required', 'string', 'email', 'max:120', 'unique:users']
            ]);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $hasChanges = true;
                $user->email = $request->input(['email']);
            }
        }

        // check if role changed
        if ($user->role !== $request->input(['role'])) {
            $hasChanges = true;
            $user->role = $request->input(['role']);
        }

        if ($hasChanges) {
            $user->save();
            return back()->with('status', __('admin.user.data.success'))
                ->with('severity', 'success');
        } else {
            return back()->with('status', __('admin.user.data.noChange'))
                ->with('severity', 'warning');
        }

        dd($user->email);
    }

}
