<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'tlca_product';
	
	protected $fillable = [
        'title', 'seller_id', 'artist_img', 'artist_name', 'artist_intro', 'artist_career', 'image1', 'image2', 'image3', 'image4', 'image5', 'introduce', 'art_width_size', 'art_height_size',
        'art_date', 'ca_id', 'ca_use', 'cash_price','coin_price', 'batting_yn', 'sell_yn', 'reject_infor', 'coin_batting',
        'start_time', 'end_time', 'batting_status', 'get_hit', 'created_at', 'updated_at', 'block_hash',
	];
	
	public $timestamps = false;
	
	public function reviews(){
		return $this->hasMany('TLCfund\Review','art_id','id');
	}
	
	public function expert_reviews(){
		return $this->hasMany('TLCfund\Expert_review','art_id','id');
	}
	
	public function order(){
		return $this->belongsTo('TLCfund\Order','product_id','id');
	}
	
	public function carts(){
		return $this->hasMany('TLCfund\Cart','id');
	}
	
	public function battings(){
		return $this->hasMany('TLCfund\Batting','art_id','id');
	}
	
	public function batting_arts(){
		return $this->hasMany('TLCfund\Batting_art','id');
	}
	
	public function category(){
		return $this->belongsTo('TLCfund\Category','ca_id');
	}
	
	public function user(){
		return $this->belongsTo('TLCfund\User','seller_id');
	}

}
