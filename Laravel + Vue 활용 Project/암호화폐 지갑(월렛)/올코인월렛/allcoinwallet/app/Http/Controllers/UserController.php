<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Facades\App\Classes\Wallet;

use App\User;

use DB;
use Auth;
use Input;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $id = Input::get('id', '');
        $fullname = Input::get('fullname', '');
        
        $symbol = Input::get('symbol', '');
        $address = Input::get('address', '');

        if(!empty($id)) {
            $users = User::select('id', 'fullname', 'email')
                ->where(DB::raw('CAST(id as CHAR)'), 'like', '%'.$id.'%')
                ->where('password', '<>', '')
                ->whereNotNull('status')
                ->get();
        } else if(!empty($symbol) || !empty($address)) {
            $symbol = strtolower($symbol);

            $address_user = DB::table('btc_users_addresses')
                ->select('uid')
                ->where("address_$symbol", $address)
                ->first();
            if($address_user == null) {
                return response()->json([]);
            }

            $user = User::select('id', 'fullname', 'email')
                ->where('id', $address_user->uid)
                ->where('password', '<>', '')
                ->whereNotNull('status')
                ->first();
            if($user == null) {
                return response()->json([]);
            }
            
            $user->address = $address;
            $users = [$user];
        } else if(!empty($fullname)) {
            $users = User::select('id', 'fullname', 'email')
                ->where('fullname','like','%'.$fullname.'%')
                ->where('password', '<>', '')
                ->whereNotNull('status')
                ->offset(0)
                ->limit(PHP_INT_MAX)
                ->get();
        } else {
            $users = User::select('id', 'fullname', 'email')
                ->where('password', '<>', '')
                ->whereNotNull('status')
                ->offset(0)
                ->limit(PHP_INT_MAX)
                ->get();
        }

        return response()->json($users);
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
        $user = User::select('id', 'fullname')
            ->where('id', $id)
            ->whereNotNull('status')
            ->first();
            
        return response()->json($user);
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
