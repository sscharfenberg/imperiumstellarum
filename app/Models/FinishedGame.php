<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;


/**
 * App\Models\FinishedGame
 *
 * @property string $id
 * @property int $number
 * @property int $dimensions
 * @property int $turns
 * @property string|null $winner_id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FinishedGameParticipant|null $winner
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGame newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGame newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGame query()
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGame whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGame whereDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGame whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGame whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGame whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGame whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGame whereTurns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGame whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinishedGame whereWinnerId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FinishedGameParticipant[] $participants
 * @property-read int|null $participants_count
 */
class FinishedGame extends Model
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
        'number',
        'dimensions',
        'turns',
        'winner_id',
        'start_date',
        'end_date'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_date',
        'end_date',
    ];

    /**
     * Get the winner for this finished game
     */
    public function winner()
    {
        return $this->hasOne(FinishedGameParticipant::class);
    }

    /**
     * Get the winner for this finished game
     */
    public function participants()
    {
        return $this->hasMany(FinishedGameParticipant::class);
    }

}
