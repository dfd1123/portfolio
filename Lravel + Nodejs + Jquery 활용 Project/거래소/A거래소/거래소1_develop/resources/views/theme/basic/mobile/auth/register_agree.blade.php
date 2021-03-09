@extends(session('theme').'.mobile.layouts.app')

@section('content')
<div class="m_hd_title">
    <div class="inner">
	{{ __('login.join') }}
    </div>
</div>
<div class="auth_wrap register_auth_wrap register_agree_wrap">

	<div class="auth_panel">

		<h1 class="card_title mb-3 pb-3">{{ __('login.join') }}</h1>

		<p class="ment_agree mb-4 mb_ment">
		{!! __('login.join_login_sentence2_mb') !!}
		</p>

		<div class="auth_form_group">

			<form method="get" action="{{route('register')}}" id="register_agree">

				<div class="agree_con agree_con_view" id="agree_con_1">
					<div class="top_tit">
						<label for="register_agree1">{{ __('login.join_login_sentence3') }}</label>
						<input class="grayCheckbox" type="checkbox" id="register_agree1" name="register_agree1" value="0" />
						<span class="view_more">{{ __('login.see') }} ></span>
					</div>
					<textarea>{{ __('login.one') }}</textarea>
				</div>

				<div class="agree_con agree_con_view" id="agree_con_2">
					<div class="top_tit">
						<label for="register_agree2">{{ __('login.join_login_sentence4') }}</label>
						<input class="grayCheckbox" type="checkbox"  id="register_agree2" name="register_agree2" value="0" />
						<span class="view_more">{{ __('login.see') }} ></span>
					</div>
					<textarea>{{ __('login.two') }}</textarea>
				</div>

				<div class="top_tit last_top_tit">
					<label for="register_agree3">{!! __('login.join_login_sentence5_mb') !!}</label>
					<input class="grayCheckbox" type="checkbox" id="register_agree3" name="register_agree3" value="0" />
					<span class="view_more">{{ __('login.see') }} ></span>
				</div>

				<div class="fixed_btn">
					
					<button type="submit" class="btn_style_next">
						{{ __('login.next_btn') }}
					</button>

				</div>

			</form>

		</div>

	</div>

</div>
 
@include(session('theme').'.mobile.auth.include.agree1_popup')
@include(session('theme').'.mobile.auth.include.agree2_popup')

<script>

	@if(session()->has('jsAlert'))
        
        $.alert({
		    title: "알림",
		    content: "{{ session()->get('jsAlert') }}",
		});

	@endif
		 	
</script>
@endsection
