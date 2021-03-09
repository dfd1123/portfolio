<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Batting_art extends Model
{
	public $timestamps = false;
    protected $table = 'tlca_batting_art';
	
	protected $fillable = [
        'seller_id', 'art_id', 'ca_id', 'bat_ranking', 'bat_cnt', 'art_name','total_price','total_hit', 'start_time', 'end_time',
    ];
	
	public function product(){
		return $this->belongsTo('TLCfund\Product','art_id');
	}
	
	public function category(){
		return $this->belongsTo('TLCfund\Category','ca_id');
	}
	
	public function user(){
		return $this->belongsTo('TLCfund\User','seller_id');
	}
	
}
