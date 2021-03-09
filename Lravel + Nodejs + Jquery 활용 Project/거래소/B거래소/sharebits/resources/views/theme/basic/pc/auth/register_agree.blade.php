@extends(session('theme').'.pc.layouts.app')

@section('content')

<div class="auth_wrap register_auth_wrap">

	<div class="auth_panel">

		<h1 class="card_title mb-3 pb-3">JOIN US</h1>

		<p class="ment mb-4">
		{{ __('login.join_login_sentence2') }}
		</p>

		<div class="auth_form_group">

			<form method="get" action="{{route('register')}}" id="register_agree">

				<div class="agree_con">
					<div class="top_tit">
						<label for="register_agree1">{{ __('login.join_login_sentence3') }}</label>
						<input class="grayCheckbox" type="checkbox" id="register_agree1" name="register_agree1" value="0" />
					</div>
					<div class="term_infor_div">
						<div>
						{!! $term->{'use_term_'.config('app.country')} !!}
						</div>
					</div>
				</div>

				<div class="agree_con">
					<div class="top_tit">
						<label for="register_agree2">{{ __('login.join_login_sentence4') }}</label>
						<input class="grayCheckbox" type="checkbox"  id="register_agree2" name="register_agree2" value="0" />
					</div>
					<div class="term_infor_div">
						<div>
						{!! $term->{'private_infor_term_'.config('app.country')} !!}
						</div>
					</div>
				</div>

				<div class="top_tit last_top_tit">
					<label for="register_agree3">{{ __('login.join_login_sentence5') }}</label>
					<input class="grayCheckbox" type="checkbox" id="register_agree3" name="register_agree3" value="0" />
				</div>

				<div class="form-group mt-3 mb-0">

					<button type="submit" class="btn_style">
						NEXT
					</button>

				</div>

			</form>

		</div>

	</div>

</div>

@endsection
