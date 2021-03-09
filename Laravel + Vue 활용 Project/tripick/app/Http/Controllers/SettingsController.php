<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

//use Illuminate\Support\Facades\Input;

class SettingsController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'Settings controller';
    }

    public function index()
    {
        return 'API FOR SETTINGS';
    }

    // 요청경로  GET - URL  : api/settings/{$req}
    public function show(Request $request, $req)
    {
        $p = $request->all();


        switch ($req) {
            case 'settings':
                $params = array();
                
                $sql = "SELECT * FROM settings;";

                $this->res = $this->execute_query($sql, $params);

            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  POST - URL  : api/settings
    public function store(Request $request)
    {
        //관리자만 삽입 가능
        $p = $request->all();

        if ($request->filled('version','terms_service','privacy_policy')) {
            $sql = 'INSERT INTO 
                        settings(version, terms_service, privacy_policy)
                    VALUES (:version, :terms_service, :privacy_policy) RETURNING version;';

            $params = array(
            'version' => $p['version'],
            'terms_service' => $p['terms_service'],
            'privacy_policy' => $p['privacy_policy']
            );

            $this->execute_query($sql, $params);

            //정상적으로 실행된 경우
            if (count($this->res['query']) >0 &&  $this->res['query'][0]->version > 0) {
            } else { 
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.NO_DATA');
                $this->res['msg'] = '쿼리응답에러';
            }
        }
        //예외상황
        else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : TYPE 277';
        }
        //정상등록된 경우 state 1
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    // 요청경로  PUT - URL  : api/settings/{$req}
    public function update(Request $request, $req)
    {
        $p = $request->all();

        switch ($req) {
            //관리자만 수정가능
            case 'update':
                if (!$request->filled('version','terms_service','privacy_policy')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }

                $sql = "UPDATE 
                            settings
                        SET 
                            version = :version,
                            terms_service = :terms_service,
                            privacy_policy = :privacy_policy;";

                $params = array('version'=>$p['version'] , 'terms_service'=>$p['terms_service'], 'privacy_policy'=>$p['privacy_policy']);

                $this->execute_query($sql, $params, 'update');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  DELETE - URL  : api/estimate_theme
    public function destroy(Request $request, $req)
    {
    
    }
}
