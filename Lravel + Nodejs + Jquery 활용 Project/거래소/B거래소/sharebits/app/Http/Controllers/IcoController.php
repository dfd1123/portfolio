<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent; 

use DB;
use Auth;
use Redirect;

class IcoController extends Controller
{
    public function __construct()
    {
            $this->middleware('auth')->except('list');  //로그인 확인
            $agent = new Agent();
            $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }

    public function index(){
	}
	
	public function list($category){

		$views = view(session('theme').'.'.$this->device.'.'.'ico.ico_list');
		//1. list 호출 시 날짜를 비교하여 등록된 ico의 category를 update

		
		DB::connection('mysql_sub')
			->table('btc_ico_new')
			->where('active','1')
			->where('ico_from','>',now())
			->update(['ico_category'=>2]);
		
		DB::connection('mysql_sub')
			->table('btc_ico_new')
			->where('active','1')
			->where('ico_from','<=',now())
			->where('ico_to','>',now())
			->update(['ico_category'=>1]);
		
		DB::connection('mysql_sub')
			->table('btc_ico_new')
			->where('active','1')
			->where('ico_to','<=',now())
			->update(['ico_category'=>3]);
		
		DB::connection('mysql_sub')
			->table('btc_ico_new')
			->where('active','1')
			->whereColumn([
				['ico_goal','=','ico_coin'],
				['ico_goal','<','ico_coin'],
				['ico_min','>','ico_remain']
			])
			->update(['ico_category'=>4]);

		$offset = 15;
		
		//2. list view
		if($this->device == 'pc'){
			if($category == 'all'){
				$count = DB::connection('mysql_sub')->table('btc_ico_new')->where('active',1)->orderBy('created_at','desc')->count();
				$icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('active',1)->orderBy('created_at','desc')->get();
			}else{
				$count = DB::connection('mysql_sub')->table('btc_ico_new')->where('ico_category',strtoupper($category))->where('active',1)->orderBy('created_at','desc')->count();
				$icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('ico_category',strtoupper($category))->where('active',1)->orderBy('created_at','desc')->get();
			}
		}else{
			if($category == 'all'){
				$count = DB::connection('mysql_sub')->table('btc_ico_new')->where('active',1)->orderBy('created_at','desc')->count();
				$icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('active',1)->orderBy('created_at','desc')->limit($offset)->get();
			}else{
				$count = DB::connection('mysql_sub')->table('btc_ico_new')->where('ico_category',strtoupper($category))->where('active',1)->orderBy('created_at','desc')->count();
				$icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('ico_category',strtoupper($category))->where('active',1)->orderBy('created_at','desc')->limit($offset)->get();
			}
		}

        $banners = DB::connection('mysql_sub')->table('btc_banners')->where('lang', config('app.country'))->where('active', 1)->orderBy(DB::raw('rand()'));
        $top_banners = (clone $banners)->where('position', 'top')->limit(1)->get();
        $left_banners = (clone $banners)->where('position', 'left')->limit(2)->get();
        $right_banners = (clone $banners)->where('position', 'right')->limit(2)->get();

		$views->icos = $icos;
		$views->count = $count;
		$views->offset = $offset;
		$views->my = 0;
		$views->category = $category;
		$views->pagename = 'ico';
		$views->top_banners = $top_banners;
        $views->left_banners = $left_banners;
        $views->right_banners = $right_banners;
        
		return $views;
	}
	
	public function create(){
		$views = view(session('theme').'.'.$this->device.'.'.'ico.ico_create');
		$icos = DB::table('btc_coins')
				->where('active',1)
				->where(function($qry){
					$qry->where('cointype','coin')->orWhere('cointype','token');
				})->get();
		$views->icos = $icos;
		$views->pagename = 'ico';	
		return $views;
	}
	
