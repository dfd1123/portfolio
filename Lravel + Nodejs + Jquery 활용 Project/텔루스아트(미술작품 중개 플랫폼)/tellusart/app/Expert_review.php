<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Expert_review extends Model
{
    protected $table = 'tlca_expert_review';
	
	protected $fillable = [
        'uid', 'art_id', 'profile_img', 'uname', 'review_title', 'review_body', 'recomend', 'unrecomend', 'rating',
    ];

    public function user(){
		return $this->belongsTo('TLCfund\User','writer_id');
	}
	
	public function product(){
		return $this->belongsTo('TLCfund\Product', 'art_id', 'id');
	}
}
