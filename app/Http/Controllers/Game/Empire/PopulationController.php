<?php

namespace App\Http\Controllers\Game\Empire;

use App\Http\Controllers\Controller;
use App\Models\Planet;
use App\Models\Player;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\Game\UsesPopulationGrowth;
use App\Http\Traits\Game\UsesEmpireVerification;

class PopulationController extends Controller
{
    use UsesPopulationGrowth, UsesEmpireVerification;

    /**
     * @function answer public xhr request with projected new population and change.
     * @param Request $request
     * @return JsonResponse
     */
    public function projectNewPopulation(Request $request): JsonResponse
    {
        $population = $request->input(['population']);
        $foodPerPop = $request->input(['food']);
        $newPopulation = $this->calculateNewPopulation($population, $foodPerPop);
        return response()->json([
            'newPopulation' => $newPopulation,
            'change' => round($newPopulation - $population, 8)
        ]);
    }

    /**
     * @function handle change food consumption xhr request
     * @param Request $request
     * @return JsonResponse
     */
    public function changeFoodConsumption(Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $input = $request->input();
        $planet = Planet::find($input['planetId']);

        if (!$this->playerOwnsPlanet($player, $planet)) {
            return response()
                ->json(['error' => __('game.empire.errors.planet.owner')], 419);
        }
        if (!$this->foodConsumptionValid($input['foodConsumption'])) {
            return response()
                ->json(['error' => __('game.empire.errors.planet.between', [
                    'min' => config('rules.planets.food.min'),
                    'max' => config('rules.planets.food.max')
                ])], 419);
        }
        if (!$this->verifyPlanetHasPopulation($planet)) {
            return response()
                ->json(['error' => __('game.empire.errors.planet.noPopulation')], 419);
        }

        $planet->food_consumption = $input['foodConsumption'];
        $planet->save();

        return response()->json([], 200);
    }
}
