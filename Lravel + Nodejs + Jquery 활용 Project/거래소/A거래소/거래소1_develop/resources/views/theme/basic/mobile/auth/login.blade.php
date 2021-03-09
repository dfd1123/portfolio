@extends(session('theme').'.mobile.layouts.app')

@section('content')
<div class="m_hd_title">
    <div class="inner">
        <h1 class="logo">
            <a href="{{ url('/?country='.config('app.country')) }}">
                <img src="{{ asset('/storage/image/homepage/spowide_header_logo.png')}}" style="    width: 106px;" alt="logo"/>
            </a>
        </h1>
    </div>
</div>

<div class="auth_wrap">

	<div class="auth_panel">

		<h1 class="card_title pb-3 mb-5">{{ __('login.login') }} </h1>

		<div class="auth_form_group">

			<form method="POST" action="{{ route('login') }}">
				@csrf
				<input type="hidden" name="push_token" id="push_token" value="">
				<div class="form-group login_email_space">

					<div class="auth_input_line">

						<input id="email" type="email" class="auth_input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('login.email_address') }}" name="email" value="{{ old('email') }}" required autofocus>

					</div>

				</div>

				<div class="form-group">

					<div class="auth_input_line">

						<input id="password" type="password" class="auth_input form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('login.password') }}" name="password" required>

					</div>

				</div>

				<div class="form-group">

					<button type="submit" class="btn_style btn_login_space point_btn_clr">
					{{ __('login.login') }}
					</button>
					
				</div>

				@if (Route::has('password.request'))
				<div class="text_align btn_group">
					<a class="btn btn-link" href="{{ route('password.request') }}">{{ __('login.find') }} </a>
					<a class="btn btn-link point_clr_2 font-weight-bold" href="{{ route('register_agree') }}"> {{ __('login.join') }} </a>
				</div>
				@endif

			</form>
		</div>

	</div>

</div>

<script>
	$(document).ready(function(){
		var login_os = getMobileOperatingSystem();
		if(login_os == "Android"){
			if(typeof window.myJS !== 'undefined'){
				window.myJS.CallPushToken();
			}
		}else if(login_os == "iOS"){
			if(typeof webkit !== 'undefined'){
				webkit.messageHandlers.CallPushToken.postMessage("push_token"); 
			}
		}else{
			
		}

		
		
		@if ($errors->has('email'))

			swal({
				title: '{{ __('message.login_fail') }}',
				text: '{{ __('message.login_fail_reson') }}',
				icon: "warning",
				button: '{{ __('message.ok') }}',
			});

		@endif
	});

	function CallBackToken(token){
		$("#push_token").val(token);
		console.log(token);
	}
</script>

@endsection
