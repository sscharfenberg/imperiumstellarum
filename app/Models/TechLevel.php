<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TechLevel
 *
 * @property string $id
 * @property string $player_id
 * @property string $type
 * @property int $level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Eloquent\Builder|TechLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TechLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TechLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|TechLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TechLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TechLevel whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TechLevel wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TechLevel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TechLevel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TechLevel extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tech_levels';

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
        'type',
        'level',
    ];

    /**
     * Get the player that owns the tech level.
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }


}
