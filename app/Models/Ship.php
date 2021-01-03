<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

/**
 * App\Models\Ship
 *
 * @property string $id
 * @property string $game_id
 * @property string $player_id
 * @property string|null $fleet_id
 * @property string|null $shipyard_id
 * @property string $hull_type
 * @property string $name
 * @property int $dmg_plasma
 * @property int $dmg_railgun
 * @property int $dmg_missile
 * @property int $dmg_laser
 * @property int $hp_armour_max
 * @property int $hp_armour_current
 * @property int $hp_shields_max
 * @property int $hp_shields_current
 * @property int $hp_structure_max
 * @property int $hp_structure_current
 * @property int $ftl
 * @property int $colony
 * @property int $acceleration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Eloquent\Builder|Ship newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ship newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ship query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereAcceleration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereColony($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereDmgLaser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereDmgMissile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereDmgPlasma($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereDmgRailgun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereFleetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereFtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereHpArmourCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereHpArmourMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereHpShieldsCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereHpShieldsMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereHpStructureCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereHpStructureMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereHullType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Shipyard $shipyard
 */
class Ship extends Model
{

    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ships';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'player_id',
        'game_id',
        'fleet_id',
        'shipyard_id',
        'hull_type',
        'name',
        'dmg_plasma',
        'dmg_railgun',
        'dmg_missile',
        'dmg_laser',
        'hp_armour_max',
        'hp_armour_current',
        'hp_shields_max',
        'hp_shields_current',
        'hp_structure_max',
        'hp_structure_current',
        'ftl',
        'colony',
        'acceleration'
    ];

    /**
     * Get the game that the construction contract belongs to
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Get the player that owns the construction contract
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }

    // TODO: belongsTo Fleet

    /**
     * Get the shipyard that this ship is assigned to
     */
    public function shipyard()
    {
        return $this->belongsTo('App\Models\Shipyard');
    }

}
