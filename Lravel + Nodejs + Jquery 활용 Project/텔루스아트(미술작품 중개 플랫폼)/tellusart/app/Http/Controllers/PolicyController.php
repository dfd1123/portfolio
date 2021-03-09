<?php

namespace TLCfund\Http\Controllers;

use Illuminate\Http\Request;

use Jenssegers\Agent\Agent;

use DB;

class PolicyController extends Controller
{
    public function __construct()
    {
		$agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }


    public function policy($id){
        $policy =DB::table('tlca_contents')->where('id',$id)->first();

        $views = view($this->device.'.policy.policy');

        $views->policy = $policy;
        $views->title = '서비스정책';

        return $views;
    }
}
