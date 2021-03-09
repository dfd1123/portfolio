<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
	public $timestamps = false;
    protected $table = 'tlca_video';
	
	protected $fillable = [
        'title', 'video_link', 'use_video',
    ];
}
