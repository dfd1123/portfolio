<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemOptions extends Model
{
    protected $primaryKey = 'op_id';
    //
    public function user()
    {
        return $this->belongsTo('App\Items', 'item_id');
    }
}
