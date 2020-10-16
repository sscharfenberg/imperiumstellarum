<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Http\Traits\UsesUuid;

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
     * @var string $name
     */
    private $name;

    /**
     * @var string $ticker
     */
    private $ticker;

    /**
     * @var array $research_priority
     */
    private $research_priority;

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
     * Get the stores for this player
     */
    public function stores()
    {
        return $this->hasMany('App\Models\Store');
    }

    /**
     * Get the techLevels for this player
     */
    public function techLevels()
    {
        return $this->hasMany('App\Models\TechLevel');
    }
}
