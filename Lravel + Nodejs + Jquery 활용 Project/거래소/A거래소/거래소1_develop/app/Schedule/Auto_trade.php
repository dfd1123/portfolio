<?php

namespace App\Schedule;


use Illuminate\Support\Facades\Hash;

use DB;
use Settings;
use Coin_info;
use My_info;
use Trade;
use Trade_new;

class Auto_trade
{
    public static function auto_trading_usd() {
		$currency = 'USD';
		
    	$coins = DB::table('btc_coins')->select('btc_coins.symbol','btc_coins.decimal_'.strtolower($currency),'btc_auto_setting.*')->leftJoin('btc_auto_setting','btc_coins.api','=','btc_auto_setting.cointype')
    	->where('btc_coins.active','1')
    	->where('btc_coins.market','<>','sports')
		->where('btc_coins.symbol','<>',$currency)
    	->where(function ($query){
			$query->where('btc_coins.cointype','coin')->orwhere('btc_coins.cointype','token');
		})->get();
		foreach($coins as $coin){
			if($coin->switch == 0){
				echo $coin->cointype." is off\n";
				continue;	
			}
			$random_success = mt_rand($coin->success_min,$coin->success_max);
			$randnum_type = mt_rand(1,2);
			$random_time = mt_rand($coin->time_min,$coin->time_max);
			$randnum_amt = mt_rand($coin->amt_min,$coin->amt_max) * $coin->amt_decimal;
			$randnum_range = mt_rand($coin->range_min,$coin->range_max);
				
			
			if($randnum_type == 1){
				$type = 'buy';
			}else{
				$type = 'sell';
			} 
			if(1 == 1){
				sleep($random_time);
				$trade_coin_symbol = $coin->symbol;
				
				
				$user_current_cash_balance = My_info::get_user_balance_allcoin("5269",strtolower($currency));
				$current_coin_price = Coin_info::get_current_coin_price3($currency,$trade_coin_symbol,"coinmarketcap");
				
				$market = DB::table('btc_settings')->where('id',1)->first();
				
				
				if($type == 'buy'){

					$buy_coin_amt = $randnum_amt;
					
					$mul_current_range = bcmul($current_coin_price,$randnum_range,$coin->{'decimal_'.strtolower($currency)});
					$mul_reduce_current = bcmul($mul_current_range,0.01,$coin->{'decimal_'.strtolower($currency)});
					$buy_coin_price = bcadd($current_coin_price, $mul_reduce_current,$coin->{'decimal_'.strtolower($currency)});
							
					$trade_coin_total_amount = bcmul($buy_coin_amt , $buy_coin_price ,17);
					$fee = 	bcmul(bcmul($trade_coin_total_amount, $market->buy_comission, 17) , 0.01 ,17 );						
					$trade_coin_total_amount_with_fee = bcadd($trade_coin_total_amount , $fee, 17);
					
					if($trade_coin_total_amount_with_fee >= $user_current_cash_balance) {    
						echo "--------------------".$trade_coin_symbol." ?????? ?????? ??????--------------------\n";
						echo "????????????";
						echo "??????: ".$user_current_cash_balance."\n";
						echo "????????? ????????? : ".$trade_coin_total_amount."\n";
					}
					else if($buy_coin_amt == 0) {  $data = 2;   }
					else if($buy_coin_amt < 0) {  $data = 2;   }
					else if($buy_coin_price < 0) {  $data = 2;   }
					else if($buy_coin_price == 0) {  $data = 2;   }
					//else if(($buy_coin_price > $current_coin_price*1.2 || $buy_coin_price < $current_coin_price*0.8) && $type == 'buy') {  $data = 4;   } // ???????????? 10% ?????? ?????? ????????? ????????????
					else {// ?????? ?????? ?????? if	
						
						// ?????? ??????????????? ??????  last_id ??? ??????
						$last_id = DB::table('btc_ads_btc')->insertGetId([
							'uid' => "5269",
							'userid' => "sbtr01",
							'status' => 'OnProgress',
							'cointype' => strtolower($trade_coin_symbol),
							'type' => $type,
							'currency' => $currency,
							'trade_total_buy' => $trade_coin_total_amount_with_fee,
							'buy_COIN_amt' => $buy_coin_amt,
							'buy_COIN_amt_total' => $buy_coin_amt,
							'buy_coin_price' => $buy_coin_price,
							'created' => time(),
							'updated' => time(),
							'created_dt' => DB::raw('now()'),
							'updated_dt' => DB::raw('now()'),
						]);
					
						// ?????? ?????? + fee??? Pending ??????
						DB::table('btc_users_addresses')->where('uid',"5269")->decrement('pending_received_balance_'.strtolower($currency), $trade_coin_total_amount_with_fee);
						$trade_result =  Trade_new::Market_buy_execute_trade( $last_id );
						
						echo "--------------------".$trade_coin_symbol." ?????? ?????? ??????--------------------\n";
						echo "??????: ".$user_current_cash_balance."\n";
						echo "????????? ??? : ".$buy_coin_amt."\n";
						echo "????????? ?????? : ".$buy_coin_price."\n";
						echo "????????? ????????? : ".$trade_coin_total_amount."\n";
						echo "????????? : ".$fee."\n";
						echo "????????? ???????????? : ".$trade_coin_total_amount_with_fee."\n";	
					}
				}else{
					$sell_coin_amt = $randnum_amt;
					
					$mul_current_range = bcmul($current_coin_price,$randnum_range,$coin->{'decimal_'.strtolower($currency)});
					$mul_reduce_current = bcmul($mul_current_range,0.01,$coin->{'decimal_'.strtolower($currency)});
					$sell_coin_price = bcadd($current_coin_price, $mul_reduce_current,$coin->{'decimal_'.strtolower($currency)});
					
					$trade_sell_amt = bcmul($sell_coin_price , $sell_coin_amt,17);
					
					$coin_balance = My_info::get_user_balance_allcoin("5269", strtolower($trade_coin_symbol));
					
					if( $sell_coin_amt > $coin_balance) {
						 echo "--------------------".$trade_coin_symbol." ?????? ?????? ??????--------------------\n";
						echo "?????? ??????\n";
						echo "??????: ".$coin_balance."\n";
						echo "????????? ??? : ".$sell_coin_amt."\n";
					}
					else if($sell_coin_amt == 0) {  $data = 2;   }
					else if($sell_coin_amt < 0) {  $data = 2;   }
					else if($sell_coin_price < 0) {  $data = 2;   }
					else if($sell_coin_price == 0) {  $data = 2;   }
					else {// ?????? ?????? ?????? if
						
						// ?????? ??????????????? ??????  last_id ??? ??????
						$last_id = DB::table('btc_ads_btc')->insertGetId([
							'uid' => "5269",
							'userid' => "sbtr01",
							'status' => 'OnProgress',
							'cointype' => strtolower($trade_coin_symbol),
							'type' => $type,
							'currency' => $currency,
							'trade_total_buy' => $trade_sell_amt,
							'sell_COIN_amt' => $sell_coin_amt,
							'sell_COIN_amt_total' => $sell_coin_amt,
							'sell_coin_price' => $sell_coin_price,
							'created' => time(),
							'updated' => time(),
							'created_dt' => DB::raw('now()'),
							'updated_dt' => DB::raw('now()'),
							
						]);
		
						$update = DB::table('btc_users_addresses')->where('uid',"5269")->decrement('pending_received_balance_'.strtolower($trade_coin_symbol), $sell_coin_amt);
																												
						$trade_result = Trade_new::Market_sell_execute_trade( $last_id );
						
						echo "--------------------".$trade_coin_symbol." ?????? ?????? ??????--------------------\n";
						echo "??????: ".$coin_balance."\n";
						echo "????????? ??? : ".$sell_coin_amt."\n";
						echo "????????? ?????? : ".$sell_coin_price."\n";
						echo "????????? ????????? : ".$trade_sell_amt."\n";
					}
					
					
						
				}
				
				
			}else{
				echo "--------------------".$trade_coin_symbol." ?????? ?????? ?????? --------------------\n";	
				echo "????????? ??????";
			}
		}
    }
	 
