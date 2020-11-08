<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Http\Traits\UsesUuid;

/**
 * App\Models\Player
 *
 * @property string $id
 * @property int $user_id
 * @property string $game_id
 * @property string $name
 * @property string $ticker
 * @property float $research_priority
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Star[] $stars
 * @property-read int|null $stars_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PlayerResource[] $resources
 * @property-read int|null $resources_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TechLevel[] $techLevels
 * @property-read int|null $tech_levels_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Player newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Player newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Player query()
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereResearchPriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Harvester[] $harvesters
 * @property-read int|null $harvesters_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StorageUpgrade[] $storageUpgrades
 * @property-read int|null $storage_upgrades_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Shipyard[] $shipyards
 * @property-read int|null $shipyards_count
 */
class Player extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'players';

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
        'user_id',
        'game_id',
        'name',
        'ticker',
        'research_priority'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the user that owns the player.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the game that owns the player.
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Get the stars for this player
     */
    public function stars()
    {
        return $this->hasMany('App\Models\Star');
    }

    /**
     * Get the resources for this player
     */
    public function resources()
    {
        return $this->hasMany('App\Models\PlayerResource');
    }

    /**
     * Get the techLevels for this player
     */
    public function techLevels()
    {
        return $this->hasMany('App\Models\TechLevel');
    }

    /**
     * Get the storage upgrades for this player
     */
    public function storageUpgrades()
    {
        return $this->hasMany('App\Models\StorageUpgrade');
    }

    /**
     * Get the harvesters for this player
     */
    public function harvesters()
    {
        return $this->hasMany('App\Models\Harvester');
    }

    /**
     * Get the shipyards for this player
     */
    public function shipyards()
    {
        return $this->hasMany('App\Models\Shipyard');
    }

}
