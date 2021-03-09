<?php

namespace App\Classes;

use DB;
use Auth;
use Common;

class Trade_new {

    function Market_buy_execute_trade( $ads_id ){
        //?? $krw_io_id 정의 안됨. 마진 먹는 관리자 정의 안됨. pending_history 테이블은 존재하지도 않음.

        $krw_io_id = '';

        $log_text = " buy Trade No. $ads_id Execute Start <br>";
        
        
        $btc_ads = DB::table('btc_ads_btc')->where('id', $ads_id)->first();
		
        $market = DB::table('btc_settings')->where('id', session('market_type'))->first();

        $response_buy = array(
            "uid" => '',
            "type" => 'buy',
            "cointype" => '',
            "amount" => 0,
        );

        $response_sell = array(
            "uid" => '',
            "type" => 'sell',
            "cointype" => '',
            "amount" => 0,
        );

        if($btc_ads != null){
            $trade_coin_symbol = $btc_ads->cointype; // 코인종류
            $buy_remain_amt = $btc_ads->buy_COIN_amt; // 구매잔량
            $process_currency = $btc_ads->currency; // 사용코인 종류
            
            $btc_ads2 = DB::table('btc_ads_btc')->where('type','sell')->where('status','OnProgress')->where('cointype',$trade_coin_symbol)
            ->where('currency',$process_currency)->where('sell_COIN_amt','>',0)->where('sell_coin_price','<=',$btc_ads->buy_coin_price)
            ->orderByRaw('sell_coin_price ASC, created ASC')->get();

            foreach($btc_ads2 as $btc_ad2){
                // 원래 있던 매물을 기준으로 거래하는 것이므로 판매건 기준으로 가격을 책정 (더 비싸게 구매한다고 요청했어도 판매건 가격이 낮으면 더 저가에 거래 체결 )	
                $log_text = $log_text."<br>\n";
                $log_text = $log_text."-- 매도건 거래번호 : ".$btc_ad2->id."<br>\n";

                $buy_ad_id = $btc_ads->id; // 매수건 거래번호
                $sell_ad_id = $btc_ad2->id; // 매도건 거래번호
                $buyer_uid =  $btc_ads->uid; // 구매자 uid
                $seller_uid = $btc_ad2->uid;  //판매자 uid
                $buyer_username =  $btc_ads->userid;  // 구매자 username
                $seller_username = $btc_ad2->userid; // 판매자 username
                $buy_COIN_amt = $buy_remain_amt;// 구매 잔량(매수량)
                $sell_COIN_amt = $btc_ad2->sell_COIN_amt; // 매도량
                
                
                
                $buy_coin_price = $btc_ad2->sell_coin_price;// 매수 되어질 가격
                $buy_coin_price_original = $btc_ads->buy_coin_price;// 매수자가 던진 매수가격
                $sell_coin_price = $btc_ad2->sell_coin_price;// 매도 되어질 가격
                
                $buy_COIN_amt_finished = 0; //체결된 양
                $sell_COIN_amt_finished = 0; //체결된양
				
										
                $buy_COIN_amt_remain = 0; // 체결되고 남는양
                $sell_COIN_amt_remain = 0; // 체결되고 남는양
                $contract_coin_amt = 0; // 체결된 코인

                if( $buy_COIN_amt == $sell_COIN_amt ) { // 매도량과 매수량이 같을 때
                    $buy_COIN_amt_finished = bcadd($btc_ads->buy_COIN_amt_finished , $buy_COIN_amt,8); // buy_COIN_amt 만큼 모두 거래
                    $sell_COIN_amt_finished = bcadd($btc_ad2->sell_COIN_amt_finished , $buy_COIN_amt,8);
                    $buy_COIN_amt_remain = 0;
                    $sell_COIN_amt_remain = 0;
                    $contract_coin_amt = $buy_COIN_amt;

                } else if( $buy_COIN_amt > $sell_COIN_amt ) { // 매수량이 클때
                    
                    $buy_COIN_amt_finished =  bcadd($btc_ads->buy_COIN_amt_finished , $sell_COIN_amt,8); // sell_COIN_amt 만큼밖에 못사기 떄문
                    $sell_COIN_amt_finished = bcadd($btc_ad2->sell_COIN_amt_finished , $sell_COIN_amt,8);
                    $buy_COIN_amt_remain = bcsub($buy_COIN_amt , $sell_COIN_amt,8);
                    $sell_COIN_amt_remain = 0;
                    $contract_coin_amt = $sell_COIN_amt;

                } else if( $buy_COIN_amt < $sell_COIN_amt ) { // 매도량이 클때

                    $buy_COIN_amt_finished =  bcadd($btc_ads->buy_COIN_amt_finished , $buy_COIN_amt,8); // buy_COIN_amt 만큼 모두 거래
                    $sell_COIN_amt_finished = bcadd($btc_ad2->sell_COIN_amt_finished , $buy_COIN_amt,8);
                    $buy_COIN_amt_remain = 0;
                    $sell_COIN_amt_remain = bcsub($sell_COIN_amt , $buy_COIN_amt,8);
                    $contract_coin_amt = $buy_COIN_amt;

                }

                $log_text = $log_text." ** . buy_COIN_amt : ".$buy_COIN_amt.". <br>\n";
                $log_text = $log_text." ** . sell_COIN_amt : ".$sell_COIN_amt.". <br>\n";
                $log_text = $log_text." ** . buy_COIN_amt_finished : ".$buy_COIN_amt_finished.". <br>\n";
                $log_text = $log_text." ** . sell_COIN_amt_finished : ".$sell_COIN_amt_finished.". <br>\n";
                $log_text = $log_text." ** . buy_COIN_amt_remain : ".$buy_COIN_amt_remain.". <br>\n";
                $log_text = $log_text." ** . sell_COIN_amt_remain : ".$sell_COIN_amt_remain.". <br>\n";
                $log_text = $log_text." ** . contract_coin_amt : ".$contract_coin_amt.". <br>\n";	

                // 매도 주문을 업데이트 해준다.
                $response_sell = $this->update_ad_sell($sell_ad_id, $contract_coin_amt, $sell_COIN_amt_remain);

                // 매수 주문을 업데이트 해준다.
                $response_buy = $this->update_ad_buy($buy_ad_id, $contract_coin_amt, $buy_COIN_amt_remain);

                $trade_pay_amt_sell = bcmul($contract_coin_amt  , $sell_coin_price, 17)  ; // 매도자 기준 거래 금액 
                $trade_pay_amt_buy = bcmul($contract_coin_amt , $buy_coin_price, 17) ; // 매수자 기준 거래 금액 
                $trade_pay_amt_buy_original = bcmul($contract_coin_amt , $buy_coin_price_original, 17) ; // 매수자 기준 거래 금액 

                $sell_trade_fee = bcmul(bcmul($trade_pay_amt_sell , $market->sell_comission, 17) , 0.01 ,17 ) ; // 판매 수수료
                $buy_trade_fee = bcmul(bcmul($trade_pay_amt_buy , $market->buy_comission, 17) , 0.01 ,17 ) ; // 구매 수수료	
                $buy_trade_fee_original = bcmul(bcmul($trade_pay_amt_buy_original , $market->buy_comission ,17), 0.01 ,17 ) ; // 구매 수수료	
                
                //$sell_trade_fee = 0; // 판매 수수료
                //$buy_trade_fee = 0; // 구매 수수료
                
                $trade_pay_amt_sell_with_fee = bcsub($trade_pay_amt_sell , $sell_trade_fee,17); // 수수료 차감 판매 금액
                $trade_pay_amt_buy_with_fee = bcadd($trade_pay_amt_buy , $buy_trade_fee,17); // 수수료 포함 구매금액
                $trade_pay_amt_buy_with_fee_original = bcadd($trade_pay_amt_buy_original , $buy_trade_fee_original,17); // 수수료 포함 구매금액
                $trade_pay_amt_buy_with_fee_minus = bcmul($trade_pay_amt_buy_with_fee , '-1',17) ; // 매수자 기준 거래 금액	
                

                $trade_margin = bcadd($sell_trade_fee , $buy_trade_fee, 17);  // 본 거래의 수수료 수익				

                $log_text = $log_text." ** . 매도 잔량 : ".$sell_COIN_amt_remain.". <br>\n";
                $log_text = $log_text." ** . 매수 잔량 : ".$buy_COIN_amt_remain.". <br>\n";
                $log_text = $log_text." ** . 거래체결수량 : ".$contract_coin_amt.". <br>\n";

                $log_text = $log_text." ** . 매도자 기준 거래금액 : ".$trade_pay_amt_sell.". <br>\n";	
                $log_text = $log_text." ** . 매수자 기준 거래금액 : ".$trade_pay_amt_buy.". <br>\n";	
                $log_text = $log_text." ** . 판매 수수료 : ".$sell_trade_fee.". <br>\n";
                $log_text = $log_text." ** . 구매 수수료 : ".$buy_trade_fee.". <br>\n";
                $log_text = $log_text." ** . 수수료 차감 판매 금액 : ".$trade_pay_amt_buy.". <br>\n";
                $log_text = $log_text." ** . 수수료 포함 구매금액 : ".$trade_pay_amt_buy_with_fee.". <br>\n";						

										
                                    
                $log_text = $log_text." (0). 매도자의 코인을 매수자에게 보낸다. <br>\n";
                    $tx_id = $this->transfer_COIN ($seller_username, $buyer_username, $contract_coin_amt , $trade_coin_symbol ); 

                $log_text = $log_text." (1). 매수자에게서  ".$trade_pay_amt_buy_with_fee_minus." 만큼 거래금액으로 차감하고 <br>\n";
                    $this->write_usd_io_history($buyer_uid, "buy_COIN", "confirmed", $trade_pay_amt_buy_with_fee_minus , "ads" , $buy_ad_id, "코인 매수",$process_currency);

                $log_text = $log_text." (2). 매수자의 pending 금액에  ".$trade_pay_amt_buy_with_fee." 만큼 더해준다. <br>\n";
                    $this->remove_pending_usd($buyer_uid, $trade_pay_amt_buy_with_fee_original,strtolower($process_currency) );
                    
                $log_text = $log_text." (3). 매도자의 Coin Pending량에서 거래량 만큼인  ".$contract_coin_amt."을 더해준다. <br>\n";					
                    $this->remove_pending_COIN($seller_uid, $contract_coin_amt,$trade_coin_symbol, 'coin sell-1-'.$sell_ad_id );
                    
                $log_text = $log_text." (4). 매도자에게  판매금액에서 수수료를 차감한 금액인 ".$trade_pay_amt_sell_with_fee." 만큼 준다. <br>\n";
                    $this->write_usd_io_history($seller_uid, "sell_COIN", "confirmed", $trade_pay_amt_sell_with_fee , "ads" , $sell_ad_id, "코인 매도",$process_currency );
                    
                $log_text = $log_text." (5). 구매수수료 ".$buy_trade_fee." 를 admin user에게 준다. <br>\n";
                    $this->write_usd_io_history('sbtr01', "margin", "confirmed", $buy_trade_fee , "ads" , $buy_ad_id, "구매수수료" ,$process_currency);
                    
                $log_text = $log_text." (6). 판매수수료 ".$sell_trade_fee." 를 admin user에게 준다. <br>\n";
                    $this->write_usd_io_history('sbtr01', "margin", "confirmed", $sell_trade_fee , "ads" , $sell_ad_id, "판매수수료" ,$process_currency);
                                            
                $log_text = $log_text." (8). 거래기록을 등록한다. <br>\n";
                    $this->write_trade_history($buyer_uid, $seller_uid, $buyer_username, $seller_username, $buy_ad_id, $sell_ad_id,  $contract_coin_amt, $buy_coin_price, $sell_coin_price, 0,  $trade_pay_amt_buy, $trade_pay_amt_sell,"0", "0", $trade_margin, $tx_id, $krw_io_id,"",$process_currency,$trade_coin_symbol,"buy" );
                
                $log_text = $log_text." (7). 시장가격(최종 거래가격)을 변경한다. <br>\n";
                $this->update_market_price($buy_coin_price, $contract_coin_amt, $process_currency, $trade_coin_symbol);

                    


                $buy_remain_amt = bcsub($buy_remain_amt ,$contract_coin_amt,8);											
				if($buy_remain_amt  <= 0) break; // 구매잔량이 0이면 판매건 찾기 끝냄
            }
        }

        $response = array(
            "response_buy" => $response_buy,
            "response_sell" => $response_sell,
        );

        return $response;
    }

