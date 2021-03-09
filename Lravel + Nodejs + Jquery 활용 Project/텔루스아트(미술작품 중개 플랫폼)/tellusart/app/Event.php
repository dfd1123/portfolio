<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'tlca_event';
	
	protected $fillable = [
        'title', 'body', 'start_time', 'end_time', 'hit', 'like', 'file1', 'pc_banner', 'mobile_banner',
    ];
}
