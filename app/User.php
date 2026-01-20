<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'pseudo',
        'idrole',
        'statut_id',
        'derniere_cnx',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'derniere_cnx' => 'datetime',
        'idrole' => 'integer',
        'statut_id' => 'integer',
    ];

    /**
     * Check if user account is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->statut_id === 1;
    }
}
