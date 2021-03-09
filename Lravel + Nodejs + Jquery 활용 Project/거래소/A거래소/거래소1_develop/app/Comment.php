<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $connection = 'mysql_sub';
    protected $table = 'comment';
    public $timestamps = false;
	
	protected $fillable = [
        'writer_id', 'writer_nickname', 'board_table', 'board_id', 'comment', 'file', 'image', 'recomend', 'unrecomend', 'recomend_uid', 'unrecomend_uid',
        'created_at', 'updated_at',
    ];

    public function re_comments(){
		return $this->hasMany('App\Recomment', 'comment_id', 'id');
	}
}
