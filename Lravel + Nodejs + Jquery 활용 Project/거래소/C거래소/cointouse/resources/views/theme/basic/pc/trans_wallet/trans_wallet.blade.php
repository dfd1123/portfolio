@extends(session('theme').'.pc.layouts.app')

@section('content')


<div class="trans_wrap">

	<div class="trans_inner_2">

		<div class="trans_con">

			<!--①왼쪽 코인목록-->
			<div class="trans_left">

				<div class="ta_total_asset">
					<label>{{__('trans.all_my_asset')}}</label>

					<span> <b id="holding_total_balance">{{ number_format($total_holding,0) }}</b> <b class="currency">KRW</b> </span>
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
									<tr name="USDC" onclick = "select_coin('{{ $coin->symbol }}');">
										<td class="coin_td"><img src="{{ asset('/storage/image/homepage/coin_img/usdc.png')}}" alt="coin_symbol" class="coin_symbol"><span class="coin_name">USDC</span><span class="coin_name_eng">USDC</span></td>
										<td><span id="holding_percent_{{ $coin->symbol }}">{{ ($total_holding == 0) ? number_format(0,2) : number_format($result[$coin->api]['balance'] * $coin->price_krw / $total_holding * 100,2) }}%</span></td>
										<td>
										<p>
											<span id="holding_balance_{{ $coin->symbol }}">{{ number_format($result[$coin->api]['balance'],8) }}</span><span class="currency">USDC</span>
										</p>
										<p class="usdc_eng_line">
											<span class="readonly">
												@if($coin->price_krw * $result[$coin->api]['balance'] > 0)
													poss
												@endif
											</span>
											<!--span id="holding_convert_{{ $coin->symbol }}">{{ number_format($coin->price_krw * $result[$coin->api]['balance'],$coin->decimal_krw) }}</span><span class="currency">KRW</span-->
										</p></td>
										<td>
										<!--button class="status_btn cvt_btn usdcConvert" onclick="exchangeCashCoin('{{ $coin->symbol }}','KRW');">
										{{__('trans.change_krw')}}
										</button></td-->
										<button class="status_btn inout_btn">
										{{__('trans.inout')}}
										</button></td>
									</tr>
									@elseif($coin->symbol == 'KRW')
									<tr name="{{ __('coin_name.'.$coin->api) }}/{{$coin->api}}" onclick = "select_coin('{{ $coin->symbol }}');">
										<td class="coin_td"><img src="{{ asset('/storage/image/homepage/coin_img/'.$coin->api.'.png')}}" alt="coin_symbol" class="coin_symbol"><span class="coin_name">{{ __('coin_name.'.$coin->api) }}</span><span class="coin_name_eng">{{ $coin->symbol }}</span></td>
						
										<td><span id="holding_percent_{{ $coin->symbol }}">{{ ($total_holding == 0) ? number_format(0,2) : number_format($result[$coin->api]['balance'] * $coin->price_krw / $total_holding * 100,2) }}%</span></td>
										<td>
										<p>
											<span id="holding_balance_{{ $coin->symbol }}">{{ number_format($result[$coin->api]['balance'],0) }}</span><span class="currency">{{ $coin->symbol }}</span>
										</p>
										<p class="usdc_eng_line">
											<span class="readonly">
												@if($coin->price_krw * $result[$coin->api]['balance'] > 0)
													poss
												@endif
											</span>
											<span id="holding_convert_{{ $coin->symbol }}">{{ number_format($coin->price_usd * $result[$coin->api]['balance'],$coin->decimal_usd) }}</span><span class="currency">USDC</span>
										</p></td>
										<td>
										<button class="status_btn cvt_btn usdcConvert" onclick="exchangeCashCoin('{{ $coin->symbol }}','{{ config('app.default_cash') }}');">
										{{__('trans.change_usdc')}}
										</button></td>
										<!--button class="status_btn inout_btn">
										{{__('trans.inout')}}
										</button></td-->
									</tr>
									@else
									<tr name="{{ __('coin_name.'.$coin->api) }}/{{$coin->api}}" onclick = "select_coin('{{ $coin->symbol }}');">
										<td class="coin_td"><img src="{{ asset('/storage/image/homepage/coin_img/'.$coin->api.'.png')}}" alt="coin_symbol" class="coin_symbol"><span class="coin_name">{{ __('coin_name.'.$coin->api) }}</span><span class="coin_name_eng">{{ $coin->symbol }}</span></td>
										<td><span id="holding_percent_{{ $coin->symbol }}">{{ ($total_holding == 0) ? number_format(0,2) : number_format($result[$coin->api]['balance'] * $coin->price_krw / $total_holding * 100,2) }}%</span></td>
										<td>
										<p>
											<span id="holding_balance_{{ $coin->symbol }}">{{ number_format($result[$coin->api]['balance'],8) }}</span><span class="currency">{{ $coin->symbol }}</span>
										</p>
										<p class="usdc_eng_line">
											<span class="readonly">
												@if($coin->price_krw * $result[$coin->api]['balance'] > 0)
													poss
												@endif
											</span>
											<span id="holding_convert_{{ $coin->symbol }}">{{ number_format($coin->price_krw * $result[$coin->api]['balance'],$coin->decimal_krw) }}</span><span class="currency">KRW</span>
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
					<label id="this_symbol">{{__('trans.inout_usdc')}}</label>
					<i class="fal fa-redo" id="all_refresh" onclick="all_refresh();"></i>
				</div>

				<div class="ta_asset_box">
					<p class="p_horizon">
						<span class="tit_small">{{__('trans.all_my_asset')}}</span>
						<span class="data_info" id="this_balance_total"> <b></b> <b class="currency">KRW</b> </span>
					</p>
					<p class="p_horizon etc_style">
						<span class="tit_small">{{__('trans.amount_of_evaluation_krw')}}</span>
						<span class="data_info" id="this_balance_eval"> <b></b> <u class="currency">KRW</u> </span>
					</p>
				</div>
				
				<div class="ta_transacinfor">
					<p class="p_horizon etc_style">
						<span class="tit_small">{{__('trans.wait_trade')}}</span>
						<span class="data_info" id="this_balance_pending"> <b></b> <u class="currency">KRW</u> </span>
					</p>
					<p class="p_horizon etc_style">
						<span class="tit_small etc_style">{{__('trans.can_out')}}</span>
						<span class="data_info" id="this_balance_available"> <b></b> <u class="currency">KRW</u> </span>
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
						<div class="ta_transac_group coin_table hide">

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
											- {!! __('trans.trans_sentence3') !!}
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
											<label id="withdraw_amt_label">{{__('trans.outusdc')}}</label>
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
									<div class="first_deposit_note_con">

										<p class="tit">
											<i class="fas fa-exclamation-circle"></i> 코인 출금 유의사항
										</p>

										<ul>
											<li>
											- 코인 입출금은 관리자가 이상여부 판단을 위한 체크 시간을 포함합니다.<br>
											다소 처리 속도가 느린경우가 있으며, 입출금이 빠른시간내에 되지 않을경우 1:1 문의를 통해 문의하시기 바랍니다.
											</li>
											<li>
											- 실제로 전송되는 암호화폐 총 수량은 신청한 수량에 전송 수수료를 제외한 수량입니다.<br>(단, 코인투스 회원간의 송금시에는 수수료가 발생하지 않습니다.)
											</li>
											<li>
											- 암호화폐 특성상 출금신청이 완료되면 취소가 불가하기 때문에, 출금 시 주소를 꼭 확인 후 입력해 주시기 바랍니다.
											</li>
											<li>
											- 고객의 부주의로 인해 발생한 손실의 경우 코인투스는 책임지지 않습니다.
											</li>
											<li>
											- 출금신청 완료 이후의 송금 과정은 블록체인 네트워크에서 처리됩니다.<br>이 과정에서 발생할 수 있는 송금 지연 등의 문제는 코인투스에서 처리가 불가능합니다.
											</li>
											<li>
											- 부정거래가 의심 될 경우 출금이 제한 될 수 있습니다.
											</li>
										</ul>

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
						
						<!-- 현금 입출금 화면 -->
						<div class="ta_transac_group cash_table hide">
							@if($security_lv < 4)
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
							<!--입금주소 출금신청 입출금내역 버튼-->
							<div class="transac_header">
								<ul>
									<li class="active">
									KRW {{__('trans.in')}}
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

									<span class="first_deposit_info ment">{!! __('trans.cash_sentence1') !!}</span>

									<div class="first_deposit_form_group ta_form_group">
										<input type="number" class="ta_form_input form-control" id="cash_deposite">
										<button class="btn transwallet_btn" onclick="cash_deposite();">
										{{__('trans.in')}}
										</button>
									</div>
									
								</div>

								<!--출금-->
								<div class="toggle_con second_wdr_con">

									<div class="second_wdr_form_group">
										<p class="p_horizon">
											<span class="tit_small">{{__('trans.one_day_out_limit')}}</span>
											<span class="data_info" id="cash_withdraw_limit_amt"> <b></b> <b class="currency"></b> </span>
										</p>
									</div>

									<div class="second_wdr_form_group_wrap">
										<div class="second_wdr_form_group ta_form_group">
											<label>{{__('trans.out_address')}}</label>
											<input type="text" class="ta_form_input form-control" id="cash_withdraw_bank_info" value = "{{ $user_info->account_num }} {{ $user_info->account_bank }}" readonly>
										</div>

										<div class="second_wdr_form_group ta_form_group">
											<label id="cash_withdraw_amt_label">{{__('trans.outusdc')}}</label>
											<input type="number" class="ta_form_input form-control" id = "cash_withdraw_amt" onchange="withdraw_onkey_amt();">
											<button class="btn" id="withdraw_amt_max_btn" onclick="withdraw_max_amt();">
											{{__('trans.max')}}
											</button>
										</div>
									</div>
									<div class="second_wdr_form_group">
										<p class="p_horizon etc_style">
											<span class="tit_small etc_style">{{__('trans.out_fees')}}</span>
											<span class="data_info"> <b id="cash_withdraw_send_fee"></b> </span>
										</p>
									</div>
									<div class="second_wdr_form_group">
										<p class="p_horizon etc_style">
											<span class="tit_small etc_style">{{__('trans.all_out')}}</span>
											<span class="data_info"> <b id="cash_withdraw_total_amt"></b> </span>
										</p>
									</div>
									<div class="second_wdr_form_group second_wdr_btn_group">
										<button class="second_wdr_btn" onclick="cash_withdraw();">
										{{__('trans.please_out')}}
										</button>
									</div>
									
									

								</div>

								<!--입출금내역-->
								<div class="toggle_con third_history_con">

									<div class="third_history_hd">
										<span style="float:left;{{ $krw_req_or_not == true ? 'display:block;' : 'display:none;' }}" id="cash_deposit_wallet">입금은행 : 우리은행 1005-903-547629</span>
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
												<tbody id="cash_list">
												</tbody>
											</table>
										</div>

									</div>

								</div>

							</div>
							<!--//입금주소~출금신청~입출금내역 박스 있는 영역-->
							@endif
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
