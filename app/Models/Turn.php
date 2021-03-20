<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Turn
 *
 * @property string $id
 * @property string $game_id
 * @property int $number
 * @property \Illuminate\Support\Carbon $due
 * @property \Illuminate\Support\Carbon|null $processed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @method static \Illuminate\Database\Eloquent\Builder|Turn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Turn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Turn query()
 * @method static \Illuminate\Database\Eloquent\Builder|Turn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Turn whereDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Turn whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Turn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Turn whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Turn whereProcessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Turn whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConstructionContract[] $constructionContracts
 * @property-read int|null $construction_contracts_count
 */
class Turn extends Model
{
    use UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'turns';

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
        'number',
        'due',
        'processed',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'due' => 'datetime',
        'processed' => 'datetime'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'due', 'processed'
    ];

    /**
     * Get the game that this turn belongs to.
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

}
