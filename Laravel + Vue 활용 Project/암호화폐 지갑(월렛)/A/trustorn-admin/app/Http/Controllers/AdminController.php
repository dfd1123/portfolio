<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 60 * 60);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use App\Exports\UsersExport;
use App\Exports\UserRevenueExport;
use App\Exports\TradeHistorysExport;
use App\Exports\CoinTrsExport;
use Maatwebsite\Excel\Facades\Excel;
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
use DateTime;

use Facades\App\Classes\LoginTrace;

class AdminController extends Controller
{
    public function index()
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $qna_counts_kr = DB::connection('mysql_sub')->table('btc_qna')->where('answered', 0)->where('country', 'kr')->count();

        $views = view('admin.main.home');
        $views->datetime = $datetime;
        $views->qna_counts_kr = $qna_counts_kr;
        $views->start_date = date("Y-m", strtotime("-11 months"));
        $views->end_date = date("Y-m");

        return $views;
    }

    public function company_info()
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $company = DB::table('btc_settings')->where('url', 'https://admin.trustorn.com/')->first();

        $views = view('admin.infor.company_infor');

        $views->company = $company;
        $views->datetime = $datetime;

        return $views;
    }

    public function company_info_update(Request $request)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        DB::table('btc_settings')->update([
            "company" => $request->company_name,
            "ceo" => $request->ceo_name,
            "business_num" => $request->business_number,
            "address" => $request->address
        ]);

        return redirect() -> back();
    }

    public function login()
    {
        $views = view('admin.auth.login');

        return $views;
    }

    public function login_form(Request $request)
    {
        if (Auth::guard('admin') -> attempt(['email' => $request -> input('email'), 'password' => $request -> input('password')])) {
            return redirect() -> route('admin.main');
        } else {
            return redirect() -> back() -> with('jsAlert', '아이디나 비밀번호가 맞지 않습니다.');
        }

        $views = view('admin.auth.login');

        return $views;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();

        return Redirect::route('admin.login');
    }

    public function register_agree()
    {
        $views = view('admin.auth.register_agree');

        return $views;
    }

    public function register_complete()
    {
        $views = view('admin.auth.register_complete');

        return $views;
    }

    public function user_list(Request $request)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $keyword_srch = $request->input('keyword_srch');

        $keyword = '';

        if ($request->input('keyword') != null) {
            $keyword = $request->input('keyword');
        }



        if ($keyword_srch != null) {
            if ($keyword_srch == 'uid') {
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->where('users.id', $keyword)->whereNotNull('users.status')->paginate(20);
            } elseif ($keyword_srch == 'fullname') {
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->where(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))->whereNotNull('users.status')->paginate(20);
            } elseif ($keyword_srch == 'email') {
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->where('users.email', 'like', '%'.$keyword.'%')->whereNotNull('users.status')->paginate(20);
            } elseif ($keyword_srch == 'mobile') {
                $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->where('users.mobile_number', 'like', '%'.$keyword)->whereNotNull('users.status')->paginate(20);
            } else {
                $users = DB::table('btc_users_addresses')
                 ->join('users', 'users.id', '=', 'btc_users_addresses.uid')
                 ->where(function ($qry) use ($keyword) {
                     $qry->where('users.email', 'like', '%'.$keyword.'%')
                    ->orWhere(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
                    ->orWhere('users.mobile_number', 'like', '%'.$keyword.'%')
                    ->orWhere('users.id', $keyword);
                 })->whereNotNull('users.status')->paginate(20);
            }
        } else {
            $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->whereNotNull('users.status')->paginate(20);
        }


        $users->withPath('user_list');

        $coins = DB::table('btc_coins')->where('active', 1)->get();

        $views = view('admin.user.user_list');

        $views->users = $users;
        $views->coins = $coins;
        $views->datetime = $datetime;
        $views->keyword_srch = $keyword_srch;

        return $views;
    }

    public function user_excel(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        return Excel::download(new UsersExport($from, $to), '트러스톤사용자리스트_' .  date("Y-m-d") . '.xlsx');
    }

    public function user_detail(Request $request, $uid, $tab)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $coins = DB::table('btc_coins')->where('active', 1)->get();

        $views = view('admin.user.user_detail');
        $views->uid = $uid;
        $views->tab = $tab;
        $views->coins = $coins;
        $views->datetime = $datetime;

        if ($tab == 1) {
            $users = DB::table('btc_users_addresses')->join('users', 'users.id', '=', 'btc_users_addresses.uid')->where('users.id', $uid)->whereNotNull('users.status')->paginate(20);
            $users->withPath('user_detail');

            $views->users = $users;
        } elseif ($tab == 2) {
            $trade_historys = DB::table('btc_ads_btc')
            ->join('users', 'btc_ads_btc.userid', '=', 'users.username')
            ->select('btc_ads_btc.*', 'users.id as uid', 'users.email', 'users.mobile_number', 'users.fullname')
            ->where('btc_ads_btc.uid', $uid)
            ->orderBy('btc_ads_btc.id', 'desc')->paginate(30);
            $trade_historys->withPath('user_detail');

            $views->trade_historys = $trade_historys;
        } elseif ($tab == 3) {
            $transactions = DB::table('btc_transaction')
            ->join('users', 'btc_transaction.account', '=', 'users.username')
            ->select('btc_transaction.*', 'users.id as uid', 'users.email', 'users.mobile_number', 'users.fullname')
            ->where('btc_transaction.category', '<>', 'trade')
            ->where('users.id', $uid)
            ->orderBy('btc_transaction.id', 'desc')->paginate(30);
            $transactions->withPath('user_detail');

            $views->transactions = $transactions;
        } elseif ($tab == 4) {
            $icos = DB::connection('mysql_sub')->table('btc_ico_new')
            ->where('w_id', $uid)
            ->orderBy('id', 'asc')->paginate(20);
            $icos->withPath('user_detail');

            $views->icos = $icos;
        } elseif ($tab == 5) {
            $qnas = DB::table('users')
                    ->leftJoin('btc_qna', 'btc_qna.createdby', '=', 'users.username')
                    ->where('users.id', $uid)
                    ->orderBy('btc_qna.id', 'desc')->paginate(20);
            $qnas->withPath('user_detail');

            $views->qnas = $qnas;
        }

        return $views;
    }

    public function user_balance_activity(Request $request)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $keyword_srch = $request->input('keyword_srch');
        $keyword = $request->input('keyword');

        $activities = DB::table('btc_coin_io')
            ->leftJoin('users', 'btc_coin_io.uid', '=', 'users.id')
            ->select('btc_coin_io.*', 'users.fullname')
            ->orderBy('btc_coin_io.id', 'desc');

        if ($keyword_srch != null) {
            $keyword = $keyword != null ? $keyword : '';
            if ($keyword_srch == 'name') {
                $activities = $activities->where('users.fullname', 'like', '%'.$keyword.'%');
            } elseif ($keyword_srch == 'id') {
                $activities = $activities->where('btc_coin_io.username', 'like', '%'.$keyword.'%');
            }
        }

        $activities_page = $activities->paginate(20);
        $activities_page->withPath('user_balance_activity');

        if ($keyword_srch != null) {
            $activities_page->appends(['keyword_srch' => $keyword_srch, 'keyword' => $keyword])->links();
        }

        $views = view('admin.user.user_balance_activity');
        $views->activities = $activities->get();
        $views->activities_page = $activities_page;
        $views->datetime = $datetime;

        return $views;
    }

    public function coin_listing_list()
    {
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

    public function coin_listing_update(Request $request, $id, $active)
    {
        DB::table('btc_coins')->where('cointype', '<>', 'cash')->where('id', $id)->update([
            'active' => $active
        ]);

        $coin = DB::table('btc_coins')->where('id', $id)->first();

        if ($active == 0) {
            LoginTrace::Activity($coin->symbol.'상폐시킴');
        } else {
            LoginTrace::Activity($coin->symbol.'상장시킴');
        }

        return Redirect::route('admin.coin_listing_list');
    }

    public function banner_list()
    {
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

    public function banner_create()
    {
        $views = view('admin.banner.banner_create');
        return $views;
    }

    public function banner_store(Request $request)
    {
        $storage_save_path = 'public/image/banner';

        $path1 = null;
        $path2 = null;

        if ($request->hasFile('file1')) {
            if ($request->file('file1')->isValid()) {
                $path1 = str_replace($storage_save_path.'/', "", $request->file1->store($storage_save_path));
            }
        }

        if ($request->hasFile('file2')) {
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

    public function banner_edit(Request $request, $id)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $banner = DB::connection('mysql_sub')->table('btc_banners')->select('*')->where('id', $id)->first();

        $views = view('admin.banner.banner_edit');
        $views->banner = $banner;
        $views->datetime = $datetime;

        return $views;
    }

    public function banner_update(Request $request, $id)
    {
        $storage_save_path = 'public/image/banner';

        $banner = DB::connection('mysql_sub')->table('btc_banners')->select('*')->where('id', $id)->first();
        $path1 = $banner->banner_url;
        $path2 = $banner->banner_mobile_url;

        if ($request->hasFile('file1')) {
            if ($request->file('file1')->isValid()) {
                $old_path1 = $storage_save_path.'/'.$path1;
                if (Storage::exists($old_path1)) {
                    Storage::delete($old_path1);
                }
                $path1 = str_replace($storage_save_path.'/', "", $request->file1->store($storage_save_path));
            }
        }

        if ($request->hasFile('file2')) {
            if ($request->file('file2')->isValid()) {
                $old_path2 = $storage_save_path.'/'.$path2;
                if (Storage::exists($old_path2)) {
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

    public function banner_delete(Request $request, $id)
    {
        $storage_save_path = 'public/image/banner';

        $banner = DB::connection('mysql_sub')->table('btc_banners')->select('*')->where('id', $id)->first();
        $path1 = $banner->banner_url;
        $path2 = $banner->banner_mobile_url;

        $delete_path1 = $storage_save_path.'/'.$path1;
        if (Storage::exists($delete_path1)) {
            Storage::delete($delete_path1);
        }

        $delete_path2 = $storage_save_path.'/'.$path2;
        if (Storage::exists($delete_path2)) {
            Storage::delete($delete_path2);
        }

        DB::connection('mysql_sub')->table('btc_banners')->where('id', $id)->delete();

        return Redirect::route('admin.banner_list');
    }

    public function notify_create(Request $request, $type, $country)
    {
        $views = view('admin.notify.notify_create');
        $views->type = $type;
        $views->country = $country;

        if ($type == 1) {
            $market_type = Session::get('market_type');
            $setting = DB::table('btc_settings')->select('name', 'nexmo_api_key', 'nexmo_api_secret')->where('id', $market_type)->first();

            $views->check_nexmo = (!blank($setting->nexmo_api_key) && !blank($setting->nexmo_api_secret));
        }

        return $views;
    }

    public function notify_store(Request $request, $type)
    {
        $country = $request->country;
        if ($type == 0) {
            $users = DB::table('users')->select('email')->whereNotNull('email')->where('country', $country)->get(); // 배포코드
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
            $recipients = implode(',', $arr_recipients);
            $subject = $request -> input('title');
            $body = $request -> input('description');

            $result = Directsend_mail::send($recipients, $subject, $body, $directsend_username, $directsend_key, $directsend_sendfrom, $directsend_sendfrom_name);
            info('Directsend_mail result: ' . $result);
        } elseif ($type == 1) {
            $market_type = Session::get('market_type');
            $setting = DB::table('btc_settings')->select('name', 'nexmo_api_key', 'nexmo_api_secret')->where('id', $market_type)->first();
            if (blank($setting->nexmo_api_key) || blank($setting->nexmo_api_secret)) {
                return Redirect::route('admin.notify_create', $type);
            }

            $users = DB::table('users')->select('mobile_number')->whereNotNull('mobile_number')->where('country', $country)->whereRaw('length(mobile_number) >= 11')->get(); // 배포코드
            //$users = DB::table('users')->select('mobile_number')->whereNotNull('mobile_number')->where('country',$country)->whereRaw('length(mobile_number) >= 11')->where('username','smkim')->get(); // !!! 테스트용 코드  !!!

            foreach ($users as $user) {
                Nexmo_sms::send_sms($country, $user->mobile_number, $request -> input('description'));
            }
        }

        return Redirect::route('admin.notify_create', ['type' => $type, 'country' => $country]);
    }

    public function account_list(Request $request, $type)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $views = view('admin.user.user_account');

        $keyword_srch = $request->input('keyword_srch');

        $keyword = '';


        if ($type == 5) {
            if ($request->input('keyword') != null) {
                $keyword = $request->input('keyword');
            }

            if ($keyword_srch != null) {
                if ($keyword_srch == 'uid') {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('users.id', $keyword);
                } elseif ($keyword_srch == 'name') {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('users.fullname', 'like', '%'.$keyword.'%');
                } elseif ($keyword_srch == 'email') {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('users.email', 'like', '%'.$keyword);
                } elseif ($keyword_srch == 'mobile') {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('users.mobile_number', 'like', '%'.$keyword);
                } else {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')
                    ->where(function ($qry) use ($keyword) {
                        $qry->where('users.email', 'like', '%'.$keyword.'%')
                        ->orWhere(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
                        ->orWhere('users.mobile_number', 'like', '%'.$keyword.'%')
                        ->orWhere('users.id', $keyword);
                    });
                }
            } else {
                $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid');
            }
        } else {
            $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('btc_security_lv.account_verified', $type);
            if ($request->input('keyword') != null) {
                $keyword = $request->input('keyword');
            }

            if ($keyword_srch != null) {
                if ($keyword_srch == 'uid') {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('users.id', $keyword);
                } elseif ($keyword_srch == 'name') {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('users.fullname', 'like', '%'.$keyword.'%')->where('btc_security_lv.account_verified', $type);
                } elseif ($keyword_srch == 'email') {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('users.email', 'like', '%'.$keyword.'%')->where('btc_security_lv.account_verified', $type);
                } elseif ($keyword_srch == 'mobile') {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('users.mobile_number', 'like', '%'.$keyword)->where('btc_security_lv.account_verified', $type);
                } else {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')
                    ->where(function ($qry) use ($keyword) {
                        $qry->where('users.email', 'like', '%'.$keyword.'%')
                        ->orWhere(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
                        ->orWhere('users.mobile_number', 'like', '%'.$keyword.'%')
                        ->orWhere('users.id', $keyword);
                    });
                }
            } else {
                $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('btc_security_lv.account_verified', $type);
            }
        }

        $securitys = $securitys->orderBy('btc_security_lv.account_verified', 'desc')->paginate(30);

        $securitys->withPath('/admin/account_list/'.$type);

        $views->type = $type;
        $views->securitys = $securitys;
        $views->datetime = $datetime;

        return $views;
    }

    public function document_list(Request $request, $type)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $views = view('admin.user.user_document');

        $keyword_srch = $request->input('keyword_srch');

        $keyword = '';


        if ($type == 5) {
            if ($request->input('keyword') != null) {
                $keyword = $request->input('keyword');
            }

            if ($keyword_srch != null) {
                if ($keyword_srch == 'uid') {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('users.id', $keyword);
                } elseif ($keyword_srch == 'name') {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('users.fullname', 'like', '%'.$keyword.'%');
                } elseif ($keyword_srch == 'email') {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('users.email', 'like', '%'.$keyword.'%');
                } elseif ($keyword_srch == 'mobile') {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('users.mobile_number', 'like', '%'.$keyword);
                } else {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')
                    ->where(function ($qry) use ($keyword) {
                        $qry->where('users.email', 'like', '%'.$keyword.'%')
                        ->orWhere(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
                        ->orWhere('users.mobile_number', 'like', '%'.$keyword.'%')
                        ->orWhere('users.id', $keyword);
                    });
                }
            } else {
                $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid');
            }
        } else {
            if ($request->input('keyword') != null) {
                $keyword = $request->input('keyword');
            }

            if ($keyword_srch != null) {
                if ($keyword_srch == 'uid') {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('users.id', $keyword)->where('btc_security_lv.document_verified', $type);
                } elseif ($keyword_srch == 'name') {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('users.fullname', 'like', '%'.$keyword.'%')->where('btc_security_lv.document_verified', $type);
                } elseif ($keyword_srch == 'email') {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('users.email', 'like', '%'.$keyword.'%')->where('btc_security_lv.document_verified', $type);
                } elseif ($keyword_srch == 'mobile') {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('users.mobile_number', 'like', '%'.$keyword)->where('btc_security_lv.document_verified', $type);
                } else {
                    $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')
                    ->where(function ($qry) use ($keyword) {
                        $qry->where('users.email', 'like', '%'.$keyword.'%')
                        ->orWhere(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
                        ->orWhere('users.mobile_number', 'like', '%'.$keyword.'%')
                        ->orWhere('users.id', $keyword);
                    });
                }
            } else {
                $securitys = DB::table('users')->leftJoin('btc_security_lv', 'users.id', '=', 'btc_security_lv.uid')->where('btc_security_lv.document_verified', $type);
            }
        }

        $securitys = $securitys->orderBy('btc_security_lv.document_verified', 'desc')->paginate(30);

        $securitys->withPath('/admin/document_list/'.$type);

        $views->type = $type;
        $views->securitys = $securitys;
        $views->datetime = $datetime;

        return $views;
    }

    public function document_agree(Request $request)
    {
        $id = $request->temp_user_id;

        info($id);

        return 0;
    }

    public function document_reject(Request $request)
    {
        $id = $request->temp_user_id;
        $reason = $request->document_reject;

        info($id);
        info($reason);

        return 0;
    }

    public function market_edit()
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $views = view('admin.setting.market');

        $trademarket = DB::table('btc_settings')->where('id', session('market_type'))->first();

        $views->trademarket = $trademarket;
        $views->datetime = $datetime;

        return $views;
    }

    public function market_update(Request $request)
    {
        $market = DB::table('btc_settings')->where('id', session('market_type'))->first();

        $path = $market->service_icon;
        $path2 = $market->logo;

        if ($request->hasFile('service_icon')) {
            if ($request->file('service_icon')->isValid()) {
                $path = $request->service_icon->store('public/image/homepage/');
                $path = str_replace("public/image/homepage/", "", $path);

                $img_path = '../storage/app/public/image/homepage/'.$market->service_icon;

                if (File::exists($img_path)) {
                    File::delete($img_path);
                }
            }
        }

        if ($request->hasFile('logo')) {
            if ($request->file('logo')->isValid()) {
                $path2 = $request->logo->store('public/image/homepage/');
                $path2 = str_replace("public/image/homepage/", "", $path2);

                $img_path = '../storage/app/public/image/homepage/'.$market->logo;

                if (File::exists($img_path)) {
                    File::delete($img_path);
                }
            }
        }

        DB::table('btc_settings')->where('id', session('market_type'))->update([
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

        LoginTrace::Activity('거래소 정보 변경');

        return redirect()->back();
    }

    public function fee_edit()
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $coins = DB::table('btc_coins')->where('active', 1)->where('symbol', '<>', 'USD')->get();
        $settings = DB::table('btc_settings')->where('id', 1)->select('buy_comission')->first();

        $views = view('admin.setting.fee');
        $views->datetime = $datetime;
        $views->coins = $coins;
        $views->settings = $settings;

        return $views;
    }

    public function fee_update(Request $request)
    {
        $trade_fee = $request->trade_fee;

        DB::table('btc_settings')->where('id', 1)->update([
            'buy_comission' => $trade_fee,
            'sell_comission' => $trade_fee
        ]);

        $coins = DB::table('btc_coins')->where('active', 1)->where('symbol', '<>', 'USD')->get();
        foreach ($coins as $coin) {
            $fee = $request->{"send_fee_$coin->id"};
            DB::table('btc_coins')->where('id', $coin->id)->update(['send_fee' => $fee]);
        }

        LoginTrace::Activity('수수료 설정 변경');

        return redirect()->back();
    }

    public function recommender_edit()
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $settings = DB::table('btc_settings')->where('id', 1)->select('recommender_yn', 'recommender_point')->first();

        $views = view('admin.setting.recommender');
        $views->datetime = $datetime;
        $views->settings = $settings;

        return $views;
    }

    public function recommender_update(Request $request)
    {
        $recommender_yn = $request->recommender_yn;
        $recommender_point = $request->recommender_point;

        DB::table('btc_settings')->where('id', 1)->update([
            'recommender_yn' => $recommender_yn,
            'recommender_point' => $recommender_point
        ]);

        LoginTrace::Activity('추천인 설정 변경('.$recommender_point.' USDC');

        return redirect()->back();
    }

    public function event_list()
    {
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

    public function event_create()
    {
        $views = view('admin.event.event_create');
        return $views;
    }

    public function event_store(Request $request)
    {
        $storage_save_path = 'public/image/event';

        $path1 = null;
        $path2 = null;
        $path3 = null;
        $path4 = null;
        $path5 = null;

        if ($request->hasFile('file1')) {
            if ($request->file('file1')->isValid()) {
                $path1 = str_replace($storage_save_path.'/', "", $request->file1->store($storage_save_path));
            }
        }

        if ($request->hasFile('file2')) {
            if ($request->file('file2')->isValid()) {
                $path2 = str_replace($storage_save_path.'/', "", $request->file2->store($storage_save_path));
            }
        }

        if ($request->hasFile('file3')) {
            if ($request->file('file3')->isValid()) {
                $path3 = str_replace($storage_save_path.'/', "", $request->file3->store($storage_save_path));
            }
        }

        if ($request->hasFile('file4')) {
            if ($request->file('file4')->isValid()) {
                $path4 = str_replace($storage_save_path.'/', "", $request->file4->store($storage_save_path));
            }
        }

        if ($request->hasFile('file5')) {
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

    public function event_edit(Request $request, $id)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $event = DB::connection('mysql_sub')->table('btc_events')->select('*')->where('id', $id)->first();

        $views = view('admin.event.event_edit');
        $views->event = $event;
        $views->datetime = $datetime;

        return $views;
    }

    public function event_update(Request $request, $id)
    {
        $storage_save_path = 'public/image/event';

        $event = DB::connection('mysql_sub')->table('btc_events')->select('*')->where('id', $id)->first();
        $path1 = $event->image_url;
        $path2 = $event->image_mobile_url;
        $path3 = $event->image1;
        $path4 = $event->image2;
        $path5 = $event->image3;

        if ($request->hasFile('file1')) {
            if ($request->file('file1')->isValid()) {
                $old_path1 = $storage_save_path.'/'.$path1;
                if (Storage::exists($old_path1)) {
                    Storage::delete($old_path1);
                }
                $path1 = str_replace($storage_save_path.'/', "", $request->file1->store($storage_save_path));
            }
        }

        if ($request->hasFile('file2')) {
            if ($request->file('file2')->isValid()) {
                $old_path2 = $storage_save_path.'/'.$path2;
                if (Storage::exists($old_path2)) {
                    Storage::delete($old_path2);
                }
                $path2 = str_replace($storage_save_path.'/', "", $request->file2->store($storage_save_path));
            }
        }

        if ($request->hasFile('file3')) {
            if ($request->file('file3')->isValid()) {
                $old_path3 = $storage_save_path.'/'.$path3;
                if (Storage::exists($old_path3)) {
                    Storage::delete($old_path3);
                }
                $path3 = str_replace($storage_save_path.'/', "", $request->file3->store($storage_save_path));
            }
        }

        if ($request->hasFile('file4')) {
            if ($request->file('file4')->isValid()) {
                $old_path4 = $storage_save_path.'/'.$path4;
                if (Storage::exists($old_path4)) {
                    Storage::delete($old_path4);
                }
                $path4 = str_replace($storage_save_path.'/', "", $request->file4->store($storage_save_path));
            }
        }

        if ($request->hasFile('file5')) {
            if ($request->file('file5')->isValid()) {
                $old_path5 = $storage_save_path.'/'.$path5;
                if (Storage::exists($old_path5)) {
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

    public function event_delete(Request $request, $id)
    {
        $storage_save_path = 'public/image/event';

        $event = DB::connection('mysql_sub')->table('btc_events')->select('*')->where('id', $id)->first();
        $path1 = $event->image_url;
        $path2 = $event->image_mobile_url;
        $path3 = $event->image1;
        $path4 = $event->image2;
        $path5 = $event->image3;

        $delete_path1 = $storage_save_path.'/'.$path1;
        if (Storage::exists($delete_path1)) {
            Storage::delete($delete_path1);
        }

        $delete_path2 = $storage_save_path.'/'.$path2;
        if (Storage::exists($delete_path2)) {
            Storage::delete($delete_path2);
        }

        $delete_path3 = $storage_save_path.'/'.$path3;
        if (Storage::exists($delete_path3)) {
            Storage::delete($delete_path3);
        }

        $delete_path4 = $storage_save_path.'/'.$path4;
        if (Storage::exists($delete_path4)) {
            Storage::delete($delete_path4);
        }

        $delete_path5 = $storage_save_path.'/'.$path5;
        if (Storage::exists($delete_path5)) {
            Storage::delete($delete_path5);
        }

        DB::connection('mysql_sub')->table('btc_events')->where('id', $id)->delete();

        return Redirect::route('admin.event_list');
    }

    public function coin_lock_list()
    {
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

    public function coin_lock_action(Request $request, $id, $type)
    {
        // -1:종료중, 0:진행안함, 1:진행중
        if ($type == 'start') {
            DB::table('btc_lock_coins')->where('id', $id)->where('status', 0)->update(['status' => 1, 'updated_dt' => now()]);
            LoginTrace::Activity('코인락 진행중으로 상태변경');
        } elseif ($type == 'exit') {
            DB::table('btc_lock_coins')->where('id', $id)->where('status', 1)->update(['status' => -1, 'updated_dt' => now()]);
            LoginTrace::Activity('코인락 종료로 상태변경');
        } elseif ($type == 'cancel_exit') {
            DB::table('btc_lock_coins')->where('id', $id)->where('status', -1)->update(['status' => 1, 'updated_dt' => now()]);
            LoginTrace::Activity('코인락 종료를 진행으로 상태변경');
        }



        return Redirect::route('admin.coin_lock_list');
    }

    public function coin_lock_create()
    {
        $available_coins = DB::table('btc_coins')
            ->select('*')
            ->where('active', 1)
            ->where('cointype', '<>', 'cash')
            ->whereNotIn('api', function ($query) {
                $query->select('coin')->from('btc_lock_coins');
            })
            ->get();

        $views = view('admin.coin_lock.coin_lock_create');
        $views->available_coins = $available_coins;



        return $views;
    }

    public function coin_lock_store(Request $request)
    {
        $coin = $request->input('coin');
        $ratio = $request->input('ratio');

        if ($ratio == '') {
        } elseif (strlen($ratio) > 6) {
        } elseif (!is_numeric($ratio)) {
        } else {
            $float_ratio = (float) $ratio;
            if ($float_ratio <= 0) {
            } elseif ($float_ratio > 1) {
            } else {
                $coin_count = DB::table('btc_lock_coins')->where('coin', $coin)->count();
                if ($coin_count > 0) {
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

    public function coin_lock_edit(Request $request, $id)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $coin = DB::table('btc_lock_coins')->where('id', $id)->first();

        $views = view('admin.coin_lock.coin_lock_edit');
        $views->coin = $coin;
        $views->datetime = $datetime;

        return $views;
    }

    public function coin_lock_update(Request $request, $id)
    {
        $ratio = $request->input('ratio');

        if ($ratio == '') {
        } elseif (strlen($ratio) > 6) {
        } elseif (!is_numeric($ratio)) {
        } else {
            $float_ratio = (float) $ratio;
            if ($float_ratio <= 0) {
            } elseif ($float_ratio > 1) {
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

    public function coin_tr_list(Request $request)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $keyword_srch = $request->input('keyword_srch');
        $keyword = $request->input('keyword');



        if ($keyword_srch != null) {
            $keyword = $keyword != null ? $keyword : '';
            if ($keyword_srch == 'uid') {
                $transactions = DB::table('btc_transaction')
                ->join('users', 'btc_transaction.account', '=', 'users.username')
                ->select('btc_transaction.*', 'users.id as uid', 'users.email', 'users.mobile_number', 'users.fullname')
                ->where('btc_transaction.category', '<>', 'trade')
                ->where('users.id', $keyword)
                ->orderBy('btc_transaction.id', 'desc');
            } elseif ($keyword_srch == 'email') {
                $transactions = DB::table('btc_transaction')
                ->join('users', 'btc_transaction.account', '=', 'users.username')
                ->select('btc_transaction.*', 'users.id as uid', 'users.email', 'users.mobile_number', 'users.fullname')
                ->where('btc_transaction.category', '<>', 'trade')
                ->where('users.email', 'like', '%'.$keyword.'%')
                ->orderBy('btc_transaction.id', 'desc');
            } elseif ($keyword_srch == 'mobile_number') {
                $transactions = DB::table('btc_transaction')
                ->join('users', 'btc_transaction.account', '=', 'users.username')
                ->select('btc_transaction.*', 'users.id as uid', 'users.email', 'users.mobile_number', 'users.fullname')
                ->where('btc_transaction.category', '<>', 'trade')
                ->where('users.mobile_number', 'like', '%'.$keyword.'%')
                ->orderBy('btc_transaction.id', 'desc');
            } elseif ($keyword_srch == 'fullname') {
                $transactions = DB::table('btc_transaction')
                ->join('users', 'btc_transaction.account', '=', 'users.username')
                ->select('btc_transaction.*', 'users.id as uid', 'users.email', 'users.mobile_number', 'users.fullname')
                ->where('btc_transaction.category', '<>', 'trade')
                ->where(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
                ->orderBy('btc_transaction.id', 'desc');
            } else {
                $transactions = DB::table('btc_transaction')
                ->join('users', 'btc_transaction.account', '=', 'users.username')
                ->select('btc_transaction.*', 'users.id as uid', 'users.email', 'users.mobile_number', 'users.fullname')
                ->where('btc_transaction.category', '<>', 'trade')
                ->where(function ($qry) use ($keyword) {
                    $qry->where('users.email', 'like', '%'.$keyword.'%')
                    ->orWhere(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
                    ->orWhere('users.mobile_number', 'like', '%'.$keyword.'%')
                    ->orWhere('users.id', $keyword)
                    ->orwhereRaw("LOWER(cointype) like LOWER('%$keyword%')");
                })
                ->orderBy('btc_transaction.id', 'desc');
            }
        } else {
            $transactions = DB::table('btc_transaction')
            ->join('users', 'btc_transaction.account', '=', 'users.username')
            ->select('btc_transaction.*', 'users.id as uid', 'users.email', 'users.mobile_number', 'users.fullname')
            ->where('btc_transaction.category', '<>', 'trade')
            ->orderBy('btc_transaction.id', 'desc');
        }

        $transactions_page = $transactions->paginate(20)->appends(request()->query());
        $transactions_page->withPath('coin_tr_list');

        if ($keyword_srch != null) {
            $transactions_page->appends(['keyword_srch' => $keyword_srch, 'keyword' => $keyword])->links();
        }

        $views = view('admin.coin_tr.coin_tr_list');
        $views->transactions = $transactions->get();
        $views->transactions_page = $transactions_page;
        $views->datetime = $datetime;

        return $views;
    }

    public function coin_tr_excel(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        return Excel::download(new CoinTrsExport($from, $to), '필립스입출금이력리스트_' .  date("Y-m-d") . '.xlsx');
    }

    public function airdrop_list()
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $today = date("Y-m-d H:i:s");
        $airdrops = DB::table('btc_airdrop')->select('*')->orderBy('status', 'desc')->get();
        foreach ($airdrops as $airdrop) {
            $id = $airdrop->id;
            $start_time = $airdrop->start_time;
            $end_time = $airdrop->end_time;

            if ($start_time <= $today && $today <= $end_time) {
                DB::table('btc_airdrop')->where('id', $id)->update(['status' => 1]);
            } elseif ($today > $end_time) {
                DB::table('btc_airdrop')->where('id', $id)->update(['status' => 0]);
            }
        }
        $airdrops = DB::table('btc_airdrop')->select('*')->orderBy('status', 'desc')->get();

        $views = view('admin.airdrop.airdrop_list');
        $views->airdrops = $airdrops;
        $views->datetime = $datetime;

        return $views;
    }

    public function airdrop_create()
    {
        $coins = DB::table('btc_coins')->where('active', 1)->where('cointype', '<>', 'cash')->orderBy('id', 'asc')->get();

        $views = view('admin.airdrop.airdrop_create');
        $views->coins = $coins;

        return $views;
    }

    public function airdrop_store(Request $request)
    {
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

    public function airdrop_edit(Request $request, $id)
    {
        $coins = DB::table('btc_coins')->where('active', 1)->where('cointype', '<>', 'cash')->orderBy('id', 'asc')->get();
        $airdrop = DB::table('btc_airdrop')->select('*')->where('id', $id)->first();

        $views = view('admin.airdrop.airdrop_edit');
        $views->coins = $coins;
        $views->airdrop = $airdrop;

        return $views;
    }

    public function airdrop_update(Request $request, $id)
    {
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

    public function p2p_list($type)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $today = date("Y-m-d H:i:s");
        if ($type == 6) {
            $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted', 0)->where('state', 'stop')->orderBy('id', 'desc')->get();
        } elseif ($type == 5) {
            $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted', 1)->orderBy('id', 'desc')->get();
        } else {
            $p2ps = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted', 0)->where('confirm', $type)->where('state', '<>', 'stop')->orderBy('id', 'desc')->get();
        }
        //$p2ps = DB::table('btc_p2p')->select('*')->orderBy('id','desc')->get();


        $views = view('admin.p2p.p2p_list');
        $views->p2ps = $p2ps;
        $views->datetime = $datetime;
        $views->types = $type;

        return $views;
    }

    public function p2p_confirm($id)
    {
        $confirm = DB::connection('mysql_sub')->table('btc_p2p')->select('confirm')->where('id', $id)->first();
        if ($confirm->confirm>0 && $confirm->confirm<4) {
            if ($confirm->confirm==3) {
                DB::connection('mysql_sub')->table('btc_p2p')
                ->where('id', $id)
                ->where('confirm', $confirm->confirm)
                ->update([
                        'confirm'=>$confirm->confirm+1,
                        'state'=>'complete',
                        'end' => now(),
                        'update_at'=>now()
                        ]);

                DB::connection('mysql_sub')->table('btc_p2p_user')
                ->where('pid', $id)
                ->update([
                        'end_day' => now(),
                        'update_at'=> now()
                        ]);
            } else {
                DB::connection('mysql_sub')->table('btc_p2p')
                ->where('id', $id)
                ->where('confirm', $confirm->confirm)
                ->update([
                        'confirm'=>$confirm->confirm+1,
                        'update_at'=>now()
                        ]);

                DB::connection('mysql_sub')->table('btc_p2p_user')
                ->where('pid', $id)
                ->update([
                        'update_at'=>now()
                        ]);
                return back();
            }
        }
        return back();
    }
    public function p2p_stop($id)
    {
        DB::connection('mysql_sub')->table('btc_p2p')
        ->where('id', $id)
        ->update([
                'state'=>'stop',
                'update_at'=>now()
                ]);
        return back();
    }
    public function p2p_detail($id)
    {
        $p2ps = DB::connection('mysql_sub')->table('btc_p2p_user')->where('pid', $id)->get();
        $table = DB::connection('mysql_sub')->table('btc_p2p')->where('id', $id)->first();
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

    public function rights_management_list(Request $request)
    {
        Log::info(Auth::guard('admin')->user()->level);

        $keyword_srch = $request->keyword_srch;

        $keyword = '';

        if ($request->keyword != null) {
            $keyword = $request->keyword;
        }

        if ($keyword_srch != null) {
            if ($keyword_srch == 'name') {
                $admins = DB::table('admin')->where('fullname', 'like', '%'.$keyword.'%')->orderBy('id', 'asc')->paginate(20);
            } elseif ($keyword_srch == 'email') {
                $admins = DB::table('admin')->where('email', 'like', '%'.$keyword.'%')->orderBy('id', 'asc')->paginate(20);
            } elseif ($keyword_srch == 'mobile') {
                $admins = DB::table('admin')->where('mobile_number', 'like', '%'.$keyword.'%')->orderBy('id', 'asc')->paginate(20);
            } else {
                $admins = DB::table('admin')->orderBy('id', 'asc')->paginate(20);
            }
        } else {
            $admins = DB::table('admin')->orderBy('id', 'asc')->paginate(20);
        }
        $admins->withPath('/admin/rights_management_list');

        $views = view('admin.setting.rights_management_list');

        $views->admins = $admins;

        return $views;
    }

    public function rights_management_create()
    {
        $views = view('admin.setting.rights_management_create');

        return $views;
    }

    public function rights_management_store(Request $request)
    {
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

    public function rights_management_edit(Request $request, $id)
    {
        $admin = DB::table('admin')->where('id', $id)->first();

        $views = view('admin.setting.rights_management_edit');

        $views->admin = $admin;



        return $views;
    }

    public function rights_management_update(Request $request, $id)
    {
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

    public function rights_management_password_edit(Request $request, $id)
    {
        $admin = DB::table('admin')->where('id', $id)->first();

        $views = view('admin.setting.rights_management_password_edit');

        $views->admin = $admin;

        return $views;
    }

    public function rights_management_password_update(Request $request, $id)
    {
        DB::table('admin')->where('id', $id)->update([
            'password' => Hash::make($request->password),
        ]);

        LoginTrace::Activity('관리자 비밀번호 변경');

        return Redirect::route('admin.rights_management_list');
    }

    public function rights_management_delete($id)
    {
        DB::table('admin')->where('id', $id)->delete();

        LoginTrace::Activity('관리자 계정 삭제');

        return Redirect::route('admin.rights_management_list');
    }

    //관리자 활동내역
    public function admin_activity(Request $request)
    {
        //

        $admin_activities = DB::connection('mysql_sub')->table('btc_admin_activity')->orderBy('id', 'desc')->paginate(20);

        $admin_activities->withPath('/admin/admin_activity');

        $views = view('admin.setting.admin_activity');

        $views->admin_activities = $admin_activities;

        return $views;
    }

    //NOTICE

    public function notice_list($country)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $notices = DB::connection('mysql_sub')->table('btc_notice_'.$country)->orderBy('id', 'desc')->paginate(20);

        $notices->withPath('/admin/notices');

        $views = view('admin.notice.notice_list');

        $views->notices = $notices;
        $views->country = $country;
        $views->datetime = $datetime;

        return $views;
    }

    public function notice_edit($country, $id)
    {
        $notice = DB::connection('mysql_sub')->table('btc_notice_'.$country)->where('id', $id)->first();

        $views = view('admin.notice.notice_edit');

        $views->notice = $notice;
        $views->country = $country;
        $views->id = $id;

        return $views;
    }

    public function notice_create($country)
    {
        $views = view('admin.notice.notice_create');
        $views->country = $country;


        return $views;
    }


    public function notice_insert(Request $request)
    {
        $country = $request->country;
        $id = DB::connection('mysql_sub')->table('btc_notice_'.$country)->insertGetId([
            "title" => $request->title,
            "description" => $request->description,
            "created" => time(),
            "updated" => time(),
        ]);

        return redirect()->route('admin.notice_edit', ['country' => $country, 'id' => $id]);
    }

    public function notice_update(Request $request, $id)
    {
        $country = $request->input('country');

        DB::connection('mysql_sub')->table('btc_notice_'.$country)->where('id', $id)->update([
            "title" => $request->input('title'),
            "description" => $request->input('description'),
            "updated" => time(),
        ]);

        return redirect()->back();
    }

    public function notice_delete($country, $id)
    {
        DB::connection('mysql_sub')->table('btc_notice_'.$country)->where('id', $id)->delete();

        return redirect()->route('admin.notice_list', $country);
    }

    //FAQ

    public function faq_list($country, $types)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        if ($types == 0) {
            $faqs = DB::connection('mysql_sub')->table('btc_faq_'.$country)->orderBy('id', 'desc')->paginate(20);
        } else {
            $faqs = DB::connection('mysql_sub')->table('btc_faq_'.$country)->where('faq_type', $types)->orderBy('id', 'desc')->paginate(20);
        }

        $faqs->withPath('/admin/faqs');

        $views = view('admin.faq.faq_list');

        $views->faqs = $faqs;
        $views->country = $country;
        $views->types = $types;
        $views->datetime = $datetime;

        return $views;
    }

    public function faq_edit($country, $id)
    {
        $faq = DB::connection('mysql_sub')->table('btc_faq_'.$country)->where('id', $id)->first();

        $views = view('admin.faq.faq_edit');

        $views->faq = $faq;
        $views->country = $country;
        $views->id = $id;

        return $views;
    }

    public function faq_create($country)
    {
        $views = view('admin.faq.faq_create');
        $views->country = $country;


        return $views;
    }


    public function faq_insert(Request $request)
    {
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

    public function faq_update(Request $request, $id)
    {
        $country = $request->input('country');

        DB::connection('mysql_sub')->table('btc_faq_'.$country)->where('id', $id)->update([
            "question" => $request->title,
            "answer" => $request->description,
            "faq_type" => $request->faq_type,
            "updated" => time(),
        ]);

        return redirect()->back();
    }

    public function faq_delete($country, $id)
    {
        DB::connection('mysql_sub')->table('btc_faq_'.$country)->where('id', $id)->delete();

        return redirect()->route('admin.faq_list', [ 'country'=>$country, 'types'=>0 ]);
    }

    //QNA

    public function qna_list($country)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        //$qnas = DB::connection('mysql_sub')->table('btc_qna')->where('country',$country)->orderBy('id','desc')->paginate(20);
        $qnas = DB::table('users')
            ->leftJoin('btc_qna', 'btc_qna.createdby', '=', 'users.username')->where('btc_qna.country', $country)->orderBy('btc_qna.id', 'desc')->paginate(20);
        $qnas->withPath('/admin/qna/'.$country);


        $views = view('admin.qna.qna_list');

        $views->qnas = $qnas;
        $views->country = $country;
        $views->datetime = $datetime;

        return $views;
    }

    public function qna_answer_create($id)
    {
        $qna = DB::connection('mysql_sub')->table('btc_qna')->where('id', $id)->first();

        $views = view('admin.qna.qna_answer_create');

        $views->qna = $qna;
        $views->id = $id;

        return $views;
    }

    public function qna_answer_edit($id)
    {
        $qna = DB::connection('mysql_sub')->table('btc_qna')->where('id', $id)->first();

        $qna_answer = DB::connection('mysql_sub')->table('btc_qna_comment')->where('qna_id', $id)->first();

        $views = view('admin.qna.qna_answer_edit');

        $views->qna = $qna;
        $views->qna_answer = $qna_answer;
        $views->id = $id;

        return $views;
    }


    public function qna_answer_insert(Request $request, $id)
    {
        DB::connection('mysql_sub')->table('btc_qna')->where('id', $id)->update([
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

    public function qna_answer_update(Request $request, $id)
    {
        DB::connection('mysql_sub')->table('btc_qna_comment')->where('qna_id', $id)->update([
            "description" => $request->description,
            "updated" => time(),
        ]);

        return redirect()->back();
    }

    public function qna_delete($country, $id)
    {
        DB::connection('mysql_sub')->table('btc_qna')->where('id', $id)->delete();
        DB::connection('mysql_sub')->table('btc_qna_comment')->where('qna_id', $id)->delete();

        return redirect()->route('admin.qna_list', $country);
    }

    public function trade_history(Request $request)
    {
        $cointype = $request->cointype;
        $srch = $request->srch;

        if ($request->srch == null || !isset($request->srch)) {
            $srch = '';
        }

        if ($request->cointype == null || !isset($request->cointype)) {
            $cointype = 'all';
        }

        if ($cointype != null) {
            $srch = $srch != null ? $srch : '';
            if ($cointype == 'uid') {
                $trade_historys = DB::table('btc_trades_COIN_btc')
                ->join('users AS buyer', 'btc_trades_COIN_btc.buyer_uid', '=', 'buyer.id')
                ->join('users AS seller', 'btc_trades_COIN_btc.seller_uid', '=', 'seller.id')
                ->select('btc_trades_COIN_btc.*', 'buyer.fullname AS buyer_fullname', 'seller.fullname AS seller_fullname')
                ->where('buyer.id', $srch)->orwhere('seller.id')
                ->orderBy('btc_trades_COIN_btc.id', 'desc')->paginate(30)->appends(request()->query());
            } elseif ($cointype == 'email') {
                $trade_historys = DB::table('btc_trades_COIN_btc')
                ->join('users AS buyer', 'btc_trades_COIN_btc.buyer_uid', '=', 'buyer.id')
                ->join('users AS seller', 'btc_trades_COIN_btc.seller_uid', '=', 'seller.id')
                ->select('btc_trades_COIN_btc.*', 'buyer.fullname AS buyer_fullname', 'seller.fullname AS seller_fullname')
                ->where('buyer.email', 'like', '%'.$srch.'%')->orwhere('seller.email', 'like', '%'.$srch.'%')
                ->orderBy('btc_trades_COIN_btc.id', 'desc')->paginate(30)->appends(request()->query());
            } elseif ($cointype == 'mobile_number') {
                $trade_historys = DB::table('btc_trades_COIN_btc')
                ->join('users AS buyer', 'btc_trades_COIN_btc.buyer_uid', '=', 'buyer.id')
                ->join('users AS seller', 'btc_trades_COIN_btc.seller_uid', '=', 'seller.id')
                ->select('btc_trades_COIN_btc.*', 'buyer.fullname AS buyer_fullname', 'seller.fullname AS seller_fullname')
                ->where('buyer.mobile_number', 'like', '%'.$srch.'%')->orwhere('seller.mobile_number', 'like', '%'.$srch.'%')
                ->orderBy('btc_trades_COIN_btc.id', 'desc')->paginate(30)->appends(request()->query());
            } elseif ($cointype == 'fullname') {
                $trade_historys = DB::table('btc_trades_COIN_btc')
                ->join('users AS buyer', 'btc_trades_COIN_btc.buyer_uid', '=', 'buyer.id')
                ->join('users AS seller', 'btc_trades_COIN_btc.seller_uid', '=', 'seller.id')
                ->select('btc_trades_COIN_btc.*', 'buyer.fullname AS buyer_fullname', 'seller.fullname AS seller_fullname')
                ->where(DB::raw("REPLACE(buyer.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$srch.'%'))
                ->orwhere(DB::raw("REPLACE(seller.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$srch.'%'))
                ->orderBy('btc_trades_COIN_btc.id', 'desc')->paginate(30)->appends(request()->query());
            } else {
                $trade_historys = DB::table('btc_trades_COIN_btc')
                ->join('users AS buyer', 'btc_trades_COIN_btc.buyer_uid', '=', 'buyer.id')
                ->join('users AS seller', 'btc_trades_COIN_btc.seller_uid', '=', 'seller.id')
                ->select('btc_trades_COIN_btc.*', 'buyer.fullname AS buyer_fullname', 'seller.fullname AS seller_fullname')
                ->where(function ($qry) use ($srch) {
                    $qry->where('buyer.email', 'like', '%'.$srch.'%')
                    ->orWhere(DB::raw("REPLACE(buyer.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$srch.'%'))
                    ->orWhere('buyer.mobile_number', 'like', '%'.$srch.'%')
                    ->orWhere('buyer.id', $srch)
                    ->orwhereRaw("LOWER(btc_trades_COIN_btc.cointype) like LOWER('%$srch%')")
                    ->orwhere('seller.email', 'like', '%'.$srch.'%')
                    ->orWhere(DB::raw("REPLACE(seller.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$srch.'%'))
                    ->orWhere('seller.mobile_number', 'like', '%'.$srch.'%')
                    ->orWhere('seller.id', $srch)
                    ->orwhereRaw("LOWER(btc_trades_COIN_btc.cointype) like LOWER('%$srch%')");
                })
                ->orderBy('btc_trades_COIN_btc.id', 'desc')->paginate(30)->appends(request()->query());
            }
        } else {
            $trade_historys = DB::table('btc_trades_COIN_btc')
            ->join('users AS buyer', 'btc_trades_COIN_btc.buyer_uid', '=', 'buyer.id')
            ->join('users AS seller', 'btc_trades_COIN_btc.seller_uid', '=', 'seller.id')
            ->select('btc_trades_COIN_btc.*', 'buyer.fullname AS buyer_fullname', 'seller.fullname AS seller_fullname')
            ->orderBy('btc_trades_COIN_btc.id', 'desc')->paginate(30)->appends(request()->query());
        }

        $coins = DB::table('btc_coins')->where('cointype', '<>', 'cash')->where('active', 1)->get();

        $trade_historys->withPath('/admin/trade/trade_history');

        $views = view('admin.trade.trade_history');

        $datetime = date("H시 i분 s초");

        $views->datetime = $datetime;
        $views->trade_historys = $trade_historys;
        $views->coins = $coins;

        return $views;
    }

    public function trade_history_excel(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        return Excel::download(new TradeHistorysExport($from, $to), '필립스거래이력리스트_' .  date("Y-m-d") . '.xlsx');
    }

    public function coin_out_history(Request $request, $types)
    {
        $category = $request->category;
        $srch = $request->srch;

        if ($request->srch == null || !isset($request->srch)) {
            $srch = '';
        }

        if ($request->category == null || !isset($request->category)) {
            $category = 'all';
        }

        if ($category != null) {
            $srch = $srch != null ? $srch : '';
            if ($category == 'uid') {
                $co_historys = DB::table('btc_coin_send_request')
                ->join('users', 'btc_coin_send_request.sender_userid', '=', 'users.username')
                ->select('btc_coin_send_request.*', 'users.id as uid', 'users.email', 'users.mobile_number', 'users.fullname')
                ->where('users.id', $srch)
                ->orderBy('btc_coin_send_request.id', 'desc');
            } elseif ($category == 'email') {
                $co_historys = DB::table('btc_coin_send_request')
                ->join('users', 'btc_coin_send_request.sender_userid', '=', 'users.username')
                ->select('btc_coin_send_request.*', 'users.id as uid', 'users.email', 'users.mobile_number', 'users.fullname')
                ->where('users.email', 'like', '%'.$srch.'%')
                ->orderBy('btc_coin_send_request.id', 'desc');
            } elseif ($category == 'mobile_number') {
                $co_historys = DB::table('btc_coin_send_request')
                ->join('users', 'btc_coin_send_request.sender_userid', '=', 'users.username')
                ->select('btc_coin_send_request.*', 'users.id as uid', 'users.email', 'users.mobile_number', 'users.fullname')
                ->where('users.mobile_number', 'like', '%'.$srch.'%')
                ->orderBy('btc_coin_send_request.id', 'desc');
            } elseif ($category == 'fullname') {
                $co_historys = DB::table('btc_coin_send_request')
                ->join('users', 'btc_coin_send_request.sender_userid', '=', 'users.username')
                ->select('btc_coin_send_request.*', 'users.id as uid', 'users.email', 'users.mobile_number', 'users.fullname')
                ->where(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$srch.'%'))
                ->orderBy('btc_coin_send_request.id', 'desc');
            } else {
                $co_historys = DB::table('btc_coin_send_request')
                ->join('users', 'btc_coin_send_request.sender_userid', '=', 'users.username')
                ->select('btc_coin_send_request.*', 'users.id as uid', 'users.email', 'users.mobile_number', 'users.fullname')
                ->where(function ($qry) use ($srch) {
                    $qry->where('users.email', 'like', '%'.$srch.'%')
                    ->orWhere(DB::raw("REPLACE(users.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$srch.'%'))
                    ->orWhere('users.mobile_number', 'like', '%'.$srch.'%')
                    ->orWhere('users.id', $srch)
                    ->orwhereRaw("LOWER(cointype) like LOWER('%$srch%')");
                })
                ->orderBy('btc_coin_send_request.id', 'desc');
            }
        } else {
            $co_historys = DB::table('btc_coin_send_request')
            ->join('users', 'btc_coin_send_request.sender_userid', '=', 'users.username')
            ->select('btc_coin_send_request.*', 'users.id as uid', 'users.email', 'users.mobile_number', 'users.fullname')
            ->orderBy('btc_coin_send_request.id', 'desc');
        }
        if ($types != 'all') {
            $co_historys = $co_historys->where('btc_coin_send_request.status', $types)->paginate(30)->appends(request()->query());
        } else {
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

    public function deposite_withdraw_list(Request $request)
    {
        $cointype = $request->cointype;
        $srch = $request->srch;

        if ($request->srch == null || !isset($request->srch)) {
            $srch = '';
        }

        if ($request->cointype == null || !isset($request->cointype)) {
            $cointype = 'all';
        }

        if ($cointype == 'all') {
            $receive_transaction = DB::table("btc_transaction")->Join('users', 'btc_transaction.account', '=', 'users.username')
            ->where(function ($qry) use ($srch) {
                $qry->where('btc_transaction.account', 'like', '%'.$srch.'%')->orwhere('users.fullname', 'like', '%'.$srch.'%');
            })
            ->where("btc_transaction.category", "<>", "trade")
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

            $coin_io_transactions = DB::table("btc_coin_io")->Join('users', 'btc_coin_io.username', '=', 'users.username')
            ->where(function ($qry) use ($srch) {
                $qry->where('btc_coin_io.username', 'like', '%'.$srch.'%')->orwhere('users.fullname', 'like', '%'.$srch.'%');
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
            ->orderBy("updated", "desc")->paginate(30)->appends(request()->query());
        } else {
            $receive_transaction = DB::table("btc_transaction")->Join('users', 'btc_transaction.account', '=', 'users.username')
            ->where(function ($qry) use ($srch) {
                $qry->where('btc_transaction.account', 'like', '%'.$srch.'%')->orwhere('users.fullname', 'like', '%'.$srch.'%');
            })
            ->where("btc_transaction.category", "<>", "trade")->where('btc_transaction.cointype', $cointype)
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

            $coin_io_transactions = DB::table("btc_coin_io")->Join('users', 'btc_coin_io.username', '=', 'users.username')
            ->where(function ($qry) use ($srch) {
                $qry->where('btc_coin_io.username', 'like', '%'.$srch.'%')->orwhere('users.fullname', 'like', '%'.$srch.'%');
            })
            ->where('btc_coin_io.cointype', $cointype)
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
            $receive_transaction = $receive_transaction->unionAll($coin_io_transactions)->orderBy("updated", "desc")->paginate(30)->appends(request()->query());
        }

        $coins = DB::table('btc_coins')->where('cointype', '<>', 'cash')->where('active', 1)->get();

        $receive_transaction->withPath('/admin/deposite_withdraw_list');

        $views = view('admin.coin.deposite_withdraw_list');

        $views->coins = $coins;
        $views->lists = $receive_transaction;

        return $views;
    }

    public function term_service($country)
    {
        $setting = Settings::Settings();

        $term = DB::table('btc_term_service')->first();

        $views = view('admin.term.term_edit');

        $views->term = $term;
        $views->country = $country;

        return $views;
    }

    public function term_service_update(Request $request, $id)
    {
        $country = $request->country;
        $private_infor_term = $request->{'private_infor_term_'.$country};
        $use_term = $request->{'use_term_'.$country};

        DB::connection('mysql_sub')->table('btc_term_service')->where('id', $id)->update([
            "private_infor_term_".$country => $private_infor_term,
            "use_term_".$country => $use_term,
            "updated_at" => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back();
    }

    //관리자 ICO 페이지
    public function ico_list(Request $request)
    {
        $keyword_srch = $request->keyword_srch;

        $keyword = '';

        if ($request->keyword != null) {
            $keyword = $request->keyword;
        }

        if ($keyword_srch != null) {
            if ($keyword_srch == 'id') {
                $icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('id', 'like', '%'.$keyword.'%')->orderBy('id', 'asc')->paginate(20);
            } elseif ($keyword_srch == 'name') {
                $icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('ico_title', 'like', '%'.$keyword.'%')->orderBy('id', 'asc')->paginate(20);
            } elseif ($keyword_srch == 'symbol') {
                $icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('ico_symbol', 'like', '%'.$keyword.'%')->orderBy('id', 'asc')->paginate(20);
            } else {
                $icos = DB::connection('mysql_sub')->table('btc_ico_new')->orderBy('id', 'asc')->paginate(20);
            }
        } else {
            $icos = DB::connection('mysql_sub')->table('btc_ico_new')->orderBy('id', 'asc')->paginate(20);
        }
        $icos->withPath('/admin/ico_list');

        $views = view('admin.ico.ico_list');

        $views->icos = $icos;

        return $views;
    }

    public function ico_people_list(Request $request, $id)
    {
        $keyword = $request->keyword;

        $ico = DB::connection('mysql_sub')->table('btc_ico_new')->where('id', $id)->first();

        if ($keyword != null) {
            $ico_peoples = DB::connection('mysql_sub')->table('btc_ico_people')->where('pr_id', $id)->where('name', 'like', '%'.$keyword.'%')->orderBy('id', 'asc')->paginate(20);
        } else {
            $ico_peoples = DB::connection('mysql_sub')->table('btc_ico_people')->where('pr_id', $id)->orderBy('id', 'asc')->paginate(20);
        }
        $ico_peoples->withPath('/admin/ico_people_list');

        $views = view('admin.ico.ico_people_list');

        $views->ico = $ico;
        $views->ico_peoples = $ico_peoples;


        return $views;
    }

    public function ico_confirm(Request $request, $id)
    {
        $ico = DB::connection('mysql_sub')->table('btc_ico_new')->where('id', $id)->first();
        if (strtotime($ico->ico_from) <= time() && strtotime($ico->ico_to) > time()) {
            $ico_category = 1; //진행중
        } elseif (strtotime($ico->ico_from) > time()) {
            $ico_category = 2; //진행예정
        } elseif (strtotime($ico->ico_to) <= time()) {
            $ico_category = 3; //종료
        }

        DB::connection('mysql_sub')->table('btc_ico_new')->where('id', $id)->update([
            'active' => 1,
            'ico_category' => $ico_category,
        ]);
        if ($ico_category == 1) {
            LoginTrace::Activity($id.'번 ICO/IEO 진행중으로 상태변경');
        } elseif ($ico_category == 1) {
            LoginTrace::Activity($id.'번 ICO/IEO 진행예정으로 상태변경');
        } else {
            LoginTrace::Activity($id.'번 ICO/IEO 종료로 상태변경');
        }

        return Redirect::route('admin.ico_list');
    }

    public function ico_ban(Request $request, $id)
    {
        $reject = $request->reject;
        DB::connection('mysql_sub')->table('btc_ico_new')->where('id', $id)->update([
            'active' => 0,
            'ico_category' => 5,
            'reject' => $reject,
        ]);

        LoginTrace::Activity($id.'번 ICO/IEO 거부');

        return Redirect::route('admin.ico_list');
    }
    public function popup_list($country)
    {
        $views = view('admin.popup.list');

        $popups = DB::connection('mysql_sub')->table('btc_popup_'.$country)->paginate(15);

        $popups->withPath('/admin/popup/list/'.$country);

        $datetime = date("H시 i분 s초");
        $views->country = $country;
        $views->popups = $popups;
        $views->datetime = $datetime;

        return $views;
    }

    public function popup_create($country)
    {
        $views = view('admin.popup.create');
        $views->country = $country;
        return $views;
    }

    public function popup_insert(Request $request)
    {
        $country = $request->country;

        $store_img_path = 'public/image/popup';
        $pc_path = null;
        $mb_path = null;


        if ($file = $request->file('pc_img')) {
            if ($file->isValid()) {
                $pc_path = $file->store($store_img_path.'/');
                $pc_path = str_replace($store_img_path.'/', "", $pc_path);
            }
        }

        if ($file = $request->file('mb_img')) {
            if ($file->isValid()) {
                $mb_path = $file->store($store_img_path.'/');
                $mb_path = str_replace($store_img_path.'/', "", $mb_path);
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
        LoginTrace::Activity($country.'나라의 '.$id.'번 팝업 생성 ('.$request->title.')');
        return redirect()->route('admin.popup_list', $country);
    }

    public function popup_edit($id, $country)
    {
        $views = view('admin.popup.edit');

        $popup = DB::connection('mysql_sub')->table('btc_popup_'.$country)->where('id', $id)->first();

        $views->popup = $popup;
        $views->country = $country;
        return $views;
    }

    public function popup_update(Request $request, $id)
    {
        $country = $request->country;

        $store_img_path = 'public/image/popup';

        $popup = DB::connection('mysql_sub')->table('btc_popup_'.$country)->where('id', $id)->first();

        $pc_path = $popup->pc_img;
        $mb_path = $popup->mb_img;


        if ($file = $request->file('pc_img')) {
            if (isset($file)) {
                if ($file->isValid()) {
                    $pc_path = $file->store($store_img_path.'/');
                    $pc_path = str_replace($store_img_path.'/', "", $pc_path);

                    $img_path = '../storage/app/public/image/popup/'.$popup->pc_img;

                    if (File::exists($img_path)) {
                        File::delete($img_path);
                    }
                }
            }
        }

        if ($file = $request->file('mb_img')) {
            if (isset($file)) {
                if ($file->isValid()) {
                    $mb_path = $file->store($store_img_path.'/');
                    $mb_path = str_replace($store_img_path.'/', "", $mb_path);

                    $img_path = '../storage/app/public/image/popup/'.$popup->mb_img;

                    if (File::exists($img_path)) {
                        File::delete($img_path);
                    }
                }
            }
        }


        DB::connection('mysql_sub')->table('btc_popup_'.$country)->where('id', $id)->update([
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
        LoginTrace::Activity($country.'나라의 '.$id.'번 팝업 수정 ('.$request->title.')');
        return redirect()->route('admin.popup_list', $country);
    }

    public function popup_delete($id, $country)
    {
        $popup = DB::connection('mysql_sub')->table('btc_popup_'.$country)->where('id', $id)->first();

        $img_path = '../storage/app/public/image/popup/'.$popup->pc_img;

        if (File::exists($img_path)) {
            File::delete($img_path);
        }

        $img_path = '../storage/app/public/image/popup/'.$popup->mb_img;

        if (File::exists($img_path)) {
            File::delete($img_path);
        }

        DB::connection('mysql_sub')->table('btc_popup_'.$country)->where('id', $id)->delete();

        LoginTrace::Activity($country.'나라의 '.$id.'번 팝업 삭제');

        return redirect()->route('admin.popup_list', $country);
    }

    public function auto_setting(Request $request)
    {
        $views = view('admin.trade.auto_setting');

        $auto_settings = DB::table('btc_auto_setting')->get();

        $views->auto_settings = $auto_settings;

        return $views;
    }

    public function auto_setting_update(Request $request, $id, $switch)
    {
        DB::table('btc_auto_setting')->where('id', $id)->update([
            'switch' => $switch
        ]);
        if ($switch == 0) {
            LoginTrace::Activity($id.'번 자동거래 활성화');
        } else {
            LoginTrace::Activity($id.'번 자동거래 비활성화');
        }
        return redirect()->route('admin.auto_setting');
    }

    public function auto_bot_edit($id)
    {
        $views = view('admin.trade.auto_bot_edit');

        $auto = DB::table('btc_auto_setting')->where('id', $id)->first();

        $views->auto = $auto;

        return $views;
    }

    public function auto_bot_update(Request $request, $id)
    {
        $time_min = $request->time_min;
        $time_max = $request->time_max;
        $amt_min = $request->amt_min;
        $amt_max = $request->amt_max;
        $amt_decimal = $request->amt_decimal;
        $range_min = $request->range_min;
        $range_max = $request->range_max;


        DB::table('btc_auto_setting')->where('id', $id)->update([
            "time_min" => $time_min,
            "time_max" => $time_max,
            "amt_min" => $amt_min,
            "amt_max" => $amt_max,
            "amt_decimal" => $amt_decimal,
            "range_min" => $range_min,
            "range_max" => $range_max,
        ]);

        LoginTrace::Activity($id.'번 자동거래 상태변경');

        return redirect()->route('admin.auto_bot_edit', $id);
    }

    //동민추가
    public function new_trade_history(Request $request)
    {
        $cointype = $request->cointype;
        $markettype = $request->markettype;
        $srch = $request->srch;

        if ($request->srch == null || !isset($request->srch)) {
            $srch = '';
        }

        if ($request->cointype == null || !isset($request->cointype)) {
            $cointype = 'all';
        }

        if ($request->markettype == null || !isset($request->markettype)) {
            $markettype = 'all';
        }

        if ($markettype == 'all') {
            if ($cointype == 'all') {
                $trade_historys = DB::table('btc_ads_btc')
                                    ->where(function ($qry) use ($srch) {
                                        $qry->where('userid', 'like', '%'.$srch.'%')->orWhere('status', 'like', '%'.$srch.'%');
                                    })
                                    ->orderBy('id', 'desc')->paginate(30);
            } else {
                $trade_historys = DB::table('btc_ads_btc')
                                    ->where(function ($qry) use ($srch) {
                                        $qry->where('userid', 'like', '%'.$srch.'%')->orWhere('status', 'like', '%'.$srch.'%');
                                    })
                                    ->where('cointype', $cointype)
                                    ->orderBy('id', 'desc')->paginate(30);
            }
        } else {
            if ($cointype == 'all') {
                $trade_historys = DB::table('btc_ads_btc')
                                    ->where(function ($qry) use ($srch) {
                                        $qry->where('userid', 'like', '%'.$srch.'%')->orWhere('status', 'like', '%'.$srch.'%');
                                    })
                                    ->where('currency', $markettype)
                                    ->orderBy('id', 'desc')->paginate(30);
            } else {
                $trade_historys = DB::table('btc_ads_btc')
                                    ->where(function ($qry) use ($srch) {
                                        $qry->where('userid', 'like', '%'.$srch.'%')->orWhere('status', 'like', '%'.$srch.'%');
                                    })
                                    ->where('currency', $markettype)
                                    ->where('cointype', $cointype)
                                    ->orderBy('id', 'desc')->paginate(30);
            }
        }

        $coins = DB::table('btc_coins')->where('cointype', '<>', 'cash')->where('active', 1)->get();

        $trade_historys->withPath('/admin/trade/new_trade_history');

        $views = view('admin.trade.new_trade_history');

        $datetime = date("H시 i분 s초");

        $views->datetime = $datetime;
        $views->trade_historys = $trade_historys;
        $views->coins = $coins;

        return $views;
    }

    public function user_trace_list(Request $request)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        if ($request->srch == null || !isset($request->srch)) {
            $srch = '';
        }

        $keyword_srch = $request->input('keyword_srch');
        $keyword = $request->input('keyword');

        if ($keyword_srch != null) {
            $keyword = $keyword != null ? $keyword : '';
            if ($keyword_srch == 'uid') {
                $traces = DB::connection('mysql_sub')->table('btc_login_trace')->where('uid', 'like', '%'.$keyword.'%')->orderBy('id', 'desc');
            } elseif ($keyword_srch == 'email') {
                $traces = DB::connection('mysql_sub')->table('btc_login_trace')->where('email', 'like', '%'.$keyword.'%')->orderBy('id', 'desc');
            } elseif ($keyword_srch == 'mobile_number') {
                $traces = DB::connection('mysql_sub')->table('btc_login_trace')->where('mobile_number', 'like', '%'.$keyword.'%')->orderBy('id', 'desc');
            } elseif ($keyword_srch == 'fullname') {
                $traces = DB::connection('mysql_sub')
                    ->table('btc_login_trace')
                    ->where(DB::raw("REPLACE(fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'))
                    ->orderBy('id', 'desc');
            } else {
                $traces = DB::connection('mysql_sub')
                    ->table('btc_login_trace')
                    ->where(function ($qry) use ($keyword) {
                        $qry->where('uid', 'like', '%'.$keyword.'%')
                        ->orWhere('email', 'like', '%'.$keyword.'%')
                        ->orWhere('mobile_number', 'like', '%'.$keyword.'%')
                        ->orWhere(DB::raw("REPLACE(fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$keyword.'%'));
                    })
                    ->orderBy('id', 'desc');
            }
        } else {
            $traces = DB::connection('mysql_sub')->table('btc_login_trace')->orderBy('id', 'desc');
        }

        $traces = $traces->paginate(20)->appends(request()->query())->withPath('user_trace_list');

        $views = view('admin.user_trace.user_trace_list');
        $views->datetime = $datetime;
        $views->traces = $traces;
        $views->keyword_srch = $keyword_srch;

        return $views;
    }


    public function cash_list(Request $request)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $keyword_srch = $request->input('keyword_srch');

        $keyword = '';

        if ($request->input('keyword') != null) {
            $keyword = $request->input('keyword');
        }


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
            ->select('btc_krw_io.*', 'users.fullname')
            ->orwhere('users.id', $keyword)
            ->orwhere('users.fullname', 'like', '%'.$keyword.'%')
            ->orwhere('users.email', 'like', '%'.$keyword.'%')
            ->orwhere('users.mobile_number', 'like', '%'.$keyword.'%');
        }

        $views = view('admin.cash.cash_list');

        $krw_ios = $krw_ios->orderBy('btc_krw_io.id', 'desc')->paginate(30)->appends(request()->query())->withPath('cash_list');

        $views->krw_ios = $krw_ios;
        $views->datetime = $datetime;

        return $views;
    }

    public function company_list(Request $request)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $keyword_srch = $request->input('keyword_srch');

        $keyword = '';

        if ($request->input('keyword') != null) {
            $keyword = $request->input('keyword');
        }


        if ($keyword_srch != null) {
            if ($keyword_srch == 'uid') {
                $company_lists = DB::table('btc_payment_company')
                ->join('users', 'users.id', '=', 'btc_payment_company.uid')
                ->select('btc_payment_company.*')
                ->where('users.id', $keyword);
            } elseif ($keyword_srch == 'name') {
                $company_lists = DB::table('btc_payment_company')
                ->join('users', 'users.id', '=', 'btc_payment_company.uid')
                ->select('btc_payment_company.*')
                ->where('users.fullname', 'like', '%'.$keyword.'%');
            } elseif ($keyword_srch == 'email') {
                $company_lists = DB::table('btc_payment_company')
                ->join('users', 'users.id', '=', 'btc_payment_company.uid')
                ->select('btc_payment_company.*')
                ->where('users.email', 'like', '%'.$keyword.'%');
            } elseif ($keyword_srch == 'mobile') {
                $company_lists = DB::table('btc_payment_company')
                ->join('users', 'users.id', '=', 'btc_payment_company.uid')
                ->select('btc_payment_company.*')
                ->where('users.mobile_number', 'like', '%'.$keyword);
            }
        } else {
            $company_lists = DB::table('btc_payment_company')
            ->join('users', 'users.id', '=', 'btc_payment_company.uid')
            ->select('btc_payment_company.*')
            ->orwhere('users.id', $keyword)
            ->orwhere('users.fullname', 'like', '%'.$keyword.'%')
            ->orwhere('users.email', 'like', '%'.$keyword.'%')
            ->orwhere('users.mobile_number', 'like', '%'.$keyword.'%');
        }

        $views = view('admin.payment.company_list');

        $company_lists = $company_lists->orderBy('btc_payment_company.company_confirm', 'desc')->orderBy('btc_payment_company.id', 'desc')->paginate(30)->appends(request()->query())->withPath('company_list');

        $views->company_lists = $company_lists;
        $views->datetime = $datetime;

        return $views;
    }

    public function payment_calculate(Request $request)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $keyword_srch = $request->input('keyword_srch');

        $keyword = '';

        if ($request->input('keyword') != null) {
            $keyword = $request->input('keyword');
        }


        if ($keyword_srch != null) {
            if ($keyword_srch == 'uid') {
                $payment_lists = DB::table('btc_payment')
                ->join('users', 'users.id', '=', 'btc_payment.uid')
                ->join('btc_security_lv', 'btc_security_lv.uid', '=', 'btc_payment.uid')
                ->select(DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt)) AS calcul_month"), DB::raw('SUM(btc_payment.cash_price) AS calcul_cash'), 'btc_payment.status', 'btc_payment.seller_fullname', 'btc_payment.company_name', 'btc_payment.username', 'btc_security_lv.account_num', 'btc_security_lv.account_bank')
                ->where('users.id', $keyword)
                ->where(function ($query2) {
                    $query2->where('btc_payment.status', 'complete')->orwhere('btc_payment.status', 'calculate');
                })->groupBy('btc_payment.username', 'btc_payment.status', DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt))"), 'btc_payment.seller_fullname', 'btc_payment.company_name', 'btc_security_lv.account_num', 'btc_security_lv.account_bank');
            } elseif ($keyword_srch == 'name') {
                $payment_lists = DB::table('btc_payment')
                ->join('users', 'users.id', '=', 'btc_payment.uid')
                ->join('btc_security_lv', 'btc_security_lv.uid', '=', 'btc_payment.uid')
                ->select(DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt)) AS calcul_month"), DB::raw('SUM(btc_payment.cash_price) AS calcul_cash'), 'btc_payment.status', 'btc_payment.seller_fullname', 'btc_payment.company_name', 'btc_payment.username', 'btc_security_lv.account_num', 'btc_security_lv.account_bank')
                ->where('users.fullname', 'like', '%'.$keyword.'%')
                ->where(function ($query2) {
                    $query2->where('btc_payment.status', 'complete')->orwhere('btc_payment.status', 'calculate');
                })->groupBy('btc_payment.username', 'btc_payment.status', DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt))"), 'btc_payment.seller_fullname', 'btc_payment.company_name', 'btc_security_lv.account_num', 'btc_security_lv.account_bank');
            } elseif ($keyword_srch == 'email') {
                $payment_lists = DB::table('btc_payment')
                ->join('users', 'users.id', '=', 'btc_payment.uid')
                ->join('btc_security_lv', 'btc_security_lv.uid', '=', 'btc_payment.uid')
                ->select(DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt)) AS calcul_month"), DB::raw('SUM(btc_payment.cash_price) AS calcul_cash'), 'btc_payment.status', 'btc_payment.seller_fullname', 'btc_payment.company_name', 'btc_payment.username', 'btc_security_lv.account_num', 'btc_security_lv.account_bank')
                ->where('users.email', 'like', '%'.$keyword.'%')
                ->where(function ($query2) {
                    $query2->where('btc_payment.status', 'complete')->orwhere('btc_payment.status', 'calculate');
                })->groupBy('btc_payment.username', 'btc_payment.status', DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt))"), 'btc_payment.seller_fullname', 'btc_payment.company_name', 'btc_security_lv.account_num', 'btc_security_lv.account_bank');
            } elseif ($keyword_srch == 'mobile') {
                $payment_lists = DB::table('btc_payment')
                ->join('users', 'users.id', '=', 'btc_payment.uid')
                ->join('btc_security_lv', 'btc_security_lv.uid', '=', 'btc_payment.uid')
                ->select(DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt)) AS calcul_month"), DB::raw('SUM(btc_payment.cash_price) AS calcul_cash'), 'btc_payment.status', 'btc_payment.seller_fullname', 'btc_payment.company_name', 'btc_payment.username', 'btc_security_lv.account_num', 'btc_security_lv.account_bank')
                ->where('users.mobile_number', 'like', '%'.$keyword)
                ->where(function ($query2) {
                    $query2->where('btc_payment.status', 'complete')->orwhere('btc_payment.status', 'calculate');
                })->groupBy('btc_payment.username', 'btc_payment.status', DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt))"), 'btc_payment.seller_fullname', 'btc_payment.company_name', 'btc_security_lv.account_num', 'btc_security_lv.account_bank');
            }
        } else {
            $payment_lists = DB::table('btc_payment')
            ->join('users', 'users.id', '=', 'btc_payment.uid')
            ->join('btc_security_lv', 'btc_security_lv.uid', '=', 'btc_payment.uid')
            ->select(DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt)) AS calcul_month"), DB::raw('SUM(btc_payment.cash_price) AS calcul_cash'), 'btc_payment.status', 'btc_payment.seller_fullname', 'btc_payment.company_name', 'btc_payment.username', 'btc_security_lv.account_num', 'btc_security_lv.account_bank')
            ->where(function ($query) use ($keyword) {
                $query->orwhere('users.fullname', 'like', '%'.$keyword.'%')->orwhere('users.email', 'like', '%'.$keyword.'%')->orwhere('users.mobile_number', 'like', '%'.$keyword.'%');
            })->where(function ($query2) {
                $query2->where('btc_payment.status', 'complete')->orwhere('btc_payment.status', 'calculate');
            })->groupBy('btc_payment.username', 'btc_payment.status', DB::raw("CONCAT(YEAR(btc_payment.updated_dt),'-',MONTH(btc_payment.updated_dt))"), 'btc_payment.seller_fullname', 'btc_payment.company_name', 'btc_security_lv.account_num', 'btc_security_lv.account_bank');
        }

        $views = view('admin.payment.payment_calculate');

        $payment_lists = $payment_lists->orderBy('calcul_month', 'desc')->paginate(30)->appends(request()->query())->withPath('payment_calculate');

        $views->payment_lists = $payment_lists;
        $views->datetime = $datetime;

        return $views;
    }

    public function monthly_revenue_edit()
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $this_year = date('Y');
        $this_month = date('m');

        $views = view('admin.revenue.revenue_edit');

        $views->datetime = $datetime;
        $views->date = $this_year.'.'.$this_month;

        $this_month_exsit = DB::table('monthly_all_revenue')->where('date', $this_year.'.'.$this_month)->exists();
        
        if ($this_month_exsit) {
            $revenue = DB::table('monthly_all_revenue')->where('date', $this_year.'.'.$this_month)->first();

            $views->revenue = $revenue;

            return $views;
        } else {
            $revenue = DB::table('monthly_all_revenue')->orderBy('created_at', 'desc')->orderBy('write_yn', 'asc')->first();

            $views->revenue = $revenue;

            return $views->with('jsAlert', '수익률을 입력하지 않은 달이 있습니다.');
        }
    }

    public function monthly_revenue_update(Request $request)
    {
        $this_year = date('Y');
        $this_month = date('m');
        $this_month_exsit = DB::table('monthly_all_revenue')->where('date', $this_year.'.'.$this_month)->exists();

        if ($this_month_exsit) {
            return redirect()->route('admin.monthly_revenue_edit')->with('jsAlert', '이미 이번달 수익률을 등록하셨습니다');
        }

        if ((float)$request->revenue <= 0) {
            return redirect()->route('admin.monthly_revenue_edit')->with('jsAlert', '수익은 0보다 커야합니다');
        }

        $price_eth = DB::table('btc_coins')->where('api', 'eth')->first()->last_coinmarketcap_price_usd;

        if (empty($price_eth)) {
            return redirect()->route('admin.monthly_revenue_edit')->with('jsAlert', '코인마켓캡에서 정보를 불러오는 중 오류발생');
        }

        $users = DB::table('btc_users_addresses')->get();
        $data = array();
        $total_eth = 0;
        foreach ($users as $user) {
            $uid = $user->uid;
            if ($user->available_balance_tru != 0) {
                $tru_retention = bcdiv($user->available_balance_tru, '150000000', 8);
                $revenue = bcmul($request->revenue, $tru_retention, 8);
                $fee = bcmul($revenue, '0.1', 8);
                $return_invest = bcsub($revenue, $fee, 8);
                $return_eth = bcdiv($return_invest, $price_eth, 8);
            } else {
                $tru_retention = 0;
                $revenue = 0;
                $fee = 0;
                $return_invest = 0;
                $return_eth = 0;
            }

            array_push($data, array(
                'uid' => $uid,
                'date' => $request->date,
                'revenue' => $request->revenue,
                'coin_retention' => bcmul($tru_retention, '100', 8),
                'fee' => $fee,
                'return_invest' => $return_invest,
                'price_eth' => $price_eth,
                'return_eth' => $return_eth
            ));
            $total_eth = bcadd($total_eth, $return_eth, 8);
        }

        DB::table('monthly_all_revenue')->insert([
            "date" => $request->date,
            "revenue" => $request->revenue,
            "dividend" => $total_eth
        ]);

        DB::table('monthly_personal_revenue')->insert($data);

        DB::update('
        UPDATE btc_users_addresses b, monthly_personal_revenue m
        SET b.available_balance_eth = b.available_balance_eth + m.return_eth
        WHERE 1 = 1
            AND b.uid = m.uid
            AND m.date = :date
        ', [
            'date' => $request->date
        ]);

        DB::update('
        UPDATE btc_users_addresses b
        SET b.available_balance_eth = b.available_balance_eth - cast(:dividend as decimal(21,8))
        WHERE uid = 1
        ', [
            'dividend' => $total_eth
        ]);

        return redirect()->route('admin.monthly_revenue_edit');
    }

    public function monthly_revenue_store()
    {
        // 수익률 관리 insert 크론에서 실행
    }

    public function users_revenue_manage(Request $request)
    {
        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        $keyword = $request->keyword;
        $keyword_srch = $request->keyword_srch;

        if ($keyword_srch !== null) {
            if ($keyword_srch === 'uid') {
                $users = DB::table('monthly_personal_revenue')
                        ->leftJoin('users', 'monthly_personal_revenue.uid', '=', 'users.id')
                        ->where('uid', $keyword)
                        ->orderBy('monthly_personal_revenue.created_at', 'desc')
                        ->orderBy('monthly_personal_revenue.uid', 'asc')
                        ->paginate(40);
            } elseif ($keyword_srch === 'fullname') {
                $users = DB::table('monthly_personal_revenue')
                        ->leftJoin('users', 'monthly_personal_revenue.uid', '=', 'users.id')
                        ->where('fullname', 'like', '%'.$keyword.'%')
                        ->orderBy('monthly_personal_revenue.created_at', 'desc')
                        ->orderBy('monthly_personal_revenue.uid', 'asc')
                        ->paginate(40);
            } elseif ($keyword_srch === 'email') {
                $users = DB::table('monthly_personal_revenue')
                        ->leftJoin('users', 'monthly_personal_revenue.uid', '=', 'users.id')
                        ->where('email', 'like', '%'.$keyword.'%')
                        ->orderBy('monthly_personal_revenue.created_at', 'desc')
                        ->orderBy('monthly_personal_revenue.uid', 'asc')
                        ->paginate(40);
            }
        } else {
            $users = DB::table('monthly_personal_revenue')
                    ->leftJoin('users', 'monthly_personal_revenue.uid', '=', 'users.id')
                    ->orderBy('monthly_personal_revenue.created_at', 'desc')
                    ->orderBy('monthly_personal_revenue.uid', 'asc')
                    ->paginate(40);
        }

        $users->withPath('users_revenue_manage?keyword='.$keyword);

        $views = view('admin.revenue.users_revenue_manage');

        $views->datetime = $datetime;
        $views->keyword_srch = $keyword_srch;
        $views->users = $users;

        return $views;
    }

    public function user_revenue_excel(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        date_default_timezone_set("Asia/Seoul");
        $datetime = date("H시 i분 s초");

        return Excel::download(new UserRevenueExport($from, $to), '회원수익관리_' .  date("Y-m-d") . '.xlsx');
    }

    public function ico_edit()
    {
        $company = DB::table('btc_settings')->first();
        $views = view('admin.ico.ico_edit');

        $today = strtotime(date("Y-m-d H:i:s"));
        $ico_start_date = strtotime($company->ico_start_date);
        $ico_end_date = strtotime($company->ico_end_date);
        $tru_per_eth = $company->tru_per_eth;

        if ($ico_start_date <= $today && $today <= $ico_end_date) {
            $status = '진행중';
        } elseif ($today > $ico_end_date) {
            $status = '마감';
        } else {
            $status = '진행예정';
        }

        $views->ico_end_date = $company->ico_end_date;
        $views->ico_start_date = $company->ico_start_date;
        $views->tru_per_eth = $company->tru_per_eth;
        $views->status = $status;

        return $views;
    }

    public function ico_update(Request $request)
    {
        DB::table('btc_settings')->update([
            "ico_start_date" => $request->ico_start_date.' 00:00:00',
            "ico_end_date" => $request->ico_end_date.' 00:00:00'
        ]);

        return redirect()->back();
    }

    public function ico_qna_list()
    {
        $qnas = DB::table('ico_qna')->orderBy('created_at', 'desc')->paginate(20);

        $qnas->withPath('ico_qna_list');

        $views = view('admin.ico.ico_qna_list');

        $views->qnas = $qnas;

        return $views;
    }

    public function ico_qna_view($id)
    {
        $qna = DB::table('ico_qna')->where('id', $id)->first();

        $views = view('admin.ico.ico_qna_view');

        $views->qna = $qna;

        return $views;
    }

    public function ico_qna_store(Request $request)
    {
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            }

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }

            exit(0);
        }

        $name = $request->name;
        $email = $request->email;
        $contents = $request->contents;

        $status = DB::table('ico_qna')->insert([
            "name" => $name,
            "email" => $email,
            "contents" => $contents
        ]);

        return response()->json($status);
    }

    public function ico_main_data()
    {
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            }

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }

            exit(0);
        }

        $ico = DB::table('btc_settings')->first();

        $ico_start_date = strtotime($ico->ico_start_date);
        $ico_end_date = strtotime($ico->ico_end_date);
        $today = strtotime(date('Y-m-d H:i:s'));

        $start_date = new DateTime($ico->ico_start_date);
        $end_date = new DateTime($ico->ico_end_date);

        $diff = date_diff($start_date, $end_date);

        if ($ico_start_date <= $today && $today <= $ico_end_date) {
            $namun_time = $ico_end_date - $ico_start_date;
            $status = 1;
        } elseif ($today > $ico_end_date) {
            $namun_time = $today - $ico_end_date;
            $status = 2;
        } else {
            $namun_time = $ico_start_date - $today;
            $status = 0;
        }

        $response = array(
            "ico_start_date" => date("Y/m/d", $ico_start_date),
            "ico_end_date" => date("Y/m/d", $ico_end_date),
            "diff" => $diff,
            "d" => date("d", $namun_time),
            "h" => date("H", $namun_time),
            "i" => date("i", $namun_time),
            "s" => date("s", $namun_time),
            "status" => $status
        );

        return response()->json($response);
    }

    private function DateDiff($startDate, $endDate)
    {
        $date1 = new DateTime($startDate);
        $date2 = new DateTime($endDate);
    
        $gap = $date1->diff($date2);
        return $gap;
    }
    
    private function DateDisplay($y, $m, $d)
    {
        $gap ='Diff : ';
        if ($y > 0) {
            $gap.=$y."년 ";
        }
        if ($m > 0) {
            $gap.=$m."월 ";
        }
        $gap.=$d."일 ";
        return $gap;
    }
    
    private function time_diff($datetime1, $datetime2)
    {
        return date('U', strtotime($datetime2))-date('U', strtotime($datetime1));
    }
}