    function Market_sell_execute_trade( $ads_id ){ // 판매건 단건에 대한 매칭을 처리한다.	
        
        $krw_io_id = '';

        $log_text = " sell Trade No. $ads_id Execute Start <br>";	
        

        $btc_ads = DB::table('btc_ads_btc')->where('id', $ads_id)->first();
		$process_currency = $btc_ads->currency; // 사용코인 종류
        $market = DB::table('btc_settings')->where('id', session('market_type'))->first();
		
        $response_buy = array(
            "uid" => '',
            "type" => 'buy',
            "cointype" => '',
            "amount" => 0,
        );

        $response_sell = array(
            "uid" => '',
            "type" => 'sell',
            "cointype" => '',
            "amount" => 0,
        );
    
        if($btc_ads != null){
            $log_text = $log_text."\n<br>";
            $log_text = $log_text."매도건 거래번호 : ".$btc_ads->id."\n<br>";
            $log_text = $log_text." [ 매도잔량 : ".$btc_ads->sell_COIN_amt." ]"."\n<br>";

            $trade_coin_symbol = $btc_ads->cointype; // 코인종류
            $sell_remain_amt = $btc_ads->sell_COIN_amt; // 판매잔량

            // 구매 건들중에 구매 잔량이 0보다 크고 진행중인 건을 금액 큰것부터 가져온다.
            $btc_ads2 = DB::table('btc_ads_btc')->where('type','buy')->where('status','OnProgress')->where('cointype',$trade_coin_symbol)
            ->where('currency',$process_currency)->where('buy_COIN_amt','>',0)->where('buy_coin_price','>=',$btc_ads->sell_coin_price)
            ->orderByRaw('buy_coin_price DESC, created ASC')->get();
            
            foreach($btc_ads2 as $btc_ad2){
                // 원래 있던 매물을 기준으로 거래하는 것이므로 구매건 기준으로 가격을 책정 (더 싸게 판매한다고 요청했어도 구매건 가격이 높으면 더 고가에 거래 체결 )	
                $log_text = $log_text."<br>\n";
                $log_text = $log_text."-- 매수건 거래번호 : ".$btc_ad2->id."<br>\n";
                
                $sell_ad_id = $btc_ads->id; // 매수건 거래번호
                $buy_ad_id = $btc_ad2->id; // 매도건 거래번호
                $seller_uid =  $btc_ads->uid; // 구매자 uid
                $buyer_uid = $btc_ad2->uid;  //판매자 uid
                $seller_username =  $btc_ads->userid;  // 구매자 username
                $buyer_username = $btc_ad2->userid; // 판매자 username
                $sell_COIN_amt = $sell_remain_amt;// 판매 잔량(매도량)
                $buy_COIN_amt = $btc_ad2->buy_COIN_amt; // 매수량								

                
                $sell_coin_price = $btc_ad2->buy_coin_price;// 매수가격
                $buy_coin_price = $btc_ad2->buy_coin_price;// 매도가격
                
                $buy_COIN_amt_finished = 0;
                $sell_COIN_amt_finished = 0;							
                $buy_COIN_amt_remain = 0;
                $sell_COIN_amt_remain = 0;
                $contract_coin_amt = 0;

                if( $sell_COIN_amt == $buy_COIN_amt ) { // 매도량과 매수량이 같을 때
                    $sell_COIN_amt_finished = bcadd($btc_ads->sell_COIN_amt_finished , $sell_COIN_amt,8); // sell_COIN_amt 만큼 모두 거래
                    $buy_COIN_amt_finished = bcadd($btc_ad2->buy_COIN_amt_finished , $sell_COIN_amt,8);
                    $sell_COIN_amt_remain = 0;
                    $buy_COIN_amt_remain = 0;
                    $contract_coin_amt = $sell_COIN_amt;

                } else if( $sell_COIN_amt > $buy_COIN_amt ) { // 매도량이 클때
                    
                    $sell_COIN_amt_finished =  bcadd($btc_ads->sell_COIN_amt_finished , $buy_COIN_amt,8); // buy_COIN_amt 만큼만 거래
                    $buy_COIN_amt_finished = bcadd($btc_ad2->buy_COIN_amt_finished , $buy_COIN_amt,8);
                    $sell_COIN_amt_remain = bcsub($sell_COIN_amt , $buy_COIN_amt,8);
                    $buy_COIN_amt_remain = 0;
                    $contract_coin_amt = $buy_COIN_amt;

                } else if( $sell_COIN_amt < $buy_COIN_amt ) { // 매수량이 클때

                    $sell_COIN_amt_finished =  bcadd($btc_ads->sell_COIN_amt_finished , $sell_COIN_amt,8); // sell_COIN_amt 만큼만 거래
                    $buy_COIN_amt_finished = bcadd($btc_ad2->buy_COIN_amt_finished , $sell_COIN_amt,8);
                    $sell_COIN_amt_remain = 0;
                    $buy_COIN_amt_remain = bcsub($buy_COIN_amt , $sell_COIN_amt,8);
                    $contract_coin_amt = $sell_COIN_amt;

                }

                // 매도 주문을 업데이트 해준다.
                $response_sell = $this->update_ad_sell($sell_ad_id, $contract_coin_amt, $sell_COIN_amt_remain);

                // 매수 주문을 업데이트 해준다.
                $response_buy = $this->update_ad_buy($buy_ad_id, $contract_coin_amt, $buy_COIN_amt_remain);	
                
                $trade_pay_amt_sell = bcmul($contract_coin_amt , $sell_coin_price,17 ) ; // 매도자 기준 거래 금액 
                $trade_pay_amt_buy = bcmul($contract_coin_amt , $buy_coin_price,17) ; // 매수자 기준 거래 금액 
                

                $sell_trade_fee = bcmul(bcmul($trade_pay_amt_sell , $market->sell_comission,17) , 0.01 ,17 ) ; // 판매 수수료
                $buy_trade_fee = bcmul(bcmul($trade_pay_amt_buy , $market->buy_comission,17) , 0.01 ,17 ) ; // 구매 수수료	
                
                
                $trade_pay_amt_sell_with_fee = bcsub($trade_pay_amt_sell , $sell_trade_fee,17); // 수수료 차감 판매 금액
                $trade_pay_amt_buy_with_fee = bcadd( $trade_pay_amt_buy , $buy_trade_fee,17); // 수수료 포함 구매금액
                $trade_pay_amt_buy_with_fee_minus = bcmul($trade_pay_amt_buy_with_fee, '-1',17) ; // 매수자 기준 거래 금액	
                
                $trade_margin = 	bcadd($sell_trade_fee , $buy_trade_fee,17);  // 본 거래의 수수료 수익		
       
                $log_text = $log_text." (0). 매도자의 코인을 매수자에게 보낸다. <br>\n";
                    $tx_id = $this->transfer_COIN ($seller_username, $buyer_username, $contract_coin_amt , $trade_coin_symbol ); 

                $log_text = $log_text." (1). 매수자에게서  ".$trade_pay_amt_buy_with_fee_minus." 만큼 거래금액으로 차감하고 <br>\n";
                    $this->write_usd_io_history($buyer_uid, "buy_COIN", "confirmed", $trade_pay_amt_buy_with_fee_minus , "ads" , $buy_ad_id, "코인 매수" ,$process_currency);

                $log_text = $log_text." (2). 매수자의 pending 금액에  ".$trade_pay_amt_buy_with_fee." 만큼 더해준다. <br>\n";
                    $this->remove_pending_usd($buyer_uid, $trade_pay_amt_buy_with_fee, strtolower($process_currency));
                    
                    
                    
                $log_text = $log_text." (3). 매도자의 Coin Pending량에서 거래량 만큼인  ".$contract_coin_amt."을 더해준다. <br>\n";					
                    $this->remove_pending_COIN($seller_uid, $contract_coin_amt,$trade_coin_symbol , 'coin sell-2-'.$sell_ad_id);
                    
                $log_text = $log_text." (4). 매도자에게  판매금액에서 수수료를 차감한 금액인 ".$trade_pay_amt_sell_with_fee." 만큼 준다. <br>\n";
                    $this->write_usd_io_history($seller_uid, "sell_COIN", "confirmed", $trade_pay_amt_sell_with_fee , "ads" , $sell_ad_id, "코인 매도",$process_currency );
                    
                $log_text = $log_text." (5). 구매수수료 ".$buy_trade_fee." 를 admin user에게 준다. <br>\n";
                    $this->write_usd_io_history("sbtr01", "margin", "confirmed", $buy_trade_fee , "ads" , $buy_ad_id, "구매수수료",$process_currency );
                    
                $log_text = $log_text." (6). 판매수수료 ".$sell_trade_fee." 를 admin user에게 준다. <br>\n";
                    $this->write_usd_io_history("sbtr01", "margin", "confirmed", $sell_trade_fee , "ads" , $sell_ad_id, "판매수수료",$process_currency );
                
				$log_text = $log_text."(8). 거래기록을 등록한다. <br>\n";
                    $this->write_trade_history($buyer_uid, $seller_uid, $buyer_username, $seller_username, $buy_ad_id, $sell_ad_id,  $contract_coin_amt, $buy_coin_price, $sell_coin_price, 0,  $trade_pay_amt_buy, $trade_pay_amt_sell,"0", "0", $trade_margin, $tx_id, $krw_io_id,"",$process_currency,$trade_coin_symbol,"sell" );
				                            
                $log_text = $log_text." (7). 시장가격(최종 거래가격)을 변경한다. <br>\n";
                $this->update_market_price($buy_coin_price, $contract_coin_amt, $process_currency, $trade_coin_symbol);


                $sell_remain_amt = bcsub($sell_remain_amt ,$contract_coin_amt,8);											
				if($sell_remain_amt  <= 0) break; // 구매잔량이 0이면 판매건 찾기 끝냄		
            }
        }

        //file_put_contents("/storage/".date("Y-m-d")."_sell_minor_".$trade_coin_symbol."_".$ads_id.".log", $log_text, FILE_APPEND );
        $response = array(
            "response_buy" => $response_buy,
            "response_sell" => $response_sell,
        );

        return $response;
    
    } // Market_sell_execute_trade 펑션 끝

