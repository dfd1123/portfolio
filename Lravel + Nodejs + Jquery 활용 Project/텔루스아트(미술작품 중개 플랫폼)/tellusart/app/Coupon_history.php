<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Coupon_history extends Model
{
    public $timestamps = false;
	protected $table = 'tlca_coupon_history';
	
	public function coupon(){
		return $this->belongsTo('App\Coupon','id');
	}
}
