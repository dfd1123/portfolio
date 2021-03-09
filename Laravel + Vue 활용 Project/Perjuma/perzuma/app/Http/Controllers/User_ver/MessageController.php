<?php

namespace App\Http\Controllers\User_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class MessageController extends Controller
{
    public function data_load(Request $request, $req){
        switch($req){
            case 'default_list':
             return $this->alert_list($request);
            case 'more_list':
             return $this->alert_list($request);
            case 'view':
             return $this->alert_view($request);
        }
    }

    private function alert_list(Request $request){
        if(auth()->check()){
            $messages_cnt = DB::table('message')
                        ->where('user_no', auth()->user()->user_no)
                        ->leftJoin('trades','message.trd_no','=','trades.trd_no')
                        ->select('message.state as msg_state','message.*','trades.*')
                        ->orderBy('send_dt','desc')
                        ->orderBy('msg_type','desc')
                        ->get();

            $messages = DB::table('message')
                        ->where('user_no', auth()->user()->user_no)
                        ->leftJoin('trades','message.trd_no','=','trades.trd_no')
                        ->select('message.state as msg_state','message.*','trades.*')
                        ->orderBy('send_dt','desc')
                        ->orderBy('msg_type','desc')
                        ->limit(10)
                        ->get();

            $new = 0;

            foreach($messages_cnt as $message){
                if($message->msg_state == 0){
                    $new = 1;
                }
            }

            $response = array(
                            "state" => 1,
                            "new" => $new,
                            "offset" => count($messages),
                            "count" => count($messages_cnt),
                            "messages" => $messages,
                        );
        }else{
            $response = array(
                            "state" => 0,
                        );
        }

        return response()->json($response);
    }

    private function more_list(Request $request){
        if(auth()->check()){
            $messages = DB::table('message')
                        ->where('user_no', auth()->user()->user_no)
                        ->leftJoin('trades','message.trd_no','=','trades.trd_no')
                        ->select('message.state as msg_state','message.*','trades.*')
                        ->orderBy('send_dt','desc')
                        ->orderBy('msg_type','desc')
                        ->offset($offset)
                        ->limit(10)
                        ->get();

            $offset += count($messages);

            $response = array(
                            "state" => 1,
                            "offset" => $offset,
                            "messages" => $messages,
                        );
        }else{
            $response = array(
                            "state" => 0,
                        );
        }

        return response()->json($response);
    }

    private function alert_view(Request $request){

        $msg_no = $request->msg_no;
        
        $message = DB::table('message')
                    ->where('msg_no', $msg_no)
                    ->leftJoin('trades','message.trd_no','=','trades.trd_no')
                    ->select('message.state as msg_state','message.*','trades.*')
                    ->orderBy('send_dt','desc')
                    ->orderBy('msg_type','desc')
                    ->first();

        DB::table('message')->where('msg_no', $msg_no)->update([
            "state" => 1,
        ]);

        $response = array(
                        "message" => $message,
                    );

        return response()->json($response);
    }
}
