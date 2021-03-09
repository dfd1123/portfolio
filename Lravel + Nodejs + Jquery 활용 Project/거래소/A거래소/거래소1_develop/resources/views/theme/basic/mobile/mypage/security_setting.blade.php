@extends(session('theme').'.mobile.layouts.app') 
@section('content')
    @include(session('theme').'.mobile.mypage.include.mypage_hd')

<div class="m_mypage_wrap scrl_wrap m_mypage_wrap-lvup">
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
                <button type="button" class="security_change_btn_success">{{ __('myp.btn_certi_success') }}</button>
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
            @if($status < 2)
                <span id="account_infor" class="indiv_info">{{ __('myp.no_registered_account') }}</span>
                <button type="button" id="account_status_btn" class="security_change_btn" onclick="swal('','휴대폰 인증부터 진행하세요.', 'warning', {button:'확인'})">{{ __('myp.btn_certification') }}</button>
            @elseif($status == 2)
                <span class="indiv_info">{{ __('myp.no_registered_account') }}</span>
                <button type="button" id="account_status_btn" class="security_change_btn" onclick="mypage_popup_open('#popup_page_account')">{{ __('myp.btn_certification') }}</button>
            @else
                <span id="account_infor" class="indiv_info">{{$security->account_bank.' '.$security->account_num}}</span>
                <button type="button" id="account_status_btn" class="security_change_btn" onclick="mypage_popup_open('#popup_page_account')">{{ __('myp.btn_exchange') }}</button>
            @endif
        </div>
    </div>
</div>


<script>
    if (typeof __ === 'undefined') { var __ = {}; }
    __.boan = {
        @foreach(__('boan') as $key => $value)
            '{{$key}}':'{{$value}}',
        @endforeach
    }
</script>

@include(session('theme').'.mobile.mypage.include.mypage_modal')


@endsection