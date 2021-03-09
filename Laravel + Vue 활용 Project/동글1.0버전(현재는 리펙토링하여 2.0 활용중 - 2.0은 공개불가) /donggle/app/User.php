<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject; // jwt 미들웨어(Middleware)의 인터페이스
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'nickname', 'mobile_number', 'ad_agree', 'wear_size'
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
    ];

    public function getJWTIdentifier() {  // jwt 미들웨어(Middleware)의 인터페이스를 구현한 부분
        return $this->getKey();
    }

    public function getJWTCustomClaims() {  // jwt의 토큰을 습득하기 위한 함수
        return [
            'name' => $this->name,
            'email' => $this->email
        ];
    }
}
