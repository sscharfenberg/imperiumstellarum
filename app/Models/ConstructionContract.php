<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ConstructionContract
 *
 * @property string $id
 * @property string $blueprint_id
 * @property string $shipyard_id
 * @property int $amount
 * @property string $hull_type
 * @property int $amount_left
 * @property int $turns_per_ship
 * @property int $turns_left
 * @property int $costs_minerals
 * @property int $costs_energy
 * @property float $costs_population
 * @property array $cached_ship
 * @property string $game_id
 * @property boolean $hold_resources
 * @property boolean $hold_population
 * @property string $player_id
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
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereBlueprintId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereCostsEnergy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereCostsMinerals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereCostsPopulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereHullType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereHoldResources($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereHoldPopulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstructionContract whereShipyardId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Blueprint $blueprint
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Player $player
 * @property-read \App\Models\Shipyard $shipyard
 * @property-read \App\Models\Planet $planet
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Turn|null $turnCreated
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
        'turn_id',
        'hull_type',
        'amount',
        'amount_left',
        'turns_per_ship',
        'turns_left',
        'costs_minerals',
        'costs_energy',
        'costs_population',
        'cached_ship',
        'hold_resources',
        'hold_population'
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
    public function game(): BelongsTo
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Get the player that owns the construction contract
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo('App\Models\Player');
    }

    /**
     * Get the blueprint that the construction contract uses
     */
    public function blueprint(): BelongsTo
    {
        return $this->belongsTo('App\Models\Blueprint');
    }

    /**
     * Get the shipyard where the construction contract is executed
     */
    public function shipyard(): BelongsTo
    {
        return $this->belongsTo('App\Models\Shipyard');
    }

    /**
     * Get the planet where the ships are delivered to
     */
    public function planet(): BelongsTo
    {
        return $this->belongsTo('App\Models\Planet');
    }

}
