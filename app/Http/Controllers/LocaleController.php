<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LocaleController extends Controller
{
    /**
     * change locale
     *
     * @param string $locale
     * @return RedirectResponse
     */
    public function change(string $locale): RedirectResponse
    {
        $userId = Auth::id();
        if (in_array($locale, config()->get('app.supportedLocales'))) {
            if ($userId) {
                $user = User::where('id', $userId)->first();
                $user->locale = $locale;
                $user->save();
            }
            session(['locale' => $locale]);
            app()->setLocale($locale);
        }
        return back();
    }
}
