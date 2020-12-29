<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\UsesUuid;

class Ship extends Model
{

    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ships';

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
        'fleet_id',
        'name',
        'dmg_plasma',
        'dmg_railgun',
        'dmg_missile',
        'dmg_laser',
        'hp_armour_max',
        'hp_armour_current',
        'hp_shields_max',
        'hp_shields_current',
        'hp_structure_max',
        'hp_structure_current',
        'ftl',
        'colony',
        'acceleration'
    ];

    /**
     * Get the game that the construction contract belongs to
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Get the player that owns the construction contract
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }

}
