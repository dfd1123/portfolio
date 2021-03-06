<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Utils\JWT;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $res = array('state'=>1 , 'query'=>null, 'msg'=>'init1');

    protected $decode_res =null;

    public function __construct(Request $request)
    {
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http"). " - {$_SERVER['REQUEST_URI']}";

        $this->logger($actual_link, 'myuser', $_SERVER["REQUEST_METHOD"]);

        $url = $request->path();

        $JWTObject = JWT::get_instance();

        $token = $request->cookie('Authorization');

        if ($request->headers->has('Authorization')) {
            $token = $request->header('Authorization');
        }

        $decode_res= $JWTObject->decode_tkn($token ,config('constant.JWT_SECRET_A_KEY'));
        $this->decode_res =  $decode_res;
        // $this->decode_res 
        //uid - 유저번호, null
        //iss
        //exp


    }
    protected function execute_query($sql, $param, $method='select')
    {
        $method = strtolower($method);

        if ($param !==null) {
            foreach ($param as $key=>$value) {
                $param[$key] = strip_tags($value);
            }
        }
        try {
            //$this->res['msg']= $method;

            switch ($method) {

                case 'insert':
                if ($param ===null) {
                    $this->res['query'] = DB::insert($sql);
                } else {
                    $this->res['query'] = DB::insert($sql, $param);
                }
                break;

                case 'update':
                    $this->res['query'] = DB::update($sql, $param);
                break;
                
                case 'delete':
                if ($param ===null) {
                    $this->res['query'] = DB::delete($sql);
                }else{
                    $this->res['query'] = DB::delete($sql, $param);
                }
                break;
                
                case 'select':
                if ($param ===null) {
                    $this->res['query'] = DB::select($sql);
                } else {
                    $this->res['query'] = DB::select(DB::raw($sql), $param);
                }
                break;
                }
        } catch (exception $e) {
            $this->res['query'] =null;
            $this->res['msg'] .= "쿼리실행오류";
            $this->res['state'] = config('res_code.QUERY_ERR');
        }

        return $this->res;
    }


    protected function checkFiles($filename, $request ,$allowedExts){

        $hasValidExt =true;
        if($request->hasFile($filename) ){
            foreach( $request->file($filename) as $file){
                if($file->isValid()){
                    $sysExtension = $file->extension();
                    $extension =   $file->getClientOriginalExtension();
            
                    foreach ($allowedExts as $ext) {
                        if ($sysExtension == $ext) {
                            $hasValidExt = $ext;
                            break;
                        }else{
                            $hasValidExt = false;
                        }
                    }
                    if($hasValidExt ===false )
                        return $hasValidExt;
                }
            }    
        }else{
        	return false;
        }
        return true;
    }

    protected function checkFile($filename, $request)
    {
        if ($request->hasFile($filename) && $request->file($filename)->isValid()) {
            return true;
        }
        return false;
    }

    protected function checkExtension($filename, $request, $allowedExts)
    {
        $sysExtension = $request->$filename->extension();
        $extension = $request->$filename->getClientOriginalExtension();

        foreach ($allowedExts as $ext) {
            if ($sysExtension == $ext) {
                return $sysExtension;
            }
        }
        return false;
    }
    protected function saveFile($filename, $request, $path, $ext='.data')
    {
        $name =$path.Str::uuid()->toString().'.'.$ext;

        return $this->saveFileNameUnder($filename, $request, $name);
    }


    //$filename FORM 파일, $request : request  , $name : 저장할 파일명
    protected function saveFileNameUnder($filename, $request, $name)
    {
       //return $request->{$filename}->storeAs($name, '','shared') ;
       return $request->{$filename}->storeAs($name, '') ;
    }


    protected function checkLength($param, $min, $max)
    {
        return  (strlen($param) > $min &&  strlen($param) < $max);
    }
    protected function checkRange($param, $min, $max)
    {
        return  ($param > $min &&  $param  < $max);
    }

    protected function logger($url, $user, $method)
    {
        $urlinfo =  'Requested URL : '.$url;
        $userinfo =  'Called FROM '.$user;

        $info =  PHP_EOL.$urlinfo.PHP_EOL.$userinfo.PHP_EOL.$method;

        Log::channel('monitoring')->info('Requested URL : '.$info);
    }
}