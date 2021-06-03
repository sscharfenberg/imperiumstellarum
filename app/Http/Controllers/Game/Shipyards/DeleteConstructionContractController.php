<?php

namespace App\Http\Controllers\Game\Shipyards;

use App\Http\Controllers\Controller;
use App\Models\ConstructionContract;
use App\Models\Player;
use Exception;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\Game\UsesShipyardsVerification;
use Illuminate\Support\Facades\Log;

class DeleteConstructionContractController extends Controller
{

    use UsesShipyardsVerification;

    /**
     * @function handle delete construction contract xhr request
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function handle (Request $request): JsonResponse
    {
        $contractId = $request->input(['id']);
        $contract = ConstructionContract::find($contractId);
        $player = Player::find(Auth::user()->selected_player);

        // verification
        if (!$this->isContractPlayerOwned($contractId, $player)) {
            return response()
                ->json(['error' => __('game.shipyards.errors.constructionContract.owner')], 419);
        }

        // do delete
        try {
            $name = $contract->blueprint->name;
            $contract->delete();
            Log::channel('api')
                ->info("Empire $player->ticker has deleted construction contract that uses bp $name");
            $updatedPlayer = Player::find(Auth::user()->selected_player);
            $f = new FormatApiResponseService;
            return response()->json([
                'constructionContracts' => $updatedPlayer->constructionContracts->map(function ($c) use ($f) {
                    return $f->formatConstructionContract($c);
                }),
                'message' => __('game.shipyards.constructionContractDeleted')
            ]);
        } catch (Exception $e) {
            Log::channel('api')
                ->error('Exception while attempting to delete a construction contract:\n'. $e->getMessage());
            return response()->json(['error' => __('game.common.errors.deletion')], 419);
        }

    }

}
