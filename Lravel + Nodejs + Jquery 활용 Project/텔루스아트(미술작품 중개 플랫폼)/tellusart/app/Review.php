<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'tlca_review';
	
	protected $fillable = [
        'art_id', 'writer_id', 'writer_name', 'profile_img', 'unickname', 'review_name',
         'review_body', 'recomend', 'unrecomend', 'rating',
    ];
	
	public function user(){
		return $this->belongsTo('TLCfund\User','writer_id');
	}
	
	public function product(){
		return $this->belongsTo('TLCfund\Product', 'art_id', 'id');
	}
}
