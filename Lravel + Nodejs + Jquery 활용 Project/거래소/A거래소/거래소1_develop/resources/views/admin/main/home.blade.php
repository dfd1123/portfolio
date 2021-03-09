@extends('admin.layouts.app')

@section('content')

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<div class="card-body">
		<div class="row">
			<div class="col-xl-4 col-sm-6 mb-3">
				<div class="card text-white bg-navy o-hidden h-100">
					<div class="card-body">
						<div class="card-body-icon tsa-body-icon">
							<i class="fas fa-fw fa-users"></i>
						</div>
						<div class="main_news">
						{{ __('main.now') }}<span>{{$active_user_count}}</span>
						</div>
					</div>
					<a class="card-footer text-white clearfix small z-1" href="{{route('admin.user_list_now')}}"> <span class="float-left">{{ __('main.see') }}</span> <span class="float-right"> <i class="fas fa-angle-right"></i> </span> </a>
				</div>
			</div>
			<div class="col-xl-4 col-sm-6 mb-3">
				<div class="card text-white bg-navy o-hidden h-100">
					<div class="card-body">
						<div class="card-body-icon tsa-body-icon">
							<i class="fas fa-fw fa-users"></i>
						</div>
						<div class="main_news">
						{{ __('main.all') }}<span>{{$users_count}}</span>
						</div>
					</div>
					<a class="card-footer text-white clearfix small z-1" href="{{route('admin.user_list')}}"> <span class="float-left">{{ __('main.see') }}</span> <span class="float-right"> <i class="fas fa-angle-right"></i> </span> </a>
				</div>
			</div>
			<div class="col-xl-4 col-sm-6 mb-3">
				<div class="card text-white bg-navy o-hidden h-100">
					<div class="card-body">
						<div class="card-body-icon tsa-body-icon">
							<i class="fas fa-fw fa-users"></i>
						</div>
						<div class="main_news">
						{{ __('main.new') }}<span>{{$new_user_count}}</span>
						</div>
					</div>
					<a class="card-footer text-white clearfix small z-1" href="{{route('admin.user_list_new')}}"> <span class="float-left">{{ __('main.see') }}</span> <span class="float-right"> <i class="fas fa-angle-right"></i> </span> </a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xl-3 col-sm-6 mb-3">
				<div class="card text-white bg-primary o-hidden h-100">
					<div class="card-body">
						<div class="card-body-icon tsa-body-icon">
							<i class="fas fa-fw fa-users"></i>
						</div>
						<div class="main_news">
						{{ __('main.kr') }}<span>{{$qna_counts_kr}}</span>
						</div>
					</div>
					<a class="card-footer text-white clearfix small z-1" href="{{route('admin.qna_list','kr')}}"> <span class="float-left">{{ __('main.see') }}</span> <span class="float-right"> <i class="fas fa-angle-right"></i> </span> </a>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 mb-3">
				<div class="card text-white bg-primary o-hidden h-100">
					<div class="card-body">
						<div class="card-body-icon tsa-body-icon">
							<i class="fas fa-fw fa-users"></i>
						</div>
						<div class="main_news">
						{{ __('main.jp') }}<span>{{$qna_counts_jp}}</span>
						</div>
					</div>
					<a class="card-footer text-white clearfix small z-1" href="{{route('admin.qna_list','jp')}}"> <span class="float-left">{{ __('main.see') }}</span> <span class="float-right"> <i class="fas fa-angle-right"></i> </span> </a>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 mb-3">
				<div class="card text-white bg-primary o-hidden h-100">
					<div class="card-body">
						<div class="card-body-icon tsa-body-icon">
							<i class="fas fa-fw fa-users"></i>
						</div>
						<div class="main_news">
						{{ __('main.ch') }}<span>{{$qna_counts_ch}}</span>
						</div>
					</div>
					<a class="card-footer text-white clearfix small z-1" href="{{route('admin.qna_list','ch')}}"> <span class="float-left">{{ __('main.see') }}</span> <span class="float-right"> <i class="fas fa-angle-right"></i> </span> </a>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 mb-3">
				<div class="card text-white bg-primary o-hidden h-100">
					<div class="card-body">
						<div class="card-body-icon tsa-body-icon">
							<i class="fas fa-fw fa-users"></i>
						</div>
						<div class="main_news">
						{{ __('main.en') }}<span>{{$qna_counts_en}}</span>
						</div>
					</div>
					<a class="card-footer text-white clearfix small z-1" href="{{route('admin.qna_list','en')}}"> <span class="float-left">{{ __('main.see') }}</span> <span class="float-right"> <i class="fas fa-angle-right"></i> </span> </a>
				</div>
			</div>
		</div>
		@if(Auth::guard('admin')->user()->level <= 3)
		<div class="row">
			<div class="col-xl-4 col-sm-6 mb-3">
				<div class="card text-white bg-danger o-hidden h-100">
					<div class="card-body">
						<div class="card-body-icon tsa-body-icon">
							<i class="fas fa-fw fa-users"></i>
						</div>
						<div class="main_news">
						{{ __('main.out') }}<span>{{$send_requests_count}}</span>
						</div>
					</div>
					<a class="card-footer text-white clearfix small z-1" href="{{route('admin.coin_out_history','all')}}"> <span class="float-left">{{ __('main.see') }}</span> <span class="float-right"> <i class="fas fa-angle-right"></i> </span> </a>
				</div>
			</div>
			<div class="col-xl-4 col-sm-6 mb-3">
				<div class="card text-white bg-danger o-hidden h-100">
					<div class="card-body">
						<div class="card-body-icon tsa-body-icon">
							<i class="fas fa-fw fa-users"></i>
						</div>
						<div class="main_news">
						{{ __('main.ico') }}<span>{{$ico_confirm_count}}</span>
						</div>
					</div>
					<a class="card-footer text-white clearfix small z-1" href="{{route('admin.ico_list')}}"> <span class="float-left">{{ __('main.see') }}</span> <span class="float-right"> <i class="fas fa-angle-right"></i> </span> </a>
				</div>
			</div>
			<div class="col-xl-4 col-sm-6 mb-3">
				<div class="card text-white bg-danger o-hidden h-100">
					<div class="card-body">
						<div class="card-body-icon tsa-body-icon">
							<i class="fas fa-fw fa-users"></i>
						</div>
						<div class="main_news">
						{{ __('main.ptp') }}<span>{{$p2p_confirm_count}}</span>
						</div>
					</div>
					<a class="card-footer text-white clearfix small z-1" href="{{route('admin.p2p_list',0)}}"> <span class="float-left">{{ __('main.see') }}</span> <span class="float-right"> <i class="fas fa-angle-right"></i> </span> </a>
				</div>
			</div>
		</div>
		@endif
		@if(Auth::guard('admin')->user()->level <= 2)
		<div class="card mb-3">
			<div class="card-header">
				<i class="fas fa-chart-area"></i>
				{{ __('main.line') }}
				<select id="chart1_select" class="custom-select custum-select-sm form-control form-control-sm">
					@foreach($coins as $coin)
						<option value="{{$coin->api}}">{{$coin->symbol}}</option>
					@endforeach
				</select>
			</div>
			<div class="card-body">
				<canvas id="chart1" style="width: 100%; height: 200px;"></canvas>
			</div>
		</div>

		

		<div class="card mb-3">
			<div class="card-header">
				<i class="fas fa-chart-area"></i>
				{{ __('main.stic') }}
				<select id="chart2_select" class="custom-select custum-select-sm form-control form-control-sm">
					<option value="krw">KRW</option>
				</select>
			</div>
			<div class="card-body">
				<canvas id="chart2" style="width: 100%; height: 200px;"></canvas>
			</div>
		</div>

		<div class="card mb-3">
			<div class="card-header">
				<i class="fas fa-chart-area"></i>
				달별, 코인총합 출금 수수료 수익 일년기간 막대차트 표시
				<select id="chart3_select" class="custom-select custum-select-sm form-control form-control-sm">
					@foreach($coins as $coin)
						<option value="{{$coin->api}}">{{$coin->symbol}}</option>
					@endforeach
				</select>
			</div>
			<div class="card-body">
				<canvas id="chart3" style="width: 100%; height: 200px;"></canvas>
			</div>
		</div>

		@endif

	</div>
	<div class="card-footer small text-muted">{{$datetime}}{{ __('home.up')}}</div>
