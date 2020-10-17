<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrawerController extends Controller
{
    public function update (Request $request)
    {
        $drawerStatus = $request->input(['drawer']);
        $user = Auth::user();
        $user->drawer_open = $drawerStatus;
        $user->save();
        return response()->json(['status' => 'ok']);
    }
}
