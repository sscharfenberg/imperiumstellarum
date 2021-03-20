<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Research
 *
 * @property string $id
 * @property string $game_id
 * @property string $player_id
 * @property string $tech_level_id
 * @property string $type
 * @property int $level
 * @property int $remaining
 * @property int $work
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Player $player
 * @property-read \App\Models\TechLevel $techLevel
 * @method static \Illuminate\Database\Eloquent\Builder|Research newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Research newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Research query()
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereTechLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereRemaining($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereWork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereOrder($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereUpdatedAt($value)
 */
class Research extends Model
{
    use UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'research';

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
        'player_id',
        'game_id',
        'tech_level_id',
        'type',
        'level',
        'remaining',
        'work',
        'order',
    ];

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
     * Get the player that owns this shipyard
     */
    public function techLevel()
    {
        return $this->belongsTo('App\Models\TechLevel');
    }
}
