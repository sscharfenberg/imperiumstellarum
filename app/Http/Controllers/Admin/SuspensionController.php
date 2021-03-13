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
use App\Services\UserService;

class SuspensionController extends Controller
{

    /**
     * suspend a user
     * @param Request $request
     * @param int $userId
     * @return RedirectResponse
     */
    public function suspend (Request $request, int $userId): RedirectResponse
    {
        $validator = Validator::make($request->input(), [
            'duration' => ['required'],
            'reason' => ['required', 'string', 'min:10', 'max:200']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $u = new UserService;
            $u->suspend($userId, Auth::user(), $request->input(['duration']), $request->input(['reason']));
            return back()
                ->with('status', __('admin.user.suspensions.success'))
                ->with('severity', 'success');
        }
    }

    /**
     * delete a suspension
     * @param Request $request
     * @param string $suspension
     * @throws \Exception
     * @return RedirectResponse
     */
    public function delete (Request $request, string $suspension): RedirectResponse
    {
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
