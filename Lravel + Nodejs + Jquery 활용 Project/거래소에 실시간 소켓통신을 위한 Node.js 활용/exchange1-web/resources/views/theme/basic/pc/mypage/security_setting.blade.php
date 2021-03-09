@extends(session('theme').'.pc.layouts.app') 
@section('content')
    @include(session('theme').'.pc.mypage.include.mypage_hd')


<div class="mypage_wrap">
	
    <div class="mypage_inner">
        
        {{-- 이메일 인증~휴대폰 인증 그룹 --}}
        
        <div class="lv_certifi_before">
            @if($status == 0)
            <p class="big_tit notosans mb-3">{{ __('boan.email_submit_boan')}}</p>
            <h2 class="title mb-5">{{ __('boan.nextphone')}}</h2>
            @elseif($status == 1)
            <p class="big_tit notosans mb-3">{{ __('boan.phone_submit_boan')}}</p>
            <h2 class="title mb-5">{!! __('boan.email_ok_next_phone') !!}</h2>
            @endif
        
            <div class="security_set_group">
            
                <!--디자인 그룹-->
                <div class="boan_level_wrap mb-5">
                    <div class="boan_level_con mr-4 {{($status == 0)?'active':''}}">
                        <span>{{ __('boan.email_submit')}}</span>
                    </div>
                    <div class="boan_level_con {{($status == 1)?'active':''}}">
                        <span>{{ __('boan.phone_submit')}}</span>
                    </div>
                </div>
                <!--//디자인 그룹-->
            
                @if($status == 0)
                    @if (session('resent'))
                        
                        <p class="ment s_ment" style="text-align: center;">
                        {{ __('login.no_cer') }} 
                            
                        </p>
                        
                        <div class="form-group mb-0 mt-4">
                            <a class="resend_btn" href="{{ route('verification.resend') }}">
                            {{ __('login.re') }}
                            </a>
                        </div>
                        <div class="form-group mb-0 mt-4" style="text-align: center;">
                            <a class="btn_style" href="{{route('mypage.security_setting')}}">{{ __('message.complete_certify')}}</a>
                        </div>
                    @else
                    
                        <p class="ment mb-3" style="text-align: center;">
                        {!! __('login.join_login_sentence14') !!}				
                        </p>
                        
                        <div class="form-group mb-0 mt-4" style="text-align: center;">
                            <a id="verification_resend" class="btn_style vrf_send_btn" href="{{ route('verification.resend') }}">
                            {{ __('login.send_certification') }}
                            </a>
                        </div>

                        
                        
                    @endif
                @elseif($status == 1)
                    <!--휴대폰 인증 폼 그룹-->
                    <div class="lv2_certifi_form_wrap">
                        
                        <div class="form-group mb-2">
                            <div class="certifi_form_group">
                                <select class="form-control country_slt mr-1" name="country">
                                    <option value="">{{__('boan.country')}}</option>
                                    <option value="kr">{{__('boan.kr')}}</option>
                                    <option value="jp">{{__('boan.jp')}}</option>
                                    <option value="ch">{{__('boan.ch')}}</option>
                                    <option value="en">{{__('boan.usa')}}</option>
                                </select>
                                <input id="mobile_number" placeholder="{{ __('ptop.phonenumber') }}" type="text" class="certifi_form_input mr-1 auth_input form-control" name="mobile_number" required autofocus>
                                <button type="button" id="sms_certify" class="btn certify_btn active">
                                    {{__('boan.send_massege')}}
                                </button>
                            </div>
                        </div>
                
                        <div class="form-group mb-2">
                            <div class="certifi_form_group">
                                <div class="mobile_certify_code_wrap">
                                    <label class="certify_label">{!!__('boan.input_number')!!}</label>
                                    <input id="mobile_number_code" type="text" class="pl-120px certifi_form_input auth_input form-control" name="mobile_certify_code" value="">
                                    <span id="ViewTimer">03:00</span>
                                </div>
                                <button type="button" id="sms_certify_confirm" class="btn certify_btn">
                                {{ __('boan.certification') }}
                                </button>
                            </div>
                        </div>
                            
                    </div>
                    <!--//휴대폰 인증 폼 그룹-->
                @endif
            
                <!--하단의 안내사항-->
                <ul class="info_list mt-3 pt-3">
                    
                    {{-- 이메일 인증 완료시, 첫번째 li 숨기고 두번째 li만 나타나게 해주세요 --}}
                    <li>{{ __('boan.email_phone_submit')}}</li>
                    <li>{{ __('boan.otp_submit')}}</li>
                </ul>
                <!--//하단의 안내사항-->
            
            </div>
        
        </div>
        {{-- //이메일 인증~휴대폰 인증 그룹 --}}
        
        {{-- 완료시에 보이는 화면 --}}
        <div class="security_set_group text-center hide">
            
            <img src="{{ asset('/storage/image/homepage/mobile_icon/complete_icon.svg')}}" alt="complit_icon" class="complit_icon mb-3"/>
            
            <p class="big_tit notosans mb-3">{{ __('boan.boan_submit')}}</p>
        
            <span class="mb-5">{{ __('boan.all_submit')}}</span>
            
        </div>
        {{-- //완료시에 보이는 화면 --}}
        
    </div>
        
</div>


@if (!session('resent'))
	<div class="overlay" style="display:none;"></div>
	<div class="sending_progress_wrap" style="display:none;">
		<div class="box">
			<div class="border one"></div>
			<div class="border two"></div>
			<div class="border three"></div>
			<div class="border four"></div>

			<div class="line one"></div>
			<div class="line two"></div>
			<div class="line three"></div>
		</div>
	</div>

	<script>

		$(document).ready(function(){
			@if (session('status'))
				swal({
					title: '{{ __('message.mail_send') }}',
					text: '{{ __('message.confirm_your_email') }}',
					icon: "success",
					button: '{{ __('message.ok') }}',
				});
			@endif
		})

		$('#verification_resend').click(function(){
			$('.overlay').show();
			$('.sending_progress_wrap').show();

			return true;
		})
    </script>
    
@else
    <script>

        $(document).ready(function(){
            @if (session('status'))
                swal({
                    title: '{{ __('message.send_complete')}}',
                    text: '{{ __('message.join_certification')}}',
                    icon: "success",
                    button: '{{ __('message.ok') }}',
                });
            @endif
        })
    </script>
@endif
    
    

@endsection