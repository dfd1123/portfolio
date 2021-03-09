@extends(session('theme').'.mobile.layouts.app')

@section('content')

@include(session('theme').'.mobile.mypage.include.mypage_hd')

<div class="m_mypage_wrap scrl_wrap m_mypage_wrap-2">
            
        @if($status == 2)
        
        <p class="mypage_msg text-center">
            <span class="tit">{{ __('myp.enrollmnet_OTP') }}</span>
            {{ __('myp.mypage_sentence3') }}<br>
            {{ __('myp.mypage_sentence2') }}
        </p>
        
        <div class="top_qr_box">
            <span class="qr_box"><img src="{{$qr_image}}" /></span>
            <p class="mt-2">{{$google2fa_secret}}</p>
        </div>
            
        <div class="otp_div">
            <form method="post" action="{{route('mypage.otp_setting_register')}}">
    
                @csrf
                
                <input type="text" placeholder="{{ __('myp.input_OTP') }}" class="form-control mb-2" name="secret" required/>
                <button type="submit" class="btn_style">{{ __('myp.enrollment') }}</button>
    
            </form>
        </div>

        <div class="otp_setting_group">
            <span class="s_txt">{{ __('myp.OTP_download') }}</span>
            <a class="btn_style download_btn mt-2 mb-2" target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">{!! __('myp.andrioid_download') !!}</a>
            <a class="btn_style download_btn" target="_blank" href="https://itunes.apple.com/app/google-authenticator/id388497605">{!! __('myp.apple_download') !!}</a>
        </div>
        
        <div class="otp_setting_guide">
            
        </div>
        
        @elseif($status == 3)
        <div class="lv_certifi_after">
            
            <img src="{{ asset('/storage/image/homepage/icon/icon_otp.svg')}}" alt="complit_icon" class="complit_icon mb-4 mt-5"/>
                
            <h2 class="big_tit notosans mb-4">{{ __('otp.complete')}}</h2>
            
            <p class="ment small_ment boxing mb-4">
                재등록 및 해지를 원하신다면 <br><b>고객센터 > 자주 묻는 질문 > OTP 재등록 및 해지</b>에서 내용을 확인해주세요.
            </p>
            
        </div>
        
        @endif
    </div>
 
 
@endsection
