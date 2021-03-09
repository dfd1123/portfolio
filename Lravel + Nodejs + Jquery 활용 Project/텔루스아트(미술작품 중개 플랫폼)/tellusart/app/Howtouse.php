<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Howtouse extends Model
{
    protected $table = 'tlca_howtouse';
	
	protected $fillable = [
        'title', 'pc_img1', 'pc_img2', 'pc_img3', 'mb_img1', 'mb_img2', 'mb_img3',
    ];
}
