<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $res = array('state'=>1 , 'query'=>null, 'msg'=>'init1');

    public function __construct()
    {
        // $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
        // . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        /*
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
        . " - {$_SERVER['REQUEST_URI']}";


        $this->logger($actual_link, 'myuser', $_SERVER["REQUEST_METHOD"]);
        */
    }

    protected function logger($url, $user, $method)
    {
        $urlinfo =  'Requested URL : '.$url;
        $userinfo =  'Called FROM '.$user;

        $info =  PHP_EOL.$urlinfo.PHP_EOL.$userinfo.PHP_EOL.$method;

        Log::channel('monitoring')->info('Requested URL : '.$info);
    }


    protected function execute_query($sql, $param, $method='select')
    {
        $method = strtolower($method);


        foreach ($param as $key=>$value) {
            $param[$key] = strip_tags($value);
        }

        try {
            switch ($method) {

            case 'insert':
                $this-> res['query'] = DB::insert($sql, $param);
            break;

            case 'update':
                $this-> res['query'] = DB::update($sql, $param);
            break;
            
            case 'delete':
                $this-> res['query'] = DB::delete($sql, $param);
            break;
            
            case 'select':
                $this-> res['query'] = DB::select($sql, $param);
            break;
            }
        } catch (exception $e) {
            $this-> res['query'] =null;
            $this-> res['msg'] = "쿼리실행오류";
            $this-> res['state'] = config('res_code.QUERY_ERR');
        }

        return $this-> res;
    }
}
