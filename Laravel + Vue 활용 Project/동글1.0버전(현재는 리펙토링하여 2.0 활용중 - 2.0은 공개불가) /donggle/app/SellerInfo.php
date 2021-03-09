<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SellerInfo extends Model
{
    protected $table = 'seller_infor';
    protected $primaryKey = 'store_id';
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\User', 'uid', 'id');
    }
}