	public static function auto_trading_btc() {
		$currency = 'BTC';
		
    	$coins = DB::table('btc_coins')->select('btc_coins.symbol','btc_coins.decimal_'.strtolower($currency),'btc_auto_setting.*')->leftJoin('btc_auto_setting','btc_coins.api','=','btc_auto_setting.cointype')
    	->where('btc_coins.active','1')
    	->where('btc_coins.market','<>','sports')
		->where('btc_coins.symbol','<>',$currency)
    	->where(function ($query){
			$query->where('btc_coins.cointype','coin')->orwhere('btc_coins.cointype','token');
		})->get();
		foreach($coins as $coin){
			if($coin->switch == 0){
				echo $coin->cointype." is off\n";
				continue;	
			}
			$random_success = mt_rand($coin->success_min,$coin->success_max);
			$randnum_type = mt_rand(1,2);
			$random_time = mt_rand($coin->time_min,$coin->time_max);
			$randnum_amt = mt_rand($coin->amt_min,$coin->amt_max) * $coin->amt_decimal;
			$randnum_range = mt_rand($coin->range_min,$coin->range_max);
				
			
			if($randnum_type == 1){
				$type = 'buy';
			}else{
				$type = 'sell';
			} 
			if(1 == 1){
				sleep($random_time);
				$trade_coin_symbol = $coin->symbol;
				
				
				$user_current_cash_balance = My_info::get_user_balance_allcoin("5269",strtolower($currency));
				$current_coin_price = Coin_info::get_current_coin_price3($currency,$trade_coin_symbol,"coinmarketcap");
				
				$market = DB::table('btc_settings')->where('id',1)->first();
				
				
				if($type == 'buy'){

					$buy_coin_amt = $randnum_amt;
					
					$mul_current_range = bcmul($current_coin_price,$randnum_range,$coin->{'decimal_'.strtolower($currency)});
					$mul_reduce_current = bcmul($mul_current_range,0.01,$coin->{'decimal_'.strtolower($currency)});
					$buy_coin_price = bcadd($current_coin_price, $mul_reduce_current,$coin->{'decimal_'.strtolower($currency)});
							
					$trade_coin_total_amount = bcmul($buy_coin_amt , $buy_coin_price ,17);
					$fee = 	bcmul(bcmul($trade_coin_total_amount, $market->buy_comission, 17) , 0.01 ,17 );						
					$trade_coin_total_amount_with_fee = bcadd($trade_coin_total_amount , $fee, 17);
					
					if($trade_coin_total_amount_with_fee >= $user_current_cash_balance) {    
						echo "--------------------".$trade_coin_symbol." ?????? ?????? ??????--------------------\n";
						echo "????????????";
						echo "??????: ".$user_current_cash_balance."\n";
						echo "????????? ????????? : ".$trade_coin_total_amount."\n";
					}
					else if($buy_coin_amt == 0) {  $data = 2;   }
					else if($buy_coin_amt < 0) {  $data = 2;   }
					else if($buy_coin_price < 0) {  $data = 2;   }
					else if($buy_coin_price == 0) {  $data = 2;   }
					//else if(($buy_coin_price > $current_coin_price*1.2 || $buy_coin_price < $current_coin_price*0.8) && $type == 'buy') {  $data = 4;   } // ???????????? 10% ?????? ?????? ????????? ????????????
					else {// ?????? ?????? ?????? if	
						
						// ?????? ??????????????? ??????  last_id ??? ??????
						$last_id = DB::table('btc_ads_btc')->insertGetId([
							'uid' => "5269",
							'userid' => "sbtr01",
							'status' => 'OnProgress',
							'cointype' => strtolower($trade_coin_symbol),
							'type' => $type,
							'currency' => $currency,
							'trade_total_buy' => $trade_coin_total_amount_with_fee,
							'buy_COIN_amt' => $buy_coin_amt,
							'buy_COIN_amt_total' => $buy_coin_amt,
							'buy_coin_price' => $buy_coin_price,
							'created' => time(),
							'updated' => time(),
							'created_dt' => DB::raw('now()'),
							'updated_dt' => DB::raw('now()'),
						]);
					
						// ?????? ?????? + fee??? Pending ??????
						DB::table('btc_users_addresses')->where('uid',"5269")->decrement('pending_received_balance_'.strtolower($currency), $trade_coin_total_amount_with_fee);
						$trade_result =  Trade_new::Market_buy_execute_trade( $last_id );
						
						echo "--------------------".$trade_coin_symbol." ?????? ?????? ??????--------------------\n";
						echo "??????: ".$user_current_cash_balance."\n";
						echo "????????? ??? : ".$buy_coin_amt."\n";
						echo "????????? ?????? : ".$buy_coin_price."\n";
						echo "????????? ????????? : ".$trade_coin_total_amount."\n";
						echo "????????? : ".$fee."\n";
						echo "????????? ???????????? : ".$trade_coin_total_amount_with_fee."\n";	
					}
				}else{
					$sell_coin_amt = $randnum_amt;
					
					$mul_current_range = bcmul($current_coin_price,$randnum_range,$coin->{'decimal_'.strtolower($currency)});
					$mul_reduce_current = bcmul($mul_current_range,0.01,$coin->{'decimal_'.strtolower($currency)});
					$sell_coin_price = bcadd($current_coin_price, $mul_reduce_current,$coin->{'decimal_'.strtolower($currency)});
					
					$trade_sell_amt = bcmul($sell_coin_price , $sell_coin_amt,17);
					
					$coin_balance = My_info::get_user_balance_allcoin("5269", strtolower($trade_coin_symbol));
					
					if( $sell_coin_amt > $coin_balance) {
						 echo "--------------------".$trade_coin_symbol." ?????? ?????? ??????--------------------\n";
						echo "?????? ??????\n";
						echo "??????: ".$coin_balance."\n";
						echo "????????? ??? : ".$sell_coin_amt."\n";
					}
					else if($sell_coin_amt == 0) {  $data = 2;   }
					else if($sell_coin_amt < 0) {  $data = 2;   }
					else if($sell_coin_price < 0) {  $data = 2;   }
					else if($sell_coin_price == 0) {  $data = 2;   }
					else {// ?????? ?????? ?????? if
						
						// ?????? ??????????????? ??????  last_id ??? ??????
						$last_id = DB::table('btc_ads_btc')->insertGetId([
							'uid' => "5269",
							'userid' => "sbtr01",
							'status' => 'OnProgress',
							'cointype' => strtolower($trade_coin_symbol),
							'type' => $type,
							'currency' => $currency,
							'trade_total_buy' => $trade_sell_amt,
							'sell_COIN_amt' => $sell_coin_amt,
							'sell_COIN_amt_total' => $sell_coin_amt,
							'sell_coin_price' => $sell_coin_price,
							'created' => time(),
							'updated' => time(),
							'created_dt' => DB::raw('now()'),
							'updated_dt' => DB::raw('now()'),
							
						]);
		
						$update = DB::table('btc_users_addresses')->where('uid',"5269")->decrement('pending_received_balance_'.strtolower($trade_coin_symbol), $sell_coin_amt);
																												
						$trade_result = Trade_new::Market_sell_execute_trade( $last_id );
						
						echo "--------------------".$trade_coin_symbol." ?????? ?????? ??????--------------------\n";
						echo "??????: ".$coin_balance."\n";
						echo "????????? ??? : ".$sell_coin_amt."\n";
						echo "????????? ?????? : ".$sell_coin_price."\n";
						echo "????????? ????????? : ".$trade_sell_amt."\n";
					}
					
					
						
				}
				
				
			}else{
				echo "--------------------".$trade_coin_symbol." ?????? ?????? ?????? --------------------\n";	
				echo "????????? ??????";
			}
		}
    }
  	
