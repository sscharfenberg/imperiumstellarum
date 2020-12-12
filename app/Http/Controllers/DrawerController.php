<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrawerController extends Controller
{

    /**
     * @function update user drawer status
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update (Request $request): \Illuminate\Http\JsonResponse
    {
        $drawerStatus = $request->input(['drawer']);
        $user = Auth::user();
        $user->drawer_open = $drawerStatus;
        $user->save();
        return response()->json(['status' => 'ok']);
    }
}
