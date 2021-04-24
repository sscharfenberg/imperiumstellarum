<?php

namespace App\Actions\Turn;

use App\Models\Fleet;
use App\Models\Game;
use App\Models\Ship;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;


class ProcessShipRegen
{


    /**
     * @function natural shield regeneration
     * @param Game $game
     * @param string $turnSlug
     * @return void
     */
    private function regenerateShields (Game $game, string $turnSlug)
    {
        $ships = Ship::where('game_id', '=', $game->id)
            ->whereRaw('hp_shields_current <> hp_shields_max')
            ->get();
        Log::channel('turn')->notice("$turnSlug ".$ships->count()." ships have damaged shields.");
        // loop ships that do not have max shields
        $ships->each(function ($ship) use ($turnSlug) {
            $oldShields = $ship->hp_shields_current;
            // we need to round up, otherwise some ships would never regen shields
            $add = ceil($ship->hp_shields_max * config('rules.ships.regen.shields'));
            $ship->hp_shields_current += $add;
            if ($ship->hp_shields_current > $ship->hp_shields_max) {
                $ship->hp_shields_current = $ship->hp_shields_max;
            }
            $regenerated = $ship->hp_shields_current - $oldShields;
            $ship->save();
            Log::channel('turn')->notice(
                "$turnSlug ship #$ship->id '$ship->name' regenerated $regenerated"
                ." hp of shields, new shield hp=$ship->hp_shields_current/$ship->hp_shields_max"
            );
        });
    }


    /**
     * @function repair ships that are at the player's owns stars with a shipyard
     * @param Game $game
     * @param string $turnSlug
     */
    private function repairShips (Game $game, string $turnSlug)
    {
        $ships = Ship::where('game_id', '=', $game->id)
            ->whereRaw('hp_armour_current <> hp_armour_max')
            ->orWhereRaw('hp_structure_current <> hp_structure_max')
            ->get();
        Log::channel('turn')->notice("$turnSlug ".$ships->count()." ships have armour/structure damage.");
        // get the unique fleetIds of the ships
        $fleetIds = $ships->map(function ($ship) {
            return $ship->fleet_id;
        });
        // get the database objects of the fleets, but only if it is located at a star that the player owns
        $fleets = Fleet::where('game_id', '=', $game->id)
            ->whereIn('id', $fleetIds)
            ->whereNotNull('star_id')
            ->whereHas('star', function (Builder $query) {
                $query->where('player_id', '=', DB::raw('fleets.player_id'));
            })
            ->get();
        // filter the fleets, so only fleets at stars with a shipyard are left.
        $validFleetIds = $fleets->filter(function ($fleet) {
            return count($fleet->star->shipyards) > 0;
        })->map( function ($fleet) {
            return $fleet->id;
        });
        // loop the ships, check if they are in a valid fleet, then repair
        $ships->each(function($ship) use ($validFleetIds, $turnSlug) {
            // is the ship's fleet in validFleetIds?
            if ($validFleetIds->contains($ship->fleet_id)) {
                // repair armour?
                if ($ship->hp_armour_current !== $ship->hp_armour_max) {
                    $oldArmour = $ship->hp_armour_current;
                    $add = ceil($ship->hp_armour_max * config('rules.ships.regen.armour'));
                    $ship->hp_armour_current += $add;
                    if ($ship->hp_armour_current > $ship->hp_armour_max) {
                        $ship->hp_armour_current = $ship->hp_armour_max;
                    }
                    $regeneratedArmour = $ship->hp_armour_current - $oldArmour;
                    Log::channel('turn')->notice(
                        "$turnSlug ship #$ship->id '$ship->name' repaired $regeneratedArmour"
                        ." hp of armour, new armour hp=$ship->hp_armour_current/$ship->hp_armour_max"
                    );
                }
                // repair structure?
                if ($ship->hp_structure_current !== $ship->hp_structure_max) {
                    $oldStructure = $ship->hp_structure_current;
                    $add = ceil($ship->hp_structure_max * config('rules.ships.regen.structure'));
                    $ship->hp_structure_current += $add;
                    if ($ship->hp_structure_current > $ship->hp_structure_max) {
                        $ship->hp_structure_current = $ship->hp_structure_max;
                    }
                    $regeneratedStructure = $ship->hp_structure_current - $oldStructure;
                    Log::channel('turn')->notice(
                        "$turnSlug ship #$ship->id '$ship->name' repaired $regeneratedStructure"
                        ." hp of structure, new structure hp=$ship->hp_structure_current/$ship->hp_structure_max"
                    );
                }
                // save the ship!
                $ship->save();
            } else {
                Log::channel('turn')->notice(
                    "$turnSlug ship #$ship->id '$ship->name' could not be repaired, "
                    ."since it's fleet is not located at a player-owned star with shipyard"
                );
            }
        });
    }


    /**
     * @function build ships
     * @param Game $game
     * @param string $turnSlug
     * @throws Exception
     * @return void
     */
    public function handle(Game $game, string $turnSlug)
    {
        // natural shields regen
        $this->regenerateShields($game, $turnSlug);
        // repair ships if they are at a star with shipyard
        $this->repairShips($game, $turnSlug);
    }


}
