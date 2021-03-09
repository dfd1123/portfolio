@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">{{ __('auth2.ex') }}</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<form method="post" action="{{ route('admin.register.form') }}">
		@csrf
		<div class="card-header">
		{{ __('auth2.made') }}
		</div>
		<div class="card-body">
			
		{{ __('auth2.name') }} : <input type="text" name="title"  value="{{ old('title') }}" /><br>
		{{ __('auth2.blr') }} : <input type="text" name="description"  value="{{ old('description') }}" /><br>
		{{ __('auth2.key') }}: <input type="text" name="keywords"  id="keywords" value="{{ old('keywords') }}" /><br>
		{{ __('auth2.ceo') }}l : <input type="email" name="infoemail"  value="{{ old('infoemail') }}" /><br>
		{{ __('auth2.ceo2') }} : <input type="email" name="supportemail"  value="{{ old('supportemail') }}"  /><br>
		{{ __('auth2.adrs') }} : <input type="text" name="url" value="{{ old('url') }}" /><br>
		{{ __('auth2.sv') }} : <input type="text" name="url_support" value="{{ old('url') }}" /><br>
			
			<h5>{{ __('auth2.ac') }}</h5>
			
			{{ __('auth2.bank') }} : <input type="text" name="bankname" value="{{ old('bankname') }}"  /><br>
			{{ __('auth2.no') }} : <input type="text" name="bankaccount" value="{{ old('bankaccount') }}"  /><br>
			{{ __('auth2.you') }} : <input type="text" name="bankowner" value="{{ old('bankowner') }}"  /><br>
		</div>
		<div class="card-header">
		{{ __('auth2.info') }}
		</div>
		<div class="card-body">
			
		{{ __('auth2.mail') }} : <input type="email" name="email"  value="{{ old('email') }}" /><br>
		{{ __('auth2.pw') }} : <input type="password" name="password" /><br>
		{{ __('auth2.pw2') }} : <input type="password" name="password_confirmation"  id="password-confirm" /><br>
		{{ __('auth2.name2') }} : <input type="text" name="fullname"  value="{{ old('email') }}" /><br>
		{{ __('auth2.con') }} : <input type="text" name="mobile_number"  value="{{ old('email') }}"  /><br>
			
			
			<button type="submit">{{ __('auth2.join2') }}</button>

		</div>
	</form>

@endsection

@section('script')



@endsection