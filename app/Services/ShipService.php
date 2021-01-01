<?php
namespace App\Services;

use App\Models\Blueprint;
use Illuminate\Support\Facades\Log;
use Exception;

class ShipService {

    /**
     * @var array
     */
    private $shipScaffolding = [
        'game_id' => '',
        'player_id' => '',
        'fleet_id' => '',
        'hull_type' => '',
        'dmg_plasma' => 0,
        'dmg_railgun' => 0,
        'dmg_missile' => 0,
        'dmg_laser' => 0,
        'hp_armour' => 0,
        'hp_shields' => 0,
        'hp_structure' => 0,
        'ftl' => false,
        'colony' => false,
        'acceleration' => 1
    ];

    /**
     * @var string[]
     */
    private $offensiveModules = [
        'plasma',
        'railgun',
        'missile',
        'laser'
    ];

    /**
     * @var string[]
     */
    private $defensiveModules = [
        'armour',
        'shields'
    ];

    /**
     * @function select a random ship name
     * @return string
     * @throws Exception
     */
    public function randomShipName(): string
    {
        $names = config('names');
        try {
            $index = random_int(0, count($names) - 1);
        } catch(Exception $e) {
            Log::error('random_int exception: '.$e->getMessage());
            $index = rand(0, count($names) - 1);
        }
        return config('names')[$index];
    }


    /**
     * @function calculate ship base stats for constructionContract caching
     * @param Blueprint $blueprint
     * @return array
     */
    public function calculateShipStats (Blueprint $blueprint): array
    {
        $ship = $this->shipScaffolding;
        $modules = explode("  ", $blueprint->modules);
        $hullType = $blueprint->hull_type;
        $techLevels = $blueprint->techLevels;
        $hullRules = config('rules.ships.hullTypes.'.$blueprint->hull_type.'.baseHp');
        $tlFactor = config('rules.tech.tlFactor');
        $mods = config('rules.modules');

        // apply meta data
        $ship['game_id'] = $blueprint->game_id;
        $ship['player_id'] = $blueprint->player_id;
        $ship['fleet_id'] = null;
        $ship['hull_type'] = $hullType;

        // apply base hitpoints for hull type
        $ship['hp_structure'] = $hullRules['structure'];
        $ship['hp_armour'] = $hullRules['armour'];
        $ship['hp_shields'] = $hullRules['shields'];

        // loop blueprint modules and process them
        foreach($modules as $mod) {
            // get the rules for the module
            $modRules = collect(config('rules.modules'))
                ->where('hullType', $hullType)
                ->where('techType', $mod)
                ->first();
            // defensive module: increase HP
            if ($modRules && $modRules['moduleType'] === 'defensive') {
                $ship['hp_'.$mod] += $modRules['baseHp'];
            }
            // offensive module: increase damage
            if ($modRules && $modRules['moduleType'] === 'offensive') {
                $ship['dmg_'.$mod] += $modRules['baseDmg'];
            }
            if ($modRules && $modRules['techType'] === 'ftl') {
                $ship['ftl'] = true;
            }
            if ($modRules && $modRules['techType'] === 'colony') {
                $ship['colony'] = true;
            }
        }

        // adjust hp values for blueprint techLevels
        foreach($this->defensiveModules as $area) {
            $tl = $techLevels->where('type', $area)->first();
            if ($tl->level > 0) {
                $ship['hp_'.$area] =
                    intval(round($ship['hp_'.$area] * (1 + ($tlFactor * $tl->level) / 100)));
            }
        }
        // adjust dmg values for blueprint techLevels
        foreach($this->offensiveModules as $area) {
            $tl = $techLevels->where('type', $area)->first();
            if ($tl->level > 0) {
                $ship['dmg_'.$area] =
                    intval(round($ship['dmg_'.$area] * (1 + ($tlFactor * $tl->level) / 100)));
            }
        }

        // calculate acceleration
        $ship['acceleration'] = config('rules.ships.hullTypes.'.$hullType)['baseAcceleration'];
        foreach($modules as $mod) {
            $modRules = collect(config('rules.modules'))
                ->where('hullType', $hullType)
                ->where('techType', $mod)
                ->first();
            if($modRules && isset($modRules['acceleration'])) {
                $ship['acceleration'] += $modRules['acceleration'];
            }
        }

        return $ship;
    }

}
