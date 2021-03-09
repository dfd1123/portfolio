<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $timestamps = false;
    protected $table = 'tlca_cart';
	
	protected $fillable = [
        'uid', 'product_id',
    ];
	
	public function product(){
		return $this->belongsTo('TLCfund\Product','product_id');
	}
}
