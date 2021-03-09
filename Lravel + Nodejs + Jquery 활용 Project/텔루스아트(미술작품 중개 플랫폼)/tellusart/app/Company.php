<?php

namespace TLCfund;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public $timestamps = false;
    protected $table = 'tlca_company';
	
	protected $fillable = [
        'service_name', 'company_name', 'ceo_name', 'company_email', 'business_number', 'tellsell_number'
        , 'phone_num', 'fax_num', 'address', 'infor_admin',
    ];
}
