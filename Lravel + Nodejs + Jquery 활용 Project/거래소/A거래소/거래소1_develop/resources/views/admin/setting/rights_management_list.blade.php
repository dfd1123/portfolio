@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">{{ __('setting.ad_2') }}</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('setting.ad_list') }}
	</div>
	<div class="card-body">
		<div class="usr_search_box tsa-sch-box">
			<form method="get" action="">
					<select name="keyword_srch">
						<option value="name">{{ __('setting.name') }}</option>
						<option value="email">{{ __('setting.email') }}</option>
						<option value="mobile">{{ __('setting.four') }}</option>
					</select>
					<input type="text" name="keyword" />
					<button type="submit">{{ __('setting.search') }}</button>
			</form>
		</div>
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
				<thead>
				<tr>
					<th style="width: 5%;">ID</th>
					<th style="width: 10%;">{{ __('setting.name') }}</th>
					<th style="width: 20%;">{{ __('setting.email_id') }}</th>
					<th style="width: 20%;">{{ __('setting.phone_number') }}</th>
					<th style="width: 10%;">{{ __('setting.lv') }}</th>
					<th style="width: 40%;">{{ __('setting.setting') }}</th>
				</tr>
				</thead>
				<tbody>
				@foreach($admins as $admin)
				<tr>
					<td>{{ $admin->id }}</td>
					<td>{{ $admin->fullname }}</td>
					<td>{{ $admin->email }}</td>
					<td>{{ $admin->mobile_number }}</td>
					<td>{{ $admin->level }}</td>
					
					<td>
					@if(Auth::guard('admin')->user()->level <= 2)	
						@if($admin->id != Auth::guard('admin')->user()->id)
							<a href="{{route('admin.rights_management_edit', $admin->id)}}" class="myButton edit">{{ __('setting.edit') }}</a>&nbsp
							<a href="{{route('admin.rights_management_password_edit', $admin->id)}}" class="myButton navy">{{ __('setting.change_pass') }}</a>&nbsp
							<a href="{{route('admin.rights_management_delete', $admin->id)}}" class="link-delete myButton del" onclick="return confirm('{{ __('setting.setting_sentence_8') }}');">{{ __('setting.del') }}</a>
						@else
							<a href="{{route('admin.rights_management_edit', $admin->id)}}" class="myButton edit">{{ __('setting.edit') }}</a>&nbsp
							<a href="{{route('admin.rights_management_password_edit', $admin->id)}}" class="myButton navy">{{ __('setting.change_pass') }}</a>&nbsp
						@endif
					@else
						@if($admin->id == Auth::guard('admin')->user()->id)
							{{-- <a href="{{route('admin.rights_management_edit', $admin->id)}}" class="myButton edit">{{ __('setting.edit') }}</a>&nbsp --}}
							<a href="{{route('admin.rights_management_password_edit', $admin->id)}}" class="myButton navy">{{ __('setting.change_pass') }}</a>&nbsp
						@endif
					@endif
					</td>
					
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
		@if($admins)
			{!! $admins->render() !!}
		@endif
		@if(Auth::guard('admin')->user()->level <= 2)	
		<div>
			<button type="button" class="mint_btn" onclick="location.href='{{route('admin.rights_management_create')}}'">{{ __('setting.add') }}</button>
		</div>
		@endif
	</div>
</div>
		
@endsection
