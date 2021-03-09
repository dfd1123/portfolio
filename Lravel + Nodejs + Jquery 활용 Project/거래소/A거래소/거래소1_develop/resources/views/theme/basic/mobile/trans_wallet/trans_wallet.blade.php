@extends(session('theme').'.mobile.layouts.app') 
@section('content')
<div class="m_hd_title">
    <div class="inner">
        {{ __('trans.inout') }}
    </div>
</div>

{{-- 입출금 --}}
<div id="m_transWltwrap" class="m_transwlt_wrap">

    <!-- 탑 버튼 -->
    <div class="btn_top_box">
        <a href="#" class="btn_top"><img src="/images/button/btn_fixed_top.svg" alt=""></a>  
    </div>

    <!-- 입출금 왼쪽 코인목록 -->
    <div class="left_wrap both_wrap">

        <!-- 위에 총 보유자산 및 코인검색바 -->
        <div class="status_bar">
            <div class="total_ast text-right">
                <label class="float-left tit">{{ __('trans.all_my_asset') }}</label>
                <span class="point_clr_db">{{ number_format($total_holding,0) }}</span>
                <span class="currency">원</span>
            </div>
            <div class="coin_category">
                <ul>
                    <li class="active" data-kind="sports">SPORTS COIN</li>
                    <li data-kind="public">PUBLIC COIN</li>
                </ul>
            </div>
            <div class="sch_bar">
                <div class="coin_sch_bar">
                    <div class="inner">
                        <input type="text" id="txtFilter_trans" class="none_border" tabindex="-1" placeholder="{{ __('trans.search_coin') }}" />
                        <i class="sch_icon"></i>
                    </div>
                </div>
                <div class="coin_sch_checkbox">
                    <input id="my_coin" type="checkbox" class="grayCheckbox hide" />
                    <!-- 스포츠 코인시 보유코인 컬러 -->
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
						@if($coin->symbol == 'KRW')
						<tr name="{!! __('coin_name.'.$coin->api) !!}/{{$coin->api}}" data-kind="currency" onclick = "select_coin('{{ $coin->symbol }}');" class = "{{ $result[$coin->api]['balance'] > 0 ? 'exist_balance' : '' }}">
                            <td class="coin_td" name="{!! __('coin_name.'.$coin->api) !!}" onclick = "select_coin('{{ $coin->symbol }}');">
                                <img src="{{ asset('/images/coin_img/'.$coin->api.'.png') }}" alt="coin_img" class="coin_img" />
                                <span class="coin_name">{!! __('coin_name.'.$coin->api) !!}</span>
                            </td>
                            <td>
                                <span id="holding_percent_{{ $coin->symbol }}">{{ ($total_holding == 0) ? number_format(0,2) : number_format($result[$coin->api]['balance'] * $coin->price_krw / $total_holding * 100,2) }}%</span>
                            </td>
                            <td>
                                <p>
                                    <span id="holding_balance_{{ $coin->symbol }}">{{ number_format($result[$coin->api]['balance'],0) }}</span><span class="currency">{{ $coin->symbol }}</span>
                                </p>
                                <p class="s_size">
                                    <span id="holding_convert_{{ $coin->symbol }}">{{ number_format($coin->price_krw * $result[$coin->api]['balance'],$coin->decimal_krw) }}</span>
                                    <span class="currency">KRW</span>
                                </p>
                            </td>
                            <td>
                                <input type="button" value="입출금" class="coin_inout_btn">
                            </td>
                            
                        </tr>
						@else
						<tr name="{!! __('coin_name.'.$coin->api) !!}/{{$coin->api}}" data-kind="{{$coin->market}}" onclick = "select_coin('{{ $coin->symbol }}');" class = "{{ $result[$coin->api]['balance'] > 0 ? 'exist_balance' : '' }} {{ $coin->market == 'public'?'hide':'' }}">
                            <td class="coin_td" name="{!! __('coin_name.'.$coin->api) !!}" onclick = "select_coin('{{ $coin->symbol }}');">
                                <img src="{{ asset('/images/coin_img/'.$coin->api.'.png') }}" alt="coin_img" class="coin_img" />
                                <span class="coin_name">{!! __('coin_name.'.$coin->api) !!}</span>
                            </td>
                            <td>
                                <span id="holding_percent_{{ $coin->symbol }}">{{ ($total_holding == 0) ? number_format(0,2) : number_format($result[$coin->api]['balance'] * $coin->price_krw / $total_holding * 100,2) }}%</span>
                            </td>
                            <td>
                                <p>
                                    <span id="holding_balance_{{ $coin->symbol }}">{{ number_format($result[$coin->api]['balance'],8) }}</span><span class="currency">{{ $coin->symbol }}</span>
                                </p>
                                <p class="s_size">
                                    <span id="holding_convert_{{ $coin->symbol }}">{{ number_format($coin->price_krw * $result[$coin->api]['balance'],$coin->decimal_krw) }}</span>
                                    <span class="currency">KRW</span>
                                </p>
                            </td>
                            <td>
                                <input type="button" value="입출금" class="coin_inout_btn">
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
                <label id="this_symbol">{{ __('trans.inout_usdc') }}</label>
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
                <label class="float-left tit">{{ __('trans.all_my_asset') }}</label>
                <span class="point_clr_db" id="this_balance_total"></span>
                <span class="currency"  id="this_balance_total_currency">KRW</span>
                <p class="s_size mb-2"  id="this_balance_eval">
                     <span class="currency">{{ __('trans.wait_trade') }}</span>
                </p>
                <p class="s_size">
                    <label class="float-left">{{ __('trans.wait_trade') }}</label>
                    <span id="this_balance_pending" class="trans_currency_clr"> <u>KRW</u></span>
                </p>
                <p class="s_size">
                    <label class="float-left">{{ __('trans.can_out') }}</label>
                    <span id="this_balance_available" class="trans_currency_clr"> <u>KRW</u></span>
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
                        {{ __('trans.do_request') }}
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
                        <img src="/images/icon/m_icon_security.svg" alt="" class="lock_icon">
                    <p class="mt-2">
                             {!! __('trans.need') !!}
                    </p>
                    @if($security_lv == 0)
                    <button class="btn_style mt-4 btn_dark" onclick="location.href='{{ route('mypage.security_setting') }}'">{{__('trans.injung')}}</button>
                    @else
                    <button class="btn_style mt-4" onclick="location.href='{{ route('mypage.security_setting') }}'">{{__('trans.injung')}}</button>
                    @endif
                    
                </div>
                @else
                <input id="con_in" type="radio" name="view_con" class="hide" />
                <input id="con_out" type="radio" name="view_con" class="hide" />
                <input id="con_history" type="radio" name="view_con" class="hide" />
				<div class="trans_coin_table active">
                    <!-- START 1.입금 -->
                    <div class="con_1 con_box">
                        <span class="tit mt-4 mb-2" id="first_deposit_info">{!! __('trans.my_BTC_in_address') !!}</span>
                        <span class="tit mt-4 mb-2" id="limit_deposit_info">(이더리움의 경우 0.1 ETH 이상만 입금가능)</span>

                        <span class="qr_code_span" id="deposit_coin_address_qrcode">
                            <!--img
                                src="{{ asset('/storage/image/homepage/chart.png') }}"
                                alt="qr"
                            /-->
                        </span>

                        <input class="qr_adrs" type="text" tabindex="-1" id="deposit_coin_address" />

                        <button type="button" class="mini_btn_st transwallet_btn" data-clipboard-action="copy" data-clipboard-target="#deposit_coin_address">{{ __('trans.copy_mb') }}</button>

                        <div class="coin_desti_wrap" id="deposit_address_desti_box">
                            <span class="coin_desti_label">{!! __('trans.desti') !!}</span>
                            <div class="coin_desti_group" >
                                <input type="text" class="coin_desti_input" id="deposit_coin_address_desti" readonly>
                                <button type="button" class="coin_desti_btn">복사</button>
                            </div>
                        </div>

                    </div>
                    <!-- //END 1.입금 -->

                    <!-- START 2.출금 -->
                    <div class="con_2 con_box">
                        <div class="form_group">
                            <!--
                            <label>{{ __('trans.one_day_out_limit') }}</label>
                            <span class="red float-right" id="withdraw_limit_amt">
                                <b>0</b><b class="currency">KRW</b>
                            </span>
                            -->
                        </div>

                        <div class="form_group_wrap bb-f0f0 bt-f0f0 pt-2 pb-1">
                            <div class="form_group mb-3">
                                <label>{{ __('trans.out_address') }}
                                        <!--span class="mini_btn_st btn_b_sp" onclick="withdraw_check_address();">{!! __('trans.m_check_address') !!}</span-->
                                </label>

                                    <!-- 출금주소 KRW 입출금시 출금주소 스타일  
                                    <input
                                        placeholder="{{ __('trans.input') }}"
                                        type="text"
                                        class="form-control light_line"
                                        id="withdraw_check_address"
                                    />-->

                                <input
                                    placeholder="{{ __('trans.input') }}"
                                    type="text"
                                    class="form-control"
                                    id="withdraw_check_address"
                                    tabindex="-1"
                                    style="padding-right:10px;"
                                />        

                            </div>

                            <div class="form_group mb-3" id="withdraw_address_desti_box">
                                <label>{{ __('trans.desti') }}
                                </label>

                                <input
                                    placeholder="{{ __('trans.input_desti') }}"
                                    type="text"
                                    class="form-control"
                                    id="withdraw_address_desti"
                                />
                            </div>
                                
                            <div id="QR_code_scan_btn">
                                <i class="fal fa-qrcode"></i>
                                <p>QR코드 스캔하기</p>
                            </div>

                            <div class="form_group ta_form_group pt_space pt-2 bt-f0f0">
                                <label>
                                    {{ __('trans.out_mb') }}
                                    <span class="mini_btn_st btn_sp_g" onclick="withdraw_max_amt();">{{ __('trans.max') }}</span>
                                </label>
                                <input type="number" class="form-control form_none_style"  id = "withdraw_amt" tabindex="-1"  onchange="withdraw_onkey_amt();"/>
                            </div>

                        </div>

                        <div class="form_group bb-f0f0 pt-2 pb-2">
                            <label class="out_font_clr">{{ __('trans.out_fees') }}</label>
                            <span class="float-right">
                                <b id="withdraw_send_fee">0.00000000</b> <b class="currency" id="withdraw_send_fee_currency"></b>
                            </span>
                        </div>

                        <div class="form_group mb-4 lg_font bb_line">
                            <label>{{ __('trans.all_out') }}</label>
                            <span class="float-right">
                                <b id="withdraw_total_amt">0.00000000</b> <b class="currency" id="withdraw_total_amt_currency"></b>
                            </span>
                        </div>

                        <div class="form_group mb-5">
                            <button class="mini_btn_dark" type="button" onclick="send_transaction();">{{ __('trans.outing') }}</button>
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
                </div>
                    
                    <div class="trans_cash_table hide">
                        @if($security_lv < 3)
                        <div class="lv_up_alert">
                                <img src="/images/icon/m_icon_security.svg" alt="" class="lock_icon">
                            <p class="mt-2">
                                    {!! __('trans.need') !!}
                            </p>
                            @if($security_lv == 0)
                            <button class="btn_style mt-4 btn_dark" onclick="location.href='{{ route('mypage.security_setting') }}'">{{__('trans.injung')}}</button>
                            @else
                            <button class="btn_style mt-4" onclick="location.href='{{ route('mypage.security_setting') }}'">{{__('trans.injung')}}</button>
                            @endif
                            
                        </div>
                        @else
                        <!-- START 1.입금 -->
                        <div class="con_1 con_box bb_line">
                            <div id="deposite_krw_info_box" style="display:none;">
                            <h4 class="bank_infor">국민은행 529401-01-250937 스포홀딩스</h4>
                                <p>입금 통장 표시 : <b>{{Auth::user()->fullname}} {{$rand_num}}</b>
                                <button class="btn" style="outline: none; padding:0px; margin-bottom:0px; font-size: 0.9rem" onclick="copy_text();"><img src="/images/copy.png" alt="" /></button>
                                </p>
                            </div>
                            <span class="tit mt-4 mb-2 cash" id="first_deposit_info">
                                {!! __('trans.cash_sentence1') !!}
                            </span>
                            <br />(ex. 입금통장 표시 : 홍길동 {{$rand_num}})
                            <br />계좌번호와 인증코드는 입금요청 버튼을 <br /> 눌러야만 제공됩니다.

                            <div class="cash_inp_div">
                                <input type="hidden" id="verification_code" value="{{$rand_num}}" />
                                <input class="mb-2 form-control" type="number" id="cash_deposite" tabindex="-1" placeholder="입금예정금액을 입력하세요." maxlength="9" oninput="cash_deposite_length_check(this)"/>                                
                            </div>     
                            <button type="button" class="mini_btn_st before_acti_btn" id="before_acti_btn" onclick="cash_deposite();">{{__('trans.in')}}</button>
                            
                            
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
                        <!-- //END 1.입금 -->

                        <!-- START 2.출금 -->
                        <div class="con_2 con_box">
                            
                            <div class="form_group">
                                <!--
                                <label>{{ __('trans.one_day_out_limit') }}</label>
                                <span class="red float-right" id="cash_withdraw_limit_amt"><b>0</b><b class="currency">KRW</b></span>
                                -->
                            </div>

                            <div class="form_group_wrap bb-f0f0 pt-2 pb-1">
                                <div class="form_group mb-3">
                                    <label>{{ __('trans.out_address') }}</label>
                                    <input
                                        placeholder="{{ __('trans.input') }}"
                                        type="text"
                                        class="form-control"
                                        id="cash_withdraw_bank_info"
                                        value = "{{ $user_info->account_num }} {{ $user_info->account_bank }}"
                                        tabindex="-1"
                                        readonly
                                    />
                                </div>

                                <div class="form_group ta_form_group">
                                    <label
                                        >{{ __('trans.out_mb') }}
                                        <span class="mini_btn_st cash_max_btn" onclick="withdraw_max_amt();">{{ __('trans.max') }}</span></label
                                    >
                                    <input type="number" class="form-control"  id = "cash_withdraw_amt" tabindex="-1"  onchange="withdraw_onkey_amt();"/>
                                </div>
                            </div>

                            <div class="form_group bb-f0f0 pt-2 pb-2">
                                <label>{{ __('trans.out_fees') }}</label>
                                <span class="float-right">
                                    <b id="cash_withdraw_send_fee">0</b> <b class="currency">원</b>
                                </span>
                            </div>

                            <div class="form_group mb-4 lg_font">
                                <label>{{ __('trans.all_out') }}</label>
                                <span class="float-right">
                                    <b id="cash_withdraw_total_amt">0</b> <b class="currency">원</b>
                                </span>
                            </div>
                            

                            <div class="form_group mb-5">
                                <button class="btn_style" type="button" onclick="cash_withdraw();">{{ __('trans.outing') }}</button>
                            </div>

                            
                            <div class="inout_notice_box">
                                <h5 class="inout_notice_tit mb-2">※ 입출금 유의사항</h5>
                                <ul class="inout_notice_list">
                                    <li>- 금융당국 가이드라인에 따라 보이스피싱, 자금세탁방지를 위해 첫 입금 후 120시간 이후부터 출금 가능합니다.</li>
                                    <li>- 출금 시간 안내 (365일)<br>
                                    평일: 09:00 ~ 20:00<br>주말 및 휴일: 13:00 ~ 20:00<br>(단, 명절 3일은 출금 휴무)</li>
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
                                <ul class="list" id="cash_list">
                                    
                                </ul>
                            </div>
                        </div>
                        <!-- //END 3.입출금내역 -->
                        @endif
                    </div>
                    
                @endif
            @endif
			
            <div id="goTolist" class="abslt_btn" style="display:none;">
            {{ __('trans.list') }}
            </div>
        </div>
    </div>
    <!-- //입출금 오른쪽 기능 -->
    
</div>

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


    //탑버튼
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('.btn_top').fadeIn();
        } else {
            $('.btn_top').fadeOut();
        }
    });
    
    $(".btn_top").click(function() {
        $('html, body').animate({
            scrollTop : 0
        }, 400);
        return false;
    });

    $('.coin_category ul li').on('click', function(){
        $('.coin_category ul li').removeClass('active');
        $(this).addClass('active');

        var kind = $(this).data('kind');

        if(kind == 'all'){
            $('.coin_chart_tbl tr').removeClass('hide');
        }else{
            $('.coin_chart_tbl tr').addClass('hide');
            $('.coin_chart_tbl tr[data-kind="'+kind+'"]').removeClass('hide');
            $('.coin_chart_tbl tr[data-kind="currency"]').removeClass('hide');
        }
    });
        
    $('#cash_deposite').on('change paste keyup', function(){

        $('#before_acti_btn').addClass('active');

        if( $('#cash_deposite').val() == '' ){
            $('#before_acti_btn').removeClass('active');
        }

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
