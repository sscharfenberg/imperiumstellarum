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
 */
class FinishedGameParticipant extends Model
{

    use UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'finished_games';

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
        'name',
        'ticker',
        'colour',
        'died',
        'total_population',
        'stars',
        'ships'
    ];

    /**
     * Get the winner for this finished game
     */
    public function game()
    {
        return $this->belongsTo(FinishedGame::class, 'game_id');
    }

}
