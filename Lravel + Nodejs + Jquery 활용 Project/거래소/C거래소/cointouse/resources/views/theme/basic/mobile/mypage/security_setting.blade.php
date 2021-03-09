@extends(session('theme').'.mobile.layouts.app') 
@section('content')
    @include(session('theme').'.mobile.mypage.include.mypage_hd')

<div class="m_mypage_wrap scrl_wrap m_mypage_wrap-lvup">
	
    {{-- 이메일 인증~휴대폰 인증 그룹 --}}
    <div class="lv_certifi_before">
        @if($status == 0)
            <p class="big_tit mt-4">{{ __('boan.email_submit_boan')}}</p>
            <h2 class="title">{{ __('boan.nextphone')}}</h2>
        @elseif($status == 1)
            <p class="big_tit mt-4">{{ __('boan.phone_submit_boan')}}</p>
            <h2 class="title">{!! __('boan.email_ok_next_phone') !!}</h2>
        @elseif($status == 2)
            <p class="big_tit mt-4">{{ __('boan.document_submit_boan')}}</p>
            <h2 class="title">{!! __('boan.phone_ok_next_document') !!}</h2>
        @elseif($status == 3)
            <p class="big_tit mt-4">{{ __('boan.account_submit_boan')}}</p>
            <h2 class="title">{!! __('boan.document_ok_next_account') !!}</h2>
        @elseif($status == 3.5)
            <p class="big_tit mt-4">{{ __('boan.complete_submit_boan')}}</p>
            <h2 class="title">{!! __('boan.account_ok_next_otp') !!}</h2>
        @endif
    
        <div class="security_set_group">
        
            <!--디자인 그룹-->
            <div class="boan_level_wrap mb-4">
                <div class="boan_level_con mr-4 {{($status == 0)?'active':''}}">
                    <span>{{ __('boan.email_submit')}}</span>
                </div>
                <div class="boan_level_con {{($status == 1)?'active':''}}">
                    <span>{{ __('boan.phone_submit')}}</span>
                </div>
            </div>
            <div class="boan_level_wrap mb-4">
            	 <div class="boan_level_con mr-4 second {{($status == 2)?'active':''}}">
                    <span>{{ __('boan.document_submit')}}</span>
                </div>
                <div class="boan_level_con second {{($status == 2.5)?'active':''}}">
                    <span>{{ __('boan.account_submit')}}</span>
                </div>
            </div>
            <!--//디자인 그룹-->
            
            @if($status == 0)

                <div class="lv1_certifi_form_wrap">
                    @if (session('resent'))
                        
                        <p class="ment s_ment">
                        {{ __('login.no_cer') }} 
                            
                        </p>
                        
                        <div class="form-group mb-0 mt-4">
                            <a class="resend_btn" href="{{ route('verification.resend') }}">
                            {{ __('login.re') }}
                            </a>
                        </div>

                        <div class="form-group mb-0 mt-4">
                            <a class="btn_style" href="{{route('mypage.security_setting')}}">{{ __('message.complete_certify')}}</a>
                        </div>

                    @else
                        
                        <div class="form-group mb-0 mt-4">
                            <a id="verification_resend" class="btn_style vrf_send_btn" href="{{ route('verification.resend') }}">
                            {{ __('login.send_certification') }}
                            </a>
                        </div>
                        
                    @endif
                </div>
            @elseif($status == 1)
        
                <!--휴대폰 인증 폼 그룹-->
                <div class="lv2_certifi_form_wrap ">
                    <form name="form_chk" method="post">
						<input type="hidden" name="m" value="checkplusSerivce">	
						<input type="hidden" name="EncodeData" value="{{ $enc_data }}">
					</form>
					
                    <div class="form-group mb-2">
                        <div class="certifi_form_group">
                            <select class="form-control country_slt mr-1" name="country" id="security_country">
                                <option value="">{{__('boan.country')}}</option>
                                <option value="kr">{{__('boan.kr')}}</option>
                                <option value="jp">{{__('boan.jp')}}</option>
                                <option value="ch">{{__('boan.ch')}}</option>
                                <option value="en">{{__('boan.usa')}}</option>
                            </select>
                            <input id="mobile_number" placeholder="{{ __('ptop.phonenumber') }}" type="text" class="certifi_form_input mr-1 auth_input form-control" name="mobile_number" readonly required autofocus>
                            <button type="button" id="sms_certify" class="btn certify_btn active">
                                {{__('boan.send_massege')}}
                            </button>
                        </div>
                    </div>
            
                    <div class="form-group mb-2">
                        <div class="certifi_form_group">
                            <div class="mobile_certify_code_wrap">
                                <label class="certify_label">{!!__('boan.input_number')!!}</label>
                                <input type="text" id="mobile_number_code" class="pl-120px certifi_form_input auth_input form-control" name="mobile_certify_code" value="">
                                <span id="ViewTimer">03:00</span>
                            </div>
                            <button type="button" id="sms_certify_confirm" class="btn certify_btn">
                            {{ __('boan.certification') }}
                            </button>
                        </div>
                    </div>
                        
                </div>
                <!--//휴대폰 인증 폼 그룹-->
            @elseif($status == 2)
            	<!--신분증 인증 폼 그룹-->
                <div class="lv2_certifi_form_wrap ">
                    <span style="color:red;">{{__('boan.reject_reason')}} : {{ $security->document_reject }}</span>
                    <div class="form-group mb-2">
                        <div class="certifi_form_group">
                            <input type="text" class="mr-1 auth_input form-control" value="{{ Auth::user()->fullname }}" readonly>
                        </div>
                    </div>
                    
                    <div class="form-group mb-2">
                        <div class="certifi_form_group">
                            <input type="text" class="mr-1 auth_input form-control" value="{{ Auth::user()->mobile_number }}" readonly>
                        </div>
                    </div>
                    <form method="post" enctype="multipart/form-data" action="{{route('mypage.security_setting_document')}}" id="security_setting_document" >
                    	@csrf
                        <div class="form-group mb-2">
                            <div class="certifi_form_group">
                                <div class="mobile_certify_code_wrap">
                                	<input type="text" placeholder="{{__('boan.placeholder_document1')}}" class="certifi_form_input auth_input form-control filename_input" readonly>
                                	<input type="file" name="file1" id="thum_file" class="hide img_up">
                                </div>
                                <label class="btn certify_btn" for="thum_file">{{ __('support.add_image') }}</label>
                            </div>
                        </div>
                        
                        <div class="form-group mb-2">
                            <div class="certifi_form_group">
                                <div class="mobile_certify_code_wrap">
                                	<input type="text" placeholder="{{__('boan.placeholder_document2')}}" class="certifi_form_input auth_input form-control filename_input" readonly>
                                	<input type="file" name="file2" id="thum_file2" class="hide img_up">
                                </div>
                                <label class="btn certify_btn" for="thum_file2">{{ __('support.add_image') }}</label>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <div class="certifi_form_group">
                                <button type="submit" id="document_certify_confirm" class="btn certify_btn">
		                            {{ __('boan.certification') }}
		                        </button>
                            </div>
                        </div>
                    </form>
                        
                </div>
                <!--//신분증 인증 폼 그룹-->
            @elseif($status == 2.5)
            	<!--계좌인증-->
            	<div class="lv2_certifi_form_wrap">
            		<span style="color:red;">{{__('boan.reject_reason')}} : {{ $security->account_reject }}</span>
                    <form method="post" enctype="multipart/form-data" action="{{route('mypage.security_setting_account')}}" id="security_setting_account" >
                    @csrf
                        <div class="form-group mb-2">
                            <div class="certifi_form_group">
                                <input type="text" class="mr-1 auth_input form-control" value="{{ Auth::user()->fullname }}" readonly>
                            </div>
                            
                        </div>
                        
                        <div class="form-group mb-2">
                            <div class="certifi_form_group">
                                <input type="text" name="account_num" id="account_num" class="mr-1 auth_input form-control" placeholder="{{__('boan.placeholder_account1')}}">
                            </div>
                        </div>
                        
                        <div class="form-group mb-2">
                            <div class="certifi_form_group">
                                <input type="text" name="account_bank" id="account_bank" class="mr-1 auth_input form-control" placeholder="{{__('boan.placeholder_account2')}}">
                            </div>
                        </div>
                    
                        <div class="form-group mb-2">
                            <div class="certifi_form_group">
                                <div class="mobile_certify_code_wrap">
                                	<input type="text" placeholder="{{__('boan.placeholder_account3')}}" class="certifi_form_input auth_input form-control filename_input" readonly>
                                	<input type="file" name="file1" id="thum_file" class="hide img_up">
                                </div>
                                <label class="btn certify_btn" for="thum_file">{{ __('support.add_image') }}</label>
                            </div>
                        </div>
                        
                        <div class="form-group mb-2">
                            <div class="certifi_form_group">
                                <div class="mobile_certify_code_wrap">
                                	<input type="text" placeholder="{{__('boan.placeholder_account4')}}" class="certifi_form_input auth_input form-control filename_input" readonly>
                                	<input type="file" name="file2" id="thum_file2" class="hide img_up">
                                </div>
                                <label class="btn certify_btn" for="thum_file2">{{ __('support.add_image') }}</label>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <div class="certifi_form_group">
                                <button type="submit" id="document_certify_confirm" class="btn certify_btn">
		                            {{ __('boan.certification') }}
		                        </button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            	<!--//계좌인증-->
            @elseif($status == 3.5)
            	<!--계좌 및 신분증 제출 확인중-->
            	<div class="lv2_certifi_form_wrap">
            		{{ __('boan.check_ready_document') }}
            	</div>
            	<!--//계좌 및 신분증 제출 확인중-->
            @endif
        
            <!--하단의 안내사항-->
            <ul class="info_list mt-3 pt-3">
                
                {{-- 이메일 인증 완료시, 첫번째 li 숨기고 두번째 li만 나타나게 해주세요 --}}
                <li class="mb-2">{{ __('boan.email_phone_submit')}}</li>
                <li>{{ __('boan.otp_submit')}}</li>
                
            </ul>
            <!--//하단의 안내사항-->
        
        </div>
    
    </div>
    {{-- //이메일 인증~휴대폰 인증 그룹 --}}
    
    {{-- 완료시에 보이는 화면 --}}
    <div class="security_set_group text-center hide">
        
        <img src="{{ asset('/storage/image/homepage/mobile_icon/complete_icon.svg')}}" alt="complit_icon" class="complit_icon mb-4 mt-5"/>
        
        <p class="big_tit mb-1">{{ __('boan.boan_submit')}}</p>
    
        <span class="mb-5 mypage_msg">{{ __('boan.all_submit')}}</span>
        
    </div>
    {{-- //완료시에 보이는 화면 --}}
        
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
<script>
    if (typeof __ === 'undefined') { var __ = {}; }
    __.boan = {
        @foreach(__('boan') as $key => $value)
            '{{$key}}':'{{$value}}',
        @endforeach
    }
</script>
    
@endsection