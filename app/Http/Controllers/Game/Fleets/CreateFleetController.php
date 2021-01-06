<?php

namespace App\Http\Controllers\Game\Fleets;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Traits\Game\UsesFleetsVerification;
use Illuminate\Support\Facades\Auth;

class CreateFleetController extends Controller
{

    use UsesFleetsVerification;

    /**
     * @function create a new blueprint from xhr request
     * @param Request $request
     * @return JsonResponse
     */
    public function handle (Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $starId = $request->input(['location']);
        $name = $request->input(['name']);
        dd($starId);
    }

}
