<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

class RequestQuoteController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
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

    if (!$request->filled('items', 'req_name', 'req_tel', 'req_email', 'req_company', 'req_contents', 'req_date')) {
      $this->res['query'] = null;
      $this->res['msg'] = "필수 정보 부족!";
      $this->res['state'] = config('res_code.PARAM_ERR');
      return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    $items = json_decode($request->items);
    $req_name = $request->req_name;
    $req_tel = $request->req_tel;
    $req_email = $request->req_email;
    $req_company = $request->req_company;
    $req_contents = $request->req_contents;
    $req_date = $request->req_date;
    $tax_mny = 0;
    $vat_mny = 0;
    $total_price = 0;

    foreach ($items as $item) {
      $tax_mny += $item->tax_mny;
      $vat_mny += $item->vat_mny;
      $total_price += $item->total_price;
    }

    $receipt_price = $total_price;

    try {

      $reqQuoteId = DB::table('request_quote')->insertGetId([
        "items" => $request->items,
        "req_name" => $req_name,
        "req_tel" => $req_tel,
        "req_email" => $req_email,
        "req_company" => $req_company,
        "req_contents" => $req_contents,
        "req_date" => $req_date,
        "total_price" => $total_price,
        "receipt_price" => $receipt_price,
        "tax_mny" => $tax_mny,
        "vat_mny" => $vat_mny
      ]);

      $query = array(
        "reqQuoteId" => $reqQuoteId
      );

      $this->res['query'] = $query;
      $this->res['msg'] = "성공";
      $this->res['state'] = config('res_code.OK');
    } catch (Exception $e) {
      $this->res['query'] = null;
      $this->res['msg'] = "시스템 에러(쿼리)";
      $this->res['state'] = config('res_code.QUERY_ERR');
      return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    try {
      $requestQuote = DB::table('request_quote')->where('rq_id', $id)->first();

      if (!$requestQuote) {
        $this->res['query'] = null;
        $this->res['msg'] = "찾으시려는 견적서가 존재하지 않습니다.";
        $this->res['state'] = config('res_code.PARAM_ERR');
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
      }

      $query = array(
        "items" => json_decode($requestQuote->items),
        "invoice" => $requestQuote
      );

      $this->res['query'] = $query;
      $this->res['msg'] = "성공";
      $this->res['state'] = config('res_code.OK');
    } catch (Exception $e) {
      $this->res['query'] = null;
      $this->res['msg'] = "시스템 에러(쿼리)";
      $this->res['state'] = config('res_code.QUERY_ERR');
      return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
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