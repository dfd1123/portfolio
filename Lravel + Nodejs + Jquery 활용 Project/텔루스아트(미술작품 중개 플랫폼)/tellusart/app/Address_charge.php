<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Address_charge extends Model
{
    protected $table = 'tlca_user_charge';
	
	protected $fillable = [
        'user_id', 'amount_krw', 'amount_tlc', 'created_at', 
    ];
	
	public function user(){
		return $this->belongsTo('TLCfund\User','user_id');
	}
}
