<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    public $timestamps = false;
    protected $table = 'tlca_user_addresses';
	
	protected $fillable = [
        'user_id', 'user_email', 'address_tlc',
    ];
	
	public function user(){
		return $this->hasOne('TLCfund\User','user_id');
	}
}
