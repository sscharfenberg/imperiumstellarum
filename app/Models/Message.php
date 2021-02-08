<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

/**
 * App\Models\Message
 *
 * @property string $id
 * @property string $game_id
 * @property string $player_id
 * @property string $sender_id
 * @property string|null $message_id
 * @property string $body
 * @property string $subject
 * @property int $read
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Player $player
 * @property-read Message|null $repliesTo
 * @property-read \Illuminate\Database\Eloquent\Collection|Message[] $replys
 * @property-read int|null $replys_count
 * @property-read \App\Models\Player $sender
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property array $recipient_ids
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereRecipientIds($value)
 */
class Message extends Model
{

    use HasFactory, UsesUuid;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'messages';

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
        'sender_id',
        'message_id',
        'body',
        'subject',
        'read',
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
     * Get the player that owns the message (recipient)
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    /**
     * Get the player that has sent the message
     */
    public function sender()
    {
        return $this->belongsTo(Player::class, 'sender_id');
    }

    /**
     * Get the message that this message replies to
     */
    public function repliesTo()
    {
        return $this->belongsTo(MessageSent::class, 'message_id');
    }

}
