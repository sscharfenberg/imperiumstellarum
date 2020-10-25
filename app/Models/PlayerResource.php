<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PlayerResource
 *
 * @property string $id
 * @property string $player_id
 * @property string $resource_type
 * @property int $storage
 * @property int $storage_level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerResource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerResource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerResource query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerResource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerResource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerResource wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerResource whereResourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerResource whereStorage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerResource whereStorageLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerResource whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PlayerResource extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'player_resources';

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
        'resource_type',
        'storage',
        'storage_level',
    ];

    /**
     * Get the game that owns the player.
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }
}