	public static function auto_trading_eth() {
		$currency = 'ETH';
		
    	$coins = DB::table('btc_coins')->select('btc_coins.symbol','btc_coins.decimal_'.strtolower($currency),'btc_auto_setting.*')->leftJoin('btc_auto_setting','btc_coins.api','=','btc_auto_setting.cointype')
    	->where('btc_coins.active','1')
    	->where('btc_coins.market','<>','sports')
		->where('btc_coins.symbol','<>',$currency)
    	->where(function ($query){
			$query->where('btc_coins.cointype','coin')->orwhere('btc_coins.cointype','token');
		})->get();
		foreach($coins as $coin){
			if($coin->switch == 0){
				echo $coin->cointype." is off\n";
				continue;	
			}
			$random_success = mt_rand($coin->success_min,$coin->success_max);
			$randnum_type = mt_rand(1,2);
			$random_time = mt_rand($coin->time_min,$coin->time_max);
			$randnum_amt = mt_rand($coin->amt_min,$coin->amt_max) * $coin->amt_decimal;
			$randnum_range = mt_rand($coin->range_min,$coin->range_max);
				
			
			if($randnum_type == 1){
				$type = 'buy';
			}else{
				$type = 'sell';
			} 
			if(1 == 1){
				sleep($random_time);
				$trade_coin_symbol = $coin->symbol;
				
				
				$user_current_cash_balance = My_info::get_user_balance_allcoin("5269",strtolower($currency));
				$current_coin_price = Coin_info::get_current_coin_price3($currency,$trade_coin_symbol,"coinmarketcap");
				
				$market = DB::table('btc_settings')->where('id',1)->first();
				
				
				if($type == 'buy'){

					$buy_coin_amt = $randnum_amt;
					
					$mul_current_range = bcmul($current_coin_price,$randnum_range,$coin->{'decimal_'.strtolower($currency)});
					$mul_reduce_current = bcmul($mul_current_range,0.01,$coin->{'decimal_'.strtolower($currency)});
					$buy_coin_price = bcadd($current_coin_price, $mul_reduce_current,$coin->{'decimal_'.strtolower($currency)});
							
					$trade_coin_total_amount = bcmul($buy_coin_amt , $buy_coin_price ,17);
					$fee = 	bcmul(bcmul($trade_coin_total_amount, $market->buy_comission, 17) , 0.01 ,17 );						
					$trade_coin_total_amount_with_fee = bcadd($trade_coin_total_amount , $fee, 17);
					
					if($trade_coin_total_amount_with_fee >= $user_current_cash_balance) {    
						echo "--------------------".$trade_coin_symbol." ?????? ?????? ??????--------------------\n";
						echo "????????????";
						echo "??????: ".$user_current_cash_balance."\n";
						echo "????????? ????????? : ".$trade_coin_total_amount."\n";
					}
					else if($buy_coin_amt == 0) {  $data = 2;   }
					else if($buy_coin_amt < 0) {  $data = 2;   }
					else if($buy_coin_price < 0) {  $data = 2;   }
					else if($buy_coin_price == 0) {  $data = 2;   }
					//else if(($buy_coin_price > $current_coin_price*1.2 || $buy_coin_price < $current_coin_price*0.8) && $type == 'buy') {  $data = 4;   } // ???????????? 10% ?????? ?????? ????????? ????????????
					else {// ?????? ?????? ?????? if	
						
						// ?????? ??????????????? ??????  last_id ??? ??????
						$last_id = DB::table('btc_ads_btc')->insertGetId([
							'uid' => "5269",
							'userid' => "sbtr01",
							'status' => 'OnProgress',
							'cointype' => strtolower($trade_coin_symbol),
							'type' => $type,
							'currency' => $currency,
							'trade_total_buy' => $trade_coin_total_amount_with_fee,
							'buy_COIN_amt' => $buy_coin_amt,
							'buy_COIN_amt_total' => $buy_coin_amt,
							'buy_coin_price' => $buy_coin_price,
							'created' => time(),
							'updated' => time(),
							'created_dt' => DB::raw('now()'),
							'updated_dt' => DB::raw('now()'),
						]);
					
						// ?????? ?????? + fee??? Pending ??????
						DB::table('btc_users_addresses')->where('uid',"5269")->decrement('pending_received_balance_'.strtolower($currency), $trade_coin_total_amount_with_fee);
						$trade_result =  Trade_new::Market_buy_execute_trade( $last_id );
						
						echo "--------------------".$trade_coin_symbol." ?????? ?????? ??????--------------------\n";
						echo "??????: ".$user_current_cash_balance."\n";
						echo "????????? ??? : ".$buy_coin_amt."\n";
						echo "????????? ?????? : ".$buy_coin_price."\n";
						echo "????????? ????????? : ".$trade_coin_total_amount."\n";
						echo "????????? : ".$fee."\n";
						echo "????????? ???????????? : ".$trade_coin_total_amount_with_fee."\n";	
					}
				}else{
					$sell_coin_amt = $randnum_amt;
					
					$mul_current_range = bcmul($current_coin_price,$randnum_range,$coin->{'decimal_'.strtolower($currency)});
					$mul_reduce_current = bcmul($mul_current_range,0.01,$coin->{'decimal_'.strtolower($currency)});
					$sell_coin_price = bcadd($current_coin_price, $mul_reduce_current,$coin->{'decimal_'.strtolower($currency)});
					
					$trade_sell_amt = bcmul($sell_coin_price , $sell_coin_amt,17);
					
					$coin_balance = My_info::get_user_balance_allcoin("5269", strtolower($trade_coin_symbol));
					
					if( $sell_coin_amt > $coin_balance) {
						 echo "--------------------".$trade_coin_symbol." ?????? ?????? ??????--------------------\n";
						echo "?????? ??????\n";
						echo "??????: ".$coin_balance."\n";
						echo "????????? ??? : ".$sell_coin_amt."\n";
					}
					else if($sell_coin_amt == 0) {  $data = 2;   }
					else if($sell_coin_amt < 0) {  $data = 2;   }
					else if($sell_coin_price < 0) {  $data = 2;   }
					else if($sell_coin_price == 0) {  $data = 2;   }
					else {// ?????? ?????? ?????? if
						
						// ?????? ??????????????? ??????  last_id ??? ??????
						$last_id = DB::table('btc_ads_btc')->insertGetId([
							'uid' => "5269",
							'userid' => "sbtr01",
							'status' => 'OnProgress',
							'cointype' => strtolower($trade_coin_symbol),
							'type' => $type,
							'currency' => $currency,
							'trade_total_buy' => $trade_sell_amt,
							'sell_COIN_amt' => $sell_coin_amt,
							'sell_COIN_amt_total' => $sell_coin_amt,
							'sell_coin_price' => $sell_coin_price,
							'created' => time(),
							'updated' => time(),
							'created_dt' => DB::raw('now()'),
							'updated_dt' => DB::raw('now()'),
							
						]);
		
						$update = DB::table('btc_users_addresses')->where('uid',"5269")->decrement('pending_received_balance_'.strtolower($trade_coin_symbol), $sell_coin_amt);
																												
						$trade_result = Trade_new::Market_sell_execute_trade( $last_id );
						
						echo "--------------------".$trade_coin_symbol." ?????? ?????? ??????--------------------\n";
						echo "??????: ".$coin_balance."\n";
						echo "????????? ??? : ".$sell_coin_amt."\n";
						echo "????????? ?????? : ".$sell_coin_price."\n";
						echo "????????? ????????? : ".$trade_sell_amt."\n";
					}
					
					
						
				}
				
				
			}else{
				echo "--------------------".$trade_coin_symbol." ?????? ?????? ?????? --------------------\n";	
				echo "????????? ??????";
			}
		}
	}
	
