<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'token',
    ];

    function generateToken()
    {
        $this->token = sha1($this->username);
        $this->save();
    }

    function resetToken()
    {
        $this->token = null;
        $this->save();
    }

    static function get() {
        $bearer = request()->header('Authorization');
        $bearer = str_replace('Bearer ', '', $bearer);
        return User::whereToken($bearer)->first();
    }
}
