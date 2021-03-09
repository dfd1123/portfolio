<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Log;
use Auth;

class IcoAjaxController extends Controller
{
    public function ico_list_more1(Request $request){
        
        $offset = $request->offset;
        $category = $request->category;
        $str = '';

        if($category == 'all'){
            $icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('active',1)->orderBy('created_at','desc')->offset($offset)->limit(10)->get();
        }else{
            $icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('ico_category',strtoupper($category))->where('active',1)->orderBy('created_at','desc')->offset($offset)->limit(10)->get();
        }

        foreach($icos as $ico){
            $str .= '<div class="list_in_con_outer"><div class="list_in_con">';
            $str .= '<a href="/ico/'.$ico->id.'">';
            
            if($ico->active == 0){
                $str .= '<div class="thumnail waiting">';
            }else if($ico->ico_category == 1 && $ico->active==1){
                $str .= '<div class="thumnail oncoming confirm">';
            }else if ($ico->ico_category == 2 && $ico->active==1){
                $str .= '<div class="thumnail  upcoming confirm">';
            }else if ($ico->ico_category == 3 && $ico->active==1){
                $str .= '<div class="thumnail  end confirm">';
            }else if($ico->ico_category == 4 && $ico->active==1){
                $str .= '<div class="thumnail  soldout confirm">';
            }

            if($ico->ico_thumnail == NULL){
                $str .= '<img src="/storage/image/ico/no_image.jpg" alt="" /><br>';
            }else{
                $str .= '<img src="/storage/image/ico'.$ico->ico_thumnail.'" alt="" /><br>';
            }

            $str .= '</div><div class="infos">';

            $str .= '<p class="hd"><span>'.$ico->ico_symbol.'</span><span>'.$ico->ico_title.'</span></p>';
            $str .= '<p class="info _txt">'.$ico->ico_intro.'</p>';
            $str .= '<p class="info _period">'.date("Y-m-d", strtotime($ico->ico_from)).' ~ '.date("Y-m-d", strtotime($ico->ico_to)).'</p>';
            $str .= '<p class="info _psbcoin"><label>'.$ico->ico_collect.'</label><span>'.$ico->ico_symbol.' ≈ '.$ico->ico_coin_p.' '.$ico->ico_collect.'</span></p>';
            $str .= '<p class="info _minimal"><label>'.__("icoo.minimum_buy").'</label><span>'.$ico->ico_min.' '.$ico->ico_symbol.'</span></p>';
            $str .= '<p class="info text-right _date">'.$ico->w_name.' '.$ico->created_at.'</p>';
            $str .= '</div></a></div></div>';

            $offset++;
        }

        $response = array(
            "str" => $str,
            "offset" => $offset,
        );

        return response()->json($response);
    }

    public function ico_list_more2(Request $request){
        
        $offset = $request->offset;
        $category = $request->category;
        $str = '';

        if($category == 'all'){
            $icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('w_id',Auth::id())->offset($offset)->limit(10)->get();
        }else{
            $icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('w_id',Auth::id())->where('ico_category',strtoupper($category))->offset($offset)->limit(10)->get();
        }

        foreach($icos as $ico){
            $str .= '<div class="list_in_con_outer"><div class="list_in_con">';
            $str .= '<a href="/ico/'.$ico->id.'">';
            
            if($ico->active == 0){
                $str .= '<div class="thumnail waiting">';
            }else if($ico->ico_category == 1 && $ico->active==1){
                $str .= '<div class="thumnail oncoming confirm">';
            }else if ($ico->ico_category == 2 && $ico->active==1){
                $str .= '<div class="thumnail  upcoming confirm">';
            }else if ($ico->ico_category == 3 && $ico->active==1){
                $str .= '<div class="thumnail  end confirm">';
            }else if($ico->ico_category == 4 && $ico->active==1){
                $str .= '<div class="thumnail  soldout confirm">';
            }

            if($ico->ico_thumnail == NULL){
                $str .= '<img src="/storage/image/ico/no_image.jpg" alt="" /><br>';
            }else{
                $str .= '<img src="/storage/image/ico'.$ico->ico_thumnail.'" alt="" /><br>';
            }

            $str .= '</div><div class="infos">';

            $str .= '<p class="hd"><span>'.$ico->ico_symbol.'</span><span>'.$ico->ico_title.'</span></p>';
            $str .= '<p class="info _txt">'.$ico->ico_intro.'</p>';
            $str .= '<p class="info _period">'.date("Y-m-d", strtotime($ico->ico_from)).' ~ '.date("Y-m-d", strtotime($ico->ico_to)).'</p>';
            $str .= '<p class="info _psbcoin"><label>'.$ico->ico_collect.'</label><span>'.$ico->ico_symbol.' ≈ '.$ico->ico_coin_p.' '.$ico->ico_collect.'</span></p>';
            $str .= '<p class="info _minimal"><label>'.__("icoo.minimum_buy").'</label><span>'.$ico->ico_min.' '.$ico->ico_symbol.'</span></p>';
            $str .= '<p class="info text-right _date">'.$ico->w_name.' '.$ico->created_at.'</p>';
            $str .= '</div></a></div></div>';

            $offset++;
        }

        $response = array(
            "str" => $str,
            "offset" => $offset,
        );

        return response()->json($response);
    }

    public function ico_history_more(Request $request){
        
        $offset = $request->offset;
        $str = '';

        $icos = DB::connection('mysql_sub')->table('btc_ico_people')->where('uid',Auth::id())->orderBy('order_time','desc')->offset($offset)->limit(10)->get();

        foreach($icos as $ico){
            $str .= '<li class="con buy">';
            $str .= '<p class="info _date mb-2"><span>'.$ico->order_time.'</span></p>';
            $str .= '<p class="info _coin">'.__('coin_name.'.strtolower($ico->order_coin)).'(<u>'.$ico->order_coin.'</u>)</p>';
            $str .= '<p class="info"><label>'.__('icoo.buy_quantity').'</label><span>'.$ico->buy_amount.'</span><span class="currency"> '.$ico->order_coin.'</span></p>';
            $str .= '<p class="info"><label>'.__('icoo.buy_price').'</label><span>'.$ico->order_price.'</span><span class="currency"> '.$ico->buy_pay.'</span></p>';
            $str .= '<p class="info"><label>'.__('icoo.pay_price').'</label><span>'.$ico->buy_price.'</span><span class="currency"> '.$ico->buy_pay.'</span></p>';
            $str .= '<p class="info"><label>'.__('icoo.total_payable_').'</label><span>'.$ico->buy_amount.'</span><span class="currency"> '.$ico->order_coin.'</span></p>';
            $str .= '</li>';

            $offset++;
        }

        $response = array(
            "str" => $str,
            "offset" => $offset,
        );

        return response()->json($response);
    }
}
