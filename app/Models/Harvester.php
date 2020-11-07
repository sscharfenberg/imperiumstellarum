<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Harvester
 *
 * @property string $id
 * @property string $planet_id
 * @property string $game_id
 * @property string $player_id
 * @property string $resource_type
 * @property float $production
 * @property int $until_complete
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Planet $planet
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Eloquent\Builder|Harvester newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Harvester newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Harvester query()
 * @method static \Illuminate\Database\Eloquent\Builder|Harvester whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Harvester whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Harvester whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Harvester wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Harvester wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Harvester whereResourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Harvester whereProduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Harvester whereUntilComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Harvester whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Harvester extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'harvesters';

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
        'planet_id',
        'player_id',
        'game_id',
        'resource_type',
        'production',
        'until_complete',
    ];

    /**
     * Get the planet where the harvester is installed
     */
    public function planet()
    {
        return $this->belongsTo('App\Models\Planet');
    }

    /**
     * Get the game that the harvester belongs to
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Get the player that owns this harvester
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }

}
