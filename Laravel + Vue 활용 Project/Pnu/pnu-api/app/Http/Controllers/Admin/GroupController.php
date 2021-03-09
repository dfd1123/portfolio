<?php

namespace App\Http\Controllers\Admin;

use Facades\App\Classes\FileRequest;
use Illuminate\Http\Request;
use Auth;
use DB;

class GroupController extends Controller
{

  public function index(Request $request){

    if( $request->filled('req')){
        $req = $request->req;

        $res =null;
        switch($req){
          //전공요청
          case 'major':
            //차후 subquery로 index조회하게 수정해야함
            $this->res = DB::select("SELECT * FROM  majors ");
            $this->res['state'] =1;
          break;
        
          //학과요청
          case 'dept':
               //차후 subquery로 index조회하게 수정해야함
               $this->res = DB::select("SELECT  * FROM  depts;");
            $this->res['state'] =1;
          break;

          //단과대학요청
          case 'coll':
            //차후 subquery로 index조회하게 수정해야함
            $this->res = DB::select("SELECT  * FROM  colls;");
            $this->res['state'] =1;
          break;

          case 'batch' :

            $this->res['query'] = DB::select("SELECT  batch_id FROM  batch;");
            $this->res['state'] =1;

          break;

        }
    }
    return response()->json($this->res);
  
  
  }
}
