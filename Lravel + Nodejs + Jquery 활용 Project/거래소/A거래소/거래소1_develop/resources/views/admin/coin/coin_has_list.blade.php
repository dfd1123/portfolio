@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
		코인현황
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
    코인현황 리스트
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>코인명</th>
						<th>봇 잔액</th>
						<th>핫월렛 잔액</th>
						<th>현재 출금 되야할 금액</th>
						<th>입금 주소</th>
					</tr>
				</thead>
				<tbody>
					@forelse($coins as $coin)
					<tr>
						<td>{{ __('coin_name.'.$coin->api)}}({{ $coin->symbol }})</td>
						<td>{{ $results['bot_balance_'.$coin->api] }} {{ $coin->symbol }}</td>
						<td>{{ $results['market_balance_'.$coin->api] }} {{ $coin->symbol }}</td>
						<td>{{ $results['send_balance_'.$coin->api] }} {{ $coin->symbol }}</td>
						<td>{!! $results['admin_address_'.$coin->api] !!}</td>
					</tr>
					@empty
					<tr>
						
					</tr>
					@endforelse			
				</tbody>
			</table>
			
		</div>
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('coin.update')}}
	</div>
</div>


<div id="deposite_modal" class="jw_modal_wrap hidden">
	<div class="jw_overlay"></div>
	<div class="jw_modal_content_wrap">
		<div class="jw_modal_content">
			<div class="jw_modal_hd">
				<h5>코인입금</h5>
				<div><i class="fal fa-chevron-down"></i></div>
			</div>
			<div class="jw_modal_bd">
				<div class="content_box">
                    <div class="toggle_con first_deposit_con">
                        <span class="first_deposit_info"  id="first_deposit_info">{!!__('trans.my_BTC_in_address') !!}</span>

                        <div class="first_deposit_qr_con">

                            <span id="deposit_coin_address_qrcode"></span>

                        </div>

                        <div class="first_deposit_form_group ta_form_group">
                            <input readonly="readonly" type="text" class="ta_form_input form-control" id="deposit_coin_address">
                            <button class="btn transwallet_btn" data-clipboard-action="copy" data-clipboard-target="#deposit_coin_address">
                            {{__('trans.copy')}}
                            </button>
                        </div>

                        

                    </div>
				</div>
			</div>
			<div class="jw_modal_ft">
			</div>
		</div>
	</div>
</div>

<div id="withdraw_modal" class="jw_modal_wrap hidden">
	<div class="jw_overlay"></div>
	<div class="jw_modal_content_wrap">
		<div class="jw_modal_content">
			<div class="jw_modal_hd">
				<h5>코인출금</h5>
				<div><i class="fal fa-chevron-down"></i></div>
			</div>
			<div class="jw_modal_bd">
				<div class="content_box">
                    <div class="toggle_con second_wdr_con">

                        <div class="second_wdr_form_group">
                            <p class="p_horizon">
                                <span class="tit_small">{{__('trans.one_day_out_limit')}}</span>
                                <span class="data_info" id="withdraw_limit_amt"> <b></b> <b class="currency"></b> </span>
                            </p>
                        </div>

                        <div class="second_wdr_form_group_wrap">
                            <div class="second_wdr_form_group ta_form_group">
                                <label>{{__('trans.out_address')}}</label>
                                <input placeholder="{{__('trans.input')}}" type="text" class="ta_form_input form-control" id="withdraw_check_address">
                                <button class="btn" id="withdraw_check_address_btn" onclick="withdraw_check_address();">
                                {{__('trans.check_address')}}
                                </button>
                            </div>

                            <div class="second_wdr_form_group ta_form_group">
                                <label id="withdraw_amt_label">{{__('trans.outusdc')}}</label>
                                <input type="number" class="ta_form_input form-control" id = "withdraw_amt" onchange="withdraw_onkey_amt();">
                                <button class="btn" id="withdraw_amt_max_btn" onclick="withdraw_max_amt();">
                                {{__('trans.max')}}
                                </button>
                            </div>
                        </div>
                        <div class="second_wdr_form_group">
                            <p class="p_horizon etc_style">
                                <span class="tit_small etc_style">{{__('trans.out_fees')}}</span>
                                <span class="data_info"> <b id="withdraw_send_fee"></b> </span>
                            </p>
                        </div>
                        <div class="second_wdr_form_group">
                            <p class="p_horizon etc_style">
                                <span class="tit_small etc_style">{{__('trans.all_out')}}</span>
                                <span class="data_info"> <b id="withdraw_total_amt"></b> </span>
                            </p>
                        </div>
                        <div class="second_wdr_form_group second_wdr_btn_group">
                            <button class="second_wdr_btn" onclick="send_transaction();">
                            {{__('trans.please_out')}}
                            </button>
                        </div>

                    </div>
				</div>
			</div>
			<div class="jw_modal_ft">
			</div>
		</div>
	</div>
</div>

@endsection

@section('script')

@endsection