<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\FcmPush;
use Auth;

class PushController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Request $request, $id)
    {
        //
    }

    public function push_topic(Request $request)
    {
        $data = [
            'title' => $request->title ?? '',
            'body' => $request->body ?? '',
            'topic_id' => 0
        ];

        $fcm_return_key = FcmPush::push_topic("/topics/default", $data);
        if (!empty($fcm_return_key)) {
            $result = json_decode($fcm_return_key);
            FcmPush::store_history([
                'id' => $result->message_id,
                'title' => $data['title'],
                'body' => $data['body']
            ]);
        }

        return response()->json(null, 200);
    }

    public function push_topic_history(Request $request)
    {
        $res = FcmPush::index_history([]);

        return response()->json($res, 200);
    }
}
