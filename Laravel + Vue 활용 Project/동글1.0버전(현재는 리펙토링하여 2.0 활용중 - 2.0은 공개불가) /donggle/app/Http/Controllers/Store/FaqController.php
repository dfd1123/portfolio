<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class FaqController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'Qna controller';
    }

    public function index()
    {
        return 'QNA FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch ($req) {
            case 'list':
                $query = DB::table('store_faq')->orderBy('created_at', 'DESC')->get();
                
                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
    }

    public function update(Request $request, $req)
    {
        switch ($req) {
            
        }
  
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request)
    {
    }
}
