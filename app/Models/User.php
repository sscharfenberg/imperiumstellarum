<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $locale
 * @property string $role
 * @property string $password
 * @property bool $game_mail_optin
 * @property int $drawer_open
 * @property string|null $selected_player
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Player[] $players
 * @property-read int|null $players_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Suspension[] $suspensions
 * @property-read int|null $suspensions_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDrawerOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGameMailOptin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSelectedPlayer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //'name', 'email', 'password',
        'id',
        'email',
        'password',
        'locale',
        'game_mail_optin',
        'selected_player'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'role'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'game_mail_optin' => 'boolean'
    ];

    /**
     * Get the players that this user owns.
     */
    public function players()
    {
        return $this->hasMany('App\Models\Player');
    }

    /**
     * Get the players that this user owns.
     */
    public function suspensions()
    {
        return $this->hasMany('App\Models\Suspension')->orderByDesc('until');
    }

    /**
     * @function checks if the user has an active suspension.
     * TODO: make this accessible; currently we can't access this function from relations
     * @return bool
     */
    public function isSuspended() {
        foreach ($this->suspensions as $suspension) {
            if ($suspension->isActive()) return true;
        }
        return false;
    }

    /**
     * helper functions for role permissions
     * @return bool
     */
    public function isAdmin() {
        return $this->role == "admin";
    }
    public function isMod() {
        return $this->role == "mod" || $this->role == "admin";
    }
}
