<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use App\Exports\UsersExport;
use App\Exports\TradeHistorysExport;
use App\Exports\CoinTrsExport;
use App\Exports\CashWithdrawExport;
use App\Exports\CashWithdrawHistoryExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\Notify;
use Facades\App\Classes\File_store;
use Facades\App\Classes\FcmPush;

use Auth;
use DB;
use Redirect;
use Mail;
use Session;
use Directsend_mail;
use Settings;
use Log;
use File;
use Visitor;
use Nexmo_sms;

use Facades\App\Classes\LoginTrace;

class AdminController extends Controller {
    public function index() {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $active_user_count = Visitor::live('count');
        
        $users_count = DB::table('users')->count();
        
        $new_user_count = DB::table('users')->whereMonth('created_at',date('m'))->count();
        
        $coins = DB::table('btc_coins')->where('active',1)->where('cointype','<>','cash')->orderBy('market','asc')->get();
        
        $month_trades = DB::table('btc_trades_COIN_btc')->select(DB::raw('SUM(contract_coin_amt) AS amt'), DB::raw('CONCAT(YEAR(created_dt),"-",MONTH(created_dt)) AS date'),'cointype')
            ->where('created','>',DB::raw('UNIX_TIMESTAMP(NOW() - INTERVAL 1 YEAR)'))->where('buyer_username','<>','sbtr01')->where('seller_username','<>','sbtr01')->orderBy(DB::raw('CONCAT(YEAR(created_dt),"-",MONTH(created_dt))'),'asc')->groupBy('cointype',DB::raw('CONCAT(YEAR(created_dt),"-",MONTH(created_dt))'))->get();
        
        $month_trades_revenues = DB::select(
            DB::raw("SELECT
                        btc_trades_COIN_btc.currency, 
                        CONCAT(YEAR(btc_trades_COIN_btc.created_dt),'-',MONTH(btc_trades_COIN_btc.created_dt)) AS date, 
                        (SUM(btc_trades_COIN_btc.contract_coin_amt*btc_trades_COIN_btc.buy_coin_price) * (SELECT (buy_comission + sell_comission) AS fee FROM btc_settings WHERE id = 1 ) * 0.01) - IFNULL((SELECT sum(amount) FROM btc_lock_dividend where CONCAT(YEAR(created_dt),'-',MONTH(created_dt)) = date),0) AS total_fee_price 
                    FROM `btc_trades_COIN_btc` 
                    WHERE `btc_trades_COIN_btc`.`created` > UNIX_TIMESTAMP(NOW() - INTERVAL 1 YEAR) and 
                            `buyer_username` <> 'sbtr01' and 
                            `seller_username` <> 'sbtr01' 
                    group by btc_trades_COIN_btc.currency, CONCAT(YEAR(btc_trades_COIN_btc.created_dt),'-',MONTH(btc_trades_COIN_btc.created_dt)) 
                    order by CONCAT(YEAR(btc_trades_COIN_btc.created_dt),'-',MONTH(btc_trades_COIN_btc.created_dt)) asc")
        );

        $month_withdraws_revenues = DB::select(DB::raw('select CONCAT(YEAR(created_dt),"-",MONTH(created_dt)) AS date, (SUM(send_fee)* IFNULL((SELECT avg(btc_trades_COIN_btc.buy_coin_price) FROM `btc_trades_COIN_btc` WHERE currency = "KRW" and CONCAT(YEAR(btc_trades_COIN_btc.created_dt),"-",MONTH(btc_trades_COIN_btc.created_dt)) = date and btc_coin_send_request.cointype = btc_trades_COIN_btc.cointype ),0) ) AS amt, `cointype` from `btc_coin_send_request` where `created` > UNIX_TIMESTAMP(NOW() - INTERVAL 1 YEAR) and status = "withdraw_complete" group by `cointype`, CONCAT(YEAR(created_dt),"-",MONTH(created_dt)) ORDER BY `btc_coin_send_request`.`cointype` ASC'));

        $qna_counts_kr = DB::connection('mysql_sub')->table('btc_qna')->where('answered',0)->where('country','kr')->count();
        $qna_counts_jp = DB::connection('mysql_sub')->table('btc_qna')->where('answered',0)->where('country','jp')->count();
        $qna_counts_ch = DB::connection('mysql_sub')->table('btc_qna')->where('answered',0)->where('country','ch')->count();
        $qna_counts_en = DB::connection('mysql_sub')->table('btc_qna')->where('answered',0)->where('country','en')->count();
        
        $send_requests_count = DB::table('btc_coin_send_request')->where('status','withdraw_request')->count();
        
        $ico_confirm_count = DB::connection('mysql_sub')->table('btc_ico_new')->where('active',0)->where('ico_category',0)->count();
        
        $p2p_confirm_count = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('state','<>','stop')->where(function ($query){
            $query->where('confirm',1)->orwhere('confirm',2)->orwhere('confirm',3);
        })->count();

        $views = view('admin.main.home');
        $views->coins = $coins;
        $views->datetime = $datetime;
        $views->users_count = $users_count;
        $views->new_user_count = $new_user_count;
        $views->month_trades = $month_trades;
        $views->month_trades_revenues = $month_trades_revenues;
        $views->month_withdraws_revenues = $month_withdraws_revenues;
        $views->qna_counts_kr = $qna_counts_kr;
        $views->qna_counts_jp = $qna_counts_jp;
        $views->qna_counts_ch = $qna_counts_ch;
        $views->qna_counts_en = $qna_counts_en;
        $views->send_requests_count = $send_requests_count;
        $views->ico_confirm_count = $ico_confirm_count;
        $views->p2p_confirm_count = $p2p_confirm_count;
        $views->start_date = date("Y-m",strtotime("-11 months"));
        $views->end_date = date("Y-m");
        $views->active_user_count = $active_user_count;

        return $views;
    }

    public function login() {
        $views = view('admin.auth.login');

        return $views;
    }

    public function login_form(Request $request) {

        if (Auth::guard('admin') -> attempt(['email' => $request -> input('email'), 'password' => $request -> input('password')])) {
            return redirect() -> route('admin.main');

        } else {
            return redirect() -> back() -> with('jsAlert', '아이디나 비밀번호가 맞지 않습니다.');
        }

        $views = view('admin.auth.login');

        return $views;
    }

    public function logout(Request $request) {
        Auth::logout();
        Session::flush();

        return Redirect::route('admin.login');
    }

    public function register_agree() {
        $views = view('admin.auth.register_agree');

        return $views;
    }

    public function register_complete() {
        $views = view('admin.auth.register_complete');

        return $views;
    }
    
    public function user_list(Request $request){
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        $keyword_srch = $request->input('keyword_srch');
        
        $keyword = '';
        
        if($request->input('keyword') != null){
            $keyword = $request->input('keyword');
        }
        
	
		
        if($keyword_srch != null){
        	if($keyword_srch == 'uid'){
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->where('users.id',$keyword)->whereNotNull('users.status')->orderBy('users.id', 'desc')->paginate(20);
            }else if($keyword_srch == 'fullname'){
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->where(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))->whereNotNull('users.status')->orderBy('users.id', 'desc')->paginate(20);
            }else if($keyword_srch == 'email'){
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->where('users.email','like','%'.$keyword.'%')->whereNotNull('users.status')->orderBy('users.id', 'desc')->paginate(20);
            }else if($keyword_srch == 'mobile'){
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->where('users.mobile_number','like','%'.$keyword)->whereNotNull('users.status')->orderBy('users.id', 'desc')->paginate(20);
            }else{
            	 $users = DB::table('btc_users_addresses')
            	 ->join('users', 'users.id', '=', 'btc_users_addresses.uid')
				 ->where(function($qry) use ($keyword){
                    $qry->where('users.email','like','%'.$keyword.'%')
                    ->orWhere(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
                    ->orWhere('users.mobile_number','like','%'.$keyword.'%')
                    ->orWhere('users.id',$keyword);
				 })->whereNotNull('users.status')->orderBy('users.id', 'desc')->paginate(20);
	
            }
        }else{
            $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->whereNotNull('users.status')->orderBy('users.id', 'desc')->paginate(20);
        }
        
        
        $users->withPath('user_list');
        
        $coins = DB::table('btc_coins')->where('active',1)->get();
        
        $views = view('admin.user.user_list');
        
        $views->users = $users;
        $views->coins = $coins;
        $views->datetime = $datetime;
        $views->keyword_srch = $keyword_srch;
        
        return $views;
    }

    public function user_list_new(Request $request){
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        $keyword_srch = $request->input('keyword_srch');
        
        $keyword = '';
        
        if($request->input('keyword') != null){
            $keyword = $request->input('keyword');
        }
        
	
		
        if($keyword_srch != null){
        	if($keyword_srch == 'uid'){
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->whereMonth('users.created_at',date('m'))->where('users.id',$keyword)->whereNotNull('users.status')->orderBy('users.id', 'desc')->paginate(20);
            }else if($keyword_srch == 'fullname'){
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->whereMonth('users.created_at',date('m'))->where(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))->whereNotNull('users.status')->orderBy('users.id', 'desc')->paginate(20);
            }else if($keyword_srch == 'email'){
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->whereMonth('users.created_at',date('m'))->where('users.email','like','%'.$keyword.'%')->whereNotNull('users.status')->orderBy('users.id', 'desc')->paginate(20);
            }else if($keyword_srch == 'mobile'){
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->whereMonth('users.created_at',date('m'))->where('users.mobile_number','like','%'.$keyword)->whereNotNull('users.status')->orderBy('users.id', 'desc')->paginate(20);
            }else{
            	 $users = DB::table('btc_users_addresses')
            	 ->join('users', 'users.id', '=', 'btc_users_addresses.uid')
				 ->where(function($qry) use ($keyword){
                    $qry->where('users.email','like','%'.$keyword.'%')
                    ->orWhere(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
                    ->orWhere('users.mobile_number','like','%'.$keyword.'%')
                    ->orWhere('users.id',$keyword);
				 })->whereNotNull('users.status')->whereMonth('users.created_at',date('m'))->orderBy('users.id', 'desc')->paginate(20);
	
            }
        }else{
            $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->whereNotNull('users.status')->whereMonth('users.created_at',date('m'))->orderBy('users.id', 'desc')->paginate(20);
        }
        
        
        $users->withPath('user_list_new');
        
        $coins = DB::table('btc_coins')->where('active',1)->get();
        
        $views = view('admin.user.user_list_new');
        
        $views->users = $users;
        $views->coins = $coins;
        $views->datetime = $datetime;
        $views->keyword_srch = $keyword_srch;
        
        return $views;
    }

    public function user_list_now(Request $request){
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $active_user_list = Visitor::live('list');
        
        $views = view('admin.user.user_list_now');
        
        $views->users = $active_user_list;
        $views->datetime = $datetime;
        
        return $views;
    }

    public function user_excel(Request $request){
        $from = $request->from;
        $to = $request->to;

        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
                
        return Excel::download(new UsersExport($from, $to), '사용자리스트_' .  date("Y-m-d") . '.xlsx'); 
    }

	public function user_detail(Request $request, $uid, $tab){
    	date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
		
		$coins = DB::table('btc_coins')->where('active',1)->get();
		
		$views = view('admin.user.user_detail');
		$views->uid = $uid;
		$views->tab = $tab;
        $views->coins = $coins;
		$views->datetime = $datetime;
		
        if($tab == 1){
            $users = DB::table('btc_users_addresses')
            ->join('users', 'users.id', '=', 'btc_users_addresses.uid')
            ->join('btc_security_lv', 'btc_security_lv.uid', '=', 'btc_users_addresses.uid')
            ->where('users.id',$uid)->whereNotNull('users.status')->paginate(20);
			$users->withPath('/admin/user_detail/'.$uid.'/user_detail');
	
			$views->users = $users;
        }else if($tab == 2){
        	$trade_historys = DB::table('btc_ads_btc')
        	->join('users','btc_ads_btc.userid','=','users.username')
			->select('btc_ads_btc.*','users.id as uid','users.email' ,'users.mobile_number' ,'users.fullname' )
			->where('btc_ads_btc.uid',$uid)
        	->orderBy('btc_ads_btc.id','desc')->paginate(30);
			$trade_historys->withPath('/admin/user_detail/'.$uid.'/'.$tab);
			
			$views->trade_historys = $trade_historys;
        }else if($tab == 3){
			$transactions = DB::table('btc_transaction')
        	->join('users','btc_transaction.account','=','users.username')
            ->select('btc_transaction.*','users.id as uid','users.email' ,'users.mobile_number' ,'users.fullname' )
            ->where('btc_transaction.category', '<>', 'trade')
			->where('users.id',$uid)
        	->orderBy('btc_transaction.id','desc')->paginate(30);
			$transactions->withPath('/admin/user_detail/'.$uid.'/'.$tab);
			
			$views->transactions = $transactions;
        }else if($tab == 4){
        	$icos = DB::connection('mysql_sub')->table('btc_ico_new')
			->where('w_id',$uid)
        	->orderBy('id','asc')->paginate(20);
			$icos->withPath('/admin/user_detail/'.$uid.'/'.$tab);
			
			$views->icos = $icos; 
        }else if($tab == 5){
        	$qnas = DB::table('html.users')
                    ->join('html_read.btc_qna','html_read.btc_qna.createdby','=','html.users.username')
					->where('html.users.id',$uid)
                    ->orderBy('html_read.btc_qna.id','desc')->paginate(20);
	        $qnas->withPath('/admin/user_detail/'.$uid.'/'.$tab);
	
	        $views->qnas = $qnas;
        }
        
        return $views;
    }

    public function user_balance_activity(Request $request){
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $keyword_srch = $request->input('keyword_srch');
        $keyword = $request->input('keyword');

        $activities = DB::table('btc_coin_io')
            ->leftJoin('users', 'btc_coin_io.uid', '=', 'users.id')
            ->select('btc_coin_io.*', 'users.fullname')
            ->orderBy('btc_coin_io.id', 'desc');

        if($keyword_srch != null) {
            $keyword = $keyword != null ? $keyword : '';
            if($keyword_srch == 'name') {
                $activities = $activities->where('users.fullname','like','%'.$keyword.'%');
            } else if($keyword_srch == 'id') {
                $activities = $activities->where('btc_coin_io.username','like','%'.$keyword.'%');
            }
        }

        $activities_page = $activities->paginate(20);
        $activities_page->withPath('user_balance_activity');

        if($keyword_srch != null) {
            $activities_page->appends(['keyword_srch' => $keyword_srch, 'keyword' => $keyword])->links();
        }

        $views = view('admin.user.user_balance_activity');
        $views->activities = $activities->get();
        $views->activities_page = $activities_page;
        $views->datetime = $datetime;

        return $views;
    }

    public function user_memo_change(Request $request){
        $uid = $request->uid;
        $user_memo = $request->user_memo;
        
        $user = DB::table('users')->where('id', $uid)->first(); 

        $status = DB::table('users')->where('id', $uid)->update([
            "user_memo" => $user_memo,
        ]);

        $response = array(
            "status" => $status,
            "user_memo" => $user_memo,
        );

        if($status){
            LoginTrace::Activity('관리자가 "'.$user->fullname.'" ('.$uid.')의 메모 작성 or 변경');
        }
        

        return response()->json($response); 
    }

    public function user_fullname_change(Request $request){
        $uid = $request->uid;
        $fullname = $request->fullname;
        
        $user = DB::table('users')->where('id', $uid)->first(); 

        $status = DB::table('users')->where('id', $uid)->update([
            "fullname" => $fullname,
        ]);

        $response = array(
            "status" => $status,
            "fullname" => $fullname,
        );

        if($status){
            DB::table('btc_security_lv')->where('uid', $uid)->update([
                "mobile_verified" => 0,
            ]);

            LoginTrace::Activity('관리자가 "'.$user->fullname.'"에서 "'.$fullname.'"로 이름 변경');
        }
        

        return response()->json($response); 
    }

    public function user_nickname_change(Request $request){
        $uid = $request->uid;
        $nickname = $request->nickname;
        
        $exsit = DB::table('users')->where('nickname', $nickname)->exists();

        if($exsit){
            $response = array(
                "status" => 0,
                "message" => '이미 존재하는 닉네임 입니다.',
            );
        }else{
            $user = DB::table('users')->where('id', $uid)->first(); 

            if(isset($user->nick_updated_at)){
                if(strtotime($user->nick_updated_at) > strtotime("-6 month", time())){
                    $response = array(
                        "status" => 0,
                        "message" => "6개월 이내에 이미 닉네임을 바꾼 이력이 있습니다.",
                    );
                }
            }

            $status = DB::table('users')->where('id', $uid)->update([
                "nickname" => $nickname,
                "nick_updated_at" => DB::raw('now()'),
            ]);

            if($status > 0){
                $message = "닉네임 변경 성공";
            }else{
                $message = "닉네임 변경 실패";
            }
            $response = array(
                "status" => $status,
                "nickname" => $nickname,
                "message" => $message,
            );

            if($status){
                LoginTrace::Activity('관리자가 "'.$user->nickname.'"에서 "'.$nickname.'"로 닉네임 변경');
            }
        }

        return response()->json($response); 
    }

    public function user_email_change(Request $request){
        $uid = $request->uid;
        $email = $request->email;
        
        $exsit = DB::table('users')->where('email', $email)->exists();

        if($exsit){
            $response = array(
                "status" => 0,
                "exist" => $exsit,
            );
        }else{
            $user = DB::table('users')->where('id', $uid)->first(); 

            $status = DB::table('users')->where('id', $uid)->update([
                "email" => $email,
                "email_verified_at" => NULL,
            ]);
    
            $response = array(
                "status" => $status,
                "exist" => $exsit,
                "email" => $email,
            );
    
            if($status){
                if(DB::table('change_email_history')->where('uid', $uid)->exists()){
                    DB::table('change_email_history')->where('uid', $uid)->update([
                        "after_email" => $email,
                        "updated_at" => DB::raw('now()'),
                    ]);
                }else{
                    DB::table('change_email_history')->insert([
                        "uid" => $uid,
                        "before_email" => $user->email,
                        "after_email" => $email,
                        "admin_id" => Auth::guard('admin')->user()->id,
                        "admin_email" => Auth::guard('admin')->user()->email,
                    ]);
                }  

                LoginTrace::Activity('관리자가 "'.$user->email.'"에서 "'.$email.'"로 이메일 주소 변경');
            }
        }

        return response()->json($response); 
    }

    public function user_mobile_change(Request $request){
        $uid = $request->uid;
        $mobile_number = $request->mobile_number;
        
        $exsit = DB::table('users')->where('mobile_number', $mobile_number)->exists();

        if($exsit){
            $response = array(
                "status" => 0,
                "exist" => $exsit,
            );
        }else{
            $user = DB::table('users')->where('id', $uid)->first(); 

            $status = DB::table('users')->where('id', $uid)->update([
                "mobile_number" => $mobile_number,
            ]);
    
            $response = array(
                "status" => $status,
                "exist" => $exsit,
                "mobile_number" => $mobile_number,
            );
    
            if($status){
                DB::table('btc_security_lv')->where('uid', $uid)->update([
                    "mobile_verified" => 0,
                ]);
                LoginTrace::Activity('관리자가 "'.$user->mobile_number.'"에서 "'.$mobile_number.'"로 휴대폰 번호 변경');
            }
        }

        return response()->json($response); 
    }

    public function user_password_change(Request $request){
        $uid = $request->uid;

        $password = $this->GenerateString(8);

        $status = DB::table('users')->where('id', $uid)->update([
            "password" => Hash::make($password),
        ]);

        $response = array(
            "status" => $status,
        );

        if($status){
            $user = DB::table('users')->where('id', $uid)->first();

            $description = '[암호화폐 거래소 스포와이드] 고객님의 임시 비밀번호가 발급되었습니다.
아래에 보여지는 임시비밀번호로 로그인 하시기 바랍니다.
            
임시 비밀번호 : '.$password.'
            
해당 비밀번호는 관리자도 알 수 없으며 반드시 마이페이지에서 비밀번호 변경을 해주시기 바랍니다.';
            Nexmo_sms::send_sms($user->country, $user->mobile_number, $description);

            LoginTrace::Activity('관리자가 "'.$user->email.'" 님의 임시 비밀번호 전송');
        }

        return response()->json($response); 
    }

    private function GenerateString($length)  
    {  
        $characters  = "0123456789";  
        $characters .= "abcdefghijklmnopqrstuvwxyz";  
        $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";  
        $characters .= "_";  
        
        $string_generated = "";  
        
        $nmr_loops = $length;  
        while ($nmr_loops--)  
        {  
            $string_generated .= $characters[mt_rand(0, strlen($characters) - 1)];  
        }  
        
        return $string_generated;  
    }  
    
    public function coin_listing_list() {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        $coins = DB::table('btc_coins')->select('*')->where('cointype', '<>', 'cash')->where('active', '<>', 2);
        $coins_page = $coins->paginate(20);
        $coins_page->withPath('coin_listing_list');
        
        $views = view('admin.coin_listing.coin_listing_list');
        $views->coins = $coins->get();
        $views->coins_page = $coins_page;
        $views->datetime = $datetime;

        return $views;
    }

    public function sports_infor_edit($coin_id){
        $coin = DB::table('btc_coins')->where('id', $coin_id)->first();

        $views = view('admin.coin_listing.sports_infor');

        if($coin->market != 'sports'){
            return redirect()->route('admin.coin_listing_list');
        }else{
            $views->coin = $coin;
        }

        return $views;
    }

    public function sports_infor_update(Request $request, $coin_id){

        $league_name = $request->league_name;
        $league_rank = $request->league_rank;
        $club_value_kr = $request->club_value_kr;
        $club_value_jp = $request->club_value_jp;
        $club_value_ch = $request->club_value_ch;
        $club_value_en = $request->club_value_en;
        $world_pan_kr = $request->world_pan_kr;
        $world_pan_jp = $request->world_pan_jp;
        $world_pan_ch = $request->world_pan_ch;
        $world_pan_en = $request->world_pan_en;


        $coin = DB::table('btc_coins')->where('id', $coin_id)->first();

        if($coin->market == 'sports'){
            DB::table('btc_coins')->where('id', $coin_id)->update([
                "league_name" => $league_name,
                "league_rank" => $league_rank,
                "club_value_kr" => $club_value_kr,
                "club_value_jp" => $club_value_jp,
                "club_value_ch" => $club_value_ch,
                "club_value_en" => $club_value_en,
                "world_pan_kr" => $world_pan_kr,
                "world_pan_jp" => $world_pan_jp,
                "world_pan_ch" => $world_pan_ch,
                "world_pan_en" => $world_pan_en,
            ]);
        }

        return redirect()->route('admin.coin_listing_list');
    }

    public function coin_listing_update(Request $request, $id, $active) {
        DB::table('btc_coins')->where('cointype', '<>', 'cash')->where('id', $id)->update([
            'active' => $active
        ]);
		
		$coin = DB::table('btc_coins')->where('id',$id)->first();
		
		if($active == 0){
        	LoginTrace::Activity($coin->symbol.'상폐시킴');
		}else{
			LoginTrace::Activity($coin->symbol.'상장시킴');
		}
		
        return Redirect::route('admin.coin_listing_list');
    }
    
    public function banner_list() {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        $banners = DB::connection('mysql_sub')->table('btc_banners')->select('*');
        $banners_page = $banners->paginate(20);
        $banners_page->withPath('banner_list');
        
        $views = view('admin.banner.banner_list');
        $views->banners = $banners->get();
        $views->banners_page = $banners_page;
        $views->datetime = $datetime;

        return $views;
    }
    
    public function banner_create() {		
        $views = view('admin.banner.banner_create');
        return $views;
    }

    public function banner_store(Request $request) {
        $storage_save_path = 'public/image/banner';
        
        $path1 = null;
        $path2 = null;
        
        if($request->hasFile('file1')){
            if ($request->file('file1')->isValid()) {
                $path1 = str_replace($storage_save_path.'/', "", $request->file1->store($storage_save_path));
            }
        }

        if($request->hasFile('file2')){
            if ($request->file('file2')->isValid()) {
                $path2 = str_replace($storage_save_path.'/', "", $request->file2->store($storage_save_path));
            }
        }
        
        DB::connection('mysql_sub')->table('btc_banners')->insert([
            'target_url' => $request->input('target_url'),
            'banner_url' => $path1,
            'banner_mobile_url' => $path2,
            'detail' => $request->input('detail'),
            'position' => $request->input('position'),
            'lang' => $request->input('lang'),
            'active' => $request->input('active')
        ]);
        
        return Redirect::route('admin.banner_list');
    }
    
    public function banner_edit(Request $request, $id) {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        $banner = DB::connection('mysql_sub')->table('btc_banners')->select('*')->where('id', $id)->first();
        
        $views = view('admin.banner.banner_edit');
        $views->banner = $banner;
        $views->datetime = $datetime;
        
        return $views;
    }
    
    public function banner_update(Request $request, $id) {
        $storage_save_path = 'public/image/banner';
        
        $banner = DB::connection('mysql_sub')->table('btc_banners')->select('*')->where('id', $id)->first();
        $path1 = $banner->banner_url;
        $path2 = $banner->banner_mobile_url;
        
        if($request->hasFile('file1')){
            if ($request->file('file1')->isValid()) {
                $old_path1 = $storage_save_path.'/'.$path1;
                if(Storage::exists($old_path1)) {
                    Storage::delete($old_path1);
                }
                $path1 = str_replace($storage_save_path.'/', "", $request->file1->store($storage_save_path));
            }
        }

        if($request->hasFile('file2')){
            if ($request->file('file2')->isValid()) {
                $old_path2 = $storage_save_path.'/'.$path2;
                if(Storage::exists($old_path2)) {
                    Storage::delete($old_path2);
                }
                $path2 = str_replace($storage_save_path.'/', "", $request->file2->store($storage_save_path));
            }
        }
            
        DB::connection('mysql_sub')->table('btc_banners')->where('id', $id)->update([
            'target_url' => $request->input('target_url'),
            'banner_url' => $path1,
            'banner_mobile_url' => $path2,
            'detail' => $request->input('detail'),
            'lang' => $request->input('lang'),
            'active' => $request->input('active'),
        ]);
        
        return Redirect::route('admin.banner_list');
    }
    
    public function banner_delete(Request $request, $id) {
        $storage_save_path = 'public/image/banner';
        
        $banner = DB::connection('mysql_sub')->table('btc_banners')->select('*')->where('id', $id)->first();
        $path1 = $banner->banner_url;
        $path2 = $banner->banner_mobile_url;
        
        $delete_path1 = $storage_save_path.'/'.$path1;
        if(Storage::exists($delete_path1)) {
            Storage::delete($delete_path1);
        }

        $delete_path2 = $storage_save_path.'/'.$path2;
        if(Storage::exists($delete_path2)) {
            Storage::delete($delete_path2);
        }
        
        DB::connection('mysql_sub')->table('btc_banners')->where('id', $id)->delete();
        
        return Redirect::route('admin.banner_list');
    }
    
    public function notify_create(Request $request, $type, $country) {
        $views = view('admin.notify.notify_create');
        $views->type = $type;
        $views->country = $country;
        
        if($type == 1) {
            $market_type = Session::get('market_type');
            $setting = DB::table('btc_settings')->select('name', 'nexmo_api_key', 'nexmo_api_secret')->where('id', $market_type)->first();
            
            $views->check_nexmo = (!blank($setting->nexmo_api_key) && !blank($setting->nexmo_api_secret));
        }
        
        return $views;
    }
    
    public function notify_store(Request $request, $type) {	
        $country = $request->country;	
        if($type == 0) {
            $users = DB::table('users')->select('email')->whereNotNull('email')->where('country',$country)->get(); // 배포코드
            //$users = DB::table('users')->select('email')->whereNotNull('email')->where('country',$country)->where('username','smkim')->get(); // !!! 테스트용 코드  !!!

            // .env 파일에서 환경변수 가져오기
            $directsend_username = env('DIRECTSEND_USERNAME');
            $directsend_key = env('DIRECTSEND_KEY');
            $directsend_sendfrom = env('DIRECTSEND_SENDFROM');
            $directsend_sendfrom_name = env('DIRECTSEND_SENDFROM_NAME');
            
            // 모든 사용자 이메일을 콤마로 구분해서 하나로 합침
            $arr_recipients = array();
            foreach ($users as $user) {
                if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    $arr_recipients[] = $user->email;
                }
            }
            $recipients = implode(',' , $arr_recipients);
            $subject = $request -> input('title');
            $body = $request -> input('description');

            $result = Directsend_mail::send($recipients, $subject, $body, $directsend_username, $directsend_key, $directsend_sendfrom, $directsend_sendfrom_name);
            info('Directsend_mail result: ' . $result);
        } else if($type == 1) {
            $market_type = Session::get('market_type');
            $setting = DB::table('btc_settings')->select('name', 'nexmo_api_key', 'nexmo_api_secret')->where('id', $market_type)->first();
            if(blank($setting->nexmo_api_key) || blank($setting->nexmo_api_secret)) {
                return Redirect::route('admin.notify_create', $type);
            }
            
            $users = DB::table('users')->select('mobile_number')->whereNotNull('mobile_number')->where('country',$country)->whereRaw('length(mobile_number) >= 11')->get(); // 배포코드
            //$users = DB::table('users')->select('mobile_number')->whereNotNull('mobile_number')->where('country',$country)->whereRaw('length(mobile_number) >= 11')->where('username','smkim')->get(); // !!! 테스트용 코드  !!!    
            
            foreach ($users as $user) {
                Nexmo_sms::send_sms($country, $user->mobile_number, $request -> input('description'));
            }
        }

        return Redirect::route('admin.notify_create', ['type' => $type, 'country' => $country]);
    }

