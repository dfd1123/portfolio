<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'tlca_faq';
	
	protected $fillable = [
        'question', 'answer',
    ];
}
