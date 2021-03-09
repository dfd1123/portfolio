<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;

//use Illuminate\Support\Facades\Input;

class TriptipController extends Controller
{
    public function __invoke($id)
    {
        return 'TripTip Controller';
    }
    public function index()
    {
        return 'API FOR TripTip';
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
/*
        if ($this->decode_res['uid'] ===null) {
            $this->res['query'] =null;
            $this->res['state'] = config('rescode.NO_AUTH_100');
            $this->res['msg'] = $this->decode_res['msg'].' no-token available';
            die(json_encode($this->res));
        }*/
    }

    public function show(Request $request, $req)
    {
        $p = $request->all();

        $params = array();
        switch ($req) {
           
            case 'list':
                $params['offset'] = 0;
                if ($request->filled('offset')  && $p['offset']>0) {
                    $params['offset'] = $p['offset'];
                }

                $where = " ";
                if($request->filled('tip_id') &&  $this->checkRang($p['tip_id'] ,0 ,21000000000) ){
                    $where = " AND tip_id = :tip_id";
                    $params['tip_id'] = $p['tip_id'];
                }

                if($request->filled('tip_title') &&  $this->checkRang($p['tip_title'] ,0 ,21000000000) ){
                    $where = " AND tip_title LIKE  :tip_title";
                    $params['tip_title'] = '%'.$p['tip_title'].'%';
                }

                $sql = "SELECT tip_id
                ,tip_title
                ,tip_content
                ,updated_at
                ,tip_imgs
                ,tip_tag
                ,views
                FROM triptip 
                WHERE state = 1 ";
                $sql.= $where;
                $sql.="  OFFSET :offset LIMIT 10; ";
                $this->execute_query($sql, $params);
            break;
		
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

  //문구수정
  public function update(Request $request, $req='view')
  {
      $p=  $request->all();
      $params = array();
      switch ($req) {
          //내용수정
          default:
          case 'view':

          if (!$request->filled('tip_id')) {
              break;
          }

          $params['tip_id'] =  $p['tip_id'];


          $sql ="UPDATE triptip 
          SET views = views +1  
          WHERE tip_id = :tip_id 
          RETURNING tip_id, views;";

          $this->execute_query($sql, $params);


          break;
      }
      return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
  }

}