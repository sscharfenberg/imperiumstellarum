<?php
namespace App\Services;

use App\Models\Game;
use App\Models\Planet;
use App\Models\Star;
use Illuminate\Support\Str;

class SeedGameService {

    /**
     * @function get a random type - spectral type for stars, planet type for planets
     * @param int $owner
     * @param array $conf
     * @return string
     * @throws \Exception
     */
    private function randomType (int $owner, array $conf)
    {
        $found = false;
        $chanceAccumulated = 0;
        $spectral = "";
        $chanceTotal = array_reduce($conf, function($carry, $type) use ($owner) {
            $carry += $owner === 1 ? $type['chance'] : $type['chanceHome'];
            return $carry;
        });
        $rolled = random_int(1, $chanceTotal);
        foreach($conf as $key=>$type) {
            $chanceAccumulated += $owner === 1 ? $type['chance'] : $type['chanceHome'];
            if ($chanceAccumulated >= $rolled && !$found) {
                $found = true;
                $spectral = $key;
            }
        }
        return $spectral;
    }


    /**
     * @function create a random star name
     * @throws \Exception
     */
    private function randomStarName ()
    {
        $starName = "";
        $length = random_int(5, 7);
        $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        while (strlen($starName) < $length) {
            $newLetter = $letters[random_int(0, strlen($letters) - 1)];
            $starName = $starName.$newLetter;
            $starName = strlen($starName) === 3 ? $starName."-" : $starName;
        }
        return $starName;
    }


    /**
     * @function determines the number of planets for a star
     * @param string $spectral
     * @param bool $player
     * @return int
     * @throws \Exception
     */
    private function getNumPlanets (string $spectral, bool $player)
    {
        $rules = config('rules.planets.num.'. ($player ? 'player' : 'npc'));
        $planetsMod = config('rules.stars.types.'.$spectral.'.planetsMod');
        $min = $rules['min'];
        $max = $rules['max'];
        return random_int($min, $max) + $planetsMod;
    }


    /**
     * @function create a random planet
     * @param string $gameId
     * @param string $starId
     * @param int $orbitalIndex
     * @param $isPlayer
     * @return array
     * @throws \Exception
     */
    private function randomPlanet (string $gameId, string $starId, int $orbitalIndex, $isPlayer)
    {
        $planetType = $this->randomType(($isPlayer ? 2 : 1), config('rules.planets.types'));
        $slotRules = config('rules.planets.types.'.$planetType.'.resourceSlots');
        $resources = [];
        foreach ($slotRules as $key => $slot) {
            $max = $slot['max'];
            $chance = $slot['chance'];
            $rolled = random_int(1, 100);
            $slots = 0;
            while ($rolled < $chance && $slots < $max) {
                $chance -= $rolled;
                $rolled = random_int(1, 100);
                $slots++;
            }
            if ($slots > 0) {
                $resources[] = [
                    'resourceType' => $key,
                    'slots' => $slots,
                    'value' => (random_int($slot['potential'][0] * 100, $slot['potential'][1] * 100) / 100)
                ];
            }
        }
        return [
            'id' => Str::uuid(),
            'game_id' => $gameId,
            'star_id' => $starId,
            'type' => $planetType,
            'orbital_index' => $orbitalIndex,
            'resources' => json_encode($resources),
            'population' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }


    /**
     * @function handle game seeding
     * @param Game $game
     * @throws \Exception
     */
    public function seedStars (Game $game)
    {
        $points = json_decode($game->map);
        $stars = array_map(function($point) use ($game) {
            return [
                'id' => Str::uuid(),
                'game_id' => $game->id,
                'coord_x' => $point->x,
                'coord_y' => $point->y,
                'home_system' => $point->type === 2,
                'spectral' => $this->randomType($point->type, config('rules.stars.types')),
                'name' => $this->randomStarName(),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }, $points);
        foreach($stars as $star) {
            Star::insert($star);
        }
    }


    /**
     * @function seed planets
     * @param Game $game
     * @throws \Exception
     */
    public function seedPlanets (Game $game)
    {
        $stars = $game->stars()->get();
        $planets = [];
        foreach ($stars as $star) {
            $numPlanets = $this->getNumPlanets($star->spectral, $star->home_system);
            for ($i = 0; $i < $numPlanets; $i++) {
                $planets[] = $this->randomPlanet($game->id, $star->id, $i, $star->home_system);
            }
        };
        foreach($planets as $planet) {
            Planet::insert($planet);
        }
    }

}
