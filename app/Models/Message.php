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
 * @property string|null $sender_id
 * @property string|null $message_id
 * @property string $body
 * @property string $subject
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MessageRecipient[] $recipients
 * @property-read int|null $recipients_count
 * @property-read Message|null $repliesTo
 * @property-read \App\Models\Player|null $sender
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @mixin \Eloquent
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
        'sender_id',
        'message_id',
        'body',
        'subject',
        'recipient_ids'
    ];

    /**
     * Get the game that the message belongs to
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game', 'game_id');
    }

    /**
     * Get the player that has sent the message
     */
    public function sender()
    {
        return $this->belongsTo(Player::class, 'sender_id');
    }

    /**
     * Get the recipients of the message
     */
    public function recipients()
    {
        return $this->hasMany(MessageRecipient::class, 'message_id', 'id');
    }

    /**
     * Get the message that this message replies to
     */
    public function repliesTo()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

}
