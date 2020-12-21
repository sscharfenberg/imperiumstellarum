<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

/**
 * App\Models\BlueprintTechLevel
 *
 * @property string $id
 * @property string $type
 * @property int $level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Blueprint $blueprint
 * @property-read \App\Models\Game $game
 * @method static \Illuminate\Database\Eloquent\Builder|BlueprintTechLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlueprintTechLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlueprintTechLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint whereBlueprintId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blueprint whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BlueprintTechLevel extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blueprint_tech_levels';

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
        'blueprint_id',
        'type',
        'level'
    ];

    /**
     * Get the game that the blueprint belongs to
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Get the blueprint that this tech level belongs to
     */
    public function blueprint()
    {
        return $this->belongsTo('App\Models\Blueprint');
    }
}
