<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
     protected $table = 'tlca_user_transactions';
	
	protected $fillable = [
        'user_id', 'cointype', 'address', 'kind', 'amount', 'txid', 'created_at' 
    ];
}
