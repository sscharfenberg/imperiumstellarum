<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;


/**
 * App\Models\MessageRecipient
 *
 * @property string $id
 * @property string $game_id
 * @property string $message_id
 * @property string $recipient_id
 * @property bool $read
 * @property bool $deleted
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Message $message
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Eloquent\Builder|MessageRecipient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageRecipient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageRecipient query()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageRecipient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageRecipient whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageRecipient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageRecipient whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageRecipient whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageRecipient whereRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageRecipient whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageRecipient whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MessageRecipient extends Model
{

    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'message_recipients';

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
        'message_id',
        'recipient_id',
        'read',
        'deleted'
    ];

    /**
     * Get the message that this recipient belongs to
     */
    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    /**
     * Get the player that this recipient belongs to
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'recipient_id');
    }

}
