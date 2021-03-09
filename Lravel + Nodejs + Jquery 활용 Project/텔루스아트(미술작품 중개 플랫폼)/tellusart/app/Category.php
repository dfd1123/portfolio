<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;
    protected $table = 'tlca_category';
	
	protected $fillable = [
        'ca_name','ca_sm_name', 'ca_discript', 'ca_icon', 'ca_use',
    ];
	
	public function products(){
		return $this->hasMany('Product','ca_id');
	}
}
