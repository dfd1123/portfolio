<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;

class FcmController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    
    public function __invoke($id)
    {
        return 'FcmController';
    }
    
    private function send2topic()
    {
        $notification = array('title'=>"주문안내", "body" => "상품주문완료", "icon"=>"splash");
        $data = array("testval"=>"key");

        $fields = array(
             'to' => "/topics/default",
             'notification' => $notification,
             'data' => $data
            );
        $this->sendpush($fields);
    }

    private function send2user($user)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';


        $notification = array('title'=>"주문안내", "body" => "상품주문완료 ", "icon"=>"splash");
        $data = array("testval"=>"key");

        $fields = array(
             'to' =>  $user,
             'notification' => $notification,
             'data' => $data
            );
        /*
        $notification = array('title'=>"주문안내", "body" => "상품주문완료 : ", "icon"=>"splash");
        $data = array("testval"=>"key");

        $array_module_srl = array($user);

        $fields = array(
                    'registration_ids'  => $array_module_srl, // array type
                    'priority' => 'high',
                    'notification'=> array(
                    'title' => '주문안내',
                    'body' => '상품주문완료',
                    'sound' => 'default',
                    'badge' => 0
                    ),
                    'data'=> array('testval'=> 'key1')
            );*/
        $this->sendpush($fields);
    }

    private function sendpush($fields)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $key = "AAAAIjU_rzw:APA91bHuBTjclP7mMeYJQUSdO2g2aMl2e_OXSt2_4ZgEE7wj_mFAjiq5HTQ798yR0RdqT_aRDpzr-4RMCQVbymP1_iGhkT6C-NB1-Bb7S5fqVTbsUmXiQulD8gK8c8D_Xad0nq8gs2Se";
        $headers = array(
            'Authorization:key=' . $key,
            'Content-Type:application/json'
            );
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
             
        //var_dump(json_encode($fields));
        $result = curl_exec($ch);
        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        //var_dump($result);
        return $result;
    }

    public function index()
    {
        return 'API FOR FCM';
    }
    public function show(Request $request, $req)
    {
        $p = $request->all();
        switch ($req) {
            case 'send':
                if ($request->filled('target') && $request->input('target') >= 0) {
                } else {
                   break;
                }
               
                $this->send2user($p['target']);

            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function store(Request $request)
    {
        $p = $request->all();
        $params = array();
        if ($request->filled('bl_name')
        && (strlen($p['bl_name'])>0)
       ) {
            $sql = 'INSERT INTO business_list
            (bl_name
            ,bl_thumb)
            VALUES (:bl_name
            , :bl_thumb)
            RETURNING bl_no ;';
            $params['bl_name'] = $p['bl_name'];

            //썸네일 있을경우 변경저장
            if ($request->hasFile('bl_thumb') && $request->file('bl_thumb')->isValid()) {
                $extension = $request->bl_thumb->extension();
                $path = $request->bl_thumb->storeAs(
                    config('filesystems.blist_photo'),
                    'bllist.'.$extension
                );
                $params['bl_thumb'] = $path;
            }
            
            $this->execute_query($sql, $params, 'select');

            //정상적으로 실행된 경우
            if (count($this->res['query']) >0 &&  $this->res['query'][0]->bl_no > 0) {
            } else {
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.NO_DATA');
                $this->res['msg'] = '쿼리응답에러';
            }
        } else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : 1';
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
