@extends(session('theme').'.pc.layouts.app')

@section('content')

<div class="auth_wrap register_auth_wrap">

	<div class="auth_panel">

		<h1 class="card_title mb-5 pb-3">
			{{__('boan.existing_member_security_authentication')}}
		</h1>
		
		<p class="ment mb-3">
			{!!__('boan.security_sentence1')!!}
		</p>
		
		<p class="ment small_ment boxing mb-4">
			{!!__('boan.security_sentence2')!!}
		</p>

		<div class="auth_form_group">

			<p class="group_name mb-3">
				{!!__('boan.phone_certification')!!}
			</p>

			<div class="form-group mb-2">
				<div class="certifi_form_group">
					<input type="hidden" name="duplicate_confirm" value="0" />
					<select class="form-control country_slt mr-1" name="country">
						<option value="">{{__('boan.country')}}</option>
						<option value="kr">{{__('boan.kr')}}</option>
						<option value="jp">{{__('boan.jp')}}</option>
						<option value="ch">{{__('boan.ch')}}</option>
						<option value="en">{{__('boan.usa')}}</option>
					</select>
					<input id="mobile_number" placeholder="{{ __('ptop.phonenumber') }}" type="text" class="certifi_form_input mr-1 auth_input form-control" name="mobile_number" required autofocus>
					<button type="button" id="existing_user_sms_certify" class="btn certify_btn active">
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
					<button type="button" id="existing_user_sms_certify_confirm" class="btn certify_btn">
					{{ __('boan.certification') }}
					</button>
				</div>
			</div>

		</div>

	</div>

</div>

@endsection
