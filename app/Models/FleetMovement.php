<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

/**
 * App\Models\FleetMovement
 *
 * @property string $id
 * @property string $game_id
 * @property string $player_id
 * @property string $fleet_id
 * @property string $star_id
 * @property int $until_arrival
 * @property-read \App\Models\Fleet $fleet
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Player $player
 * @property-read \App\Models\Star $star
 * @method static \Illuminate\Database\Eloquent\Builder|FleetMovement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FleetMovement whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FleetMovement wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FleetMovement whereFleetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FleetMovement whereStarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FleetMovement whereUntilArrival($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FleetMovement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FleetMovement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FleetMovement query()
 * @mixin \Eloquent
 */
class FleetMovement extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fleet_movements';

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
        'fleet_id',
        'star_id',
        'until_arrival'
    ];

    /**
     * Get the game that the fleetMovement belongs to
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Get the player that owns the fleetMovement
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }

    /**
     * Get the fleet that owns the fleetMovement
     */
    public function fleet()
    {
        return $this->belongsTo('App\Models\Fleet');
    }

    /**
     * Get the destination star of the fleet
     */
    public function star()
    {
        return $this->belongsTo('App\Models\Star');
    }
}
