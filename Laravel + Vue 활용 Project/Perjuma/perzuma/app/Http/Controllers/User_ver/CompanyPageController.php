<?php

namespace App\Http\Controllers\User_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

use DB;

class CompanyPageController extends Controller
{
    public function __construct(){
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function company_page(Request $request, $kind){

 
        $agent_no = $request->agent_no;
        $trd_no = $request->trd_no;

        if($kind === 'estimate_view'){
            $views = view('user_ver.company_page.company_page');


            if($trd_no != null){
                $agent = DB::table('agent_info')->join('agent_trades_bidding', 'agent_info.agent_no', '=', 'agent_trades_bidding.agt_no')->where('agent_no',$agent_no)->where('trd_no',$trd_no)->first();
                
                if($agent == NULL){
                    return redirect('/user_ver');
                }

                $trade = DB::table('trades')
                        ->where('trd_no',$trd_no)
                        ->first();

                $views->ft_btn_yn = 1;
                $views->state = $trade->state;
                //dd($trade->agent_no.' / '.$agent_no);
                if($trade->agent_no == $agent_no){
                    if($trade->state < 4){
                        $views->ft_btn_name = '계약대기';
                    }else if($trade->state == 4){
                        $views->ft_btn_name = '시공 완료 신청';
                    }else{
                        $views->ft_btn_name = '계약완료';
                    }
                }else{
                    if($trade->state > 1){
                        $views->ft_btn_yn = 0;
                    }else{
                        $views->ft_btn_yn = 1;
                    }
                    $views->ft_btn_name = '계약하기';
                }
                
            }else{
                $agent = DB::table('agent_info')
                        ->where('agent_no',$agent_no)
                        ->first();
                
                $views->ft_btn_yn = 0;
            }

            

            if(!isset($agent->agent_profile_img)){
                $agent_profile_img = '/default_profile.png';
            }else{
                $agent_profile_img = json_decode($agent->agent_profile_img);
                $agent_profile_img = $agent_profile_img->profile_img;
            }

            $views->agent = $agent;
            //dd($agent);
            $views->agent_profile_img = $agent_profile_img;


        }else if($kind === 'review_view'){
            $views = view('user_ver.company_page.company_review');


            $agent = DB::table('agent_info')
                        ->where('agent_no',$agent_no)
                        ->first();
        

            $views->agent = $agent;
        }

        $reviews = DB::table('review')
                        ->join('users', 'users.user_no', '=', 'review.client_no')
                        ->where('agent_no',$agent_no)
                        ->orderBy('review.reg_dt','asc')
                        ->get();

        $views->reviews = $reviews;
        $views->title = '견적확인';
        $views->kind = $kind;
        $views->agent_no = $agent_no;
        $views->trd_no = $trd_no;

        return $views;
    }

    public function estimate_view_data_load(Request $request){
        $agent_no = $request->agent_no;
        $trd_no = $request->trd_no;

        $agent = DB::table('agent_info')
                ->join('agent_trades_bidding', 'agent_info.agent_no', '=', 'agent_trades_bidding.agt_no')
                ->where('agent_no',$agent_no)
                ->where('trd_no',$trd_no)
                ->first();
        
        if($agent->agt_others == null){
            $agt_others = null;
        }else{
            $agt_others = str_replace('\r\n','<br />', $agent->agt_others);
        }

        $response = array(
            "agent" => $agent,
            "agt_others" => $agt_others,
        );

        return response()->json($response);
    }

    public function review_view_data_load(Request $request){
        $agent_no = $request->agent_no;
        $trd_no = $request->trd_no;

        $offset = 10;

        $agent = DB::table('agent_info')
                ->where('agent_no',$agent_no)
                ->first();

        $reviews = DB::table('review')
                    ->join('users', 'users.user_no', '=', 'review.client_no')
                    ->where('agent_no',$agent_no)
                    ->orderBy('rv_no','desc')
                    ->get();

        if($trd_no != NULL){
            $trade = DB::table('trades')->where('trd_no', $trd_no)->first();
            if($trade->agent_no == $agent_no && $trade->state == 5){
                if($trade->client_no == auth()->user()->user_no){
                    $write_yn = 1;
                }else{
                    $write_yn = 0;
                }
            }else{
                $write_yn = 0;
            }
        }else{
            $write_yn = 0;
        }

        $response = array(
            "agent" => $agent,
            "reviews" => $reviews,
            "reviews_cnt" => count($reviews),
            "offset" => $offset,
            "write_yn" => $write_yn,
            "auth_no" => auth()->user()->user_no,
        );

        return response()->json($response);
    }

    public function construction_contract(Request $request){
        $agent_no = $request->agent_no;
        $trd_no = $request->trd_no;
        $req = $request->req;

        if($req == 'construct_req'){
            $status = DB::table('trades')
                    ->where('trd_no', $trd_no)
                    ->update([
                        "agent_no" => $agent_no,
                        "state" => 2,
                    ]);
        }else if($req == 'complete_req'){
            $status = DB::table('agent_info')
                        ->where('agent_no', $agent_no) 
                        ->increment('agent_construction_cnt', 1);

            if($status){
                $status = DB::table('trades')
                            ->where('trd_no', $trd_no)
                            ->update([
                                "agent_no" => $agent_no,
                                "state" => 5,
                            ]);
            }
        }


        $response = array(
            "status" => $status,
        );

        return response()->json($response);
    }
}
