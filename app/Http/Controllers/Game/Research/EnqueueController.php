<?php

namespace App\Http\Controllers\Game\Research;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnqueueController extends Controller
{

    public function handle(Request $request)
    {
        $player = Player::find(Auth::user()->selected_player);
        $type = $request->input(["type"]);
        $level = $request->input(["level"]);
        dd($level);
    }

}
