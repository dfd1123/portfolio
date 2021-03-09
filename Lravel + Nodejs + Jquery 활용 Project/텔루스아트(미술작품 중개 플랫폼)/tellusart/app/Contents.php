<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Contents extends Model
{
    protected $table = 'tlca_contents';
	
	protected $fillable = [
        'title', 'pc_contents', 'mobile_contents',
    ];
}
