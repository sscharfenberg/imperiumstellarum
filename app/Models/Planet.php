<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Http\Traits\UsesUuid;

/**
 * App\Models\Planet
 *
 * @property string $id
 * @property string $game_id
 * @property string $star_id
 * @property string $type
 * @property int $orbital_index
 * @property array|null $resources
 * @property float $population
 * @property float $food_consumption
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Star $star
 * @method static \Illuminate\Database\Eloquent\Builder|Planet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Planet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Planet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereFoodConsumption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereOrbitalIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet wherePopulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereResources($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereStarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Planet whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Harvester[] $harvesters
 * @property-read int|null $harvesters_count
 */
class Planet extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'planets';

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
        'type',
        'orbital_index',
        'resources',
        'population',
        'food_consumption',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'resources' => 'array',
        'population' => 'float',
        'food_consumption' => 'float',
    ];

    /**
     * Get the game that this star belongs to
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Get the star that this planet orbits
     */
    public function star()
    {
        return $this->belongsTo('App\Models\Star');
    }

    /**
     * Get the harvesters for this planet
     */
    public function harvesters()
    {
        return $this->hasMany('App\Models\Harvester');
    }

}
