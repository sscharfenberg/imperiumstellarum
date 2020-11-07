<?php

namespace App\Actions\Turn;

use App\Models\Game;
use App\Models\Planet;
use App\Models\Player;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\Game\UsesPopulationGrowth;

class Colonies
{
    use UsesPopulationGrowth;


    /**
     * @function handle population growth
     * @param Game $game
     * @return void
     */
    public function handle(Game $game)
    {
        $planets = Planet::where('game_id', $game->id)
            ->where('population', '>', 0)->get();
        $players = $game->players;

        foreach($planets as $planet) {
            $playerId = $planet->star->player_id;

            if ($playerId) { // star has an owner, subtract food!
                $player = Player::find($playerId);
                $foodResource = $player->resources->filter(function($res) {
                    return $res->resource_type === 'food';
                })->first();
                $consumption = ceil($planet->population * $planet->food_consumption);

                if ($foodResource->storage === 0) {
                    // no food -> set consumption to zero.
                    $planet->food_consumption = 0;
                }

                // set new food resources
                if ($foodResource->storage < $consumption) {
                    // not enough food for this colony. set to 0, use current consumption
                    $planet->food_consumption = round($foodResource->storage / $planet->population, 8);
                    $foodResource->storage = 0;
                } else {
                    // enough resources to feed the colony.
                    $foodResource->storage -= $consumption;
                }

                // calculate new population
                $newPop = $this->calculateNewPopulation(
                    $planet->population, $planet->food_consumption
                );
                Log::notice('Planet '.$planet->id.' population changed '.$planet->population.' => '.$newPop);
                $planet->population = $newPop;

                // save planet and resources.
                $planet->save();
                $foodResource->save();
            } else {
                Log::notice('Planet '.$planet->id.' has no owner, skipping.');
            }

        }
    }


}
