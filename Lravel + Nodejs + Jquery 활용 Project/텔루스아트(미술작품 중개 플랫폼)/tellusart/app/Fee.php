<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
	public $timestamps = false;
    protected $table = 'tlca_fee';
	
	protected $fillable = [
        'product_fee', 'withdraw_fee', 'fee_email'
    ];
	
}
