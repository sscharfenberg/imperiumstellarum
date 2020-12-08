<?php

namespace App\Http\Controllers\Game\Starchart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StarDetailsController extends Controller
{
    /**
     * @function handle star details request
     * @param Request $request
     */
    public function handle(Request $request)
    {
        $gameId = $request->route("game");
        $starId = $request->route("star");
        dd($starId);
    }
}
