<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

/**
 * App\Models\FinishedGameParticipant
 *
 * @property string $id
 * @property int $number
 * @property int $dimensions
 * @property int $turns
 * @property string|null $winner_id
 * @property string $start_date
 * @property string $end_date
 * @property float $population
 * @property int $stars
 * @property array $ships
 * @property array $shipyards
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FinishedGame $game
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant query()
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereTurns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereWinnerId($value)
 * @mixin \Eloquent
 * @property string $game_id
 * @property string $name
 * @property string $ticker
 * @property string $colour
 * @property int $died_turn
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereColour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereDied($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant wherePopulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereShips($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereShipyards($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereStars($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGameParticipant whereTicker($value)
 * @property int $died
 */
class FinishedGameParticipant extends Model
{

    use UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'finished_game_participants';

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
        'name',
        'ticker',
        'colour',
        'died',
        'population',
        'stars',
        'ships',
        'shipyards'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'ships' => 'array',
        'shipyards' => 'array',
    ];

    /**
     * Get the winner for this finished game
     */
    public function game()
    {
        return $this->belongsTo(FinishedGame::class, 'game_id');
    }

}
