@extends(session('theme').'.pc.layouts.app')

@section('content')
<div class="email_verified_wrap">
	<div class="email_verified_con">
		<div class="email_verified_box">
			<h1 class="card_title mb-5 pb-3">
			{{ __('login.email_cer') }}
			</h1>
			
			<img src="{{ asset('/storage/image/homepage/icon/icon_mailcheck.svg')}}" alt="icon_mailcheck" class="mb-4 icon_mailcheck"/>
			
			@if (session('resent'))
			
				<p class="ment mb-3">
				{!! __('login.join_login_sentence13') !!}				
				</p>
				
				<p class="ment s_ment">
				{{ __('login.no_cer') }} 
					
				</p>
				
				<div class="form-group mb-0 mt-4">
					<a class="btn_style vrf_send_btn not_active" href="{{ route('verification.resend') }}">
					{{ __('login.re') }}
					</a>
				</div>
				
			@else
			
				<p class="ment mb-3">
				{!! __('login.join_login_sentence14') !!}				
				</p>
				
				<div class="form-group mb-0 mt-4">
					<a id="verification_resend" class="btn_style vrf_send_btn" href="{{ route('verification.resend') }}">
					{{ __('login.send_certification') }}
					</a>
				</div>
				
			@endif
		</div>
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
@endif
@endsection