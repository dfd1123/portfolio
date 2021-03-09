@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">{{ __('icoo.set')}}</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	ICO/IEO {{ __('icoo.buyer_list')}}
	</div>
	<div class="card-body">
		<table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
			<thead>
			<tr>
				<th style="width: 3%;">ID</th>
				<th style="width: 4%;">{{ __('icoo.writer')}}</th>
				<th style="width: 10%;">{{ __('icoo.title')}}</th>
				<th style="width: 4%;">{{ __('icoo.symbol')}}</th>
				<th style="width: 5%;">{{ __('icoo.ico_dani')}}</th>
				<th style="width: 5%;">{{ __('icoo.dollor')}}</th>
				<th style="width: 8%;">{{ __('icoo.coinstarprice')}}</th>
				<th style="width: 6%;">{{ __('icoo.ing_date')}}</th>
				<th style="width: 5%;">URL</th>
				<th style="width: 4%;">{{ __('icoo.goal_q')}}</th>
				<th style="width: 4%;">{{ __('icoo.now_q')}}</th>
				<th style="width: 5%;">{{ __('icoo.percent')}}</th>
				<th style="width: 5%;">{{ __('icoo.active')}}</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>{{ $ico->id }}</td>
				<td>{{ $ico->w_name }}</td>
				<td><a href="{{route('ico_show', $ico->id)}}">{{ $ico->ico_title }}</a></td>
				<td>{{ $ico->ico_symbol }}</td>
				<td>{{ $ico->ico_min }} {{ $ico->ico_symbol }}</td>
				<td>{{ $ico->ico_collect }}</td>
				<td>1 {{ $ico->ico_symbol }} = {{ $ico->ico_coin_p }} {{ $ico->ico_collect }}</td>
				<td>{{ date_format(date_create($ico->ico_from),"Y-m-d") }} ~<br> {{ date_format(date_create($ico->ico_to),"Y-m-d") }}</td>
				<td><a href="{{ $ico->ico_url }}" class="myButton navy" target="_blank">{{ __('icoo.link')}}</a></td>
				<td>{{ number_format($ico->ico_goal) }}</td>
				<td>{{ number_format($ico->ico_coin) }}</td>
				<td>{{ number_format(bcmul(bcdiv($ico->ico_coin,$ico->ico_goal,4),100,2),2) }} %</td>
				<td>
					@if($ico->ico_category == 5)
					{{ __('icoo.no')}&nbsp
					@elseif($ico->active == 1 && $ico->ico_category == 0)
					{{ __('icoo.wait')}}&nbsp
					@elseif($ico->active == 1 && $ico->ico_category == 1)
					{{ __('icoo.ing')}}&nbsp
					@elseif($ico->active == 1 && $ico->ico_category == 2)
					{{ __('icoo.will_ing')}}&nbsp
					@elseif($ico->active == 1 && $ico->ico_category == 3)
					{{ __('icoo.jong')}}&nbsp
					@elseif($ico->active == 1 && $ico->ico_category == 4)
					{{ __('icoo.sold_out')}}&nbsp
					@endif
				</td>
				
			</tr>
			</tbody>
		</table>
		<div class="usr_search_box tsa-sch-box">
			
			<form method="get" action="">
					<input type="text" name="keyword" placeholder="{{ __('icoo.name_search')}}" style="padding-left:0;"/>
					<button type="submit">{{ __('setting.search') }}</button>
			</form>
		</div>
		
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
				<thead>
				<tr>
					<th style="width: 10%;">{{ __('icoo.sunseo')}}</th>
					<th style="width: 10%;">{{ __('icoo.buy_coin_name')}}</th>
					<th style="width: 10%;">{{ __('icoo.buy_time')}}</th>
					<th style="width: 10%;">{{ __('icoo.buy_coin_01')}}</th>
					<th style="width: 10%;">{{ __('icoo.buy_coin_02')}}</th>
					<th style="width: 10%;">{{ __('icoo.krw')}}</th>
					<th style="width: 10%;">{{ __('icoo.buy_coin_03')}}</th>
					<th style="width: 10%;">{{ __('icoo.all_price')}}</th>
				</tr>
				</thead>
				<tbody>
				@forelse($ico_peoples as $ico_people)
				<tr>
					<td>{{ $ico_people->id }}</td>
					<td>{{ $ico_people->name }}</td>
					<td>{{ $ico_people->order_time }}</td>
					<td>{{ $ico_people->order_coin }}</td>
					<td>{{ $ico_people->buy_amount }} {{ $ico_people->order_coin }}</td>
					
					
					<td>{{ $ico_people->buy_pay }}</td>
					<td>{{ $ico_people->order_price }} {{ $ico_people->buy_pay }}</td>
					<td>{{ $ico_people->buy_price }} {{ $ico_people->buy_pay }}</td>
				</tr>
				@empty
				<tr>
					<td rowspan="8">{{ __('icoo.no_buyer')}}</td>
				</tr>
				@endforelse
				</tbody>
			</table>
		</div>
		@if($ico_peoples)
			{!! $ico_peoples->render() !!}
		@endif
	</div>
</div>
		
@endsection

