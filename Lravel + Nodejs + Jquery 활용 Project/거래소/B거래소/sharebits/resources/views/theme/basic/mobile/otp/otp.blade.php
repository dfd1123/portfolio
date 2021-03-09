@extends(session('theme').'.mobile.layouts.app')

@section('content')
<div class="m_hd_title">
    <div class="inner">
        <h1 class="logo">
            <a href="{{ url('/?country='.config('app.country')) }}">
                <img src="{{ asset('/storage/image/homepage/sharebits-logo-dark.svg')}}" alt="logo"/>
            </a>
        </h1>
    </div>
</div>

<div class="auth_wrap">

	<div class="auth_panel">

		<h1 class="card_title pb-3 mb-5">{{ __('otp.otp')}}</h1>

		<div class="auth_form_group">

			<form method="POST" action="{{ route('login') }}">

				<div class="form-group">

					<div class="auth_input_line">
						<input type="text" class="auth_input form-control" placeholder="{{ __('otp.otpn2')}}" required autofocus>
					</div>

				</div>

				<p class="ment">
				{{ __('otp.otpn')}}
				</p>

				<div class="form-group mt-4">

					<button type="submit" class="btn_style">
					{{ __('otp.otpn2')}}
					</button>

				</div>

			</form>

		</div>

	</div>

</div>

 
 
@endsection
