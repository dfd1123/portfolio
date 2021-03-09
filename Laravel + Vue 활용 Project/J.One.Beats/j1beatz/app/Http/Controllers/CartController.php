<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Input;
use \Facades\App\Classes\Cart;
use \Facades\App\Classes\BeatOrder;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $req = Input::get('req');
        switch ($req) {
            case 'cart':
                $res = Cart::cartlist(auth()->user()->user_id);
            break;
            case 'leftheader':
                $res = Cart::leftheader(auth()->user()->user_id);
            break;
        }

        return response()->json($res);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 이미 장바구니에 있는 비트인지 체크
        $check = Cart::by_beat_id(auth()->user()->user_id, $request->beat_id);
        // 아직 유효한 구매내역이 있는 비트인지 체크
        $check2 = BeatOrder::available(auth()->user()->user_id, $request->beat_id);

        if ($check === null && $check2 === null) {
            Cart::store(auth()->user()->user_id, $request->beat_id);
            return response()->json(null, 201);
        }

        return response()->json(null, 422);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($id == "collection") {
            if ($request->action == "store") {
                foreach ($request->beat_ids as $beat_id) {
                    // 이미 장바구니에 있는 비트인지 체크
                    $check = Cart::by_beat_id(auth()->user()->user_id, $beat_id);
                    // 아직 유효한 구매내역이 있는 비트인지 체크
                    $check2 = BeatOrder::available(auth()->user()->user_id, $beat_id);

                    if ($check === null && $check2 === null) {
                        Cart::store(auth()->user()->user_id, $beat_id);
                    }
                }

                return response()->json(null, 201);
            }

            if ($request->action == "destroy") {
                foreach ($request->cart_ids as $cart_id) {
                    Cart::destroy(auth()->user()->user_id, $cart_id);
                }

                return response()->json(null, 200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cart::destroy(auth()->user()->user_id, $id);
        return response()->json(null, 200);
    }
}
