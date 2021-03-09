<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Auth;

class P2pAjaxController extends Controller
{
    public function history_more(Request $request){
        $offset = $request->offset;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $id = Auth::id();
        $str = '';
        
        $p2ps = DB::connection('mysql_sub')->table('btc_p2p')
                ->where('deleted',0)
                ->where('confirm','=',4)
                ->where('state','<>','stop')
                ->whereDate('end','>=',$from_date)
                ->whereDate('end','<=',$to_date)
                ->where(function($query) use ($id){$query->where('s_id',$id)->orWhere('b_id',$id);})
                ->orderBy('end','desc')->offset($offset)->limit(10)->get(); //select

        foreach($p2ps as $p2p){
            if($p2p->type == 'buy'){
                $str .= '<tr class="p2p_type-buy" name="'. __('coin_name.'.$p2p->coin_type).'">';

            }else{
                $str .= '<tr class="p2p_type-sell" name="'. __('coin_name.'.$p2p->coin_type).'">';
            }
            $str .= '<td><p>';
            if($p2p->uid == $p2p->b_id){
                $str .= '<span>'. __("ptop.buy") .'</span>';
            }elseif($p2p->uid == $p2p->s_id ){
                $str .= '<span>'. __("ptop.sell") .'</span>';
            }
            $str .= '</p></td>';

            $str .= '<td class="coin_td"><img class="coin_symbol" src="'.asset('/storage/image/homepage/coin_img/'.$p2p->coin_type).'.png" alt="coin_img"><span class="coin_name">'.__('coin_name.'.$p2p->coin_type).'</span><span class="coin_name_eng">'.$p2p->coin_type.'</span></td>';
            $str .= '<td><p><span>'.number_format($p2p->coin_price).'</span><span class="currency">'.$p2p->country_money.'</span></p></td>';
            $str .= '<td><p><span>'.$p2p->coin_amount.'</span><span class="currency">'.strtoupper($p2p->coin_type).'</span></p></td>';
            $str .= '<td><p><span>'.$p2p->name.'</span></p></td><td><p><span>'.$p2p->end.'</span></p></td></tr>';

            $offset += 1;
        }

        $response = array(
            "str" => $str,
            "offset" => $offset,
        );

        return response()->json($response); 
    }