    function update_ad_sell($sell_ad_id, $sell_COIN_amt_finished, $sell_COIN_amt_remain){ //매도 주문을 업데이트
        
        $ads = DB::table('btc_ads_btc')->where('id', $sell_ad_id)->first();

        if($ads != null){
            $response = array(
                "uid" => $ads->uid,
                "type" => 'sell',
                "cointype" => $ads->cointype,
                "amount" => $sell_COIN_amt_finished,
            );
        }else{
            $response = array(
                "uid" => '',
                "type" => 'sell',
                "cointype" => '',
                "amount" => 0,
            );
        }

        
        DB::table('btc_ads_btc')->where('id', $sell_ad_id)->update([
        	'sell_COIN_amt_finished' => DB::raw('sell_COIN_amt_finished + '.$sell_COIN_amt_finished ),
            'sell_COIN_amt' => $sell_COIN_amt_remain,
            'trade_percentage' => DB::raw('round( (sell_COIN_amt_total - (sell_COIN_amt_total - sell_COIN_amt_finished) ) * 100 / sell_COIN_amt_total )'),
        ]);

        return  $response;
    }

    function update_ad_buy($buy_ad_id, $buy_COIN_amt_finished, $buy_COIN_amt_remain){

        $ads = DB::table('btc_ads_btc')->where('id', $buy_ad_id)->first();

        if($ads != null){
            $response = array(
                "uid" => $ads->uid,
                "type" => 'buy',
                "cointype" => $ads->cointype,
                "amount" => $buy_COIN_amt_finished,
            );
        }else{
            $response = array(
                "uid" => '',
                "type" => 'buy',
                "cointype" => '',
                "amount" => 0,
            );
        }

        DB::table('btc_ads_btc')->where('id', $buy_ad_id)->update([
        	'buy_COIN_amt_finished' => DB::raw('buy_COIN_amt_finished + '.$buy_COIN_amt_finished),
            'buy_COIN_amt' => $buy_COIN_amt_remain,
        	'trade_percentage' => DB::raw('round( (buy_COIN_amt_total - (buy_COIN_amt_total - buy_COIN_amt_finished) ) * 100 / buy_COIN_amt_total )'),
        ]);

        return  $response;
    }

