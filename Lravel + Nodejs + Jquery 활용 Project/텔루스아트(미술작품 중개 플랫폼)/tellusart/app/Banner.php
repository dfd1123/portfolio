<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public $timestamps = false;
    protected $table = 'tlca_banner';
	
	protected $fillable = [
        'bn_alt', 'bn_file', 'bn_begin_time', 'bn_end_time',
    ];
}
