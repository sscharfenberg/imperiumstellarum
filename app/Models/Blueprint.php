<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

/**
 * App\Models\Blueprint
 *
 * @property string $hull_type
 * @property string $modules
 * @property string $name
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Player $player
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint query()
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereHullType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereModules($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereName($value)
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

}
