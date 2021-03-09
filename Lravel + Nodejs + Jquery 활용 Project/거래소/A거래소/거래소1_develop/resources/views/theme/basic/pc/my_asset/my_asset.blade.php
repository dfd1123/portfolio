@extends(session('theme').'.pc.layouts.app')

@section('content')

<div class="trans_wrap">

	<div class="trans_inner_2">

		<div class="trans_con">

			<div class="my_asset_wrap">

				<div class="ast_info">

					<ul>

						<li>
							<span>{{ __('my_asset.all_buy_price') }}</span>
							<span class="align_right"> <b>{{ number_format($total_buying,0) }}</b> <u>KRW</u> </span>
						</li>
						<li>
							<span>{{ __('my_asset.all_rating_revenue') }}</span>
							<span class="align_right"> <b>{{ number_format($total_eval_revenue,0) }}</b> <u>KRW</u> </span>
						</li>
						<li>
							<span>{{ __('my_asset.my_krw') }}</span>
							<span class="align_right"> <b>{{ number_format($coin_balance_krw,0) }}</b> <u>KRW</u> </span>
						</li>
						<li>
							<span>{{ __('my_asset.all_rating_price') }}</span>
							<span class="align_right"> <b>{{ number_format($total_holding,0) }}</b> <u>KRW</u> </span>
						</li>
						<li>
							<span>{{ __('my_asset.all_rating_yield') }}</span>
							<span class="align_right">
								<b>{{ number_format($total_eval_percent,2) }}</b>
								<u>%</u>
							</span>
						</li>
						<li>
							<span>{{ __('my_asset.all_my_money') }}</span>
							<span class="align_right">
								<b>{{ number_format($total_asset,0) }}</b>
								<u>KRW</u>
							</span>
						</li>

					</ul>

				</div>

				<div class="my_asset_hd transac_header">

					<ul>
						<li class="active" data-index="0">
						{{ __('my_asset.my_coin') }}
						</li>
						<li data-index="1">
						{{ __('my_asset.trade_list') }}
						</li>
						<li data-index="2">
						{{ __('my_asset.no') }}
						</li>
						<span class="underline"></span>
					</ul>

				</div>

				<div class="my_asset_con_wrap transac_con_wrap">

					<div class="my_asset_con my_asset_1 toggle_con" data-index="0">

						<div class="coin_category">
							<ul>
								<li class="active" data-kind="all">전체</li>
								<li data-kind="sports">SPORTS COIN</li>
								<li data-kind="public">PUBLIC COIN</li>
								<span class="underline"></span>
							</ul>
						</div>

						<div class="note_label">

							<div class="coin_sch_checkbox">

								
								<label for="my_coin"><input id="my_coin" type="checkbox" class="grayCheckbox" style="margin-right: 8px;" />&nbsp;{{ __('my_asset.my_coin') }}</label>

							</div>
							
							<span>{{ __('my_asset.property_sentence1') }}</span>
						
						</div>

								<div class="ast_table_wrap">

								<table class="table_label">
								<thead>
								<tr>
								<th>{{ __('my_asset.my_coin') }}</th>
								<th>{{ __('my_asset.my_quantity') }}</th>
								<th>{{ __('my_asset.buy_average') }}</th>
								<th>{{ __('my_asset.buy_price') }}</th>
								<th>{{ __('my_asset.rating_price') }}</th>
								<th>{{ __('my_asset.rating_profit_and_loss') }}</th>
								<th>　</th>
								</tr>
								</thead>
								</table>

								<table id="my_coin_table" class="coin_chart_tbl">
								<tbody>
								@foreach($coins as $coin)
									@if($coin->cointype != 'cash')
									<tr name="{!! __('coin_name.'.$coin->api) !!}" data-kind="{{$coin->market}}">
										<td class="coin_td"><img class="coin_symbol" src="{{ asset('/images/coin_img/'.$coin->api.'.png')}}" alt="coin_img"><span class="coin_name">{!! __('coin_name.'.$coin->api) !!}</span><span class="coin_name_eng">{{ $coin->api }}</span></td>
										<td>
											<p>
												<span>{{ number_format($result[$coin->api]['balance'],8) }}</span>
												<span class="currency">{{ $coin->api }}</span>
												<span class="readonly">
													@if($result[$coin->api]['balance'] > 0)
														poss
													@endif
												</span>
											</p>
										</td>
										<td>
											<p>
												<span>{{ number_format($result[$coin->api]['avg'],0) }}</span>
												<span class="currency">KRW</span>
											</p>
										</td>
										<td>
											<p>
												<span>{{ number_format($result[$coin->api]['buying'],0) }}</span>
												<span class="currency">KRW</span>
											</p>
										</td>
										<td>
											<p>
												<span>{{ number_format($result[$coin->api]['eval'],0) }}</span>
												<span class="currency">KRW</span>
											</p>
										</td>
										<td>
											<p>
												{{ number_format($result[$coin->api]['eval_percent'],2) }} %
											</p>
											<p>
												{{ number_format($result[$coin->api]['eval_revenue'],0) }}<span class="currency" >KRW</span>
											</p>
										</td>
										<td>
											<button class="status_btn" onclick="location.href='{{ route('marketKRW',$coin->api) }}'">
											{{ __('my_asset.order') }}
											</button>
										</td>
									</tr>
									@endif
								@endforeach
								

								</tbody>
								</table>

						</div>

					</div>

					<div class="my_asset_con history_con my_asset_2 toggle_con" data-index="1">

						<div class="note_label">

							<div class="increase_btn_group">
								<button class="btn_style mr-1" onclick="input_calendar_data(7);">{{ __('my_asset.one_week') }}</button>
								<button class="btn_style mr-1" onclick="input_calendar_data(14);">{{ __('my_asset.two_weeks') }}</button>
								<button class="btn_style" onclick="input_calendar_data(30);">{{ __('my_asset.one_month') }}</button>
							</div>

							<div class="period_sch_group ml-3">
								<label class="pr-2">{{ __('my_asset.term') }}</label>
								<input type="text" class="period_sch_input" id="start_sch" readonly="readonly">
								~ <input type="text" class="period_sch_input" id="end_sch" readonly="readonly">
								<button class="btn_style sch_btn ml-1" onclick="search_date_history();">{{ __('my_asset.lookup') }}</button>
							</div>

							<div class="coin_sch_group ml-3">
								<input type="text" id="txtFilter" placeholder="{{ __('my_asset.search_coin') }}"/>
							</div>

							<select id="selectFilter" class="type_sort_slt">
								<option value="">{{ __('my_asset.all_trade') }}</option>
								<option value="{{ __('my_asset.sell') }}">{{ __('my_asset.sell') }}</option>
								<option value="{{ __('my_asset.buy') }}">{{ __('my_asset.buy') }}</option>
								<option value="{{__('my_asset.self_trade')}}">{{__('my_asset.self_trade')}}</option>
							</select>

						</div>

						<div class="ast_table_wrap">

							<table class="table_label">
								<thead>
									<tr>
										<th>{{ __('my_asset.order_time') }}</th>
										<th>{{ __('my_asset.gb') }}</th>
										<th>{{ __('my_asset.coin') }}</th>
										<th>{{ __('my_asset.trade_kind') }}</th>
										<th>
											<div>{{ __('my_asset.trade_unit_price') }}</div>
											<div>{{ __('my_asset.trade_quantity') }}</div>
										</th>
										<th>
											<div>{{ __('my_asset.trade_price') }}</div>
											<div>{{ __('my_asset.fees') }}</div>
										</th>
										<th>{!! __('my_asset.settlement_amount') !!}</th>
									</tr>
								</thead>
							</table>

							<table class="coin_chart_tbl target">
								<tbody  id="history_lists">
									@forelse($historys as $history)
										@if($history->buyer_uid == $history->seller_uid)
										<tr class="trs_type-self" name="{!! __('coin_name.'.$history->cointype) !!}/{{$history->cointype}}">
											<td>
												<p>
													<span>{{ $history->created_dt }}</span>
												</p>
											</td>
											<td>
												<p>
													<span>{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}{{ __('my_asset.market') }}</span>
												</p>
											</td>
											<td class="coin_td">
												<img class="coin_symbol" src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($history->cointype).'.png')}}" alt="coin_img">
												<span class="coin_name">{!! __('coin_name.'.strtolower($history->cointype)) !!}</span>
												<span class="coin_name_eng">{{ strtolower($history->cointype) }}/{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}</span>
											</td>
											<td>
												<p>
													<span>{{ __('my_asset.self_trade') }}</span>
												</p>
											</td>
											<td>
												<div>
													<p>
														<span>{{ number_format($history->buy_coin_price, $history->{'decimal_'.strtolower($history->currency)}) }}</span>
														<span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}</span>
													</p>
												</div>
												<div>
													<p>
														<span>{{ number_format($history->contract_coin_amt,8) }}</span>
														<span class="currency">{{ strtoupper($history->cointype) }}</span>
													</p>
												</div>
											</td>
											<td>
												<div>
													<p>
														<span>{{ number_format($history->trade_total_buy, $history->{'decimal_'.strtolower($history->currency)}) }}</span>
														<span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}</span>
													</p>
												</div>
												<div>
													<p>
														<span>{{ number_format(bcmul(bcmul($history->trade_total_buy,$setting->buy_comission,$history->{'decimal_'.strtolower($history->currency)} + 4),"0.01",$history->{'decimal_'.strtolower($history->currency)} + 4), $history->{'decimal_'.strtolower($history->currency)}) }}</span>
														<span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}</span>
													</p>
												</div>
											</td>
											<td>
											<p>
												<span>{{ number_format(bcadd(bcmul(bcmul($history->trade_total_buy,$setting->buy_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}),$history->trade_total_buy,$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)}) }}</span>
												<span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}</span>
											</p></td>
										</tr>
										@elseif($history->buyer_uid == Auth::user()->id)
										<tr class="trs_type-buy">
											<td>
												<p>
													<span>{{ $history->created_dt }}</span>
												</p>
											</td>
											<td>
												<p>
													<span>{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}{{ __('my_asset.market') }}</span>
												</p>
											</td>
											<td class="coin_td"><img class="coin_symbol" src="{{ asset('/images/coin_img/'.strtolower($history->cointype).'.png')}}" alt="coin_img"><span class="coin_name">{!! __('coin_name.'.strtolower($history->cointype)) !!}</span><span class="coin_name_eng">{{ strtolower($history->cointype) }}/{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}</span></td>
											<td>
												<p>
													@if($history->buyer_uid == $history->seller_uid)
													<span style = "color:black;">{{ __('my_asset.self_trade') }}</span>
													@else
													<span>{{ __('my_asset.buy') }}</span>
													@endif
												</p>
											</td>
											<td>
												<div>
													<p>
														<span>{{ number_format($history->buy_coin_price, $history->{'decimal_'.strtolower($history->currency)}) }}</span>
														<span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}</span>
													</p>
												</div>
												<div>
													<p>
														<span>{{ number_format($history->contract_coin_amt,8) }}</span>
														<span class="currency">{{ strtoupper($history->cointype) }}</span>
													</p>
												</div>
											</td>
											<td>
												<div>
													<p>
														<span>{{ number_format($history->trade_total_buy, $history->{'decimal_'.strtolower($history->currency)}) }}</span>
														<span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}</span>
													</p>
												</div>
												<div>	
													<p>
														<span>{{ number_format(bcmul(bcmul($history->trade_total_buy,$setting->buy_comission,$history->{'decimal_'.strtolower($history->currency)} + 4),"0.01",$history->{'decimal_'.strtolower($history->currency)} + 4), $history->{'decimal_'.strtolower($history->currency)}) }}</span>
														<span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}</span>
													</p>
												</div>
											</td>
											<td>
											<p>
												<span>{{ number_format(bcadd(bcmul(bcmul($history->trade_total_buy,$setting->buy_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}),$history->trade_total_buy,$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)}) }}</span>
												<span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}</span>
											</p></td>
										</tr>
										@else
										<tr class="trs_type-sell">
											<td>
												<p>
													<span>{{ $history->created_dt }}</span>
												</p>
											</td>
											<td>
												<p>
													<span>{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}{{ __('my_asset.market') }}</span>
												</p>
											</td>
											<td class="coin_td"><img class="coin_symbol" src="{{ asset('/images/coin_img/'.strtolower($history->cointype).'.png')}}" alt="coin_img"><span class="coin_name">{!! __('coin_name.'.strtolower($history->cointype)) !!}</span><span class="coin_name_eng">{{ strtolower($history->cointype) }}/{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}</span></td>
											<td>
											<p>
												@if($history->buyer_uid == $history->seller_uid)
												<span style = "color:black;">{{ __('my_asset.self_trade') }}</span>
												@else
												<span>{{ __('my_asset.sell') }}</span>
												@endif
											</p></td>
											<td>
												<div>
													<p>
														<span>{{ number_format($history->sell_coin_price, $history->{'decimal_'.strtolower($history->currency)}) }}</span>
														<span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}</span>
													</p>
												</div>
												<div>
													<p>
														<span>{{ number_format($history->contract_coin_amt,8) }}</span>
														<span class="currency">{{ strtoupper($history->cointype) }}</span>
													</p>
												</div>
											</td>
											<td>
												<div>
													<p>
														<span>{{ number_format(bcadd($history->trade_total_sell,bcmul(bcmul($history->trade_total_sell,$setting->sell_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)}) }}</span>
														<span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}</span>
													</p>
												</div>
												<div>
													<p>
														<span>{{ number_format(bcmul(bcmul($history->trade_total_sell,$setting->sell_comission,$history->{'decimal_'.strtolower($history->currency)} + 4),"0.01",$history->{'decimal_'.strtolower($history->currency)} + 4), $history->{'decimal_'.strtolower($history->currency)}) }}</span>
														<span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}</span>
													</p>
												</div>
											</td>
											<td>
											<p>
												<span>{{ number_format($history->trade_total_sell, $history->{'decimal_'.strtolower($history->currency)}) }}</span>
												<span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'USDC' }}</span>
											</p></td>
										</tr>
										@endif
									@empty
									<tr class="none_transac">
										<td colspan="8">
											<i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('my_asset.property_sentence2') }}
										</td>
									</tr>
									@endforelse
								</tbody>
							</table>

						</div>
						
						<!--페이지 많아지면 더보기-->
						
						<div class="table_view_more mt-3" id="show_more_btn_div">
							@if($historys_count > 20)
							<button onclick = "show_more_history();"><i class="fal fa-plus"></i> {{ __('my_asset.more') }}</button>
							@endif
						</div>
						
						<!--//페이지 많아지면 더보기-->

					</div>

					<div class="my_asset_con my_asset_3 toggle_con" data-index="2">
					
						<div class="ast_table_wrap">

							<table class="table_label">
								<thead>
									<tr>
										<th>{{ __('my_asset.order_time') }}</th>
										<th>{{ __('my_asset.gb') }}</th>
										<th>{{ __('my_asset.coin') }}</th>
										<th>{{ __('my_asset.trade_kind') }}</th>
										<th>
											<div>{{ __('my_asset.order_price') }}</div>
											<div>{{ __('my_asset.order_quntity') }}</div>
										</th>
										<th>
											<div>{{ __('my_asset.conclusion_quantity') }}</div>
											<div>{{ __('my_asset.not_conclusion_quantity') }}</div>
										</th>
										<th>상태</th>
									</tr>
								</thead>
							</table>

							<table class="coin_chart_tbl">
								<tbody id="not_concluded_lists">
									@forelse($wait_trades as $wait_trade)
										@if($wait_trade->type == 'buy')
										<tr class="trs_type-buy">
											<td>
											<p>
												<span>{{ $wait_trade->created_dt }}</span>
											</p></td>
											<td>
												<p>
													<span>{{ strtoupper($wait_trade->currency) != 'USD' ? strtoupper($wait_trade->currency) : 'USDC' }}{{ __('my_asset.market') }}</span>
												</p>
											</td>
											<td class="coin_td">
												<img class="coin_symbol" src="{{ asset('/images/coin_img/'.strtolower($wait_trade->cointype).'.png')}}" alt="coin_img">
												<span class="coin_name">{!! __('coin_name.'.strtolower($wait_trade->cointype)) !!}</span>
												<span class="coin_name_eng">{{ strtolower($wait_trade->cointype) }}/{{ strtoupper($wait_trade->currency) != 'USD' ? strtoupper($wait_trade->currency) : 'USDC' }}</span>
											</td>
											<td>
											<p>
												<span>{{ __('my_asset.buy') }}</span>
											</p></td>
											<td>
												<div>
													<p>
														<span>{{ number_format($wait_trade->buy_coin_price,$wait_trade->{'decimal_'.strtolower($wait_trade->currency)}) }}</span>
														<span class="currency">{{ strtoupper($wait_trade->currency) != 'USD' ? strtoupper($wait_trade->currency) : 'USDC' }}</span>
													</p>
												</div>
												<div>
													<p>
														<span>{{ number_format($wait_trade->buy_COIN_amt_total,8) }}</span>
														<span class="currency">{{ strtoupper($wait_trade->cointype) }}</span>
													</p>
												</div>
											</td>
											<td>
												<div>
													<p>
														<span>{{ number_format($wait_trade->buy_COIN_amt_finished,8) }}</span>
														<span class="currency">{{ strtoupper($wait_trade->cointype) }}</span>
													</p>
												</div>
												<div>
													<p>
														<span>{{ number_format($wait_trade->buy_COIN_amt,8) }}</span>
														<span class="currency">{{ strtoupper($wait_trade->cointype) }}</span>
													</p>
												</div>
											</td>
											<td>
											<button class="status_btn" id="btc_cancel_request_{{$wait_trade->id}}" data-id="{{$wait_trade->id}}" onclick="myasset_trade_cancel({{$wait_trade->id}})">
											{{ __('my_asset.cancel_trade') }}
											</button></td>
										</tr>
										@else
										<tr class="trs_type-sell">
											<td>
											<p>
												<span>{{ $wait_trade->created_dt }}</span>
											</p></td>
											<td>
												<p>
													<span>{{ strtoupper($wait_trade->currency) != 'USD' ? strtoupper($wait_trade->currency) : 'USDC' }}{{ __('my_asset.market') }}</span>
												</p>
											</td>
											<td class="coin_td">
												<img class="coin_symbol" src="{{ asset('/images/coin_img/'.strtolower($wait_trade->cointype).'.png')}}" alt="coin_img">
												<span class="coin_name">{!! __('coin_name.'.strtolower($wait_trade->cointype)) !!}</span>
												<span class="coin_name_eng">{{ strtolower($wait_trade->cointype) }}/{{ strtoupper($wait_trade->currency) != 'USD' ? strtoupper($wait_trade->currency) : 'USDC' }}</span>
											</td>
											<td>
											<p>
												<span>{{ __('my_asset.sell') }}</span>
											</p></td>
											<td>
												<div>
													<p>
														<span>{{ number_format($wait_trade->sell_coin_price,$wait_trade->{'decimal_'.strtolower($wait_trade->currency)}) }}</span>
														<span class="currency">{{ strtoupper($wait_trade->currency) != 'USD' ? strtoupper($wait_trade->currency) : 'USDC' }}</span>
													</p>
												</div>
												<div>
													<p>
														<span>{{ number_format($wait_trade->sell_COIN_amt_total,8) }}</span>
														<span class="currency">{{ strtoupper($wait_trade->cointype) }}</span>
													</p>
												</div>
											</td>
											<td>
												<div>
													<p>
														<span>{{ number_format($wait_trade->sell_COIN_amt_finished,8) }}</span>
														<span class="currency">{{ strtoupper($wait_trade->cointype) }}</span>
													</p>
												</div>
												<div>
													<p>
														<span>{{ number_format($wait_trade->sell_COIN_amt,8) }}</span>
														<span class="currency">{{ strtoupper($wait_trade->cointype) }}</span>
													</p>
												</div>
											</td>
											<td>
											<button class="status_btn" id="btc_cancel_request_{{$wait_trade->id}}" data-id="{{$wait_trade->id}}" onclick="myasset_trade_cancel({{$wait_trade->id}})">
											{{ __('my_asset.cancel_trade') }}
											</button></td>
										</tr>
										@endif
									
									@empty
									<tr class="none_transac">
										<td colspan="8">
											<i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('my_asset.property_sentence3') }}
										</td>
									</tr>
									@endforelse

								</tbody>
							</table>

						</div>
						
						<!--페이지 많아지면 더보기-->
						<div class="table_view_more mt-3" id="show_more_not_concluded_btn">
							@if($wait_trades_count > 20)
							<button onclick="show_more_not_concluded();"><i class="fal fa-plus"></i> {{ __('my_asset.more') }}</button>
							@endif
						</div>
						<!--//페이지 많아지면 더보기-->
						
					</div>

				</div>

			</div>

		</div>

	</div>

</div>
<script>
$(function(){ 
	update_erc_eth_balance();		
});
if (typeof __ === 'undefined') { var __ = {}; }
    __.my_asset = {
        @foreach(__('my_asset') as $key => $value)
            '{{$key}}':'{{$value}}',
        @endforeach
	}
	
	$('.coin_category ul li').on('click', function(){
		var kind = $(this).data('kind');

		$(this).siblings().removeClass('active');
		$(this).addClass('active');
		
		if(kind == 'all'){
			$('#my_coin_table tbody>tr').removeClass('hide');
		}else{
			$('#my_coin_table tbody>tr').addClass('hide');
			$('#my_coin_table tbody>tr[data-kind="'+kind+'"]').removeClass('hide');
		}
	});
</script>

@endsection
