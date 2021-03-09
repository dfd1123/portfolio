<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent; 

use Auth;
use DB;

class P2pController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('list');  //로그인 확인
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }//

    public function index(){
        
        $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->get(); //select
        
        $views = view(session('theme').'.'.$this->device.'.'.'p2p.p2p_list');
        $views->p2ps = $p2ps;
        $views->type = "buy";

        return $views;
    }    
    public function list(Request $request, $type){

        $category = $request->category;
        $srch = $request->srch;
        
        $coins = DB::table('btc_coins')->where('active',1)->where('cointype','<>','cash')->get();

        if($request->category == NULL || !isset($request->category)){
            $category = 'all';
        }

        if($request->srch == NULL || !isset($request->srch)){
            $srch = '';
        }

        $offset = 9;

        $views = view(session('theme').'.'.$this->device.'.'.'p2p.p2p_list');
        
        if($type=='buy'){
            if($category == 'all'){
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('type','buy')
                        ->where(function($qry) use ($srch){
                            $qry->where('coin_type','like','%'.$srch.'%')->orWhere('name','like','%'.$srch.'%');
                        })
                        ->orderBy('confirm','asc')->orderBy('id','desc')->limit($offset)->get();

                $p2p_count = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('type','buy')
                            ->where(function($qry) use ($srch){
                                $qry->where('coin_type','like','%'.$srch.'%')->orWhere('name','like','%'.$srch.'%');
                            })
                            ->orderBy('confirm','asc')->orderBy('id','desc')->count();
            }else{
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('type','buy')
                        ->where($category,'like','%'.$srch.'%')
                        ->orderBy('confirm','asc')->orderBy('id','desc')->limit($offset)->get();

                $p2p_count = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('type','buy')
                            ->where($category,'like','%'.$srch.'%')
                            ->orderBy('confirm','asc')->orderBy('id','desc')->count();
            }
            
        }else if($type=='sell'){
            if($category == 'all'){
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('type','sell')
                        ->where(function($qry) use ($srch){
                            $qry->where('coin_type','like','%'.$srch.'%')->orWhere('name','like','%'.$srch.'%');
                        })
                        ->orderBy('confirm','asc')->orderBy('id','desc')->limit($offset)->get();

                $p2p_count = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('type','sell')
                            ->where(function($qry) use ($srch){
                                $qry->where('coin_type','like','%'.$srch.'%')->orWhere('name','like','%'.$srch.'%');
                            })
                            ->orderBy('confirm','asc')->orderBy('id','desc')->count();
            }else{
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('type','sell')
                        ->where($category,'like','%'.$srch.'%')
                        ->orderBy('confirm','asc')->orderBy('id','desc')->limit($offset)->get();

                $p2p_count = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('type','sell')
                            ->where($category,'like','%'.$srch.'%')
                            ->orderBy('confirm','asc')->orderBy('id','desc')->count();
            }
        }else if($type=='my'){
            if($category == 'all'){
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('uid',Auth::id())
                        ->where(function($qry) use ($srch){
                            $qry->where('coin_type','like','%'.$srch.'%')->orWhere('name','like','%'.$srch.'%');
                        })
                        ->orderBy('confirm','asc')->orderBy('id','desc')->limit($offset)->get();

                $p2p_count = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('uid',Auth::id())
                            ->where(function($qry) use ($srch){
                                $qry->where('coin_type','like','%'.$srch.'%')->orWhere('name','like','%'.$srch.'%');
                            })
                            ->orderBy('confirm','asc')->orderBy('id','desc')->count();
            }else{
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('uid',Auth::id())
                        ->where($category,'like','%'.$srch.'%')
                        ->orderBy('confirm','asc')->orderBy('id','desc')->limit($offset)->get();

                $p2p_count = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('uid',Auth::id())
                            ->where($category,'like','%'.$srch.'%')
                            ->orderBy('confirm','asc')->orderBy('id','desc')->count();
            }
        }else{
            if($category == 'all'){
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)
                        ->where(function($qry) use ($srch){
                            $qry->where('coin_type','like','%'.$srch.'%')->orWhere('name','like','%'.$srch.'%');
                        })
                        ->orderBy('confirm','asc')->orderBy('id','desc')->limit($offset)->get();
                        

                $p2p_count = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)
                            ->where(function($qry) use ($srch){
                                $qry->where('coin_type','like','%'.$srch.'%')->orWhere('name','like','%'.$srch.'%');
                            })
                            ->orderBy('confirm','asc')->orderBy('id','desc')->count();
            }else{
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)
                        ->where($category,'like','%'.$srch.'%')
                        ->orderBy('confirm','asc')->orderBy('id','desc')->limit($offset)->get();

                $p2p_count = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)
                            ->where($category,'like','%'.$srch.'%')
                            ->orderBy('confirm','asc')->orderBy('id','desc')->count();
            }
        }

        $banners = DB::connection('mysql_sub')->table('btc_banners')->where('lang', config('app.country'))->where('active', 1)->orderBy(DB::raw('rand()'));
        $top_banners = (clone $banners)->where('position', 'top')->limit(1)->get();
        $left_banners = (clone $banners)->where('position', 'left')->limit(2)->get();
        $right_banners = (clone $banners)->where('position', 'right')->limit(2)->get();

        $views->withPath('p2p_list/url');
        $views->coins = $coins;
        $views->p2ps = $p2ps;
        $views->p2p_count = $p2p_count;
        $views->offset = $offset;
        $views->category = $category;
        $views->type = $type;
        $views->top_banners = $top_banners;
        $views->left_banners = $left_banners;
        $views->right_banners = $right_banners;

        return $views;
    }
    public function insert(Request $request){
        $type=$request->p2p_type;
        $account_number = str_replace('.','',$request->wt_account);
        if($request->country_money == 'KRW'){
            $account=$request->wt_bank.' '.$request->wt_account_name.' '.$account_number;
        }else{
            $account=$request->wt_bank.' '.$request->jp_bank.' '.$request->wt_account_name.' '.$account_number;
        }
        // dd($account);
        
        if($request->coin_type == ''){
            return back()->with('jsAlert',__('message.is_sel_c'));
        }
        if($request->coin_amount == ''){
            return back()->with('jsAlert',__('message.is_amt_c'));
        }
        if($request->country_money == ''){
            return back()->with('jsAlert',__('message.is_sel_m'));
        }
        if($request->coin_price == ''){
            return back()->with('jsAlert',__('message.is_amt_m'));
        }
        if($request->wt_coin_address == ''){
            return back()->with('jsAlert',__('message.is_coin_addr'));
        }
        if($request->wt_bank == ''){
            return back()->with('jsAlert', __('message.is_bank'));
        }
        if($request->wt_cont == ''){
            return back()->with('jsAlert',__('message.is_cont'));
        }






        if($type=='buy'){
            $id=DB::connection('mysql_sub')->table('btc_p2p')->insertGetId([
                'uid'=>Auth::user()->id,
                'name'=>Auth::user()->fullname,
                'b_id'=>Auth::user()->id,
                'type'=>$type,
                'coin_type'=>$request->coin_type,
                
                'coin_price'=>$request->coin_price,
                'coin_amount'=>$request->coin_amount,
                'country_money'=>$request->country_money,
                'b_coin_address'=>$request->wt_coin_address,
                'b_account'=>$account,
                'cont'=>$request->wt_cont,
                'start'=>now()
            ]);
            
            DB::connection('mysql_sub')->table('btc_p2p_user')->insert([
                'tr_type' => 'buy',
                'pid' => $id,
                'uid' => Auth::user()->id,
                'username' => Auth::user()->fullname,
                'coin_address' => $request->wt_coin_address,
                'account'=>$account,
                'start_day' => now()      
            ]);
        }else if($type=='sell'){
            $balance = DB::table('btc_users_addresses')->where('label',Auth::user()->username)->first();
            if($balance->{'available_balance_'.strtolower($request->coin_type)} < $request->coin_amount){
                return back()->with('jsAlert', 'Not Enough Coin Balance');
            }

            DB::table('btc_users_addresses')->where('label',Auth::user()->username)->decrement('pending_received_balance_'.strtolower($request->coin_type),$request->coin_amount);
            $id=DB::connection('mysql_sub')->table('btc_p2p')->insertGetId([
                'uid'=>Auth::user()->id,
                'name'=>Auth::user()->fullname,
                's_id'=>Auth::user()->id,
                'type'=>$type,
                'coin_type'=>$request->coin_type,
                'coin_price'=>$request->coin_price,
                'coin_amount'=>$request->coin_amount,
                'country_money'=>$request->country_money,
                's_coin_address'=>$request->wt_coin_address,
                's_account'=>$account,
                'cont'=>$request->wt_cont,
                'start'=>now()
            ]);
            DB::connection('mysql_sub')->table('btc_p2p_user')->insert([
                'tr_type' => 'sell',
                'pid' => $id,
                'uid' => Auth::user()->id,
                'username' => Auth::user()->fullname,
                'coin_address' => $request->wt_coin_address,
                'account'=>$account,
                'start_day' => now()           
            ]);
            
        }else{

        }
        return back()->with('jsCheck',__('message.is_p2p_create_complete'));
    }
    public function apply(Request $request){
        
        //$this->middleware('auth');
        //$agent = new Agent();
        //$views = view(session('theme').'.'.$this->device.'.'.'p2p.p2p_apply');
        $id = $request->p_id;
        $type = $request->type;
        $coin_address = $request->coin_address;
        $account_number = str_replace('.','',$request->account);
        $account = $request->bank.' '.$request->account_name.' '.$account_number;
        if($request->coin_address == ''){
            return back()->with('jsAlert',__('message.is_coin_addr'));
        }
        if($request->bank == ''){
            return back()->with('jsAlert',__('message.is_bank'));
        }
        if($request->account == ''){
            return back()->with('jsAlert',__('message.is_account'));
        }


        if($type=='buy'){
            $request_info = DB::connection('mysql_sub')->table('btc_p2p')->where('id',$id)->first();
            $balance = DB::table('btc_users_addresses')->where('label',Auth::user()->username)->first();
            if($balance->{'available_balance_'.strtolower($request_info->coin_type)} < $request_info->coin_amount){
                return back()->with('jsAlert', 'Not Enough Coin Balance');
            }
            
            DB::table('btc_users_addresses')->where('label',Auth::user()->username)->decrement('pending_received_balance_'.strtolower($request_info->coin_type),$request_info->coin_amount);

            DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('id',$id)->update([
                's_id'=>Auth::user()->id,
                's_coin_address'=>$coin_address,
                's_account'=>$account,
                'confirm'=>'1',
                'state'=>'onProgress'
            ]);

            DB::connection('mysql_sub')->table('btc_p2p_user')->insert([
                'tr_type' => 'sell',
                'pid' => $id,
                'uid' => Auth::user()->id,
                'username' => Auth::user()->fullname,
                'coin_address' => $coin_address,
                'account'=>$account,
                'start_day' => now()      
            ]);

        }elseif($type=='sell'){
            DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('id',$id)->update([
                'b_id' => Auth::user()->id,
                'b_coin_address' => $coin_address,
                'b_account' => $account,
                'confirm' => '1',
                'state' => 'onProgress'
            ]);
            DB::connection('mysql_sub')->table('btc_p2p_user')->insert([
                'tr_type' => 'buy',
                'pid' => $id,
                'uid' => Auth::user()->id,
                'username' => Auth::user()->fullname,
                'coin_address' => $coin_address,
                'account' => $account,
                'start_day' => now()
            ]);
        }

        
       

        return back()->with('jsCheck',__('message.is_p2p_apply_complete'));
    }

    public function p2p_ajax_test(Request $request){
        $id=$request->id;
        $p2p = DB::connection('mysql_sub')->table('btc_p2p')->where('id',$id)->first(); //select
        $data = array(
            "id" => $p2p->id,
            "type" => $p2p->type,
            "name" => $p2p->name,
            "coin_type" => $p2p->coin_type,
            "coin_symbol" => __('coin_name.'.strtolower($p2p->coin_type)),
            "coin_amount" => $p2p->coin_amount,
            "coin_price" => $p2p->coin_price,
            "country_money" => $p2p->country_money,
        );
        
        return response()->json($data); 
    }
    
    public function onProgress($type){
        $id=Auth::id();
        $views = view(session('theme').'.'.$this->device.'.'.'p2p.p2p_onprogress');

        $offset = 15;
        
        if($this->device == 'pc'){
            if($type=='buy'){
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('b_id',$id)->where('deleted',0)->where('state','<>','stop')->where('confirm','>',0)->where('confirm','<',4)->get(); //select
            }elseif($type=='sell'){
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('s_id',$id)->where('deleted',0)->where('state','<>','stop')->where('confirm','>',0)->where('confirm','<',4)->get(); //select
            }else{
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')
                ->where('deleted',0)
                ->where('state','<>','stop')
                ->where('confirm','>',0)
                ->where('confirm','<',4)
                ->where(function($query) use ($id){$query->where('s_id',$id)->orWhere('b_id',$id);})->get(); //select
            }
        }else{
            if($type=='buy'){
                $count = DB::connection('mysql_sub')->table('btc_p2p')->where('b_id',$id)->where('deleted',0)->where('state','<>','stop')->where('confirm','>',0)->where('confirm','<',4)->count();
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('b_id',$id)->where('deleted',0)->where('state','<>','stop')->where('confirm','>',0)->where('confirm','<',4)->limit($offset)->get(); //select
            }elseif($type=='sell'){
                $count = DB::connection('mysql_sub')->table('btc_p2p')->where('s_id',$id)->where('deleted',0)->where('state','<>','stop')->where('confirm','>',0)->where('confirm','<',4)->count();
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('s_id',$id)->where('deleted',0)->where('state','<>','stop')->where('confirm','>',0)->where('confirm','<',4)->limit($offset)->get(); //select
            }else{
                $count = DB::connection('mysql_sub')->table('btc_p2p')
                ->where('deleted',0)
                ->where('state','<>','stop')
                ->where('confirm','>',0)
                ->where('confirm','<',4)
                ->where(function($query) use ($id){$query->where('s_id',$id)->orWhere('b_id',$id);})->count();
                $p2ps = DB::connection('mysql_sub')->table('btc_p2p')
                ->where('deleted',0)
                ->where('state','<>','stop')
                ->where('confirm','>',0)
                ->where('confirm','<',4)
                ->where(function($query) use ($id){$query->where('s_id',$id)->orWhere('b_id',$id);})->limit($offset)->get(); //select
            }

            $views->count = $count;
        }

        $banners = DB::connection('mysql_sub')->table('btc_banners')->where('lang', config('app.country'))->where('active', 1)->orderBy(DB::raw('rand()'));
        $top_banners = (clone $banners)->where('position', 'top')->limit(1)->get();
        $left_banners = (clone $banners)->where('position', 'left')->limit(2)->get();
		$right_banners = (clone $banners)->where('position', 'right')->limit(2)->get();
        
        $views->p2ps = $p2ps;
        $views->type = $type;
        $views->offset = $offset;
        $views->top_banners = $top_banners;
        $views->left_banners = $left_banners;
		$views->right_banners = $right_banners;
        return $views;
    }

    public function hiStory(Request $request){
        $id=Auth::user()->id;
        $views = view(session('theme').'.'.$this->device.'.'.'p2p.p2p_history');
        $from_date = date('Y-m-d', strtotime('-10day'));
        $to_date = date('Y-m-d');

        $during = $request->during;

        if(!empty($request->from_date) &&  $request->from_date != null){
            $from_date = $request->from_date;
        }

        if(!empty($request->to_date) &&  $request->to_date != null){
            $to_date = $request->to_date;
        }

        if($during == 7){
            $from_date = date('Y-m-d', strtotime('-7day'));
            $to_date = date('Y-m-d');
        }else if($during == 14){
            $from_date = date('Y-m-d', strtotime('-14day'));
            $to_date = date('Y-m-d');
        }else if($during == 30){
            $from_date = date('Y-m-d', strtotime('-1 month'));
            $to_date = date('Y-m-d');
        }

        $mydate = date("Ymd", strtotime("-7 day", strtotime(20190314)));
        
        $offset = 15;    
        
        $p2ps = DB::connection('mysql_sub')->table('btc_p2p')
                ->where('deleted',0)
                ->where('confirm','=',4)
                ->where('state','<>','stop')
                ->whereDate('end','>=',$from_date)
                ->whereDate('end','<=',$to_date)
                ->where(function($query) use ($id){$query->where('s_id',$id)->orWhere('b_id',$id);})
                ->orderBy('end','desc')->limit($offset)->get(); //select

        $p2p_count = DB::connection('mysql_sub')->table('btc_p2p')
                    ->where('deleted',0)
                    ->where('confirm','=',4)
                    ->where('state','<>','stop')
                    ->whereDate('end','>=',$from_date)
                    ->whereDate('end','<=',$to_date)
                    ->where(function($query) use ($id){$query->where('s_id',$id)->orWhere('b_id',$id);})
                    ->orderBy('end','desc')->count(); //select

        $banners = DB::connection('mysql_sub')->table('btc_banners')->where('lang', config('app.country'))->where('active', 1)->orderBy(DB::raw('rand()'));
        $top_banners = (clone $banners)->where('position', 'top')->limit(1)->get();
        $left_banners = (clone $banners)->where('position', 'left')->limit(2)->get();
		$right_banners = (clone $banners)->where('position', 'right')->limit(2)->get();

        $views->p2ps = $p2ps;
        $views->mydate = $mydate;
        $views->from_date = $from_date;
        $views->to_date = $to_date;
        $views->p2p_count = $p2p_count;
        $views->offset = $offset;
        $views->top_banners = $top_banners;
        $views->left_banners = $left_banners;
		$views->right_banners = $right_banners;
        return $views;
    }

    public function deleted(Request $request, $id){
        //info($id);
        $delete_info = DB::connection('mysql_sub')->table('btc_p2p')->where('id',$id)
        ->where(function($query){
            $query->where('b_id',Auth::user()->id)->orwhere('s_id',Auth::user()->id);
        })->first();

        if(isset($delete_info)){

            DB::table('btc_users_addresses')->where('label',Auth::user()->username)->increment('pending_received_balance_'.strtolower($delete_info->coin_type),$delete_info->coin_amount);

            DB::connection('mysql_sub')->table('btc_p2p')
            ->where('id',$id)
            ->where(function($query) use ($id){$query->where('s_id',Auth::user()->id)->orWhere('b_id',Auth::user()->id);})
            ->where('confirm',0)
            ->update(['deleted'=>1]); //select

            DB::connection('mysql_sub')->table('btc_p2p_user')
            ->where('pid',$id)
            ->where('uid',Auth::user()->id)
            ->update(['deleted'=>1]);

            return back()->with('jsCheck',__('ptop.cancel_pr'));
        }else{
            return back()->with('jsAlert','Wrong Access!');
        }
    }

    public function confirm(Request $request, $id){
        $request_info = DB::connection('mysql_sub')->table('btc_p2p')->where('id',$id)->where('s_id',Auth::user()->id)->first();
        if(isset($request_info)){
            $amount = $request_info->coin_amount;
            
            //거래에 의한 판매자 코인차감 펜딩제거, 구매자 코인 더함 시작
            DB::table('btc_users_addresses')->where('uid',$request_info->s_id)->increment('pending_received_balance_'.strtolower($request_info->coin_type),$request_info->coin_amount);
            DB::table('btc_users_addresses')->where('uid',$request_info->s_id)->decrement('available_balance_'.strtolower($request_info->coin_type),$request_info->coin_amount);
            DB::table('btc_users_addresses')->where('uid',$request_info->b_id)->increment('available_balance_'.strtolower($request_info->coin_type),$request_info->coin_amount);
            //거래에 의한 판매자 코인차감 펜딩제거, 구매자 코인 더함 끝

            // p2p 정보 및 유저 정보 상태 변환 시작
            DB::connection('mysql_sub')->table('btc_p2p')->where('id',$id)->where('state','<>','stop')->update([
                'confirm'=>4,
                'state'=>'complete',
                'end'=>now(),
                'update_at'=>now()
            ]);

            DB::connection('mysql_sub')->table('btc_p2p_user')
            ->where('uid',$request_info->b_id)
            ->where('pid',$request_info->id)
            ->update([
                'end_day' => now(),
                'update_at'=> now()
            ]);

            DB::connection('mysql_sub')->table('btc_p2p_user')
            ->where('uid',$request_info->s_id)
            ->where('pid',$request_info->id)
            ->update([
                'end_day' => now(),
                'update_at'=> now()
            ]);

            // p2p 정보 및 유저 정보 상태 변환 끝

            return back()->with('jsCheck', 'Trade Completed!');
        }else{
            return back()->with('jsAlert', 'Wrong Access!');
        }
    }

    public function canceled(Request $request, $id){
        $request_info = DB::connection('mysql_sub')->table('btc_p2p')->where('id',$id)
        ->where(function($query){
            $query->where('s_id',Auth::user()->id)->orwhere('b_id',Auth::user()->id);
        })->first();

        if(isset($request_info)){

            if($request_info->type == 'buy'){
                // p2p 정보 및 유저 정보 상태 변환 시작
                DB::table('btc_users_addresses')->where('uid',$request_info->s_id)->increment('pending_received_balance_'.strtolower($request_info->coin_type),$request_info->coin_amount);
                DB::connection('mysql_sub')->table('btc_p2p')->where('id',$id)->where('state','<>','stop')->update([
                    'confirm'=>0,
                    'state'=>'on',
                    's_id'=>null
                ]);
            }else if($request_info->type == 'sell'){
                // p2p 정보 및 유저 정보 상태 변환 시작
                DB::connection('mysql_sub')->table('btc_p2p')->where('id',$id)->where('state','<>','stop')->update([
                    'confirm'=>0,
                    'state'=>'on',
                    'b_id'=>null
                ]);
            }
            

            DB::connection('mysql_sub')->table('btc_p2p_user')
            ->where('uid',$request_info->b_id)
            ->where('pid',$request_info->id)
            ->update([
                'deleted' => 0
            ]);

            DB::connection('mysql_sub')->table('btc_p2p_user')
            ->where('uid',$request_info->s_id)
            ->where('pid',$request_info->id)
            ->update([
                'deleted' => 0
            ]);

            // p2p 정보 및 유저 정보 상태 변환 끝

            return back()->with('jsCheck', 'Trade Canceled!');
        }else{
            return back()->with('jsAlert', 'Wrong Access!');
        }
    }
}