    function transfer_COIN( $from_username, $to_username, $amount ,$cointype ){
            
    
            // 코인 전송함수 개발 필요
            //$tx = send_Crescentcoin_internal( trim($from_username), trim($to_username) , (float) $amount);
            
            $r1 = $this->minus_coin_balance ($cointype, $from_username, $amount  ) ; 
            $r2 = $this->add_coin_balance ($cointype, $to_username, $amount) ; 
            
            if($r1 && $r2) {
                
                $time = time();
                $tx = "trade from ".$from_username." to ".$to_username."->".$amount.$cointype.Common::randomHash();
                // 여기에 tx 이력 넣어야함	
                $result = $this->insert_transaction($cointype,$from_username,$to_username,"trade",$amount,999,$tx,"",$time,$time,"y"); // internal tr을 기록한다.
                // $sql = "insert into btc_users_addresses set available_balance_".strtolower($cointype)." = available_balance_".strtolower($cointype)." + $amount where label = '$userid' ";
                
                
                return $tx;
            } else {
                return "error";
            }
    }

    function insert_transaction($cointype,$account,$address,$category,$amount,$confirmations,$txid,$normtxid,$tr_time,$timereceived,$processed){

        if($category == 'receive'){
            $insert = DB::table('btc_transaction')->insert([
                'cointxid' => $cointype.'_'.$txid.'_'.$address,
                'cointype' => $cointype,
                'account' => $account,
                'address' => $address,
                'category' => $category,
                'amount' => $amount,
                'confirmations' => $confirmations,
                'txid' => $txid,
                'normtxid' => $normtxid,
                'tr_time' => $tr_time,
                'timereceived' => $timereceived,
                'processed' => $processed,
                'created_dt' => DB::raw('now()'),
            ]);

            if($insert) { 
                return true;
            } else {
                return false;
            }
        }else if($category == 'send'){
            $insert = DB::table('btc_transaction')->insert([
                'cointxid' => $cointype.'_'.$txid.'_'.$address,
                'cointype' => $cointype,
                'account' => $account,
                'address' => $address,
                'category' => $category,
                'amount' => $amount,
                'confirmations' => $confirmations,
                'txid' => $txid,
                'normtxid' => $normtxid,
                'tr_time' => $tr_time,
                'timereceived' => $timereceived,
                'processed' =>'y',
                'created_dt' => DB::raw('now()'),
            ]);
            
            if($insert) { 
                return true;
            } else {
                return false;
            }
        }else if($category == 'trade'){
            $insert = DB::table('btc_transaction')->insert([
                'cointxid' => $cointype.'_'.$txid.'_'.$address,
                'cointype' => $cointype,
                'account' => $account,
                'address' => $address,
                'category' => $category,
                'amount' => $amount,
                'confirmations' => $confirmations,
                'txid' => $txid,
                'normtxid' => $normtxid,
                'tr_time' => $tr_time,
                'timereceived' => $timereceived,
                'processed' =>'y',
                'created_dt' => DB::raw('now()'),
            ]);
            
            if($insert) { 
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    function add_coin_balance ($cointype, $userid, $amount){
    
        $update = DB::table('btc_users_addresses')
        ->where('label', $userid)
        ->increment('available_balance_'.strtolower($cointype), $amount);
        
        if($update) { 
            return true;
        } else {
            return false;
        }
    
    }
    
    
    
    function minus_coin_balance ($cointype, $userid, $amount){    
    
        $update = DB::table('btc_users_addresses')
        ->where('label', $userid)
        ->decrement('available_balance_'.strtolower($cointype), $amount);
        
        if($update) { 
            return true;
        } else {
            return false;
        }
    
    
    }


    function write_trade_history($buyer_uid, $seller_uid, $buyer_username, $seller_username, $buy_ad_id, $sell_ad_id,  $contract_coin_amt, $buy_coin_price, $sell_coin_price,$trade_price_margin,  $trade_Cash_amt_buy, $trade_Cash_amt_sell, $trade_COIN_amt_buy, $trade_COIN_amt_sell, $trade_margin, $tx_id, $krw_io_id, $pay_tx_id ,$process_currency,$cointype, $last_trade_kind){

 
        if($krw_io_id == "" ) {$krw_io_id = 0;}

        DB::table('btc_trades_COIN_btc')->insert([
            'buyer_uid' => $buyer_uid,
            'seller_uid' => $seller_uid,
            'buyer_username' => $buyer_username,
            'seller_username' => $seller_username,
            'buy_ad_id' => $buy_ad_id,
            'sell_ad_id' => $sell_ad_id,
            'cointype' => $cointype,
            'currency' => $process_currency,
            'contract_coin_amt' => $contract_coin_amt,
            'buy_coin_price' => $buy_coin_price,
            'sell_coin_price' => $sell_coin_price,
            'trade_price_margin' => $trade_price_margin,
            'trade_total_buy' => $trade_Cash_amt_buy,
            'trade_total_sell' => $trade_Cash_amt_sell,
            'trade_margin' => $trade_margin,
            'created' => time(),
            'created_dt' => DB::raw('now()'),
            'last_trade_kind' => $last_trade_kind,
        ]);
    }

    function write_usd_io_history($uid, $type, $status, $amount, $rel_type, $rel_id, $memo,$process_currency){
        $username = "";
        $balance_before = 0;

        $userinfo = DB::table('btc_users_addresses')->where('uid',$uid)->first();
        if($userinfo != null) {
    
                $balance_before = $userinfo->{'available_balance_'.strtolower($process_currency)};
                $balance_after = $balance_before + $amount;
    
                $username = $userinfo->label;
    
                if ($amount >= 0 ) { $plus =  $amount; $minus = 0;}
                else { $plus =  0; $minus = $amount; }
    
                DB::table('btc_usd_io')->insert([
                    'uid' => $uid,
                    'username' => $username,
                    'type' => $type,
                    'status' => $status,
                    'plus' => $plus,
                    'minus' => $minus,
                    'amount' => $amount,
                    'balance_before' => $balance_before,
                    'balance_after' => $balance_after,
                    'rel_type' => $rel_type,
                    'rel_id' => $rel_id,
                    'memo' => $memo,
                    'created' => time(),
                ]);
    
                // 사용자 테이블 업데이트
                DB::table('btc_users_addresses')->where('uid',$uid)->increment('available_balance_'.strtolower($process_currency), $amount);
    
        } // if userinfo
    }

    function remove_pending_usd($buyer_uid, $trade_usd_amt_buy,$process_currency, $reason = 'trade' ){     

        DB::table('btc_users_addresses')->where('uid', $buyer_uid)->increment('pending_received_balance_'.strtolower($process_currency), $trade_usd_amt_buy);
        
    }

    function remove_pending_COIN($seller_uid, $trade_COIN_amt_sell, $cointype, $reason = 'trade'){

        DB::table('btc_users_addresses')->where('uid', $seller_uid)->increment('pending_received_balance_'.strtolower($cointype), $trade_COIN_amt_sell);
    }

    function update_market_price($price, $amount, $currency, $cointype){
        $time = time();
    
        //echo "<br><br>---------------Update Market Price-----------------<br>";
        $coin = DB::table('btc_coins')->where('symbol', strtoupper($cointype))->first();

        $price_change = bcsub($price, $coin->{'last_trade_price_'.strtolower($currency)}, 8);
		
		if($coin->{'max_price_'.strtolower($currency)} > $price){
			$max_price = $coin->{'max_price_'.strtolower($currency)};	
		}else{
			$max_price = $price;
		}
		
		if($coin->{'min_price_'.strtolower($currency)} < $price){
			$min_price = $coin->{'min_price_'.strtolower($currency)};	
		}else{
			$min_price = $price;
		}
		
		$volume = bcadd($coin->{'24h_volume_'.strtolower($currency)},$amount,8);
		
		$price_amount = bcmul($price,$amount,$coin->{'decimal_'.strtolower($currency)});
		
		$price_all = bcadd($coin->{'price_all_24h_'.strtolower($currency)},$price_amount,$coin->{'decimal_'.strtolower($currency)});  
		
        DB::table('btc_coins')->where('symbol', strtoupper($cointype))-> update([
            'price_'.strtolower($currency) => $price,
            'last_trade_price_'.strtolower($currency) => $price,
            'price_change_24h_'.strtolower($currency) => $price_change,
            '24h_volume_'.strtolower($currency) => $volume,
            'price_all_24h_'.strtolower($currency) => $price_all,
            'min_price_'.strtolower($currency) => $min_price,
            'max_price_'.strtolower($currency) => $max_price,
            'updated_dt' => DB::raw('now()'),
            'updated' => time(),
        ]);

    }

    function Market_cancel_buy_order() { // 	
    // ---------------------------- 구매 취소 ---------------------------
        // 요청건들 중에 구매잔량이 0보다 크고 취소 요청중인 건부터 가져온다.
    
        //$process_currency = "USD";
            
        $market = DB::table('btc_settings')->where('id',session('market_type'))->first();
        $log_text = " Cancel Buy Order Start <br>";

        $btc_adss = DB::table('btc_ads_btc')->where('type','buy')->where('status','CancelRequest')
                    ->where('buy_COIN_amt','>',0)
                    ->orderByRaw('buy_coin_price, id asc')->get();
        
        
		
        foreach($btc_adss as $btc_ads){
        	$process_currency = $btc_ads->currency;
            $trade_coin_symbol = $btc_ads->cointype;
    
            $buy_COIN_amt = $btc_ads->buy_COIN_amt;
            $buy_coin_price = $btc_ads->buy_coin_price;
     

            $pending_usd_amt = 0;
            $pending_usd_amt_with_fee = 0;

            $pending_usd_amt = bcmul($buy_COIN_amt , $buy_coin_price,17 ); // 구매금액
            $pending_usd_amt_with_fee =  bcadd($pending_usd_amt , bcmul(bcmul( $pending_usd_amt , $market->buy_comission , 17) , '0.01' ,17 ),17) ;

            

            $buyer_uid = $btc_ads->uid;

            // 요청건 상태값 변경
            
            DB::table('btc_ads_btc')->where('id',$btc_ads->id)->update([
                "status" => 'Cancel',
            ]);

			$this->remove_pending_usd($buyer_uid, $pending_usd_amt_with_fee,strtolower($process_currency)); // buy_COIN_amt * buy_coin_price 만큼 uid 유저의 btc_users_addresses의 pending_received_balance_usd 에 더해준다. 

        }
        
        return $log_text;
    }

    function Market_cancel_sell_order() {//판매요청 취소
    
        
            
        $market = DB::table('btc_settings')->where('id',session('market_type'))->first();
        $log_text = " Cancel Sell Order Start <br>";
    
        // 요청건들 중에 판매잔량이 0보다 크고 취소 요청중인 건부터 가져온다.

        $btc_adss = DB::table('btc_ads_btc')->where('type','sell')->where('status','CancelRequest')
                    ->where('sell_COIN_amt','>',0)->get();
		
		
		
        foreach($btc_adss as $btc_ads){
        	$process_currency = $btc_ads->currency;
            $trade_coin_symbol = $btc_ads->cointype;
            $pending_COIN_amt = $btc_ads->sell_COIN_amt;
            $seller_uid = $btc_ads->uid;

            DB::table('btc_ads_btc')->where('id',$btc_ads->id)->update([
                "status" => 'Cancel',
            ]);

            $this->remove_pending_COIN($seller_uid, $pending_COIN_amt,$trade_coin_symbol); // $pending_COIN_amt 만큼 uid 유저의 btc_users_addresses의 pending_COIN_amt 에 더해준다. 
        }
        
        return $log_text;
    
    }

}