<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Batting extends Model
{
    protected $table = 'tlca_batting';
	
	protected $fillable = [
        'art_id', 'uid', 'user_id', 'unickname', 'batting_price', 'batting_status', 'get_price', 'start_time', 'end_time', 
    ];
	
	public function product(){
		return $this->belongsTo('TLCfund\Product','art_id');
	}
	
	public function user(){
		return $this->belongsTo('TLCfund\User','uid');
	}

}