	public function insert(Request $request){
		
		$store_img_path = 'public/image/ico/';
		$store_doc_path = 'public/doc/ico/';
		
		$benefits = array(NULL);
		$sns = array(NULL);
		$sns_url = array(NULL);
		
		$news = array(NULL);
		$news_name = array(NULL);
		$news_url = array(NULL);

		$cnt=0;


		$benefits = $request->benefit;
		
		
		$sns_url = $request->sns_url;
		
		$news_name = $request->news_name;
		$news_url = $request->news_url;
		
		

		$ico_from = strtotime($request->ico_from.' '.$request->ico_from_h);
		$ico_to = strtotime($request->ico_to.' '.$request->ico_to_h);
		
		/*-----파일첨부 경로 배열------*/
		$ico_whitepaper = array(NULL);
		$ico_reports = array();
		$cont_paths = array();


		$ico_whitepaper[0] = 'no_image.svg';    //이미지가 안들어올 수도 있다. 기본이미지
		$ico_reports[0] = 'no_image.svg';			  //이미지가 안들어올 수도 있다. 기본이미지
		$cont_paths[0] = 'no_image.svg';			  //이미지가 안들어올 수도 있다. 기본이미지

		
		if(empty($request->title)){
			return back()->with('jsAlert',__('message.is_title'));
		}

		if(empty($request->intro)){
			return back()->with('jsAlert',__('message.is_intro'));
		}

		if(empty($request->coin_name)){
			return back()->with('jsAlert',__('message.is_coin_name'));
		}

		if(empty($request->symbol)){
			return back()->with('jsAlert',__('message.is_coin_symbol'));
		}

		if(empty($request->collect)){
			return back()->with('jsAlert',__('message.is_coin_collect'));
		}

		if(empty($request->ico_coin_p)){
			return back()->with('jsAlert',__('message.is_coin_price'));
		}

		if(empty($request->ico_min)){
			return back()->with('jsAlert',__('message.is_coin_min'));
		}

		if(empty($request->ico_goal)){
			return back()->with('jsAlert',__('message.is_goal_price'));
		}

		if(empty($request->ico_from)){
			return back()->with('jsAlert',__('message.is_ico_from'));
		}

		if(empty($request->ico_to)){
			return back()->with('jsAlert',__('message.is_ico_to'));
		}

		if(empty($request->ico_use)){
			return back()->with('jsAlert',__('message.is_ico_use'));
		}

		if(empty($request->ico_tech)){
			return back()->with('jsAlert',__('message.is_ico_tech'));
		}

		if(empty($request->ico_url)){
			return back()->with('jsAlert',__('message.is_ico_url'));
		}

		if($file = $request->file('thumnail')){    	//name이 images인 값들을 모두 받아와 $files에 저장한다.
			if ($file->isValid()) {            		//파일값이 존재하면
				$thumnail = $file->store($store_img_path.'/');                  //$file에 저장된 이미지를 public/image/ico/에 저장을하고 $path[해당인덱스]에 경로를 저장한다. -> 이때 $path에는 해당경로 + 이미지이름이 저장된다. 
				$thumnail = str_replace($store_img_path.'/',"",$thumnail);		//$path[해당인덱스]에 저장된 경로에서 경로를 str_replace를 이용하여 공백처리한다. -> 이미지이름만 남게된다.
			}
		}else{
			return back()->with('jsAlert',__('message.is_thumnail'));
		}

		$cnt=0;
		
		if($files = $request->file('ico_whitepater')){	//백서 insert
			for($i=0; $i<4; $i++){

				if(!empty($files[$i])){
					if ($files[$i]->isValid()){
						$ico_whitepaper[$i] = $files[$i]->store($store_doc_path.'/');
						$ico_whitepaper[$i] = str_replace($store_doc_path.'/',"",$ico_whitepaper[$i]);
						$cnt = $cnt + 1;
					}
					
				}else{
					$ico_whitepaper[$i] = NULL;
				}
			}
			if($cnt==0){
				return back()->with('jsAlert',__('message.is_whitepaper'));
			}
		}else{
			return back()->with('jsAlert',__('message.is_whitepaper'));
		}

		$cnt=0;
		
		if($files = $request->file('ico_report')){	//분석레포트 insert
			for($i=0; $i<4; $i++){
				if(!empty($files[$i])){
					if ($files[$i]->isValid()){
						$ico_reports[$i] = $files[$i]->store($store_doc_path.'/');
						$ico_reports[$i] = str_replace($store_doc_path.'/',"",$ico_reports[$i]);
						$cnt = $cnt + 1;
					}
				}else{
					$ico_reports[$i] = NULL;
				}
			}
			if($cnt==0){
				return back()->with('jsAlert',__('message.is_report'));	
			}
		}else{
			return back()->with('jsAlert',__('message.is_report'));
		}
		
		$cnt=0;

		if($files = $request->file('cont')){		//내용이미지 insert
			for($i=0; $i<3; $i++){
				if(!empty($files[$i])){
					if ($files[$i]->isValid()){		//성공적으로 업로드 되었는지 확인
						$cont_paths[$i] = $files[$i]->store($store_img_path.'/');
						$cont_paths[$i] = str_replace($store_img_path.'/',"",$cont_paths[$i]);
						$cnt = $cnt + 1;
					}
				}else{
					$cont_paths[$i] = NULL;
				}
			}
			if($cnt==0){
				return back()->with('jsAlert',__('message.is_image'));
			}
		}else{
			return back()->with('jsAlert',__('message.is_image'));
		}

		
			for($i=0; $i<10; $i++){

				if(!empty($benefits[$i])){
					$benefit[$i] = $benefits[$i];					
				}else{
					$benefit[$i] = NULL;
				}
				
			}
			for($i=0; $i<10; $i++){

				if((!empty($news_name[$i])) && (!empty($news_url[$i]))){
					$news[$i] = $news_name[$i].'__'.$news_url[$i];					
				}else{
					$news[$i] = NULL;
				}
				
			}
			
			for($i=0; $i<5; $i++){

				if(!empty($sns_url[$i])){
					$sns[$i]=$sns_url[$i];					
				}else{
					$sns[$i] = NULL;
				}
				
			}


		DB::connection('mysql_sub')->table('btc_ico_new')
		->insert([
			'w_id' => Auth::id(),					//작성자 id
			'label' => Auth::user()->username,		//작성자 username
			'w_name' =>	Auth::user()->fullname,		//작성자 name
			'ico_title' => $request->title,			//프로젝트 명칭
			'ico_intro' => $request->intro,			//소개
			'coin_name' => $request->symbol,
			'ico_symbol' => $request->symbol,		//코인심볼
			'ico_collect' => $request->collect,		//접수가능 코인
			'ico_coin_p' => $request->ico_coin_p,	//초기가격
			'ico_min' => $request->ico_min,			//최소구매 갯수
			'ico_from' => date('Y-m-d H:i:s',$ico_from),		//모집기간(시작)
			'ico_to' => date('Y-m-d H:i:s',$ico_to),			//모집기간(마감)
			'ico_url' => $request->ico_url,			//도메인주소
			'ico_goal' => $request->ico_goal,		//목표금액
			'ico_remain' => $request->ico_goal,
			'ico_use_kind' => $request->ico_use,	//세부정보 - 사용분야
			'ico_tech' => $request->ico_tech,		//세부정보 - 기술기반

			'ico_whitepaper_jp' => $ico_whitepaper[0],	//백서(일본)
			'ico_whitepaper_en' => $ico_whitepaper[1],	//백서(미국)
			'ico_whitepaper_ch' => $ico_whitepaper[2],	//백서(중국)
			'ico_whitepaper_kr' => $ico_whitepaper[3],	//백서(한국)
					
			'ico_report2' => $ico_reports[0],	//분석레포트(일본)
			'ico_report3' => $ico_reports[1],	//분석레포트(미국)
			'ico_report4' => $ico_reports[2],	//분석레포트(중국)
			'ico_report1' => $ico_reports[3],	//분석레포트(한국)
			
			
			
			'ico_image1'  => $cont_paths[0],			//내용소개1
			'ico_image2' => $cont_paths[1],				//내용소개2
			'ico_image3' =>$cont_paths[2],				//내용소개3
			
			'ico_thumnail' => $thumnail,		//배너이미지

			'ico_benefit1' => $benefit[0],				//세부정보-혜택1
			'ico_benefit2' => $benefit[1],				//세부정보-혜택2
			'ico_benefit3' => $benefit[2],				//세부정보-혜택3
			'ico_benefit4' => $benefit[3],				//세부정보-혜택4
			'ico_benefit5' => $benefit[4],				//세부정보-혜택5
			'ico_benefit6' => $benefit[5],				//세부정보-혜택6
			'ico_benefit7' => $benefit[6],				//세부정보-혜택7
			'ico_benefit8' => $benefit[7],				//세부정보-혜택8
			'ico_benefit9' => $benefit[8],				//세부정보-혜택9
			'ico_benefit10' => $benefit[9],				//세부정보-혜택10
			
			'news1' => $news[0],				//세부정보-뉴스1
			'news2' => $news[1],				//세부정보-뉴스2
			'news3' => $news[2],				//세부정보-뉴스3
			'news4' => $news[3],				//세부정보-뉴스4
			'news5' => $news[4],				//세부정보-뉴스5
			'news6' => $news[5],				//세부정보-뉴스6
			'news7' => $news[6],				//세부정보-뉴스7
			'news8' => $news[7],				//세부정보-뉴스8
			'news9' => $news[8],				//세부정보-뉴스9
			'news10' => $news[9],				//세부정보-뉴스10

			'ico_sns1' => $sns[0],				//sns1
			'ico_sns2' => $sns[1],				//sns2
			'ico_sns3' => $sns[2],				//sns3
			'ico_sns4' => $sns[3],				//sns4
			'ico_sns5' => $sns[4],				//sns5
			'created_at' => now(),
			'updated_at' => now(),
			]);
		

		
		                                          //DB에 저장할때 이미지명만 저장하기위해 위의 작업들을 진행함.
		return redirect()->route('ico_list','all');
		

	}

