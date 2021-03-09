@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div class="auth_wrap register_auth_wrap">
    <div class="auth_panel">

        <h1 class="card_title mb-5 pb-3">
        {{ __('login.suc_email') }}
        </h1>

        <img src="{{ asset('/storage/image/homepage/mobile_icon/complete_icon.svg')}}" alt="complt_icon" class="mb-4 complt_icon"/>

        <p class="ment mb-3">
        {{ __('login.join_login_sentence12') }}
        </p>

        <p class="ment small_ment boxing mb-4">
            {!!__('boan.security_sentence2')!!}
        </p>

        <div class="form-group mb-0 mt-4">
            <a class="btn_style vrf_send_btn" href="{{ route('home') }}">
            {{ __('login.main') }}
			</a>
        </div>

    </div>
</div>
@endsection