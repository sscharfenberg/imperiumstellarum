<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Raid
 *
 * @property string $id
 * @property string $game_id
 * @property string|null $star_owner_id
 * @property string $star_id
 * @property int $start_turn
 * @property int|null $end_turn
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Star $star
 * @property-read \App\Models\Player $starOwner
 * @method static \Illuminate\Database\Eloquent\Builder|Raid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Raid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Raid query()
 * @method static \Illuminate\Database\Eloquent\Builder|Raid whereid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Raid whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Raid whereStarOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Raid whereStarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Raid whereStartTurn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Raid whereEndTurn($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RaidPlayer[] $players
 * @property-read int|null $players_count
 */
class Raid extends Model
{
    use UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'raids';

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
        'star_id',
        'start_turn',
        'end_turn'
    ];

    /**
     * Get the game that this raid belongs to
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    /**
     * Get the game that this raid belongs to
     */
    public function starOwner()
    {
        return $this->belongsTo(Player::class, 'star_owner_id');
    }

    /**
     * Get the game that this raid belongs to
     */
    public function star()
    {
        return $this->belongsTo(Star::class, 'star_id');
    }

    /**
     * Get the game that this raid belongs to
     */
    public function players()
    {
        return $this->hasMany(RaidPlayer::class, 'raid_id', 'id');
    }
}