	public function edit($id){
		$ico = DB::connection('mysql_sub')->table('btc_ico_new')->where('id',$id)->first();

		if(Auth::id() != $ico->w_id){
			return back()->with('jsAlert','고객님의 등록 게시물만 수정할 수 있습니다.');
		}

		$views = view(session('theme').'.'.$this->device.'.'.'ico.ico_edit');

		$views->ico = $ico;

		return $views;
	}

	public function update(Request $request, $id){
		
	}

	public function ico_show($id){

		$views = view(session('theme').'.'.$this->device.'.'.'ico.ico_view');

		$ico = DB::connection('mysql_sub')->table('btc_ico_new')->where('id',$id)->first();

		if(Auth::check()){
			$my_asset = DB::table('btc_users_addresses')
					->select('available_balance_'.strtolower($ico->ico_collect),'pending_received_balance_'.strtolower($ico->ico_collect))
					->where('uid',Auth::id())->first();

			$available_coin = bcadd($my_asset->{'available_balance_'.strtolower($ico->ico_collect)},$my_asset->{'pending_received_balance_'.strtolower($ico->ico_collect)},8);
			$available_coin = (float)$available_coin;
		}else{
			$available_coin = 0;
		}




		$views->ico = $ico;
		$views->available_coin = $available_coin;


		return $views;
	}




