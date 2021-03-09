@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
		{{ __('coin.out')}}
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
    {{ __('coin.outlist')}}
	</div>
	<div class="card-body">
        <ul class="nav nav-tabs">
            <li class="{{ ($types=='all')?'active':'' }}"><a href="{{route('admin.coin_out_history', 'all')}}">{{__('admin_coin.all')}}</a></li>
            <li class="{{ ($types=='withdraw_request')?'active':'' }}"><a href="{{route('admin.coin_out_history', 'withdraw_request')}}">{{__('admin_coin.withdraw_request')}}</a></li>
            <li class="{{ ($types=='withdraw_request_confirm')?'active':'' }}"><a href="{{route('admin.coin_out_history', 'withdraw_request_confirm')}}">{{__('admin_coin.withdraw_request_confirm')}}</a></li>
            <li class="{{ ($types=='withdraw_reject')?'active':'' }}"><a href="{{route('admin.coin_out_history', 'withdraw_reject')}}">{{__('admin_coin.withdraw_reject')}}</a></li>
            <li class="{{ ($types=='withdraw_complete')?'active':'' }}"><a href="{{route('admin.coin_out_history', 'withdraw_complete')}}">{{__('admin_coin.withdraw_complete')}}</a></li>
            <li class="{{ ($types=='try_withdraw_request')?'active':'' }}"><a href="{{route('admin.coin_out_history', 'try_withdraw_request')}}">{{__('admin_coin.try_withdraw_request')}}</a></li>
        </ul>
		<div class="usr_search_box tsa-sch-box">
			<form method="get" action="">
					<select name="category">
						<option value="all">{{ __('coin.all')}}</option>
                        <option value="uid">UID</option>
						<option value="fullname">{{ __('coin.user')}}</option>
						<option value="email">Email</option>
						<option value="mobile">핸드폰 번호</option>
					</select>
					<input type="text" name="srch" />
					<button type="submit">{{ __('coin.search')}}</button>
			</form>
		</div>
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;" rowspan="2">{{ __('coin.no')}}</th>
						<th style="width:10%;" rowspan="2">UID</th>
						<th style="width:10%;" rowspan="1">{{ __('coin.user')}}</th>
						<th style="width:10%;" rowspan="1">E-mail</th>	
						<th style="width:10%;" rowspan="2">{{ __('coin.symbol')}}</th>
                        <th style="width:10%;" rowspan="2">{{ __('coin.out2')}}</th>
                        <th style="width:10%;" rowspan="1">{{ __('coin.out3')}}</th>
                        <th style="width:10%;" rowspan="1">{{ __('coin.adrs2')}}</th>
                        <th style="width:10%;" rowspan="2">{{ __('coin.active')}}</th>
                        <th style="width:10%;" rowspan="2">출금당시시세</th>
					</tr>
                    <tr>
                        <th style="width:10%;" rowspan="1">{{ __('coin.date')}}</th>
                        <th style="width:10%;" rowspan="1">Mobile Number</th>
                        <th style="width:10%;" rowspan="1">{{ __('coin.fee')}}</th>
                        <th style="width:10%;" rowspan="1">TXID</th>
                    </tr>
				</thead>
				<tbody>
					@forelse($co_historys as $co_history)
					<tr>
						<td rowspan="2">{{$co_history->id}}</td>
						<td rowspan="2">{{strtoupper($co_history->uid)}}</td>
                        <td rowspan="1">{{$co_history->fullname}}</td>
                        <td rowspan="1">{{strtoupper($co_history->email)}}</td>
                        <td rowspan="2">{{ strtoupper($co_history->cointype) != 'USD' ? strtoupper($co_history->cointype) : 'USDC'}}</td>
                        <td rowspan="2">{{__('admin_coin.'.$co_history->send_type)}}</td>
                        <td rowspan="1">{{$co_history->req_amount}}</td>
                        <td rowspan="1">{{$co_history->receiver_address}}</td>
                        <td rowspan="2" id="co_button_wrap_{{$co_history->id}}">
                            @if($co_history->status == 'withdraw_request')
                                @if($co_history->send_type == 'external')
                                    <button type="button" class="myButton edit" onclick="auto_withdraw_confirm({{$co_history->id}})">{{__('admin_coin.withdraw_confirm')}}</button>
                                    <button type="button" class="myButton edit notauto_withdraw_confirm" data-id="{{$co_history->id}}">{{__('admin_coin.not_auto_withdraw_confirm')}}</button>
                                @else
                                    <button type="button" class="myButton edit" onclick="internal_withdraw_confirm({{$co_history->id}})">{{__('admin_coin.withdraw_confirm')}}</button>
                                @endif
                                <button type="button" class="myButton del" onclick="cancel_co_io({{$co_history->id}}, 'withdraw_reject')">{{__('admin_coin.withdraw_reject')}}</button>
                            @elseif($co_history->status == 'withdraw_request_confirm')
                                <span class="withdraw_wait">{{__('admin_coin.'.$co_history->status)}}</span>
                            @elseif($co_history->status == 'withdraw_reject')
                                <span class="withdraw_reject">{{__('admin_coin.'.$co_history->status)}}</span>
                            @elseif($co_history->status == 'withdraw_complete')
                                <span class="withdraw_complete">{{__('admin_coin.'.$co_history->status)}}</span>
                            @elseif($co_history->status == 'try_withdraw_request')
                                <span class="withdraw_fail">{{__('admin_coin.'.$co_history->status)}}</span>
                            @endif
                        </td>
                        <td>
                        	{{ $co_history->price_usd != NULL ? $co_history->price_usd : '기록안된시세'}}
                        </td>
					</tr>
                    <tr>
                        <td rowspan="1">{{date("Y.m.d H:i:s", $co_history->updated)}}</td>
                        <td rowspan="1">{{strtoupper($co_history->mobile_number)}}</td>
                        <td rowspan="1">{{$co_history->send_fee}}</td>
                        <td rowspan="1">{{$co_history->tx_id}}</td>
                    </tr>
					@empty
					<tr>
						<th colspan="7">{{__('admin_coin.'.$types)}} {{ __('coin.nohere')}}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@if($co_historys)
		{!! $co_historys->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('coin.update')}}.
	</div>
</div>

<div id="notauto_confirm_wrap" class="jw_modal_wrap hidden">
	<div class="jw_overlay"></div>
	<div class="jw_modal_content_wrap like_cux">
		<div class="jw_modal_content">
			<div class="jw_modal_hd">
				<h5>{{ __('coin.out4')}}</h5>
				<div><i class="fal fa-chevron-down"></i></div>
			</div>
			<div class="jw_modal_bd">
				<div class="content_box">
                    <h5><i class="fal fa-chevron-circle-right"></i>{{ __('coin.in')}}</h5>
					<input type="text" name="tx_id" >
					<input type="hidden" name="id" value="">
				</div>
			</div>
			<div class="jw_modal_ft">
                <button type="button" class="notauto_confirm_submit cashgo">{{ __('coin.write')}}</button>
			</div>
		</div>
	</div>
</div>


<style>
    .withdraw_confirm{
        font-size: 14px;
        font-weight: bold;
        color: red;
    }

    .withdraw_wait{
        font-size: 14px;
        font-weight: bold;
        color: orange;
    }

    .withdraw_reject{
        font-size: 14px;
        font-weight: bold;
        color: red;
    }

    .withdraw_complete{
        font-size: 14px;
        font-weight: bold;
        color: blue;
    }

    .withdraw_fail{
        font-size: 14px;
        font-weight: bold;
        color: red;
    }
</style>


@endsection