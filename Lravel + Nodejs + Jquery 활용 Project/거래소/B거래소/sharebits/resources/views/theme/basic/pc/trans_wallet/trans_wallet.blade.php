@extends(session('theme').'.pc.layouts.app')

@section('content')


<div class="trans_wrap">

	<div class="trans_inner_2">

		<div class="trans_con">

			<!--①왼쪽 코인목록-->
			<div class="trans_left">

				<div class="ta_total_asset">
					<label>{{__('trans.all_my_asset')}}</label>

					<span> <b id="holding_total_balance">{{ number_format($total_holding,8) }}</b> <b class="currency">UCSS</b> </span>
				</div>

				<div class="ta_sch_bar">

					<div class="coin_sch_bar">

						<input type="text" id="txtFilter" placeholder="{{__('trans.search_coin')}}"/>

					</div>

					<div class="coin_sch_checkbox">

						<input id="my_coin" type="checkbox" class="grayCheckbox"/>
						<label for="my_coin">&nbsp;{{__('trans.my_coin')}}</label>

					</div>

				</div>

				<div class="ta_coin_list_wrap" >

					<table class="table_label">
						<thead>
							<th>{{__('trans.coin_name')}}</th>
							<th>{{__('trans.holding_weight')}}</th>
							<th>{{__('trans.holding_quantity')}}</th>
							<th>{{__('trans.now')}}</th>
						</thead>
					</table>
					<div class="scl_wrap">
						<table class="coin_chart_tbl target" id="coin_list_table">
							<tbody>
								@foreach($coins as $coin)
									@if($coin->symbol == config('app.default_cash'))
									<tr name="UCSS" onclick = "select_coin('{{ $coin->symbol }}');">
										<td class="coin_td"><img src="{{ asset('/storage/image/homepage/coin_img/ucss.png')}}" alt="coin_symbol" class="coin_symbol"><span class="coin_name">UCSS</span><span class="coin_name_eng">UCSS</span></td>
										<td><span id="holding_percent_{{ $coin->symbol }}">{{ ($total_holding == 0) ? number_format(0,2) : number_format($result[$coin->api]['balance'] * $coin->price_usd / $total_holding * 100,2) }}%</span></td>
										<td>
										<p>
											<span id="holding_balance_{{ $coin->symbol }}">{{ number_format($result[$coin->api]['balance'],8) }}</span><span class="currency">UCSS</span>
										</p>
										<p class="ucss_eng_line">
											<span class="readonly">
												@if($btc_price_usd > 0)
													poss
												@endif
											</span>
											<span id="holding_convert_{{ $coin->symbol }}">{{ ($btc_price_usd == 0) ? number_format(0) : number_format($result[$coin->api]['balance'] / $btc_price_usd, 8) }}</span><span class="currency">BTC</span>
										</p></td>
										<td>
										
										<button class="status_btn inout_btn">
										{{__('trans.inout')}}
										</button></td>
									</tr>
									@elseif($coin->symbol == 'BTC')
									<tr name="{{ __('coin_name.'.$coin->api) }}/{{$coin->api}}" onclick = "select_coin('{{ $coin->symbol }}');">
										<td class="coin_td"><img src="{{ asset('/storage/image/homepage/coin_img/'.$coin->api.'.png')}}" alt="coin_symbol" class="coin_symbol"><span class="coin_name">{{ __('coin_name.'.$coin->api) }}</span><span class="coin_name_eng">{{ $coin->symbol }}</span></td>
						
										<td><span id="holding_percent_{{ $coin->symbol }}">{{ ($total_holding == 0) ? number_format(0,2) : number_format($result[$coin->api]['balance'] * $coin->price_usd / $total_holding * 100,2) }}%</span></td>
										<td>
										<p>
											<span id="holding_balance_{{ $coin->symbol }}">{{ number_format($result[$coin->api]['balance'],8) }}</span><span class="currency">{{ $coin->symbol }}</span>
										</p>
										<p class="ucss_eng_line">
											<span class="readonly">
												@if($coin->price_usd * $result[$coin->api]['balance'] > 0)
													poss
												@endif
											</span>
											<span id="holding_convert_{{ $coin->symbol }}">{{ number_format($coin->price_usd * $result[$coin->api]['balance'],$coin->decimal_usd) }}</span><span class="currency">UCSS</span>
										</p></td>
										<td>
										<button class="status_btn cvt_btn ucssConvert" onclick="exchangeCashCoin('{{ $coin->symbol }}','{{ config('app.default_cash') }}');">
										{{__('trans.change_ucss')}}
										</button></td>
									</tr>
									@else
									<tr name="{{ __('coin_name.'.$coin->api) }}/{{$coin->api}}" onclick = "select_coin('{{ $coin->symbol }}');">
										<td class="coin_td"><img src="{{ asset('/storage/image/homepage/coin_img/'.$coin->api.'.png')}}" alt="coin_symbol" class="coin_symbol"><span class="coin_name">{{ __('coin_name.'.$coin->api) }}</span><span class="coin_name_eng">{{ $coin->symbol }}</span></td>
										<td><span id="holding_percent_{{ $coin->symbol }}">{{ ($total_holding == 0) ? number_format(0,2) : number_format($result[$coin->api]['balance'] * $coin->price_usd / $total_holding * 100,2) }}%</span></td>
										<td>
										<p>
											<span id="holding_balance_{{ $coin->symbol }}">{{ number_format($result[$coin->api]['balance'],8) }}</span><span class="currency">{{ $coin->symbol }}</span>
										</p>
										<p class="ucss_eng_line">
											<span class="readonly">
												@if($coin->price_usd * $result[$coin->api]['balance'] > 0)
													poss
												@endif
											</span>
											<span id="holding_convert_{{ $coin->symbol }}">{{ number_format($coin->price_usd * $result[$coin->api]['balance'],$coin->decimal_usd) }}</span><span class="currency">UCSS</span>
										</p></td>
										<td>
										<button class="status_btn inout_btn">
										{{__('trans.inout')}}
										</button></td>
									</tr>
									@endif
								@endforeach
								
							</tbody>

						</table>
					</div>

				</div>

			</div>
			<!--//①왼쪽 코인목록-->

			<!--②오른쪽 입출금 -->
			<input type="hidden" id="this_symbol_hidden">
			<input type="hidden" id="this_symbol_text_hidden">
			<div class="trans_right">

				<div class="ta_right_tit">
					<label id="this_symbol">{{__('trans.inout_ucss')}}</label>
					<i class="fal fa-redo" id="all_refresh" onclick="all_refresh();"></i>
				</div>

				<div class="ta_asset_box">
					<p class="p_horizon">
						<span class="tit_small">{{__('trans.all_my_asset')}}</span>
						<span class="data_info" id="this_balance_total"> <b></b> <b class="currency">UCSS</b> </span>
					</p>
					<p class="p_horizon etc_style">
						<span class="tit_small">{{__('trans.amount_of_evaluation_ucss')}}</span>
						<span class="data_info" id="this_balance_eval"> <b></b> <u class="currency">UCSS</u> </span>
					</p>
				</div>
				
				<div class="ta_transacinfor">
					<p class="p_horizon etc_style">
						<span class="tit_small">{{__('trans.wait_trade')}}</span>
						<span class="data_info" id="this_balance_pending"> <b></b> <u class="currency">ucss</u> </span>
					</p>
					<p class="p_horizon etc_style">
						<span class="tit_small etc_style">{{__('trans.can_out')}}</span>
						<span class="data_info" id="this_balance_available"> <b></b> <u class="currency">ucss</u> </span>
					</p>
				</div>

				@if(Auth::user()->status == 2)
					<div class="lv_up_alert">
						<p class="mt-5">
							<i class="fas fa-exclamation-circle"></i> 계정이 정지된 회원은 입출금을 이용하실 수 없습니다.
						</p>
						<button class="btn_style stop_user_id_warning mt-4">계정 정지</button>
					</div>
				@else
					@if($security_lv < 2)
						<!-- 보안인증 필요할 때 -->
						<div class="lv_up_alert">
							<p class="mt-5">
								<i class="fas fa-exclamation-circle"></i> {{__('trans.need')}}
							</p>
							@if($security_lv == 0)
								<button class="btn_style mt-4" onclick="location.href='{{ route('mypage.security_setting') }}'">{{__('trans.injung')}}</button>
							@else
								<button class="btn_style mt-4" onclick="location.href='{{ route('mypage.security_setting') }}'">{{__('trans.injung')}}</button>
							@endif
						</div>
						<!-- 보안인증 필요할 때 -->
					@else
						<div class="ta_transac_group">

							<!--입금주소 출금신청 입출금내역 버튼-->
							<div class="transac_header">
								<ul>
									<li class="active">
									{{__('trans.in_address')}}
									</li>
									<li>
									{{__('trans.please_out')}}
									</li>
									<li>
									{{__('trans.inout_list')}}
									</li>
								</ul>
							</div>
							<!--//입금주소 출금신청 입출금내역 버튼-->

							<!--입금주소~출금신청~입출금내역 박스 있는 영역-->
							<div class="transac_con_wrap">

								<!--입금-->
								<div class="toggle_con first_deposit_con">

									<span class="first_deposit_info ment" id="first_deposit_info_ment">{{__('trans.trans_sentence1')}}.</span>
									<span class="first_deposit_info"  id="first_deposit_info">{!!__('trans.my_BTC_in_address') !!}</span>

									<div class="first_deposit_qr_con">

										<span id="deposit_coin_address_qrcode"></span>

									</div>

									<div class="first_deposit_form_group ta_form_group">
										<input readonly="readonly" type="text" class="ta_form_input form-control" id="deposit_coin_address">
										<button class="btn transwallet_btn" data-clipboard-action="copy" data-clipboard-target="#deposit_coin_address">
										{{__('trans.copy')}}
										</button>
									</div>

									<div class="first_deposit_note_con">

										<p class="tit">
											<i class="fas fa-exclamation-circle"></i> {!! __('trans.inout_notice') !!}
										</p>

										<ul>
											<li>
											{!! __('trans.trans_sentence2') !!}
											</li>
											<li>
											- {!! __('trans.trans_sentence4') !!}
											</li>
											<li>
											- {!! __('trans.trans_sentence5') !!}
											</li>
										</ul>

									</div>

								</div>

								<!--출금-->
								<div class="toggle_con second_wdr_con">

									<div class="second_wdr_form_group">
										<p class="p_horizon">
											<span class="tit_small">{{__('trans.one_day_out_limit')}}</span>
											<span class="data_info" id="withdraw_limit_amt"> <b></b> <b class="currency"></b> </span>
										</p>
									</div>

									<div class="second_wdr_form_group_wrap">
										<div class="second_wdr_form_group ta_form_group">
											<label>{{__('trans.out_address')}}</label>
											<input placeholder="{{__('trans.input')}}" type="text" class="ta_form_input form-control" id="withdraw_check_address">
											<button class="btn" id="withdraw_check_address_btn" onclick="withdraw_check_address();">
											{{__('trans.check_address')}}
											</button>
										</div>

										<div class="second_wdr_form_group ta_form_group">
											<label id="withdraw_amt_label">{{__('trans.outucss')}}</label>
											<input type="number" class="ta_form_input form-control" id = "withdraw_amt" onchange="withdraw_onkey_amt();">
											<button class="btn" id="withdraw_amt_max_btn" onclick="withdraw_max_amt();">
											{{__('trans.max')}}
											</button>
										</div>
									</div>
									<div class="second_wdr_form_group">
										<p class="p_horizon etc_style">
											<span class="tit_small etc_style">{{__('trans.out_fees')}}</span>
											<span class="data_info"> <b id="withdraw_send_fee"></b> </span>
										</p>
									</div>
									<div class="second_wdr_form_group">
										<p class="p_horizon etc_style">
											<span class="tit_small etc_style">{{__('trans.all_out')}}</span>
											<span class="data_info"> <b id="withdraw_total_amt"></b> </span>
										</p>
									</div>
									<div class="second_wdr_form_group second_wdr_btn_group">
										<button class="second_wdr_btn" onclick="send_transaction();">
										{{__('trans.please_out')}}
										</button>
									</div>

								</div>

								<!--입출금내역-->
								<div class="toggle_con third_history_con">

									<div class="third_history_hd">
										<select>
											<option value="5">{{__('trans.all')}}</option>
											<option value="1">{{__('trans.in')}}</option>
											<option value="0">{{__('trans.out')}}</option>
										</select>

									</div>

									<div class="third_history_wrap">

										<table class="table_label" >
											<thead>
												<tr>
													<th>{{__('trans.innout')}}</th>
													<th>{{__('trans.quantity_address')}}</th>
													<th>{{__('trans.now_date')}}</th>
												</tr>
											</thead>
										</table>

										<div class="scl_wrap">
											<table class="third_history_tbl">
												<tbody id="transaction_list">
												</tbody>
											</table>
										</div>

									</div>

								</div>

							</div>
							<!--//입금주소~출금신청~입출금내역 박스 있는 영역-->

						</div>
						<div class="posi_wrap">
							<div>
								<div id="loading"></div>
							</div>
						</div>
					@endif
				@endif
				
			</div>
			<!--//②오른쪽 입출금 -->

		</div>

	</div>

</div>
<script>
	$(function(){
		update_erc_eth_balance(); 
		select_coin('USD');
	});
</script>
@endsection
