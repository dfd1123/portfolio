<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'fullname', 'nickname', 'password', 'secret_pin', 'level', 'email', 'email_verified_at',
        'mobile_number', 'status', 'hash', 'wallet', 'ip', 'time_signup', 'time_signin', 'time_activity', 'referral_id',
        'market_type', 'remember_token', 'alarm_email', 'alarm_sms', 'alarm_io_email', 'alarm_io_sms', 'secret_key', 'push_token',
        'register_type', 'company_name', 'ceo_name',  'business_number', 'company_phone_number', 'company_address', 'company_detail_address',
        'recomend_email', 'comunity_status', 'comunity_count', 'comment_count',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'username', 'password', 'remember_token',
    ];
}
