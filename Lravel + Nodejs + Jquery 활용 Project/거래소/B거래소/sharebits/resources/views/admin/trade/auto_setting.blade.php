@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
		자동 거래 셋팅
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	자동 거래 리스트
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;">순서</th>
						<th style="width:10%;">코인이름</th>
						<th style="width:10%;">1분간 딜레이 시간 간격</th>
						<th style="width:10%;">매물 양 조절</th>
						<th style="width:10%;">매물 양 소수점 조절</th>
						<th style="width:10%;">실제 매물던지는 양 (거래할 코인 * 거래할 양)</th>
						<th style="width:10%;">거래할 시세 범위</th>
						<th style="width:10%;">작동</th>
					</tr>
				</thead>
				<tbody>
					@forelse($auto_settings as $auto_setting)
					<tr>
						<td>{{ $auto_setting->id }}</td>
						<td>{{__('coin_name.'.$auto_setting->cointype)}}({{ $auto_setting->cointype }})</td>
						<td>{{ $auto_setting->time_min }}초 ~ {{$auto_setting->time_max}}초</td>
						<td>{{ $auto_setting->amt_min }} {{ strtoupper($auto_setting->cointype) }} ~ {{$auto_setting->amt_max}} {{ strtoupper($auto_setting->cointype) }}</td>
						<td>{{ $auto_setting->amt_decimal }}</td>
						<td>{{ $auto_setting->amt_min * $auto_setting->amt_decimal }} {{ strtoupper($auto_setting->cointype) }} ~ {{ $auto_setting->amt_max * $auto_setting->amt_decimal }} {{ strtoupper($auto_setting->cointype) }}</td>
						<td>{{ $auto_setting->range_min }}% ~ {{ $auto_setting->range_max }}%</td>
						<td>
							<a href="{{route('admin.auto_bot_edit', $auto_setting->id)}}" class="myButton edit">편집</a>
							@if($auto_setting->switch == 1)
							<a href="{{route('admin.auto_setting_update', ['id' => $auto_setting->id, 'switch' => '0'])}}" class="myButton del" onclick="return confirm(' 해당코인의 자동 거래를 중지하시겠습니까? ');">{{ __('coin.nouse2')}}</a>
							@else
							<a href="{{route('admin.auto_setting_update', ['id' => $auto_setting->id, 'switch' => '1'])}}" class="myButton navy" onclick="return confirm('해당코인의 자동 거래를 시작하시겠습니까?');">{{ __('coin.use2')}}</a>
							@endif
						</td>
					</tr>
					@empty
					<tr>
						<th colspan="7">{{ __('coin.coinnohere')}}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>

@endsection

@section('script')

@endsection