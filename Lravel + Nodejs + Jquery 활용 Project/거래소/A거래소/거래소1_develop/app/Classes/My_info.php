<?php

namespace App\Classes;

use DB;
use Auth;
use Log;
use Coin_info;
use Secure;
use Settings;

class My_info {

    public function walletinfo($uid, $value) {
        $row = DB::table('btc_users_addresses')->where('uid',$uid)->first();
        return $row->{$value};
    }
	
	

	public function get_user_balance_allcoin($uid,$coin_type) {
        $balance = $this->walletinfo($uid, "available_balance_".$coin_type) + $this->walletinfo($uid,"pending_received_balance_".$coin_type);
        if( $balance == NULL || $balance == 'null' || $balance == null){ $balance = 0 ;}
        return (float)$balance;
    }
	
	public function get_user_available_balance_allcoin($uid,$coin_type) {
		$balance = $this->walletinfo($uid,"available_balance_".$coin_type);
		return $balance;
	}
	
	public function get_user_pending_balance_allcoin($uid,$coin_type) {
		$balance = $this->walletinfo($uid,"pending_received_balance_".$coin_type);
		return $balance;
	}
	
	public function get_user_address_allcoin($uid,$coin_type) {
		$address = $this->walletinfo($uid,"address_".$coin_type);
		$username = Auth::user()->username;
		$coininfo = Coin_info::get(strtoupper($coin_type));
		$coin_category = $coininfo->cointype;
		if($coininfo->api == 'eos' || $coininfo->api == 'xrp'){
			$address = $this->walletinfo($uid,"address_".$coin_type)."<br>".$this->walletinfo($uid,"address_desti_".$coin_type);

		}
		
		if($address == null || $address == '<br>'){
			if($coin_category == 'coin') { // 코인일때
				$address = $this->create_new_address($username,$coin_type); 
				if($coininfo->api == 'eos' || $coininfo->api == 'xrp'){
					$address_desti = explode('<br>',$address);
					DB::table('btc_users_addresses')->where('uid', Auth::user()->id)->update([
						'address_'.strtolower($coin_type) => $address_desti[0],
						'address_desti_'.strtolower($coin_type) => $address_desti[1],
					]);
				}else{
					$address = str_replace('<br>','',$address);
                    DB::table('btc_users_addresses')->where('uid', Auth::user()->id)->update([
						'address_'.strtolower($coin_type) => $address,
					]);
                }			
			} else if($coin_category == 'token') { // 토큰일때
			
				$address = $this->get_erc20_address($username); 

			    DB::table('btc_users_addresses')->where('uid',Auth::user()->id)->update([
					'address_'.strtolower($coin_type) => $address,
				]);	
						
			
			} 
		}else{
			$address = $address;	
		}
		
		return $address;
	}
	public function get_erc20_address($username){
		$eth_addr = $this->walletinfo(Auth::user()->id,"address_eth");
	
		if($eth_addr == null){
			// 이더리움 주소가 없는경우 이더리움 주소를 생성해주고, 이더리움 주소를 저장한다.	
	
			$eth_addr = $this->create_new_address($username,'eth'); 
			
			DB::table('btc_users_addresses')->where('uid',Auth::user()->id)->update([
				'address_eth' => $eth_addr,
			]);	
		}
		return $eth_addr;
	
	}
	
	//curl 통한 코인 주소값 생성
	private function create_new_address($username,$coin_type){ 
		$Settings = Settings::Settings();
		$url = $Settings->url_io."api/create_address.php?v=".time(); // Where you want to post data
		$coin_api_key = "sdafuyhoFicxdDvzhnvkewmnjkGE324dsSsvuzxcvb";
		$postdata = array(
			'userid' => $username,
			'coin_type' => $coin_type,
			'api_key' => $coin_api_key
		);
		$ch = curl_init();                    // Initiate cURL
	    curl_setopt($ch, CURLOPT_URL,$url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); // Define what you want to post
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
	    $output = curl_exec ($ch); // Execute
	    curl_close ($ch); // Close cURL handle  
		
		
		
		return $output;
		
	}
	
