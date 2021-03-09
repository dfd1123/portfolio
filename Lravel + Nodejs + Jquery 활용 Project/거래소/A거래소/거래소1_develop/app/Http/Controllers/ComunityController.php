<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Facades\App\Classes\File_store;

use Carbon;
use Auth;
use Secure;
use Hash;
use DB;
use File;
use DateTime;

class ComunityController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware('auth', ['except' => [
            'index', 'show'
        ]]);
        
        $this->middleware('comunity_verify', ['except' => [
            'index', 'show', 'destroy'
        ]]);

        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }


    public function index(Request $request)
    {
        $board_name = $request->board_name;
        $orderBy = $request->orderBy;
        $srch_filter = null;
        $srch = null;

        $board_lists = array();
        $re_board_lists = array();

        $views = view(session('theme').'.'.$this->device.'.comunity.'.'list');

        $status_check = DB::connection('mysql_sub')->table('comunity_board')->where('bo_table',strtolower($board_name))->first();

        if ($board_name != 'free') {
            if(isset($status_check->status)){
                if($status_check->status == 0){
                    $another_coin_board = DB::connection('mysql_sub')->table('comunity_board')->where('bo_table', '<>', 'free')->where('status',1)->first();
                    if(isset($another_coin_board->bo_table)){
                        return redirect('/comunity?board_name='.$another_coin_board->bo_table);
                    }else{
                        return redirect('/notice')->with('jsAlert', '현재 열린 코인게시판이 없습니다. 공지사항으로 이동합니다.');
                    }
                        
                }
            }else{
                return redirect('/notice')->with('jsAlert', '현재 존재하지 않는 게시판입니다. 공지사항으로 이동합니다.');
            }

            $comunity_lists = DB::connection('mysql_sub')->table('comunity_board')->where('bo_table', '<>', 'free')->where('status',1)->get();
            $views->comunity_lists = $comunity_lists;

            foreach ($comunity_lists as $comunity_list) {
                if ($comunity_list->bo_table == $board_name) {
                    $comunity = $comunity_list;
                    break;
                }
            }
            $views->comunity = $comunity;
        }else{
            
            if(isset($status_check->status)){
                if($status_check->status == 0){
                    return redirect('/notice')->with('jsAlert', '현재 닫힌 게시판입니다. 공지사항으로 이동합니다.');    
                }
            }else{
                return redirect('/notice')->with('jsAlert', '현재 닫힌 게시판입니다. 공지사항으로 이동합니다.');
            }
        }

        if (isset($request->filter)) {
            $srch_filter = $request->filter;
            if (isset($request->srch)) {
                $srch = $request->srch;
                if ($srch_filter == 'all') {
                    $boards = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))
                                ->where(function ($query) use ($srch) {
                                    $query->where('title', 'like', '%'.$srch.'%')
                                        ->orWhere('content', 'like', '%'.$srch.'%')
                                        ->orWhere('writer_nickname', 'like', '%'.$srch.'%');
                                });
                } else {
                    $boards = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))
                                ->where($srch_filter, 'like', '%'.$srch.'%');
                }
            } else {
                $srch = '';
                $boards = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'));
            }
        } else {
            $srch_filter = 'all';
            $boards = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'));
        }

        $board_cnt = $boards->count();



        if ($this->device == 'pc') {
            switch ($orderBy) {
                case 'latest':
                    $boards = $boards->orderBy('notice', 'desc')->orderBy('id', 'desc')->paginate(15);
                    break;
                case 'recomend':
                    $boards = $boards->orderBy('notice', 'desc')->orderBy('recomend', 'desc')->paginate(15);
                    break;
                case 'update':
                    $boards = $boards->orderBy('notice', 'desc')->orderBy('updated_at', 'desc')->paginate(15);
                    break;
                default:
                    $orderBy = '';
                    $boards = $boards->orderBy('notice', 'desc')->orderBy('id', 'desc')->paginate(15);
            }

            $boards->withPath('/comunity?board_name='.$board_name.'&orderBy='.$orderBy.'&filter='.$srch_filter.'&srch='.$srch);
        } else {
            switch ($orderBy) {
                case 'latest':
                    $boards = $boards->orderBy('notice', 'desc')->orderBy('id', 'desc')->limit(15)->get();
                    break;
                case 'recomend':
                    $boards = $boards->orderBy('notice', 'desc')->orderBy('recomend', 'desc')->limit(15)->get();
                    break;
                case 'update':
                    $boards = $boards->orderBy('notice', 'desc')->orderBy('updated_at', 'desc')->limit(15)->get();
                    break;
                default:
                    $boards = $boards->orderBy('notice', 'desc')->orderBy('id', 'desc')->limit(15)->get();
            }
        }

        //dd($orderBy);
        $today = new DateTime();
        $notice_lists = DB::connection('mysql_sub')->table('btc_notice_'.config('app.country'))->orderBy('created','desc')->limit(5)->get();

        foreach ($boards as $board) {
            if ($board->re_id == null) {
                array_push($board_lists, $board);
            } else {
                array_push($re_board_lists, $board);
            }
        }

        $views->boards = $boards;
        $views->board_cnt = $board_cnt;
        $views->board_lists = $board_lists;
        $views->re_board_lists = $re_board_lists;
        $views->notice_lists = $notice_lists;
        $views->srch_filter = $srch_filter;
        $views->orderBy = $orderBy;
        $views->srch = $srch;
        $views->board_name = $board_name;
        $views->today = $today;
        
        return $views;
    }

    public function show(Request $request, $id)
    {
        $board_name = $request->board_name;

        $views = view(session('theme').'.'.$this->device.'.comunity.'.'view');

        $board = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))->where('id', $id)->first();
        
        $comunity_admin = DB::connection('mysql_sub')
            ->table('comunity_admin')
            ->where('uid', Auth::id())
            ->where('active', 1)
            ->where(function ($q) use($board_name) {
                $q->whereNull('bo_table')->orWhere('bo_table', $board_name);
            })
            ->first();
        

        // 비밀글인지 체크
        if ($board->secret_key !== null) {
            // 해당 글 작성자가 아닌 경우에만 비밀번호 체크
            if (Auth::id() != $board->writer_id && Auth::id() != 202798 && Auth::id() != 5269 && $comunity_admin == null) {
                // POST 요청으로 오는 비밀번호 값을 체크
                if (!isset($request->secret_key)) {
                    return abort(403);
                }

                $hashed_key = hash("sha256", $request->secret_key);
                if ($hashed_key != $board->secret_key) {
                    return abort(403);
                }
            }
        }

        $before_board = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))->where('secret_key', null)->where('id', '<', $id)->orderBy('id', 'desc')->first();
        $after_board = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))->where('secret_key', null)->where('id', '>', $id)->orderBy('id', 'asc')->first();

        // 조회수 늘리기
        $this->hit_up($id, $board_name);
        
        if (Auth::check()) {
            $uid = Auth::id();
        } else {
            $uid = null;
        }
        
        $views->board_name = $board_name;
        $views->board = $board;
        $views->board_id = $id;
        $views->before_board = $before_board;
        $views->after_board = $after_board;
        $views->uid = $uid;
        $views->comunity_admin = $comunity_admin;

        return $views;
    }

    private function hit_up($id, $board_name)
    {
        if (!isset($_COOKIE["{$board_name}_{$id}"])) {
            DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))->where('id', $id)->increment('hit', 1);
            // time() + 86400 = 쿠키 유효 시간 설정해줌(하루)
            setcookie("{$board_name}_{$id}", "1", time() + 86400);
        }
    }

    public function create(Request $request)
    {
        $board_name = $request->board_name;
        $re_id = null;

        if (isset($request->re_id)) {
            $re_id = $request->re_id;
        }

        $comunity_admin = DB::connection('mysql_sub')
            ->table('comunity_admin')
            ->where('uid', Auth::id())
            ->where('active', 1)
            ->where(function ($q) use($board_name) {
                $q->whereNull('bo_table')->orWhere('bo_table', $board_name);
            })
            ->first();

        $views = view(session('theme').'.'.$this->device.'.comunity.'.'write');

        $views->board_name = $board_name;
        $views->re_id = $re_id;
        $views->comunity_admin = $comunity_admin;

        return $views;
    }

    public function store(Request $request)
    {
        $kind = $request->kind;

        switch ($kind) {
            case 'board':
                return $this->board_store($request);
                break;
            case 'comment':
                return $this->comment_store($request);
                break;
        }
    }

    private function board_store($request)
    {
        $board_name = $request->board_name;
        $re_id = null;
        $secret_key = null;
        
        /*
        if(Auth::user()->comunity_status === 0){
            if ($re_id == null) {
                return redirect('/comunity?board_name='.$board_name)->with('comunity_reject', '커뮤니티 사용이 금지되셨습니다.\n운영자에게 문의하세요.');
            } else {
                return redirect('/comunity/?board_name='.$board_name.'&re_id='.$re_id)->with('comunity_reject', '커뮤니티 사용이 금지되셨습니다.\n운영자에게 문의하세요.');
            }
        }
        */

        if (isset($request->re_id)) {
            $re_id = $request->re_id;
        }

        if (isset($request->secret_key)) {
            $secret_key = $request->secret_key;
        }

        if (isset($request->files)) {
            $files = File_store::File_store('comunity', $request->file('files'));
        }

        if (isset($request->notice)) {
            $notice = 1;
        } else {
            $notice = 0;
        }

        $user = Auth::user();
        if($user->comunity_status == 0){
            return redirect()->back()->with('jsAlert', '현재 커뮤니티 사용상태가 이용 중지입니다. 고객센터에 문의해주세요.');
        }
        
        $available = DB::table('btc_users_addresses')->where('uid',$user->id)->first();
        $coins = DB::table('btc_coins')
		->select('name','api','symbol','last_trade_price_krw',
		DB::raw("IFNULL((SELECT SUM(sell_COIN_amt) FROM btc_ads_btc WHERE uid = ".$user->id." AND status='OnProgress' AND sell_COIN_amt > 0 AND cointype = btc_coins.api),0) as trading_pending"))
		->where('active',1)->where('api','<>','krw')->get();
        $result_balance = 0;
        $limit_comunity_krw = 1000000;
		foreach($coins as $coin){
			$available_balance = bcadd($available->{"available_balance_".$coin->api},0,8);
			$trading_pending = bcmul($coin->trading_pending,(-1),8);
			$lock_pending = bcadd($available->{"pending_received_balance_".$coin->api},$coin->trading_pending,8);
            
            $real_balance_krw = bcmul(bcadd($available_balance,$lock_pending,8),$coin->last_trade_price_krw,8);
            
            $result_balance = bcadd($result_balance,$real_balance_krw,8);
        }
        
        if($user->comunity_count <= 0 && $result_balance < $limit_comunity_krw){
            return redirect()->back()->with('jsAlert', '현재 사용가능한 글쓰기 횟수는 0으로 사용가능한 글쓰기 횟수를 모두 소진하셨습니다. (총 순 매수 코인 보유량의 원화 가치가 100만원을 넘는다면 무제한으로 사용 가능합니다. *이벤트코인 무료코인 제외)');
        }

        if (gettype($files) == "array") {
            
            if($result_balance < $limit_comunity_krw){
                DB::table('users')->where('id',$user->id)->decrement('comunity_count',1);
            }
            $id = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))
                    ->insertGetId([
                        "re_id" => $re_id,
                        "writer_id" => $user->id,
                        "writer_nickname" => $user->nickname,
                        "title" => $request->title,
                        "content" => $request->content,
                        "files" => json_encode($files),
                        "secret_key" => $secret_key == null?null:hash("sha256", $request->secret_key),
                        "notice" => $notice,
                        "search_permit" => $request->search_permit,
                    ]);
        } elseif (gettype($files) == "string") {
            if($result_balance < $limit_comunity_krw){
                DB::table('users')->where('id',$user->id)->decrement('comunity_count',1);
            }
            $id = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))
                    ->insertGetId([
                        "re_id" => $re_id,
                        "writer_id" => $user->id,
                        "writer_nickname" => $user->nickname,
                        "title" => $request->title,
                        "content" => $request->content,
                        "secret_key" => $secret_key == null?null:hash("sha256", $request->secret_key),
                        "notice" => $notice,
                        "search_permit" => $request->search_permit,
                    ]);
        }


        if ($re_id == null) {
            if($result_balance < $limit_comunity_krw){
                return redirect('/comunity/'.$id.'?board_name='.$board_name)->with('jsAlert', '글쓰기 횟수가 1회 소진됩니다. 현재 남은 글쓰기 횟수는 '.($user->comunity_count - 1).'회 남았습니다.');
            }else{
                return redirect('/comunity/'.$id.'?board_name='.$board_name.'&re_id='.$re_id)->with('jsAlert', '게시물 등록이 완료되었습니다.');
            }
        } else {
            if($result_balance < $limit_comunity_krw){
                return redirect('/comunity/'.$id.'?board_name='.$board_name.'&re_id='.$re_id)->with('jsAlert', '글쓰기 횟수가 1회 소진됩니다. 현재 남은 글쓰기 횟수는 '.($user->comunity_count - 1).'회 남았습니다.');
            }else{
                return redirect('/comunity/'.$id.'?board_name='.$board_name.'&re_id='.$re_id)->with('jsAlert', '게시물 등록이 완료되었습니다.');
            }
        }
    }

    private function comment_store($request)
    {
        $board_name = $request->board_name;
        $user = Auth::user();
        if($user->comunity_status == 0){
            return redirect()->back()->with('jsAlert', '현재 커뮤니티 사용상태가 이용 중지입니다. 고객센터에 문의해주세요.');
        }
        
        $available = DB::table('btc_users_addresses')->where('uid',$user->id)->first();
        $coins = DB::table('btc_coins')
		->select('name','api','symbol','last_trade_price_krw',
		DB::raw("IFNULL((SELECT SUM(sell_COIN_amt) FROM btc_ads_btc WHERE uid = ".$user->id." AND status='OnProgress' AND sell_COIN_amt > 0 AND cointype = btc_coins.api),0) as trading_pending"))
		->where('active',1)->where('api','<>','krw')->get();
        $result_balance = 0;
        $limit_comunity_krw = 1000000;
		foreach($coins as $coin){
			$available_balance = bcadd($available->{"available_balance_".$coin->api},0,8);
			$trading_pending = bcmul($coin->trading_pending,(-1),8);
			$lock_pending = bcadd($available->{"pending_received_balance_".$coin->api},$coin->trading_pending,8);
            
            $real_balance_krw = bcmul(bcadd($available_balance,$lock_pending,8),$coin->last_trade_price_krw,8);
            
            $result_balance = bcadd($result_balance,$real_balance_krw,8);
        }
        
        if($user->comment_count <= 0 && $result_balance < $limit_comunity_krw){
            return redirect()->back()->with('jsAlert', '현재 사용가능한 글쓰기 횟수는 0으로 사용가능한 글쓰기 횟수를 모두 소진하셨습니다. (총 순 매수 코인 보유량의 원화 가치가 100만원을 넘는다면 무제한으로 사용 가능합니다. *이벤트코인 무료코인 제외)');
        }

        if (empty($request->comment_id)) {
            if($result_balance < $limit_comunity_krw){
                DB::table('users')->where('id',$user->id)->decrement('comment_count',1);
            }
            $id = DB::connection('mysql_sub')->table('comment')
                    ->insertGetId([
                        "writer_id" => $user->id,
                        "writer_nickname" => $user->nickname,
                        "board_table" => $board_name.'_board_'.config('app.country'),
                        "board_id" => $request->board_id,
                        "comment" => $request->comment,
                    ]);
        } else {
            if($result_balance < $limit_comunity_krw){
                DB::table('users')->where('id',$user->id)->decrement('comment_count',1);
            }
            $id = DB::connection('mysql_sub')->table('re_comment')
                    ->insertGetId([
                        "board_table" => $board_name.'_board_'.config('app.country'),
                        "board_id" => $request->board_id,
                        "comment_id" => $request->comment_id,
                        "writer_id" => $user->id,
                        "writer_nickname" => $user->nickname,
                        "comment" => $request->comment,
                    ]);
        }
        if($result_balance < $limit_comunity_krw){
            return redirect()->back()->with('jsAlert', '글쓰기 횟수가 1회 소진됩니다. 현재 남은 글쓰기 횟수는 '.($user->comment_count - 1).'회 남았습니다.');
        }else{
            return redirect()->back();
        }
        
    }

    public function edit(Request $request, $id)
    {
        $board_name = $request->board_name;
        $file1 = '';
        $file2 = '';

        $views = view(session('theme').'.'.$this->device.'.comunity.'.'edit');

        if (isset($request->re_id)) {
            $views->re_id = $request->re_id;
        } else {
            $views->re_id = '';
        }

        $board = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))->where('id', $id)->first();
        
        $comunity_admin = DB::connection('mysql_sub')
            ->table('comunity_admin')
            ->where('uid', Auth::id())
            ->where('active', 1)
            ->where(function ($q) use($board_name) {
                $q->whereNull('bo_table')->orWhere('bo_table', $board_name);
            })
            ->first();

        // 글작성자 체크
        if (Auth::id() != $board->writer_id) {
            return abort(403);
        }

        if ($board->files != null) {
            $files = json_decode($board->files);

            if (count($files) == 2) {
                $temp = explode('[[', $files[0]);
                $file1 = $temp[1];

                $temp = explode('[[', $files[1]);
                $file2 = $temp[1];
            } elseif (count($files) == 1) {
                $temp = explode('[[', $files[0]);
                $file1 = $temp[1];
            }
        }


        $views->board_name = $board_name;
        $views->board = $board;
        $views->file1 = $file1;
        $views->file2 = $file2;
        $views->comunity_admin = $comunity_admin;

        return $views;
    }

    public function update(Request $request, $id)
    {
        $kind = $request->kind;

        switch ($kind) {
            case 'board':
                return $this->board_update($request, $id);
                break;
            case 'comment':
                return $this->comment_update($request);
                break;
            case 're_comment':
                return $this->comment_update($request);
                break;
        }
    }

    private function board_update($request, $id)
    {
        $board_name = $request->board_name;
        $re_id = null;
        $secret_key = null;
        $file1_ch = $request->file1_ch;
        $file2_ch = $request->file2_ch;

        $board = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))->where('id', $id)->first();
        
        // 글작성자 체크
        if (Auth::id() != $board->writer_id) {
            return abort(403);
        }

        if (isset($request->re_id)) {
            $re_id = $request->re_id;
        }

        if (isset($request->secret_key)) {
            $secret_key = hash("sha256", $request->secret_key);
        } else {
            $secret_key = NULL;
        }
        
        if (isset($request->notice)) {
            $notice = 1;
        } else {
            $notice = 0;
        }

        if (isset($request->files)) {
            $files = File_store::File_store('comunity', $request->file('files'));

            $original_files = $board->files;
            $original_files = json_decode($original_files);

            if ($original_files != null) {
                $path = '../storage/app/public/file/comunity';
                foreach ($original_files as $key => $original_file) {
                    if ($key == 0) {
                        if ($file1_ch) {
                            if (File::exists($path.$original_file)) {
                                File::delete($path.$original_file);
                            }

                            if (count($files) != 2) {
                                $original_files[0] = $files[0];
                            }
                        }
                    } elseif ($key == 1) {
                        if ($file2_ch) {
                            if (File::exists($path.$original_file)) {
                                File::delete($path.$original_file);
                            }

                            if (count($files) != 2) {
                                $original_files[1] = $files[0];
                            }
                        }
                    }
                }

                if (count($files) == 2) {
                    $original_files = $files;
                }
            } else {
                $original_files = $files;
            }
        }

        if (gettype($files) == "array") {
            $user = Auth::user();

            $status = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))
                    ->where('id', $id)
                    ->update([
                        "re_id" => $re_id,
                        "writer_id" => $user->id,
                        "writer_nickname" => $user->nickname,
                        "title" => $request->title,
                        "content" => $request->content,
                        "files" => json_encode($original_files),
                        "secret_key" => $secret_key,
                        "search_permit" => $request->search_permit,
                        "notice" => $notice,
                        "updated_at" => now(),
                    ]);
        } elseif (gettype($files) == "string") {
            $user = Auth::user();

            $status = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))
                    ->where('id', $id)
                    ->update([
                        "re_id" => $re_id,
                        "writer_id" => $user->id,
                        "writer_nickname" => $user->nickname,
                        "title" => $request->title,
                        "content" => $request->content,
                        "secret_key" => $secret_key,
                        "search_permit" => $request->search_permit,
                        "notice" => $notice,
                        "updated_at" => now(),
                    ]);
        }

        if($status){
            $origin_images = File_store::getImages($board->content);
            $new_images = File_store::getImages($request->content);

            File_store::imageUpdate($origin_images, $new_images);
        }


        if ($re_id == null) {
            return redirect('/comunity/'.$id.'?board_name='.$board_name);
        } else {
            return redirect('/comunity/'.$id.'?board_name='.$board_name.'&re_id='.$re_id);
        }
    }

    private function comment_update($request)
    {
        $board_name = $request->board_name;
        $comment_id = $request->comment_id;

        DB::connection('mysql_sub')->table('comment')->where('id', $comment_id)->update([
            "comment" => $request->comment,
            "updated_at" => now(),
        ]);

        return redirect()->back();
    }

    private function re_comment_update($request)
    {
        $board_name = $request->board_name;
        $comment_id = $request->comment_id;

        DB::connection('mysql_sub')->table('re_comment')->where('id', $comment_id)->update([
            "comment" => $request->comment,
            "updated_at" => now(),
        ]);

        return redirect()->back();
    }

    public function destroy(Request $request, $id)
    {
        $kind = $request->kind;

        switch ($kind) {
            case 'board':
                return $this->board_delete($id, $request);
                break;
            case 'comment':
                return $this->comment_delete($id);
                break;
            case 're_comment':
                return $this->re_comment_delete($id);
                break;
        }
    }

    private function board_delete($id, $request)
    {
        $board_name = $request->board_name;
        $board = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))->where('id', $id)->first();

        $comunity_admin = DB::connection('mysql_sub')
            ->table('comunity_admin')
            ->where('uid', Auth::id())
            ->where('active', 1)
            ->where(function ($q) use($board_name) {
                $q->whereNull('bo_table')->orWhere('bo_table', $board_name);
            })
            ->first();

        // 글작성자 체크
        if (Auth::id() != $board->writer_id && Auth::id() != 202798 && Auth::id() != 5269 && $comunity_admin == null) {
            return abort(403);
        }

        $files = json_decode($board->files);

        $path = '../storage/app/public/file/comunity';
        if ($board->files != null) {
            foreach ($files as $file) {
                if (File::exists($path.$file)) {
                    File::delete($path.$file);
                }
            }
        }

        // 기존 이미지들 삭제
        $origin_images = File_store::getImages($board->content);

        $default_path = '../storage/app/public/image';

        foreach($origin_images as $origin_image){
            $img_path = $default_path.$origin_image;
            if(File::exists($img_path)) {
                File::delete($img_path);
            }
        }

        DB::connection('mysql_sub')->table('re_comment')->where('board_table', $board_name.'_board_'.config('app.country'))->where('board_id', $id)->delete();
        DB::connection('mysql_sub')->table('comment')->where('board_table', $board_name.'_board_'.config('app.country'))->where('board_id', $id)->delete();
        DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))->where('id', $id)->delete();

        return redirect('/comunity?board_name='.$board_name);
    }

    private function comment_delete($id)
    {
        DB::connection('mysql_sub')->table('re_comment')->where('comment_id', $id)->delete();
        DB::connection('mysql_sub')->table('comment')->where('id', $id)->delete();

        return redirect('/comunity?board_name='.$board_name);
    }

    private function re_comment_delete($id)
    {
        DB::connection('mysql_sub')->table('re_comment')->where('id', $id)->delete();

        return redirect('/comunity?board_name='.$board_name);
    }
}
