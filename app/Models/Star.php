<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Http\Traits\UsesUuid;

class Star extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stars';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var int $coord_x
     */
    private $coord_x;

    /**
     * @var int $coord_y
     */
    private $coord_y;

    /**
     * @var boolean $home_system
     */
    private $home_system;

    /**
     * @var string $spectral
     */
    private $spectral;

    /**
     * @var string $name
     */
    private $name;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_id',
        'owner_id',
        'coord_x',
        'coord_y',
        'home_system',
        'spectral',
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'home_system' => 'boolean',
    ];

    /**
     * Get the game that this star belongs to
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Get the owner of this star
     */
    public function owner()
    {
        return $this->belongsTo('App\Models\Player');
    }

    /**
     * Get the owner of this star
     */
    public function planets()
    {
        return $this->hasMany('App\Models\Planet');
    }

}
