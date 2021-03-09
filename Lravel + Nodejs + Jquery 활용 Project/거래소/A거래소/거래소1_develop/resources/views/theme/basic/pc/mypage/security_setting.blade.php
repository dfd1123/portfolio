@extends(session('theme').'.pc.layouts.app') 
@section('content')
    @include(session('theme').'.pc.mypage.include.mypage_hd')
<!-- 계좌 인증 모달 팝업 컨텐츠 start -->
<div id="account" class="modal-window" style="display:none;">
    <div class="modal_content">
        <div id="cancel_btn">
            <span></span>
        </div>
        <div class="my_page_pop_con">
            <h3 class="pop_tit mb-3">{{ __('myp.pop_certi_tit') }}</h3>
            <h3 class="pop_sub_sentence mb-3">{{ __('myp.pop_certi_sub') }}</h3>
            <p class="pop_sub_sentence mb-4"></p>
        </div>
        <div class="bank_sch_checkbox">
            <label class="certify_label mt-3 mb-2">{{ __('myp.bank_name') }}</label>
            <label for="check_pop_bank" id="check_bank" class="bank_label"><em>{{ __('myp.select') }}</em>
                <span class="bank_coin_name"></span>
            </label>
            <input type="checkbox" name="bank_chart_check" id="check_pop_bank" style="display: none;"  placeholder="{{ __('myp.write_account') }}">
            <div class="bank_view">
                <ul>
                    <li data-code="002">산업</li>
                    <li data-code="003">기업</li>
                    <li data-code="004">국민</li>
                    <li data-code="007">수협</li>
                    <li data-code="011">농협</li>
                    <li data-code="012">농협중앙회</li>
                    <li data-code="020">우리</li>
                    <li data-code="023">SC제일</li>
                    <li data-code="027">한국씨티</li>
                    <li data-code="031">대구</li>
                    <li data-code="032">부산</li>
                    <li data-code="034">광주</li>
                    <li data-code="035">제주</li>
                    <li data-code="037">전북</li>
                    <li data-code="039">경남</li>
                    <li data-code="045">새마을금고중앙회</li>
                    <li data-code="048">신협중앙회</li>
                    <li data-code="050">상호저축</li>
                    <li data-code="054">HSBC</li>
                    <li data-code="055">도이치</li>
                    <li data-code="057">제이피모간체이스</li>
                    <li data-code="060">BOA</li>
                    <li data-code="062">중국공상</li>
                    <li data-code="064">산림조합중앙회</li>
                    <li data-code="071">우체국</li>
                    <li data-code="081">KEB하나</li>
                    <li data-code="088">신한은행</li>
                    <li data-code="089">K뱅크</li>
                    <li data-code="090">카카오뱅크</li>
                </ul>
                <input type="hidden" id="check_bank_name">
            </div>
        </div>
        <div class="act_number_box">
            <label class="certify_label mt-3 mb-2">{{ __('myp.account_number') }}</label>
            <input id="account_num" type="number" class="pl-120px certifi_form_input auth_input form-control mb-2" name="mobile_certify_code" value="" placeholder="{{ __('myp.write_account') }}">
        </div>

        <button type="button" id="account_certify_btn" class="btn_style mt-3">{{ __('myp.account_certi_sending') }}</button>

        <div id="account_confirm_form" class="hide">
            <span class="pop_division mt-4"></span>
            <div class="check_form">
                <p class="pop_checking_sub mt-4">
                    {!! __('myp.account_certi_sub') !!}
                </p>
                <div class="act_number_box">
                    <label class="certify_label mt-4 mb-2">{{ __('myp.write_certi_tit') }}</label>
                    <input id="act_number_code" type="number" class="pl-120px certifi_form_input auth_input form-control mb-3 certifi_line" name="mobile_certify_code" value="" placeholder="{{ __('myp.certi_number') }}">
                    <button type="button" id="account_certify_confirm_btn" class="file_btn_style number_space">{{ __('myp.checking_number') }}</button>
                </div>
                <span class="account_checking_division"></span>
            </div>
        </div>
    </div>
</div>
<!-- 계좌 인증 모달 팝업 컨텐츠 end -->


