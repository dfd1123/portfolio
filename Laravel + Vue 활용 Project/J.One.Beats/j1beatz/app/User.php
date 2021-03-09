<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_email', 'user_pw', 'user_nick', 'user_name', 'user_mobile', 'stream_start_at', 'state', 'user_agr_email_prom', 'pg_info'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_pw', 'stream_start_at', 'created_at', 'updated_at', 'email_verified_at', 'pg_info'
    ];

    public function username()
    {
        return $this->user_email;
    }

    public function getAuthPassword()
    {
        return $this->user_pw;
    }
}
