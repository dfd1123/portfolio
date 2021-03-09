<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'user_trd_comment';

    protected $primaryKey = 'ucc_no';
    protected $dateFormat = 'Y-m-d H:i:s+'; //postgres dateFormat

    public $timestamps = false;

    
    protected $fillable = [
        'ucc_aes', 'client_no', 'agent_no', 'ucc_title', 'ucc_comment', 'ucc_imgs', 'reg_dt', 'trd_no', 'confirm', 'ucc_files',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}
