<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var string $password
     */
    private $password;

    /**
     * @var string $locale
     */
    private $locale;

    /**
     * @var boolean $game_mail_optin
     */
    private $game_mail_optin;

    /**
     * @var string $selected_player
     */
    private $selected_player;

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
