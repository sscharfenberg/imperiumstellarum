<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginStatusController extends Controller
{
    /**
     * TEMP test if we can still reach api when logged in, but not when logged out.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function test(Request $request) {
        return response()->json(['user' => Auth::user()->id]);
    }
}
