<?php

namespace App\Http\Controllers\Game\Shipyards;

use App\Http\Controllers\Controller;
use App\Models\Blueprint;
use App\Models\Player;
use App\Services\FormatApiResponseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\Game\UsesShipyardsVerification;

class DeleteBlueprintController extends Controller
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
        $player = Player::find(Auth::user()->selected_player);

        // verification
        if (!$this->isBlueprintPlayerOwned($blueprintId, $player)) {
            return response()
                ->json(['error' => __('game.shipyards.errors.blueprint.owner')], 419);
        }
        // TODO: ensure no ships are being built based on this blueprint

        // do delete
        try {
            $blueprint->delete();
            Log::info("Empire $player->ticker has deleted blueprint $blueprint->name");
            $updatedPlayer = Player::find(Auth::user()->selected_player);
            $f = new FormatApiResponseService;
            return response()->json([
                'blueprints' => $updatedPlayer->blueprints->map(function ($bp) use ($f) {
                    return $f->formatBlueprint($bp);
                }),
                'message' => __('game.shipyards.blueprintDeleted')
            ]);

        } catch (Exception $e) {
            Log::error('Exception while attempting to delete a blueprint:\n'. $e->getMessage());
        }

    }

}
