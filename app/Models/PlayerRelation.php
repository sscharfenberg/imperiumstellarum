<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

/**
 * App\Models\PlayerRelation
 *
 * @property string $id
 * @property string $player_id
 * @property string $game_id
 * @property string $recipient_id
 * @property int $status
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelation wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelation whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelation whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelation whereUpdatedAt($value)
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Player $player
 * @property-read \App\Models\Player $recipient
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PlayerRelation extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'player_relations';

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
        'recipient_id',
        'status'
    ];

    /**
     * Get the game that the playerRelation belongs to
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Get the player that owns the playerRelation
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    /**
     * Get the player that this playerRelation is set to
     */
    public function recipient()
    {
        return $this->belongsTo(Player::class, 'recipient_id');
    }

}
