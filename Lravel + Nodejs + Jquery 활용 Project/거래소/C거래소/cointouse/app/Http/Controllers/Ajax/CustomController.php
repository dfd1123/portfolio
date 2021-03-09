<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class CustomController extends Controller
{
    public function notice(Request $request){
        $offset = $request->offset;
        $str = '';

        $notices = DB::connection('mysql_sub')->table('btc_notice_'.config('app.country'))->orderBy('created','desc')->offset($offset)->limit(10)->get();

        foreach($notices as $notice){
            $str .= '<tr><td class="list_tit">';
            $str .= '<a href="/notice/'.$notice->id.'"><span>'.$notice->title.'</span></a>';
            $str .= '</td>';
            $str .= '<td class="date_ymd">'.date("Y-m-d", $notice->created).'</td></tr>';
            $offset++;
        }

        $response = array(
            "str" => $str,
            "offset" => $offset,
        );

        return response()->json($response);
    }

    public function event(Request $request){
        $offset = $request->offset;
        $str = '';
        $today = date('Y-m-d');

        $events = DB::connection('mysql_sub')->table('btc_events')->where('lang', config('app.country'))->where('active', 1)->orderBy('created','desc')->offset($offset)->limit(10)->get();
    
        foreach($events as $event){
            $str .= '<tr>';

            if(strtotime($today) < strtotime($event->start_time)){
                $str .= '<td><b class="red">'. __('event.soon').'</b></td>';
            }else if(strtotime($today) <= strtotime($event->end_time)){
                $str .= '<td><b class="blue">'. __('event.ing').'</b></td>';
            }else if(strtotime($today) > strtotime($event->end_time)){
                $str .= '<td><b>'. __('event.exit').'</b></td>';
            }

            $str .= '<td>'.$event->start_time.' ~ '.$event->end_time.'</td>';

            $str .= '</tr>';

            $str .= '<tr><td colspan="2" class="list_tit">';
            $str .= '<a href="/event/'.$event->id.'"><span>'.$event->title.'</span></a>';
            $str .= '</td></tr>';
            $offset++;
        }

        $response = array(
            "str" => $str,
            "offset" => $offset,
        );

        return response()->json($response);
    }

    public function qna(Request $request){
        $offset = $request->offset;
        $str = '';

        $qnas = DB::connection('mysql_sub')->table('btc_qna')->where('createdby',Auth::user()->username)->orderBy('created','desc')->offset($offset)->limit(10)->get();
    
        foreach($qnas as $qna){
            $str .= '<tr>';
            $str .= '<td><a href="/qna/'.$qna->id.'">'.$qna->title.'</a></td>';
            $str .= '<td class="status">';
            if($qna->answered){
                $str .= '<b class="wait_ans">'.__('support.success_answer').'</b>';
            }else{
                $str .= '<b class="complete_ans">'.__('support.waiting_answer').'</b>';
            }
            $str .= '</td></tr>';

            $offset++;
        }

        $response = array(
            "str" => $str,
            "offset" => $offset,
        );

        return response()->json($response);
    }
}