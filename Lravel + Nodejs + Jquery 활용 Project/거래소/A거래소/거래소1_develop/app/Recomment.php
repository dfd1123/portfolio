<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recomment extends Model
{
    protected $connection = 'mysql_sub';
    protected $table = 're_comment';
    public $timestamps = false;
	
	protected $fillable = [
        'comment_id', 'writer_id', 'writer_nickname', 'comment', 'file', 'image', 'recomend', 'unrecomend', 'recomend_uid', 'unrecomend_uid',
        'created_at', 'updated_at',
    ];
}
