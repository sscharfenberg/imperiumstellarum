<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Laravel\Fortify\Http\Requests\VerifyEmailRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  VerifyEmailRequest  $request
     * @return RedirectResponse
     */
    public function __invoke(VerifyEmailRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect(config('fortify.home').'?verified=1')
                ->with('status', __('app.verify.success'))->with('severity', 'success');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        //return redirect(config('fortify.home').'?verified=1');
        return redirect(config('fortify.home').'?verified=1')
            ->with('status', __('app.verify.success'))->with('severity', 'success');
    }
}
