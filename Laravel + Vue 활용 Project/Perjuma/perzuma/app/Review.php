<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'review';

    protected $primaryKey = 'rv_no';
    protected $dateFormat = 'Y-m-d H:i:s+'; //postgres dateFormat

    public $timestamps = false;

    
    protected $fillable = [
        'rv_title', 'rv_content', 'rv_imgs', 'reg_dt', 'rv_point', 'client_no', 'agent_no', 'ctrt_no', 'state',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

}
