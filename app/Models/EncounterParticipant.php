<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

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
