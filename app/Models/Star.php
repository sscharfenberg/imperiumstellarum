<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Http\Traits\UsesUuid;

/**
 * App\Models\Star
 *
 * @property string $id
 * @property string $game_id
 * @property string|null $player_id
 * @property int $coord_x
 * @property int $coord_y
 * @property bool $home_system
 * @property string $spectral
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Player|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Planet[] $planets
 * @property-read int|null $planets_count
 * @method static \Illuminate\Database\Eloquent\Builder|Star newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Star newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Star query()
 * @method static \Illuminate\Database\Eloquent\Builder|Star whereCoordX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Star whereCoordY($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Star whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Star whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Star whereHomeSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Star whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Star whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Star wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Star whereSpectral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Star whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FleetMovement[] $fleetMovements
 * @property-read int|null $fleet_movements_count
 */
class Star extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stars';

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
        'game_id',
        'player_id',
        'coord_x',
        'coord_y',
        'home_system',
        'spectral',
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'home_system' => 'boolean',
    ];

    /**
     * Get the game that this star belongs to
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Get the owner of this star
     */
    public function owner()
    {
        return $this->belongsTo('App\Models\Player');
    }

    /**
     * Get the owner of this star
     */
    public function planets()
    {
        return $this->hasMany('App\Models\Planet');
    }

    /**
     * Get the fleet movements to this star
     */
    public function fleetMovements()
    {
        return $this->hasMany('App\Models\FleetMovement');
    }

}
