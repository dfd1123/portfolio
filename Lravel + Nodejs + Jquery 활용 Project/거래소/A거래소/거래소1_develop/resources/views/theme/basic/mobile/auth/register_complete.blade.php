@extends(session('theme').'.mobile.layouts.app')

@section('content')
<div class="m_hd_title">
    <div class="inner">
	{{ __('login.join') }}
    </div>
</div>

<div class="auth_wrap register_auth_wrap register_complt_wrap">

	<div class="auth_panel">

		<h1 class="card_title mb-5 pb-3">{{ __('login.join') }}</h1>

		<div class="in_register_wrapper">

            <img src="{{ asset('/storage/image/homepage/mobile_icon/complete_icon.svg')}}" class="mb_block complt_icon" alt="complete_icon"/>

			<p class="ment">
			{!! __('login.join_login_sentence8') !!}
			</p>

			<p class="ment small_ment mt-4 mb-5">
			{!! __('login.join_login_sentence9') !!}
			</p>

			<div class="fixed_btn">

				<button class="btn_style" onclick="window.location.href='/';">
				{{ __('login.login') }}
				</button>

			</div>

		</div>

	</div>

</div>
@endsection
