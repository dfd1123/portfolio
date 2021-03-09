<?php

namespace TLCfund\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use TLCfund\Address;
use TLCfund\Product;
use TLCfund\Order;
use TLCfund\Delivery;
use TLCfund\Category;
use TLCfund\Batting;
use TLCfund\Batting_art;
use TLCfund\Review;
use TLCfund\Result_calculate;
use Facades\App\Classes\EthApi;
use Facades\App\Classes\SweetTracker;
use Log;
use DB;


class Kernel extends ConsoleKernel
{

	
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //Commands\NameOfCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
		
    	//일주일 지난 상품 배송처리
		$schedule->call(function(){
				
			$weeks_ago = strtotime("-1 week",time());
		
			$deliverys = Delivery::whereNotNull('delivery_company')->whereNotNull('send_post_num')->with('order')->get();    		
			
			foreach($deliverys as $delivery){
				if($delivery->order->order_state == 2 ){
					if($weeks_ago < strtotime($delivery->order->updated_at)){
						$delivery_api_res = SweetTracker::trackingInfo($delivery->delivery_company,$delivery->send_post_num);
						if(!isset($delivery_api_res['status'])){
							if($delivery_api_res['completeYN'] == 'Y'){
								Order::where('id',$delivery->order_id)->update([
									'order_state' => 3,
								]);
								echo "배송 완료";
							}else{
								echo "배송 미완료";	
							}
						}
					}else{
						Order::where('id',$delivery->order_id)->update([
							'order_state' => 3,
						]);
						echo "배송 완료";
					}
				}
			}
		})->timezone('Asia/Seoul')->cron('0 0-21/3 * * *');

		
		//일주일 지난 상품 배송처리
		$schedule->call(function(){
			
			$weeks_ago = strtotime("-1 week",time());
		
			$deliverys = Delivery::whereNotNull('delivery_company')->whereNotNull('send_post_num')->with('order')->get();
			
			foreach($deliverys as $delivery){
				if($delivery->order->order_state == 2 ){
					if($weeks_ago < strtotime($delivery->order->updated_at)){
						$delivery_api_res = SweetTracker::trackingInfo($delivery->delivery_company,$delivery->send_post_num);
						if(!isset($delivery_api_res['status'])){
							if($delivery_api_res['completeYN'] == 'Y'){
								Order::where('id',$delivery->order_id)->update([
									'order_state' => 3,
								]);
								echo "배송 완료";
							}else{
								echo "배송 미완료";	
							}
						}
					}else{
						Order::where('id',$delivery->order_id)->update([
							'order_state' => 3,
						]);
						echo "배송 완료";
					}
				}
			}
        })->timezone('Asia/Seoul')->cron('30 1-22/3 * * *');
		
		$schedule->call(function(){
			$today = date('Y-m-d H:i:s');
			$dead_line = strtotime($today.'-7 days');
			$dead_line = date('Y-m-d H:i:s', $dead_line);
			
			Order::where('order_state', 3)->where('order_cancel', 0)->whereDate('updated_at', '<', $dead_line)->update([
				"order_state" => 5,
			]);

			$orders = Order::where('order_state',5)->with('seller')->with('product')->get();
			
			foreach($orders as $order){
				Result_calculate::firstOrCreate([
					"order_id" => $order->id,
					"product_name" => $order->product->title,
					"seller_name" => $order->seller->name,
					"seller_email" => $order->seller->email,
					"seller_phone" => $order->seller->mobile_number,
					"bank_name" => $order->seller->account_bank,
					"bank_holder" => $order->seller->account_user,
					"bank_number" => $order->seller->account_number,
					"sale_price" => $order->total_price,
					"fee" => bcmul($order->total_price, 0.05, 0),
					"result_price" => bcsub($order->total_price, bcmul($order->total_price, 0.05, 0), 0)
				]);
			}
		})->daily();
		
        $schedule->call(function(){
			// 베팅이 진행중인 작품 가져오기
			$battings = DB::table('tlca_product')->where('batting_status','<>', 0)->get();
			foreach($battings as $batting) {
				// 작품의 배팅 중에서 상태가 같은 
				$get_like = DB::table('tlca_batting')->where('art_id', $batting->id)->where('batting_status', $batting->batting_status)->count();

				// 작품의 좋아요 업데이트
				DB::table('tlca_product')->where('id', $batting->id)->update([
					'get_like' => $get_like
				]);
			}
		})->timezone('Asia/Seoul')->everyMinute();
		
