<?php

namespace App\Http\Controllers\Game\Shipyards;

use App\Http\Controllers\Controller;
use App\Models\Blueprint;
use App\Models\Player;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\Game\UsesShipyardsVerification;

class RenameBlueprintController extends Controller
{

    use UsesShipyardsVerification;

    /**
     * @function create a new blueprint from xhr request
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function handle (Request $request): JsonResponse
    {
        $blueprintId = $request->input(['id']);
        $blueprint = Blueprint::find($blueprintId);
        $bpName = $request->input(['name']);
        $player = Player::find(Auth::user()->selected_player);

        // verification
        if (!$this->isBlueprintPlayerOwned($blueprintId, $player)) {
            return response()
                ->json(['error' => __('game.shipyards.errors.blueprint.owner')], 419);
        }
        if (!$this->isClassNameValid($bpName)) {
            return response()
                ->json(['error' => __('game.shipyards.errors.blueprint.className')], 419);
        }

        // change blueprint name
        $blueprint->name = $bpName;
        $blueprint->save();

        $updatedPlayer = Player::find(Auth::user()->selected_player);
        $f = new FormatApiResponseService;
        return response()->json([
            'blueprints' => $updatedPlayer->blueprints->map(function ($bp) use ($f) {
                return $f->formatBlueprint($bp);
            }),
            'message' => __('game.shipyards.blueprintRenamed')
        ]);
    }

}
