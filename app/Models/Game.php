<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Http\Traits\UsesUuid;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Game
 *
 * @property string $id
 * @property int $number
 * @property bool $active
 * @property bool $can_enlist
 * @property bool $processing
 * @property int $turn_duration
 * @property int $max_players
 * @property int $dimensions
 * @property bool $finished
 * @property string|null $map
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game query()
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereCanEnlist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereMap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereMaxPlayers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereProcessing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereFinished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereTurnDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Player[] $players
 * @property-read int|null $players_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Star[] $stars
 * @property-read int|null $stars_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Turn[] $turns
 * @property-read int|null $turns_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Harvester[] $harvesters
 * @property-read int|null $harvesters_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Planet[] $planets
 * @property-read int|null $planets_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StorageUpgrade[] $storageUpgrades
 * @property-read int|null $storage_upgrades_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Shipyard[] $shipyards
 * @property-read int|null $shipyards_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Research[] $researches
 * @property-read int|null $researches_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Blueprint[] $blueprints
 * @property-read int|null $blueprints_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BlueprintTechLevel[] $blueprintTechLevels
 * @property-read int|null $blueprint_tech_levels_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConstructionContract[] $constructionContracts
 * @property-read int|null $construction_contracts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ship[] $ships
 * @property-read int|null $ships_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Fleet[] $fleets
 * @property-read int|null $fleets_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FleetMovement[] $fleetMovements
 * @property-read int|null $fleet_movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PlayerRelation[] $playerRelations
 * @property-read int|null $player_relations_count
 */
class Game extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'games';

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
        'dimensions',
        'start_date',
        'turn_duration',
        'number',
        'active',
        'can_enlist',
        'processing',
        'finished',
        'map'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime',
        'active' => 'boolean',
        'can_enlist' => 'boolean',
        'processing' => 'boolean',
        'finished' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_date'
    ];

    /**
     * Get the players for this game
     */
    public function players()
    {
        return $this->hasMany('App\Models\Player');
    }

    /**
     * Get the stars for this game
     */
    public function stars()
    {
        return $this->hasMany('App\Models\Star');
    }

    /**
     * Get the planets for this game
     */
    public function planets()
    {
        return $this->hasMany('App\Models\Planet');
    }


    /**
     * Get the turns for this game
     */
    public function turns()
    {
        return $this->hasMany('App\Models\Turn');
    }

    /**
     * Get the storageUpgrades for this game
     */
    public function storageUpgrades()
    {
        return $this->hasMany('App\Models\storageUpgrade');
    }

    /**
     * Get the harvesters for this game
     */
    public function harvesters()
    {
        return $this->hasMany('App\Models\Harvester');
    }

    /**
     * Get the shipyards for this game
     */
    public function shipyards()
    {
        return $this->hasMany('App\Models\Shipyard');
    }

    /**
     * Get the research orders for this game
     */
    public function researches()
    {
        return $this->hasMany('App\Models\Research');
    }

    /**
     * Get the blueprints for this game
     */
    public function blueprints()
    {
        return $this->hasMany('App\Models\Blueprint');
    }

    /**
     * Get the blueprint tech levels for this game
     */
    public function blueprintTechLevels()
    {
        return $this->hasMany('App\Models\BlueprintTechLevel');
    }

    /**
     * Get the construction contracts for this game
     */
    public function constructionContracts()
    {
        return $this->hasMany('App\Models\ConstructionContract');
    }

    /**
     * Get the ships for this game
     */
    public function ships()
    {
        return $this->hasMany('App\Models\Ship');
    }

    /**
     * Get the fleets for this game
     */
    public function fleets()
    {
        return $this->hasMany('App\Models\Fleet');
    }

    /**
     * Get the fleets for this game
     */
    public function fleetMovements()
    {
        return $this->hasMany('App\Models\FleetMovement');
    }

    /**
     * Get the fleets for this game
     */
    public function playerRelations()
    {
        return $this->hasMany('App\Models\PlayerRelation');
    }

}
