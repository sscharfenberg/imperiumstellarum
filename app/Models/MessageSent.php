<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

/**
 * App\Models\MessageSent
 *
 * @property string $id
 * @property string $game_id
 * @property string $player_id
 * @property string|null $message_id
 * @property string $body
 * @property string $subject
 * @property int $read
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Player $player
 * @property-read \App\Models\Message|null $repliesTo
 * @method static \Illuminate\Database\Eloquent\Builder|MessageSent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageSent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageSent query()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageSent whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageSent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageSent whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageSent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageSent whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageSent wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageSent whereRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageSent whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageSent whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageSent whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property array $recipient_ids
 * @method static \Illuminate\Database\Eloquent\Builder|MessageSent whereRecipientIds($value)
 */
class MessageSent extends Model
{

    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'messages_sent';

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
        'message_id',
        'body',
        'subject',
        'recipient_ids'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'recipient_ids' => 'array',
    ];

    /**
     * Get the game that the message belongs to
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game', 'game_id');
    }

    /**
     * Get the player that owns the message (sender)
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    /**
     * Get the message that this message replies to
     */
    public function repliesTo()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

}
