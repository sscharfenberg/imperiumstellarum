<?php

namespace App\Http\Controllers\Game\Fleets;

use App\Http\Controllers\Controller;
use App\Models\Fleet;
use App\Models\Game;
use App\Models\Player;
use App\Models\Shipyard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Traits\Game\UsesFleetsVerification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\FormatApiResponseService;

class FleetTransferController extends Controller
{

    use UsesFleetsVerification;

    /**
     * @function change fleet name via xhr request
     * @param Request $request
     * @return JsonResponse
     */
    public function handle (Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $sourceId = $request->input(['sourceId']);
        $sourceType = $request->input(['sourceType']);
        $sourceShipIds = $request->input(['sourceShipIds']);
        $targetId = $request->input(['targetId']);
        $targetType = $request->input(['targetType']);
        $targetShipIds = $request->input(['targetShipIds']);
        $game = Game::find($request->route('game'));
        $f = new FormatApiResponseService;

        Log::info(
            "Player $player->ticker in g$game->number requests ship transfer between $sourceType $sourceId and $targetType $targetId.\n"
            .'Source shipIds: '.json_encode($sourceShipIds, JSON_PRETTY_PRINT)."\n"
            .'Target shipIds: '.json_encode($targetShipIds, JSON_PRETTY_PRINT)
        );

        // verification
        if (!$this->holdersBelongToPlayer($player, $sourceId, $targetId, $sourceType, $targetType)) {
            return response()
                ->json(['error' => __('game.fleets.errors.holderOwner')], 419);
        }
        if (!$this->areShipIdsUnique($sourceShipIds, $targetShipIds)) {
            return response()
                ->json(['error' => __('game.fleets.errors.shipsUnique')], 419);
        }
        if (!$this->playerOwnsShips($player, array_merge($sourceShipIds, $targetShipIds))) {
            return response()
                ->json(['error' => __('game.fleets.errors.shipsOwner')], 419);
        }
        if (!$this->shipsBelongToHolders([$sourceId, $targetId], array_merge($sourceShipIds, $targetShipIds))) {
            return response()
                ->json(['error' => __('game.fleets.errors.shipHolders')], 419);
        }

        // verification ok; proceed with actual change. These updates use query builder for performance reasions.
        // left side, source
        if ($sourceType === 'fleet') {
            DB::table('ships')
                ->whereIn('id', $sourceShipIds)
                ->update(['fleet_id' => $sourceId, 'shipyard_id' => null, 'updated_at' => now()]);
        } else if ($sourceType === 'shipyard') {
            DB::table('ships')
                ->whereIn('id', $sourceShipIds)
                ->update(['fleet_id' => null, 'shipyard_id' => $sourceId, 'updated_at' => now()]);
        }
        // right side, target
        if ($targetType === 'fleet') {
            DB::table('ships')
                ->whereIn('id', $targetShipIds)
                ->update(['fleet_id' => $targetId, 'shipyard_id' => null, 'updated_at' => now()]);
        } else if ($targetType === 'shipyard') {
            DB::table('ships')
                ->whereIn('id', $targetShipIds)
                ->update(['fleet_id' => null, 'shipyard_id' => $targetId, 'updated_at' => now()]);
        }

        $updatedPlayer = Player::find(Auth::user()->selected_player);
        return response()->json([
            'ships' => $updatedPlayer->ships->map(function ($ship) use ($f) {
                return $f->formatShip($ship);
            }),
            'fleets' => $updatedPlayer->fleets->map(function ($fleet) use ($f) {
                return $f->formatFleet($fleet);
            }),
            'message' => __('game.fleets.shipsTransfered')
        ]);

    }
}