	public function ico_buy(Request $request, $id){
	$total_buy = (float)$request->total_buy;
	//유의사항에 동의하지 않았을때
		if(!isset($request->info_agree)){
			return back()->with('jsAlert',__('message.is_ico_checked'));
		}
	//구매량이 0보다 적을때
		if($request->total_buy<=0){
			return back()->with('jsAlert',__('message.is_wrong_buy2'));
		}
		
		$ico = DB::connection('mysql_sub')
			->table('btc_ico_new')
			->where('id',$id)
			->first();
				
		//ICO가 컨펌을 받지 않았는데 구매신청이 먼저 온 경우
		if($ico->active==0){
			return back()->with('jsAlert',__('message.no_confirmed'));
		}
		if($ico->ico_category!=1){
			return back()->with('jsAlert',__('message.no_sell'));
		}
			
		if(Auth::check()){
			//구매자 자산
			$buyer_asset = DB::table('btc_users_addresses')
			->select('available_balance_'.strtolower($ico->ico_collect),'pending_received_balance_'.strtolower($ico->ico_collect))
			->where('uid',Auth::id())
			->first();

			$seller_asset = DB::table('btc_users_addresses')
			->select('available_balance_'.strtolower($ico->ico_symbol),'pending_received_balance_'.strtolower($ico->ico_symbol))
			->where('uid',$ico->w_id)
			->first();

			$buyer_available_coin = bcadd($buyer_asset->{'available_balance_'.strtolower($ico->ico_collect)},$buyer_asset->{'pending_received_balance_'.strtolower($ico->ico_collect)},8);
			$buyer_available_coin = (float)$buyer_available_coin;
			$seller_available_coin = bcadd($seller_asset->{'available_balance_'.strtolower($ico->ico_symbol)},$seller_asset->{'pending_received_balance_'.strtolower($ico->ico_symbol)},8);
			$seller_available_coin = (float)$seller_available_coin;
		
		}else{
			$buyer_available_coin = 0;
			$seller_available_coin = 0;
		}
		//
	//구매량이 최소 구매량보다 적을때
		if($ico->ico_min > $request->total_buy){
			return back()->with('jsAlert',__('message.is_wrong_buy2'));
		}
	//구매자의 자산이 구매량보다 적을때
		if($buyer_available_coin < $request->total_buy){
			return back()->with('jsAlert',__('message.less_than_my_asset'));
		}
		//dd(bcdiv($ico->ico_min, $ico->ico_coin_p,8).'--'.$seller_available_coin);
	//판매자의 자산이 최소 구매량보다 적을때
		if(bcdiv($ico->ico_min, $ico->ico_coin_p,8) > $seller_available_coin){
			//dd('awdad');
			return back()->with('jsAlert',__('message.less_seller_asset'));
		}
		
	//판매자의 자산이 구매량보다 적을때
		if(bcdiv($request->total_buy, $ico->ico_coin_p, 8) > $seller_available_coin){
			
			return back()->with('jsAlert',__('message.less_seller_asset'));
		}

		//현재모집금액 + 구매요청금액이 목표 판매금액보다 적을 때
		if($ico->ico_goal >= bcadd($ico->ico_coin, $request->total_buy, 8)){
			// 해당프로젝트의 구매 후 모집한 갯수가 모집목표 갯수보다 적을때 모집갯수 추가 
			DB::connection('mysql_sub')
			->table('btc_ico_new')
			->where('id',$id)->update([
				"ico_coin" => DB::raw('ico_coin + '.$request->total_buy),
				"ico_remain" => DB::raw('ico_remain - '.$total_buy),
			]);
			



			// 해당프로젝트의 구매 후 모집한 갯수가 모집목표 갯수보다 적을때 구매 진행 


			$sell_amt = bcdiv($request->total_buy,$ico->ico_coin_p,'8'); // 지불 코인 수량
			$pay_amt = $request->total_buy; // ICO한 코인 구/판매 수량

			//uid가 신청자 id와 같으면 구매자가 결제한 만큼 결제코인을 감소(구매자)
			
			//결제
			DB::table('btc_users_addresses')
			->where('uid',Auth::id())
			->update([
				"available_balance_".strtolower($ico->ico_collect) => DB::raw('available_balance_'.strtolower($ico->ico_collect).' - '.$pay_amt),
				"available_balance_".strtolower($ico->ico_symbol) => DB::raw('available_balance_'.strtolower($ico->ico_symbol).' + '.$sell_amt),
			]);


			//w_id와 user의 아이디가 같으면 결제한 만큼 결제코인을 추가
			//코인 결제
			DB::table('btc_users_addresses')
			->where('uid',$ico->w_id)
			->update([
				"available_balance_".strtolower($ico->ico_collect) => DB::raw('available_balance_'.strtolower($ico->ico_collect).' + '.$pay_amt),
				"available_balance_".strtolower($ico->ico_symbol) => DB::raw('available_balance_'.strtolower($ico->ico_symbol).' - '.$sell_amt),
			]);
			

			$user_addrs = DB::table('btc_users_addresses')
			->where('uid',Auth::id())->orWhere('uid',$ico->w_id)->get();
			
			foreach($user_addrs as $user_addr){
				if($user_addr->uid == Auth::id()){
					$buyer_reciev_addr = $user_addr->{'address_'.strtolower($ico->ico_symbol)};
					$buyer_send_addr = $user_addr->{'address_'.strtolower($ico->ico_collect)};
				}else{
					$seller_reciev_addr = $user_addr->{'address_'.strtolower($ico->ico_collect)};
					$seller_send_addr = $user_addr->{'address_'.strtolower($ico->ico_symbol)};
				}
			}


			
			// 구매 완료 시 거래기록을 남김
			DB::connection('mysql_sub')
			->table('btc_ico_people')
			->insert([
				'name' => Auth::user()->fullname,
				'order_price' => $ico->ico_coin_p,
				'buy_price' => $request->total_buy,
				'buy_amount' => $sell_amt,
				'pr_id' => $id,
				'buy_pay' => $ico->ico_collect,
				'order_coin' => $ico->ico_symbol,
				'uid' => Auth::id(),
				'order_time' => now()
			]);

			$pay_txid = '_internal_transfer_'.$pay_amt.'_'.Auth::user()->username.'_'.$ico->label.'_'.substr(md5(rand()),0,7);
			$sell_txid = '_internal_transfer_'.$sell_amt.'_'.$ico->label.'_'.Auth::user()->username.'_'.substr(md5(rand()),0,7);
			
			$insert_data = array(
				array(
					'cointxid' => strtolower($ico->ico_collect)."_".$pay_txid."_send",
					'cointype' => strtolower($ico->ico_collect),
					'account' => Auth::user()->username,
					'address' => $seller_reciev_addr,
					'category' => 'send',
					'amount' => $pay_amt,
					'confirmations' => 999,
					'txid' => $pay_txid,
					'normtxid' => '',
					'tr_time' => time(),
					'timereceived' => time(),
					'processed' => 'y',
					'created_dt' => DB::raw('now()'),
				),
				array(
					'cointxid' => strtolower($ico->ico_collect)."_".$pay_txid."_receive",
					'cointype' => strtolower($ico->ico_collect),
					'account' => $ico->label,
					'address' => $buyer_reciev_addr,
					'category' => 'receive',
					'amount' => $pay_amt,
					'confirmations' => 999,
					'txid' => $pay_txid,
					'normtxid' => '',
					'tr_time' => time(),
					'timereceived' => time(),
					'processed' => 'y',
					'created_dt' => DB::raw('now()'),
				),
				array(
					'cointxid' => strtolower($ico->ico_symbol)."_".$sell_txid."_send",
					'cointype' => strtolower($ico->ico_symbol),
					'account' => $ico->label,
					'address' => $buyer_reciev_addr,
					'category' => 'send',
					'amount' => $sell_amt,
					'confirmations' => 999,
					'txid' => $sell_txid,
					'normtxid' => '',
					'tr_time' => time(),
					'timereceived' => time(),
					'processed' => 'y',
					'created_dt' => DB::raw('now()'),
				),
				array(
					'cointxid' => strtolower($ico->ico_symbol)."_".$sell_txid."_receive",
					'cointype' => strtolower($ico->ico_symbol),
					'account' => Auth::user()->username,
					'address' => $seller_reciev_addr,
					'category' => 'receive',
					'amount' => $sell_amt,
					'confirmations' => 999,
					'txid' => $sell_txid,
					'normtxid' => '',
					'tr_time' => time(),
					'timereceived' => time(),
					'processed' => 'y',
					'created_dt' => DB::raw('now()'),
				),
			);
			
			//입출금 이력 INSERT
			DB::table('btc_transaction')->insert($insert_data);



		}else{
			//구매 목표금액 - 현재 모집금액이 최소구매 금액보다 작을 때
			if(($ico->ico_goal - $ico->ico_coin) < $ico->ico_min){

				return back()->with('jsAlert','SOLD OUT!');	 
			}else{
				return back()->with('jsAlert','구매실패! 남은수량 : '.($ico->ico_goal-$ico->ico_coin).$ico->ico_symbol);
			}
		}
		return back()->with('jsCheck','구매완료! 내가 진행한 ICO에서 구매내역을 확인하실 수 있습니다. ');
	}









