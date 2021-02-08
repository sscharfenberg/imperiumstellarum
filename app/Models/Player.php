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
 * @property string $colour
 * @property float $research_priority
 * @property bool $dead
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Player newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Player newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Player query()
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereResearchPriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereColour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereDead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Game $game
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Star[] $stars
 * @property-read int|null $stars_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PlayerResource[] $resources
 * @property-read int|null $resources_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TechLevel[] $techLevels
 * @property-read int|null $tech_levels_count
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Harvester[] $harvesters
 * @property-read int|null $harvesters_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StorageUpgrade[] $storageUpgrades
 * @property-read int|null $storage_upgrades_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Shipyard[] $shipyards
 * @property-read int|null $shipyards_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Research[] $researches
 * @property-read int|null $researches_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Blueprint[] $blueprints
 * @property-read int|null $blueprints_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConstructionContract[] $constructionContracts
 * @property-read int|null $construction_contracts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ship[] $ships
 * @property-read int|null $ships_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Fleet[] $fleets
 * @property-read int|null $fleets_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FleetMovement[] $fleetMovements
 * @property-read int|null $fleet_movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PlayerRelation[] $relations
 * @property-read int|null $relations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PlayerRelation[] $recipientRelations
 * @property-read int|null $recipient_relations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PlayerRelationChange[] $recipientRelationChanges
 * @property-read int|null $recipient_relation_changes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PlayerRelationChange[] $relationChanges
 * @property-read int|null $relation_changes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MessageSent[] $sentMessages
 * @property-read int|null $sent_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $inbox
 * @property-read int|null $inbox_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MessageSent[] $outbox
 * @property-read int|null $outbox_count
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
        'research_priority',
        'dead',
        'colour'
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

    /**
     * Get the research orders for this player
     */
    public function researches()
    {
        return $this->hasMany('App\Models\Research');
    }

    /**
     * Get the blueprints for this player
     */
    public function blueprints()
    {
        return $this->hasMany('App\Models\Blueprint');
    }

    /**
     * Get the construction contracts for this player
     */
    public function constructionContracts()
    {
        return $this->hasMany('App\Models\ConstructionContract');
    }

    /**
     * Get the ships for this player
     */
    public function ships()
    {
        return $this->hasMany('App\Models\Ship');
    }

    /**
     * Get the fleets for this player
     */
    public function fleets()
    {
        return $this->hasMany('App\Models\Fleet');
    }

    /**
     * Get the fleet movements for this player
     */
    public function fleetMovements()
    {
        return $this->hasMany('App\Models\FleetMovement');
    }

    /**
     * Get the fleet movements for this player
     */
    public function relations()
    {
        return $this->hasMany('App\Models\PlayerRelation', 'player_id');
    }

    /**
     * Get the fleet movements for this player
     */
    public function recipientRelations()
    {
        return $this->hasMany('App\Models\PlayerRelation', 'recipient_id');
    }

    /**
     * Get the relation changes for this player
     */
    public function relationChanges()
    {
        return $this->hasMany('App\Models\PlayerRelationChange', 'player_id');
    }

    /**
     * Get the recipient relation changes for this player
     */
    public function recipientRelationChanges()
    {
        return $this->hasMany('App\Models\PlayerRelationChange', 'recipient_id');
    }

    /**
     * Get the inbox messages for this player
     */
    public function inbox()
    {
        return $this->hasMany('App\Models\Message', 'player_id');
    }

    /**
     * Get the outbox messages for this player
     */
    public function outbox()
    {
        return $this->hasMany('App\Models\MessageSent', 'player_id');
    }

}
