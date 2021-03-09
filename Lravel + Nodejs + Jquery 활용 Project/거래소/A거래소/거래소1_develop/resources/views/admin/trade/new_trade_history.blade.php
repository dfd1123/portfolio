@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	{{ __('trade_history.tr_ad') }}
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('trade_history.tr_li') }}
	</div>
	<div class="card-body">
		<div class="usr_search_box tsa-sch-box">
			<form method="get" action="" style="max-width=600px;">
                    
					<select name="cointype" style="left: 105px;">
						<option value="all">{{ __('trade_history.all') }}</option>
						@foreach($coins as $coin)
							<option value="{{$coin->api}}">{{__('coin_name.'.$coin->api)}}</option>
						@endforeach
					</select>
                    <select name="markettype">
                        <option value="all">{{ __('trade_history.all') }}</option>
                        <option value="btc">{{__('coin_name.btc')}}</option>
                        <option value="eth">{{__('coin_name.eth')}}</option>
                        <option value="usd">{{__('coin_name.usdc')}}</option>
                    </select>
					<input type="text" name="srch" style="padding-left: 220px;"/>
					<button type="submit">{{ __('trade_history.search') }}</button>
			</form>
		</div>
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;" rowspan="2">{{ __('trade_history.number') }}</th>
						<th style="width:10%;" rowspan="1">{{ __('trade_history.fir') }}</th>
						<th style="width:7%;" rowspan="2">{{ __('trade_history.user_acc') }}</th>
						<th style="width:5%;" rowspan="2">{{ __('trade_history.coin_type') }}</th>
                        <th style="width:5%;" rowspan="2">{{ __('trade_history.market_type') }}</th>
						<th style="width:10%;" rowspan="2">{{ __('trade_history.sell_plz') }}</th>
                        <th style="width:10%;" rowspan="1">{{ __('trade_history.sell_bal') }}</th>
                        <th style="width:10%;" rowspan="2">{{ __('trade_history.sell_price') }}</th>
                        <th style="width:10%;" rowspan="2">{{ __('trade_history.buy_plz') }}</th>
                        <th style="width:10%;" rowspan="1">{{ __('trade_history.buy_bal') }}</th>
                        <th style="width:10%;" rowspan="2">{{ __('trade_history.buy_price') }}</th>
                        <th style="width:8%;" rowspan="2">{{ __('trade_history.now') }}</th>
					</tr>
                    <tr>
                        <th rowspan="1">{{ __('trade_history.last') }}</th>
                        <th rowspan="1">{{ __('trade_history.sell_suc') }}</th>
                        <th rowspan="1">{{ __('trade_history.buy_suc') }}</th>
                    </tr>
				</thead>
				<tbody>
					@forelse($trade_historys as $trade_history)
					<tr>
						<td rowspan="2">{{$trade_history->id}}</td>
                        <td rowspan="1">{{date("Y.m.d H:i:s", $trade_history->created)}}</td>
                        <td rowspan="2">{{$trade_history->userid}}</td>
                        <td rowspan="2">{{strtoupper($trade_history->cointype)}}</td>
                        <td rowspan="2">{{strtoupper($trade_history->currency)== 'USD' ? 'USDC' : strtoupper($trade_history->currency)}}</td>
                        <td rowspan="2">{{$trade_history->sell_COIN_amt_total}}</td>
                        <td rowspan="1">{{$trade_history->sell_COIN_amt}}</td>
                        <td rowspan="2">{{$trade_history->sell_coin_price}}</td>
                        <td rowspan="2">{{$trade_history->buy_COIN_amt_total}}</td>
                        <td rowspan="1">{{$trade_history->buy_COIN_amt}}</td>
                        <td rowspan="2">{{$trade_history->buy_coin_price}}</td>
						<td rowspan="2">
                            {{__('market.'.$trade_history->status)}}
                        </td>

					</tr>
                    <tr>
                        <td rowspan="1">{{date("Y.m.d H:i:s", $trade_history->updated)}}</td>
                        <td rowspan="1">{{$trade_history->sell_COIN_amt_finished}}</td>
                        <td rowspan="1">{{$trade_history->buy_COIN_amt_finished}}</td>
                    </tr>
					@empty
					<tr>
						<th colspan="12">{{ __('trade_history.trade_history_sentence1') }}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@if($trade_historys)
		{!! $trade_historys->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('trade_history.trade_hitory.sentenc2') }}
	</div>
</div>



@endsection