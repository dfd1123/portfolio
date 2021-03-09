<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Jenssegers\Agent\Agent;

use DB;
use Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }
    
    public function index(Request $request)
    {
        $p2p_id = $request->input('p2p_id');

        $p2p = DB::connection('mysql_sub')->table('btc_p2p')->where('id', $p2p_id)->first();
        if ($p2p == null) {
            abort(404);
        }

        $user = Auth::user();

        if ($user == null) {
            abort(404);
        }

        if ($user->id != $p2p->b_id && $user->id != $p2p->s_id) {
            abort(404);
        }

        if ($p2p->chatroom_id == null) {
            $room_token = str_random(64);
            $room_id = DB::connection('mysql_sub')->table('btc_p2p_chatroom')->insertGetId(
                [
                    'p2p_id' => $p2p->id,
                    'token' => $room_token,
                    'data' => "[]"
                ]
            );

            DB::connection('mysql_sub')->table('btc_p2p')->where('id', $p2p->id)->update(
                [
                    'chatroom_id' => $room_id
                ]
            );
        } else {
            $room = DB::connection('mysql_sub')->table('btc_p2p_chatroom')->where('id', $p2p->chatroom_id)->first();
            $room_token = $room->token;
            $room_id = $room->id;
        }

        $view = view(session('theme').'.'.$this->device.'.'.'chat.chat');
        $view->room_token = $room_token;
        $view->room_id = $room_id;
        $view->user_id = $user->id;
        $view->user_name = $user->fullname;
        
        return $view;
    }
}
