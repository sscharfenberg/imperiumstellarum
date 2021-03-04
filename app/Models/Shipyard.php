<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

/**
 * App\Models\Shipyard
 *
 * @property string $id
 * @property string $planet_id
 * @property string $game_id
 * @property string $player_id
 * @property string $type
 * @property int $until_complete
 * @property boolean $notified
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Planet $planet
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Eloquent\Builder|Shipyard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipyard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipyard query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipyard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipyard whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipyard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipyard wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipyard wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipyard whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipyard whereNotified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipyard whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipyard whereUntilComplete($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConstructionContract[] $constructionContract
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ship[] $ships
 * @property-read int|null $ships_count
 */
class Shipyard extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shipyards';

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
        'type',
        'until_complete',
        'notified'
    ];

    /**
     * Get the planet where the shipyard is located
     */
    public function planet()
    {
        return $this->belongsTo('App\Models\Planet');
    }

    /**
     * Get the game that the shipyard belongs to
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Get the player that owns this shipyard
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }

    /**
     * Get the construction contracts for this shipyard
     */
    public function constructionContract()
    {
        return $this->hasOne('App\Models\ConstructionContract');
    }

    /**
     * Get the ships assigned to this shipyard
     */
    public function ships()
    {
        return $this->hasMany('App\Models\Ship');
    }

}
