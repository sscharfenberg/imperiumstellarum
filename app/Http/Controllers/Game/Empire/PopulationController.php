<?php

namespace App\Http\Controllers\Game\Empire;

use App\Http\Controllers\Controller;
use App\Models\Planet;
use App\Models\Player;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PopulationController extends Controller
{

    /**
     * @function calculate new population from "foodConsumption per population"
     * @param float $population
     * @param float $foodPerPop
     * @return float
     */
    public function calculateNewPopulation (float $population, float $foodPerPop)
    {
        $starvingMultiplier = 0.8;
        $newPop = $foodPerPop < 1
            ? $population * (1 + ((log($foodPerPop) * 3) / 100))
            : $population + ((exp($foodPerPop - 3) / $population) / 20);
        $newPop = $foodPerPop < 0.01 ? $population * $starvingMultiplier : $newPop;
        return round($newPop, 8);
    }


    /**
     * @function answer public xhr request with new population and change.
     * @param Request $request
     * @return JsonResponse
     */
    public function newPopulation(Request $request)
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
     * @function check if player owns the star that the planet belongs to
     * @param Player $player
     * @param Planet $planet
     * @return bool
     */
    private function playerOwnsPlanet(Player $player, Planet $planet)
    {
        $playerStar = $player->stars->find($planet->star->id);
        if ($playerStar) return true;
        return false;
    }


    /**
     * @function check if foodConsumption is valid and within bounds
     * @param float $foodConsumption
     * @return bool
     */
    private function consumptionValid(float $foodConsumption)
    {
        if (!is_numeric($foodConsumption)) return false;
        return $foodConsumption >= config('rules.planets.food.min')
            && $foodConsumption <= config('rules.planets.food.max');
    }


    /**
     * @function verify planet has population > 0
     * @param Planet $planet
     * @return bool
     */
    private function planetHasPopulation(Planet $planet)
    {
        return $planet->population > 0;
    }

    /**
     * @function handle change food consumption xhr request
     * @param Request $request
     * @return JsonResponse
     */
    public function changeFoodConsumption(Request $request)
    {
        $player = Player::find(Auth::user()->selected_player);
        $input = $request->input();
        $planet = Planet::find($input['planetId']);

        if (!$this->playerOwnsPlanet($player, $planet)) {
            return response()
                ->json(['error' => __('game.empire.errors.planet.owner')], 419);
        }
        if (!$this->consumptionValid($input['foodConsumption'])) {
            return response()
                ->json(['error' => __('game.empire.errors.planet.between', [
                    'min' => config('rules.planets.food.min'),
                    'max' => config('rules.planets.food.max')
                ])], 419);
        }
        if (!$this->planetHasPopulation($planet)) {
            return response()
                ->json(['error' => __('game.empire.errors.planet.noPopulation')], 419);
        }

        $planet->food_consumption = $input['foodConsumption'];
        $planet->save();

        return response()->json([], 200);
    }
}
