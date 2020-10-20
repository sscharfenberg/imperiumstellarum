<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Suspension
 *
 * @property string $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $until
 * @property string $issuer_id
 * @property string $issuer_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Suspension newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Suspension newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Suspension query()
 * @method static \Illuminate\Database\Eloquent\Builder|Suspension whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suspension whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suspension whereIssuerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suspension whereIssuerReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suspension whereUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suspension whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suspension whereUserId($value)
 * @mixin \Eloquent
 */
class Suspension extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suspensions';

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
        'user_id', 'until', 'issuer_id', 'issuer_reason'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'until' => 'datetime'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'until',
    ];

    /**
     * Get the user that owns the suspension
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * is the suspension still active?
     */
    public function isActive()
    {
        return $this->until->gte(now());
    }

}
