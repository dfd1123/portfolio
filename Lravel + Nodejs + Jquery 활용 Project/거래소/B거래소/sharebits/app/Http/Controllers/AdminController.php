<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use App\Mail\Notify;

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

        $month_trades_revenues = DB::table('btc_trades_COIN_btc')
        ->select(
            DB::raw('CONCAT(YEAR(btc_trades_COIN_btc.created_dt),"-",MONTH(btc_trades_COIN_btc.created_dt)) AS date'),
            DB::raw('(SUM(btc_trades_COIN_btc.contract_coin_amt*btc_trades_COIN_btc.buy_coin_price) * (SELECT (buy_comission + sell_comission) AS fee FROM btc_settings WHERE id = '.Session('market_type').' ) * 0.01) - IFNULL((SELECT sum(amount) FROM btc_lock_dividend where CONCAT(YEAR(created_dt),"-",MONTH(created_dt)) = date),0) AS total_fee_price')
        )->where('btc_trades_COIN_btc.created','>',DB::raw('UNIX_TIMESTAMP(NOW() - INTERVAL 1 YEAR)'))->where('buyer_username','<>','sbtr01')->where('seller_username','<>','sbtr01')
        ->orderBy(DB::raw('CONCAT(YEAR(btc_trades_COIN_btc.created_dt),"-",MONTH(btc_trades_COIN_btc.created_dt))'),'asc')
        ->groupBy(DB::raw('CONCAT(YEAR(btc_trades_COIN_btc.created_dt),"-",MONTH(btc_trades_COIN_btc.created_dt))'))->get();

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
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->where('users.id',$keyword)->whereNotNull('users.status')->paginate(20);
            }else if($keyword_srch == 'username'){
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->where('users.username','like','%'.$keyword.'%')->whereNotNull('users.status')->paginate(20);
            }else if($keyword_srch == 'name'){
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->where('users.fullname','like','%'.$keyword.'%')->whereNotNull('users.status')->paginate(20);
            }else if($keyword_srch == 'id'){
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->where('users.email','like','%'.$keyword.'%')->whereNotNull('users.status')->paginate(20);
            }else if($keyword_srch == 'mobile'){
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->where('users.mobile_number','like','%'.$keyword)->whereNotNull('users.status')->paginate(20);
            }
        }else{
            $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->whereNotNull('users.status')->paginate(20);
        }
        
        
        $users->withPath('user_list');
        
        $coins = DB::table('btc_coins')->where('active',1)->get();
        
        $views = view('admin.user.user_list');
        
        $views->users = $users;
        $views->coins = $coins;
        $views->datetime = $datetime;
        
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

    public function coin_listing_update(Request $request, $id, $active) {
        DB::table('btc_coins')->where('cointype', '<>', 'cash')->where('id', $id)->update([
            'active' => $active
        ]);
        
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
                if($keyword_srch == 'name'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.fullname','like','%'.$keyword.'%');
                }else if($keyword_srch == 'id'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.email','like','%'.$keyword.'%');
                }else if($keyword_srch == 'mobile'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.mobile_number','like','%'.$keyword);
                }
            }else{
                $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid');
            }
        }else{
            $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('btc_security_lv.account_verified',$type)->paginate(30);
            if($request->input('keyword') != null){
                $keyword = $request->input('keyword');
            }
            
            if($keyword_srch != null){
                if($keyword_srch == 'name'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.fullname','like','%'.$keyword.'%')->where('btc_security_lv.account_verified',$type);
                }else if($keyword_srch == 'id'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.email','like','%'.$keyword.'%')->where('btc_security_lv.account_verified',$type);
                }else if($keyword_srch == 'mobile'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.mobile_number','like','%'.$keyword)->where('btc_security_lv.account_verified',$type);
                }
            }else{
                $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('btc_security_lv.account_verified',$type);
            }
        }
        
        $securitys = $securitys->paginate(30);
        
        $securitys->withPath('account_list');
        
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
                if($keyword_srch == 'name'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.fullname','like','%'.$keyword.'%');
                }else if($keyword_srch == 'id'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.email','like','%'.$keyword.'%');
                }else if($keyword_srch == 'mobile'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.mobile_number','like','%'.$keyword);
                }
            }else{
                $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid');
            }
        }else{
            $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('btc_security_lv.document_verified',$type)->paginate(30);
            if($request->input('keyword') != null){
                $keyword = $request->input('keyword');
            }
            
            if($keyword_srch != null){
                if($keyword_srch == 'name'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.fullname','like','%'.$keyword.'%')->where('btc_security_lv.document_verified',$type);
                }else if($keyword_srch == 'id'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.email','like','%'.$keyword.'%')->where('btc_security_lv.document_verified',$type);
                }else if($keyword_srch == 'mobile'){
                    $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('users.mobile_number','like','%'.$keyword)->where('btc_security_lv.document_verified',$type);
                }
            }else{
                $securitys = DB::table('users')->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->where('btc_security_lv.document_verified',$type);
            }
        }
        
        $securitys = $securitys->paginate(30);
        
        $securitys->withPath('document_list');
        
        $views->type = $type;
        $views->securitys = $securitys;
        $views->datetime = $datetime;
        
        return $views;
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
            "infor_manager" => $request->input('infor_manager'),
        ]);
        
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
        } elseif($type == 'exit') {
            DB::table('btc_lock_coins')->where('id', $id)->where('status', 1)->update(['status' => -1, 'updated_dt' => now()]);
        } elseif($type == 'cancel_exit') {
            DB::table('btc_lock_coins')->where('id', $id)->where('status', -1)->update(['status' => 1, 'updated_dt' => now()]);
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
            }
        }
        
        return Redirect::route('admin.coin_lock_list');
    }

    public function coin_tr_list(Request $request) {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $keyword_srch = $request->input('keyword_srch');
        $keyword = $request->input('keyword');

        $transactions = DB::table('btc_transaction')->select('*')->where('category', '<>', 'trade')->orderBy('id','desc');

        if($keyword_srch != null) {
            $keyword = $keyword != null ? $keyword : '';
            if($keyword_srch == 'account') {
                $transactions = $transactions->where('account','like','%'.$keyword.'%');
            } else if($keyword_srch == 'address') {
                $transactions = $transactions->where('address','like','%'.$keyword.'%');
            } else if($keyword_srch == 'coin') {
                $transactions = $transactions->whereRaw("LOWER(cointype) like LOWER('%$keyword%')");
            }
        }
        
        $transactions_page = $transactions->paginate(20);
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

        return Redirect::route('admin.airdrop_list');
    }

    public function p2p_list($type) {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");
        
        $today = date("Y-m-d H:i:s");
        if($type == 6){
            $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('state','stop')->orderBy('id','desc')->get();
        }
        elseif($type == 5){
            $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',1)->orderBy('id','desc')->get();
        }else{
            $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('confirm',$type)->where('state','<>','stop')->orderBy('id','desc')->get();
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
        
        return Redirect::route('admin.rights_management_list');
    }
    
    public function rights_management_delete($id){
        DB::table('admin')->where('id', $id)->delete();
    
        return Redirect::route('admin.rights_management_list');
    }

    //NOTICE

    public function notice_list($country){
        $notices = DB::connection('mysql_sub')->table('btc_notice_'.$country)->orderBy('id','desc')->paginate(20);

        $notices->withPath('/admin/notices');

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

        return redirect()->route('admin.notice_edit', ['country' => $country, 'id' => $id]);
    }

    public function notice_update(Request $request, $id){

        $country = $request->input('country');

        DB::connection('mysql_sub')->table('btc_notice_'.$country)->where('id',$id)->update([
            "title" => $request->input('title'),
            "description" => $request->input('description'),
            "updated" => time(),
        ]);

        return redirect()->back();
    }

    public function notice_delete($country, $id){
        DB::connection('mysql_sub')->table('btc_notice_'.$country)->where('id',$id)->delete();

        return redirect()->route('admin.notice_list', $country);
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

        DB::connection('mysql_sub')->table('btc_faq_'.$country)->where('id',$id)->update([
            "question" => $request->title,
            "answer" => $request->description,
            "faq_type" => $request->faq_type,
            "updated" => time(),
        ]);

        return redirect()->back();
    }

    public function faq_delete($country, $id){
        DB::connection('mysql_sub')->table('btc_faq_'.$country)->where('id',$id)->delete();

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
        $qna = DB::connection('mysql_sub')->table('btc_qna')->where('id',$id)->first();

        $views = view('admin.qna.qna_answer_create');

        $views->qna = $qna;
        $views->id = $id;
        
        return $views;
    }

    public function qna_answer_edit($id){
        $qna = DB::connection('mysql_sub')->table('btc_qna')->where('id',$id)->first();

        $qna_answer = DB::connection('mysql_sub')->table('btc_qna_comment')->where('qna_id',$id)->first();

        $views = view('admin.qna.qna_answer_edit');

        $views->qna = $qna;
        $views->qna_answer = $qna_answer;
        $views->id = $id;
        
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

        DB::connection('mysql_sub')->table('btc_qna_comment')->where('qna_id',$id)->update([
            "description" => $request->description,
            "updated" => time(),
        ]);

        return redirect()->back();
    }

    public function qna_delete($country, $id){
        DB::connection('mysql_sub')->table('btc_qna')->where('id',$id)->delete();
        DB::connection('mysql_sub')->table('btc_qna_comment')->where('qna_id',$id)->delete();

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

        $coins = DB::table('btc_coins')->where('cointype','<>','cash')->where('active',1)->get();

        $trade_historys->withPath('/admin/trade/trade_history');

        $views = view('admin.trade.trade_history');

        $datetime = date("H시 i분 s초");

        $views->datetime = $datetime;
        $views->trade_historys = $trade_historys;
        $views->coins = $coins;

        return $views;
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

        if($category == 'all'){
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
        }

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
            ->orderBy("updated","desc")->paginate(30);	
            
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
            $receive_transaction = $receive_transaction->unionAll($coin_io_transactions)->orderBy("updated","desc")->paginate(30);	
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
        
        return Redirect::route('admin.ico_list');
    }

    public function ico_ban(Request $request, $id){
        $reject = $request->reject;
        DB::connection('mysql_sub')->table('btc_ico_new')->where('id', $id)->update([
            'active' => 0,
            'ico_category' => 5,
            'reject' => $reject,
        ]);
    
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
			"start_time" => $request->start_time,
			"end_time" => $request->end_time,
			"created_at" => now(),
			"updated_at" => now(),
		]);
		
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
			"start_time" => $request->start_time,
			"end_time" => $request->end_time,
			"updated_at" => now(),
		]);
		
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
		$coins = DB::table('btc_coins')->where('active','1')
					->where(function($qry){
			        	$qry->where('cointype','coin')->orWhere('cointype','token');
					})->orderBy('id','asc')->get();
			
		$company_balances = DB::table('btc_users_addresses')->where('label','sbtr01')->first();
		$results = array();
		foreach($coins as $coin){
			$sum_users_balance = DB::table('btc_users_addresses')->where('label','!=','sbtr01')->sum('available_balance_'.$coin->api);
			$company_balance = $company_balances->{'available_balance_'.$coin->api};
			
			if($coin->cointype == 'coin' && $coin->symbol != 'ETH'){
				$postdata = array(
					'cointype' => $coin->symbol
				);
				
				$ch = curl_init();                    // Initiate cURL
			    $url = "https://io.sharebits.info/api/get_private_info.php"; // Where you want to post data
			    curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			    curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); // Define what you want to post
			    
			    $balance = curl_exec ($ch); // Execute
			
			    curl_close ($ch); // Close cURL handle
			}else{
				if($coin->symbol == 'ETH'){
					$ch = curl_init();                    // Initiate cURL
				    $api_url = 'https://api.etherscan.io/api?module=account&action=balance&address=0x483a3afb464c5fc5f76802f1795058aed3f8608f&tag=latest&apikey=X59FEHDBQKKI8P6NHQ3J5IPFXEYCQ42RI3';
				    curl_setopt($ch, CURLOPT_URL,$api_url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				    $result = curl_exec ($ch); // Execute
				    curl_close ($ch); // Close cURL handle
				    
				    $json_balance = json_decode($result);
				    
					$balance = bcdiv($json_balance->result,pow(10,$coin->decimal_place),8);
				}else{
					$ch = curl_init();                    // Initiate cURL
				    $api_url = 'https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress='.$coin->token_contract_addr.'&address=0x483a3afb464c5fc5f76802f1795058aed3f8608f&tag=latest&apikey=X59FEHDBQKKI8P6NHQ3J5IPFXEYCQ42RI3';
				    curl_setopt($ch, CURLOPT_URL,$api_url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				    $result = curl_exec ($ch); // Execute
				    curl_close ($ch); // Close cURL handle
				    
				    $json_balance = json_decode($result);
				    
					$balance = bcdiv($json_balance->result,pow(10,$coin->decimal_place),8);
				}
				
			}
			
			$results["company_balance_".$coin->api] = $company_balance;
			$results["users_balance_".$coin->api] = $sum_users_balance;
			$results["market_balance_".$coin->api] = $balance;
		}
		info($results);
        $views = view('admin.coin.coin_has_list');
        $datetime = date("H시 i분 s초");
		
		$views->coins = $coins;
		$views->results = $results;
        $views->datetime = $datetime;

        return $views;

    }
}
