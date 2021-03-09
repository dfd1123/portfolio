<?php

namespace TLCfund\Http\Controllers;

use Jenssegers\Agent\Agent;

use Illuminate\Http\Request;
use TLCfund\Address;
use Facades\App\Classes\EthApi;
use Auth;

class CoinController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
		$agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    /*
    public function coin_charge(){
        $view = view('mobile.coin.coin_charge');
        $view->title = '코인충전';
        return $view;
    }
    */

    public function coin_deposit(){
        $view = view('mobile.coin.coin_deposit');
        $view->title = '입출금내역';
        return $view;
    }

    public function coin_edit(){
        $user = Auth::user();

        $address_info = Address::select('available_balance_tlc as available')->where('user_email', $user->email)->first();
        $coin_balance = floatval($address_info->available);
        $coin_address = EthApi::getAddress($user->email);
        $transactions = EthApi::listTransactions($user->email, 10, 0);

        $view = view('mobile.coin.coin_edit');
        $view->title = '코인입출금';
        $view->coin_balance = $coin_balance;
        $view->coin_address = $coin_address;
        $view->transactions = $transactions;
        return $view;
    }

    /*
    public function coin_list(){
        $view = view('mobile.coin.coin_list');
        $view->title = '충전내역';
        return $view;
    }
    */
}
