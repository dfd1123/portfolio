@extends(session('theme').'.pc.layouts.app')

@section('content')

<input type="hidden" name="coin_api" value="{{$trade_coin->apiname}}" />
<input type="hidden" name="coin_apiname" value="{{$trade_coin->api}}" />
<input type="hidden" name="setting_url" value="{{$market->url}}chart" />
<input type="hidden" name="decimal_usd" value="{{$trade_coin->decimal_usd}}" />
<input type="hidden" name="call_unit" value="{{$trade_coin->call_unit}}" />
@if(count($notices) > 0)
	<!-- ①뉴스 -->
	<div class="ntc_bar">

		<div class="ntc_bar_mask">

			<ul class="ntc_ul">
				@foreach($notices as $notice)
				<li>
					<a href="{{route('notice_view',$notice->id)}}"> <span class="tit">{{ __('market.notice') }}</span> <span class="ntc_tit">{{$notice->title}}</span> </a>
				</li>
				@endforeach
			</ul>

		</div>

		<div id="ntc_x_btn" class="close_btn">
			<span></span>
		</div>

		<div class="ntc_show">
			NOTICE
		</div>

		<span id="ntc_next_btn" class="ntc_next">NEXT</span>

	</div>
	<!-- //①뉴스 -->
@endif

<div class="trans_wrap">

	<div class="trans_inner">
		<!-- ②거래소영역 -->
		<div class="trans_con">

			<!-- ②-2 왼쪽박스(호가창) -->
			<div class="left_con">
				<div class="tit">
					<ul class="btn_list">
						<li id="arrow_both_btn" class="arrow_both">
							<img src="{{ asset('/storage/image/homepage/icon/left_box_btn-01.svg')}}" alt="both_icon">
						</li>
						<li id="arrow_up_btn" class="arrow_up">
							<img src="{{ asset('/storage/image/homepage/icon/left_box_btn-02.svg')}}" alt="top_icon">
						</li>
						<li id="arrow_down_btn" class="arrow_down">
							<img src="{{ asset('/storage/image/homepage/icon/left_box_btn-03.svg')}}" alt="under_icon">
						</li>
					</ul>
					<h6>{{ __('market.arc_window') }}</h6>

					<table class="table_label">
						<thead>
							<th>{{ __('market.price_usdc') }}</th>
							<th>{{ __('market.quantity') }}({{$trade_coin->symbol}})</th>
							<th>{{ __('market.total_amount') }}</th>
						</thead>
					</table>
				</div>
				<div class="up_wrap wait_wrap">
					<table id="sell_wait" class="table_in_box both_table up_table">
						<tbody>
							@if($sell_ads_cnt > 0)
								@foreach($sell_adss as $sell_ads)
								<tr data-price="{{round($sell_ads->price, $trade_coin->decimal_usd)}}" data-amt="{{round($sell_ads->amt, 8)}}">
									<td class="blue"><span>{{number_format($sell_ads->price, $trade_coin->decimal_usd)}}</span></td>
									<td><span>{{number_format($sell_ads->amt, 8)}}</span></td>
									<td><span>{{number_format(($sell_ads->price * $sell_ads->amt), $trade_coin->decimal_usd)}}</span><div class="per_bar" style="width: {{$sell_ads->amt/$total_amt_sell->total_amt_sell*600}}%;"></div></td>
								</tr>
								@endforeach
							@else
								<tr>
									<td colspan="3" rowspan="15">{!! __('market.no_sell') !!}</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
				<div class="total_box">
					<span id="orderbook_middle" class="{{$last_trade_kind}}">
						-
					</span>
				</div>
				<div class="down_wrap wait_wrap">
					<table id="buy_wait" class="table_in_box both_table down_table">
						<tbody>
							@if($buy_ads_cnt > 0)
								@foreach($buy_adss as $buy_ads)
								<tr data-price="{{round($buy_ads->price, $trade_coin->decimal_usd)}}" data-amt="{{round($buy_ads->amt, 8)}}">
									<td class="red"><span>{{number_format($buy_ads->price, $trade_coin->decimal_usd)}}</span></td>
									<td><span>{{number_format($buy_ads->amt, 8)}}</span></td>
									<td><span>{{number_format(($buy_ads->price * $buy_ads->amt), $trade_coin->decimal_usd)}}</span><div class="per_bar" style="width: {{$buy_ads->amt/$total_amt_buy->total_amt_buy*600}}%;"></div></td>
								</tr>
								@endforeach
							@else
								<tr>
									<td colspan="3" rowspan="15">{!! __('market.no_buy') !!}</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>

			</div>
			<!-- //②-2 왼쪽박스(호가창) -->

			<!-- ②-4 중간박스(코인상태바, 차트, 구매-판매박스) -->
			<div class="center_con">

				<div id="top_ticker" class="trans_hd" data-coin="{{$trade_coin->api}}">

					<div class="coin_state_1">

						<p class="coin_name in_cell">
							<span class="c_name">{{__('coin_name.'.$trade_coin->api)}}</span>
							<span class="c_name_eng">{{$trade_coin->symbol}}/USDC</span>
						</p>

						<p class="coin_num_data in_cell">
							<span class="{{$up_down_color}}"> <b class="last_trade_price">{{number_format($trade_coin->last_trade_price_usd, $trade_coin->decimal_usd)}}</b> USDC <u class="price_change_24h">({{$price_change_24h_number_symbol}}{{number_format($trade_coin->price_change_24h, $trade_coin->decimal_usd)}})</u> </span>
							<span class="{{$up_down_color}}"> ≈ <em class="last_trade_price_currency">{{$trade_local}}</em> {{$local_currency}} <u class="percent_change_24h">({{$percent_change_24h_indicate_symbol}}{{number_format($trade_coin->percent_change_24h, 2)}}%)</u> </span>
						</p>

					</div>

					<div class="coin_state_2">
						<ul>
							<li class="border_r border_b">

								<span>{{ __('market.high') }}</span>
								<span class="high_price_24h align_right"> <b class="number_price">{{number_format($trade_coin->max_price, $trade_coin->decimal_usd)}}</b> <u>USDC</u> </span>

							</li>

							<li class="border_b">

								<span>{{ __('market.volume') }}</span>
								<span class="trade_volume_24h align_right"> <b class="number_price">{{number_format($trade_coin->{'24h_volume'},8)}}</b> <u>USDC</u> </span>

							</li>

							<li class="border_r">

								<span>{{ __('market.low') }}</span>
								<span class="row_price_24h align_right"> <b class="number_price">{{number_format($trade_coin->min_price, $trade_coin->decimal_usd)}}</b> <u>USDC</u> </span>

							</li>

							<li>

								<span>{{ __('market.today') }}</span>
								<span class="trading_value_today align_right"> <b>-</b> <u>USD</u> </span>

							</li>
						</ul>
					</div>

				</div>

				<div class="trans_chart_wrap">
					<input type="hidden" name="trade_fee" value="{{$market->buy_comission/100}}" />
					<div id="chartdiv" class="chart_con"></div>

					<div class="deal_wrap">
						<div class="choice_option_tit">
							<ul>
								<li class="option_li buy_color" id="option_buy_btn">
									<div class="tit">
										<span> <b>{{$trade_coin->symbol}}</b> {{ __('market.buy') }} </span>
									</div>
								</li>
								<li class="option_li sell_color" id="option_sell_btn">
									<div class="tit">
										<span> <b>{{$trade_coin->symbol}}</b> {{ __('market.sell') }} </span>
									</div>
								</li>
							</ul>
						</div>
						<div id="coin_buy_con" class="deal_con coin_buy_con">
							<input type="hidden" name="my_asset_cash" value="{{$user_current_cash_balance}}" />
							<div class="tit">
								<span> <b>{{$trade_coin->symbol}}</b> {{ __('market.buy') }} </span>
							</div>
							<div id="use_balance_buy" class="balance_state">
							{{ __('market.usdc_balance') }} :
								<u>{{number_format($user_current_cash_balance,$trade_coin->decimal_usd)}}</u> USDC
							</div>

							<div class="buysell_wrap">
								<table class="buysell_table">
									<tbody>
										<tr>
											<td><label class="label">{{ __('market.price') }}</label>
											<div class="buysell_input buysell_price">
												<input type="number" id="buy_coin_price" class="buysell_price_inp" data-decimal="{{$trade_coin->decimal_usd}}" value="{{round($trade_coin->last_trade_price_usd, $trade_coin->decimal_usd)}}" placeholder="0"/>
												<span class="currency">USDC</span>
												<span class="up_btn updown_btn"></span>
												<span class="down_btn updown_btn"></span>
											</div></td>
										</tr>
										<tr>
											<td><label class="label">{{ __('market.quantity') }}</label>
											<div class="buysell_input">
												<input type="number" id="buy_max_amount" class="buysell_amt_inp" value="" placeholder="0" />
												<span class="currency">{{$trade_coin->symbol}}</span>
											</div></td>
										</tr>
										<tr>
											<td>
											<ul class="buy_percent">
												<li>
													<button>
														10%
													</button>
												</li>
												<li>
													<button>
														25%
													</button>
												</li>
												<li>
													<button>
														50%
													</button>
												</li>
												<li>
													<button>
														75%
													</button>
												</li>
												<li>
													<button>
														100%
													</button>
												</li>
											</ul></td>
										</tr>
										<tr>
											<td>
											<div class="total_sum_wrap">
												<span class="label">{{ __('market.all_buy_price') }}</span>

												<p>
													<b id="buy_cash_amt">0</b>
													<span class="currency">USDC</span>
												</p>
											</div>
											<div class="buysell_btn buy_btn">
												@auth
													@if(Auth::user()->status == 2)
														<button type="button" class="not_active_btn stop_user_id_warning">계정 정지</button>
													@else
														@if($security_lv < 2)
															@if($security_lv == 0)
																<a class="not_active_btn" href="{{route('mypage.security_setting')}}">{{ __('market.email') }}</a>
															@else
																<a class="not_active_btn" href="{{route('mypage.security_setting')}}">{{ __('market.phone') }}</a>
															@endif
														@else
															<button type="button" onclick="buysell_coin_data('buy','USD','{{$trade_coin->symbol}}');">{{ __('market.buying') }}</button>
														@endif
													@endif
												@else
													<a class="not_active_btn" href="{{route('login')}}">{{ __('market.login') }}</a>
												@endauth
											</div></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div id="coin_sell_con" class="deal_con coin_sell_con">
							<input type="hidden" name="my_asset_coin" value="{{$user_current_coin_balance}}" />
							<div class="tit">
								<span> <b>{{$trade_coin->symbol}}</b> {{ __('market.sell') }} </span>
							</div>
							<div id="use_balance_sell" class="balance_state">
								{{$trade_coin->symbol}} {{ __('market.balance') }} :
								<u>{{number_format($user_current_coin_balance,8)}}</u> {{$trade_coin->symbol}}
							</div>

							<div class="buysell_wrap">
								<table class="buysell_table">
									<tbody>
										<tr>
											<td><label class="label">{{ __('market.price') }}</label>
											<div class="buysell_input buysell_price">
												<input type="number" id="sell_coin_price" class="buysell_price_inp" data-decimal="{{$trade_coin->decimal_usd}}" value="{{round($trade_coin->last_trade_price_usd, $trade_coin->decimal_usd)}}" placeholder="0" />
												<span class="currency">USDC</span>
												<span class="up_btn updown_btn"></span>
												<span class="down_btn updown_btn"></span>
											</div></td>
										</tr>
										<tr>
											<td><label class="label">{{ __('market.quantity') }}</label>
											<div class="buysell_input">
												<input type="number" id="sell_max_amount" class="buysell_amt_inp" value="" placeholder="0" />
												<span class="currency">{{$trade_coin->symbol}}</span>
											</div></td>
										</tr>
										<tr>
											<td>
											<ul class="sell_percent">
												<li>
													<button>
														10%
													</button>
												</li>
												<li>
													<button>
														25%
													</button>
												</li>
												<li>
													<button>
														50%
													</button>
												</li>
												<li>
													<button>
														75%
													</button>
												</li>
												<li>
													<button>
														100%
													</button>
												</li>
											</ul></td>
										</tr>
										<tr>
											<td>
											<div class="total_sum_wrap">
												<span class="label">{{ __('market.all_sell_price') }}</span>

												<p>
													<b id="sell_cash_amt">0</b>
													<span class="currency">USDC</span>
												</p>
											</div>
											<div class="buysell_btn sell_btn">
												@auth
													@if(Auth::user()->status == 2)
														<button type="button" class="not_active_btn stop_user_id_warning">계정 정지</button>
													@else
														@if($security_lv < 2)
															@if($security_lv == 0)
																<a class="not_active_btn" href="{{route('mypage.security_setting')}}">{{ __('market.email') }}</a>
															@else
																<a class="not_active_btn" href="{{route('mypage.security_setting')}}">{{ __('market.phone') }}</a>
															@endif
														@else
															<button type="button" onclick="buysell_coin_data('sell','USD','{{$trade_coin->symbol}}');">{{ __('market.selling') }}</button>
														@endif
													@endif
												@else
												<a class="not_active_btn" href="{{route('login')}}">{{ __('market.login') }}</a>
												@endauth
											</div></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>

					</div>

				</div>

			</div>
			<!-- //②-4 중간박스(코인상태바, 차트, 구매-판매박스) -->

			<!-- ②-3 오른쪽박스 (코인목록, 거래기록)-->
			<div class="right_con">
				<div class="inner_box right_con_inbox-1">
					<div class="tit">
						{{--
						<ul class="market_list_tab">
							<li class="active">
								<label for="usdc_market_list">
									USDC마켓
								</label>
							</li>
							<li>
								<label for="btc_market_list">
									BTC마켓
								</label>
							</li>
							<li>
								<label for="eth_market_list">
									ETH마켓
								</label>
							</li>
						</ul>
						--}}
						<div class="coin_sch_bar">
							<input type="text" id="txtFilter" >
						</div>
						
						<table class="table_label">
							<thead>
								<th>{{ __('market.coin_name') }}</th>
								<th>{{ __('market.last_price') }}</th>
								<th>{{ __('market.yesterday') }}</th>
							</thead>
						</table>
					</div>

					<div class="scl_wrap">
						<input id="usdc_market_list" class="hide" type="radio" name="coin_list" />
						<input id="btc_market_list" class="hide" type="radio" name="coin_list" />
						<input id="eth_market_list" class="hide" type="radio" name="coin_list" />
						<table id="coin_list_table" class="table_in_box coin_table target coin_table-1">
							<tbody>
								@foreach($coins as $coin)
									<tr name="{{__('coin_name.'.$coin->api)}}/{{$coin->api}}" onclick="location.href='{{route('market',$coin->symbol)}}'" data-coin="{{$coin->api}}">
										<td>
										<img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image))}}" alt="{{__('coin_name.'.$coin->api)}}" class="coin_img"/>
										<p>
											{{__('coin_name.'.$coin->api)}}
										</p><span>({{$coin->symbol}}/USDC)</span></td>
										<td><div class="cell"><span class="last_trade_price_usd {{($coin->percent_change_24h >= 0)?'red':'blue'}}">{{number_format($coin->last_trade_price_usd, $coin->decimal_usd)}}</span></div></td>
										<td><div class="cell"><span class="percent_change_24h {{($coin->percent_change_24h >= 0)?'red':'blue'}}">{{number_format($coin->percent_change_24h, 2)}}%</span></div></td>
									</tr>
								@endforeach
							</tbody>
						</table>
						{{-- BTC마켓 --}}
						{{--
						<table class="table_in_box coin_table target coin_table-2">
							<tbody>
								@foreach($coins as $coin)
									<tr name="{{__('coin_name.'.$coin->api)}}/{{$coin->api}}" data-coin="{{$coin->api}}">
										<td>
										<img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image))}}" alt="{{__('coin_name.'.$coin->api)}}" class="coin_img"/>
										<p>
											{{__('coin_name.'.$coin->api)}}
										</p><span>({{$coin->symbol}}/BTC)</span></td>
										<td><div class="cell"><span class="last_trade_price_usd {{($coin->percent_change_24h >= 0)?'red':'blue'}}">{{number_format($coin->last_trade_price_usd, $coin->decimal_usd)}}</span></div></td>
										<td><div class="cell"><span class="percent_change_24h {{($coin->percent_change_24h >= 0)?'red':'blue'}}">{{number_format($coin->percent_change_24h, 2)}}%</span></div></td>
									</tr>
								@endforeach
							</tbody>
						</table>
						--}}
						{{-- ETH마켓 --}}
						{{--
						<table class="table_in_box coin_table target coin_table-3">
							<tbody>
								@foreach($coins as $coin)
									<tr name="{{__('coin_name.'.$coin->api)}}/{{$coin->api}}" data-coin="{{$coin->api}}">
										<td>
										<img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image))}}" alt="{{__('coin_name.'.$coin->api)}}" class="coin_img"/>
										<p>
											{{__('coin_name.'.$coin->api)}}
										</p><span>({{$coin->symbol}}/ETH)</span></td>
										<td><div class="cell"><span class="last_trade_price_usd {{($coin->percent_change_24h >= 0)?'red':'blue'}}">{{number_format($coin->last_trade_price_usd, $coin->decimal_usd)}}</span></div></td>
										<td><div class="cell"><span class="percent_change_24h {{($coin->percent_change_24h >= 0)?'red':'blue'}}">{{number_format($coin->percent_change_24h, 2)}}%</span></div></td>
									</tr>
								@endforeach
							</tbody>
						</table>
						--}}
					</div>

				</div>

				<div class="inner_box right_con_inbox-2">
					<div class="tit">
						<h6>{{ __('market.trade_list') }}</h6>
						<span id="toggle_but" class="toggle_btn"> <span></span> </span>
					</div>

					<div class="scl_wrap">
						<table id="trade_list" class="table_in_box trans_record">
							<tbody>
								@forelse($trade_historys as $trade_history)
									<tr class="{{ ($trade_history->last_trade_kind != null)? $trade_history->last_trade_kind:'' }}">
										<td>{{number_format($trade_history->contract_coin_amt, 8)}}</td>
										<td>{{number_format($trade_history->sell_coin_price,2)}}</td>
										@if(config('app.locale') == 'kr' || config('app.locale') == 'jp')
											<td>{{date("m-d H:i:s", strtotime("+9 hours", strtotime($trade_history->created_dt)))}}</td>
										@elseif(config('app.locale') == 'ch')
											<td>{{date("m-d H:i:s", strtotime("+8 hours", strtotime($trade_history->created_dt)))}}</td>
										@elseif(config('app.locale') == 'th')
											<td>{{date("m-d H:i:s", strtotime("+7 hours", strtotime($trade_history->created_dt)))}}</td>
										@else
											<td>{{date("m-d H:i:s", strtotime($trade_history->created_dt))}}</td>
										@endif
									</tr>
								@empty

								@endforelse
							</tbody>
						</table>
					</div>
				</div>

			</div>
			<!-- //②-3 오른쪽박스 (코인목록, 거래기록)-->

		</div>
		<!--//②거래소영역 -->
		@auth
			<!--③대기주문-거래대기영역 start-->
			<div id="readyorder_wrap" class="trans_tbl_con trans_tbl_con_1">

				<h1>{{ __('market.wait_order') }}</h1>

				<div class="trans_tbl_wrap">
					<table class="trans_tbl">
						<thead class="tbl_head">
							<tr>
								<th>{{ __('market.time') }}</th>
								<th>{{ __('market.coin') }}</th>
								<th>{{ __('market.division') }}</th>
								<th>{{ __('market.price') }}</th>
								<th>{{ __('market.quantity') }}</th>
								<th>{{ __('market.conclusion_rate') }}</th>
								<th>{{ __('market.sum_of_money') }}</th>
								<th>{{ __('market.now') }}</th>
							</tr>
						</thead>
						<tbody id="ready_order_queue" class="tbl_body">
							@forelse($wait_trades as $wait_trade)
								<tr class="queue_{{strtoupper($wait_trade->cointype)}}">
									<td><span>{{date("m-d H:i:s", strtotime("+9 hours", strtotime($wait_trade->created_dt)))}}</span></td>
									<td><span>{{__('coin_name.'.strtolower($wait_trade->cointype))}}({{strtoupper($wait_trade->cointype)}})</span></td>
									<td><span>{{__('market.'.$wait_trade->type)}}</span></td>
									<td><span>{{number_format($wait_trade->{$wait_trade->type.'_coin_price'}, $trade_coin->decimal_usd)}}</span></td>
									<td><span>{{number_format($wait_trade->{$wait_trade->type.'_COIN_amt'},8)}} {{strtoupper($wait_trade->cointype)}}</span></td>
									<td><span>{{number_format($wait_trade->trade_percentage,2)}}%</span></td>
									<td><span>{{number_format(($wait_trade->{$wait_trade->type.'_coin_price'} * $wait_trade->{$wait_trade->type.'_COIN_amt'}), $trade_coin->decimal_usd)}}</span></td>
									<td>
									<p class="status_type">
										{{__('market.'.$wait_trade->status)}}
									</p>
									@if($wait_trade->status == 'OnProgress')
										<button type="submit" id="btc_cancel_request_{{$wait_trade->id}}" class="btc_cancel_request" data-id="{{$wait_trade->id}}" onclick="trade_cancel({{$wait_trade->id}})">
										{{ __('market.order_cancel') }}
										</button></td>
									@endif
								</tr>
							@empty
								<tr>
									<td class="non_data" colspan="8" rowspan="5">{{ __('market.no_wait') }}</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>

			</div>

			<div class="trans_tbl_con trans_tbl_con_2">

				<h1>{{ __('market.day_trade') }}</h1>

				<div class="trans_tbl_wrap">
					<table class="trans_tbl">
						<thead class="tbl_head">
							<tr>
								<th>{{ __('market.time') }}</th>
								<th>{{ __('market.coin_list') }}</th>
								<th>{{ __('market.trade_type') }}</th>
								<th>{{ __('market.price') }}</th>
								<th>{{ __('market.quantity') }}</th>
								<th>{{ __('market.all_amount') }}</th>
							</tr>
						</thead>
						<tbody id="ready_history_queue" class="tbl_body">
							@forelse($today_historys as $today_history)
								@if($today_history->buyer_username == $username)
									<tr>
										<td><span>{{date("m-d H:i:s", strtotime("+9 hours", strtotime($today_history->created_dt)))}}</span></td>
										<td><span>{{__('coin_name.'.strtolower($today_history->cointype))}}({{strtoupper($today_history->cointype)}})</span></td>
										<td><span>{{__('market.buy')}}</span></td>
										<td><span>{{number_format($today_history->buy_coin_price, $trade_coin->decimal_usd)}}</span></td>
										<td><span>{{number_format($today_history->contract_coin_amt, 8)}} {{strtoupper($today_history->cointype)}}</span></td>
										<td><span>{{number_format($today_history->trade_usd_amt_buy, $trade_coin->decimal_usd)}} USDC</span></td>
									</tr>
								@else
									<tr>
										<td><span>{{date("m-d H:i:s", strtotime("+9 hours", strtotime($today_history->created_dt)))}}</span></td>
										<td><span>{{__('coin_name.'.strtolower($today_history->cointype))}}({{strtoupper($today_history->cointype)}})</span></td>
										<td><span>{{__('market.sell')}}</span></td>
										<td><span>{{number_format($today_history->sell_coin_price, $trade_coin->decimal_usd)}}</span></td>
										<td><span>{{number_format($today_history->contract_coin_amt, 8)}} {{strtoupper($today_history->cointype)}}</span></td>
										<td><span>{{number_format($today_history->trade_usd_amt_sell, $trade_coin->decimal_usd)}} USDC</span></td>
									</tr>
								@endif
							@empty
								<tr>
									<td class="non_data" colspan="8" rowspan="5">{{ __('market.no_today') }}</td>
								</tr>
							@endforelse
						</tbody>
					</table>

				</div>

			</div>
			<!--③대기주문-거래대기영역 end-->
			<script>
			$(function(){ 
				update_erc_eth_balance();		
			});
			</script>
		@endauth
	</div>

</div>


@endsection
