<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    public $timestamps = false;
    protected $table = 'tlca_slide';
	
	protected $fillable = [
        'slide_file', 'slide_info'
    ];
}
