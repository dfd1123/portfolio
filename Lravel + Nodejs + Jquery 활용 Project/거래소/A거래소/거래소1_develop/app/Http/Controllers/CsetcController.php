<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

use Carbon;
use Auth;
use Secure;
use DB;

class CsetcController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }

    public function show($page){
        $views = view(session('theme').'.'.$this->device.'.cs_etc.'.$page);

        switch($page){
            case 'limit_guide':
                break;
            case 'privacy_guide' :
                $views->private_infor_term =  DB::connection('mysql_sub')
                                                ->table('btc_term_service')
                                                ->where('market_type', session('market_type'))
                                                ->value('private_infor_term_'.config('app.country'));
                break;
            case 'service_guide' :
                $views->use_term =  DB::connection('mysql_sub')
                                    ->table('btc_term_service')
                                    ->where('market_type', session('market_type'))
                                    ->value('use_term_'.config('app.country'));
                break;
            case 'intro' :
                break;
            case 'guide' :
                break;
            case 'et_cetera' :
                $views->setting =  DB::table('btc_settings')
                                ->where('id', 19)
                                ->first();
                break;
        }
        /*
        if($page == 'limit_guide'){
            
        }else if($page == 'privacy_guide'){
            $views->private_infor_term =  DB::connection('mysql_sub')
                                            ->table('btc_term_service')
                                            ->where('market_type', session('market_type'))
                                            ->value('private_infor_term_'.config('app.country'));
        }else if($page == 'service_guide'){
            $views->use_term =  DB::connection('mysql_sub')
                                ->table('btc_term_service')
                                ->where('market_type', session('market_type'))
                                ->value('use_term_'.config('app.country'));
        }
        */

        return $views;
    }
}
