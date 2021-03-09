<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Facades\App\Classes\Wallet;
use Facades\App\Classes\Settings;
use Facades\App\Classes\Secure;

use App\User;

use DB;
use Auth;
use Input;

class TosController extends Controller
{
    public function private_info_term($locale)
    {
        $private_info_term = DB::connection('mysql_sub')->table("btc_term_service")->select("private_infor_term_$locale")->first();
        return response()->json($private_info_term->{"private_infor_term_$locale"});
    }

    public function use_term($locale)
    {
        $private_info_term = DB::connection('mysql_sub')->table("btc_term_service")->select("use_term_$locale")->first();
        return response()->json($private_info_term->{"use_term_$locale"});
    }
}