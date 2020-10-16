<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Http\Traits\UsesUuid;

class Planet extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'planets';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string $type
     */
    private $type;

    /**
     * @var int $orbital_index
     */
    private $orbital_index;

    /**
     * @var string $resources
     */
    private $resources;

    /**
     * @var float $population
     */
    private $population;

    /**
     * @var float $food_consumption
     */
    private $food_consumption;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'orbital_index',
        'resources',
        'population',
        'food_consumption',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'resources' => 'array',
        'population' => 'float',
        'food_consumption' => 'float',
    ];

    /**
     * Get the game that this star belongs to
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Get the star that this planet orbits
     */
    public function star()
    {
        return $this->belongsTo('App\Models\Star');
    }

}
