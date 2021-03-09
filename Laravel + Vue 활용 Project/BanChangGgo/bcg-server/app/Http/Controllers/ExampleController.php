<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Classes\Example;
use Auth;

class ExampleController extends Controller
{
    public function __construct()
    {
        // 에러 리턴 (로직 에러)
        // return response()->json([
        //    'error' => 'invalid_request', // 에러 스트링
        //    'message' => '이메일을 찾을 수 없습니다' // 에러 메세지
        // ], 422); // HTTP 422 리턴
    }

    /*
    // 값 가져오기
    $value_name = $request['value_name']; // GET
    $value_name = $request->get('value_name', 'default_value'); // GET Default
    $value_name = $request->value_name; // POST
    $value_name = $request->input('value_name', 'default_value'); // POST Default
    */

    public function index(Request $request)
    {
        // 기본값 설정, 변수 체크
        $params = [
            'param' => $request->get('value', 'default_value')
        ];

        // 로직 실행
        $res = Example::index($params);

        // 배열 리턴
        return response()->json($res);
    }

    public function store(Request $request)
    {
        // 기본값 설정, 변수 체크
        $params = [
            'param' => $request->input('value', 'default_value')
        ];
        
        // 로직 실행
        $res = Example::store($params);

        // 성공 시 HTTP 201, lastInsertId 리턴
        return response()->json($res, 201);
    }

    public function show(Request $request, $id)
    {
        // 기본값 설정, 변수 체크
        // $param1 = $request->input('value', 'default_value');
        
        // 로직 실행
        $res = Example::show(/* Auth::id(), */ $id /*, $param1, $param2... */);

        // 배열을 리턴하지 않음 값이 없으면 null 리턴
        return response()->json($res);
    }

    public function update(Request $request, $id)
    {
        // 기본값 설정, 변수 체크
        $params = [
            /* 'user_id' => Auth::id(), */
            'id' => $id,
            'param' => $request->input('value', 'default_value')
        ];

        // 로직 실행
        $res = Example::update($params);

        // 열 업데이트 여부(true or false) 리턴
        return response()->json($res);
    }

    public function destroy(Request $request, $id)
    {
        // 기본값 설정, 변수 체크
        $params = [
            /* 'user_id' => Auth::id(), */
            'id' => $id,
            'param' => $request->input('value', 'default_value')
        ];

        // 로직 실행
        $res = Example::destroy($params);

        // 열 삭제 여부(true or false) 리턴
        return response()->json($res);
    }

    public function any(Request $request /*, param1, param2 */)
    {
        // 기타
        // Route에서 /model_name/param1/param2/ ...
    }
}
