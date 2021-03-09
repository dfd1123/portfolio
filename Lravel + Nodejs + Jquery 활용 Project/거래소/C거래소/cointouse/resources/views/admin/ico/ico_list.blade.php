@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">{{ __('icoo.ico_set')}}</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('icoo.ico_list')}}
	</div>
	<div class="card-body">
		<div class="usr_search_box tsa-sch-box">
			<form method="get" action="">
					<select name="keyword_srch">
						<option value="name">{{ __('icoo.ico_id')}}</option>
						<option value="symbol">ICO/IEO SYMBOL</option>
					</select>
					<input type="text" name="keyword" />
					<button type="submit">{{ __('setting.search') }}</button>
			</form>
		</div>
		<div class="table-responsive tsa-table-wrap">
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
					<th style="width: 5%;">{{ __('icoo.buyer_list')}}</th>
					<th style="width: 5%;">{{ __('icoo.active')}}</th>
					<th style="width: 10%;">{{ __('setting.setting') }}</th>
				</tr>
				</thead>
				<tbody>
				@foreach($icos as $ico)
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
					<td><a href="{{route('admin.ico_people_list', $ico->id)}}" class="myButton navy">{{ __('icoo.see')}}</a>&nbsp</td>
					<td>
						@if($ico->ico_category == 5)
						{{ __('icoo.no')}}&nbsp
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
					<td>
						@if($ico->ico_category == 5)
						<button type="button" class="btn_exit myButton xbtn ico_reject" data-reject="{{$ico->reject}}">{{ __('icoo.reason')}}</button>
						<a href="{{route('admin.ico_confirm', $ico->id)}}" class="myButton navy">{{ __('icoo.recon')}}</a>
						@elseif($ico->active == 0 && $ico->ico_category != 5)
						<a href="{{route('admin.ico_confirm', $ico->id)}}" class="myButton navy">{{ __('icoo.con')}}</a>
						<form method="get" action="{{route('admin.ico_ban', $ico->id)}}" id="cancel_form_{{$ico->id}}" class="ico_cancel_form">
							@csrf
							<input type="hidden" name="reject" />
							<button type="button" data-id="{{$ico->id}}" class="myButton navy">{{ __('icoo.no_01')}}</button>
						</form>
						@else
						<form method="get" action="{{route('admin.ico_ban', $ico->id)}}" id="cancel_form_{{$ico->id}}" class="ico_cancel_form">
							@csrf
							<input type="hidden" name="reject" />
							<button type="button" data-id="{{$ico->id}}" class="myButton del">{{ __('icoo.cancel')}}</button>
						</form>
						@endif
					</td>
					
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
		@if($icos)
			{!! $icos->render() !!}
		@endif
	</div>
</div>
		
@endsection

