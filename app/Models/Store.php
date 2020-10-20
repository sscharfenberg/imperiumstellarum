<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Store
 *
 * @property string $id
 * @property string $player_id
 * @property string $resource_type
 * @property int $storage
 * @property int $storage_level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Eloquent\Builder|Store newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Store newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Store query()
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereResourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereStorage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereStorageLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Store extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stores';

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
