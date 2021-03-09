<?php

namespace TLCfund;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;

class User extends Authenticatable
{
    use Notifiable;
	
	const ADMIN_TYPE = 3;
	const DEFAULT_TYPE = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'email', 'name', 'password', 'nickname', 'mobile_number', 'post_num', 'addr1', 'addr2', 'extra_addr', 'level', 'ad_agree', 'register_kind', 'count_newnotice'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	public function isAdmin()    {        
	    return $this->level === self::ADMIN_TYPE;    
	}
	
	public function products(){
		return $this->hasMany('TLCfund\Product','seller_id','id');
	}
	
	public function address(){
		return $this->hasOne('TLCfund\Address','user_id','id');
	}
	
	public function address_charge(){
		return $this->hasMany('TLCfund\Address_charge','user_id','id');
    }
    
    public function account(){
		return $this->hasOne('TLCfund\Account','uid','id');
	}
}