	//거래내역 코인별 구매당시 매수 수량 합
	public function buy_amt($symbol){
		$sum_amt = DB::table('btc_trades_COIN_btc')->where('buyer_uid',Auth::user()->id)->where('cointype',strtolower($symbol))->sum(DB::raw("contract_coin_amt"));

		return $sum_amt;
	}
	//거래내역 코인별 (매수수량 * 구매당시) 시세 합
	public function buy_total($symbol){
		$sum_total = DB::table('btc_trades_COIN_btc')->where('buyer_uid',Auth::user()->id)->where('cointype',strtolower($symbol))->sum(DB::raw("contract_coin_amt * buy_coin_price"));

		return $sum_total;
	}
    
    //okj 평균단가 초기화 적용 2019-12-09
	public function avg_trades_id($symbol){

		if(strtolower($symbol)=='che' or strtolower($symbol)=='mct' or strtolower($symbol)=='liv' or strtolower($symbol)=='tot' or strtolower($symbol)=='int' or strtolower($symbol)=='atm' or strtolower($symbol)=='bar' or strtolower($symbol)=='rma' or strtolower($symbol)=='brn' or strtolower($symbol)=='asn' or strtolower($symbol)=='val' or strtolower($symbol)=='dor' or strtolower($symbol)=='nap' or strtolower($symbol)=='mnu'){
			$pre_buy = DB::select(DB::raw("select `".strtolower($symbol)."` as coin_type FROM lock_precoin where email =(select email from html.users where id =".Auth::user()->id.")"));
			if($pre_buy){ $pre_buy_value = $pre_buy[0]->coin_type;}else{$pre_buy_value =0;}
		}else{
			$pre_buy_value = 0;
		}

		$avg_id = DB::select(DB::raw("select ifnull(max(sell_id),0) as result from 
									(
									select b.id as sell_id, b.contract_coin_amt, @num:=@num+b.contract_coin_amt as sum_value_sell
									from (select @num:=0) a, btc_trades_COIN_btc b
									where b.seller_uid =".Auth::user()->id." and b.cointype ='".strtolower($symbol)."'
									) c,
									(
									select b.id as buy_id, b.contract_coin_amt, @num2:=@num2+b.contract_coin_amt as sum_value_buy
									from (select @num2:=".$pre_buy_value.") a, btc_trades_COIN_btc b
									where b.buyer_uid =".Auth::user()->id." and b.cointype ='".strtolower($symbol)."'
									) d
									where c.sum_value_sell = d.sum_value_buy"));
		$rtn_avg_id = $avg_id[0]->result;

		return $rtn_avg_id;
	}
	//okj 거래내역 코인별 구매당시 매수 수량 합 NEW  2019-12-06
	public function buy_amt_new($symbol, $avg_id){
		$sum_amt = DB::table('btc_trades_COIN_btc')->where('buyer_uid',Auth::user()->id)->where('id','>',$avg_id)->where('cointype',strtolower($symbol))->sum(DB::raw("contract_coin_amt"));

		return $sum_amt;
	}
	//okj 거래내역 코인별 (매수수량 * 구매당시) 시세 합 NEW  2019-12-06
	public function buy_total_new($symbol, $avg_id){
		$sum_total = DB::table('btc_trades_COIN_btc')->where('buyer_uid',Auth::user()->id)->where('id','>',$avg_id)->where('cointype',strtolower($symbol))->sum(DB::raw("contract_coin_amt * buy_coin_price"));

		return $sum_total;
	}
	
	public function first_krw_io_history(){
		$cash = DB::table('btc_krw_io')->where('uid',Auth::user()->id)->orderBy('id','asc')->first(); 
		
		if(isset($cash)){
			$created = $cash->created;	
		}else{
			$created = null;	
		}
		
		return $created;	
	}
	
	public function krw_requested_or_not(){
		$count = DB::table('btc_krw_io')->whereIn('status',array('withdraw_request','deposite_request'))->where('uid',Auth::user()->id)->count();
		
		if($count == 0){
			return false;	
		}else{
			return true;
		}
	}
}
