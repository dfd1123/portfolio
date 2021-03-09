<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $table = 'tlca_order';
	
	protected $fillable = [
        'product_id', 'seller_id', 'uid', 'order_state', 'how_pay', 'total_price', 'sales_price', 'payment_price', 'order_cancel', 'pay_cancel_infor',
    ];
	
	public function product(){
		return $this->belongsTo('TLCfund\Product','product_id');
	}
	
    public function delivery(){
		return $this->belongsTo('TLCfund\Delivery','id','order_id');
	}
	
	public function deposit_pay(){
		return $this->belongsTo('TLCfund\Deposit_pay','id');
	}
	
	public function user(){
		return $this->belongsTo('TLCfund\User','uid','id');
	}

	public function seller(){
		return $this->belongsTo('TLCfund\User','seller_id','id');
	}
}
