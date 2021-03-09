<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Comment;

use DB;
use Auth;
use Hash;

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
    }

    public function index(Request $request){
        $kind = $request->kind;

        switch($kind){
            case 'board_more':
                return $this->board_more($request);
                break;
            case 'comment':
                return $this->comment($request);
                break;
            case 'comment_more':
                return $this->comment_more($request);
                break;
        }
    }

    public function show(Request $request, $id){
        $kind = $request->kind;

        switch($kind){
            case 'comment':
                return $this->comment_load($id);
                break;
        }
    }

    public function store(Request $request){
        $kind = $request->kind;

        switch($kind){
            case 'comment':
                return $this->comment_store($request);
                break;
        }
    }

    public function update(Request $request, $id){
        $kind = $request->kind;

        switch($kind){
            case 'comment':
                return $this->comment_update($request, $id);
                break;
            case 'board_recomend':
                return $this->recomend($request, $id);
                break;
            case 'comment_recomend':
                return $this->comment_recomend($request);
                break;
            case 'comment_unrecomend':
                return $this->comment_unrecomend($request);
                break;
        }
    }

    public function destroy(Request $request, $id){
        $kind = $request->kind;

        switch($kind){
            case 'comment':
                return $this->comment_delete($id, $request);
                break;
            case 're_comment':
                return $this->re_comment_delete($id, $request);
                break;
        }
    }

    public function secret_key_confirm(Request $request){
        $id = $request->id;
        $secret_key_inp = hash("sha256", $request->secret_key);
        $board_name = $request->board_name;

        $comunity_admin = DB::connection('mysql_sub')
            ->table('comunity_admin')
            ->where('uid', Auth::id())
            ->where('active', 1)
            ->where(function ($q) use($board_name) {
                $q->whereNull('bo_table')->orWhere('bo_table', $board_name);
            })
            ->first();

        $secret_key = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))->where('id', $id)->value('secret_key');
        
        if(empty($comunity_admin)) {
            $status = ($secret_key_inp == $secret_key)?true:false;
        } else {
            $status = true;
        }
        
        $response = array(
            "status" => $status,
        );

        return response()->json($response);            
    }

    private function board_more($request){
        $board_name = $request->board_name;
        $offset = $request->offset;

        $boards = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))
                    ->orderBy('id','desc')
                    ->offset($offset)
                    ->limit(15)
                    ->get();

        $offset = $offset + count($boards);

        $response = array(
            "offset" => $offset,
            "boards" => $boards,
            "board_name" => $board_name,
        );

        return response()->json($response);

    }

    private function comment($request){
        $board_name = $request->board_name;
        $offset = 15;
        $orderBy = $request->order_by;

        if($orderBy === 'past'){
            $comments = Comment::where('board_table', $board_name.'_board_'.config('app.country'))
                    ->where('board_id', $request->board_id)
                    ->with('re_comments')
                    ->orderBy('comment.recomend', 'asc')
                    ->limit($offset)
                    ->get();
        }else{
            $comments = Comment::where('board_table', $board_name.'_board_'.config('app.country'))
                    ->where('board_id', $request->board_id)
                    ->with('re_comments')
                    ->orderBy('comment.'.$orderBy, 'desc')
                    ->limit($offset)
                    ->get();
        }

        $comment_cnt = Comment::where('board_table', $board_name.'_board_'.config('app.country'))
                        ->where('board_id', $request->board_id)
                        ->orderBy('comment.id', 'asc')
                        ->orderBy('comment.recomend', 'desc')
                        ->count();

        $auth = Auth::check();

        $comunity_admin = null;
        if($auth){
            $comunity_admin = DB::connection('mysql_sub')
                ->table('comunity_admin')
                ->where('uid', Auth::id())
                ->where('active', 1)
                ->where(function ($q) use($board_name) {
                    $q->whereNull('bo_table')->orWhere('bo_table', $board_name);
                })
                ->first();
            $uid = Auth::id();
        }else{
            $uid = -1;
        }

        $response = array(
            "comment_cnt" => $comment_cnt,
            "comments" => $comments,
            "offset" => $offset,
            "auth" => $auth,
            "uid" => $uid,
        );
        if(!empty($comunity_admin)) {
            $response['admin'] = true;
        }

        return response()->json($response);
        
    }

    private function comment_more($request){
        $board_name = $request->board_name;
        $id = $request->id;
        $offset = $request->offset;
        $orderBy = $request->order_by;

        $comments = Comment::where('board_table', $board_name.'_board_'.config('app.country'))
                    ->where('board_id', $id)
                    ->with('re_comments')
                    ->orderBy($orderBy, 'desc')
                    ->offset($offset)
                    ->limit(15)
                    ->get();

        $auth = Auth::check();

        if($auth){
            $uid = Auth::id();
        }else{
            $uid = -1;
        }

        $offset += count($comments);

        $response = array(
            "comments" => $comments,
            "offset" => $offset,
            "auth" => $auth,
            "uid" => $uid,
        );

        return response()->json($response);
    }

    private function comment_store($request){
        $board_name = $request->board_name;
        $user = Auth::user();

        if($user->comunity_status == 0){
            $response = array(
                "status" => 0,
                "comment" => $request->comment,
                "writer_nickname" => $user->nickname,
                "message" => "현재 커뮤니티 사용상태가 이용 중지입니다. 고객센터에 문의해주세요.",
            );
            return response()->json($response);
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
            $response = array(
                "status" => 0,
                "comment" => $request->comment,
                "writer_nickname" => $user->nickname,
                "message" => "현재 사용가능한 댓글쓰기 횟수는 0으로 사용가능한 댓글쓰기 횟수를 모두 소진하셨습니다. (총 순 매수 코인 보유량의 원화 가치가 100만원을 넘는다면 무제한으로 사용 가능합니다. *이벤트코인 무료코인 제외)",
            );
            return response()->json($response);
        }

        if(empty($request->comment_id)){
            if($result_balance < $limit_comunity_krw){
                DB::table('users')->where('id',$user->id)->decrement('comment_count',1);
            }
            $status = DB::connection('mysql_sub')->table('comment')
                    ->insert([
                        "writer_id" => $user->id,
                        "writer_nickname" => $user->nickname,
                        "board_table" => $board_name.'_board_'.config('app.country'),
                        "board_id" => $request->board_id,
                        "comment" => $request->comment,
                    ]);

            if($status){
                DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))
                ->where('id', $request->board_id)
                ->increment('comment_cnt', 1);
            }
        }else{
            if($result_balance < $limit_comunity_krw){
                DB::table('users')->where('id',$user->id)->decrement('comment_count',1);
            }
            $status = DB::connection('mysql_sub')->table('re_comment')
                    ->insert([
                        "comment_id" => $request->comment_id,
                        "writer_id" => $user->id,
                        "writer_nickname" => $user->nickname,
                        "board_table" => $board_name.'_board_'.config('app.country'),
                        "board_id" => $request->board_id,
                        "comment" => $request->comment,
                    ]);
        }
        if($result_balance < $limit_comunity_krw){
            $message = '댓글쓰기 횟수가 1회 소진됩니다. 현재 남은 글쓰기 횟수는 '.($user->comment_count - 1).'회 남았습니다.';
        }else{
            $message = '댓글 등록 완료!';
        }
        $response = array(
            "status" => $status,
            "comment" => $request->comment,
            "writer_nickname" => $user->nickname,
            "message" => $message,
        );

        return response()->json($response);
    }

    private function comment_update($request, $id){
        $user = Auth::user();

        if($request->recomment == 0){
            $status = DB::connection('mysql_sub')->table('comment')
                        ->where('id', $id)
                        ->where('writer_id', $user->id)
                        ->update([
                            "writer_nickname" => $user->nickname,
                            "comment" => $request->content,
                            "updated_at" => now(),
                        ]);
        }else{
            $status = DB::connection('mysql_sub')->table('re_comment')
                        ->where('id', $id)
                        ->where('writer_id', $user->id)
                        ->update([
                            "writer_nickname" => $user->nickname,
                            "comment" => $request->content,
                            "updated_at" => now(),
                        ]);
        }

        $response = array(
            "status" => $status,
            "comment" => $request->content,
        );

        return response()->json($response);
    }

    private function recomend($request, $id){
        if(!Auth::check()){
            $response = array(
                "status" => 0
            );

            return response()->json($response);
        }

        $board_name = $request->board_name;
        $uid = Auth::id();

        $recommend_uids = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))->where('id',$id)->value('recomend_uid');

        $recommend_uid = array(
            $uid
        );
        
        if($recommend_uids == NULL){

            $status = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))
                        ->where('id',$id)
                        ->update([
                            "recomend" => DB::raw("recomend + 1"),
                            "recomend_uid" => json_encode($recommend_uid),
                        ]);

            $in_de = 1;
        }else{
            $recommend_uids = json_decode($recommend_uids);

            if(in_array($uid, $recommend_uids)){
                $recommend_uids = array_diff($recommend_uids, $recommend_uid);
                $status = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))
                            ->where('id',$id)
                            ->update([
                                "recomend" => DB::raw("recomend - 1"),
                                "recomend_uid" => json_encode($recommend_uids),
                            ]);
                
                $in_de = 0;
            }else{
                array_push($recommend_uids, $uid);
                $status = DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))
                            ->where('id',$id)
                            ->update([
                                "recomend" => DB::raw("recomend + 1"),
                                "recomend_uid" => json_encode($recommend_uids),
                            ]);

                $in_de = 1;
            }
        }

        $response = array(
            "status" => $status,
            "in_de" => $in_de,
        );

        return response()->json($response);

    }

    private function comment_recomend($request){
        $id = $request->comment_id;
        $uid = Auth::id();

        $recommend_uids = DB::connection('mysql_sub')->table('comment')->where('id',$id)->value('recomend_uid');

        $recommend_uid = array(
            $uid
        );
        
        if($recommend_uids == NULL){

            $status = DB::connection('mysql_sub')->table('comment')
                        ->where('id',$id)
                        ->update([
                            "recomend" => DB::raw("recomend + 1"),
                            "recomend_uid" => json_encode($recommend_uid),
                        ]);

            $in_de = 1;
        }else{
            $recommend_uids = json_decode($recommend_uids);

            if(in_array($uid, $recommend_uids)){
                $recommend_uids = array_diff($recommend_uids, $recommend_uid);
                $status = DB::connection('mysql_sub')->table('comment')
                            ->where('id',$id)
                            ->update([
                                "recomend" => DB::raw("recomend - 1"),
                                "recomend_uid" => json_encode($recommend_uids),
                            ]);
                
                $in_de = 0;
            }else{
                array_push($recommend_uids, $uid);
                $status = DB::connection('mysql_sub')->table('comment')
                            ->where('id',$id)
                            ->update([
                                "recomend" => DB::raw("recomend + 1"),
                                "recomend_uid" => json_encode($recommend_uids),
                            ]);

                $in_de = 1;
            }
        }

        $response = array(
            "status" => $status,
            "in_de" => $in_de,
        );

        return response()->json($response);

    }

    private function comment_unrecomend($request){
        $id = $request->comment_id;
        $uid = Auth::id();

        $unrecomend_uids = DB::connection('mysql_sub')->table('comment')->where('id',$id)->value('unrecomend_uid');

        $unrecomend_uid = array(
            $uid
        );
        
        if($unrecomend_uids == NULL){

            $status = DB::connection('mysql_sub')->table('comment')
                        ->where('id',$id)
                        ->update([
                            "unrecomend" => DB::raw("unrecomend + 1"),
                            "unrecomend_uid" => json_encode($unrecomend_uid),
                        ]);

            $in_de = 1;
        }else{
            $unrecomend_uids = json_decode($unrecomend_uids);

            if(in_array($uid, $unrecomend_uids)){
                $unrecomend_uids = array_diff($unrecomend_uids, $unrecomend_uid);
                $status = DB::connection('mysql_sub')->table('comment')
                            ->where('id',$id)
                            ->update([
                                "unrecomend" => DB::raw("unrecomend - 1"),
                                "unrecomend_uid" => json_encode($unrecomend_uids),
                            ]);
                
                $in_de = 0;
            }else{
                array_push($unrecommend_uids, $uid);
                $status = DB::connection('mysql_sub')->table('comment')
                            ->where('id',$id)
                            ->update([
                                "unrecomend" => DB::raw("unrecomend + 1"),
                                "unrecomend_uid" => json_encode($unrecomend_uids),
                            ]);

                $in_de = 1;
            }
        }

        $response = array(
            "status" => $status,
            "in_de" => $in_de,
        );

        return response()->json($response);

    }

    private function comment_delete($id, $request){
        $board_name = $request->board_name;
        
        $status = DB::connection('mysql_sub')->table('re_comment')->where('comment_id',$id)->delete();
        $status = DB::connection('mysql_sub')->table('comment')->where('id',$id)->delete();

        if($status){
            DB::connection('mysql_sub')->table($board_name.'_board_'.config('app.country'))
            ->where('id', $request->board_id)
            ->decrement('comment_cnt', 1);
        }

        $response = array(
            "status" => $status,
        );

        return response()->json($response);
    }

    private function re_comment_delete($id, $request){
        $status = DB::connection('mysql_sub')->table('re_comment')->where('id',$id)->delete();

        $response = array(
            "status" => $status,
        );

        return response()->json($response);
    }

    
}
