@extends(session('theme').'.pc.layouts.app')

@section('content')

<input type="hidden" name="standard_api" value="{{ $standard_info->api }}" />
<input type="hidden" name="coin_api" value="{{$trade_coin->apiname}}" />
<input type="hidden" name="coin_apiname" value="{{$trade_coin->api}}" />
<input type="hidden" name="setting_url" value="{{$market->url}}chart_new" />
<input type="hidden" name="chart_symbol" value="{{$trade_coin->api}}/{{ $symbol_text }}" />
<input type="hidden" name="decimal_usd" value="{{ $trade_coin->{'decimal_'.$standard_info->api} }}" />
<input type="hidden" name="call_unit" value="{{$trade_coin->call_unit}}" />
<input type="hidden" name="hm_cur" value="{{ $hm_cur }}" />
<input type="hidden" name="hm_usd" value="{{ $standard_info->last_trade_price_krw }}" />
<input type="hidden" name="hm_dec" value="{{ $hm_decimal }}" />

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
						<li id="arrow_both_btn" class="arrow_both active">
							전체
						</li>
						<li id="arrow_up_btn" class="arrow_up">
							매도
						</li>
						<li id="arrow_down_btn" class="arrow_down">
							매수
						</li>
					</ul>
					<h6>{{ __('market.arc_window') }}</h6>

					<table class="table_label">
						<thead>
							<th>{{ __('market.price') }}({{ $symbol_text }})</th>
							<th>{{ __('market.quantity') }}({{$trade_coin->symbol}})</th>
						</thead>
					</table>
				</div>
				<div class="up_wrap wait_wrap">
					<table id="sell_wait" class="table_in_box both_table up_table">
						<tbody>
							@if($sell_ads_cnt > 0)
								@foreach($sell_adss as $sell_ads)
								<tr data-price="{{round($sell_ads->price, $trade_coin->{'decimal_'.$standard_info->api})}}" data-amt="{{round($sell_ads->amt, $trade_coin->{'decimal_'.$standard_info->api})}}">
									<td class="blue"><span class="orderbook_price">{{number_format($sell_ads->price, $trade_coin->{'decimal_'.$standard_info->api})}}</span></td>
									<td><span class="orderbook_amt">{{number_format($sell_ads->amt, 8)}}</span><div class="per_bar" style="width: {{$sell_ads->amt/$total_amt_sell->total_amt_sell}}%;"></div></td>
								</tr>
								@endforeach
							@else
								<!--
								<tr data-price="1000000" data-amt="0.00001">
									<td class="blue"><div class="per_bar" style="width: 34%;"></div><span>1,000,000</span></td>
									<td><span>0.00001</span></td>
								</tr>
								-->
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
								<tr data-price="{{round($buy_ads->price, $trade_coin->{'decimal_'.$standard_info->api})}}" data-amt="{{round($buy_ads->amt, $trade_coin->{'decimal_'.$standard_info->api})}}">
									<td class="red"><span class="orderbook_price">{{number_format($buy_ads->price, $trade_coin->{'decimal_'.$standard_info->api})}}</span></td>
									<td><span class="orderbook_amt">{{number_format($buy_ads->amt, 8)}}</span><div class="per_bar" style="width: {{$buy_ads->amt/$total_amt_buy->total_amt_buy}}%;"></div></td>
								</tr>
								@endforeach
							@else
								<!--
								<tr data-price="1000000" data-amt="0.00001">
									<td class="red"><span>1,000,000</span></td>
									<td><span>0.00001</span><div class="per_bar" style="width: 34%;"></div></td>
								</tr>
								-->
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

				<div id="top_ticker" class="trans_hd" data-coin="{{$trade_coin->api}}" data-market="{{$standard_info->api}}">

					<div class="coin_state_1">

						<div class="coin_name in_cell">
							<img src="/images/coin_img/{{$trade_coin->api}}.png" alt="{{$trade_coin->api}}_icon" />
							<div>
								<span class="c_name">{!! __('coin_name.'.$trade_coin->api) !!}</span>
								<span class="c_name_eng">{{$trade_coin->symbol}}/{{ $symbol_text }}</span>
							</div>
						</div>

						<p class="coin_num_data in_cell">
							<span>
								<b class="last_trade_price {{$up_down_color}}">{{number_format($trade_coin->{'last_trade_price_'.$standard_info->api}, $trade_coin->{'decimal_'.$standard_info->api})}}</b> {{ $symbol_text }}
							</span>
							<span>
								{{ __('market.yesterday') }}
								<em class="price_change_24h {{$up_down_color}}">
									{{$price_change_24h_number_symbol}}{{number_format($trade_coin->{'price_change_24h_'.$standard_info->api}, $trade_coin->{'decimal_'.$standard_info->api})}}
								</em>
								<em class="percent_change_24h {{$up_down_color}}">
									({{$price_change_24h_number_symbol}}{{number_format($trade_coin->{'percent_change_24h_'.$standard_info->api}, 2)}}%) {{$percent_change_24h_indicate_symbol}}
								</em>
							</span>
						</p>

					</div>

					<div class="coin_state_2">
						<ul>
							<li class="border_r border_b">

								<span>{{ __('market.high') }}</span>
								<span class="high_price_24h align_right"> <b class="number_price">{{number_format($trade_coin->{'max_price_'.$standard_info->api}, $trade_coin->{'decimal_'.$standard_info->api})}}</b> <u>{{ $symbol_text }}</u> </span>

							</li>

							<li class="border_b">

								<span>{{ __('market.volume') }}</span>
								<span class="trade_volume_24h align_right"> <b class="number_price">{{number_format($trade_coin->{'24h_volume_'.$standard_info->api},8)}}</b> <u>{{ $trade_coin->symbol }}</u> </span>

							</li>

							<li class="border_r">

								<span>{{ __('market.low') }}</span>
								<span class="row_price_24h align_right"> <b class="number_price">{{number_format($trade_coin->{'min_price_'.$standard_info->api}, $trade_coin->{'decimal_'.$standard_info->api})}}</b> <u>{{ $symbol_text }}</u> </span>

							</li>

							<li>

								<span>{{ __('market.today') }}</span>
								<span class="trading_value_today align_right"> <b>{{number_format($trade_coin->{'price_all_24h_'.$standard_info->api}, $trade_coin->{'decimal_'.$standard_info->api})}}</b> <u>{{ $symbol_text }}</u> </span>

							</li>
						</ul>
					</div>

				</div>
				@if($trade_coin->market == 'sports')
						@if(!empty($event_info->winning_Array))
						<div class="event_infor_panel">
							<div class="event_bar">
								<div class="event_bar_mask">
									<ul class="event_ul">
										@foreach(array_chunk($event_info->winning_Array, 2) as $winning_chunk_array)
										<li>
											@foreach($winning_chunk_array as $winning)
												{{ $winning }} <span style="white-space: pre;">&#9;&#9;</span>
											@endforeach
										</li>
										@endforeach
									</ul>
								</div>

								<!-- <div id="event_x_btn" class="close_btn">
									<span></span>
								</div>

								<div class="event_show">
									NOTICE
								</div> -->

								<span id="event_next_btn" class="event_next">NEXT</span>
							</div>					
						</div>
						@endif
					<div class="sports_infor_panel">
						{{$trade_coin->league_name}} <b>{{$trade_coin->league_rank}}위</b> <b>·</b> 구단가치 <b>{{ $trade_coin->{'club_value_'.config('app.country')} }}</b> <b>·</b> 전세계 팬 <b>{{ $trade_coin->{'world_pan_'.config('app.country')} }}</b>
					</div>
				@endif
				<div class="trans_chart_wrap">
					<input type="hidden" name="trade_fee" value="{{$market->buy_comission/100}}" />
					<div id="chartdiv" class="chart_con"></div>
					<div class="deal_n_history_wrap">
						<div class="deal_wrap">
							<div class="choice_option_tit">
								<ul>
									<li class="option_li buy_color" id="option_buy_btn">
										<div class="tit">
											<span> 매수 </span>
										</div>
									</li>
									<li class="option_li sell_color" id="option_sell_btn">
										<div class="tit">
											<span> 매도 </span>
										</div>
									</li>
								</ul>
							</div>
							<div id="coin_buy_con" class="deal_con coin_buy_con">
								<input type="hidden" name="my_asset_cash" value="{{$user_current_cash_balance}}" />
								<div class="balance_state">
									<span id="use_balance_buy">
										구매가능 :
										<u>{{number_format($user_current_cash_balance,$trade_coin->{'decimal_'.$standard_info->api})}}</u> {{ $symbol_text }}
									</span>
									<span>
										※수수료 {{round($market->buy_comission, 2)}}%
									</span>
								</div>

								<div class="buysell_wrap">
									<table class="buysell_table">
										<tbody>
											<tr>
												<td>
													<div class="price_inp_box">
														<label class="label">매수 가격</label>
														<div class="buysell_input buysell_price">
															<input type="number" id="buy_coin_price" class="buysell_price_inp" data-decimal="{{ $trade_coin->{'decimal_'.$standard_info->api} }}" value="{{ number_format($trade_coin->{'last_trade_price_'.$standard_info->api}, $trade_coin->{'decimal_'.$standard_info->api},'.','') }}" placeholder="0"/>
															<span class="currency">{{ $symbol_text }}</span>
															<span class="up_btn updown_btn"></span>
															<span class="down_btn updown_btn"></span>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="amt_inp_box">
														<label class="label">매수 수량</label>
														<div class="buysell_input">
															<input type="number" id="buy_max_amount" class="buysell_amt_inp" value="" placeholder="0" />
															<span class="currency">{{$trade_coin->symbol}}</span>
														</div>
													</div>
												</td>
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
													<div class="fee_box">
														<span>수수료</span>
														<span><em id="buy_fee">0</em><em>{{ $symbol_text }}</em></span>
													</div>
												</td>
											</tr>
											<tr>
												<td>
												<div class="total_sum_wrap">
													<span class="label">{{ __('market.all_buy_price') }}(수수료 포함)</span>

													<p>
														<b id="buy_cash_amt">0</b>
														<span class="currency">{{ $symbol_text }}</span>
													</p>
												</div>
												<div class="buysell_btn buy_btn">
													@auth
													@if(Auth::user()->status == 2)
													<button type="button" class="not_active_btn" onclick="alert('비정상적인 활동으로 이용이 정지되셨습니다.\n관리자에게 문의하세요.')">거래정지</button>
													@else
													<button type="button" onclick="buysell_coin_data('buy','{{ $standard_info->api }}','{{$trade_coin->symbol}}');">{{ __('market.buying') }}</button>
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
							<div id="coin_sell_con" class="deal_con coin_sell_con" style="display:none;">
								<input type="hidden" name="my_asset_coin" value="{{$user_current_coin_balance}}" />
								<div class="balance_state">
									<span id="use_balance_sell">
										판매가능 :
										<u>{{number_format($user_current_coin_balance,8)}}</u> {{$trade_coin->symbol}}
									</span>
									<span>
										※수수료 {{round($market->sell_comission, 2)}}%
									</span>
								</div>

								<div class="buysell_wrap">
									<table class="buysell_table">
										<tbody>
											<tr>
												<td>
													<div class="price_inp_box">
														<label class="label">매도 가격</label>
														<div class="buysell_input buysell_price">
															<input type="number" id="sell_coin_price" class="buysell_price_inp" data-decimal="{{ $trade_coin->{'decimal_'.$standard_info->api} }}" value="{{ number_format($trade_coin->{'last_trade_price_'.$standard_info->api},$trade_coin->{'decimal_'.$standard_info->api},'.','') }}" placeholder="0" />
															<span class="currency">{{ $symbol_text }}</span>
															<span class="up_btn updown_btn"></span>
															<span class="down_btn updown_btn"></span>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="amt_inp_box">
														<label class="label">매도 수량</label>
														<div class="buysell_input">
															<input type="number" id="sell_max_amount" class="buysell_amt_inp" value="" placeholder="0" />
															<span class="currency">{{$trade_coin->symbol}}</span>
														</div>
													</div>
												</td>
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
													<div class="fee_box">
														<span>수수료</span>
														<span><em id="sell_fee">0</em><em>{{ $symbol_text }}</em></span>
													</div>
												</td>
											</tr>
											<tr>
												<td>
												<div class="total_sum_wrap">
													<span class="label">{{ __('market.all_sell_price') }}(수수료 포함)</span>

													<p>
														<b id="sell_cash_amt">0</b>
														<span class="currency">{{ $symbol_text }}</span>
													</p>
												</div>
												<div class="buysell_btn sell_btn">
													@auth
													@if(Auth::user()->status == 2)
													<button type="button" class="not_active_btn" onclick="alert('비정상적인 활동으로 이용이 정지되셨습니다.\n관리자에게 문의하세요.')">거래정지</button>
													@else
													<button type="button" onclick="buysell_coin_data('sell','{{ $standard_info->api }}','{{$trade_coin->symbol}}');">{{ __('market.selling') }}</button>
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
						<div class="history_wrap">
							<div class="inner_box right_con_inbox-2">
								<div class="tit">
									<h6>{{ __('market.trade_list') }}</h6>
								</div>
								<div>
									<table class="trans_record">
										<thead>
											<tr>
												<th>{{ __('market.quantity') }}</th>
												<th>{{ __('market.price') }}</th>
												<th>{{ __('market.time') }}</th>
											</tr>
										</thead>
									</table>
								</div>
								<div class="scl_wrap">
									<table id="trade_list" class="table_in_box trans_record" data-latest="{{isset($trade_historys[0]) ? $trade_historys[0]->created : ''}}">
										<tbody>
											@forelse($trade_historys as $trade_history)
												<tr class="{{ ($trade_history->last_trade_kind != null)? $trade_history->last_trade_kind:'' }}">
													<td>{{ number_format($trade_history->contract_coin_amt, 8) }}</td>
													<td>{{ number_format($trade_history->sell_coin_price ,$trade_coin->{'decimal_'.$standard_info->api}) }}</td>
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

					</div>
					@auth
					<div class="my_trade_infor">
						<div class="my_trade_infor_nav">
							<ul>
								<li data-kind="ready_order">미체결</li>
								<li data-kind="today_trade">당일매매</li>
								<li class="active" data-kind="my_assets">잔고 및 자산현황</li>
								<span class="underline"></span>
							</ul>
						</div>
						<div class="my_trade_table_wrap">
							<!--//②거래소영역 -->
							<div id="my_assets_tbl_wrap" class="trans_tbl_con" data-kind="my_assets">
								<h1>잔고 및 자산현황</h1>
								<div class="my_assets_avg">
									<ul>
										<li>
											<span>총 매수금액</span>
											<span><b id="total_buying">{{ number_format(bcmul($total_buying,1,0),0) }}</b>원</span>
										</li>
										<li>
											<span>총 평가수익</span>
											<span><b id="total_eval_revenue">{{ number_format(bcmul($total_eval_revenue,1,0),0) }}</b>원</span>
										</li>
										<li>
											<span>보유원화</span>
											<span><b id="total_balance_krw">{{ number_format(bcmul($coin_balance_krw,1,0),0) }}</b>원</span>
										</li>
										<li>
											<span>총 평가금액</span>
											<span><b id="total_holding">{{ number_format(bcmul($total_holding,1,0),0) }}</b>원</span>
										</li>
										<li>
											<span>총 추정자산</span>
											<span><b id="total_asset">{{ number_format(bcmul($total_asset,1,0),0) }}</b>원</span>
										</li>
										<li>
											<span>총 평가수익률</span>
											<span><b id="total_eval_percent">{{ number_format(bcmul($total_eval_percent,1,2),2) }}</b>%</span>
										</li>
									</ul>
								</div>
								<div class="trans_tbl_wrap">
									<table class="trans_tbl">
										<thead class="tbl_head">
											<tr>
												<th>보유코인</th>
												<th>보유수량</th>
												<th>매수평균가</th>
												<th>매수금액</th>
												<th>평가금액</th>
												<th>평가손익</th>
											</tr>
										</thead>
									</table>
									<div class="tbody_wrap">
										<table class="trans_tbl">
											<tbody id="my_assets_tbl">
											@foreach($coins as $coin)
												<tr data-symbol="{{ $coin->symbol }}" style="{{ $result[$coin->api]['balance'] == 0 ? 'display:none' : '' }}">
													<td>
														<div>{!! __('coin_name.'.$coin->api) !!}</div>
														<div>{{ $coin->symbol }}</div>
													</td>
													<td><b name="asset_balance">{{ number_format($result[$coin->api]['balance'],8) }}</b><em class="currency">{{ $coin->symbol }}</em></td>
													<td><b name="asset_avg">{{ number_format($result[$coin->api]['avg'],0) }}</b><em class="currency">원</em></td>
													<td><b name="asset_buying">{{ number_format($result[$coin->api]['buying'],0) }}</b><em class="currency">원</em></td>
													<td><b name="asset_eval">{{ number_format($result[$coin->api]['eval'],0) }}</b><em class="currency">원</em></td>
													<td>
														<div>
															<b name="asset_percent">{{ number_format($result[$coin->api]['eval_percent'],2) }}</b><em class="currency">%</em>
														</div>
														<div>
															<b name="asset_revenue">{{ number_format($result[$coin->api]['eval_revenue'],0) }}</b><em class="currency">원</em>
														</div>
													</td>
												</tr>
											@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<!--③대기주문-거래대기영역 start-->
							<div id="readyorder_wrap" class="trans_tbl_con trans_tbl_con_1" data-kind="ready_order" style="display:none;">

								<h1>{{ __('market.wait_order') }}</h1>

								<div class="trans_tbl_wrap">
									<table class="trans_tbl">
										<thead class="tbl_head">
											<tr>
												<th style="width:12.5%;">{{ __('market.time') }}</th>
												<th style="width:12.5%;">{{ __('market.coin') }}</th>
												<th style="width:9.5%;">{{ __('market.division') }}</th>
												<th style="width:13.5%;">{{ __('market.order_price') }}</th>
												<th style="width:15.5%;">{{ __('market.order_quantity') }}</th>
												<th style="width:10.5%;">체결률</th>
												<th style="width:13.5%;">총액</th>
												<th style="width:12.5%;">{{ __('market.now') }}</th>
											</tr>
										</thead>
									</table>
									<div class="tbody_wrap">
										<table class="trans_tbl">
											<tbody id="ready_order_queue" class="tbl_body">
												@forelse($wait_trades as $wait_trade)
													<tr class="queue_{{strtoupper($wait_trade->cointype)}}">
														<td style="width:12.5%;"><span>{{date("m-d H:i:s", strtotime("+9 hours", strtotime($wait_trade->created_dt)))}}</span></td>
														<td style="width:12.5%;"><span>{{__('coin_name.'.strtolower($wait_trade->cointype))}}({{strtoupper($wait_trade->cointype)}})</span></td>
														<td style="width:9.5%;"><span>{{__('market.'.$wait_trade->type)}}</span></td>
														<td style="width:13.5%;"><span>{{number_format($wait_trade->{$wait_trade->type.'_coin_price'}, $trade_coin->{'decimal_'.$standard_info->api})}}</span></td>
														<td style="width:15.5%;"><span>{{number_format($wait_trade->{$wait_trade->type.'_COIN_amt'},8)}} {{strtoupper($wait_trade->cointype)}}</span></td>
														<td style="width:10.5%;"><span>{{number_format($wait_trade->trade_percentage,2)}}%</span></td>
														<td style="width:13.5%;"><span>{{number_format(($wait_trade->{$wait_trade->type.'_coin_price'} * $wait_trade->{$wait_trade->type.'_COIN_amt'}), $trade_coin->{'decimal_'.$standard_info->api})}}</span></td>
														<td style="width:12.5%;">
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

							</div>

							<div class="trans_tbl_con trans_tbl_con_2" data-kind="today_trade" style="display:none;">

								<h1>{{ __('market.day_trade') }}</h1>

								<div class="trans_tbl_wrap">
									<table class="trans_tbl">
										<thead class="tbl_head">
											<tr>
												<th style="width:13.6%;">{{ __('market.time') }}</th>
												<th style="width:13.6%;">{{ __('market.coin_list') }}</th>
												<th style="width:10.6%;">{{ __('market.trade_type') }}</th>
												<th style="width:20.6%;">{{ __('market.price') }}</th>
												<th style="width:20.6%;">{{ __('market.quantity') }}</th>
												<th style="width:20.6%;">{{ __('market.all_amount') }}</th>
											</tr>
										</thead>
									</table>
									<div class="tbody_wrap">
										<table class="trans_tbl">
											<tbody id="ready_history_queue" class="tbl_body">
												@forelse($today_historys as $today_history)
													@if($today_history->buyer_username == $username)
														<tr>
															<td style="width:13.6%;"><span>{{date("m-d H:i:s", strtotime("+9 hours", strtotime($today_history->created_dt)))}}</span></td>
															<td style="width:13.6%;"><span>{{__('coin_name.'.strtolower($today_history->cointype))}}({{strtoupper($today_history->cointype)}})</span></td>
															<td style="width:10.6%;"><span>{{__('market.buy')}}</span></td>
															<td style="width:20.6%;"><span>{{number_format($today_history->buy_coin_price, $trade_coin->{'decimal_'.$standard_info->api})}}</span></td>
															<td style="width:20.6%;"><span>{{number_format($today_history->contract_coin_amt, 8)}} {{strtoupper($today_history->cointype)}}</span></td>
															<td style="width:20.6%;"><span>{{number_format($today_history->trade_total_buy, $trade_coin->{'decimal_'.$standard_info->api})}} {{ $symbol_text }}</span></td>
														</tr>
													@else
														<tr>
															<td style="width:13.6%;"><span>{{date("m-d H:i:s", strtotime("+9 hours", strtotime($today_history->created_dt)))}}</span></td>
															<td style="width:13.6%;"><span>{{__('coin_name.'.strtolower($today_history->cointype))}}({{strtoupper($today_history->cointype)}})</span></td>
															<td style="width:10.6%;"><span>{{__('market.sell')}}</span></td>
															<td style="width:20.6%;"><span>{{number_format($today_history->sell_coin_price, $trade_coin->{'decimal_'.$standard_info->api})}}</span></td>
															<td style="width:20.6%;"><span>{{number_format($today_history->contract_coin_amt, 8)}} {{strtoupper($today_history->cointype)}}</span></td>
															<td style="width:20.6%;"><span>{{number_format($today_history->trade_total_sell, $trade_coin->{'decimal_'.$standard_info->api})}} {{ $symbol_text }}</span></td>
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

							</div>
							<!--③대기주문-거래대기영역 end-->
						</div>
					</div>
					@endauth
					@if($comunity_exist)
					<!-- 게시판 리스트 뷰 -->
					<div class="cs_table_wrap comunity_table_wrap">
						<div class="comunity_hd">
							<h3>{{__('coin_name.'.$trade_coin->api)}}({{$trade_coin->api}}) 게시판</h3>
							<a href="/comunity?board_name={{$trade_coin->api}}" target="_blank">+ 더보기</a>
						</div>
						<table class="table_label">
							<thead>
								<tr>
									<th class="hits_th">{{ __('support.hits') }}</th>
									<th class="recommend_th">{{ __('support.recom') }}</th>
									<th class="title_th">{{ __('support.title') }}</th>
									<th class="writer_th">{{ __('support.writer') }}</th>
									<th class="date_th">{{ __('support.date') }}</th>
								</tr>
							</thead>
						</table>

						<table class="cs_table">
							<tbody>
								@forelse($comunity_lists as $board)
								<tr>
									<td class="hits_td">{{number_format($board->hit)}}</td>
									<td class="recommend_td">{{number_format($board->recomend)}}</td>
									<td class="title_td">
										@if($board->secret_key == NULL)
										<a href="{{ route('comunity.show', $board->id) }}?board_name={{$trade_coin->api}}">
											@if($board->images != NULL || $board->images != '')
											<!-- ① 게시물에 이미지 있을 경우, 썸네일 -->
											<span class="comunity_board_thumb"></span>
											<!-- ② 새로 올린 게시글 뱃지 -->
											@endif

											@if(date_diff(new DateTime($board->created_at), $today)->days < 2)
											<img src="/images/icon/new_icon.svg" alt="new board">
											@endif
											<em class="comunity_board_title">{{$board->title}}</em>

											<!-- ③ 댓글 수 보이기 -->
											<b class="reply_amt">{{number_format($board->comment_cnt)}}</b>
										</a>
										@else
										<a href="#" onclick="custom_alert_popup_open('#modal_popup_change_pw',{{$board->id}})">
											@if($board->images != NULL || $board->images != '')
											<!-- ① 게시물에 이미지 있을 경우, 썸네일 -->
											<span class="comunity_board_thumb"></span>
											<!-- ② 새로 올린 게시글 뱃지 -->
											@endif
											@if(date_diff(new DateTime($board->created_at), $today)->days < 2)
											<img src="/images/icon/new_icon.svg" alt="new board">
											@endif

											<em class="comunity_board_title">{{$board->title}}</em>

											<!-- ③ 댓글 수 보이기 -->
											<b class="reply_amt">{{number_format($board->comment_cnt)}}</b>
										</a>
										@endif
									</td>
									<td class="writer_td">{{$board->writer_nickname}}</td>
									<td class="date_td">{{date("Y-m-d", strtotime($board->created_at))}}</td>
								</tr>
									@foreach($re_board_lists as $re_board)
										@if($re_board->re_id == $board->id)
											<tr>
												<td class="hits_td">{{number_format($re_board->hit)}}</td>
												<td class="recommend_td">{{number_format($re_board->recomend)}}</td>
												<td class="title_td">
													@if($board->secret_key == NULL)
													<a href="{{ route('comunity.show', $re_board->id) }}?board_name={{$trade_coin->api}}">
														<i class="fal fa-reply" style="transform: rotate( 180deg );font-size: 16px;margin-right: 10px;"></i>
														@if($re_board->images != NULL || $re_board->images != '')
														<!-- ① 게시물에 이미지 있을 경우, 썸네일 -->
														<span class="comunity_board_thumb"></span>
														<!-- ② 새로 올린 게시글 뱃지 -->
														@endif
														<img src="/images/icon/new_icon.svg" alt="new board">

														<em class="comunity_board_title">{{$re_board->title}}</em>

														<!-- ③ 댓글 수 보이기 -->
														<b class="reply_amt">{{number_format($re_board->comment_cnt)}}</b>
													</a>
													@else
													<a href="#" onclick="custom_alert_popup_open('#modal_popup_change_pw',{{$board->id}})">
														<i class="fal fa-reply" style="transform: rotate( 180deg );font-size: 16px;margin-right: 10px;"></i>
														@if($re_board->images != NULL || $re_board->images != '')
														<!-- ① 게시물에 이미지 있을 경우, 썸네일 -->
														<span class="comunity_board_thumb"></span>
														<!-- ② 새로 올린 게시글 뱃지 -->
														@endif
														<img src="/images/icon/new_icon.svg" alt="new board">

														<em class="comunity_board_title">{{$re_board->title}}</em>

														<!-- ③ 댓글 수 보이기 -->
														<b class="reply_amt">{{number_format($re_board->comment_cnt)}}</b>
													</a>
													@endif
												</td>
												<td class="writer_td">{{$re_board->writer_nickname}}</td>
												<td class="date_td">{{date("Y-m-d", strtotime($re_board->created_at))}}</td>
											</tr>
										@endif
									@endforeach

								<!-- 공지사항은 제목색상이 다름 -->

								<tr class="notice_tr hide">
									<td class="hits_td">425465</td>
									<td class="recommend_td">556</td>
									<td class="title_td">
										<a href="{{ route('comunity.show', '3') }}">{{-- {{$board->title}} --}}
											<em class="comunity_board_title">공지사항은 제목 색상이 다릅니다.</em>
											<!-- ③ 댓글 수 보이기 -->
											<b class="reply_amt">34</b>
										</a>
									</td>
									<td class="writer_td">스포와이드</td>
									<td class="date_td">{{-- {{date("Y-m-d", $board->created)}} --}}2019-08-22</td>
								</tr>


								@empty
								<tr>
									<td colspan="4" class="non_data"><i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('support.no_board') }}</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
					<!-- // 게시판 리스트 뷰 -->
					@endif
				</div>

			</div>
			<!-- //②-4 중간박스(코인상태바, 차트, 구매-판매박스) -->

			<!-- ②-3 오른쪽박스 (코인목록, 거래기록)-->
			<div class="right_con">
				<div class="inner_box right_con_inbox-1">
					<div class="tit bd_0">
						<ul class="market_list_tab">
							<li {{ $trade_coin->market == 'sports' ? ' class=active ' : '' }} >
								<label for="usdc_market_list">
									SPORTS COIN
								</label>
							</li>
							<li {{ $trade_coin->market == 'public' ? ' class=active ' : '' }} >
								<label for="krw_market_list">
									PUBLIC COIN
								</label>
							</li>
							<span class="underline"></span>
						</ul>
						<div class="coin_sch_bar">
							<input type="text" id="txtFilter" autocomplete="off" placeholder="{{ __('market.coin_search') }}">
						</div>

						{{-- 정혜진이 고쳐야 할 곳 --}}
						{{--
						<table class="coin_list_thead">
							<thead>
								<th>{{ __('market.coin_name') }}</th>
								<th>{{ __('market.last_price') }}</th>
								<th>{{ __('market.yesterday') }}</th>
							</thead>
						</table>
						--}}
					</div>

					<div class="scl_wrap">
						<input id="usdc_market_list" class="hide" type="radio" name="coin_list" {{ $trade_coin->market == 'sports' ? 'checked=checked' : '' }} />
						<input id="krw_market_list" class="hide" type="radio" name="coin_list" {{ $trade_coin->market == 'public' ? 'checked=checked' : '' }} />
						{{-- SPORTS COIN --}}
						<table id="coin_list_table_usd" class="table_in_box coin_table target coin_table_renew">
							<thead>
								<th>{{ __('market.coin_name') }}</th>
								<th>{{ __('market.last_price') }}</th>
								<th>{{ __('market.yesterday') }}</th>
							</thead>
							<tbody>
								@foreach($coins as $coin)
									@if($coin->market == 'sports')
									<tr name="{{__('coin_name.'.$coin->api)}}/{{$coin->api}}" onclick="location.href='{{route('marketKRW',$coin->api)}}'" data-coin="{{$coin->api}}">
										<td>
										<img src="{{ asset('/images/coin_img/'.strtolower($coin->image))}}" alt="{{__('coin_name.'.$coin->api)}}" class="coin_img"/>
										<p>
											{!! __('coin_name.'.$coin->api) !!}
										</p><span>{{$coin->symbol}}</span></td>
										<td><div class="cell"><span class="last_trade_price_usd {{ ((float) number_format($coin->{'percent_change_24h_krw'}) == 0) ? '' : (((float) $coin->{'percent_change_24h_krw'} > 0) ? 'red' : 'blue') }}">{{number_format($coin->{'last_trade_price_krw'}, $coin->{'decimal_krw'})}}</span></div></td>
										<td><div class="cell"><span class="percent_change_24h {{ ((float) number_format($coin->{'percent_change_24h_krw'}) == 0) ? '' : (((float) $coin->{'percent_change_24h_krw'} > 0) ? 'red' : 'blue') }}">{{number_format($coin->{'percent_change_24h_krw'}, 2)}}%</span></div></td>
									</tr>
									@endif
								@endforeach
							</tbody>
						</table>
						{{-- PUBLIC COIN --}}
						<table id="coin_list_table_krw" class="table_in_box coin_table target coin_table_renew">
							<thead>
								<th>{{ __('market.coin_name') }}</th>
								<th>{{ __('market.last_price') }}</th>
								<th>{{ __('market.yesterday') }}</th>
							</thead>
							<tbody>
								@foreach($coins as $coin)
									@if($coin->market == 'public')
									<tr name="{{__('coin_name.'.$coin->api)}}/{{$coin->api}}" onclick="location.href='{{route('marketKRW',$coin->api)}}'" data-coin="{{$coin->api}}">
										<td>
										<img src="{{ asset('/images/coin_img/'.strtolower($coin->image))}}" alt="{{__('coin_name.'.$coin->api)}}" class="coin_img"/>
										<p>
											{{__('coin_name.'.$coin->api)}}
										</p><span>{{$coin->symbol}}</span></td>
										<td><div class="cell"><span class="last_trade_price_usd {{ ((float) number_format($coin->{'percent_change_24h_krw'}) == 0) ? '' : (((float) $coin->{'percent_change_24h_krw'} > 0) ? 'red' : 'blue') }}">{{number_format($coin->{'last_trade_price_krw'}, $coin->{'decimal_krw'})}}</span></div></td>
										<td><div class="cell"><span class="percent_change_24h {{ ((float) number_format($coin->{'percent_change_24h_krw'}) == 0) ? '' : (((float) $coin->{'percent_change_24h_krw'} > 0) ? 'red' : 'blue') }}">{{number_format($coin->{'percent_change_24h_krw'}, 2)}}%</span></div></td>
									</tr>
									@endif
								@endforeach
							</tbody>
						</table>
					</div>

				</div>

			</div>
			<!-- //②-3 오른쪽박스 (코인목록, 거래기록)-->

		</div>
	</div>