    public function list_more(Request $request){
        $offset = $request->offset;
        $category = $request->category;
        $srch = $request->srch;
        $type = $request->type;
        $auth = $request->auth;
        $str = '';

        if($type=='buy'){
            if($category == 'all'){
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('type','buy')
                        ->where(function($qry) use ($srch){
                            $qry->where('coin_type','like','%'.$srch.'%')->orWhere('name','like','%'.$srch.'%');
                        })
                        ->offset($offset)->orderBy('confirm','asc')->orderBy('id','desc')->limit(9)->get();
            }else{
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('type','buy')
                        ->where($category,'like','%'.$srch.'%')
                        ->offset($offset)->orderBy('confirm','asc')->orderBy('id','desc')->limit(9)->get();
            }
            
        }else if($type=='sell'){
            if($category == 'all'){
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('type','sell')
                        ->where(function($qry) use ($srch){
                            $qry->where('coin_type','like','%'.$srch.'%')->orWhere('name','like','%'.$srch.'%');
                        })
                        ->offset($offset)->orderBy('confirm','asc')->orderBy('id','desc')->limit(9)->get();
            }else{
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('type','sell')
                        ->where($category,'like','%'.$srch.'%')
                        ->offset($offset)->orderBy('confirm','asc')->orderBy('id','desc')->limit(9)->get();
            }
        }else if($type=='my'){
            if($category == 'all'){
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('uid',Auth::id())
                        ->where(function($qry) use ($srch){
                            $qry->where('coin_type','like','%'.$srch.'%')->orWhere('name','like','%'.$srch.'%');
                        })
                        ->offset($offset)->orderBy('confirm','asc')->orderBy('id','desc')->limit(9)->get();
            }else{
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('uid',Auth::id())
                        ->where($category,'like','%'.$srch.'%')
                        ->offset($offset)->orderBy('confirm','asc')->orderBy('id','desc')->limit(9)->get();
            }
        }else{
            if($category == 'all'){
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)
                        ->where(function($qry) use ($srch){
                            $qry->where('coin_type','like','%'.$srch.'%')->orWhere('name','like','%'.$srch.'%');
                        })
                        ->offset($offset)->orderBy('confirm','asc')->orderBy('id','desc')->limit(9)->get();
                        
            }else{
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)
                        ->where($category,'like','%'.$srch.'%')
                        ->offset($offset)->orderBy('confirm','asc')->orderBy('id','desc')->limit(9)->get();
            }
        }

        foreach($p2ps as $p2p){
            $str .= '<div class="list_in_con"><div class="hd">';
            
            if($p2p->type=='buy'){
                $str .= '<label class="tit buy_tit">';
            }else if($p2p->type=='sell'){
                $str .= '<label class="tit sell_tit" >';
            }

            $str .= __('p2p.'.$p2p->type.'s').__('p2p.'.$p2p->state).'</label><p class="etc_info">';
            $str .= '<span>'.$p2p->name.'</span><span>'.date("Y-m-d",strtotime($p2p->start)).'</span></p>';
            if(Auth::check()){
                if($p2p->uid==Auth::user()->id && $p2p->confirm==0){
                    $str .= '<button class="del_btn" onclick="location.href='.route('p2p_deleted',$p2p->id).'">'.__('ptop.delete').'</button>';
                }
            }
            $str .= '</div><div class="info_1"><img src="'.asset('/storage/image/homepage/coin_img/'.$p2p->coin_type.'.png').'" alt="coin_img" class="coin_symbol">';
            $str .= '<p class="coin_name">'.__('coin_name.'.strtolower($p2p->coin_type)).'</p><span class="coin_name_eng">'.$p2p->coin_type.'</span>';
            if($p2p->type=='sell'){
                $str .= '<span class="have_coin_check" id="c_addr"><a href="'.__('blockexplore.'.$p2p->coin_type).$p2p->s_coin_address.'" target="_blank">'. __('ptop.confirm_possessio').'</a></span>';
            }
            $str .= '</div><div class="info_2"><ul><li class="amt"><label class="mr-2">'. __('ptop.quantity') .'</label><span>'.$p2p->coin_amount.'<span class="currency pl-1">'.$p2p->coin_type.'</span></span></li>';
            $str .= '<li class="prc"><label class="mr-2">'. __('ptop.price') .'</label><span>'.$p2p->coin_price.'<span class="currency pl-1">'.$p2p->country_money.'</span></span></li></ul></div><div class="info_3"><textarea readonly="readonly">'.$p2p->cont.'</textarea></div>';
            
            if($auth){
                if(Auth::user()->status == 2){
                    $str .= '<button class="btn_style not_active_btn stop_user_id_warning" type="button">계정 정지</button> ';
                }else if($p2p->state == 'on'){
                    $str .= '<button class="btn_style apply_btn write_btn p2pApply" data-id="'.$p2p->id.'" type="button">'.__('p2p.'.$p2p->type).__('ptop.apply1').'</button>';
                }else if($p2p->state == 'onProgress'){
                    $str .= '<button class="btn_style not_active_btn" type="button">'.__('ptop.ing').'</button> ';
                }elseif($p2p->state == 'complete'){
                    $str .= ' <button class="btn_style not_active_btn" type="button">'.__('ptop.complete').'</button> ';
                }elseif($p2p->state == 'stop'){
                    $str .= '<button class="btn_style not_active_btn" type="button">'.__('ptop.stop').'</button> ';
                }
            }else{
                if($p2p->state == 'on'){
                    $str .= '<button class="btn_style apply_btn write_btn" type="button" onclick="location.href=\''.route("login").'\'">'.__('p2p.'.$p2p->type).__('ptop.apply1').'</button>';
                }else if($p2p->state == 'onProgress'){
                    $str .= '<button class="btn_style not_active_btn" type="button">'.__('ptop.ing').'</button> ';
                }else if($p2p->state == 'complete'){
                    $str .= ' <button class="btn_style not_active_btn" type="button">'.__('ptop.complete').'</button> ';
                }else if($p2p->state == 'stop'){
                    $str .= '<button class="btn_style not_active_btn" type="button">'.__('ptop.stop').'</button> ';
                }
            }
            
            $str .= '</div>';

            $offset += 1;

        }

        $response = array(
            "str" => $str,
            "offset" => $offset,
        );

        return response()->json($response); 
    }


    public function mobile_history_more(Request $request){
        $offset = $request->offset;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $id = Auth::id();
        $str = '';
        
        $p2ps = DB::connection('mysql_sub')->table('btc_p2p')
                ->where('deleted',0)
                ->where('confirm','=',4)
                ->where('state','<>','stop')
                ->whereDate('end','>=',$from_date)
                ->whereDate('end','<=',$to_date)
                ->where(function($query) use ($id){$query->where('s_id',$id)->orWhere('b_id',$id);})
                ->orderBy('end','desc')->offset($offset)->limit(10)->get(); //select

        foreach($p2ps as $p2p){
            if($p2p->type == 'buy'){
                $str .= '<li class="con buy" name="'. __('coin_name.'.$p2p->coin_type).'/'.$p2p->coin_type.'">';

            }else{
                $str .= '<li class="con sell" name="'. __('coin_name.'.$p2p->coin_type).'/'.$p2p->coin_type.'">';
            }
            $str .= '<p class="info _date mb-2">';
            if($p2p->uid == $p2p->b_id){
                $str .= '<span class="float-right type">'. __("ptop.buy") .'</span>';
            }elseif($p2p->uid == $p2p->s_id ){
                $str .= '<span class="float-right type">'. __("ptop.sell") .'</span>';
            }
            $str .= '</p>';

            $str .= '<p class="info _coin">'.__('coin_name.'.$p2p->coin_type).'(<u>'.$p2p->coin_type.'</u>)</p>';
            
            $str .= '<p class="info">';
            $str .= '<label>'.__('ptop.quantity').'</label><span>'.$p2p->coin_amount.'</span><span class="currency">'.strtoupper($p2p->coin_type).'</span>';
            $str .= '</p>';

            $str .= '<p class="info">';
            $str .= '<label>'.__('ptop.trade_price').'</label><span>'.number_format($p2p->coin_price).'</span><span class="currency">'.$p2p->country_money.'</span>';
            $str .= '</p>';

            $str .= '<p class="info">';
            $str .= '<label>'.__('ptop.selldr_buyer').'</label><span>'.$p2p->name.'</span>';
            $str .= '</p>';

            $str .= '<p class="info">';
            $str .= '<label>'.__('ptop.due_date_trade').'</label><span>'.$p2p->end.'</span>';
            $str .= '</p>';

            $str .= '</li>';

            $offset += 1;
        }

        $response = array(
            "str" => $str,
            "offset" => $offset,
        );

        return response()->json($response); 
    }

    public function mobile_p2p_onprogress(Request $request){
        $offset = $request->offset;
        $type = $request->type;
        $id = Auth::id();

        if($type=='buy'){
            $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('b_id',$id)->where('deleted',0)->where('state','<>','stop')->where('confirm','>',0)->where('confirm','<',4)->offset($offset)->limit(10)->get(); //select
        }elseif($type=='sell'){
            $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('s_id',$id)->where('deleted',0)->where('state','<>','stop')->where('confirm','>',0)->where('confirm','<',4)->offset($offset)->limit(10)->get(); //select
        }else{
            $p2ps = DB::connection('mysql_sub')->table('btc_p2p')
            ->where('deleted',0)
            ->where('state','<>','stop')
            ->where('confirm','>',0)
            ->where('confirm','<',4)
            ->where(function($query) use ($id){$query->where('s_id',$id)->orWhere('b_id',$id);})->offset($offset)->limit(10)->get(); //select
        }

        $str = '';

        foreach($p2ps as $p2p){

            $str .= '<div class="list_in_con_outer"><div class="list_in_con">';
            $str .= '<div class="in_left_con">';
            $str .= '<div class="hd">';
            if($p2p->b_id==Auth::user()->id){
                $str .= '<label class="tit">'. __('ptop.apply_buy') .'</label>';
                $str .= '<span class="etc_info pl-1 pr-1"><u>'. __('ptop.writer') .':</u>'.$p2p->name.'</span>';
                $str .= '<span class="etc_info"><u>'. __('ptop.application_date') .':</u>'.date("Y-m-d",strtotime($p2p->start)).'</span>';
            }else if($p2p->s_id==Auth::user()->id){
                $str .= '<label class="tit">'. __('ptop.apply_sell') .'</label>';
                $str .= '<span class="etc_info pl-1 pr-1"><u>'. __('ptop.writer') .':</u>'.$p2p->name.'</span>';
                $str .= '<span class="etc_info"><u>'. __('ptop.application_date') .':</u>'.date("Y-m-d",strtotime($p2p->start)).'</span>';
            }

            if($p2p->b_id==Auth::user()->id && $p2p->confirm==2){
                $str .= '<p class="text-right mt-2"><a class="write_btn_st coin_in_check" onclick="location.href=\'/p2p_confirm/'.$p2p->id.'\'">'.__('ptop.coin_check_in').'</a></p>';
            }

            $str .= '</div>';
            $str .= '<div class="info_1">';
            $str .= '<img src="/storage/image/homepage/coin_img/'.$p2p->coin_type.'.png" alt="coin_img" class="coin_symbol">';
            $str .= '<p class="coin_name">'.__('coin_name.'.$p2p->coin_type).'</p>';
            $str .= '<span class="coin_name_eng ml-1">'.$p2p->coin_type.'</span>';
            $str .= '<span class="have_coin_check"><a href="'.__('blockexplore.'.$p2p->coin_type).$p2p->s_coin_address.'" target="_blank">'.__('ptop.confirm_possession').'</a></span>';
            $str .= '</div>';
            $str .= '<div class="info_2 bb-dddd"><ul>';
            $str .= '<li class="amt"><label class="mr-2">'.__('ptop.quantity').'</label><span>'.$p2p->coin_amount.'<span class="currency pl-1">'.strtoupper($p2p->coin_type).'</span></span></li>';
            $str .= '<li class="prc"><label class="mr-2">'.__('ptop.price').'</label><span>'.$p2p->coin_price.'<span class="currency pl-1">'.strtoupper($p2p->country_money).'</span></span></li>';
            $str .= '</ul></div>';
            $str .= '</div>';
            $str .= '<div class="in_right_con">';

            if($p2p->b_id==Auth::user()->id){
                $str .= '<ul>';
                if($p2p->confirm == 1){
                    $str .= '<li class="active">';
                }else if($p2p->confirm > 1){
                    $str .= '<li class="active complt">';
                }

                $str .= '<span class="status_icon"></span><span class="status_tit">';
                $str .= '<span class="ing"><span class="currency">'.$p2p->country_money.'</span> '.__('ptop.ptop_ing').'</span>';
                $str .= '<span class="complt"><span class="currency">'.$p2p->country_money.'</span> '.__('ptop.ptop_end').'</span>';
                $str .= '</span>';
                $str .= '</li>';

                if($p2p->confirm == 2){
                    $str .= '<li class="active">';
                }else if($p2p->confirm > 2){
                    $str .= '<li class="active complt">';
                }else{
                    $str .= '<li>';
                }

                $str .= '<span class="status_icon"></span><span class="status_tit">';
                $str .= '<span class="ing"><span class="currency">'.strtoupper($p2p->coin_type).'</span> '.__('ptop.ptop_outing').'</span>';
                $str .= '<span class="complt"><span class="currency">'.strtoupper($p2p->coin_type).'</span> '.__('ptop.ptop_c').'</span>';
                $str .= '</span>';
                $str .= '</li>';

                if($p2p->confirm == 3){
                    $str .= '<li class="active">';
                }else{
                    $str .= '<li>';
                }

                $str .= '<span class="status_icon"></span><span class="status_tit">'.__('ptop.settlement_in_progress').'</span>';
                $str .= '</li>';

                $str .= '</ul>';

                if($p2p->confirm == 1){
                    $str .= '<div class="step_ment active _1">';
                }else{
                    $str .= '<div class="step_ment">';
                }

                $str .= '<p>'.__('ptop.p_to_p_sentence1') .'</p>';
                $str .= '<span>'.$p2p->account.'</span>';
                $str .= '</div>';

                if($p2p->confirm == 2){
                    $str .= '<div class="step_ment active">';
                }else{
                    $str .= '<div class="step_ment">';
                }

                $str .= '<p>'.__('ptop.p_to_p_sentence2') .'</p>';
                $str .= '<span>'.$p2p->s_coin_address.'</span>';
                $str .= '</div>';     
                
                if($p2p->confirm == 3){
                    $str .= '<div class="step_ment active _3">';
                }else{
                    $str .= '<div class="step_ment">';
                }

                $str .= '<p>'.__('ptop.p_to_p_sentence3') .'</p>';
                $str .= '<span>'.$p2p->s_coin_address.'</span>';
                $str .= '</div>';  

            }else if($p2p->s_id == Auth::user()->id){
                $str .= '<ul>';

                if($p2p->confirm == 1){
                    $str .= '<li class="active">';
                }else if($p2p->confirm > 1){
                    $str .= '<li class="active complt">';
                }

                $str .= '<span class="status_icon"></span><span class="status_tit">';
                $str .= '<span class="ing"><span class="currency">'.$p2p->country_money.'</span> '.__('ptop.ptop_ing').'</span>';
                $str .= '<span class="complt"><span class="currency">'.$p2p->country_money.'</span> '.__('ptop.ptop_end').'</span>';
                $str .= '</span>';
                $str .= '</li>';

                if($p2p->confirm == 2){
                    $str .= '<li class="active">';
                }else if($p2p->confirm > 2){
                    $str .= '<li class="active complt">';
                }else{
                    $str .= '<li>';
                }

                $str .= '<span class="status_icon"></span><span class="status_tit">';
                $str .= '<span class="ing"><span class="currency">'.strtoupper($p2p->coin_type).'</span> '.__('ptop.ptop_ing').'</span>';
                $str .= '<span class="complt"><span class="currency">'.strtoupper($p2p->coin_type).'</span> '.__('ptop.ptop_end').'</span>';
                $str .= '</span>';
                $str .= '</li>';

                if($p2p->confirm == 3){
                    $str .= '<li class="active">';
                }else{
                    $str .= '<li>';
                }

                $str .= '<span class="status_icon"></span><span class="status_tit">'.__('ptop.settlement_in_progress').'</span>';
                $str .= '</li>';

                $str .= '</ul>';

                if($p2p->confirm == 1){
                    $str .= '<div class="step_ment active _1">';
                }else{
                    $str .= '<div class="step_ment">';
                }

                $str .= '<p>'.__('ptop.you').' '.$p2p->country_money.' '.__('ptop.me').'</p>';
                $str .= '</div>';

                if($p2p->confirm == 2){
                    $str .= '<div class="step_ment active">';
                }else{
                    $str .= '<div class="step_ment">';
                }

                $str .= '<p>'.__('ptop.home').' '.strtoupper($p2p->coin_type).' '.__('ptop.give').'</p>';
                $str .= '<span>'.$p2p->b_coin_address.'</span>';
                $str .= '</div>';     
                
                if($p2p->confirm == 3){
                    $str .= '<div class="step_ment active _3">';
                }else{
                    $str .= '<div class="step_ment">';
                }

                $str .= '<p>'.__('ptop.p_to_p_sentence3') .'</p>';
                $str .= '<span>'.$p2p->b_coin_address.'</span>';
                $str .= '</div>';  
            }

            $str .= '</div>';
            $str .= '</div>';
            $str .= '</div>';

            $offset += 1;
        }

        $response = array(
            "str" => $str,
            "offset" => $offset,
        );

        return response()->json($response); 
    }

    public function mobile_list_more(Request $request){
        $offset = $request->offset;
        $category = $request->category;
        $srch = $request->srch;
        $type = $request->type;
        $auth = $request->auth;
        $str = '';

        if($type=='buy'){
            if($category == 'all'){
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('type','buy')
                        ->where(function($qry) use ($srch){
                            $qry->where('coin_type','like','%'.$srch.'%')->orWhere('name','like','%'.$srch.'%');
                        })
                        ->offset($offset)->orderBy('confirm','asc')->orderBy('id','desc')->limit(9)->get();
            }else{
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('type','buy')
                        ->where($category,'like','%'.$srch.'%')
                        ->offset($offset)->orderBy('confirm','asc')->orderBy('id','desc')->limit(9)->get();
            }
            
        }else if($type=='sell'){
            if($category == 'all'){
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('type','sell')
                        ->where(function($qry) use ($srch){
                            $qry->where('coin_type','like','%'.$srch.'%')->orWhere('name','like','%'.$srch.'%');
                        })
                        ->offset($offset)->orderBy('confirm','asc')->orderBy('id','desc')->limit(9)->get();
            }else{
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('type','sell')
                        ->where($category,'like','%'.$srch.'%')
                        ->offset($offset)->orderBy('confirm','asc')->orderBy('id','desc')->limit(9)->get();
            }
        }else if($type=='my'){
            if($category == 'all'){
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('uid',Auth::id())
                        ->where(function($qry) use ($srch){
                            $qry->where('coin_type','like','%'.$srch.'%')->orWhere('name','like','%'.$srch.'%');
                        })
                        ->offset($offset)->orderBy('confirm','asc')->orderBy('id','desc')->limit(9)->get();
            }else{
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('uid',Auth::id())
                        ->where($category,'like','%'.$srch.'%')
                        ->offset($offset)->orderBy('confirm','asc')->orderBy('id','desc')->limit(9)->get();
            }
        }else{
            if($category == 'all'){
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)
                        ->where(function($qry) use ($srch){
                            $qry->where('coin_type','like','%'.$srch.'%')->orWhere('name','like','%'.$srch.'%');
                        })
                        ->offset($offset)->orderBy('confirm','asc')->orderBy('id','desc')->limit(9)->get();
                        
            }else{
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)
                        ->where($category,'like','%'.$srch.'%')
                        ->offset($offset)->orderBy('confirm','asc')->orderBy('id','desc')->limit(9)->get();
            }
        }

        foreach($p2ps as $p2p){
            $str .= '<div class="list_in_con_outer"><div class="list_in_con"><div class="hd">';
            
            if($p2p->type=='buy'){
                $str .= '<label class="tit buy_tit">';
            }else if($p2p->type=='sell'){
                $str .= '<label class="tit sell_tit" >';
            }

            $str .= __('p2p.'.$p2p->type.'s').__('p2p.'.$p2p->state).'</label><p class="etc_info">';
            $str .= '<span>'.$p2p->name.'</span><span>'.date("Y-m-d",strtotime($p2p->start)).'</span></p>';
            if(Auth::check()){
                if($p2p->uid==Auth::user()->id && $p2p->confirm==0){
                    $str .= '<button class="del_btn" onclick="location.href=\''.route('p2p_deleted',$p2p->id).'\'">'. __('ptop.delete').'</button>';
                }
            }
            $str .= '</div> <div class="info_1"><img src="'.asset('/storage/image/homepage/coin_img/'.$p2p->coin_type.'.png').'" alt="coin_img" class="coin_symbol">';
            $str .= '<p class="coin_name">'.__('coin_name.'.strtolower($p2p->coin_type)).'</p><span class="coin_name_eng">'.$p2p->coin_type.'</span>';
            if($p2p->type=='sell'){
                $str .= '<span class="have_coin_check" id="c_addr"><a href="'.__('blockexplore.'.$p2p->coin_type).$p2p->s_coin_address.'" target="_blank">'. __('ptop.confirm_possessio').'</a></span>';
            }
            $str .= '</div><div class="info_2"><ul><li class="amt"><label class="mr-2">'. __('ptop.quantity') .'</label><span>'.$p2p->coin_amount.'<span class="currency pl-1">'.$p2p->coin_type.'</span></span></li>';
            $str .= '<li class="prc"><label class="mr-2">'. __('ptop.price') .'</label><span>'.$p2p->coin_price.'<span class="currency pl-1">'.$p2p->country_money.'</span></span></li></ul></div><div class="info_3"><textarea readonly="readonly">'.$p2p->cont.'</textarea></div>';
            
            if($auth){
                if(Auth::user()->status == 2){
                    $str .= '<button class="btn_style not_active_btn stop_user_id_warning" type="button">계정 정지</button> ';
                }else if($p2p->state == 'on'){
                    $str .= '<button class="btn_style apply_btn write_btn p2pApply" data-id="'.$p2p->id.'" type="button">'.__('p2p.'.$p2p->type).__('ptop.apply1').'</button>';
                }else if($p2p->state == 'onProgress'){
                    $str .= '<button class="btn_style not_active_btn" type="button">'.__('ptop.ing').'</button> ';
                }else if($p2p->state == 'complete'){
                    $str .= ' <button class="btn_style not_active_btn" type="button">'.__('ptop.complete').'</button> ';
                }else if($p2p->state == 'stop'){
                    $str .= '<button class="btn_style not_active_btn" type="button">'.__('ptop.stop').'</button> ';
                }
            }else{
                if($p2p->state == 'on'){
                    $str .= '<button class="btn_style apply_btn write_btn" type="button" onclick="location.href=\''.route("login").'\'">'.__('p2p.'.$p2p->type).__('ptop.apply1').'</button>';
                }else if($p2p->state == 'onProgress'){
                    $str .= '<button class="btn_style not_active_btn" type="button">'.__('ptop.ing').'</button> ';
                }else if($p2p->state == 'complete'){
                    $str .= ' <button class="btn_style not_active_btn" type="button">'.__('ptop.complete').'</button> ';
                }else if($p2p->state == 'stop'){
                    $str .= '<button class="btn_style not_active_btn" type="button">'.__('ptop.stop').'</button> ';
                }
            }
            
            $str .= '</div></div>';

            $offset += 1;

        }

        $response = array(
            "str" => $str,
            "offset" => $offset,
        );

        return response()->json($response); 
    }
}
