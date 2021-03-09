<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Coupon_user extends Model
{
    public $timestamps = false;
	protected $table = 'tlca_user_coupon';
	
	public function coupon(){
		return $this->belongsTo('App\Coupon','id');
	}
}
