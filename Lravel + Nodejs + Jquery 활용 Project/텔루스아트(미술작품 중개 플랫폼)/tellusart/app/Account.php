<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'users';
	
	protected $fillable = [
        'account_bank', 'account_number', 'account_user'
    ];

}
