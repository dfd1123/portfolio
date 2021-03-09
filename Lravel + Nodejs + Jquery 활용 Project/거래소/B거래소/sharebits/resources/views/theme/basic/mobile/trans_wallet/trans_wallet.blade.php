@extends(session('theme').'.mobile.layouts.app') 
@section('content')
<div class="m_hd_title">
    <div class="inner">
        {{ __('trans.inout') }}
    </div>
</div>

{{-- 입출금 --}}
<div id="m_transWltwrap" class="m_transwlt_wrap">
    
    <!-- 입출금 왼쪽 코인목록 -->
    <div class="left_wrap both_wrap">

        <!-- 위에 총 보유자산 및 코인검색바 -->
        <div class="status_bar">
            <div class="total_ast text-right">
                <label class="float-left tit">{{ __('trans.all_my_asset') }}</label>
                <span class="point_clr_1">{{ number_format($total_holding,8) }}</span>
                <span class="currency">UCSS</span>
            </div>
            <div class="sch_bar">
                <div class="coin_sch_bar">
                    <div class="inner">
                        <input type="text" id="txtFilter_trans" placeholder="{{ __('trans.search_coin') }}" />
                        <i class="sch_icon"></i>
                    </div>
                </div>
                <div class="coin_sch_checkbox">
                    <input id="my_coin" type="checkbox" class="grayCheckbox hide" />
                    <label for="my_coin">&nbsp;{{ __('trans.my_coin') }}</label>
                </div>
            </div>
        </div>
        <!-- //위에 총 보유자산 및 코인검색바 -->

        <!-- 밑에 코인테이블 -->
        <table class="table_label">
            <thead>
                <tr>
                    <th>{{ __('trans.coin') }}</th>
                    <th>{{ __('trans.holding_weight') }}</th>
                    <th>{{ __('trans.holding_quantity_mb') }}</th>
                    <th></th>
                </tr>
            </thead>
        </table>

        <!-- * scrl_wrap만 스크롤할 수 있게 지정해놓음 -->
        <div class="scrl_wrap">
            <div class="coin_table">
                <table class="coin_chart_tbl" id="coin_list_table">
                    <tbody>
                    @foreach($coins as $coin)
						@if($coin->symbol == config('app.default_cash'))
						<tr name="UCSS" onclick = "select_coin('{{ $coin->symbol }}');" class = "{{ $result[$coin->api]['balance'] > 0 ? 'exist_balance' : '' }}">
                            <td class="coin_td">
                                <img src="{{ asset('/storage/image/homepage/coin_img/ucss.png') }}" alt="coin_img" class="coin_img" />
                                <span class="coin_name">UCSS</span>
                            </td>
                            <td>
                                <span id="holding_percent_{{ $coin->symbol }}">{{ ($total_holding == 0) ? number_format(0,2) : number_format($result[$coin->api]['balance'] * $coin->price_usd / $total_holding * 100,2) }}%</span>
                            </td>
                            <td>
                                <p>
                                    <span id="holding_balance_{{ $coin->symbol }}">{{ number_format($result[$coin->api]['balance'],8) }}</span><span class="currency">UCSS</span>
                                </p>
                                <p class="s_size">
                                    <span id="holding_convert_{{ $coin->symbol }}">{{ ($btc_price_usd == 0) ? number_format(0) : number_format($result[$coin->api]['balance'] / $btc_price_usd, 8) }}</span><span class="currency">BTC</span>
                                </p>
                            </td>
                            <td>
	                            <span>
	                            	<i class="fal fa-angle-right point_clr_2 ml-1"></i>
	                            </span>
                            </td>
                        </tr>
						@elseif($coin->symbol == 'BTC')
						<tr name="{{ __('coin_name.'.$coin->api) }}/{{$coin->api}}" onclick = "select_coin('{{ $coin->symbol }}');" class = "{{ $result[$coin->api]['balance'] > 0 ? 'exist_balance' : '' }}">
                            <td class="coin_td" name="{{ __('coin_name.'.$coin->api) }}" onclick = "select_coin('{{ $coin->symbol }}');">
                                <img src="{{ asset('/storage/image/homepage/coin_img/'.$coin->api.'.png') }}" alt="coin_img" class="coin_img" />
                                <span class="coin_name">{{ __('coin_name.'.$coin->api) }}</span>
                            </td>
                            <td>
                                <span id="holding_percent_{{ $coin->symbol }}">{{ ($total_holding == 0) ? number_format(0,2) : number_format($result[$coin->api]['balance'] * $coin->price_usd / $total_holding * 100,2) }}%</span>
                            </td>
                            <td>
                                <p>
                                    <span id="holding_balance_{{ $coin->symbol }}">{{ number_format($result[$coin->api]['balance'],8) }}</span><span class="currency">{{ $coin->symbol }}</span>
                                </p>
                                <p class="s_size">
                                    <span id="holding_convert_{{ $coin->symbol }}">{{ number_format($coin->price_usd * $result[$coin->api]['balance'],$coin->decimal_usd) }}</span>
                                    <span class="currency">UCSS</span>
                                </p>
                            </td>
                            <td class="td_btn">
                                <button onclick="exchangeCashCoin('{{ $coin->symbol }}','{{ config('app.default_cash') }}');">UCSS<br/>{{ __('trans.change') }}</button>
                            </td>
                        </tr>
						@else
						<tr name="{{ __('coin_name.'.$coin->api) }}/{{$coin->api}}" onclick = "select_coin('{{ $coin->symbol }}');" class = "{{ $result[$coin->api]['balance'] > 0 ? 'exist_balance' : '' }}">
                            <td class="coin_td" name="{{ __('coin_name.'.$coin->api) }}" onclick = "select_coin('{{ $coin->symbol }}');">
                                <img src="{{ asset('/storage/image/homepage/coin_img/'.$coin->api.'.png') }}" alt="coin_img" class="coin_img" />
                                <span class="coin_name">{{ __('coin_name.'.$coin->api) }}</span>
                            </td>
                            <td>
                                <span id="holding_percent_{{ $coin->symbol }}">{{ ($total_holding == 0) ? number_format(0,2) : number_format($result[$coin->api]['balance'] * $coin->price_usd / $total_holding * 100,2) }}%</span>
                            </td>
                            <td>
                                <p>
                                    <span id="holding_balance_{{ $coin->symbol }}">{{ number_format($result[$coin->api]['balance'],8) }}</span><span class="currency">{{ $coin->symbol }}</span>
                                </p>
                                <p class="s_size">
                                    <span id="holding_convert_{{ $coin->symbol }}">{{ number_format($coin->price_usd * $result[$coin->api]['balance'],$coin->decimal_usd) }}</span>
                                    <span class="currency">UCSS</span>
                                </p>
                            </td>
                            <td>
                                <span>
                                    <i class="fal fa-angle-right point_clr_2 ml-1"></i>
                                </span>
                            </td>
                        </tr>
						@endif
					@endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- //밑에 코인테이블 -->

    </div>
    <!-- //입출금 왼쪽 코인목록 -->

    <!-- 입출금 오른쪽 기능 -->
    <input type="hidden" id="this_symbol_hidden">
	<input type="hidden" id="this_symbol_text_hidden">
    
    <div class="right_wrap both_wrap">

        <!-- 위에 유의사항 및 코인정보들 -->
        <div class="status_bar">
            <h1>
                <label id="this_symbol">{{ __('trans.inout_ucss') }}</label>
                <label class="ntc_label" for="ntc_box">{{ __('trans.ue') }}
                    <input id="ntc_box" type="checkbox" class="hide" />
                    <span class="ntc_box_ment">
                        <strong>{!! __('trans.inout_notice') !!}</strong>
                        <span>
                        - {!! __('trans.trans_sentence3') !!}
                        </span><br><br>
                        <span>
                        - {!! __('trans.trans_sentence4') !!}
                        </span><br><br>
                        <span>
                        - {!! __('trans.trans_sentence5') !!}
                        </span>
                    </span>
                </label>
            </h1>

            <div class="total_ast text-right">
                <label class="float-left tit">{{ __('trans.holding_quantity') }}</label>
                <span class="point_clr_1" id="this_balance_total"></span>
                <span class="currency"  id="this_balance_total_currency">UCSS</span>
                <p class="s_size mb-2"  id="this_balance_eval">
                     <span class="currency">UCSS</span>
                </p>
                <p class="s_size">
                    <label class="float-left">{{ __('trans.wait_trade') }}</label>
                    <span id="this_balance_pending"> <u>UCSS</u></span>
                </p>
                <p class="s_size">
                    <label class="float-left">{{ __('trans.can_out') }}</label>
                    <span id="this_balance_available"> <u>UCSS</u></span>
                </p>
            </div>
        </div>
        <!-- //위에 유의사항 및 코인정보들 -->

        <!-- 기능들 -->
        <div class="m_tab_list" id="trans_wlt_tab">
            <ul class="w-120_eng">
                <li class="active">
                    <a href="#">
                        <label for="con_in">
                        {{ __('trans.ining') }}
                        </label>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <label for="con_out">
                        {{ __('trans.outing') }}
                        </label>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <label for="con_history" class="lh-20">
                        {{ __('trans.inout_list') }}
                        </label>
                    </a>
                </li>
            </ul>
        </div>
        <!-- //기능들 -->

        <!-- * scrl_wrap만 스크롤할 수 있게 지정해놓음 -->
        <div class="scrl_wrap">
            @if(Auth::user()->status == 2)
                <div class="lv_up_alert">
                    <p class="mt-5">
                        <i class="fas fa-exclamation-circle"></i> 계정이 정지된 회원은 입출금을 이용하실 수 없습니다.
                    </p>
                    <button class="btn_style stop_user_id_warning mt-4">계정 정지</button>
                    
                </div>
            @else
                @if($security_lv < 2)
                <div class="lv_up_alert">
                    <p class="mt-5">
                        <i class="fas fa-exclamation-circle"></i> {{ __('trans.need') }}
                    </p>
                    @if($security_lv == 0)
                    <button class="btn_style mt-4" onclick="location.href='{{ route('mypage.security_setting') }}'">{{__('trans.injung')}}</button>
                    @else
                    <button class="btn_style mt-4" onclick="location.href='{{ route('mypage.security_setting') }}'">{{__('trans.injung')}}</button>
                    @endif
                    
                </div>
                @else
                    <input id="con_in" type="radio" name="view_con" class="hide" />
                    <input id="con_out" type="radio" name="view_con" class="hide" />
                    <input id="con_history" type="radio" name="view_con" class="hide" />

                    <!-- START 1.입금 -->
                    <div class="con_1 con_box">
                        <span class="tit mt-4" id="first_deposit_info">{!! __('trans.my_BTC_in_address') !!}</span>

                        <span class="qr_code_span" id="deposit_coin_address_qrcode">
                            <!--img
                                src="{{ asset('/storage/image/homepage/chart.png') }}"
                                alt="qr"
                            /-->
                        </span>

                        <input class="qr_adrs mb-3 form-control" type="text" id="deposit_coin_address" />

                        <button type="button" class="mini_btn_st transwallet_btn" data-clipboard-action="copy" data-clipboard-target="#deposit_coin_address">{{ __('trans.copy_mb') }}</button>
                    </div>
                    <!-- //END 1.입금 -->

                    <!-- START 2.출금 -->
                    <div class="con_2 con_box">
                        <div class="form_group">
                            <label>{{ __('trans.one_day_out_limit') }}</label>
                            <span class="red float-right" id="withdraw_limit_amt">
                                <b>0</b> <b class="currency">UCSS</b>
                            </span>
                    </div>

                    <div class="form_group_wrap bb-f0f0 bt-f0f0 pt-2 pb-1">
                        <div class="form_group mb-3">
                            <label>{{ __('trans.out_address') }}
                                    <span class="mini_btn_st" onclick="withdraw_check_address();">{!! __('trans.m_check_address') !!}</span></label>
                                <input
                                    placeholder="{{ __('trans.input') }}"
                                    type="text"
                                    class="form-control"
                                    id="withdraw_check_address"
                                />
                            </div>

                            <div class="form_group ta_form_group">
                                <label
                                    >{{ __('trans.out_mb') }}
                                    <span class="mini_btn_st" onclick="withdraw_max_amt();">{{ __('trans.max') }}</span></label
                                >
                                <input type="number" class="form-control"  id = "withdraw_amt"  onchange="withdraw_onkey_amt();"/>
                            </div>
                        </div>

                        <div class="form_group bb-f0f0 pt-2 pb-2">
                            <label>{{ __('trans.out_fees') }}</label>
                            <span class="float-right">
                                <b id="withdraw_send_fee">0.00000000</b> <b class="currency" id="withdraw_send_fee_currency"></b>
                            </span>
                        </div>

                        <div class="form_group mb-4 lg_font">
                            <label>{{ __('trans.out_fees') }}</label>
                            <span class="float-right">
                                <b id="withdraw_total_amt">0.00000000</b> <b class="currency" id="withdraw_total_amt_currency"></b>
                            </span>
                        </div>

                        <div class="form_group mb-5">
                            <button class="btn_style" type="button" onclick="send_transaction();">{{ __('trans.outing') }}</button>
                        </div>
                    </div>
                    <!-- //END 2.출금 -->

                    <!-- START 3.입출금내역 -->
                    <div class="con_3 con_box">
                        <div class="sch_bar">
                            <div class="coin_sch_checkbox">
                                <select>
                                    <option value="5">{{ __('trans.all_tr') }}</option>
                                    <option value="1">{{ __('trans.in') }}</option>
                                    <option value="0">{{ __('trans.out') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="history_st">
                            <ul class="list" id="transaction_list">
                                
                            </ul>
                        </div>
                    </div>
                    <!-- //END 3.입출금내역 -->
                    <div class="posi_wrap">
                        <div>
                            <div id="loading"></div>
                        </div>
                    </div>
                @endif
            @endif
			
            <div id="goTolist" class="abslt_btn hide">
            {{ __('trans.list') }}
            </div>
        </div>
    </div>
    <!-- //입출금 오른쪽 기능 -->
    
</div>
{{-- //입출금 --}}
<script>
	if (typeof __ === 'undefined') { var __ = {}; }
    __.trans = {
        @foreach(__('trans') as $key => $value)
            '{{$key}}':'{{$value}}',
        @endforeach
    }
    
    $(function(){
		update_erc_eth_balance(); 
	});
</script>
@endsection