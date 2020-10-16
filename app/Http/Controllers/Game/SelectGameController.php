<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Player;
use Illuminate\Support\Facades\Auth;

class SelectGameController extends Controller
{

    /**
     * @function change the selected game(/player) of the user
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function select (Request $request, $id)
    {
        $user = Auth::user();
        $player = Player::where('user_id', $user->id)->where('game_id', $id)->first();
        // TODO: get player!
        if (!$player) {
            return redirect()->back()
                ->with('status', __('admin.game.notfound'))
                ->with('severity', 'error');
        }
        $user->selected_player = $player->id;
        $user->save();
        return redirect()->back();
    }

}
