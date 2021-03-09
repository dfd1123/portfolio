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
				
				<div class="trans_left_bottom">
					<div class="coin_category">
						<ul>
							<li class="active" data-kind="all">전체</li>
							<li data-kind="sports">SPORTS COIN</li>
							<li data-kind="public">PUBLIC COIN</li>
							<span class="underline"></span>
						</ul>
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
										<tr name="{!! __('coin_name.'.$coin->api) !!}/{{$coin->api}}" data-kind="{{$coin->market}}" onclick = "select_coin('{{ $coin->symbol }}');">
											<td class="coin_td"><img src="/images/coin_img/{{$coin->api.'.png'}}" alt="coin_symbol" class="coin_symbol"><span class="coin_name">{!! __('coin_name.'.$coin->api) !!}</span><span class="coin_name_eng">{{ $coin->symbol }}</span></td>
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
										@else
										<tr name="{!! __('coin_name.'.$coin->api) !!}/{{$coin->api}}" data-kind="{{$coin->market}}" onclick = "select_coin('{{ $coin->symbol }}');">
											<td class="coin_td"><img src="/images/coin_img/{{$coin->api.'.png'}}" alt="coin_symbol" class="coin_symbol"><span class="coin_name">{!! __('coin_name.'.$coin->api) !!}</span><span class="coin_name_eng">{{ $coin->symbol }}</span></td>
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

			</div>
			<!--//①왼쪽 코인목록-->

			<!--②오른쪽 입출금 -->
			<input type="hidden" id="this_symbol_hidden">
			<input type="hidden" id="this_symbol_text_hidden">
			<div class="trans_right">
				<div class="trans_right_hd">
					<div class="ta_right_tit">
						<label id="this_symbol"></label>
						<span id="this_just_symbol">krw</span>
						<i class="fal fa-redo" id="all_refresh" onclick="all_refresh();"></i>
					</div>

					<div class="ta_asset_box">
						<p class="p_horizon">
							<span class="tit_small">{{__('trans.total_holding_quantity')}}</span>
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
				</div>

				@if(Auth::user()->status == 2)
					<div class="lv_up_alert">
						<p>
							<img src="/images/icon_notice.svg" alt="" class="btn_notice"> 계정이 정지된 회원은 입출금을 이용하실 수 없습니다.
						</p>
						<button class="btn_style stop_user_id_warning mt-4">계정 정지</button>
					</div>
				@else
					@if($security_lv < 2)
						<!-- 보안인증 필요할 때 -->
						<div class="lv_up_alert">
							<p>
								<img src="/images/lv_up_alert.svg" alt="" /> {!! __('trans.need') !!}
							</p>
							@if($security_lv == 0)
								<button onclick="location.href='{{ route('mypage.security_setting') }}'">{{__('trans.injung')}}</button>
							@else
								<button onclick="location.href='{{ route('mypage.security_setting') }}'">{{__('trans.injung')}}</button>
							@endif
						</div>
						<!-- 보안인증 필요할 때 -->
					@else
						<div class="ta_transac_group coin_table hide">

							<!--입금주소 출금신청 입출금내역 버튼-->
							<div class="transac_header">
								<ul>
									<li class="active" data-index="0">
									{{__('trans.in_address')}}
									</li>
									<li data-index="1">
									{{__('trans.please_out')}}
									</li>
									<li data-index="2">
									{{__('trans.inout_list')}}
									</li>
									<span class="underline"></span>
								</ul>
							</div>
							<!--//입금주소 출금신청 입출금내역 버튼-->

							<!--입금주소~출금신청~입출금내역 박스 있는 영역-->
							<div class="transac_con_wrap">

								<!--입금-->
								<div class="toggle_con first_deposit_con" data-index="0">

									<span class="first_deposit_info ment" id="first_deposit_info_ment" style="padding:20px 0;">
										{!!__('trans.trans_sentence1')!!}.<br>
										
									</span>
									
									<span class="first_deposit_info"  id="first_deposit_info">{!!__('trans.my_BTC_in_address') !!}</span>
									<span class="first_deposit_info" id="limit_deposit_info">(이더리움의 경우 0.1 ETH 이상만 입금가능)</span>
									
									<div class="first_deposit_qr_con">

										<span id="deposit_coin_address_qrcode"></span>

									</div>

									<div class="coin_address_box">
										<input readonly="readonly" type="text" id="deposit_coin_address">
										<button class="btn transwallet_btn" data-clipboard-action="copy" data-clipboard-target="#deposit_coin_address">
										{{__('trans.copy')}}
										</button>
									</div>

									<!-- FIXME: 입금주소의 목적지칸 -->
									<div id="deposit_address_desti_box">
										<span class="coin_desti_label">{{__('trans.desti')}}</span>
										<div class="coin_address_box" id="">
											<input readonly="readonly" type="text" id="deposit_coin_address_desti">
											<button class="btn transwallet_btn" >
											{{__('trans.copy')}}
											</button>
										</div>
									</div>
                                    <!-- END 입금주소의 목적지칸 -->

									<div class="inout_notice_box">
										<h5 class="inout_notice_tit mb-2">※ 코인 입출금 유의사항</h5>
										<ul class="inout_notice_list">
											<li>{!! __('trans.trans_sentence2') !!}</li>
											<li>- {!! __('trans.trans_sentence4') !!}</li>
											<li>- {!! __('trans.trans_sentence5') !!}</li>
											<li>- 입금했으나 충전이 되지 않았다면<br>
												1. 고객센터 -> 1:1문의 / 2. 스포와이드 공식 텔레그램: https://t.me/Spowide<br>
												3. 스포와이드 공식 카카오톡 : http://pf.kakao.com/_GxhxgZT / 4. 스포와이드 공식 메일: cs@spowide.com<br>
												5. 고객센터 1588-5808 중 선택하셔서 문의 부탁드리겠습니다.</li>
										</ul>

									</div>

								</div>

								<!--출금-->
								<div class="toggle_con second_wdr_con" data-index="1">

									<div class="second_wdr_form_group">
										<!-- <p class="p_horizon"> -->
											<!--
											<span class="tit_small">{{__('trans.one_day_out_limit')}}</span>
											<span class="data_info" id="withdraw_limit_amt"> 
												<b>0</b><b class="currency"></b> 
											</span>
											-->
										<!--  -->
									</div>

									<div class="second_wdr_form_group_wrap">
										<div class="second_wdr_form_group ta_form_group">
											<label>{{__('trans.out_address')}}</label>
											<div class="pos_relative_box">
												<input placeholder="{{__('trans.input')}}" type="text" class="ta_form_input" id="withdraw_check_address" style="padding-right:10px;">
												<!--button class="btn" id="withdraw_check_address_btn" onclick="withdraw_check_address();" style="background-color:#00b9ff !important;">
													{{__('trans.check_address')}}
												</button-->
											</div>
										</div>
                                        
                                        <!-- FIXME: 출금신청의 목적지칸 -->
										<div class="second_wdr_form_group ta_form_group"  id="withdraw_address_desti_box">
											<label>{{__('trans.desti')}}</label>
											<div class="pos_relative_box">
												<input placeholder="{{__('trans.input_desti')}}" type="text" class="ta_form_input" id="withdraw_address_desti">
											</div>
										</div>
                                        <!-- END 출금신청의 목적지칸 -->

										<div class="second_wdr_form_group ta_form_group">
											<label id="withdraw_amt_label">{{__('trans.outusdc')}}</label>
											<div class="pos_relative_box">
												<input type="number" class="ta_form_input" id = "withdraw_amt" onchange="withdraw_onkey_amt();" style="padding-right: 100px;text-align:right;">
												<span>BTC</span>
												<button class="btn" id="withdraw_amt_max_btn" onclick="withdraw_max_amt();">
													{{__('trans.max')}}
												</button>
											</div>
										</div>
										<div class="withdraw_fee_box">
											<p>
												<span>{{__('trans.out_fees')}}</span>
												<span> <b id="withdraw_send_fee">0</b> <em>BTC</em></span>
											</p>
										</div>
									</div>
									<div class="total_price_box">
										<p>
											<span>{{__('trans.all_out')}}</span>
											<span> <b id="withdraw_total_amt">0</b> <em>BTC</em></span>
										</p>
									</div>
									<div class="second_wdr_form_group second_wdr_btn_group">
										<button class="second_wdr_btn" onclick="send_transaction();">
										{{__('trans.please_out')}}
										</button>
									</div>

									<div class="inout_notice_box">
										<h5 class="inout_notice_tit mb-2">※ 원화 및 코인 출금시 주의사항</h5>
										<ul class="inout_notice_list">
											<li>- 금융당국 가이드라인에 따라 보이스피싱, 자금세탁방지를 위해 첫 입금 후 120시간 이후부터 출금 가능합니다.</li>
											<li>- 출금 시간 안내 (365일) 평일: 09:00 ~ 20:00 | 주말 및 휴일: 13:00 ~ 20:00 (단, 명절 3일은 출금 휴무)</li>
											<li>- 보이스피싱, 자금세탁, 이상거래 판단 후 출금 승인하는데까지 10분 ~ 1시간 소요</li>
											<li>- 출금 승인 후 5~30분 안에 고객 통장으로 입금 완료<br>(단, 보이스피싱, 자금세탁, 이상거래 의심자로 확인될 경우 출금이 되지 않거나 철저한 검증 이후 출금진행될 수 있으니 이 점 참고바랍니다.)</li>
											<li>- 입출금 내역에서 출금 승인을 확인하였으나 입금되지 않았다면<br>
												1. 고객센터 -> 1:1문의<br>
												2. 스포와이드 공식 텔레그램: https://t.me/Spowide<br>
												3. 스포와이드 공식 카카오톡 : http://pf.kakao.com/_GxhxgZT<br>
												4. 스포와이드 공식 메일: cs@spowide.com<br>
												5. 고객센터 1588-5808<br>
												선택하셔서 문의 부탁드리겠습니다.</li>
										</ul>
									</div>
								</div>

								<!--입출금내역-->
								<div class="toggle_con third_history_con" data-index="2">

									<div class="third_history_hd">
										{{--<select>
											<option value="5"></option>
											<option value="1">{{__('trans.in')}}</option>
											<option value="0">{{__('trans.out')}}</option>
										</select>--}}
										<ul>
											<li class="active" data-io="all">{{__('trans.all')}}</li>
											<li data-io="in">{{__('trans.in')}}</li>
											<li data-io="out">{{__('trans.out')}}</li>
										</ul>
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
													<tr class="in" data-kind="0">
														<td rowspan="2">입금</td>
														<td rowspan="2">
															<p>
																<span class="currency">LTC</span>
															</p>
														</td>
														<td> - </td>
													</tr>
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
							@if($security_lv < 3)
							<!-- 보안인증 필요할 때 -->
							<div class="lv_up_alert">
								<p class="mt-5">
									<img src="/images/lv_up_alert.svg" alt="" /> 현금 입출금을 위해서는 계좌 인증이 필요합니다.
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
									<li class="active" data-index="0">
									KRW {{__('trans.in')}}
									</li>
									<li data-index="1">
									{{__('trans.please_out')}}
									</li>
									<li data-index="2">
									{{__('trans.inout_list')}}
									</li>
									<span class="underline"></span>
								</ul>
							</div>
							<!--//입금주소 출금신청 입출금내역 버튼-->

							<!--입금주소~출금신청~입출금내역 박스 있는 영역-->
							<div class="transac_con_wrap">

								<!--입금-->
								<div class="toggle_con first_deposit_con" data-index="0">
									<div id="deposite_krw_info_box" style="display:none;">
										<h4 class="bank_infor">국민은행 529401-01-250937 스포홀딩스</h4> 
										<p>입금 통장 표시 : <b>{{Auth::user()->fullname}} {{$rand_num}}</b> 
										<button class="btn transwallet_btn" onclick="copy_text();"><img src="/images/copy.png" alt="" /></button>
										</p>
									</div>
									<span class="first_deposit_info ment">
										{!!__('trans.charging_info')!!}		
									<br />(ex. 입금자명 : 홍길동 {{$rand_num}})
									<br />계좌번호와 인증코드는 입금요청 버튼을 눌러야만 제공됩니다.	
									</span>            

									<div class="first_deposit_form_group ta_form_group">
										<label for="cash_deposite">충전금액</label>
										<input type="hidden" id="verification_code" value="{{$rand_num}}" />
										<input type="number" class="ta_form_input" id="cash_deposite" placeholder="{{__('trans.write_price')}}" maxlength="9" oninput="cash_deposite_length_check(this)">
										<button class="btn transwallet_btn before_acti_btn" id="before_acti_btn" onclick="cash_deposite();">
										{{__('trans.in_request')}}
										</button>	
									</div>
									<div class="inout_notice_box">
										<h5 class="inout_notice_tit mb-2">※ 원화 입금시 주의사항</h5>
										<ul class="inout_notice_list">
											<li>- 금융당국 가이드라인에 따라 보이스피싱, 자금세탁방지를 위해 첫 입금 후 120시간 이후 부터 출금 가능합니다.</li>
											<li>- 입금 완료 후 1~5분 내로 충전이 완료됩니다.<br>입금 표시가 되지 않았다면 새로고침을 눌러주시거나,<br>다른메뉴로 이동했다가 다시 내자산으로 들어오셔서 확인 부탁드립니다.</li>
											<li>- 입금했으나 충전이 되지 않았다면<br>
												1. 고객센터 -> 1:1문의<br>
												2. 스포와이드 공식 텔레그램: https://t.me/Spowide<br>
												3. 스포와이드 공식 카카오톡 : http://pf.kakao.com/_GxhxgZT<br>
												4. 스포와이드 공식 메일: cs@spowide.com<br>
												5. 고객센터 1588-5808<br>
												선택하셔서 문의 부탁드리겠습니다.</li>
										</ul>
									</div>
								</div>

								<!--출금-->
								<div class="toggle_con second_wdr_con" data-index="1">

									<div class="second_wdr_form_group">
										<!-- <p class="p_horizon"> -->
                                            <!--
											<span class="tit_small">{{__('trans.one_day_out_limit')}}</span>
                                            <span class="data_info" id="cash_withdraw_limit_amt"> <b></b> <b class="currency"></b> </span>
                                            -->
										<!-- </p> -->
									</div>

									<div class="second_wdr_form_group_wrap">
										<div class="second_wdr_form_group ta_form_group">
											<label>{{__('trans.out_address')}}</label>
											<input type="text" id="cash_withdraw_bank_info" value = "{{ $user_info->account_num }} {{ $user_info->account_bank }}" readonly>
										</div>
										

										<div class="second_wdr_form_group ta_form_group">
											<label id="cash_withdraw_amt_label">{{__('trans.outusdc')}}</label>
											<div class="pos_relative_box">
												<input type="number" class="ta_form_input" id = "cash_withdraw_amt" onchange="withdraw_onkey_amt();" placeholder="0">
												<span>원</span>
												<button class="btn" id="withdraw_amt_max_btn" onclick="withdraw_max_amt();">
													{{__('trans.max')}}
												</button>
											</div>
										</div>
										<div class="withdraw_fee_box">
											<p>
												<span>{{__('trans.out_fees')}}</span>
												<span> <b id="cash_withdraw_send_fee">0</b> 원</span>
											</p>
										</div>
									</div>
									<div class="total_price_box">
										<p>
											<span>{{__('trans.total_buying_deposite')}}</span>
											<span> <b id="cash_withdraw_total_amt">0</b> 원</span>
										</p>
									</div>
									<div class="second_wdr_form_group second_wdr_btn_group">
										<button class="second_wdr_btn" onclick="cash_withdraw();">
										{{__('trans.please_out')}}
										</button>
									</div>
									
									<div class="inout_notice_box">
									<h5 class="inout_notice_tit mb-2">※ 원화 및 코인 출금시 주의사항</h5>
										<ul class="inout_notice_list">
											<li>- 금융당국 가이드라인에 따라 보이스피싱, 자금세탁방지를 위해 첫 입금 후 120시간 이후부터 출금 가능합니다.</li>
											<li>- 출금 시간 안내 (365일) 평일: 09:00 ~ 20:00 | 주말 및 휴일: 13:00 ~ 20:00 (단, 명절 3일은 출금 휴무)<br></li>
											<li>- 보이스피싱, 자금세탁, 이상거래 판단 후 출금 승인하는데까지 10분 ~ 1시간 소요</li>
											<li>- 출금 승인 후 5~30분 안에 고객 통장으로 입금 완료<br>(단, 보이스피싱, 자금세탁, 이상거래 의심자로 확인될 경우 출금이 되지 않거나 철저한 검증 이후 출금진행될 수 있으니 이 점 참고바랍니다.)</li>
											<li>- 입출금 내역에서 출금 승인을 확인하였으나 입금되지 않았다면<br>
												1. 고객센터 -> 1:1문의<br>
												2. 스포와이드 공식 텔레그램: https://t.me/Spowide<br>
												3. 스포와이드 공식 카카오톡 : http://pf.kakao.com/_GxhxgZT<br>
												4. 스포와이드 공식 메일: cs@spowide.com<br>
												5. 고객센터 1588-5808<br>
												선택하셔서 문의 부탁드리겠습니다.</li>
										</ul>
									</div>

								</div>

								<!--입출금내역-->
								<div class="toggle_con third_history_con" data-index="2">

									<div class="third_history_hd">
										
									{{--<select>
											<option value="5">{{__('trans.all')}}</option>
											<option value="1">{{__('trans.in')}}</option>
											<option value="0">{{__('trans.out')}}</option>
										</select>--}}
							
										<ul>
											<li class="active" data-io="all">{{__('trans.all')}}</li>
											<li data-io="in">{{__('trans.in')}}</li>
											<li data-io="out">{{__('trans.out')}}</li>
										</ul>
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
		select_coin('KRW');

		$('.coin_category ul li').on('click', function(){
			$(this).siblings().removeClass('active');
			$(this).addClass('active');
			var kind = $(this).data('kind');

			if(kind == 'all'){
				$('.coin_chart_tbl tr').removeClass('hide');
			}else{
				$('.coin_chart_tbl tr').addClass('hide');
				$('.coin_chart_tbl tr[data-kind="'+kind+'"]').removeClass('hide');
			}
		});

        
        $('#cash_deposite').on('change paste keyup', function(){

            $('#before_acti_btn').addClass('active');

            if( $('#cash_deposite').val() == '' ){
                $('#before_acti_btn').removeClass('active');
            }

        })
        
	});

	function copy_text() {
        var copyText = document.createElement("input"); 
        copyText.setAttribute('display', 'none'); 
        copyText.setSelectionRange(0, 99999); 
        copyText.value = '{{Auth::user()->fullname}} {{$rand_num}}'; 
        document.body.appendChild(copyText); 
        copyText.select(); 
        document.execCommand("copy"); 
        document.body.removeChild(copyText);
        
        swal({
            text: '복사되었습니다.',
            icon: "success",
            button: __.message.ok
        }).then(function (confirm) {
        
        });
    
    }

	function cash_deposite_length_check(check){
        if (check.value.length > check.maxLength){
            check.value = check.value.slice(0, check.maxLength);
            swal({
            text: '최대 입금 가능액은 999,999,999원 입니다.',
			icon: "warning",
            button: __.message.ok
        	});
   		} 
    }
</script>
@endsection
