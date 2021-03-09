<?php

namespace App\Http\Controllers\User_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

use DB;

class ResultConfirmController extends Controller
{
    public function __construct(){
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function result_confirm(Request $request){
        $views = view('user_ver.result_confirm.result_confirm');

        $kind = $request->kind;

        switch($kind){
            case 'confirm':
                $views->title = '최종 확인';
                $views->ft_btn_name = '최종 확인';
                break;
            case 're_confirm':
                $views->title = '견적사항';
                $views->ft_btn_name = '수정';
                break;
            default:
                $views->title = '최종 확인';
                $views->ft_btn_name = '최종 확인';
                break;
        }
        
        $views->kind = $kind;
        $views->trd_no = $request->id;

        return $views;
    }

    public function data_load(Request $request){
        $trd_no = $request->trd_no;

        $trade = DB::table('trades')->where('trd_no', $trd_no)->join('business_list', 'trades.bl_no', '=', 'business_list.bl_no')->join('users', 'trades.client_no', '=', 'users.user_no')->first();
        
        $response = array(
            "trade" => $trade,
            "trd_img" => json_decode($trade->trd_img),
            "user_name" => $trade->name,
        );

        return response()->json($response);
    }

    public function data_store(Request $request){
        $trd_no = $request->trd_no;
        $other_remark_txt = $request->other_remark_txt;

        Db::table('trades')->where('trd_no', $trd_no)->update([
            "other_remark_txt" => $other_remark_txt,
        ]);

        $response = array(
            "id" => $trd_no,
        );

        return response()->json($response);
    }
}
