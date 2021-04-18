<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EncounterParticipant
 *
 * @property string $id
 * @property string $game_id
 * @property string $encounter_id
 * @property string $player_id
 * @property int $read
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Encounter $encounter
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterParticipant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterParticipant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterParticipant query()
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterParticipant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterParticipant whereEncounterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterParticipant whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterParticipant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterParticipant wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterParticipant whereRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterParticipant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EncounterParticipant extends Model
{

    use UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'encounter_participants';

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
        'encounter_id',
        'player_id',
        'read',
    ];

    /**
     * Get the game that the encounter participant belongs to
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    /**
     * Get the encounter that the participant belongs to
     */
    public function encounter()
    {
        return $this->belongsTo(Encounter::class, 'encounter_id');
    }

    /**
     * Get the player that the participant belongs to
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

}
