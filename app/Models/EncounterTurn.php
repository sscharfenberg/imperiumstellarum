<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EncounterTurn
 *
 * @property string $id
 * @property string $game_id
 * @property string $encounter_id
 * @property int $turn
 * @property array $attacker
 * @property array $defender
 * @property array $damage
 * @property-read \App\Models\Encounter $encounter
 * @property-read \App\Models\Game $game
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterTurn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterTurn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterTurn query()
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterTurn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterTurn whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterTurn whereEncounterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterTurn whereTurn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterTurn whereAttacker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterTurn whereDefender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncounterTurn whereDamage($value)
 * @mixin \Eloquent
 */
class EncounterTurn extends Model
{
    use UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'encounter_turns';

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
        'turn',
        'attacker',
        'defender',
        'damage',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'attacker' => 'array',
        'defender' => 'array',
        'damage' => 'array',
    ];

    /**
     * Get the game that the encounter belongs to
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    /**
     * Get the encounter that the encounter_turn belongs to
     */
    public function encounter()
    {
        return $this->belongsTo(Encounter::class, 'encounter_id');
    }
}
