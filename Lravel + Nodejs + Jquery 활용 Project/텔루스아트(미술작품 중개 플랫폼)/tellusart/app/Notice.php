<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $table = 'tlca_notice';
	
	protected $fillable = [
        'title', 'body', 'file1', 'hit'
    ];
}
