<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->filled('ca_id')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        try {
            $items = DB::table('items')
                        ->where('ca_id', $request->ca_id)
                        ->where('items.sell_yn', 1)
                        ->join('item_options', 'items.item_id', '=', 'item_options.item_id')
                        ->orderBy('items.item_id', 'DESC')
                        ->get();
            
            $itemArray = array();
            

            $item_id = 0;
            $cnt = -1;

            foreach($items as $item){
                if($item_id !== $item->item_id){
                    $cnt += 1;
                    $item_id = $item->item_id;
                    $tempItem = array(
                        "item_id" => $item->item_id,
                        "ca_id" => $item->ca_id,
                        "title" => $item->title,
                        "images" => $item->images,
                        "simple_intro" => $item->simple_intro,
                        "intro" => $item->intro,
                        "m_intro" => $item->m_intro,
                        "video_url" => $item->video_url,
                        "origin_price" => $item->origin_price,
                        "sale_price" => $item->sale_price,
                        "tax_mny" => $item->tax_mny,
                        "vat_mny" => $item->vat_mny,
                        "options" => array()
                    );
                    array_push($itemArray,  $tempItem);
                    array_push($itemArray[$cnt]["options"], $item);
                }else{
                    array_push($itemArray[$cnt]["options"], $item);
                }
            }
            

            $query = array(
                "items" => $itemArray
            );

            $this->res['query'] = $query;
            $this->res['msg'] = "성공";
            $this->res['state'] = config('res_code.OK');
        } catch(Exception $e) {
            $this->res['query'] =null;
            $this->res['msg'] = "시스템 에러(쿼리)"; 
            $this->res['state'] = config('res_code.QUERY_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
