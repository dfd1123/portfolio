<?php

use Illuminate\Http\Request;
//use DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/*
Route::middleware('auth:api')->post('/login', function (Request $request){
    $password = Hash::make($request->input('password'));
    $email = $request->input('email');

    $user = DB::table('users')->select('email','fullname as name')->where('email',$email)->where('password',$password)->first();

    if(count($user)){     
        $reponse = array(
            "login_yn" => true,
            "email" => $user->email,
            "name" => $user->name,
        );
    }else{
        $reponse = array(
            "login_yn" => false,
            "email" => null,
            "name" => null,
        );
    }

    return $reponse;
});
*/
Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => '5',
        'redirect_uri' => 'http://trademarket.local/auth/callback',
        'response_type' => 'code',
        'scope' => '',
    ]);

    return redirect('http://trademarket.local/oauth/authorize?'.$query);
});

Route::get('/callback', function (Request $request) {
    $http = new GuzzleHttp\Client;

    $response = $http->post('http://trademarket.local/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => '5',
            'client_secret' => '7V1Qj3cgjVEfgJuFhWrZqyxaIQ5zoEtAi51gsrua',
            'redirect_uri' => 'http://trademarket.local/auth/callback',
            'code' => $request->code,
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});

Route::get('password/callback', function (){
    $http = new GuzzleHttp\Client;

    $response = $http->post('http://trademarket.local/oauth/token', [
        'form_params' => [
            'grant_type' => 'password',
            'client_id' => '5',
            'client_secret' => '7V1Qj3cgjVEfgJuFhWrZqyxaIQ5zoEtAi51gsrua',
            'username' => 'dfd1123@naver.com',
            'password' => 'a1a1a2a3!',
            'scope' => '',
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});