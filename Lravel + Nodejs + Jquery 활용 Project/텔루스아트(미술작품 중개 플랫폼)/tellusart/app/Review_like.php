<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Review_like extends Model
{
    protected $table = 'tlca_review_like';
	
	protected $fillable = [
        'uid', 'review_id', 'recomend', 'unrecomend',
    ];
}
