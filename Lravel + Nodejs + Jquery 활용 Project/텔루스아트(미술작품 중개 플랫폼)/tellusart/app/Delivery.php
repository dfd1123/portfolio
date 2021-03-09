<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    public $timestamps = false;
    protected $table = 'tlca_delivery';
	
	protected $fillable = [
        'order_id', 'getter_name', 'getter_mobile', 'getter_email', 'user_addr1', 'user_addr2', 'user_extra_addr',
        'post_num', 'delivery_ask', 'delivery_company', 'send_post_num', 'delivery_date', 'delivery_result', 
    ];
	
	public function order(){
		return $this->belongsTo('TLCfund\Order','order_id');
	}
}