    public function account_list(Request $request, $type){
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        $views = view('admin.user.user_account');
        
        $keyword_srch = $request->input('keyword_srch');
        
        $keyword = '';
        
        
        if($type == 5){
            
            if($request->input('keyword') != null){
                $keyword = $request->input('keyword');
            }
            
            if($keyword_srch != null){
                if($keyword_srch == 'uid'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.id',$keyword);
                }else if($keyword_srch == 'name'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.fullname','like','%'.$keyword.'%');
                }else if($keyword_srch == 'email'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.email','like','%'.$keyword);
                }else if($keyword_srch == 'mobile'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.mobile_number','like','%'.$keyword);
                }else{
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')
                    ->where(function($qry) use ($keyword){
                        $qry->where('users.email','like','%'.$keyword.'%')
                        ->orWhere(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
                        ->orWhere('users.mobile_number','like','%'.$keyword.'%')
                        ->orWhere('users.id',$keyword);
                    });    
                }
            }else{
                $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid');
            }
        }else{
            $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('btc_security_lv.account_verified',$type);
            if($request->input('keyword') != null){
                $keyword = $request->input('keyword');
            }
            
            if($keyword_srch != null){
                if($keyword_srch == 'uid'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.id',$keyword);
                }else if($keyword_srch == 'name'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.fullname','like','%'.$keyword.'%')->where('btc_security_lv.account_verified',$type);
                }else if($keyword_srch == 'email'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.email','like','%'.$keyword.'%')->where('btc_security_lv.account_verified',$type);
                }else if($keyword_srch == 'mobile'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.mobile_number','like','%'.$keyword)->where('btc_security_lv.account_verified',$type);
                }else{
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')
                    ->where(function($qry) use ($keyword){
                        $qry->where('users.email','like','%'.$keyword.'%')
                        ->orWhere(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
                        ->orWhere('users.mobile_number','like','%'.$keyword.'%')
                        ->orWhere('users.id',$keyword);
                    });    
                }
            }else{
                $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('btc_security_lv.account_verified',$type);
            }
        }
        
        $securitys = $securitys->orderBy('btc_security_lv.account_verified','desc')->paginate(30);
        
        $securitys->withPath('/admin/account_list/'.$type);
        
        $views->type = $type;
        $views->securitys = $securitys;
        $views->datetime = $datetime;
        
        return $views;
    }
    
    public function document_list(Request $request, $type){
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        $views = view('admin.user.user_document');
        
        $keyword_srch = $request->input('keyword_srch');
        
        $keyword = '';
        
        
        if($type == 5){
            
            if($request->input('keyword') != null){
                $keyword = $request->input('keyword');
            }
            
            if($keyword_srch != null){
                if($keyword_srch == 'uid'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.id',$keyword);
                }else if($keyword_srch == 'name'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.fullname','like','%'.$keyword.'%');
                }else if($keyword_srch == 'email'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.email','like','%'.$keyword.'%');
                }else if($keyword_srch == 'mobile'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.mobile_number','like','%'.$keyword);
                }else{
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')
                    ->where(function($qry) use ($keyword){
                        $qry->where('users.email','like','%'.$keyword.'%')
                        ->orWhere(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
                        ->orWhere('users.mobile_number','like','%'.$keyword.'%')
                        ->orWhere('users.id',$keyword);
                    });    
                }
            }else{
                $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid');
            }
        }else{
            if($request->input('keyword') != null){
                $keyword = $request->input('keyword');
            }
            
            if($keyword_srch != null){
                if($keyword_srch == 'uid'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.id',$keyword)->where('btc_security_lv.document_verified',$type);
                }else if($keyword_srch == 'name'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.fullname','like','%'.$keyword.'%')->where('btc_security_lv.document_verified',$type);
                }else if($keyword_srch == 'email'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.email','like','%'.$keyword.'%')->where('btc_security_lv.document_verified',$type);
                }else if($keyword_srch == 'mobile'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.mobile_number','like','%'.$keyword)->where('btc_security_lv.document_verified',$type);
                }else{
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')
                    ->where(function($qry) use ($keyword){
                        $qry->where('users.email','like','%'.$keyword.'%')
                        ->orWhere(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
                        ->orWhere('users.mobile_number','like','%'.$keyword.'%')
                        ->orWhere('users.id',$keyword);
                    });    
                }
            }else{
                $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('btc_security_lv.document_verified',$type);
            }
        }
        
        $securitys = $securitys->orderBy('btc_security_lv.document_verified','desc')->paginate(30);
        
        $securitys->withPath('/admin/document_list/'.$type);
        
        $views->type = $type;
        $views->securitys = $securitys;
        $views->datetime = $datetime;
        
        return $views;
    }
	
	public function document_agree(Request $request){
		$id = $request->temp_user_id;
		
		info($id);
		
		return 0;	
	}
	
	public function document_reject(Request $request){
		$id = $request->temp_user_id;
		$reason = $request->document_reject;
		
		info($id);
		info($reason);
		
		return 0;	
	}
	
    public function market_edit(){
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        $views = view('admin.setting.market');
        
        $trademarket = DB::table('btc_settings')->where('id',session('market_type'))->first();
        
        $views->trademarket = $trademarket;
        $views->datetime = $datetime;
        
        return $views;
    }
    
    public function market_update(Request $request){
        
        $market = DB::table('btc_settings')->where('id',session('market_type'))->first();
        
        $path = $market->service_icon;
        $path2 = $market->logo;
        
        if($request->hasFile('service_icon')){
            if ($request->file('service_icon')->isValid()) {
                $path = $request->service_icon->store('public/image/homepage/');
                $path = str_replace("public/image/homepage/","",$path);
                
                $img_path = '../storage/app/public/image/homepage/'.$market->service_icon;
                
                if(File::exists($img_path)) {
                    File::delete($img_path);
                }
            }
        }

        if($request->hasFile('logo')){
            if ($request->file('logo')->isValid()) {
                $path2 = $request->logo->store('public/image/homepage/');
                $path2 = str_replace("public/image/homepage/","",$path2);
                
                $img_path = '../storage/app/public/image/homepage/'.$market->logo;
                
                if(File::exists($img_path)) {
                    File::delete($img_path);
                }
            }
        }
        
        DB::table('btc_settings')->where('id',session('market_type'))->update([
            "service_icon" => $path,
            "logo" => $path2,
            "title" => $request->input('title'),
            "name" => $request->input('name'),
            "company" => $request->input('company'),
            "ceo" => $request->input('ceo'),
            "infoemail" => $request->input('infoemail'),
            "business_num" => $request->input('business_num'),
            "sybersell_num" => $request->input('sybersell_num'),
            "phone_num" => $request->input('phone_num'),
            "fax_num" => $request->input('fax_num'),
            "address" => $request->input('address'),
            "address_detail" => $request->input('address_detail'),
            "infor_manager" => $request->input('infor_manager'),
        ]);
		
		LoginTrace::Activity('거래소 정보 변경');
        
        return redirect()->back();
        
    }

    public function fee_edit(){
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $coins = DB::table('btc_coins')->where('active',1)->where('symbol','<>','USD')->get();
        $settings = DB::table('btc_settings')->where('id', 1)->select('buy_comission')->first();

        $views = view('admin.setting.fee');
        $views->datetime = $datetime;
        $views->coins = $coins;
        $views->settings = $settings;
        
        return $views;
    }

    public function fee_update(Request $request){
        $trade_fee = $request->trade_fee;

        DB::table('btc_settings')->where('id', 1)->update([
            'buy_comission' => $trade_fee,
            'sell_comission' => $trade_fee
        ]);

        $coins = DB::table('btc_coins')->where('active',1)->where('symbol','<>','USD')->get();
        foreach($coins as $coin) {
            $fee = $request->{"send_fee_$coin->id"};
            DB::table('btc_coins')->where('id', $coin->id)->update(['send_fee' => $fee]);
        }
		
		LoginTrace::Activity('수수료 설정 변경');
		
        return redirect()->back();
    }

    public function recommender_edit(){
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $settings = DB::table('btc_settings')->where('id', 1)->select('recommender_yn', 'recommender_point')->first();

        $views = view('admin.setting.recommender');
        $views->datetime = $datetime;
        $views->settings = $settings;
        
        return $views;
    }

    public function recommender_update(Request $request){
        $recommender_yn = $request->recommender_yn;
        $recommender_point = $request->recommender_point;

        DB::table('btc_settings')->where('id', 1)->update([
            'recommender_yn' => $recommender_yn,
            'recommender_point' => $recommender_point
        ]);
        
		LoginTrace::Activity('추천인 설정 변경('.$recommender_point.' KRW');
		
        return redirect()->back();
    }

    public function event_list() {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        $events = DB::connection('mysql_sub')->table('btc_events')->select('*');
        $events_page = $events->paginate(20);
        $events_page->withPath('event_list');
        
        $views = view('admin.event.event_list');
        $views->events = $events->get();
        $views->events_page = $events_page;
        $views->datetime = $datetime;
        $views->today = now()->format("Y-m-d");

        return $views;
    }
    
    public function event_create() {
        $views = view('admin.event.event_create');
        return $views;
    }

    public function event_store(Request $request) {
        $storage_save_path = 'public/image/event';
        
        $path1 = null;
        $path2 = null;
        $path3 = null;
        $path4 = null;
        $path5 = null;
        
        if($request->hasFile('file1')){
            if ($request->file('file1')->isValid()) {
                $path1 = str_replace($storage_save_path.'/', "", $request->file1->store($storage_save_path));
            }
        }

        if($request->hasFile('file2')){
            if ($request->file('file2')->isValid()) {
                $path2 = str_replace($storage_save_path.'/', "", $request->file2->store($storage_save_path));
            }
        }

        if($request->hasFile('file3')){
            if ($request->file('file3')->isValid()) {
                $path3 = str_replace($storage_save_path.'/', "", $request->file3->store($storage_save_path));
            }
        }

        if($request->hasFile('file4')){
            if ($request->file('file4')->isValid()) {
                $path4 = str_replace($storage_save_path.'/', "", $request->file4->store($storage_save_path));
            }
        }

        if($request->hasFile('file5')){
            if ($request->file('file5')->isValid()) {
                $path5 = str_replace($storage_save_path.'/', "", $request->file5->store($storage_save_path));
            }
        }
        
        DB::connection('mysql_sub')->table('btc_events')->insert([
            'image_url' => $path1,
            'image_mobile_url' => $path2,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image1' => $path3,
            'image2' => $path4,
            'image3' => $path5,
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'lang' => $request->input('lang'),
            'active' => $request->input('active'),
            'created' => now()->timestamp,
            'updated' => now()->timestamp
        ]);
        
        return Redirect::route('admin.event_list');
    }
    
    public function event_edit(Request $request, $id) {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        $event = DB::connection('mysql_sub')->table('btc_events')->select('*')->where('id', $id)->first();
        
        $views = view('admin.event.event_edit');
        $views->event = $event;
        $views->datetime = $datetime;
        
        return $views;
    }
    
    public function event_update(Request $request, $id) {
        $storage_save_path = 'public/image/event';
        
        $event = DB::connection('mysql_sub')->table('btc_events')->select('*')->where('id', $id)->first();
        $path1 = $event->image_url;
        $path2 = $event->image_mobile_url;
        $path3 = $event->image1;
        $path4 = $event->image2;
        $path5 = $event->image3;
        
        if($request->hasFile('file1')){
            if ($request->file('file1')->isValid()) {
                $old_path1 = $storage_save_path.'/'.$path1;
                if(Storage::exists($old_path1)) {
                    Storage::delete($old_path1);
                }
                $path1 = str_replace($storage_save_path.'/', "", $request->file1->store($storage_save_path));
            }
        }

        if($request->hasFile('file2')){
            if ($request->file('file2')->isValid()) {
                $old_path2 = $storage_save_path.'/'.$path2;
                if(Storage::exists($old_path2)) {
                    Storage::delete($old_path2);
                }
                $path2 = str_replace($storage_save_path.'/', "", $request->file2->store($storage_save_path));
            }
        }

        if($request->hasFile('file3')){
            if ($request->file('file3')->isValid()) {
                $old_path3 = $storage_save_path.'/'.$path3;
                if(Storage::exists($old_path3)) {
                    Storage::delete($old_path3);
                }
                $path3 = str_replace($storage_save_path.'/', "", $request->file3->store($storage_save_path));
            }
        }

        if($request->hasFile('file4')){
            if ($request->file('file4')->isValid()) {
                $old_path4 = $storage_save_path.'/'.$path4;
                if(Storage::exists($old_path4)) {
                    Storage::delete($old_path4);
                }
                $path4 = str_replace($storage_save_path.'/', "", $request->file4->store($storage_save_path));
            }
        }

        if($request->hasFile('file5')){
            if ($request->file('file5')->isValid()) {
                $old_path5 = $storage_save_path.'/'.$path5;
                if(Storage::exists($old_path5)) {
                    Storage::delete($old_path5);
                }
                $path5 = str_replace($storage_save_path.'/', "", $request->file5->store($storage_save_path));
            }
        }
            
        DB::connection('mysql_sub')->table('btc_events')->where('id', $id)->update([
            'image_url' => $path1,
            'image_mobile_url' => $path2,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image1' => $path3,
            'image2' => $path4,
            'image3' => $path5,
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'lang' => $request->input('lang'),
            'active' => $request->input('active'),
            'updated' => now()->timestamp
        ]);
        
        return Redirect::route('admin.event_list');
    }
    
    public function event_delete(Request $request, $id) {
        $storage_save_path = 'public/image/event';
        
        $event = DB::connection('mysql_sub')->table('btc_events')->select('*')->where('id', $id)->first();
        $path1 = $event->image_url;
        $path2 = $event->image_mobile_url;
        $path3 = $event->image1;
        $path4 = $event->image2;
        $path5 = $event->image3;
        
        $delete_path1 = $storage_save_path.'/'.$path1;
        if(Storage::exists($delete_path1)) {
            Storage::delete($delete_path1);
        }

        $delete_path2 = $storage_save_path.'/'.$path2;
        if(Storage::exists($delete_path2)) {
            Storage::delete($delete_path2);
        }

        $delete_path3 = $storage_save_path.'/'.$path3;
        if(Storage::exists($delete_path3)) {
            Storage::delete($delete_path3);
        }

        $delete_path4 = $storage_save_path.'/'.$path4;
        if(Storage::exists($delete_path4)) {
            Storage::delete($delete_path4);
        }

        $delete_path5 = $storage_save_path.'/'.$path5;
        if(Storage::exists($delete_path5)) {
            Storage::delete($delete_path5);
        }
        
        DB::connection('mysql_sub')->table('btc_events')->where('id', $id)->delete();
        
        return Redirect::route('admin.event_list');
    }

    public function coin_lock_list() {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        $coins = DB::table('btc_lock_coins')->select('*');
        $coins_page = $coins->paginate(20);
        $coins_page->withPath('coin_lock_list');
        
        $views = view('admin.coin_lock.coin_lock_list');
        $views->coins = $coins->get();
        $views->coins_page = $coins_page;
        $views->datetime = $datetime;

        return $views;
    }

    public function coin_lock_action(Request $request, $id, $type) {
        // -1:종료중, 0:진행안함, 1:진행중	
        if($type == 'start') {
            DB::table('btc_lock_coins')->where('id', $id)->where('status', 0)->update(['status' => 1, 'updated_dt' => now()]);
			LoginTrace::Activity('코인락 진행중으로 상태변경');
        } elseif($type == 'exit') {
            DB::table('btc_lock_coins')->where('id', $id)->where('status', 1)->update(['status' => -1, 'updated_dt' => now()]);
			LoginTrace::Activity('코인락 종료로 상태변경');
        } elseif($type == 'cancel_exit') {
            DB::table('btc_lock_coins')->where('id', $id)->where('status', -1)->update(['status' => 1, 'updated_dt' => now()]);
			LoginTrace::Activity('코인락 종료를 진행으로 상태변경');
        }
		
		
		
        return Redirect::route('admin.coin_lock_list');
    }
    
    public function coin_lock_create() {
        $available_coins = DB::table('btc_coins')
            ->select('*')
            ->where('active', 1)
            ->where('cointype', '<>', 'cash')
            ->whereNotIn('api', function($query) { $query->select('coin')->from('btc_lock_coins');})
            ->get();

        $views = view('admin.coin_lock.coin_lock_create');
        $views->available_coins = $available_coins;
		
		
		
        return $views;
    }

    public function coin_lock_store(Request $request) {
        $coin = $request->input('coin');
        $ratio = $request->input('ratio');

        if($ratio == '') {
        } elseif(strlen($ratio) > 6) {
        } elseif(!is_numeric($ratio)) {
        } else {
            $float_ratio = (float) $ratio;
            if($float_ratio <= 0) {
            } elseif($float_ratio > 1) {
            } else {
                $coin_count = DB::table('btc_lock_coins')->where('coin', $coin)->count();
                if($coin_count > 0) {
                } else {
                    DB::table('btc_lock_coins')->insert([
                        'coin' => $coin,
                        'ratio' => $ratio,
                        'status' => 0,
                        'created_dt' => now(),
                        'updated_dt' => now()
                    ]);
                }
            }
        }
        
		LoginTrace::Activity('코인락 생성');
		
        return Redirect::route('admin.coin_lock_list');
    }
    
    public function coin_lock_edit(Request $request, $id) {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        $coin = DB::table('btc_lock_coins')->where('id', $id)->first();
        
        $views = view('admin.coin_lock.coin_lock_edit');
        $views->coin = $coin;
        $views->datetime = $datetime;
        
        return $views;
    }
    
    public function coin_lock_update(Request $request, $id) {
        $ratio = $request->input('ratio');
        
        if($ratio == '') {
        } elseif(strlen($ratio) > 6) {
        } elseif(!is_numeric($ratio)) {
        } else {
            $float_ratio = (float) $ratio;
            if($float_ratio <= 0) {
            } elseif($float_ratio > 1) {
            } else {
                DB::table('btc_lock_coins')->where('id', $id)->update([
                    'ratio' => $ratio,
                    'updated_dt' => now()
                ]);
				LoginTrace::Activity('코인락 수수료 배분비율 '.$ratio.'로 수정');
            }
        }
        
        return Redirect::route('admin.coin_lock_list');
    }

    public function coin_tr_list(Request $request) {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $keyword_srch = $request->input('keyword_srch');
        $keyword = $request->input('keyword');
		
        

        if($keyword_srch != null) {
            $keyword = $keyword != null ? $keyword : '';
            if($keyword_srch == 'uid') {
                $transactions = DB::table('btc_transaction')
                ->join('users','btc_transaction.account','=','users.username')
            	->select('btc_transaction.*','users.id as uid','users.email' ,'users.mobile_number' ,'users.fullname' )
            	->where('btc_transaction.category', '<>', 'trade')
                ->where('users.id',$keyword)
				->orderBy('btc_transaction.id','desc');
            } else if($keyword_srch == 'email') {
                $transactions = DB::table('btc_transaction')
                ->join('users','btc_transaction.account','=','users.username')
            	->select('btc_transaction.*','users.id as uid','users.email' ,'users.mobile_number' ,'users.fullname' )
            	->where('btc_transaction.category', '<>', 'trade')
                ->where('users.email','like','%'.$keyword.'%')
				->orderBy('btc_transaction.id','desc');
            	
			} else if($keyword_srch == 'mobile_number') {
                $transactions = DB::table('btc_transaction')
                ->join('users','btc_transaction.account','=','users.username')
            	->select('btc_transaction.*','users.id as uid','users.email' ,'users.mobile_number' ,'users.fullname' )
            	->where('btc_transaction.category', '<>', 'trade')
                ->where('users.mobile_number','like','%'.$keyword.'%')
				->orderBy('btc_transaction.id','desc');
            	
			} else if($keyword_srch == 'fullname') {
                $transactions = DB::table('btc_transaction')
                ->join('users','btc_transaction.account','=','users.username')
            	->select('btc_transaction.*','users.id as uid','users.email' ,'users.mobile_number' ,'users.fullname' )
            	->where('btc_transaction.category', '<>', 'trade')
                ->where(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
				->orderBy('btc_transaction.id','desc');
            	
			} else {
            	$transactions = DB::table('btc_transaction')
            	->join('users','btc_transaction.account','=','users.username')
            	->select('btc_transaction.*','users.id as uid','users.email' ,'users.mobile_number' ,'users.fullname' )
            	->where('btc_transaction.category', '<>', 'trade')
            	->where(function($qry) use ($keyword){
                    $qry->where('users.email','like','%'.$keyword.'%')
                    ->orWhere(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
                    ->orWhere('users.mobile_number','like','%'.$keyword.'%')
                    ->orWhere('users.id',$keyword)
					->orwhereRaw("LOWER(cointype) like LOWER('%$keyword%')");
                })
            	->orderBy('btc_transaction.id','desc');
            }
        }else{
        	$transactions = DB::table('btc_transaction')
        	->join('users','btc_transaction.account','=','users.username')
            ->select('btc_transaction.*','users.id as uid','users.email' ,'users.mobile_number' ,'users.fullname' )
            ->where('btc_transaction.category', '<>', 'trade')
        	->orderBy('btc_transaction.id','desc');
        }
        
        $transactions_page = $transactions->paginate(20)->appends(request()->query());
        $transactions_page->withPath('coin_tr_list');
        
        if($keyword_srch != null) {
            $transactions_page->appends(['keyword_srch' => $keyword_srch, 'keyword' => $keyword])->links();
        }
        
        $views = view('admin.coin_tr.coin_tr_list');
        $views->transactions = $transactions->get();
        $views->transactions_page = $transactions_page;
        $views->datetime = $datetime;

        return $views;
    }

    public function coin_tr_excel(Request $request){
        $from = $request->from;
        $to = $request->to;

        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
                
        return Excel::download(new CoinTrsExport($from, $to), '입출금이력리스트_' .  date("Y-m-d") . '.xlsx'); 
    }

    public function airdrop_list() {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        $today = date("Y-m-d H:i:s");
        $airdrops = DB::table('btc_airdrop')->select('*')->orderBy('status','desc')->get();
        foreach ($airdrops as $airdrop) {
            $id = $airdrop->id;
            $start_time = $airdrop->start_time;
            $end_time = $airdrop->end_time;

            if($start_time <= $today && $today <= $end_time) {
                DB::table('btc_airdrop')->where('id', $id)->update(['status' => 1]);
            } elseif($today > $end_time) {
                DB::table('btc_airdrop')->where('id', $id)->update(['status' => 0]);
            }
        }
        $airdrops = DB::table('btc_airdrop')->select('*')->orderBy('status','desc')->get();
        
        $views = view('admin.airdrop.airdrop_list');
        $views->airdrops = $airdrops;
        $views->datetime = $datetime;

        return $views;
    }

    public function airdrop_create() {
        $coins = DB::table('btc_coins')->where('active', 1)->where('cointype', '<>', 'cash')->orderBy('id','asc')->get();

        $views = view('admin.airdrop.airdrop_create');
        $views->coins = $coins;

        return $views;
    }

    public function airdrop_store(Request $request) {
        DB::table('btc_airdrop')->insert([
            'title' => $request->title,
            'coin' => $request->coin,
            'cases' => $request->cases,
            'overlap_yn' =>  $request->overlap_yn,
            'all_cnt' =>  $request->all_cnt,
            'send_cnt' =>  $request->send_cnt,
            'residual_cnt' =>  $request->residual_cnt,
            'start_time' =>  $request->start_time,
            'end_time' =>  $request->end_time
        ]);
		LoginTrace::Activity($request->title.' 에어드랍 생성');
        return Redirect::route('admin.airdrop_list');
    }

    public function airdrop_edit(Request $request, $id) {
        $coins = DB::table('btc_coins')->where('active', 1)->where('cointype', '<>', 'cash')->orderBy('id','asc')->get();
        $airdrop = DB::table('btc_airdrop')->select('*')->where('id', $id)->first();

        $views = view('admin.airdrop.airdrop_edit');
        $views->coins = $coins;
        $views->airdrop = $airdrop;

        return $views;
    }

    public function airdrop_update(Request $request, $id) {
        DB::table('btc_airdrop')->where('id', $id)->update([
            'title' => $request->title,
            'coin' => $request->coin,
            'cases' => $request->cases,
            'overlap_yn' =>  $request->overlap_yn,
            'all_cnt' =>  $request->all_cnt,
            'send_cnt' =>  $request->send_cnt,
            'residual_cnt' =>  $request->residual_cnt,
            'start_time' =>  $request->start_time,
            'end_time' =>  $request->end_time
        ]);
		LoginTrace::Activity($request->title.' 에어드랍 수정');
        return Redirect::route('admin.airdrop_list');
    }

    public function p2p_list($type) {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        $today = date("Y-m-d H:i:s");
        if($type == 6){
            $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('state','stop')->orderBy('id','desc')->get();
        }
        elseif($type == 5){
            $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',1)->orderBy('id','desc')->get();
        }else{
            $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('confirm',$type)->where('state','<>','stop')->orderBy('id','desc')->get();
        }
        //$p2ps = DB::table('btc_p2p')->select('*')->orderBy('id','desc')->get();
                

        $views = view('admin.p2p.p2p_list');
        $views->p2ps = $p2ps;
        $views->datetime = $datetime;
        $views->types = $type;

        return $views;
    }

    public function p2p_confirm($id){
        $confirm = DB::connection('mysql_sub')->table('btc_p2p')->select('confirm')->where('id',$id)->first();
        if($confirm->confirm>0 && $confirm->confirm<4){
            if($confirm->confirm==3){
                DB::connection('mysql_sub')->table('btc_p2p')
                ->where('id',$id)
                ->where('confirm',$confirm->confirm)
                ->update([
                        'confirm'=>$confirm->confirm+1,
                        'state'=>'complete',
                        'end' => now(),
                        'update_at'=>now()
                        ]);
    
                DB::connection('mysql_sub')->table('btc_p2p_user')
                ->where('pid',$id)
                ->update([
                        'end_day' => now(),
                        'update_at'=> now()
                        ]);
        
            }else{
                DB::connection('mysql_sub')->table('btc_p2p')
                ->where('id',$id)
                ->where('confirm',$confirm->confirm)
                ->update([
                        'confirm'=>$confirm->confirm+1,
                        'update_at'=>now()
                        ]);
        
                DB::connection('mysql_sub')->table('btc_p2p_user')
                ->where('pid',$id)
                ->update([
                        'update_at'=>now()
                        ]);
                return back();
            }
        }
        return back();
    }
    public function p2p_stop($id){
        DB::connection('mysql_sub')->table('btc_p2p')
        ->where('id',$id)
        ->update([
                'state'=>'stop',
                'update_at'=>now()
                ]);
        return back();
    }
    public function p2p_detail($id){
        $p2ps = DB::connection('mysql_sub')->table('btc_p2p_user')->where('pid',$id)->get();
        $table = DB::connection('mysql_sub')->table('btc_p2p')->where('id',$id)->first();
        $views = view('admin.p2p.p2p_detail');
        
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        $today = date("Y-m-d H:i:s");
        $views->table = $table;
        $views->p2ps = $p2ps;
        $views->datetime = $datetime;
        return $views;		
    }
    // 권한관리
    
    public function rights_management_list(Request $request){
        Log::info(Auth::guard('admin')->user()->level);
        
        $keyword_srch = $request->keyword_srch;
        
        $keyword = '';
        
        if($request->keyword != null){
            $keyword = $request->keyword;
        }
        
        if($keyword_srch != null){
            if($keyword_srch == 'name'){
                $admins = DB::table('admin')->where('fullname','like','%'.$keyword.'%')->orderBy('id','asc')->paginate(20);
            }else if($keyword_srch == 'email'){
                $admins = DB::table('admin')->where('email','like','%'.$keyword.'%')->orderBy('id','asc')->paginate(20);
            }else if($keyword_srch == 'mobile'){
                $admins = DB::table('admin')->where('mobile_number','like','%'.$keyword.'%')->orderBy('id','asc')->paginate(20);
            }else{
                $admins = DB::table('admin')->orderBy('id','asc')->paginate(20);
            }
        }else{
            $admins = DB::table('admin')->orderBy('id','asc')->paginate(20);
        }
        $admins->withPath('/admin/rights_management_list');
        
        $views = view('admin.setting.rights_management_list');
        
        $views->admins = $admins; 
        
        return $views;
    }
    
    public function rights_management_create(){
        $views = view('admin.setting.rights_management_create');
        
        return $views;
    }
    
    public function rights_management_store(Request $request){
        DB::table('admin')->insert([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'fullname' => $request->fullname,
            'mobile_number' => $request->mobile_number,
            'market_type' => session('market_type'),
            'level' => $request->level,
            'time_signup' =>  date("Y-m-d H:i:s"),
            'ip' => $_SERVER['REMOTE_ADDR'],
        ]);
        
		LoginTrace::Activity('관리자 계정 생성');
		
        return Redirect::route('admin.rights_management_list');
    }
    
    public function rights_management_edit(Request $request, $id){
        $admin = DB::table('admin')->where('id',$id)->first();
        
        $views = view('admin.setting.rights_management_edit');
        
        $views->admin = $admin;
		
		
        
        return $views;
    }
    
    public function rights_management_update(Request $request, $id){
        DB::table('admin')->where('id', $id)->update([
            'email' => $request->email,
            'fullname' => $request->fullname,
            'mobile_number' => $request->mobile_number,
            'market_type' => session('market_type'),
            'level' => $request->level,
            'time_signup' =>  date("Y-m-d H:i:s"),
            'ip' => $_SERVER['REMOTE_ADDR'],
        ]);
		
		LoginTrace::Activity('관리자 편집');
        
        return Redirect::route('admin.rights_management_list');
    }

    public function rights_management_password_edit(Request $request, $id){
        $admin = DB::table('admin')->where('id',$id)->first();
        
        $views = view('admin.setting.rights_management_password_edit');
        
        $views->admin = $admin;
		
        return $views;
    }
    
    public function rights_management_password_update(Request $request, $id){
        DB::table('admin')->where('id', $id)->update([
            'password' => Hash::make($request->password),
        ]);
		
		LoginTrace::Activity('관리자 비밀번호 변경');
        
        return Redirect::route('admin.rights_management_list');
    }
    
    public function rights_management_delete($id){
        DB::table('admin')->where('id', $id)->delete();
    	
		LoginTrace::Activity('관리자 계정 삭제');
		
        return Redirect::route('admin.rights_management_list');
    }
	
	//관리자 활동내역
	public function admin_activity(Request $request){
        //

        $admin_activities = DB::connection('mysql_sub')->table('btc_admin_activity')->orderBy('id','desc')->paginate(20);
       
        $admin_activities->withPath('/admin/admin_activity');
        
        $views = view('admin.setting.admin_activity');
        
        $views->admin_activities = $admin_activities; 
        
        return $views;
    }
	
    //NOTICE

    public function notice_list($country){
        $notices = DB::connection('mysql_sub')->table('btc_notice_'.$country)->orderBy('id','desc')->paginate(20);

        $notices->withPath('/admin/notice/'.$country);

        $datetime = date("H시 i분 s초");

        $views = view('admin.notice.notice_list');

        $views->notices = $notices;
        $views->country = $country;
        $views->datetime = $datetime;
        
        return $views;
    }

    public function notice_edit($country, $id){
        $notice = DB::connection('mysql_sub')->table('btc_notice_'.$country)->where('id',$id)->first();

        $views = view('admin.notice.notice_edit');

        $views->notice = $notice;
        $views->country = $country;
        $views->id = $id;
        
        return $views;
    }

    public function notice_create($country){

        $views = view('admin.notice.notice_create');
        $views->country = $country;

        
        return $views;
    }
    

    public function notice_insert(Request $request){
        $country = $request->country;
        $id = DB::connection('mysql_sub')->table('btc_notice_'.$country)->insertGetId([
            "title" => $request->title,
            "description" => $request->description,
            "created" => time(),
            "updated" => time(),
        ]);
        $data = array();
        $data['topic_id'] = $id;
        $data['title'] = "공지사항";
        $data['body'] = $request->title;
        $fcm_return_key = FcmPush::push_topic("notice", $data);
        info($fcm_return_key);
        return redirect()->route('admin.notice_edit', ['country' => $country, 'id' => $id]);
    }

    public function notice_update(Request $request, $id){

        $country = $request->input('country');
        $notice = DB::connection('mysql_sub')->table('btc_notice_'.$country)->where('id', $id)->first();

        $status = DB::connection('mysql_sub')->table('btc_notice_'.$country)->where('id',$id)->update([
            "title" => $request->input('title'),
            "description" => $request->input('description'),
            "updated" => time(),
        ]);

        if($status){
            $origin_images = File_store::getImages($notice->description);
            $new_images = File_store::getImages($request->description);

            File_store::imageUpdate($origin_images, $new_images);
        }

        return redirect()->back();
    }

    public function notice_delete($country, $id){
        $notice = DB::connection('mysql_sub')->table('btc_notice_'.$country)->where('id', $id)->first();

        DB::connection('mysql_sub')->table('btc_notice_'.$country)->where('id',$id)->delete();

        // 기존 이미지들 삭제
        $origin_images = File_store::getImages($notice->description);

        $default_path = '../storage/app/public/image';

        foreach($origin_images as $origin_image){
            $img_path = $default_path.$origin_image;
            if(File::exists($img_path)) {
                File::delete($img_path);
            }
        }

        return redirect()->route('admin.notice_list', $country);
    }

    //NEWSFLASH (속보)

    public function newsflash_list($country){
        $newsflashs = DB::connection('mysql_sub')->table('btc_newsflash_'.$country)->orderBy('id','desc')->paginate(20);

        $newsflashs->withPath('/admin/newsflash/'.$country);

        $datetime = date("H시 i분 s초");

        $views = view('admin.newsflash.newsflash_list');

        $views->newsflashs = $newsflashs;
        $views->country = $country;
        $views->datetime = $datetime;
        
        return $views;
    }

    public function newsflash_edit($country, $id){
        $newsflash = DB::connection('mysql_sub')->table('btc_newsflash_'.$country)->where('id',$id)->first();

        $views = view('admin.newsflash.newsflash_edit');

        $views->newsflash = $newsflash;
        $views->country = $country;
        $views->id = $id;
        
        return $views;
    }

    public function newsflash_create($country){

        $views = view('admin.newsflash.newsflash_create');
        $views->country = $country;

        
        return $views;
    }
    

    public function newsflash_insert(Request $request){
        $country = $request->country;
        $id = DB::connection('mysql_sub')->table('btc_newsflash_'.$country)->insertGetId([
            "title" => $request->title,
            "description" => $request->description,
            "created" => time(),
            "updated" => time(),
        ]);
        $data = array();
        $data['topic_id'] = $id;
        $data['title'] = "공지사항";
        $data['body'] = $request->title;
        $fcm_return_key = FcmPush::push_topic("newsflash", $data);
        info($fcm_return_key);
        return redirect()->route('admin.newsflash_edit', ['country' => $country, 'id' => $id]);
    }

    public function newsflash_update(Request $request, $id){

        $country = $request->input('country');
        $newsflash = DB::connection('mysql_sub')->table('btc_newsflash_'.$country)->where('id', $id)->first();

        $status = DB::connection('mysql_sub')->table('btc_newsflash_'.$country)->where('id',$id)->update([
            "title" => $request->input('title'),
            "description" => $request->input('description'),
            "updated" => time(),
        ]);

        if($status){
            $origin_images = File_store::getImages($newsflash->description);
            $new_images = File_store::getImages($request->description);

            File_store::imageUpdate($origin_images, $new_images);
        }

        return redirect()->back();
    }

    public function newsflash_delete($country, $id){
        $newsflash = DB::connection('mysql_sub')->table('btc_newsflash_'.$country)->where('id', $id)->first();

        DB::connection('mysql_sub')->table('btc_newsflash_'.$country)->where('id',$id)->delete();

        // 기존 이미지들 삭제
        $origin_images = File_store::getImages($newsflash->description);

        $default_path = '../storage/app/public/image';

        foreach($origin_images as $origin_image){
            $img_path = $default_path.$origin_image;
            if(File::exists($img_path)) {
                File::delete($img_path);
            }
        }

        return redirect()->route('admin.newsflash_list', $country);
    }

    //언론보도

    public function news_list($country){
        $news_lists = DB::connection('mysql_sub')->table('btc_news_'.$country)->orderBy('id','desc')->paginate(20);

        $news_lists->withPath('/admin/news');

        $datetime = date("H시 i분 s초");

        $views = view('admin.news.news_list');

        $views->news_lists = $news_lists;
        $views->country = $country;
        $views->datetime = $datetime;
        
        return $views;
    }

    public function news_edit($country, $id){
        $news = DB::connection('mysql_sub')->table('btc_news_'.$country)->where('id',$id)->first();

        $views = view('admin.news.news_edit');

        $views->news = $news;
        $views->country = $country;
        $views->id = $id;
        
        return $views;
    }

    public function news_create($country){

        $views = view('admin.news.news_create');
        $views->country = $country;

        
        return $views;
    }
    

    public function news_insert(Request $request){
        $country = $request->country;

        $images = File_store::Image_store('news', $request->image);

        if(gettype($images) == "array"){
            $id = DB::connection('mysql_sub')->table('btc_news_'.$country)->insertGetId([
                "title" => $request->title,
                "description" => $request->description,
                "thumb_img" => $images[0],
                "created" => time(),
                "updated" => time(),
            ]);
        }else if(gettype($files) == "string"){
            $id = DB::connection('mysql_sub')->table('btc_news_'.$country)->insertGetId([
                "title" => $request->title,
                "description" => $request->description,
                "created" => time(),
                "updated" => time(),
            ]);
        }

        return redirect()->route('admin.news_edit', ['country' => $country, 'id' => $id]);
    }

    public function news_update(Request $request, $id){

        $country = $request->input('country');

        if(isset($request->image)){
            $images = File_store::Image_store('news', $request->image);
            $news = DB::connection('mysql_sub')->table('btc_news_'.$country)->where('id',$id)->first();
            
            if($news->thumb_img != NULL){
                $img_path = '../storage/app/public/image/news/'.$news->thumb_img;
                    
                if(File::exists($img_path)) {
                    File::delete($img_path);
                }
            }
            
            DB::connection('mysql_sub')->table('btc_news_'.$country)->where('id',$id)->update([
                "title" => $request->input('title'),
                "description" => $request->input('description'),
                "thumb_img" => $images[0],
                "updated" => time(),
            ]);
        }else{
            DB::connection('mysql_sub')->table('btc_news_'.$country)->where('id',$id)->update([
                "title" => $request->input('title'),
                "description" => $request->input('description'),
                "updated" => time(),
            ]);
        }

        return redirect()->back();
    }

    public function news_delete($country, $id){
        $news = DB::connection('mysql_sub')->table('btc_news_'.$country)->where('id',$id)->first();
        
        if($news->thumb_img != NULL){
            $img_path = '../storage/app/public/image/news/'.$news->thumb_img;
                
            if(File::exists($img_path)) {
                File::delete($img_path);
            }
        }

        DB::connection('mysql_sub')->table('btc_news_'.$country)->where('id',$id)->delete();

        return redirect()->route('admin.news_list', $country);
    }

    //FAQ

    public function faq_list($country, $types){
        if($types == 0){
            $faqs = DB::connection('mysql_sub')->table('btc_faq_'.$country)->orderBy('id','desc')->paginate(20);
        }else{
            $faqs = DB::connection('mysql_sub')->table('btc_faq_'.$country)->where('faq_type',$types)->orderBy('id','desc')->paginate(20);
        }

        $faqs->withPath('/admin/faqs');

        $datetime = date("H시 i분 s초");

        $views = view('admin.faq.faq_list');

        $views->faqs = $faqs;
        $views->country = $country;
        $views->types = $types;
        $views->datetime = $datetime;
        
        return $views;
    }

    public function faq_edit($country, $id){
        $faq = DB::connection('mysql_sub')->table('btc_faq_'.$country)->where('id',$id)->first();

        $views = view('admin.faq.faq_edit');

        $views->faq = $faq;
        $views->country = $country;
        $views->id = $id;
        
        return $views;
    }

    public function faq_create($country){

        $views = view('admin.faq.faq_create');
        $views->country = $country;

        
        return $views;
    }
    

    public function faq_insert(Request $request){
        $country = $request->country;


        $id = DB::connection('mysql_sub')->table('btc_faq_'.$country)->insertGetId([
            "question" => $request->title,
            "answer" => $request->description,
            "faq_type" => $request->faq_type,
            "created" => time(),
            "updated" => time(),
        ]);

        return redirect()->route('admin.faq_edit', ['country' => $country, 'id' => $id]);
    }

    public function faq_update(Request $request, $id){

        $country = $request->input('country');

        $faq = DB::connection('mysql_sub')->table('btc_faq_'.$country)->where('id', $id)->first();

        $status = DB::connection('mysql_sub')->table('btc_faq_'.$country)->where('id',$id)->update([
            "question" => $request->title,
            "answer" => $request->description,
            "faq_type" => $request->faq_type,
            "updated" => time(),
        ]);

        if($status){
            $origin_images = File_store::getImages($faq->answer);
            $new_images = File_store::getImages($request->description);

            File_store::imageUpdate($origin_images, $new_images);
        }

        return redirect()->back();
    }

    public function faq_delete($country, $id){
        $faq = DB::connection('mysql_sub')->table('btc_faq_'.$country)->where('id', $id)->first();

        DB::connection('mysql_sub')->table('btc_faq_'.$country)->where('id',$id)->delete();

        // 기존 이미지들 삭제
        $origin_images = File_store::getImages($faq->answer);

        $default_path = '../storage/app/public/image';

        foreach($origin_images as $origin_image){
            $img_path = $default_path.$origin_image;
            if(File::exists($img_path)) {
                File::delete($img_path);
            }
        }

        return redirect()->route('admin.faq_list', [ 'country'=>$country, 'types'=>0 ]);
    }

    //QNA

    public function qna_list($country){
        //$qnas = DB::connection('mysql_sub')->table('btc_qna')->where('country',$country)->orderBy('id','desc')->paginate(20);
        $qnas = DB::table('html.users')
                    ->leftJoin('html_read.btc_qna','html_read.btc_qna.createdby','=','html.users.username')->where('html_read.btc_qna.country',$country)->orderBy('html_read.btc_qna.id','desc')->paginate(20);
        $qnas->withPath('/admin/qna/'.$country);

        $datetime = date("H시 i분 s초");

        $views = view('admin.qna.qna_list');

        $views->qnas = $qnas;
        $views->country = $country;
        $views->datetime = $datetime;
        
        return $views;
    }

    public function qna_answer_create($id){
        $qna_count = DB::connection('mysql_sub')->table('btc_qna')->where('id','>',$id)->count('id');
        $page_number = bcdiv($qna_count,20,0) + 1;
        $qna = DB::connection('mysql_sub')->table('btc_qna')->where('id',$id)->first();
        $user = DB::table('users')->where('email',$qna->createdby)->first();
        $views = view('admin.qna.qna_answer_create');
        
        $views->qna = $qna;
        $views->user = $user;
        $views->id = $id;
        $views->page = $page_number;
        
        return $views;
    }

    public function qna_answer_edit($id){
        $qna_count = DB::connection('mysql_sub')->table('btc_qna')->where('id','>',$id)->count('id');
        $page_number = bcdiv($qna_count,20,0) + 1;
        $qna = DB::connection('mysql_sub')->table('btc_qna')->where('id',$id)->first();
        $user = DB::table('users')->where('email',$qna->createdby)->first();
        $qna_answer = DB::connection('mysql_sub')->table('btc_qna_comment')->where('qna_id',$id)->first();

        $views = view('admin.qna.qna_answer_edit');

        $views->qna = $qna;
        $views->qna_answer = $qna_answer;
        $views->user = $user;
        $views->id = $id;
        $views->page = $page_number;
        
        return $views;
    }
    

    public function qna_answer_insert(Request $request, $id){
        DB::connection('mysql_sub')->table('btc_qna')->where('id',$id)->update([
            "answered" => 1,
        ]);

        DB::connection('mysql_sub')->table('btc_qna_comment')->insertGetId([
            "qna_id" => $id,
            "title" => "댓글",
            "description" => $request->description,
            "createdby" => "admin",
            "created" => time(),
            "updated" => time(),
        ]);

        return redirect()->route('admin.qna_answer_edit', $id);
    }

    public function qna_answer_update(Request $request, $id){

        $qna = DB::connection('mysql_sub')->table('btc_qna_comment')->where('qna_id', $id)->first();

        $status = DB::connection('mysql_sub')->table('btc_qna_comment')->where('qna_id',$id)->update([
            "description" => $request->description,
            "updated" => time(),
        ]);

        if($status){
            $origin_images = File_store::getImages($qna->description);
            $new_images = File_store::getImages($request->description);

            File_store::imageUpdate($origin_images, $new_images);
        }

        return redirect()->back();
    }

    public function qna_delete($country, $id){
        $qna_answer = DB::connection('mysql_sub')->table('btc_qna_comment')->where('qna_id', $id)->first();
        $qna = DB::connection('mysql_sub')->table('btc_qna')->where('id', $id)->first();

        DB::connection('mysql_sub')->table('btc_qna')->where('id',$id)->delete();
        DB::connection('mysql_sub')->table('btc_qna_comment')->where('qna_id',$id)->delete();

        // 기존 이미지들 삭제
        $origin_images = File_store::getImages($qna_answer->description);

        $default_path = '../storage/app/public/image';

        foreach($origin_images as $origin_image){
            $img_path = $default_path.$origin_image;
            if(File::exists($img_path)) {
                File::delete($img_path);
            }
        }

        $img_path = $default_path.'/qna/'.$qna->image_url;
        if(File::exists($img_path)) {
            File::delete($img_path);
        }

        return redirect()->route('admin.qna_list',$country);
    }

    public function trade_history(Request $request){
        $cointype = $request->cointype;
        $srch = $request->srch;

        if($request->srch == NULL || !isset($request->srch)){
            $srch = '';
        }

        if($request->cointype == NULL || !isset($request->cointype)){
            $cointype = 'all';
        }
		
		if($cointype != null) {
            $srch = $srch != null ? $srch : '';
            if($cointype == 'uid') {
                $trade_historys = DB::table('btc_trades_COIN_btc')
                ->join('users AS buyer','btc_trades_COIN_btc.buyer_uid','=','buyer.id')
                ->join('users AS seller','btc_trades_COIN_btc.seller_uid','=','seller.id')
            	->select('btc_trades_COIN_btc.*', 'buyer.fullname AS buyer_fullname', 'seller.fullname AS seller_fullname' )
            	->where('buyer.id',$srch)->orwhere('seller.id')
				->orderBy('btc_trades_COIN_btc.id','desc')->paginate(30)->appends(request()->query());
            } else if($cointype == 'email') {
                $trade_historys = DB::table('btc_trades_COIN_btc')
                ->join('users AS buyer','btc_trades_COIN_btc.buyer_uid','=','buyer.id')
                ->join('users AS seller','btc_trades_COIN_btc.seller_uid','=','seller.id')
            	->select('btc_trades_COIN_btc.*', 'buyer.fullname AS buyer_fullname', 'seller.fullname AS seller_fullname' )
            	->where('buyer.email','like','%'.$srch.'%')->orwhere('seller.email','like','%'.$srch.'%')
				->orderBy('btc_trades_COIN_btc.id','desc')->paginate(30)->appends(request()->query());
            	
			} else if($cointype == 'mobile_number') {
                $trade_historys = DB::table('btc_trades_COIN_btc')
                ->join('users AS buyer','btc_trades_COIN_btc.buyer_uid','=','buyer.id')
                ->join('users AS seller','btc_trades_COIN_btc.seller_uid','=','seller.id')
                ->select('btc_trades_COIN_btc.*', 'buyer.fullname AS buyer_fullname', 'seller.fullname AS seller_fullname' )
                ->where('buyer.mobile_number','like','%'.$srch.'%')->orwhere('seller.mobile_number','like','%'.$srch.'%')
				->orderBy('btc_trades_COIN_btc.id','desc')->paginate(30)->appends(request()->query());
            	
			} else if($cointype == 'fullname') {
                $trade_historys = DB::table('btc_trades_COIN_btc')
                ->join('users AS buyer','btc_trades_COIN_btc.buyer_uid','=','buyer.id')
                ->join('users AS seller','btc_trades_COIN_btc.seller_uid','=','seller.id')
                ->select('btc_trades_COIN_btc.*', 'buyer.fullname AS buyer_fullname', 'seller.fullname AS seller_fullname' )
                ->where(DB::raw("REPLACE(buyer.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$srch.'%'))
                ->orwhere(DB::raw("REPLACE(seller.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$srch.'%'))
				->orderBy('btc_trades_COIN_btc.id','desc')->paginate(30)->appends(request()->query());
            	
			} else {
            	$trade_historys = DB::table('btc_trades_COIN_btc')
            	->join('users AS buyer','btc_trades_COIN_btc.buyer_uid','=','buyer.id')
                ->join('users AS seller','btc_trades_COIN_btc.seller_uid','=','seller.id')
            	->select('btc_trades_COIN_btc.*', 'buyer.fullname AS buyer_fullname', 'seller.fullname AS seller_fullname' )
            	->where(function($qry) use ($srch){
                    $qry->where('buyer.email','like','%'.$srch.'%')
                    ->orWhere(DB::raw("REPLACE(buyer.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$srch.'%'))
                    ->orWhere('buyer.mobile_number','like','%'.$srch.'%')
                    ->orWhere('buyer.id',$srch)
                    ->orwhereRaw("LOWER(btc_trades_COIN_btc.cointype) like LOWER('%$srch%')")
                    ->orwhere('seller.email','like','%'.$srch.'%')
                    ->orWhere(DB::raw("REPLACE(seller.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$srch.'%'))
                    ->orWhere('seller.mobile_number','like','%'.$srch.'%')
                    ->orWhere('seller.id',$srch)
					->orwhereRaw("LOWER(btc_trades_COIN_btc.cointype) like LOWER('%$srch%')");
                })
            	->orderBy('btc_trades_COIN_btc.id','desc')->paginate(30)->appends(request()->query());
            }
        }else{
        	$trade_historys = DB::table('btc_trades_COIN_btc')
        	->join('users AS buyer','btc_trades_COIN_btc.buyer_uid','=','buyer.id')
            ->join('users AS seller','btc_trades_COIN_btc.seller_uid','=','seller.id')
            ->select('btc_trades_COIN_btc.*', 'buyer.fullname AS buyer_fullname', 'seller.fullname AS seller_fullname' )
            ->orderBy('btc_trades_COIN_btc.id','desc')->paginate(30)->appends(request()->query());
        }

        $coins = DB::table('btc_coins')->where('cointype','<>','cash')->where('active',1)->get();

        $trade_historys->withPath('/admin/trade/trade_history');

        $views = view('admin.trade.trade_history');

        $datetime = date("H시 i분 s초");

        $views->datetime = $datetime;
        $views->trade_historys = $trade_historys;
        $views->coins = $coins;
        $views->srch = $srch;

        return $views;
    }

    public function trade_error(Request $request){
        $trade_errors = DB::table('btc_ads_btc')
        ->join('users','users.username','=','btc_ads_btc.userid')
        ->select('btc_ads_btc.*', 'users.fullname', 'users.email', 'users.mobile_number')
        ->where('btc_ads_btc.status','CancelRequest')->get();

        $views = view('admin.trade.trade_error');

        $views->datetime = date("H시 i분 s초");
        $views->trade_errors = $trade_errors;

        return $views;
    }

    public function trade_history_excel(Request $request){
        $from = $request->from;
        $to = $request->to;
        $srch = $request->srch;

        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
                
        return Excel::download(new TradeHistorysExport($from, $to, $srch), '거래이력리스트_' .  date("Y-m-d") . '.xlsx'); 
    }

    public function coin_out_history(Request $request, $types){
        $category = $request->category;
        $srch = $request->srch;

        if($request->srch == NULL || !isset($request->srch)){
            $srch = '';
        }

        if($request->category == NULL || !isset($request->category)){
            $category = 'all';
        }
		
		if($category != null) {
            $srch = $srch != null ? $srch : '';
            if($category == 'uid') {
                $co_historys = DB::table('btc_coin_send_request')
                ->join('users','btc_coin_send_request.sender_userid','=','users.username')
            	->select('btc_coin_send_request.*','users.id as uid','users.email' ,'users.mobile_number' ,'users.fullname' )
            	->where('users.id',$srch)
				->orderBy('btc_coin_send_request.id','desc');
            } else if($category == 'email') {
                $co_historys = DB::table('btc_coin_send_request')
                ->join('users','btc_coin_send_request.sender_userid','=','users.username')
            	->select('btc_coin_send_request.*','users.id as uid','users.email' ,'users.mobile_number' ,'users.fullname' )
            	->where('users.email','like','%'.$srch.'%')
				->orderBy('btc_coin_send_request.id','desc');
            	
			} else if($category == 'mobile_number') {
                $co_historys = DB::table('btc_coin_send_request')
                ->join('users','btc_coin_send_request.sender_userid','=','users.username')
            	->select('btc_coin_send_request.*','users.id as uid','users.email' ,'users.mobile_number' ,'users.fullname' )
            	->where('users.mobile_number','like','%'.$srch.'%')
				->orderBy('btc_coin_send_request.id','desc');
            	
			} else if($category == 'fullname') {
                $co_historys = DB::table('btc_coin_send_request')
                ->join('users','btc_coin_send_request.sender_userid','=','users.username')
            	->select('btc_coin_send_request.*','users.id as uid','users.email' ,'users.mobile_number' ,'users.fullname' )
            	->where(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$srch.'%'))
				->orderBy('btc_coin_send_request.id','desc');
            	
			} else {
            	$co_historys = DB::table('btc_coin_send_request')
            	->join('users','btc_coin_send_request.sender_userid','=','users.username')
            	->select('btc_coin_send_request.*','users.id as uid','users.email' ,'users.mobile_number' ,'users.fullname' )
            	->where(function($qry) use ($srch){
                    $qry->where('users.email','like','%'.$srch.'%')
                    ->orWhere(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$srch.'%'))
                    ->orWhere('users.mobile_number','like','%'.$srch.'%')
                    ->orWhere('users.id',$srch)
					->orwhereRaw("LOWER(cointype) like LOWER('%$srch%')");
                })
            	->orderBy('btc_coin_send_request.id','desc');
            }
        }else{
        	$co_historys = DB::table('btc_coin_send_request')
        	->join('users','btc_coin_send_request.sender_userid','=','users.username')
            ->select('btc_coin_send_request.*','users.id as uid','users.email' ,'users.mobile_number' ,'users.fullname' )
            ->orderBy('btc_coin_send_request.id','desc');
        }
		if($types != 'all'){
			$co_historys = $co_historys->where('btc_coin_send_request.status', $types)->paginate(30)->appends(request()->query());
		}else{
			$co_historys = $co_historys->paginate(30)->appends(request()->query());
		}
        /*if($category == 'all'){
            //동민 수정 -> 전체로 셀렉 시
            if($types == 'all'){
                //검색창에 내용을 입력하지 않았을 때
                if($srch == NULL || !isset($srch)){
                    $co_historys = DB::table('btc_coin_send_request')->orderBy('id','desc')->paginate(30);
                //검색창에 내용을 입력했을 때
                }else{
                    $co_historys = DB::table('btc_coin_send_request')
                    ->where(function($qry) use ($srch){
                        $qry->where('cointype','like','%'.$srch.'%')->orWhere('sender_userid','like','%'.$srch.'%')->orWhere('receiver_address','like','%'.$srch.'%');
                    })
                    ->orderBy('id','desc')->paginate(30);
                }
            }else{
                $co_historys = DB::table('btc_coin_send_request')->where('status', $types)->orderBy('id','desc')->paginate(30);
            }
        }else{
            if($types == 'all'){
                $co_historys = DB::table('btc_coin_send_request')->where($category,'like','%'.$srch.'%')->orderBy('id','desc')->paginate(30);
            }else{
                $co_historys = DB::table('btc_coin_send_request')->where('status', $types)->where($category,'like','%'.$srch.'%')->orderBy('id','desc')->paginate(30);
            }
        }*/

        $views = view('admin.coin.coin_out_history');
        $co_historys->withPath('/admin/coin/coin_out_history/'.$types);

        $datetime = date("H시 i분 s초");

        $views->datetime = $datetime;
        $views->types = $types;
        $views->co_historys = $co_historys;

        return $views;
    }
    
    public function deposite_withdraw_list(Request $request){
        $cointype = $request->cointype;
        $srch = $request->srch;

        if($request->srch == NULL || !isset($request->srch)){
            $srch = '';
        }

        if($request->cointype == NULL || !isset($request->cointype)){
            $cointype = 'all';
        }

        if($cointype == 'all'){
            
            $receive_transaction = DB::table("btc_transaction")->Join('users','btc_transaction.account','=','users.username')
            ->where(function($qry) use ($srch){
                $qry->where('btc_transaction.account','like','%'.$srch.'%')->orwhere('users.fullname','like','%'.$srch.'%');
            })
            ->where("btc_transaction.category","<>","trade")
            ->select(
            "btc_transaction.category",
            "btc_transaction.amount",
            "btc_transaction.confirmations",
            "btc_transaction.txid",
            DB::raw("FROM_UNIXTIME(btc_transaction.tr_time) as updated"),
            "btc_transaction.cointype",
            "btc_transaction.address",
            "btc_transaction.account",
            "users.fullname"
            );
            
            $coin_io_transactions = DB::table("btc_coin_io")->Join('users','btc_coin_io.username','=','users.username')
            ->where(function($qry) use ($srch){
                $qry->where('btc_coin_io.username','like','%'.$srch.'%')->orwhere('users.fullname','like','%'.$srch.'%');
            })
            ->select(
            "btc_coin_io.type",
            "btc_coin_io.amount",
            "btc_coin_io.tx_id",
            "btc_coin_io.memo",
            DB::raw("FROM_UNIXTIME(btc_coin_io.created) as updated"),
            "btc_coin_io.cointype",
            "btc_coin_io.memo",
            "btc_coin_io.username",
            "users.fullname"
            );
            $receive_transaction = $receive_transaction->unionAll($coin_io_transactions)
            ->orderBy("updated","desc")->paginate(30)->appends(request()->query());	
            
        }else{
            
            $receive_transaction = DB::table("btc_transaction")->Join('users','btc_transaction.account','=','users.username')
            ->where(function($qry) use ($srch){
                $qry->where('btc_transaction.account','like','%'.$srch.'%')->orwhere('users.fullname','like','%'.$srch.'%');
            })
            ->where("btc_transaction.category","<>","trade")->where('btc_transaction.cointype',$cointype)
            ->select(
            "btc_transaction.category",
            "btc_transaction.amount",
            "btc_transaction.confirmations",
            "btc_transaction.txid",
            DB::raw("FROM_UNIXTIME(btc_transaction.tr_time) as updated"),
            "btc_transaction.cointype",
            "btc_transaction.address",
            "btc_transaction.account",
            "users.fullname"
            );
            
            $coin_io_transactions = DB::table("btc_coin_io")->Join('users','btc_coin_io.username','=','users.username')
            ->where(function($qry) use ($srch){
                $qry->where('btc_coin_io.username','like','%'.$srch.'%')->orwhere('users.fullname','like','%'.$srch.'%');
            })
            ->where('btc_coin_io.cointype',$cointype)
            ->select(
            "btc_coin_io.type",
            "btc_coin_io.amount",
            "btc_coin_io.tx_id",
            "btc_coin_io.memo",
            DB::raw("FROM_UNIXTIME(btc_coin_io.created) as updated"),
            "btc_coin_io.cointype",
            "btc_coin_io.memo",
            "btc_coin_io.username",
            "users.fullname"
            );
            $receive_transaction = $receive_transaction->unionAll($coin_io_transactions)->orderBy("updated","desc")->paginate(30)->appends(request()->query());	
        }

        $coins = DB::table('btc_coins')->where('cointype','<>','cash')->where('active',1)->get();
        
        $receive_transaction->withPath('/admin/deposite_withdraw_list');
        
        $views = view('admin.coin.deposite_withdraw_list');
        
        $views->coins = $coins;	
        $views->lists = $receive_transaction;
        
        return $views;
    }

    public function term_service($country){
        $setting = Settings::Settings();
        $term = DB::connection('mysql_sub')->table('btc_term_service')->where('market_type',$setting->id)->first();

        $views = view('admin.term.term_edit');

        $views->term = $term;	
        $views->country = $country;

        return $views;
    }

    public function term_service_update(Request $request, $id){
        $country = $request->country;
        $private_infor_term = $request->{'private_infor_term_'.$country};
        $use_term = $request->{'use_term_'.$country};

        DB::connection('mysql_sub')->table('btc_term_service')->where('id',$id)->update([
            "private_infor_term_".$country => $private_infor_term,
            "use_term_".$country => $use_term,
            "updated_at" => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back();
    }
    
    //관리자 ICO 페이지
    public function ico_list(Request $request){
        $keyword_srch = $request->keyword_srch;
        
        $keyword = '';
        
        if($request->keyword != null){
            $keyword = $request->keyword;
        }
        
        if($keyword_srch != null){
            if($keyword_srch == 'id'){
                $icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('id','like','%'.$keyword.'%')->orderBy('id','asc')->paginate(20);
            }else if($keyword_srch == 'name'){
                $icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('ico_title','like','%'.$keyword.'%')->orderBy('id','asc')->paginate(20);
            }else if($keyword_srch == 'symbol'){
                $icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('ico_symbol','like','%'.$keyword.'%')->orderBy('id','asc')->paginate(20);
            }else{
                $icos = DB::connection('mysql_sub')->table('btc_ico_new')->orderBy('id','asc')->paginate(20);
            }
        }else{
            $icos = DB::connection('mysql_sub')->table('btc_ico_new')->orderBy('id','asc')->paginate(20);
        }
        $icos->withPath('/admin/ico_list');
        
        $views = view('admin.ico.ico_list');
        
        $views->icos = $icos; 
        
        return $views;
    }
    
    public function ico_people_list(Request $request, $id){
        $keyword = $request->keyword;
        
        $ico = DB::connection('mysql_sub')->table('btc_ico_new')->where('id', $id)->first();
        
        if($keyword != null){
            $ico_peoples = DB::connection('mysql_sub')->table('btc_ico_people')->where('pr_id',$id)->where('name','like','%'.$keyword.'%')->orderBy('id','asc')->paginate(20);
        }else{
            $ico_peoples = DB::connection('mysql_sub')->table('btc_ico_people')->where('pr_id',$id)->orderBy('id','asc')->paginate(20);
        }
        $ico_peoples->withPath('/admin/ico_people_list');
        
        $views = view('admin.ico.ico_people_list');
        
        $views->ico = $ico;	
        $views->ico_peoples = $ico_peoples; 
    
        
        return $views;
    }
    
    public function ico_confirm(Request $request, $id){
        $ico = DB::connection('mysql_sub')->table('btc_ico_new')->where('id',$id)->first();
        if(strtotime($ico->ico_from) <= time() && strtotime($ico->ico_to) > time()){
            $ico_category = 1; //진행중
        }else if(strtotime($ico->ico_from) > time() ){
            $ico_category = 2; //진행예정
        }else if(strtotime($ico->ico_to) <= time() ){
            $ico_category = 3; //종료
        } 
        
        DB::connection('mysql_sub')->table('btc_ico_new')->where('id', $id)->update([
            'active' => 1,
            'ico_category' => $ico_category,
        ]);
		if($ico_category == 1){
        	LoginTrace::Activity($id.'번 ICO/IEO 진행중으로 상태변경');
		}else if($ico_category == 1){
        	LoginTrace::Activity($id.'번 ICO/IEO 진행예정으로 상태변경');
		}else{
			LoginTrace::Activity($id.'번 ICO/IEO 종료로 상태변경');
		}
		
        return Redirect::route('admin.ico_list');
    }

    public function ico_ban(Request $request, $id){
        $reject = $request->reject;
        DB::connection('mysql_sub')->table('btc_ico_new')->where('id', $id)->update([
            'active' => 0,
            'ico_category' => 5,
            'reject' => $reject,
        ]);
    	
		LoginTrace::Activity($id.'번 ICO/IEO 거부');
		
        return Redirect::route('admin.ico_list');
    }
    public function popup_list($country){
		$views = view('admin.popup.list');
		
		$popups = DB::connection('mysql_sub')->table('btc_popup_'.$country)->paginate(15);
		
		$popups->withPath('/admin/popup/list/'.$country);
		
		$datetime = date("H시 i분 s초");
		$views->country = $country;
		$views->popups = $popups;
		$views->datetime = $datetime;
		
		return $views;
	}
	
	public function popup_create($country){
		$views = view('admin.popup.create');
		$views->country = $country;
		return $views;
	}
	
	public function popup_insert(Request $request){
		$country = $request->country;
		
		$store_img_path = 'public/image/popup';
		$pc_path = NULL;
		$mb_path = NULL;
		

		if($file = $request->file('pc_img')){    	
			if ($file->isValid()) {            		
				$pc_path = $file->store($store_img_path.'/');                  
				$pc_path = str_replace($store_img_path.'/',"",$pc_path);		
			}
		}
		
		if($file = $request->file('mb_img')){    	
			if ($file->isValid()) {            		
				$mb_path = $file->store($store_img_path.'/');                  
				$mb_path = str_replace($store_img_path.'/',"",$mb_path);		
			}
		}
		
		
		DB::connection('mysql_sub')->table('btc_popup_'.$country)->insert([
			"writer_id" => Auth::guard('admin')->user()->id,
			"writer_name" => Auth::guard('admin')->user()->fullname,
			"title" => $request->title,
			"body" => $request->body,
			"pc_img" => $pc_path,
            "mb_img" => $mb_path,
            "link" => $request->link,
			"start_time" => $request->start_time,
			"end_time" => $request->end_time,
			"created_at" => now(),
			"updated_at" => now(),
		]);
		LoginTrace::Activity($country.'나라의 팝업 생성 ('.$request->title.')');
		return redirect()->route('admin.popup_list',$country);
	}
	
	public function popup_edit($id, $country){
        $views = view('admin.popup.edit');
        
        $popup = DB::connection('mysql_sub')->table('btc_popup_'.$country)->where('id',$id)->first();

        $views->popup = $popup;
		$views->country = $country;
		return $views;
	}

	public function popup_update(Request $request, $id){
		$country = $request->country;
		
		$store_img_path = 'public/image/popup';
        
        $popup = DB::connection('mysql_sub')->table('btc_popup_'.$country)->where('id',$id)->first();

        $pc_path = $popup->pc_img;
        $mb_path = $popup->mb_img;

		
		if($file = $request->file('pc_img')){
            if(isset($file)){
                if ($file->isValid()) {            		
                    $pc_path = $file->store($store_img_path.'/');                  
                    $pc_path = str_replace($store_img_path.'/',"",$pc_path);	
                    
                    $img_path = '../storage/app/public/image/popup/'.$popup->pc_img;
                    
                    if(File::exists($img_path)) {
                        File::delete($img_path);
                    }  
                }
            }   	
		}
		
		if($file = $request->file('mb_img')){
            if(isset($file)){
                if ($file->isValid()) {            		
                    $mb_path = $file->store($store_img_path.'/');                  
                    $mb_path = str_replace($store_img_path.'/',"",$mb_path);	
                    
                    $img_path = '../storage/app/public/image/popup/'.$popup->mb_img;
                    
                    if(File::exists($img_path)) {
                        File::delete($img_path);
                    }  
                }
            }   	
		}
		
		
		DB::connection('mysql_sub')->table('btc_popup_'.$country)->where('id',$id)->update([
			"writer_id" => Auth::guard('admin')->user()->id,
			"writer_name" => Auth::guard('admin')->user()->fullname,
			"title" => $request->title,
			"body" => $request->body,
			"pc_img" => $pc_path,
            "mb_img" => $mb_path,
            "link" => $request->link,
			"start_time" => $request->start_time,
			"end_time" => $request->end_time,
			"updated_at" => now(),
		]);
		LoginTrace::Activity($country.'나라의 '.$id.'번 팝업 수정 ('.$request->title.')');
		return redirect()->route('admin.popup_list',$country);
	}
	
	public function popup_delete($id, $country){
        $popup = DB::connection('mysql_sub')->table('btc_popup_'.$country)->where('id',$id)->first();

        $img_path = '../storage/app/public/image/popup/'.$popup->pc_img;

        if(File::exists($img_path)) {
            File::delete($img_path);
        }  

        $img_path = '../storage/app/public/image/popup/'.$popup->mb_img;

        if(File::exists($img_path)) {
            File::delete($img_path);
        }

        DB::connection('mysql_sub')->table('btc_popup_'.$country)->where('id',$id)->delete();
        
		LoginTrace::Activity($country.'나라의 '.$id.'번 팝업 삭제');

		return redirect()->route('admin.popup_list',$country);
	}
	
	public function auto_setting(Request $request){
		$views = view('admin.trade.auto_setting');
		
		$auto_settings = DB::table('btc_auto_setting')->get();
		
		$views->auto_settings = $auto_settings;
		
		return $views;
		
	}
	
	public function auto_setting_update(Request $request, $id, $switch){
		DB::table('btc_auto_setting')->where('id', $id)->update([
            'switch' => $switch
        ]);
		if($switch == 0){
			LoginTrace::Activity($id.'번 자동거래 활성화');
		}else{
			LoginTrace::Activity($id.'번 자동거래 비활성화');
		}
		return redirect()->route('admin.auto_setting');
    }
    
    public function auto_bot_edit($id){
        $views = view('admin.trade.auto_bot_edit');
        
        $auto = DB::table('btc_auto_setting')->where('id',$id)->first();
		
        $views->auto = $auto;

        return $views;

    }

    public function auto_bot_update(Request $request, $id){
        $time_min = $request->time_min;
        $time_max = $request->time_max;
        $amt_min = $request->amt_min;
        $amt_max = $request->amt_max;
        $amt_decimal = $request->amt_decimal;
        $range_min = $request->range_min;
        $range_max = $request->range_max;

        
        DB::table('btc_auto_setting')->where('id',$id)->update([
            "time_min" => $time_min,
            "time_max" => $time_max,
            "amt_min" => $amt_min,
            "amt_max" => $amt_max,
            "amt_decimal" => $amt_decimal,
            "range_min" => $range_min,
            "range_max" => $range_max,
        ]);
		
		LoginTrace::Activity($id.'번 자동거래 상태변경');

        return redirect()->route('admin.auto_bot_edit',$id);

    }

    //동민추가
    public function new_trade_history(Request $request){
        $cointype = $request->cointype;
        $markettype = $request->markettype;
        $srch = $request->srch;
        
        if($request->srch == NULL || !isset($request->srch)){
            $srch = '';
        }

        if($request->cointype == NULL || !isset($request->cointype)){
            $cointype = 'all';
        }

        if($request->markettype == NULL || !isset($request->markettype)){
            $markettype = 'all';
        }

        if($markettype == 'all'){
            if($cointype == 'all'){
                $trade_historys = DB::table('btc_ads_btc')
                                    ->where(function($qry) use ($srch){
                                        $qry->where('userid','like','%'.$srch.'%')->orWhere('status','like','%'.$srch.'%');
                                    })
                                    ->orderBy('id','desc')->paginate(30);
            }else{
                $trade_historys = DB::table('btc_ads_btc')
                                    ->where(function($qry) use ($srch){
                                        $qry->where('userid','like','%'.$srch.'%')->orWhere('status','like','%'.$srch.'%');
                                    })
                                    ->where('cointype',$cointype)
                                    ->orderBy('id','desc')->paginate(30);
            }
        }else{
            if($cointype == 'all'){
                $trade_historys = DB::table('btc_ads_btc')
                                    ->where(function($qry) use ($srch){
                                        $qry->where('userid','like','%'.$srch.'%')->orWhere('status','like','%'.$srch.'%');
                                    })
                                    ->where('currency',$markettype)
                                    ->orderBy('id','desc')->paginate(30);
            }else{
                $trade_historys = DB::table('btc_ads_btc')
                                    ->where(function($qry) use ($srch){
                                        $qry->where('userid','like','%'.$srch.'%')->orWhere('status','like','%'.$srch.'%');
                                    })
                                    ->where('currency',$markettype)
                                    ->where('cointype',$cointype)
                                    ->orderBy('id','desc')->paginate(30);
            }
        }
        
        $coins = DB::table('btc_coins')->where('cointype','<>','cash')->where('active',1)->get();
        
        $trade_historys->withPath('/admin/trade/new_trade_history');

        $views = view('admin.trade.new_trade_history');

        $datetime = date("H시 i분 s초");

        $views->datetime = $datetime;
        $views->trade_historys = $trade_historys;
        $views->coins = $coins;

        return $views;
    }

    public function coin_has_list(){
    	$market_type = Session::get('market_type');
    	$setting = DB::table('btc_settings')->where('id', $market_type)->first();
		$coins = DB::table('btc_coins')->where('active','1')
					->where(function($qry){
			        	$qry->where('cointype','coin')->orWhere('cointype','token');
					})->orderByRaw('cointype asc, id asc')->get();
			
		$bot_balances = DB::table('btc_users_addresses')->where('label','sbtr01')->first();
		$results = array();
		foreach($coins as $coin){
            $send_balance = DB::table('btc_coin_send_request')->whereIn('status',['withdraw_request','withdraw_request_confirm'])->where('cointype',$coin->symbol)->sum('req_amount');
            $bot_balance = $bot_balances->{'available_balance_'.$coin->api};
			if($coin->cointype == 'coin' && $coin->symbol != 'ETH'){
                $address = DB::table('admin_address')->where('cointype',$coin->api)->first();
                $admin_address = $address->address;
				$ch = curl_init();                    // Initiate cURL
			    $url = "http://cocop365.com/bds/bds_balances.php"; // Where you want to post data
			    
			    curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			    
                $response = curl_exec ($ch); // Execute
                
                $data1 = explode("\n",$response);
                if(isset($data1[1])){
                    $data2 = explode("<br />",$data1[1]);
					
                    foreach($data2 as $data_final){
                    	$check_flag = false;
                        $data_explode = explode(": ",$data_final);
                        if(isset($data_explode[1])){
                        	
                            $symbol = $data_explode[0];
							
                            if($symbol == $coin->symbol){
                            	$check_flag = true;
                                $balance = (float)$data_explode[1];
								break;
                            }
                        }
                    }
                }else{
                    $balance = 0;
                }
				if(!$check_flag){
					$balance = 0;	
				}
			    curl_close ($ch); // Close cURL handle
			}else{
                $admin_address = env('ETHERSCAN_ADMIN_ADDRESS');
                $admin_key = env('ETHERSCAN_ADMIN_KEY');

				if($coin->symbol == 'ETH'){
					$ch = curl_init();                    // Initiate cURL
				    $api_url = 'https://api.etherscan.io/api?module=account&action=balance&address=' . $admin_address . '&tag=latest&apikey=' . $admin_key;
				    curl_setopt($ch, CURLOPT_URL,$api_url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				    $result = curl_exec ($ch); // Execute
				    curl_close ($ch); // Close cURL handle
				    
				    $json_balance = json_decode($result);
				    
					$balance = bcdiv($json_balance->result,pow(10,$coin->decimal_place),8);
				}else{
					$ch = curl_init();                    // Initiate cURL
				    $api_url = 'https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress='. $coin->token_contract_addr . '&address=' . $admin_address . '&tag=latest&apikey=' . $admin_key;
				    curl_setopt($ch, CURLOPT_URL,$api_url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				    $result = curl_exec ($ch); // Execute
				    curl_close ($ch); // Close cURL handle
				    
				    $json_balance = json_decode($result);
				    
					$balance = bcdiv($json_balance->result,pow(10,$coin->decimal_place),8);
				}
				
			}
			$results["bot_balance_".$coin->api] = $bot_balance;
			$results["send_balance_".$coin->api] = $send_balance;
            $results["market_balance_".$coin->api] = $balance;
            $results["admin_address_".$coin->api] = $admin_address;
		}
        $views = view('admin.coin.coin_has_list');
        $datetime = date("H시 i분 s초");
		
		$views->coins = $coins;
		$views->results = $results;
        $views->datetime = $datetime;

        return $views;

    }

    public function user_trace_list(Request $request){
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        if($request->srch == NULL || !isset($request->srch)){
            $srch = '';
        }
		
		$keyword_srch = $request->input('keyword_srch');
        $keyword = $request->input('keyword');

        if($keyword_srch != null) {
            $keyword = $keyword != null ? $keyword : '';
            if($keyword_srch == 'uid') {
                $traces = DB::connection('mysql_sub')->table('btc_login_trace')->where('uid','like','%'.$keyword.'%')->orderBy('id','desc');
            } else if($keyword_srch == 'email') {
                $traces = DB::connection('mysql_sub')->table('btc_login_trace')->where('email','like','%'.$keyword.'%')->orderBy('id','desc');
            } else if($keyword_srch == 'mobile_number') {
                $traces = DB::connection('mysql_sub')->table('btc_login_trace')->where('mobile_number','like','%'.$keyword.'%')->orderBy('id','desc');
            } else if($keyword_srch == 'fullname') {
                $traces = DB::connection('mysql_sub')
                    ->table('btc_login_trace')
                    ->where(DB::raw("REPLACE(fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
                    ->orderBy('id','desc');                
            } else {
                $traces = DB::connection('mysql_sub')
                    ->table('btc_login_trace')
                    ->where(function($qry) use ($keyword){
                        $qry->where('uid','like','%'.$keyword.'%')
                        ->orWhere('email','like','%'.$keyword.'%')
                        ->orWhere('mobile_number','like','%'.$keyword.'%')
                        ->orWhere(DB::raw("REPLACE(fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'));
                    })
                    ->orderBy('id','desc');
            }
        }else{
            $traces = DB::connection('mysql_sub')->table('btc_login_trace')->orderBy('id','desc');
        }

        $traces = $traces->paginate(20)->appends(request()->query())->withPath('user_trace_list');
        
        $views = view('admin.user_trace.user_trace_list');
        $views->datetime = $datetime;
        $views->traces = $traces;
        $views->keyword_srch = $keyword_srch;
        
        return $views;
    }


	public function cash_list(Request $request){
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
		
		$keyword_srch = $request->input('keyword_srch');
        
        $keyword = '';
		
		if($request->input('keyword') != null){
            $keyword = $request->input('keyword');
        }
        
        if($request->input('type') != null){
            $type = $request->input('type');
        }else{
            $type = 'all';
        }
        info($type);
        if($type == 'all') {
            if ($keyword_srch != null) {
                if ($keyword_srch == 'uid') {
                    $krw_ios = DB::table('btc_krw_io')
                ->join('users', 'users.id', '=', 'btc_krw_io.uid')
                ->select('btc_krw_io.*', 'users.fullname')
                ->where('users.id', $keyword);
                } elseif ($keyword_srch == 'name') {
                    $krw_ios = DB::table('btc_krw_io')
                ->join('users', 'users.id', '=', 'btc_krw_io.uid')
                ->select('btc_krw_io.*', 'users.fullname')
                ->where('users.fullname', 'like', '%'.$keyword.'%');
                } elseif ($keyword_srch == 'email') {
                    $krw_ios = DB::table('btc_krw_io')
                ->join('users', 'users.id', '=', 'btc_krw_io.uid')
                ->select('btc_krw_io.*', 'users.fullname')
                ->where('users.email', 'like', '%'.$keyword.'%');
                } elseif ($keyword_srch == 'mobile') {
                    $krw_ios = DB::table('btc_krw_io')
                ->join('users', 'users.id', '=', 'btc_krw_io.uid')
                ->select('btc_krw_io.*', 'users.fullname')
                ->where('users.mobile_number', 'like', '%'.$keyword);
                }
            } else {
                $krw_ios = DB::table('btc_krw_io')
            ->join('users', 'users.id', '=', 'btc_krw_io.uid')
            ->select('btc_krw_io.*', 'users.fullname', 'users.email', 'users.mobile_number')
            ->orwhere('users.id', $keyword)
            ->orwhere('users.fullname', 'like', '%'.$keyword.'%')
            ->orwhere('users.email', 'like', '%'.$keyword.'%')
            ->orwhere('users.mobile_number', 'like', '%'.$keyword.'%');
            }
        }else{
            if ($keyword_srch != null) {
                if ($keyword_srch == 'uid') {
                    $krw_ios = DB::table('btc_krw_io')
                ->join('users', 'users.id', '=', 'btc_krw_io.uid')
                ->select('btc_krw_io.*', 'users.fullname')
                ->where('users.id', $keyword)->where('type',$type);
                } elseif ($keyword_srch == 'name') {
                    $krw_ios = DB::table('btc_krw_io')
                ->join('users', 'users.id', '=', 'btc_krw_io.uid')
                ->select('btc_krw_io.*', 'users.fullname')
                ->where('users.fullname', 'like', '%'.$keyword.'%')->where('type',$type);
                } elseif ($keyword_srch == 'email') {
                    $krw_ios = DB::table('btc_krw_io')
                ->join('users', 'users.id', '=', 'btc_krw_io.uid')
                ->select('btc_krw_io.*', 'users.fullname')
                ->where('users.email', 'like', '%'.$keyword.'%')->where('type',$type);
                } elseif ($keyword_srch == 'mobile') {
                    $krw_ios = DB::table('btc_krw_io')
                ->join('users', 'users.id', '=', 'btc_krw_io.uid')
                ->select('btc_krw_io.*', 'users.fullname')
                ->where('users.mobile_number', 'like', '%'.$keyword)->where('type',$type);
                }
            } else {
                $krw_ios = DB::table('btc_krw_io')
                ->join('users', 'users.id', '=', 'btc_krw_io.uid')
                ->select('btc_krw_io.*', 'users.fullname', 'users.email', 'users.mobile_number')->where('type',$type)
                ->where(function($qry) use ($keyword){
                    $qry->where('users.id', $keyword)
                    ->orwhere('users.fullname', 'like', '%'.$keyword.'%')
                    ->orwhere('users.email', 'like', '%'.$keyword.'%')
                    ->orwhere('users.mobile_number', 'like', '%'.$keyword.'%');
                });
            
            }
        }


        $views = view('admin.cash.cash_list');
        
        $krw_ios = $krw_ios->orderBy('btc_krw_io.id','desc')->paginate(30)->appends(request()->query())->withPath('cash_list');
		
        $views->krw_ios = $krw_ios;
        $views->type = $type;
        $views->keyword = $keyword;
        $views->datetime = $datetime;
        
        return $views;
    }

    public function cash_withdraw_excel(Request $request){


        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        return Excel::download(new CashWithdrawExport(), '원화출금리스트_'.date("Y-m-d").'.xlsx'); 
    }

    public function cash_withdraw_excel_history(Request $request){
        $srch = $request->srch;

        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        return Excel::download(new CashWithdrawHistoryExport($srch), '원화입출금내역_'.date("Y-m-d").'.xlsx'); 
    }
	
	public function company_list(Request $request){
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
		
		$keyword_srch = $request->input('keyword_srch');
        
        $keyword = '';
		
		if($request->input('keyword') != null){
            $keyword = $request->input('keyword');
        }
		
		
		if($keyword_srch != null){
	        if($keyword_srch == 'uid'){
	            $company_lists = DB::table('btc_payment_company')
	            ->join('users','users.id','=','btc_payment_company.uid')
				->select('btc_payment_company.*')
	            ->where('users.id',$keyword);
	        }else if($keyword_srch == 'name'){
	            $company_lists = DB::table('btc_payment_company')
	            ->join('users','users.id','=','btc_payment_company.uid')
				->select('btc_payment_company.*')
	            ->where('users.fullname','like','%'.$keyword.'%');
	        }else if($keyword_srch == 'email'){
	            $company_lists = DB::table('btc_payment_company')
	            ->join('users','users.id','=','btc_payment_company.uid')
				->select('btc_payment_company.*')
	            ->where('users.email','like','%'.$keyword.'%');
	        }else if($keyword_srch == 'mobile'){
	            $company_lists = DB::table('btc_payment_company')
	            ->join('users','users.id','=','btc_payment_company.uid')
				->select('btc_payment_company.*')
	            ->where('users.mobile_number','like','%'.$keyword);
	        }
		}else{
			$company_lists = DB::table('btc_payment_company')
			->join('users','users.id','=','btc_payment_company.uid')
			->select('btc_payment_company.*')
			->orwhere('users.id',$keyword)
			->orwhere('users.fullname','like','%'.$keyword.'%')
			->orwhere('users.email','like','%'.$keyword.'%')
			->orwhere('users.mobile_number','like','%'.$keyword.'%');
		}

        $views = view('admin.payment.company_list');
        
        $company_lists = $company_lists->orderBy('btc_payment_company.company_confirm','desc')->orderBy('btc_payment_company.id','desc')->paginate(30)->appends(request()->query())->withPath('company_list');
		
        $views->company_lists = $company_lists;
        $views->datetime = $datetime;
        
        return $views;
    }
    
    public function payment_calculate(Request $request){
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
		
		$keyword_srch = $request->input('keyword_srch');
        
        $keyword = '';
		
		if($request->input('keyword') != null){
            $keyword = $request->input('keyword');
        }
		
		
		if($keyword_srch != null){
	        if($keyword_srch == 'uid'){
                $payment_lists = DB::table('btc_payment')
                ->join('users','users.id','=','btc_payment.uid')
                ->join('btc_security_lv','btc_security_lv.uid','=','btc_payment.uid')
                ->select(DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt)) AS calcul_month"),DB::raw('SUM(btc_payment.cash_price) AS calcul_cash'), 'btc_payment.status','btc_payment.seller_fullname','btc_payment.company_name','btc_payment.username','btc_security_lv.account_num','btc_security_lv.account_bank')
                ->where('users.id',$keyword)
                ->where(function ($query2) {
                    $query2->where('btc_payment.status','complete')->orwhere('btc_payment.status','calculate');
                })->groupBy('btc_payment.username','btc_payment.status',DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt))"),'btc_payment.seller_fullname','btc_payment.company_name','btc_security_lv.account_num','btc_security_lv.account_bank');
	        }else if($keyword_srch == 'name'){
                $payment_lists = DB::table('btc_payment')
                ->join('users','users.id','=','btc_payment.uid')
                ->join('btc_security_lv','btc_security_lv.uid','=','btc_payment.uid')
                ->select(DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt)) AS calcul_month"),DB::raw('SUM(btc_payment.cash_price) AS calcul_cash'), 'btc_payment.status','btc_payment.seller_fullname','btc_payment.company_name','btc_payment.username','btc_security_lv.account_num','btc_security_lv.account_bank')
                ->where('users.fullname','like','%'.$keyword.'%')
                ->where(function ($query2) {
                    $query2->where('btc_payment.status','complete')->orwhere('btc_payment.status','calculate');
                })->groupBy('btc_payment.username','btc_payment.status',DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt))"),'btc_payment.seller_fullname','btc_payment.company_name','btc_security_lv.account_num','btc_security_lv.account_bank');
	        }else if($keyword_srch == 'email'){
                $payment_lists = DB::table('btc_payment')
                ->join('users','users.id','=','btc_payment.uid')
                ->join('btc_security_lv','btc_security_lv.uid','=','btc_payment.uid')
                ->select(DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt)) AS calcul_month"),DB::raw('SUM(btc_payment.cash_price) AS calcul_cash'), 'btc_payment.status','btc_payment.seller_fullname','btc_payment.company_name','btc_payment.username','btc_security_lv.account_num','btc_security_lv.account_bank')
                ->where('users.email','like','%'.$keyword.'%')
                ->where(function ($query2) {
                    $query2->where('btc_payment.status','complete')->orwhere('btc_payment.status','calculate');
                })->groupBy('btc_payment.username','btc_payment.status',DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt))"),'btc_payment.seller_fullname','btc_payment.company_name','btc_security_lv.account_num','btc_security_lv.account_bank');
	        }else if($keyword_srch == 'mobile'){
                $payment_lists = DB::table('btc_payment')
                ->join('users','users.id','=','btc_payment.uid')
                ->join('btc_security_lv','btc_security_lv.uid','=','btc_payment.uid')
                ->select(DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt)) AS calcul_month"),DB::raw('SUM(btc_payment.cash_price) AS calcul_cash'), 'btc_payment.status','btc_payment.seller_fullname','btc_payment.company_name','btc_payment.username','btc_security_lv.account_num','btc_security_lv.account_bank')
                ->where('users.mobile_number','like','%'.$keyword)
                ->where(function ($query2) {
                    $query2->where('btc_payment.status','complete')->orwhere('btc_payment.status','calculate');
                })->groupBy('btc_payment.username','btc_payment.status',DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt))"),'btc_payment.seller_fullname','btc_payment.company_name','btc_security_lv.account_num','btc_security_lv.account_bank');
	        }
		}else{
			$payment_lists = DB::table('btc_payment')
            ->join('users','users.id','=','btc_payment.uid')
            ->join('btc_security_lv','btc_security_lv.uid','=','btc_payment.uid')
            ->select(DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt)) AS calcul_month"),DB::raw('SUM(btc_payment.cash_price) AS calcul_cash'), 'btc_payment.status','btc_payment.seller_fullname','btc_payment.company_name','btc_payment.username','btc_security_lv.account_num','btc_security_lv.account_bank')
            ->where(function ($query) use ($keyword) {
                $query->orwhere('users.fullname','like','%'.$keyword.'%')->orwhere('users.email','like','%'.$keyword.'%')->orwhere('users.mobile_number','like','%'.$keyword.'%');
            })->where(function ($query2) {
                $query2->where('btc_payment.status','complete')->orwhere('btc_payment.status','calculate');
            })->groupBy('btc_payment.username','btc_payment.status',DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt))"),'btc_payment.seller_fullname','btc_payment.company_name','btc_security_lv.account_num','btc_security_lv.account_bank');
		}

        $views = view('admin.payment.payment_calculate');
        
        $payment_lists = $payment_lists->orderBy('calcul_month','desc')->paginate(30)->appends(request()->query())->withPath('payment_calculate');
		
        $views->payment_lists = $payment_lists;
        $views->datetime = $datetime;
        
        return $views;
    }

    public function comunity_manage_list(){
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        $comunity_boards = DB::connection('mysql_sub')->table('comunity_board')->paginate(20);

        $views = view('admin.comunity_board.list');

        $comunity_boards->withPath('comunity');

        $views->comunity_boards = $comunity_boards;
        $views->datetime = $datetime;

        return $views;
    }

    public function comunity_manage_create(){
        $views = view('admin.comunity_board.write');

        return $views;
    }

    public function comunity_manage_store(Request $request){
        $bo_name = $request->bo_name;
        $bo_table = $request->bo_table;
        $coin_type = $request->coin_type;
        $status = $request->status;
        $table_name = $bo_table.'_board_';

        $countrys = array(
            "kr",
            "jp",
            "ch",
            "en"
        );

        foreach($countrys as $country){
            $createTableSqlString = "CREATE TABLE ".$table_name.$country." (
                `id` INT NOT NULL AUTO_INCREMENT,
                `re_id` INT NULL DEFAULT NULL,
                `writer_id` INT NOT NULL,
                `writer_nickname` VARCHAR(255) NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `content` LONGTEXT NULL DEFAULT NULL,
                `images` JSON NULL DEFAULT NULL,
                `files` JSON NULL DEFAULT NULL,
                `hit` INT NOT NULL DEFAULT 0,
                `comment_cnt` INT NOT NULL DEFAULT 0,
                `recomend` INT NOT NULL DEFAULT 0,
                `recomend_uid` JSON NULL DEFAULT NULL,
                `secret_key` VARCHAR(100) NULL DEFAULT NULL,
                `search_permit` TINYINT NULL DEFAULT NULL,
                `created_at` DATETIME NOT NULL DEFAULT now(),
                `updated_at` DATETIME NOT NULL DEFAULT now(),
                PRIMARY KEY (`id`))
              ENGINE = InnoDB
              DEFAULT CHARACTER SET = utf8";
    
              DB::connection('mysql_sub')->statement($createTableSqlString);
        }

        DB::connection('mysql_sub')->table('comunity_board')->insert([
            "bo_table" => $bo_table,
            "bo_name" => $bo_name,
            "coin_type" => $coin_type != ''?$coin_type:NULL,
            "status" => $status,
        ]);

        return redirect()->route('admin.comunity_manage_list');
    }

    public function comunity_manage_edit($bo_table){
        $comunity = DB::connection('mysql_sub')->table('comunity_board')
                    ->where('bo_table', $bo_table)
                    ->first();
        
        $views = view('admin.comunity_board.edit');

        $views->comunity = $comunity;

        return $views;
    }

    public function comunity_manage_update(Request $request, $bo_table){
        $bo_name = $request->bo_name;
        $coin_symbol = $request->coin_symbol;
        $coin_type = $request->coin_type;
        $status = $request->status;

        DB::connection('mysql_sub')->table('comunity_board')->where('bo_table', $bo_table)->update([
            "bo_name" => $bo_name,
            "coin_symbol" => $coin_symbol != ''?$coin_symbol:NULL,
            "coin_type" => $coin_type,
            "status" => $status,
        ]);

        return redirect()->route('admin.comunity_manage_list');
    }

    public function comunity_manage_destroy(){

    }

    public function comunity_admin_list(){
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $comunity_admins = DB::connection('mysql_sub')
            ->table('comunity_admin')
            ->join('html.users','comunity_admin.uid','=','users.id')
            ->leftJoin('comunity_board','comunity_admin.bo_table','=','comunity_board.bo_table')
            ->select(
                'comunity_admin.id',
                DB::raw('users.id as uid'),
                'users.nickname',
                'users.fullname',
                'users.email',
                'users.mobile_number',
                'comunity_admin.bo_table',
                'comunity_board.bo_name',
                'comunity_admin.assign_admin',
                'comunity_admin.assign_admin_name',
                'comunity_admin.active',
                'comunity_admin.created_at',
                'comunity_admin.updated_at'
            )
            ->orderBy('comunity_board.bo_table', 'ASC')
            ->paginate(20);

        $views = view('admin.comunity_admin.list');

        $comunity_admins->withPath('/admin/comunity_list');

        $views->admin = Auth::guard('admin')->user();
        $views->comunity_admins = $comunity_admins;
        $views->datetime = $datetime;
        return $views;
    }

    public function comunity_admin_create() {
        $views = view('admin.comunity_admin.write');
        return $views;
    }

    public function comunity_admin_store(Request $request) {

        if(!empty($request->input('bo_table', null))) {
            $comunity = DB::connection('mysql_sub')
                ->table('comunity_board')
                ->where('bo_table', $request->input('bo_table'))
                ->first();
            if($comunity === null) {
                abort(403, '해당 게시판 테이블 명이 존재하지 않습니다');
            }
        }

        $user = DB::table('users')
            ->where('id', $request->input('uid'))
            ->first();
        if($user === null) {
            abort(403, '해당 유저 UID가 존재하지 않습니다');
        }
        
        DB::connection('mysql_sub')->table('comunity_admin')->insert([
            'uid' => $request->input('uid'),
            'bo_table' => $request->input('bo_table', null),
            'assign_admin' => Auth::guard('admin')->user()->id,
            'assign_admin_name' => Auth::guard('admin')->user()->fullname,
            'active' => $request->input('active'),
            "created_at" => DB::raw('now()'),
			"updated_at" => DB::raw('now()')
        ]);
        
        return Redirect::route('admin.comunity_admin_list');
    }

    public function comunity_admin_edit(Request $request, $id) {
        $comunity_admin = DB::connection('mysql_sub')->table('comunity_admin')->where('id', $id)->first();
        
        $views = view('admin.comunity_admin.edit');
        $views->comunity_admin = $comunity_admin;
		return $views;
    }

    public function comunity_admin_update(Request $request, $id){
        $comunity_admin = DB::connection('mysql_sub')->table('comunity_admin')->where('id', $id)->first();

        DB::connection('mysql_sub')
            ->table('comunity_admin')
            ->where('id', $id)
            ->update([
                'active' => $request->input('active'),
                "updated_at" => DB::raw('now()')
            ]);

        return redirect()->route('admin.comunity_admin_list');
    }

    public function comunity_admin_delete(Request $request, $id) {
        DB::connection('mysql_sub')->table('comunity_admin')->where('id', $id)->delete();
        
        return Redirect::route('admin.comunity_admin_list');
    }

    public function comunity_comment_list(Request $request){
        $board_list = DB::connection('mysql_sub')->table('comunity_board')->get();

        $board_name = '%'.$request->board_name.'%';
        $keyword = '%'.$request->keyword.'%';

        date_default_timezone_set("Asia/Seoul");
        DB::connection('mysql_sub')->enableQueryLog();
        $first = DB::connection('mysql_sub')
        ->table('comment')
        ->select('id','board_table','writer_nickname', 'comment', 'created_at', DB::raw("'comment' AS tablename"))
        ->where('board_table','like',$board_name)
        ->where(function($query) use ($keyword){
            $query->where('writer_nickname','like',$keyword)->orwhere('comment','like',$keyword);
        });
        

        $second = DB::connection('mysql_sub')
        ->table('re_comment')
        ->select('id','board_table','writer_nickname', 'comment', 'created_at', DB::raw("'re_comment' AS tablename"))
        ->where('board_table','like',$board_name)
        ->where(function($query) use ($keyword){
            $query->where('writer_nickname','like',$keyword)->orwhere('comment','like',$keyword);
        });

        $comments = $first->unionAll($second)->orderBy('created_at','desc')->paginate(20);

        info($comments);

        $datetime = date("H시 i분 s초");

        $views = view('admin.comment.list');

        $comments->withPath('/admin/comment')->appends(['board_name' => $request->board_name, 'keyword' => $request->keyword])->links();

        $views->comments = $comments;
        $views->board_list = $board_list;
        $views->board_name = $request->board_name;
        $views->keyword = $request->keyword;
        $views->page = $request->page;
        $views->datetime = $datetime;

        return $views;
    }

    public function comunity_comment_delete(Request $request) {
        $delete_items = $request->delete_item;
        if(isset($delete_items)){
            foreach($delete_items as $item){
                $explode = explode("_",$item);
                
                $table = $explode[0];
                $id = $explode[1];
                
                DB::connection('mysql_sub')->table($table)->where('id', $id)->delete();
            }
        }
        //DB::connection('mysql_sub')->table('comunity_admin')->where('id', $id)->delete();
        
        return redirect('/admin/comment?board_name='.$request->board_name.'&keyword='.$request->keyword.'&page='.$request->page);
    }

    public function simul_invest(Request $request){
        if(isset($request->page)){
            $page = $request->page;
        }else{
            $page = 1;
        }

        $coins = DB::table('btc_coins')->where('active',1)->orderBy('market','asc')->get();
        $calcul_sql = "";
        foreach($coins as $coin){
            $calcul_sql .= " + (available_balance_".$coin->api." * ".$coin->last_trade_price_krw.")";
        }
        $sql = "SELECT usr.fullname,usr.email ,(0 ".$calcul_sql." - 100000000 ) as eval_krw,  ((0 ".$calcul_sql." - 100000000 )/100000000 * 100) as eval_per
                FROM btc_users_addresses bua 
                INNER JOIN users usr 
                ON bua.label = usr.username
                ORDER BY eval_krw DESC";
        $invests = DB::table('btc_users_addresses')
                    ->where('users.username','<>','sbtr01')->where('users.username','<>','rorososo10000@naver.com')->where('users.username','<>','duddn688@gmail.com')
                    ->join('users','btc_users_addresses.label','=','users.username')
                    ->select('users.fullname','users.email',DB::raw('(0 '.$calcul_sql.' - 17000000 ) as eval_total'),DB::raw('((0 '.$calcul_sql.' - 17000000 )/17000000 * 100) as eval_per'))
                    ->orderBy('eval_total','DESC')
                    ->paginate(30);
        
        $invests->withPath('simul_invest');
        
        $views = view('admin.simul.simul_invest');
        $views->invests = $invests;
        $views->page = $page;

        

        return $views;
    }

    public function game_schedule_list(Request $request){
        $game_schedules = DB::connection('mysql_sub')->table('game_schedule')->orderBy('id','desc')->paginate(20);

        $game_schedules->withPath('/admin/game_schedules');

        $datetime = date("H시 i분 s초");

        $views = view('admin.game_schedule.game_schedule_list');
        $views->game_schedules = $game_schedules;
        $views->datetime = $datetime;

        return $views;
    }

    public function game_schedule_create(){
        $views = view('admin.game_schedule.game_schedule_create');

        return $views;
    }

    public function game_schedule_edit($id){
        $game_schedule = DB::connection('mysql_sub')->table('game_schedule')->where('id', $id)->first();
        $yoil = array("일","월","화","수","목","금","토");
        $yoil_name = $yoil[date('w', strtotime($game_schedule->date))];

        
        $views = view('admin.game_schedule.game_schedule_edit');

        $schedule_lists = json_decode($game_schedule->schedule_lists, false);
        if($schedule_lists === null){
            $schedule_lists = array();
        }
        
        $views->game_schedule = $game_schedule;
        $views->yoil_name = $yoil_name;
        $views->schedule_lists = $schedule_lists;
        $views->id = $id;

        return $views;
    }

    public function game_schedule_detail($id, $index){
        $game_schedule = DB::connection('mysql_sub')->table('game_schedule')->where('id', $id)->first();
        $yoil = array("일","월","화","수","목","금","토");
        $yoil_name = $yoil[date('w', strtotime($game_schedule->date))];

        
        $views = view('admin.game_schedule.game_schedule_detail');

        $schedule_lists = json_decode($game_schedule->schedule_lists, false);
        if($schedule_lists === null){
            $schedule_lists = array();
        }
        
        $views->game_schedule = $game_schedule;
        $views->yoil_name = $yoil_name;
        $views->schedule_lists = $schedule_lists[$index];
        $views->id = $id;
        $views->index = $index;

        return $views;
    }

    public function game_schedule_store(Request $request){
        $game_date = $request->game_date;

        $id = DB::connection('mysql_sub')->table('game_schedule')->insertGetId([
            "date" => $game_date
        ]);

        return redirect()->route('admin.game_schedule_edit', $id);
    }

    public function game_schedule_update($id, Request $request){
        if((int)$request->hour < 12){
            $am_pm = 'AM';
        }else{
            $am_pm = 'PM';
        }

        if (!isset($request->team1) || !isset($request->team2)){
            return redirect() -> back() -> with('jsAlert', '구단명은 꼭! 입력해야 합니다!');
        }

        if (!isset($request->team1_symbol) || !isset($request->team2_symbol)){
            return redirect() -> back() -> with('jsAlert', '구단 마크 이미지는 꼭! 입력 해야합니다!');
        }

        if (!isset($request->league_name)){
            return redirect() -> back() -> with('jsAlert', '리그 이름을 꼭! 입력 해야합니다!');
        }

        $game_time = $am_pm.' '.$request->hour.' : '.$request->min;

        $game_type = $request->game_type;
        $league_name = $request->league_name;

        $team1 = $request->team1;
        $team2 = $request->team2;

        $team1_score = $request->team1_score;
        $team2_score = $request->team2_score;

        $team1_symbol = File_store::ImageStoreWithName('game_schedule', $request->team1_symbol);
        $team1_symbol = implode( '', $team1_symbol );
        
        $team2_symbol = File_store::ImageStoreWithName('game_schedule', $request->team2_symbol);
        $team2_symbol = implode( '', $team2_symbol );


        $game_schedule = DB::connection('mysql_sub')->table('game_schedule')->where('id', $id)->first();

        $schedule_lists = json_decode($game_schedule->schedule_lists, true);
        //dd(gettype($schedule_lists));
        $arr = array(
            "key" => md5(time()),
            "game_time" => $game_time,
            "game_type" => $game_type,
            "league_name" => $league_name,
            "team1" => $team1,
            "team2" => $team2,
            "team1_score" => $team1_score,
            "team2_score" => $team2_score,
            "team1_symbol" => $team1_symbol,
            "team2_symbol" => $team2_symbol,
        );
        //dd($schedule_lists);
        if($schedule_lists === NULL){
            $temp_arr = array();
            array_push($temp_arr, $arr);
        }else if(count($schedule_lists) === 0){
            $temp_arr = array();
            array_push($temp_arr, $schedule_lists, $arr);
            //dd($temp_arr);
        }else{
            array_push($schedule_lists, $arr);
            $temp_arr = $schedule_lists;
            //dd($temp_arr);
        }

        DB::connection('mysql_sub')->table('game_schedule')->where('id', $id)->update([
            "schedule_lists" => json_encode( $temp_arr )
        ]);

        return redirect()->back();
    }

    public function game_schedule_update_detail($id,$index , Request $request){
        if((int)$request->hour < 12){
            $am_pm = 'AM';
        }else{
            $am_pm = 'PM';
        }

        if (!isset($request->team1) || !isset($request->team2)){
            return redirect() -> back() -> with('jsAlert', '구단명은 꼭! 입력해야 합니다!');
        }

        if (!isset($request->league_name)){
            return redirect() -> back() -> with('jsAlert', '리그 이름을 꼭! 입력 해야합니다!');
        }

        $game_time = $am_pm.' '.$request->hour.' : '.$request->min;

        $game_type = $request->game_type;
        $league_name = $request->league_name;

        $team1 = $request->team1;
        $team2 = $request->team2;

        $team1_score = $request->team1_score;
        $team2_score = $request->team2_score;

        

        $game_schedule = DB::connection('mysql_sub')->table('game_schedule')->where('id', $id)->first();

        $schedule_lists = json_decode($game_schedule->schedule_lists, true);

        if(isset($request->team1_symbol)){
            $team1_symbol = File_store::ImageStoreWithName('game_schedule', $request->team1_symbol);
            $team1_symbol = implode( '', $team1_symbol );
        }else{
            $team1_symbol = $schedule_lists[$index]['team1_symbol'];
        }
        if(isset($request->team2_symbol)){
            $team2_symbol = File_store::ImageStoreWithName('game_schedule', $request->team2_symbol);
            $team2_symbol = implode( '', $team2_symbol );
        }else{
            $team2_symbol = $schedule_lists[$index]['team2_symbol'];
        }
        //dd(gettype($schedule_lists));
        $arr[$index] = array(
            "key" => md5(time()),
            "game_time" => $game_time,
            "game_type" => $game_type,
            "league_name" => $league_name,
            "team1" => $team1,
            "team2" => $team2,
            "team1_score" => $team1_score,
            "team2_score" => $team2_score,
            "team1_symbol" => $team1_symbol,
            "team2_symbol" => $team2_symbol,
        );
        //dd($schedule_lists);
        $temp_arr = array();
        info($schedule_lists);
        array_splice($schedule_lists,$index,1,$arr);
        info($schedule_lists);
        
        $temp_arr = $schedule_lists;


        DB::connection('mysql_sub')->table('game_schedule')->where('id', $id)->update([
            "schedule_lists" => json_encode( $temp_arr )
        ]);

        return redirect()->route('admin.game_schedule_edit', $id);
    }

    public function game_schedule_delete(Request $request, $id){
        $delete_id = $request->id;
        DB::connection('mysql_sub')->table('game_schedule')->where('id', $delete_id)->delete();

        return redirect()->back();
    }

    public function game_schedule_delete_detail($id, $index){
        $game_schedule = DB::connection('mysql_sub')->table('game_schedule')->where('id', $id)->first();

        $schedule_lists = json_decode($game_schedule->schedule_lists, true);

        array_splice($schedule_lists,$index,1);

        DB::connection('mysql_sub')->table('game_schedule')->where('id', $id)->update([
            "schedule_lists" => json_encode( $schedule_lists )
        ]);

        return redirect()->route('admin.game_schedule_edit', $id);
    }

    public function user_balance_coin($api){
        info($api);
        $datetime = date("H시 i분 s초");
        $coins = DB::table('btc_coins')->select('api')->where('active',1)->orderBy('market','asc')->get();

        $columns = array();
        $columns[] = 'users.email';
        $columns[] = 'users.mobile_number';
        $columns[] = 'users.fullname';
        $columns[] = 'btc_users_addresses.available_balance_'.$api;
        /*foreach($coins as $coin){
            $columns[] = 'btc_users_addresses.available_balance_'.$coin->api;
        }*/
        $users = DB::table('btc_users_addresses')
        ->join('users','btc_users_addresses.uid','=','users.id')
        ->select($columns)
        ->where('users.status','!=','3')
        ->where('btc_users_addresses.available_balance_'.$api,'>','0')
        ->whereNotNull('users.mobile_number')
        ->get();

        $views = view('admin.user.user_balance_coin');
        $views->datetime = $datetime;
        $views->coins = $coins;
        $views->users = $users;
        $views->api = $api;
        
        return $views;
    }

    public function youtube_list(Request $request){
        $youtubes = DB::connection('mysql_sub')->table('youtube')->orderBy('id','desc')->paginate(20);

        $youtubes->withPath('/admin/youtube');

        $datetime = date("H시 i분 s초");

        $views = view('admin.youtube.youtube_list');
        $views->youtubes = $youtubes;
        $views->datetime = $datetime;

        return $views;
    }

    public function youtube_create() {
        $views = view('admin.youtube.youtube_create');
        return $views;
    }

    public function youtube_store(Request $request) {
        
        DB::connection('mysql_sub')->table('youtube')->insert([
            'sub_text' => $request->input('sub_text'),
            'title2' => $request->input('title2'),
            'title' => $request->input('title'),
            'url' => $request->input('url'),
            'sub_title' => $request->input('sub_title'),
            'contents_a' => $request->input('contents_a'),
            'contents_b' => $request->input('contents_b'),
            'contents_c' => $request->input('contents_c'),
            'created_at' => DB::raw('now()'),
            'updated_at' => DB::raw('now()')
        ]);
        
        return Redirect::route('admin.youtube_list');
    }
    
    public function youtube_edit(Request $request, $id) {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        $youtube = DB::connection('mysql_sub')->table('youtube')->select('*')->where('id', $id)->first();
        
        $views = view('admin.youtube.youtube_edit');
        $views->youtube = $youtube;
        $views->datetime = $datetime;
        
        return $views;
    }
    
    public function youtube_update(Request $request, $id) {
            
        DB::connection('mysql_sub')->table('youtube')->where('id', $id)->update([
            'sub_text' => $request->input('sub_text'),
            'title2' => $request->input('title2'),
            'title' => $request->input('title'),
            'sub_title' => $request->input('sub_title'),
            'contents_a' => $request->input('contents_a'),
            'contents_b' => $request->input('contents_b'),
            'contents_c' => $request->input('contents_c'),
            'url' => $request->input('url'),
            'updated_at' => DB::raw('now()')
        ]);
        
        return Redirect::route('admin.youtube_list');
    }
    
    public function youtube_delete(Request $request, $id) {

        
        DB::connection('mysql_sub')->table('youtube')->where('id', $id)->delete();
        
        return Redirect::route('admin.youtube_list');
    }

    public function youtube_active(Request $request, $id) {
        DB::connection('mysql_sub')->table('youtube')->where('id', '<>' ,$id)->update([
            'active' => 0
        ]);

        DB::connection('mysql_sub')->table('youtube')->where('id', $id)->update([
            'active' => 1
        ]);
        
        return Redirect::route('admin.youtube_list');
    }

    // 대표님 n_event 확인 관련 
    public function n_event_current(){
        $views = view('admin.event.n_event_current');
        $btc_events_time = DB::connection('mysql_sub')->table('btc_events')->where('id','4')->value('end_time');
        $btc_now_time = date('Y-m-d');
        
        $number_array = array();
        $status_array = array();
        $max_trades_id = 0;

        if(strtotime($btc_events_time) >= strtotime($btc_now_time)) {

            $btc_events_n_data = DB::table('btc_events_n')->where('date', '=', DB::raw('DATE_FORMAT(now(),"%Y-%m-%d")'))->first();
            
            if(isset($btc_events_n_data)) {

                $max_trades_id_array = DB::select('select max(num) AS maxnum from
                (
                select @num:=@num+1 as num, b.id
                from (select @num:=0) a, html.btc_trades_COIN_btc b, users c
                where DATE_FORMAT(b.created_dt, "%Y-%m-%d") = DATE_FORMAT(now(), "%Y-%m-%d") and b.contract_coin_amt >= 1000
                and b.buyer_uid = c.id
                order by id asc
                ) d');

                $max_trades_id = $max_trades_id_array[0]->maxnum;

                if(isset($btc_events_n_data->json_data)){
                    $json_data_array = json_decode($btc_events_n_data->json_data, true);
                    $json_data_array_new = array();

                    foreach($json_data_array as $data) {
                        $json_data_array_new[$data['number']] = $data;
                    }

                    ksort($json_data_array_new);
                }
            }
        }

        $views->json_data_array_new = $json_data_array_new;
        $views->max_trades_id = $max_trades_id;

        return $views;
    }

    public function n_event_winners_list(){
        $views = view('admin.event.n_event_winners_list');
        $winners_list = DB::select('select date as c_day, b.email, b.fullname, b.mobile_number, num, proname, a.nickname from
        (
        (select `date`, json_extract(json_data, "$[0].uid") as uid, json_extract(json_data, "$[0].number") as num, json_extract(json_data, "$[0].nickname") as nickname, json_extract(json_data, "$[0].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[1].uid") as uid, json_extract(json_data, "$[1].number") as num, json_extract(json_data, "$[1].nickname") as nickname, json_extract(json_data, "$[1].productName") as proname
        FROM html.btc_events_n)
         union all
        (select `date`, json_extract(json_data, "$[2].uid") as uid, json_extract(json_data, "$[2].number") as num, json_extract(json_data, "$[2].nickname") as nickname, json_extract(json_data, "$[2].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[3].uid") as uid, json_extract(json_data, "$[3].number") as num, json_extract(json_data, "$[3].nickname") as nickname, json_extract(json_data, "$[3].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[4].uid") as uid, json_extract(json_data, "$[4].number") as num, json_extract(json_data, "$[4].nickname") as nickname, json_extract(json_data, "$[4].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[5].uid") as uid, json_extract(json_data, "$[5].number") as num, json_extract(json_data, "$[5].nickname") as nickname, json_extract(json_data, "$[5].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[6].uid") as uid, json_extract(json_data, "$[6].number") as num, json_extract(json_data, "$[6].nickname") as nickname, json_extract(json_data, "$[6].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[7].uid") as uid, json_extract(json_data, "$[7].number") as num, json_extract(json_data, "$[7].nickname") as nickname, json_extract(json_data, "$[7].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[8].uid") as uid, json_extract(json_data, "$[8].number") as num, json_extract(json_data, "$[8].nickname") as nickname, json_extract(json_data, "$[8].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[9].uid") as uid, json_extract(json_data, "$[9].number") as num, json_extract(json_data, "$[9].nickname") as nickname, json_extract(json_data, "$[9].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[10].uid") as uid, json_extract(json_data, "$[10].number") as num, json_extract(json_data, "$[10].nickname") as nickname, json_extract(json_data, "$[10].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[11].uid") as uid, json_extract(json_data, "$[11].number") as num, json_extract(json_data, "$[11].nickname") as nickname, json_extract(json_data, "$[11].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[12].uid") as uid, json_extract(json_data, "$[12].number") as num, json_extract(json_data, "$[12].nickname") as nickname, json_extract(json_data, "$[12].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[13].uid") as uid, json_extract(json_data, "$[13].number") as num, json_extract(json_data, "$[13].nickname") as nickname, json_extract(json_data, "$[13].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[14].uid") as uid, json_extract(json_data, "$[14].number") as num, json_extract(json_data, "$[14].nickname") as nickname, json_extract(json_data, "$[14].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[15].uid") as uid, json_extract(json_data, "$[15].number") as num, json_extract(json_data, "$[15].nickname") as nickname, json_extract(json_data, "$[15].productName") as proname
        FROM html.btc_events_n)
        ) a,
        users b
        where a.uid = b.id order by c_day asc;');
        
        $views->winners_list = $winners_list;
        return $views;
    }

    public function n_event_winners_list_refresh(Request $request){
        
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        
        $search_lists = DB::select('select date as c_day, b.email, b.fullname, b.mobile_number, num, proname, a.nickname from
        (
        (select `date`, json_extract(json_data, "$[0].uid") as uid, json_extract(json_data, "$[0].number") as num, json_extract(json_data, "$[0].nickname") as nickname, json_extract(json_data, "$[0].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[1].uid") as uid, json_extract(json_data, "$[1].number") as num, json_extract(json_data, "$[1].nickname") as nickname, json_extract(json_data, "$[1].productName") as proname
        FROM html.btc_events_n)
         union all
        (select `date`, json_extract(json_data, "$[2].uid") as uid, json_extract(json_data, "$[2].number") as num, json_extract(json_data, "$[2].nickname") as nickname, json_extract(json_data, "$[2].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[3].uid") as uid, json_extract(json_data, "$[3].number") as num, json_extract(json_data, "$[3].nickname") as nickname, json_extract(json_data, "$[3].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[4].uid") as uid, json_extract(json_data, "$[4].number") as num, json_extract(json_data, "$[4].nickname") as nickname, json_extract(json_data, "$[4].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[5].uid") as uid, json_extract(json_data, "$[5].number") as num, json_extract(json_data, "$[5].nickname") as nickname, json_extract(json_data, "$[5].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[6].uid") as uid, json_extract(json_data, "$[6].number") as num, json_extract(json_data, "$[6].nickname") as nickname, json_extract(json_data, "$[6].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[7].uid") as uid, json_extract(json_data, "$[7].number") as num, json_extract(json_data, "$[7].nickname") as nickname, json_extract(json_data, "$[7].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[8].uid") as uid, json_extract(json_data, "$[8].number") as num, json_extract(json_data, "$[8].nickname") as nickname, json_extract(json_data, "$[8].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[9].uid") as uid, json_extract(json_data, "$[9].number") as num, json_extract(json_data, "$[9].nickname") as nickname, json_extract(json_data, "$[9].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[10].uid") as uid, json_extract(json_data, "$[10].number") as num, json_extract(json_data, "$[10].nickname") as nickname, json_extract(json_data, "$[10].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[11].uid") as uid, json_extract(json_data, "$[11].number") as num, json_extract(json_data, "$[11].nickname") as nickname, json_extract(json_data, "$[11].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[12].uid") as uid, json_extract(json_data, "$[12].number") as num, json_extract(json_data, "$[12].nickname") as nickname, json_extract(json_data, "$[12].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[13].uid") as uid, json_extract(json_data, "$[13].number") as num, json_extract(json_data, "$[13].nickname") as nickname, json_extract(json_data, "$[13].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[14].uid") as uid, json_extract(json_data, "$[14].number") as num, json_extract(json_data, "$[14].nickname") as nickname, json_extract(json_data, "$[14].productName") as proname
        FROM html.btc_events_n)
        union all
        (select `date`, json_extract(json_data, "$[15].uid") as uid, json_extract(json_data, "$[15].number") as num, json_extract(json_data, "$[15].nickname") as nickname, json_extract(json_data, "$[15].productName") as proname
        FROM html.btc_events_n)
        ) a,
        users b
        where a.uid = b.id and date >= :start_date and date <= :end_date order by c_day asc;', ["start_date" => $start_date , "end_date" => $end_date ]);
        
        $response = $search_lists;
        return response()->json($response);
    }
    
}
