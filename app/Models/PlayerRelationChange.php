<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

/**
 * App\Models\PlayerRelationChange
 *
 * @property string $id
 * @property string $player_id
 * @property string $game_id
 * @property string $recipient_id
 * @property int $status
 * @property int $until_done
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Player $player
 * @property-read \App\Models\Player $recipient
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelationChange whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelationChange wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelationChange whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelationChange whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelationChange whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelationChange whereUntilDone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelationChange newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelationChange newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelationChange query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRelation whereUpdatedAt($value)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class PlayerRelationChange extends Model
{

    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'player_relation_changes';

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
        'status',
        'until_done'
    ];

    /**
     * Get the game that the fleet belongs to
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Get the player that owns the relationChange
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    /**
     * Get the player that owns the fleet
     */
    public function recipient()
    {
        return $this->belongsTo(Player::class, 'recipient_id');
    }

}
