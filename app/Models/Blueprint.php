<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

/**
 * App\Models\Blueprint
 *
 * @property string $id
 * @property string $hull_type
 * @property string $modules
 * @property string $name
 * @property string $game_id
 * @property string $player_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint query()
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint whereHullType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint whereModules($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BlueprintTechLevel[] $blueprintTechLevel
 * @property-read int|null $$blueprintTechLevels_count
 * @mixin \Eloquent
 */
class Blueprint extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blueprints';

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
        'hull_type',
        'modules',
        'name'
    ];

    /**
     * Get the game that the blueprint belongs to
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Get the player that owns this blueprint
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }

    /**
     * Get the tech levels for this blueprint
     */
    public function techLevels()
    {
        return $this->hasMany('App\Models\BlueprintTechLevel');
    }

}
