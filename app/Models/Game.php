<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Http\Traits\UsesUuid;
use Illuminate\Support\Facades\DB;

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
     * @var int $number
     */
    private $number;
    /**
     * @var boolean $active
     */
    private $active;
    /**
     * @var boolean $can_enlist
     */
    private $can_enlist;
    /**
     * @var boolean $processing
     */
    private $processing;
    /**
     * @var int $turn_duration
     */
    private $turn_duration;
    /**
     * @var int $max_players
     */
    private $max_players;
    /**
     * @var int $dimensions
     */
    private $dimensions;
    /**
     * @var string $start_date
     */
    private $start_date;
    /**
     * @var string $map
     */
    private $map;

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
     * Get the turns for this game
     */
    public function turns()
    {
        return $this->hasMany('App\Models\Turn');
    }

}