</div>

@endsection

@section('script')
<script>
	var startDate = moment('{{$start_date}}');
	var endDate = moment('{{$end_date}}');
	var dateRange = [];

	/* 날짜범위 생성 */
	var tempDate = startDate.clone();
	for(var i = 1; i <= 12; i++) {
		dateRange.push(tempDate.format('YYYY-MM').toString());
		tempDate.add(1, 'months');
	}

	/* 코인별 월별 거래량 정리 */
	var monthTradeDataset = {};
	var monthTradeDataObj = {};
	var monthTradeData = [
		@foreach($month_trades as $month_trade)
		{
			@foreach($month_trade as  $key => $value)
			'{{$key}}':'{{$value}}',
			@endforeach
		},
		@endforeach
	];

	monthTradeData.forEach(function(item) {
		var coin = item.cointype.toLowerCase();
		if(!(coin in monthTradeDataObj)) {
			monthTradeDataObj[coin] = [];
		}

		monthTradeDataObj[coin].push(item);
	});

	for(var key in monthTradeDataObj) {
		var monthData = monthTradeDataObj[key];
		var datas = [];

		var currentDate = startDate.clone();
		for(var i = 1; i <= 12; i++) {
			var date = currentDate.format('YYYY-M').toString();
			var foundMonth = monthData.filter(function(x) { return x.date === date; })[0];
			if(!foundMonth) {
				datas.push(0);
			} else {
				datas.push(parseFloat(foundMonth.amt));
			}
			currentDate = currentDate.add(1, 'months');
		}
		monthTradeDataset[key] = datas;
	}

	var chartContainer1 = document.getElementById('chart1');
	var chart1 = new Chart(chartContainer1, {
		type: 'bar',
		data: {
			labels: dateRange,
		},
		options: {
			legend: {
				display: false
			},
			title: {
				display: true,
				text: '{{ __('main.grr') }}'
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			plugins: {
				datalabels: {
					align: 'top',
					offset: 4
				}
			}
		}
	});

	$('#chart1_select').change(function(e){
		var selectedCoin = $(e.currentTarget).val();
		chart1.data.datasets = [{
			label: '{{ __('main.grr') }}',
			fill: false,
			data: monthTradeDataset[selectedCoin],
			datalabels: {
				display: false
			}
		},{
			fill: false,
			data: monthTradeDataset[selectedCoin],
			type: 'line',
		}];
		chart1.options.title.text = selectedCoin.toUpperCase() + ' {{ __('main.grr') }}';
		chart1.update();
	});
	$('#chart1_select').change();

	/* 월별 수수료 수익 정리 */
	var monthTradeRevenueDataset = [];
	var monthTradeRevenueData = [
		@foreach($month_trades_revenues as $month_trades_revenue)
		{
			@foreach($month_trades_revenue as  $key => $value)
			'{{$key}}':'{{$value}}',
			@endforeach
		},
		@endforeach
	];

	var currentDate = startDate.clone();
	for(var i = 1; i <= 12; i++) {
		var date = currentDate.format('YYYY-M').toString();
		var foundMonth = monthTradeRevenueData.filter(function(x) { return x.date === date; })[0];
		if(!foundMonth) {
			monthTradeRevenueDataset.push(0);
		} else {
			monthTradeRevenueDataset.push(parseFloat(foundMonth.total_fee_price));
		}
		currentDate = currentDate.add(1, 'months');
	}

	var chartContainer2 = document.getElementById('chart2');
	var chart2 = new Chart(chartContainer2, {
		type: 'bar',
		data: {
			labels: dateRange,
			datasets: [{
				label: '{{ __('main.ssr') }}',
				fill: false,
				data: monthTradeRevenueDataset,
				datalabels: {
					display: false
				}
			},{
				fill: false,
				data: monthTradeRevenueDataset,
				type: 'line',
			}],
		},
		options:{
			legend: {
				display: false
			},
			title: {
				display: true,
				text: '{{ __('main.ssr') }}'
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			plugins: {
				datalabels: {
					align: 'top',
					offset: 4
				}
			}
		}
	});

	var monthTradeRevenueDataset = {};
	var monthTradeRevenueDataObj = {};
	var monthTradeRevenueRevenueData = [
		@foreach($month_trades_revenues as $month_trades_revenue)
		{
			@foreach($month_trades_revenue as  $key => $value)
			'{{$key}}':'{{$value}}',
			@endforeach
		},
		@endforeach
	];

	monthTradeRevenueRevenueData.forEach(function(item) {
		var coin = item.currency.toLowerCase();
		if(!(coin in monthTradeRevenueDataObj)) {
			monthTradeRevenueDataObj[coin] = [];
		}

		monthTradeRevenueDataObj[coin].push(item);
	});

	for(var key in monthTradeRevenueDataObj) {
		var monthData = monthTradeRevenueDataObj[key];
		var datas = [];

		var currentDate = startDate.clone();
		for(var i = 1; i <= 12; i++) {
			var date = currentDate.format('YYYY-M').toString();
			var foundMonth = monthData.filter(function(x) { return x.date === date; })[0];
			if(!foundMonth) {
				datas.push(0);
			} else {
				datas.push(parseFloat(foundMonth.total_fee_price));
			}
			currentDate = currentDate.add(1, 'months');
		}
		monthTradeRevenueDataset[key] = datas;
	}

	var chartContainer2 = document.getElementById('chart2');
	var chart2 = new Chart(chartContainer2, {
		type: 'bar',
		data: {
			labels: dateRange,
		},
		options: {
			legend: {
				display: false
			},
			title: {
				display: true,
				text: '{{ __('main.ssr') }}'
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			plugins: {
				datalabels: {
					align: 'top',
					offset: 4
				}
			}
		}
	});

	$('#chart2_select').change(function(e){
		var selectedCoin = $(e.currentTarget).val();
		chart2.data.datasets = [{
			label: '{{ __('main.ssr') }}',
			fill: false,
			data: monthTradeRevenueDataset[selectedCoin],
			datalabels: {
				display: false
			}
		},{
			fill: false,
			data: monthTradeRevenueDataset[selectedCoin],
			type: 'line',
		}];
		if(selectedCoin.toUpperCase() == 'USD'){
			chart2.options.title.text = 'USDC' + ' {{ __('main.ssr') }}';
		}else{
			chart2.options.title.text = selectedCoin.toUpperCase() + ' {{ __('main.ssr') }}';
		}
		chart2.update();
	});
	$('#chart2_select').change();

	/* 월별 출금 수수료 수익 정리 */
	var monthWithdrawDataset = {};
	var monthWithdrawDataObj = {};
	var monthWithdrawRevenueData = [
		@foreach($month_withdraws_revenues as $month_withdraws_revenue)
		{
			@foreach($month_withdraws_revenue as  $key => $value)
			'{{$key}}':'{{$value}}',
			@endforeach
		},
		@endforeach
	];

	monthWithdrawRevenueData.forEach(function(item) {
		var coin = item.cointype.toLowerCase();
		if(!(coin in monthWithdrawDataObj)) {
			monthWithdrawDataObj[coin] = [];
		}

		monthWithdrawDataObj[coin].push(item);
	});

	for(var key in monthWithdrawDataObj) {
		var monthData = monthWithdrawDataObj[key];
		var datas = [];

		var currentDate = startDate.clone();
		for(var i = 1; i <= 12; i++) {
			var date = currentDate.format('YYYY-M').toString();
			var foundMonth = monthData.filter(function(x) { return x.date === date; })[0];
			if(!foundMonth) {
				datas.push(0);
			} else {
				datas.push(parseFloat(foundMonth.amt));
			}
			currentDate = currentDate.add(1, 'months');
		}
		monthWithdrawDataset[key] = datas;
	}

	var chartContainer3 = document.getElementById('chart3');
	var chart3 = new Chart(chartContainer3, {
		type: 'bar',
		data: {
			labels: dateRange,
			datasets: [{
				label: '{{ __('main.ssr') }}',
				fill: false,
				data: monthWithdrawDataset,
				datalabels: {
					display: false
				}
			},{
				fill: false,
				data: monthWithdrawDataset,
				type: 'line',
			}],
		},
		options:{
			legend: {
				display: false
			},
			title: {
				display: true,
				text: '{{ __('main.ssr') }}'
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			plugins: {
				datalabels: {
					align: 'top',
					offset: 4
				}
			}
		}
	});

	$('#chart3_select').change(function(e){
		var selectedCoin = $(e.currentTarget).val();
		chart3.data.datasets = [{
			label: '{{ __('main.ssr') }}',
			fill: false,
			data: monthWithdrawDataset[selectedCoin],
			datalabels: {
				display: false
			}
		},{
			fill: false,
			data: monthWithdrawDataset[selectedCoin],
			type: 'line',
		}];
		chart3.options.title.text = selectedCoin.toUpperCase() + ' {{ __('main.ssr') }}';
		chart3.update();
	});
	$('#chart3_select').change();
</script>
@endsection