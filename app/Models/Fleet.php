<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

/**
 * App\Models\Fleet
 *
 * @property string $id
 * @property string $game_id
 * @property string $player_id
 * @property string $star_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Player $player
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ship[] $ships
 * @property-read int|null $ships_count
 * @method static \Illuminate\Database\Eloquent\Builder|Fleet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fleet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fleet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Fleet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fleet whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fleet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fleet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fleet wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fleet whereStarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fleet whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Star $star
 */
class Fleet extends Model
{

    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fleets';

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
        'star_id',
        'name'
    ];

    /**
     * Get the game that the fleet belongs to
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Get the player that owns the fleet
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }

    /**
     * Get the ships assigned to this shipyard
     */
    public function ships()
    {
        return $this->hasMany('App\Models\Ship');
    }

    /**
     * Get the star where the fleet is located
     */
    public function star()
    {
        return $this->belongsTo('App\Models\Star');
    }
}
