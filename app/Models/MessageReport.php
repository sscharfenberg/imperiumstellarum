<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

/**
 * App\Models\MessageReport
 *
 * @property string $id
 * @property string $game_id
 * @property string $message_id
 * @property string $reporter_id
 * @property string $reportee_id
 * @property string $comment
 * @property int|null $resolved_admin
 * @property string|null $admin_reportee_msg
 * @property string|null $admin_reporter_msg
 * @property int|null $suspension_duration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Message $message
 * @property-read \App\Models\Player $reportee
 * @property-read \App\Models\Player $reporter
 * @method static \Illuminate\Database\Eloquent\Builder|MessageReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageReport whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageReport whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageReport whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageReport whereReporteeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageReport whereReporterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageReport whereResolvedAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageReport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MessageReport extends Model
{

    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'message_reports';

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
        'reporter_id',
        'reportee_id',
        'comment',
        'resolved_admin',
        'admin_reportee_msg',
        'admin_reporter_msg',
        'suspension_duration'
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
    public function reporter()
    {
        return $this->belongsTo(Player::class, 'reporter_id');
    }

    /**
     * Get the player that has sent the message
     */
    public function reportee()
    {
        return $this->belongsTo(Player::class, 'reportee_id');
    }

    /**
     * Get the message that this recipient belongs to
     */
    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    /**
     * Get the admin reply message of this report
     */
    public function reply()
    {
        return $this->belongsTo(Message::class, 'admin_comment');
    }

}
