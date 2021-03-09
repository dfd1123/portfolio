<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'tlca_coupon';
	
	public function user_coupons(){
		return $this->hasMany('App\Coupon_user','coupon_id');
	}
	
	public function coupon_historys(){
		return $this->hasMany('App\Coupon_history','coupon_id');
	}
}
