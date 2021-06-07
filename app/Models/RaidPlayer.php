<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

/**
 * App\Models\RaidPlayer
 *
 * @property string $id
 * @property string $game_id
 * @property string $raid_id
 * @property string $player_id
 * @property bool $raider
 * @property int $energy
 * @property int $minerals
 * @property int $food
 * @property int $research
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Player $player
 * @property-read \App\Models\Raid $raid
 * @method static \Illuminate\Database\Eloquent\Builder|RaidPlayer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RaidPlayer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RaidPlayer query()
 * @method static \Illuminate\Database\Eloquent\Builder|RaidPlayer whereId()
 * @method static \Illuminate\Database\Eloquent\Builder|RaidPlayer whereGameId()
 * @method static \Illuminate\Database\Eloquent\Builder|RaidPlayer whereRaidId()
 * @method static \Illuminate\Database\Eloquent\Builder|RaidPlayer wherePlayerId()
 * @method static \Illuminate\Database\Eloquent\Builder|RaidPlayer whereRaider()
 * @method static \Illuminate\Database\Eloquent\Builder|RaidPlayer whereEnergy()
 * @method static \Illuminate\Database\Eloquent\Builder|RaidPlayer whereMinerals()
 * @method static \Illuminate\Database\Eloquent\Builder|RaidPlayer whereFood()
 * @method static \Illuminate\Database\Eloquent\Builder|RaidPlayer whereResearch()
 * @mixin \Eloquent
 */
class RaidPlayer extends Model
{
    use UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'raid_players';

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
        'raid_id',
        'player_id',
        'raider',
        'energy',
        'minerals',
        'food',
        'research',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'raider' => 'bool'
    ];

    /**
     * Get the game that this raidPlayer belongs to
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    /**
     * Get the raid that this raidPlayer belongs to
     */
    public function raid()
    {
        return $this->belongsTo(Raid::class, 'raid_id');
    }

    /**
     * Get the player that this raidPlayer belongs to
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

}
