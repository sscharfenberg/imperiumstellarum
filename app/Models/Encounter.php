<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

/**
 * App\Models\Encounter
 *
 * @property string $id
 * @property string $game_id
 * @property string $turn_id
 * @property string $star_id
 * @property \Illuminate\Support\Carbon $processed_at
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Star $star
 * @property-read \App\Models\Turn $turn
 * @method static \Illuminate\Database\Eloquent\Builder|Encounter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Encounter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Encounter query()
 * @method static \Illuminate\Database\Eloquent\Builder|Encounter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Encounter whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Encounter whereTurnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Encounter whereStarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Encounter whereProcessedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EncounterTurn[] $encounterTurns
 * @property-read int|null $encounter_turns_count
 * @property-read \App\Models\Turn $gameTurn
 */
class Encounter extends Model
{
    use UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'encounters';

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
        'id',
        'game_id',
        'turn_id',
        'star_id',
        'processed_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'processed_at' => 'datetime'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'processed_at'
    ];

    /**
     * Get the game that the encounter belongs to
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    /**
     * Get the star where the encounter took place
     */
    public function star()
    {
        return $this->belongsTo(Star::class, 'star_id');
    }

    /**
     * Get the turn where the encounter happened
     */
    public function gameTurn()
    {
        return $this->belongsTo(Turn::class, 'turn_id');
    }

    /**
     * Get the encounters for this game
     */
    public function encounterTurns()
    {
        return $this->hasMany(EncounterTurn::class, 'encounter_id', 'id');
    }

}