</div>

<input type="hidden" name="event_status" id="event_status" value="{{ $event_info->status }}">

<!--N번째 이벤트 모달-->	
<div id="n_event_modal" class="modal">
	<span class="close" id="n_event_modal_close">x</span>
	<div class="modal-content" id="n_event_modal_content">
		<div class="modal-body">
			<img id="modalImg" src="/images/event/modal_event_congratulation.png"/>
				<p style="line-height:120%">
				<div id="n_event_modal_notify">{{ $event_info->number }}번째 이벤트에 당첨 되셨습니다.</div>
				<div id="n_event_modal_prize">상품: {{ $event_info->productName }}</div>
				<br>
				<Button type="button" id="prize_Info_submmit" onclick="location.href='https://forms.gle/1m37PefZAjcRsPpFA'" ><p id="inner_prize_Info_submmit">당첨&nbsp;&nbsp;접수&nbsp;&nbsp;하러&nbsp;&nbsp;가기</p></Button>
				</p>
		</div>
	</div>
</div>
<!--//N번째 이벤트 모달-->
<link href="/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="/vendor/datatables/jquery.dataTables.js"></script>

<script>

	document.getElementById("n_event_modal_close").onclick = function() { 
			$('#n_event_modal').css('display', 'none');
	};

	$( document ).ready(function() {
		$('#coin_list_table_usd').DataTable({
			paging:false,
			searching:false,
			info:false,
			lengthChange:false,
			"order": [[1,"desc"]]
		});
	
		$('#coin_list_table_krw').DataTable({
			paging:false,
			searching:false,
			info:false,
			lengthChange:false,
			"order": [[1,"desc"]]
		});

		if($('input[name="event_status"]').val()) {
			$('#n_event_modal').css('display', 'block');
		}
		
	});
	

	$('.trans_wrap .center_con .my_trade_infor .my_trade_infor_nav ul li').on('click', function(){
		var kind = $(this).data('kind');
		$('.my_trade_table_wrap>div[data-kind="'+kind+'"]').siblings().hide();
		$('.my_trade_table_wrap>div[data-kind="'+kind+'"]').show();
		$(this).siblings().removeClass('active');
		$(this).addClass('active');
	});

	if (typeof __ === 'undefined') { var __ = {}; }
	__.market = {
		@foreach(__('market') as $key => $value)
			'{{$key}}':'{{$value}}',
		@endforeach
	};
	@auth
	$(function(){
		update_erc_eth_balance();
	});
	@endauth
	$(window).scroll(function(event){
		var top = $('.trans_wrap').offset().top;
		var bottom = $('.footer').offset().top;
		var left_target = $('.left_con').offset().top;
		var right_target = $('.right_con').offset().top;
		console.log($(window).scrollTop());
		@auth
			@if($comunity_exist)
				if($(window).scrollTop() >= 870){
					$('.left_con').css({'position':'absolute', 'bottom':'10px', 'top':'auto', 'left':'0'});

					if($(window).scrollTop() >= 1008){
						$('.right_con').css({'position':'absolute', 'bottom':'10px', 'top':'auto', 'right':'0'});
					}
				}else{
					if($(window).scrollTop() >= (top+15)){
						$('.left_con').css({'position':'fixed', 'bottom':'auto', 'top':'15px', 'left':'0.5%'});
						$('.right_con').css({'position':'fixed', 'bottom':'auto', 'top':'15px', 'right':'0.5%'});
					}else{
						$('.left_con').css({'position':'absolute', 'top':'0px', 'bottom':'auto', 'left':'0'});
						$('.right_con').css({'position':'absolute', 'top':'0px', 'bottom':'auto', 'right':'0'});
					}
				}
			@else
				if($(window).scrollTop() >= 538){
					$('.left_con').css({'position':'absolute', 'bottom':'10px', 'top':'auto', 'left':'0'});

					if($(window).scrollTop() >= 676){
						$('.right_con').css({'position':'absolute', 'bottom':'10px', 'top':'auto', 'right':'0'});
					}
				}else{
					if($(window).scrollTop() >= (top+15)){
						$('.left_con').css({'position':'fixed', 'bottom':'auto', 'top':'15px', 'left':'0.5%'});
						$('.right_con').css({'position':'fixed', 'bottom':'auto', 'top':'15px', 'right':'0.5%'});
					}else{
						$('.left_con').css({'position':'absolute', 'top':'0px', 'bottom':'auto', 'left':'0'});
						$('.right_con').css({'position':'absolute', 'top':'0px', 'bottom':'auto', 'right':'0'});
					}
				}
			@endif
		@else
			@if($comunity_exist)
				if($(window).scrollTop() >= 505){
					$('.left_con').css({'position':'absolute', 'bottom':'10px', 'top':'auto', 'left':'0'});

					if($(window).scrollTop() >= 645){
						$('.right_con').css({'position':'absolute', 'bottom':'10px', 'top':'auto', 'right':'0'});
					}
				}else{
					if($(window).scrollTop() >= (top+15)){
						$('.left_con').css({'position':'fixed', 'bottom':'auto', 'top':'15px', 'left':'0.5%'});
						$('.right_con').css({'position':'fixed', 'bottom':'auto', 'top':'15px', 'right':'0.5%'});
					}else{
						$('.left_con').css({'position':'absolute', 'top':'0px', 'bottom':'auto', 'left':'0'});
						$('.right_con').css({'position':'absolute', 'top':'0px', 'bottom':'auto', 'right':'0'});
					}
				}
			@else
				if($(window).scrollTop() >= 172){
					$('.left_con').css({'position':'absolute', 'bottom':'10px', 'top':'auto', 'left':'0'});

					if($(window).scrollTop() >= 313){
						$('.right_con').css({'position':'absolute', 'bottom':'10px', 'top':'auto', 'right':'0'});
					}
				}else{
					if($(window).scrollTop() >= (top+15)){
						$('.left_con').css({'position':'fixed', 'bottom':'auto', 'top':'15px', 'left':'0.5%'});
						$('.right_con').css({'position':'fixed', 'bottom':'auto', 'top':'15px', 'right':'0.5%'});
					}else{
						$('.left_con').css({'position':'absolute', 'top':'0px', 'bottom':'auto', 'left':'0'});
						$('.right_con').css({'position':'absolute', 'top':'0px', 'bottom':'auto', 'right':'0'});
					}
				}
			@endif
		@endauth
	});

</script>

@endsection
