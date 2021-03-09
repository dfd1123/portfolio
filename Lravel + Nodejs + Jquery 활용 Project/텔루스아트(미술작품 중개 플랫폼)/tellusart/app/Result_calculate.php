<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Result_calculate extends Model
{
    protected $table = 'tlca_result_calculate';
	
	protected $fillable = [
        'order_id', 'product_name', 'seller_name', 'seller_email', 'seller_phone', 'bank_name', 'bank_holder', 'bank_number', 'sale_price', 'fee',
        'result_price', 'complete',
    ];
}
