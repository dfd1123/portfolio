@extends(session('theme').'.pc.layouts.app') 
@section('content')
    @include(session('theme').'.pc.mypage.include.mypage_hd')

<div class="mypage_wrap">

    <div class="mypage_inner">

        @if($status == 2)

        <h2 class="title mb-5">{{ __('myp.mypage_sentence2') }}</h2>

        <div class="otp_setting_group">

            <div class="left_con">

                <span class="qr_box"><img src="{{$qr_image}}" /></span>
                <span class="qr_adrs mt-3">{{$google2fa_secret}}</span>

            </div>

            <div class="right_con">
                
                <p class="tit">{{ __('myp.enrollmnet_OTP') }}</p>
                <span class="s_txt mb-2">{{ __('myp.mypage_sentence3')}}</span>

                <div class="form-group otp_num_group mb-4">
                    <form method="post" action="{{route('mypage.otp_setting_register')}}">

                    @csrf
                    
                    <input type="text" placeholder="{{ __('myp.input_OTP') }}" class="form-control mr-2" name="secret" required/>
                    <button type="submit" class="btn_style">{{ __('myp.enrollment') }}</button>

                    </form>
                </div>

                <div class="download_btn_group">
                    <span class="s_txt mb-2">{{ __('myp.OTP_download') }}</span>
                    <a class="btn_style download_btn mb-2" target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">{!! __('myp.andrioid_download') !!}</a>
                    <a class="btn_style download_btn" target="_blank" href="https://itunes.apple.com/app/google-authenticator/id388497605">{!! __('myp.apple_download') !!}</a>
                </div>

            </div>

        </div>
        
        <div class="otp_setting_guide">
            <img src="{{asset('/storage/image/homepage/OTP_registration_' . strtoupper($country) . '.jpg')}}" class="otp_guide" alt="otp_guide"/>
        </div>
        @elseif($status == 3)
        
            <img src="{{ asset('/storage/image/homepage/icon/icon_otp.svg')}}" alt="complit_icon" class="complit_icon mb-4"/>
                
            <h2 class="big_tit notosans mb-4">{{ __('otp.complete')}}</h2>
            
            <p class="ment small_ment boxing mb-4">
                재등록 및 해지를 원하신다면 <br><b>고객센터 > 자주 묻는 질문 > OTP 재등록 및 해지</b>에서 내용을 확인해주세요.
            </p>
            
        
        @endif

    </div>

</div>
@endsection