	public function my_ico_history(){
		//$this->middleware('auth');
		
		$views = view(session('theme').'.'.$this->device.'.'.'ico.ico_history');

		$offset = 15;

		if($this->device == 'pc'){
			$count = DB::connection('mysql_sub')->table('btc_ico_people')->where('uid',Auth::id())->orderBy('order_time','desc')->count();
			$icos = DB::connection('mysql_sub')->table('btc_ico_people')->where('uid',Auth::id())->orderBy('order_time','desc')->get();
		}else{
			$count = DB::connection('mysql_sub')->table('btc_ico_people')->where('uid',Auth::id())->orderBy('order_time','desc')->count();
			$icos = DB::connection('mysql_sub')->table('btc_ico_people')->where('uid',Auth::id())->orderBy('order_time','desc')->limit($offset)->get();
		}

		$banners = DB::connection('mysql_sub')->table('btc_banners')->where('lang', config('app.country'))->where('active', 1)->orderBy(DB::raw('rand()'));
        $top_banners = (clone $banners)->where('position', 'top')->limit(1)->get();
        $left_banners = (clone $banners)->where('position', 'left')->limit(2)->get();
		$right_banners = (clone $banners)->where('position', 'right')->limit(2)->get();
		
		$views->pagename = 'ico_history';
		$views->icos = $icos;
		$views->count = $count;
		$views->offset = $offset;
		$views->top_banners = $top_banners;
        $views->left_banners = $left_banners;
		$views->right_banners = $right_banners;
		
		return $views;

	}
	public function my_ico($category){
		$views = view(session('theme').'.'.$this->device.'.'.'ico.ico_list');
		
		$offset = 15;
		if($this->device == 'pc'){
			if($category == 'all'){
				$count = DB::connection('mysql_sub')->table('btc_ico_new')->where('w_id',Auth::id())->count();
				$icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('w_id',Auth::id())->get();
			}else{
				$count = DB::connection('mysql_sub')->table('btc_ico_new')->where('w_id',Auth::id())->where('ico_category',strtoupper($category))->count();
				$icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('w_id',Auth::id())->where('ico_category',strtoupper($category))->get();
			}
		}else{
			if($category == 'all'){
				$count = DB::connection('mysql_sub')->table('btc_ico_new')->where('w_id',Auth::id())->count();
				$icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('w_id',Auth::id())->limit($offset)->get();
			}else{
				$count = DB::connection('mysql_sub')->table('btc_ico_new')->where('w_id',Auth::id())->where('ico_category',strtoupper($category))->count();
				$icos = DB::connection('mysql_sub')->table('btc_ico_new')->where('w_id',Auth::id())->where('ico_category',strtoupper($category))->limit($offset)->get();
			}
		}

		$banners = DB::connection('mysql_sub')->table('btc_banners')->where('lang', config('app.country'))->where('active', 1)->orderBy(DB::raw('rand()'));
        $top_banners = (clone $banners)->where('position', 'top')->limit(1)->get();
        $left_banners = (clone $banners)->where('position', 'left')->limit(2)->get();
		$right_banners = (clone $banners)->where('position', 'right')->limit(2)->get();
		

		$views->pagename = 'my_ico';
		$views->icos = $icos;
		$views->count = $count;
		$views->offset = $offset;
		$views->my = 1;
		$views->category = $category;
		$views->top_banners = $top_banners;
        $views->left_banners = $left_banners;
		$views->right_banners = $right_banners;
		
		return $views;
	}

}