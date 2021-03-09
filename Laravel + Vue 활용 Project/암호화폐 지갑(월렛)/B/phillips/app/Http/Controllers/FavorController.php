<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Facades\App\Classes\Wallet;

use DB;
use Auth;

class FavorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $favors = DB::table('btc_favor_wallet')
            ->join('btc_coins', 'btc_coins.symbol', '=', 'btc_favor_wallet.symbol')
            ->select('btc_coins.api', 'btc_coins.market', 'btc_favor_wallet.id')
            ->where('btc_favor_wallet.uid', Auth::user()->id)
            ->where('btc_coins.active', 1)
            ->where('btc_coins.cointype', '<>', 'cash')
            ->get();

        $response = [];
        foreach ($favors as $favor) {
            $balance = Wallet::balance(Auth::user()->id, $favor->api);

            array_push($response, [
                'id' => $favor->id,
                'symbol' => $favor->api,
                'balance' => $balance,
                'market' => $favor->market
            ]);
        }

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $symbol = $request->symbol;
        if (empty($symbol)) {
            return response()->json(null, 422);
        }

        $favor = DB::table('btc_favor_wallet')->insertGetId([
            'uid' => Auth::user()->id,
            'symbol' => strtoupper($symbol)
        ]);

        return response()->json($favor, 201);
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
        DB::table('btc_favor_wallet')->where('id', $id)->delete();

        return response()->json(null, 204);
    }
}
