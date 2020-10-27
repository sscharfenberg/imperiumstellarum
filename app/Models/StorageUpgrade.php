<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StorageUpgrade
 *
 * @property string $id
 * @property string $player_id
 * @property string $resource_type
 * @property int $new_level
 * @property int $until_complete
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Eloquent\Builder|StorageUpgrade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageUpgrade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageUpgrade query()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageUpgrade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageUpgrade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageUpgrade wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageUpgrade whereResourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageUpgrade whereNewLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageUpgrade whereUntilComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageUpgrade whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StorageUpgrade extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'storage_upgrades';

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
        'player_id',
        'resource_type',
        'new_level',
        'until_complete',
    ];

    /**
     * Get the game that owns the player.
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }
}