<div class="mypage_wrap">
    <div class="mypage_inner">
        <div class="indiv_info_confirm">
            <div class="certi_confirm_box">
                <h3 class="certi_main_txt">{{ __('myp.email_security_tit') }}</h3>
                <p class="certi_sub_txt">
                    {{ __('myp.email_security_sub_txt') }}
                </p>
                <span class="indiv_info">{{Auth::user()->email}}</span>
                @if($status == 0)
                    <a class="security_change_btn" href="/email/verify" target="_blank" >{{ __('myp.btn_certification') }}</a>
                @else
                    <button type="button" class="security_change_btn active">{{ __('myp.btn_certi_success') }}</button>
                @endif
            </div>
            <div class="certi_confirm_box active">
                <h3 class="certi_main_txt">{{ __('myp.phone_security_tit') }}</h3>
                <p class="certi_sub_txt">{{ __('myp.phone_security_sub_txt') }}</p>
                <span class="indiv_info">{{Auth::user()->mobile_number}}</span>
                @if($status < 1)
                    <button type="button" id="mobile_status_btn" class="security_change_btn" onclick="swal('','이메일 인증부터 진행하세요.', 'warning', {button:'확인'})">{{ __('myp.btn_certification') }}</button>
                @elseif($status == 1)
                    <button type="button" id="mobile_status_btn" class="security_change_btn" onclick="fnPopup();">{{ __('myp.btn_certification') }}</button>
                @else
                    <button type="button" id="mobile_status_btn" class="security_change_btn" onclick="fnPopup();">{{ __('myp.btn_exchange') }}</button>
                @endif
                <form name="form_chk" method="post">
                    <input type="hidden" name="m" value="checkplusSerivce">	
                    <input type="hidden" name="EncodeData" value="{{ $enc_data }}">
                </form>
            </div>
            <div class="certi_confirm_box">
                <h3 class="certi_main_txt">{{ __('myp.pop_certi_tit') }}</h3>
                <p class="certi_sub_txt">
                    {{ __('myp.account_security_tit') }}
                </p>
                
                <!-- 인증 완료 전 -->
                @if($status < 2)
                    <span id="account_infor" class="indiv_info">{{ __('myp.no_registered_account') }}</span>
                    <button type="button" id="account_status_btn" class="security_change_btn" onclick="swal('','휴대폰 인증부터 진행하세요.', 'warning', {button:'확인'})">{{ __('myp.btn_certification') }}</button>
                @elseif($status == 2)
                    <span class="indiv_info">{{ __('myp.no_registered_account') }}</span>
                    <button type="button" id="account_status_btn" class="security_change_btn" onclick="jw_modal_open('account')">{{ __('myp.btn_certification') }}</button>
                @else
                    <span id="account_infor" class="indiv_info">{{$security->account_bank.' '.$security->account_num}}</span>
                    <button type="button" id="account_status_btn" class="security_change_btn" onclick="jw_modal_open('account')">{{ __('myp.btn_exchange') }}</button>
                @endif

            </div>
        </div>
    </div>
</div>
<template id="account_in_popup_content">
    <div class="modal_content">
        <div id="cancel_btn">
            <span></span>
        </div>
        <div class="my_page_pop_con">
            <h3 class="pop_tit mb-3">{{ __('myp.pop_certi_tit') }}</h3>
            <h3 class="pop_sub_sentence mb-3">{{ __('myp.pop_certi_sub') }}</h3>
            <p class="pop_sub_sentence mb-4"></p>
        </div>
        <div class="bank_sch_checkbox">
            <label class="certify_label mt-3 mb-2">{{ __('myp.bank_name') }}</label>
            <label for="check_pop_bank" id="check_bank" class="bank_label"><em>{{ __('myp.select') }}</em>
                <span class="bank_coin_name"></span>
            </label>
            <input type="checkbox" name="bank_chart_check" id="check_pop_bank" style="display: none;"  placeholder="{{ __('myp.write_account') }}">
            <div class="bank_view">
                <ul>
                    <li data-code="002">산업</li>
                    <li data-code="003">기업</li>
                    <li data-code="004">국민</li>
                    <li data-code="007">수협</li>
                    <li data-code="011">농협</li>
                    <li data-code="012">농협중앙회</li>
                    <li data-code="020">우리</li>
                    <li data-code="023">SC제일</li>
                    <li data-code="027">한국씨티</li>
                    <li data-code="031">대구</li>
                    <li data-code="032">부산</li>
                    <li data-code="034">광주</li>
                    <li data-code="035">제주</li>
                    <li data-code="037">전북</li>
                    <li data-code="039">경남</li>
                    <li data-code="045">새마을금고중앙회</li>
                    <li data-code="048">신협중앙회</li>
                    <li data-code="050">상호저축</li>
                    <li data-code="054">HSBC</li>
                    <li data-code="055">도이치</li>
                    <li data-code="057">제이피모간체이스</li>
                    <li data-code="060">BOA</li>
                    <li data-code="062">중국공상</li>
                    <li data-code="064">산림조합중앙회</li>
                    <li data-code="071">우체국</li>
                    <li data-code="081">KEB하나</li>
                    <li data-code="088">신한은행</li>
                    <li data-code="089">K뱅크</li>
                    <li data-code="090">카카오뱅크</li>
                </ul>
                <input type="hidden" id="check_bank_name">
            </div>
        </div>
        <div class="act_number_box">
            <label class="certify_label mt-3 mb-2">{{ __('myp.account_number') }}</label>
            <input id="account_num" type="number" class="pl-120px certifi_form_input auth_input form-control mb-2" name="mobile_certify_code" value="" placeholder="{{ __('myp.write_account') }}">
        </div>

        <button type="button" id="account_certify_btn" class="btn_style mt-3">{{ __('myp.account_certi_sending') }}</button>

        <div id="account_confirm_form" class="hide">
            <span class="pop_division mt-4"></span>
            <div class="check_form">
                <p class="pop_checking_sub mt-4">
                    {!! __('myp.account_certi_sub') !!}
                </p>
                <div class="act_number_box">
                    <label class="certify_label mt-4 mb-2">{{ __('myp.write_certi_tit') }}</label>
                    <input id="act_number_code" type="number" class="pl-120px certifi_form_input auth_input form-control mb-3 certifi_line" name="mobile_certify_code" value="" placeholder="{{ __('myp.certi_number') }}">
                    <button type="button" id="account_certify_confirm_btn" class="file_btn_style number_space">{{ __('myp.checking_number') }}</button>
                </div>
                <span class="account_checking_division"></span>
            </div>
        </div>
    </div>
</template>
<script>
    if (typeof __ === 'undefined') { var __ = {}; }
    __.boan = {
        @foreach(__('boan') as $key => $value)
            '{{$key}}':'{{$value}}',
        @endforeach
    }
</script>
    

@endsection