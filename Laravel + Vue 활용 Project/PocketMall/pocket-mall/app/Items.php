<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    //
    protected $primaryKey = 'item_id';

    public function options()
    {
        return $this->hasMany('App\ItemOptions', 'item_id');
    }
}
