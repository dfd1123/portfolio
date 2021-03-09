@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">{{ __('auth2.join')}}</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('auth2.joinus')}}
	</div>
	<div class="card-body">
		<form method="get" action="{{route('admin.register')}}" id="register_agree">
			<textarea> 개인약관어쩌구... </textarea><br>
			<input type="checkbox" name="register_agree1" value="0" />{{ __('auth2.agree')}}<br><br>
			<textarea> 개인정보 어쩌구... </textarea><br>
			<input type="checkbox" name="register_agree2" value="0"  />{{ __('auth2.agree')}}<br><br>
			<textarea> 마케팅수신 어쩌구... </textarea><br>
			<input type="checkbox" name="register_agree3" value="0"  />{{ __('auth2.agree')}}<br><br>
			
			<button type="submit">{{ __('auth2.next')}}</button>
			
		</form>
	</div>
	<div class="card-footer small text-muted">{{ __('auth2.update')}}</div>
</div>

@endsection

@section('script')



@endsection