		$schedule->call(function(){
			info('실행됨');
			$betting_set = DB::table('tlca_batting_set')->first();
			$today = date("Y-m-d", strtotime('+9 hour'));
			
			info("베팅정산 시작한 날짜 : ".$today);
			if(strtotime($betting_set->end_time." +1 days") <= strtotime($today)){
				
				$batting_sum = Batting::where('batting_status',1)->whereDate('end_time','<',date('Y-m-d',strtotime('+9 hour')))->sum('batting_price'); //이번주 전체 베팅금액
				$reward_seller = $batting_sum * $betting_set->reward_seller; //판매자 배당금
				$reward_management = ($batting_sum * $betting_set->reward_management) + ($batting_sum * $betting_set->reward_welfare); //운영자 배당금
				$reward_review = $batting_sum * $betting_set->reward_review; // 리뷰 배당금
				$reward_people = $batting_sum * $betting_set->reward_people; // 베팅인원 배당금
				
				info("이번주 전체 베팅금액 : ".$batting_sum);
				info("판매자 배당금 : ".$reward_seller);
				info("운영자 배당금 : ".$reward_management);
				info("리뷰 배당금 : ".$reward_review);
				info("베팅인원 배당금 : ".$reward_people);
				
				$rank_products = Batting::where('batting_status',1)->with('product')->whereDate('end_time','<',date('Y-m-d',strtotime('+9 hour')))->groupBy('art_id')->selectRaw('art_id, sum(batting_price) as product_batting_price, count(art_id) as total_hit')->orderBy('product_batting_price','desc')->limit(3)->get();
				
				$bat_cnt = Batting_art::max('bat_cnt') + 1; // 이번회차
				
				info("회차 : ".$bat_cnt."\n");
				
				$win_batting_sum = 0; 
				
				$i=1;
				foreach($rank_products as $rank_product){
					if($i == 1){
						$win_seller_id = $rank_product->product->seller_id; // 우승작품 판매자
						$win_art_id = $rank_product->art_id; // 우승작품 id
						$win_batting_sum = $rank_product->product_batting_price; // 우승작품 베팅 금액
						
						info("우승작품 판매자id : ".$win_seller_id);
						info("우승작품id : ".$win_art_id);
						info("우승작품 베팅금액 : ".$win_batting_sum);
						
						//베팅자 70% 분배 보상
						$batting_peoples = Batting::where('art_id',$win_art_id)->where('batting_status',1)->get(); // 우승작품에 베팅한 사람들
						
						foreach($batting_peoples as $batting_people){
							$reward_personal_percent = bcdiv($batting_people->batting_price,$win_batting_sum,5);
							$reward_personal = bcmul($reward_personal_percent,$reward_people,8);
							
							Batting::where('id', $batting_people->id)
							->update([
							'get_price' => $reward_personal
							]);
							
							Address::where('user_email', $batting_people->user_id)
							->update([
							'available_balance_tlc' => DB::raw("available_balance_tlc + $reward_personal")
							]);
							EthApi::addInfoRequest($batting_people->user_id, 'deposit', $reward_personal, 'reward');

							info($batting_people->user_id.' deposit '.$reward_personal.' reward');
							// 여기 transaction insert
						}
						
						//판매자 10%보상
						$user_seller = Address::where('user_id',$win_seller_id)->first();
						
						Address::where('user_email', $user_seller->user_email)
						->update([
						'available_balance_tlc' => DB::raw("available_balance_tlc + $reward_seller")
						]);
						EthApi::addInfoRequest($user_seller->user_email, 'deposit', $reward_seller, 'reward');
						
						//베스트 리뷰 10%보상
						$best_review = Review::where('art_id',$rank_product->art_id)->orderby(DB::raw('recomend - unrecomend'),'desc')->first();
						if($best_review != null){
							$best_review_user = Address::where('user_id',$best_review->writer_id)->first();
							
							Address::where('user_email', $best_review_user->user_email)
							->update([
							'available_balance_tlc' => DB::raw("available_balance_tlc + $reward_review")
							]);
							EthApi::addInfoRequest($best_review_user->user_email, 'deposit', $reward_review, 'reward');
						}else{
							//리뷰가 존재하지 않을시 운영자 계정에 들어감
							Address::where('user_email', 'adminfee@admin.com')
							->update([
							'available_balance_tlc' => DB::raw("available_balance_tlc + $reward_review")
							]);
							EthApi::addInfoRequest('adminfee@admin.com', 'deposit', $reward_review, 'reward');										
						}
						
						//운영자 리뷰 10% 보상  id 만들면 넣으면됨
						Address::where('user_email', 'adminfee@admin.com')
						->update([
							'available_balance_tlc' => DB::raw("available_balance_tlc + $reward_management")
						]);
						EthApi::addInfoRequest('adminfee@admin.com', 'deposit', $reward_management, 'reward');
					}
		
					
		
					Batting_art::create([
						'seller_id' => $rank_product->product->seller_id,
						'art_id' => $rank_product->art_id,
						'art_name' => $rank_product->product->title,
						'ca_id' => $rank_product->product->ca_id,
						'bat_ranking' => $i,
						'bat_cnt' => $bat_cnt,
						'total_price' => $rank_product->product_batting_price,
						'total_hit' => $rank_product->total_hit,
						'start_time' => $rank_product->product->start_time,
						'end_time' => $rank_product->product->end_time
					]);
					info($i."등 판매자 id: ".$rank_product->product->seller_id);
					info($i."등 작품 id: ".$rank_product->art_id);
					info($i."등 작품 이름: ".$rank_product->product->title);
					info($i."등 카테고리 id: ".$rank_product->product->ca_id);
					info($i."등 랭킹 : ".$i);
					info($i."등 회차: ".$bat_cnt);
					info($i."등 총 베팅금액: ".$rank_product->product_batting_price);
					info($i."등 총 배팅건수: ".$rank_product->total_hit);
					info($i."등 시작날짜: ".$rank_product->product->start_time);
					info($i."등 종료날짜: ".$rank_product->product->end_time);
					
					$i++;
				}
		
				$end_batting_product = Product::where('batting_yn',1)->whereDate('end_time','<',date('Y-m-d',strtotime('+9 hour')))->update([
					'batting_status' => 2,
				]);
				
				Batting::whereDate('end_time','<',date('Y-m-d',strtotime('+9 hour')))->update([
					'batting_status' => 2,
				]);
				
				DB::table('tlca_batting_set')->update([
					"end_time" => date("Y-m-d", strtotime($betting_set->end_time." +".$betting_set->batting_term." days")),
				]);
				
				$betting_set = DB::table('tlca_batting_set')->first();
				
				$start_batting_day = date("Y-m-d",strtotime($betting_set->end_time." +1 days"));

				$delay_batting = Product::where('batting_yn',1)->where('sell_yn','<>','1')->where('sell_yn','<>','3')->whereDate('start_time','<=',date('Y-m-d',strtotime('+9 hour')))->whereDate('end_time','>=',date('Y-m-d',strtotime('+9 hour')))->update([
					'batting_status' => 0,
					'start_time' => $start_batting_day,
					'end_time' => date("Y-m-d", strtotime($start_batting_day." +".$betting_set->batting_term." days")),
				]); // 판매 승인 안난 작품들 기간 다음주로 연기
				
				$today_batting = Product::where('batting_yn',1)->where(function($query){
					$query->where('sell_yn',1)->orwhere('sell_yn',3);
				})
				->whereDate('start_time','<=',date('Y-m-d',strtotime('+9 hour')))->whereDate('end_time','>=',date('Y-m-d',strtotime('+9 hour')))->update([
					'batting_status' => 1,
					'start_time' => date('Y-m-d',strtotime('+9 hour')),
					'end_time' => $betting_set->end_time,
				]); // 판매 승인 난 베팅 예정  작품들 현재 베팅 진행으로 변경
								
				
				info('다음 시작시간 : '.$start_batting_day);
				info('다음 마감시간 : '.$betting_set->end_time);
				
				info('종료된 베팅 수 : '.$end_batting_product);
				
				info('연기된 베팅 수 : '.$delay_batting);
				
				info('이번주 배정된 베팅 수 : '.$today_batting);

			}
			
				
		})->timezone('Asia/Seoul')->cron('1 */24 * * *');
		
			
	}
			
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
