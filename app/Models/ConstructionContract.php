<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

/**
 * App\Models\ConstructionContract
 *
 * @property int $amount
 * @property int $amount_left
 * @property int $turns_per_ship
 * @property int $turns_left
 * @property array $cached_ship
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereAmountLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereTurnsPerShip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereTurnsLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereCachedShip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Blueprint $blueprint
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Player $player
 * @property-read \App\Models\Shipyard $shipyard
 */
class ConstructionContract extends Model
{

    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'construction_contracts';

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
        'blueprint_id',
        'shipyard_id',
        'hull_type',
        'amount',
        'amount_left',
        'turns_per_ship',
        'turns_left',
        'costs_minerals',
        'costs_energy',
        'cached_ship'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'cached_ship' => 'array'
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

    /**
     * Get the blueprint that the construction contract uses
     */
    public function blueprint()
    {
        return $this->belongsTo('App\Models\Blueprint');
    }

    /**
     * Get the shipyard where the construction contract is executed
     */
    public function shipyard()
    {
        return $this->belongsTo('App\Models\Shipyard');
    }

    /**
     * Get the planet where the ships are delivered to
     */
    public function planet()
    {
        return $this->belongsTo('App\Models\Planet');
    }

}
