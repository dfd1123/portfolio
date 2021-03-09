<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Deposit_pay extends Model
{
	public $timestamps = false;
    protected $table = 'tlca_deposit_pay';
	
	protected $fillable = [
        'id', 'bank', 'bil_kind', 'individual_kind', 'mobile_number', 'bilcard_number', 'business_number',
        'user_bank_name', 'user_bank_number',
    ];
	
}