	public static function auto_trading_krw() {
		$currency = 'KRW';
		
    	$coins = DB::table('btc_coins')->select('btc_coins.market','btc_coins.symbol','btc_coins.decimal_'.strtolower($currency),'btc_auto_setting.*')->leftJoin('btc_auto_setting','btc_coins.api','=','btc_auto_setting.cointype')
    	->where('btc_coins.active','1')
		->where('btc_coins.symbol','<>',$currency)
    	->where(function ($query){
			$query->where('btc_coins.cointype','coin')->orwhere('btc_coins.cointype','token');
		})->get();
		foreach($coins as $coin){
			if($coin->switch == 0){
				echo $coin->cointype." is off\n";
				continue;	
			}
			$random_success = mt_rand($coin->success_min,$coin->success_max);
			//$randnum_type = mt_rand(1,2); //?????? ?????? ?????????
			$randnum_type = 1; // ?????????
			$random_time = mt_rand($coin->time_min,$coin->time_max);
			$randnum_amt = mt_rand($coin->amt_min,$coin->amt_max) * $coin->amt_decimal;
			$randnum_range = mt_rand($coin->range_min,$coin->range_max);
				
			
			if($randnum_type == 1){
				$type = 'buy';
			}else{
				$type = 'sell';
			} 
			if(1 == 1){
				sleep($random_time);
				$trade_coin_symbol = $coin->symbol;
				
				
				$user_current_cash_balance = My_info::get_user_balance_allcoin("5269",strtolower($currency));
                if ($coin->market == 'public') {
                    $current_coin_price = Coin_info::get_current_coin_price3($currency, $trade_coin_symbol, "coinmarketcap");
                }else{
					$current_coin_price = Coin_info::get_current_coin_price3($currency, $trade_coin_symbol, "trade");
				}
				$market = DB::table('btc_settings')->where('id',1)->first();
				
				$trade_coin_info = Coin_info::get_coin_info(strtolower($trade_coin_symbol));
				if($type == 'buy'){

					$buy_coin_amt = $randnum_amt;
					
					$mul_current_range = bcmul($current_coin_price,$randnum_range,$coin->{'decimal_'.strtolower($currency)});
					$mul_reduce_current = bcmul($mul_current_range,0.01,$coin->{'decimal_'.strtolower($currency)});
					$buy_coin_price = bcadd($current_coin_price, $mul_reduce_current,$coin->{'decimal_'.strtolower($currency)});
							
					$trade_coin_total_amount = bcmul($buy_coin_amt , $buy_coin_price ,17);
					$fee = 	bcmul(bcmul($trade_coin_total_amount, $market->buy_comission, 17) , 0.01 ,17 );						
					$trade_coin_total_amount_with_fee = bcadd($trade_coin_total_amount , $fee, 17);
					
					if($trade_coin_total_amount_with_fee >= $user_current_cash_balance) {    
						echo "--------------------".$trade_coin_symbol." ?????? ?????? ??????--------------------\n";
						echo "????????????";
						echo "??????: ".$user_current_cash_balance."\n";
						echo "????????? ????????? : ".$trade_coin_total_amount."\n";
					}
					else if($buy_coin_amt == 0) {  $data = 2;   }
					else if($buy_coin_amt < 0) {  $data = 2;   }
					else if($buy_coin_price < 0) {  $data = 2;   }
					else if($buy_coin_price == 0) {  $data = 2;   }
					else if($trade_coin_info->market == 'sports' && $buy_coin_price <= 20) {  $data = 6;   }
					//else if(($buy_coin_price > $current_coin_price*1.2 || $buy_coin_price < $current_coin_price*0.8) && $type == 'buy') {  $data = 4;   } // ???????????? 10% ?????? ?????? ????????? ????????????
					else {// ?????? ?????? ?????? if	
						DB::beginTransaction();
						try {
							// ?????? ??????????????? ??????  last_id ??? ??????
							$last_id = DB::table('btc_ads_btc')->insertGetId([
								'uid' => "5269",
								'userid' => "sbtr01",
								'status' => 'OnProgress',
								'cointype' => strtolower($trade_coin_symbol),
								'type' => $type,
								'currency' => $currency,
								'trade_total_buy' => $trade_coin_total_amount_with_fee,
								'buy_COIN_amt' => $buy_coin_amt,
								'buy_COIN_amt_total' => $buy_coin_amt,
								'buy_coin_price' => $buy_coin_price,
								'created' => time(),
								'updated' => time(),
								'created_dt' => DB::raw('now()'),
								'updated_dt' => DB::raw('now()'),
							]);
						
							// ?????? ?????? + fee??? Pending ??????
							DB::table('btc_users_addresses')->where('uid',"5269")->decrement('pending_received_balance_'.strtolower($currency), $trade_coin_total_amount_with_fee);
							$commit_flag = DB::commit();
						} catch (Exception $e) {
							$rollback_flag = DB::rollback();
							info("rollback : ".$rollback_flag);

							info($e);
						}
						$trade_result =  Trade_new::Market_buy_execute_trade( $last_id );
						
						echo "--------------------".$trade_coin_symbol." ?????? ?????? ??????--------------------\n";
						echo "??????: ".$user_current_cash_balance."\n";
						echo "????????? ??? : ".$buy_coin_amt."\n";
						echo "????????? ?????? : ".$buy_coin_price."\n";
						echo "????????? ????????? : ".$trade_coin_total_amount."\n";
						echo "????????? : ".$fee."\n";
						echo "????????? ???????????? : ".$trade_coin_total_amount_with_fee."\n";	
					}
				}else{
					$sell_coin_amt = $randnum_amt;
					
					$mul_current_range = bcmul($current_coin_price,$randnum_range,$coin->{'decimal_'.strtolower($currency)});
					$mul_reduce_current = bcmul($mul_current_range,0.01,$coin->{'decimal_'.strtolower($currency)});
					$sell_coin_price = bcadd($current_coin_price, $mul_reduce_current,$coin->{'decimal_'.strtolower($currency)});
					
					$trade_sell_amt = bcmul($sell_coin_price , $sell_coin_amt,17);
					
					$coin_balance = My_info::get_user_balance_allcoin("5269", strtolower($trade_coin_symbol));
					
					if( $sell_coin_amt > $coin_balance) {
						 echo "--------------------".$trade_coin_symbol." ?????? ?????? ??????--------------------\n";
						echo "?????? ??????\n";
						echo "??????: ".$coin_balance."\n";
						echo "????????? ??? : ".$sell_coin_amt."\n";
					}
					else if($sell_coin_amt == 0) {  $data = 2;   }
					else if($sell_coin_amt < 0) {  $data = 2;   }
					else if($sell_coin_price < 0) {  $data = 2;   }
					else if($sell_coin_price == 0) {  $data = 2;   }
					else if($trade_coin_info->market == 'sports' && $sell_coin_price <= 20) {  $data = 6;   }
					else {// ?????? ?????? ?????? if
						DB::beginTransaction();

						try {
							// ?????? ??????????????? ??????  last_id ??? ??????
							$last_id = DB::table('btc_ads_btc')->insertGetId([
								'uid' => "5269",
								'userid' => "sbtr01",
								'status' => 'OnProgress',
								'cointype' => strtolower($trade_coin_symbol),
								'type' => $type,
								'currency' => $currency,
								'trade_total_buy' => $trade_sell_amt,
								'sell_COIN_amt' => $sell_coin_amt,
								'sell_COIN_amt_total' => $sell_coin_amt,
								'sell_coin_price' => $sell_coin_price,
								'created' => time(),
								'updated' => time(),
								'created_dt' => DB::raw('now()'),
								'updated_dt' => DB::raw('now()'),
								
							]);
			
							$update = DB::table('btc_users_addresses')->where('uid',"5269")->decrement('pending_received_balance_'.strtolower($trade_coin_symbol), $sell_coin_amt);
							$commit_flag = DB::commit();
							//info("commit ??? : ".$commit_flag);
						} catch (Exception $e) {
							$rollback_flag = DB::rollback();
							info("rollback : ".$rollback_flag);

							info($e);
						}																						
						$trade_result = Trade_new::Market_sell_execute_trade( $last_id );
						
						echo "--------------------".$trade_coin_symbol." ?????? ?????? ??????--------------------\n";
						echo "??????: ".$coin_balance."\n";
						echo "????????? ??? : ".$sell_coin_amt."\n";
						echo "????????? ?????? : ".$sell_coin_price."\n";
						echo "????????? ????????? : ".$trade_sell_amt."\n";
					}
					
					
						
				}
				
				
			}else{
				echo "--------------------".$trade_coin_symbol." ?????? ?????? ?????? --------------------\n";	
				echo "????????? ??????";
			}
		}
    }

	public static function auto_cancel_new() {
		DB::table('btc_ads_btc')->where('created','<',DB::raw('UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR)'))->where('userid','sbtr01')->where('status','OnProgress')
		->where(function ($query){
			$query->where('buy_COIN_amt','>','0')->orwhere('sell_COIN_amt','>','0');
		})
		->update([
			"status" => 'CancelRequest',
			"updated" => time(),
			"updated_dt" => DB::raw('now()'),
		]);

		$market_cance_buy_result = Trade_new::Market_cancel_buy_order();
		$market_cance_sell_result = Trade_new::Market_cancel_sell_order();
	}
	
	public static function auto_recovery(){
		Trade_new::Market_cancel_buy_order();
		Trade_new::Market_cancel_sell_order();
		info("?????? ?????????");
	}

  	public static function test_hash() {
  		echo "d586b597b881875190ebfccfe49250e712c17e234702c6f667a78e7ecf06f191";
	}
}