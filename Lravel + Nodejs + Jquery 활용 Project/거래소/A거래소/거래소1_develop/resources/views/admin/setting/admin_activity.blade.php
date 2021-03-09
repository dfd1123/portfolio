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
					<th style="width: 5%;">UID</th>
					<th style="width: 10%;">활동 시간</th>
					<th style="width: 10%;">활동 내역</th>
					<th style="width: 5%;">관리자 이메일(ID)</th>
					<th style="width: 10%;">관리자 전화번호</th>
					<th style="width: 10%;">관리자 이름</th>
					<th style="width: 10%;">관리자 IP</th>
					<th style="width: 5%;">DEVICE</th>
					<th style="width: 5%;">DEVICE 종류</th>
					<th style="width: 10%;">BROWSER</th>
					<th style="width: 5%;">OS</th>
					<th style="width: 10%;">위치</th>
				</tr>
				</thead>
				<tbody>
				@foreach($admin_activities as $admin_activity)
				<tr>
					<td>{{ $admin_activity->admin_id }}</td>
					<td>{{ $admin_activity->created_at }}</td>
					<td>{{ $admin_activity->activity_log }}</td>
					<td>{{ $admin_activity->admin_fullname }}</td>
					<td>{{ $admin_activity->admin_email }}</td>
					<td>{{ $admin_activity->admin_mobile }}</td>
					<td>{{ $admin_activity->admin_ip }}</td>
					<td>{{ $admin_activity->admin_device }}</td>
					<td>{{ $admin_activity->admin_device_kind }}</td>
					<td>{{ $admin_activity->admin_browser }}</td>
					<td>{{ $admin_activity->admin_os }}</td>
					<td>{{ $admin_activity->admin_location = '' ? '위치 확인 안됨' : $admin_activity->admin_location }}</td>
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
		@if($admin_activities)
			{!! $admin_activities->render() !!}
		@endif
	</div>
</div>
		
@endsection
