<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * @var string $resource_type
     */
    private $resource_type;

    /**
     * @var int $storage
     */
    private $storage;

    /**
     * @var int $storage_level
     */
    private $storage_level;

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
