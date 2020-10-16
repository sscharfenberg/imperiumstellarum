<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Suspension;
use App\Models\User;
use App\Notifications\UserSuspended;
use App\Notifications\UserSuspensionLifted;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SuspensionController extends Controller
{

    /**
     * suspend a user
     * @param Request $request
     * @param int $userId
     * @return RedirectResponse
     */
    public function suspend (Request $request, int $userId)
    {
        $validator = Validator::make($request->input(), [
            'duration' => ['required'],
            'reason' => ['required', 'string', 'min:10', 'max:200']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $duration = $request->input(['duration']);
            $until = $duration === '99' ? Carbon::now()->addYears($duration) : Carbon::now()->addDays($duration);
            $suspension = Suspension::create([
                'user_id' => $userId,
                'until' => $until,
                'issuer_id' => Auth::user()->id,
                'issuer_reason' => $request->input(['reason'])
            ]);
            $suspension['duration'] = $duration;

            // send notification to user.
            $user = User::find($userId);
            $user->notify(
                (new UserSuspended($duration, $until, $request->input(['reason'])))
                ->locale($user->locale)
            );

            return back()
                ->with('status', __('admin.user.suspensions.success'))
                ->with('severity', 'success');
        }
    }

    /**
     * delete a suspension
     * @param Request $request
     * @param string $suspension
     * @return RedirectResponse
     */
    public function delete (Request $request, string $suspension) {
        $suspension = Suspension::find($suspension);
        $user = $suspension->user;
        if (!$suspension || !$user) {
            return redirect()->back()
                ->with('status', __('admin.user.suspensions.deleteSuspensionError'))
                ->with('severity', 'error');
        } else {
            $suspension->delete();
            $user->notify(
                (new UserSuspensionLifted($suspension->until, $suspension->issuer_reason))
                    ->locale($user->locale)
            );
            return redirect()->back()
                ->with('status', __('admin.user.suspensions.deleteSuspensionSuccess'))
                ->with('severity', 'success');
        }
    }

}
