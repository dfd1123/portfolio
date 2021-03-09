@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
<li class="breadcrumb-item active">{{ __('user.user_ad') }}</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
<!-- <div class="card-header">
	회원리스트</div> -->
<div class="card-body">
	<!--a href="{{route('admin.user_excel')}}" type="button" class="myButton navy">Excel</a-->
	<ul class="nav nav-tabs">
        <li class="{{ $tab == 1 ? 'active' : '' }}"><a href="{{ route('admin.user_detail',['uid' => $uid,'tab' => 1 ]) }}">회원기본정보</a></li>
        <li class="{{ $tab == 2 ? 'active' : '' }}"><a href="{{ route('admin.user_detail',['uid' => $uid,'tab' => 2 ]) }}">거래내역</a></li>
        <li class="{{ $tab == 3 ? 'active' : '' }}"><a href="{{ route('admin.user_detail',['uid' => $uid,'tab' => 3 ]) }}">입출금내역</a></li>
        <li class="{{ $tab == 4 ? 'active' : '' }}"><a href="{{ route('admin.user_detail',['uid' => $uid,'tab' => 4 ]) }}">ICO/IEO</a></li>
        <li class="{{ $tab == 5 ? 'active' : '' }}"><a href="{{ route('admin.user_detail',['uid' => $uid,'tab' => 5 ]) }}">1:1문의내역</a></li>
        <!--li class="{{ $tab == 6 ? 'active' : '' }}"><a href="{{ route('admin.user_detail',['uid' => $uid,'tab' => 6 ]) }}">장외거래</a></li-->
    </ul>
    @if($tab == 1)
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<th style="width: 5%;">UID</th>
					<th style="width: 7%;">{{ __('user.name') }}</th>
					<th style="width: 15%;">{{ __('user.email') }}</th>
					<th style="width: 15%;">휴대폰 번호</th>
					<th style="width: 10%;">{{ __('user.bal_ad') }}</th>
					<th style="width: 7%;">{{ __('user.sec') }}</th>
					<th style="width: 7%;">{{ __('user.now') }}</th>
					<th style="width: 7%;">회원탈퇴</th>
				</thead>
				<tbody>
				@forelse($users as $user)
				<tr>
					<td>{{$user->uid}}</td>
					<td>{{$user->fullname}}</td>
					<td>{{str_replace(session('market_type')."_","",$user->email)}}</td>
					<td>{{$user->mobile_number}}</td>
					<td>
						<button type="button" data-id="{{$user->uid}}" class="availble_coin_confirm myButton navy">{{ __('user.bal_check') }}</button>
						<button type="button" data-id="{{$user->uid}}" class="add_balance_confirm myButton edit">{{ __('user.bal_add') }}</button>
					</td>
					<td><button type="button" data-id="{{$user->uid}}" class="securty_edit_btn myButton xbtn">{{ __('user.sec_check') }}</button></td>
					<td>
						<select class="form-control" name="status" data-id="{{$user->uid}}">
						{{$user->status}}
							<option value="1" {{($user->status == 1)?'selected=selected':''}}>{{ __('user.good') }}</option>
							<option value="2" {{($user->status == 2)?'selected=selected':''}}>{{ __('user.stop') }}</option>
						</select>
					</td>
					<td>
						<button type="button" data-id="{{$user->uid}}" class="user_secession myButton navy">탈퇴처리</button>
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="8" >{{ __('user.user_sentence_4') }}</td>
				</tr>
				@endforelse
				</tbody>
			</table>
			<table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<th style="width: 5%;">가입날짜</th>
					<th style="width: 7%;">등록계좌</th>
					<th style="width: 15%;">메모장</th>
				</thead>
				<tbody>
				@forelse($users as $user)
				<tr>
					<td>{{$user->created_at}}</td>
					<td>{{$user->account_num}} {{$user->account_bank}}</td>
					<td><textarea id="user_memo" data-id="{{ $user->uid }}" style="width:100%;" rows="8">{{$user->user_memo}}</textarea></td>
				</tr>
				@empty
				<tr>
					<td colspan="8" >{{ __('user.user_sentence_4') }}</td>
				</tr>
				@endforelse
				</tbody>
			</table>
		</div>
		@if($users)
			{!! $users->render() !!}
		@endif
	@elseif($tab == 2)
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;" rowspan="2">{{ __('trade_history.number') }}</th>
						<th style="width:10%;" rowspan="2">UID</th>
						<th style="width:10%;" rowspan="1">{{ __('trade_history.fir') }}</th>
						<th style="width:10%;" rowspan="1">E-mail</th>
						<th style="width:10%;" rowspan="2">{{ __('trade_history.user_acc') }}</th>
						<th style="width:10%;" rowspan="1">{{ __('trade_history.coin_type') }}</th>
						<th style="width:10%;" rowspan="2">{{ __('trade_history.sell_plz') }}</th>
                        <th style="width:10%;" rowspan="1">{{ __('trade_history.sell_bal') }}</th>
                        <th style="width:10%;" rowspan="2">{{ __('trade_history.sell_price') }}</th>
                        <th style="width:10%;" rowspan="2">{{ __('trade_history.buy_plz') }}</th>
                        <th style="width:10%;" rowspan="1">{{ __('trade_history.buy_bal') }}</th>
                        <th style="width:10%;" rowspan="2">{{ __('trade_history.buy_price') }}</th>
                        <th style="width:10%;" rowspan="2">{{ __('trade_history.now') }}</th>
					</tr>
                    <tr>
                        <th rowspan="1">{{ __('trade_history.last') }}</th>
                        <th style="width:10%;" rowspan="1">Mobile Number</th>
                        <th rowspan="1">{{ __('trade_history.currency') }}</th>
                        <th rowspan="1">{{ __('trade_history.sell_suc') }}</th>
                        <th rowspan="1">{{ __('trade_history.buy_suc') }}</th>
                        
                    </tr>
				</thead>
				<tbody>
					@forelse($trade_historys as $trade_history)
					<tr>
						<td rowspan="2">{{$trade_history->id}}</td>
						<td rowspan="2">{{strtoupper($trade_history->uid)}}</td>
                        <td rowspan="1">{{date("Y.m.d H:i:s", $trade_history->created)}}</td>
                        <td rowspan="1">{{strtoupper($trade_history->email)}}</td>
                        <td rowspan="2">{{$trade_history->fullname}}</td>
                        <td rowspan="1">{{strtoupper($trade_history->cointype)}}</td>
                        <td rowspan="2">{{$trade_history->sell_COIN_amt_total}}</td>
                        <td rowspan="1">{{$trade_history->sell_COIN_amt}}</td>
                        <td rowspan="2">{{$trade_history->sell_coin_price}}</td>
                        <td rowspan="2">{{$trade_history->buy_COIN_amt_total}}</td>
                        <td rowspan="1">{{$trade_history->buy_COIN_amt}}</td>
                        <td rowspan="2">{{$trade_history->buy_coin_price}}</td>
						<td rowspan="2">
							@if($trade_history->status == 'OnProgress' && $trade_history->type == 'sell' && $trade_history->sell_COIN_amt == 0 )
							{{__('market.sell')}} {{__('trade_history.complete')}}
							@elseif($trade_history->status == 'OnProgress' && $trade_history->type == 'buy' && $trade_history->buy_COIN_amt == 0)
							{{__('market.buy')}} {{__('trade_history.complete')}}
							@else
							{{__('market.'.$trade_history->status)}}
							@endif
							
                        </td>

					</tr>
                    <tr>
                        <td rowspan="1">{{date("Y.m.d H:i:s", $trade_history->updated)}}</td>
                        <td rowspan="1">{{strtoupper($trade_history->mobile_number)}}</td>
                        <td rowspan="1">{{ strtolower($trade_history->currency) == 'usd' ? 'USDC' : strtoupper($trade_history->currency) }}{{ __('trade_history.market') }}</td>
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
	@elseif($tab == 3)
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;">{{ __('cointr.id')}}</th>
						<th style="width:10%;">{{ __('cointr.date')}}</th>
						<th style="width:5%;">UID</th>
						<th style="width:10%;">{{ __('cointr.user')}}</th>
						<th style="width:10%;">E-mail</th>
						<th style="width:10%;">Mobile Number</th>
						<th style="width:5%;">{{ __('cointr.coin')}}</th>
						<th style="width:5%;">{{ __('cointr.dv')}}</th>
						<th style="width:5%;">{{ __('cointr.qua')}}</th>
						<!--th style="width:5%;">{{ __('cointr.charge')}}</th-->
						<th style="width:10%;">{{ __('cointr.adrs')}}</th>
						<th style="width:5%;">{{ __('cointr.cf')}}</th>
						<th style="width:5%;">TX ID</th>
					</tr>
				</thead>
				<tbody>
					@forelse($transactions as $transaction)
					<tr>
						<td>{{$transaction->id}}</td>
						<td>{{$transaction->created_dt}}</td>
						<td>{{$transaction->uid}}</td>
						<td>{{$transaction->fullname}}</td>
						<td>{{$transaction->email}}</td>
						<td>{{$transaction->mobile_number}}</td>
						<td>{{ strtoupper($transaction->cointype) != 'USD' ? strtoupper($transaction->cointype) : 'USDC' }}</td>
						<td>{{$transaction->category}}</td>
						<td>{{$transaction->amount}}</td>
						<!--td>
							@if($transaction->processed == 'n')
							{{ __('cointr.n')}}
							@elseif($transaction->processed == 'y')
							{{ __('cointr.y')}}
							@elseif($transaction->processed == 'fail')
							{{ __('cointr.fail')}}
							@endif
						</td-->
						<td>{{$transaction->address}}</td>
						<td>{{$transaction->confirmations}}</td>
						<td>{{$transaction->txid}}</td>
					</tr>
					@empty
					<tr>
						<th colspan="9">{{ __('cointr.nolist')}}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@if($transactions)
			{!! $transactions->render() !!}
		@endif
	@elseif($tab == 4)
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
	@elseif($tab == 5)
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;">{{ __('admin_qna.no')}}</th>
						<th style="width:30%;">{{ __('admin_qna.tt')}}</th>
						<th style="width:10%;">{{ __('admin_qna.act')}}</th>
						<th style="width:10%;">{{__('admin_qna.ask_user')}}</th>
						<th style="width:10%;">E-mail</th>
						<th style="width:10%;">{{ __('admin_qna.wr')}}</th>
						<th style="width:10%;">{{ __('admin_qna.edit')}}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($qnas as $qna)
					<tr>
						<td>{{$qna->id}}</td>
						<td>
                            @if($qna->answered == 0)
                                <a  href="{{route('admin.qna_answer_create', $qna->id)}}">{{$qna->title}}</a>
                            @else
                                <a href="{{route('admin.qna_answer_edit', $qna->id)}}">{{$qna->title}}</a>
                            @endif
                        </td>
						<td>
                            @if($qna->answered == 0)
                                <a class="wait_ans" href="{{route('admin.qna_answer_create', $qna->id)}}">{{ __('admin_qna.answer5')}}</a>
                            @else
                                <a class="complete_ans" href="{{route('admin.qna_answer_edit', $qna->id)}}">{{ __('admin_qna.answer6')}}</a>
                            @endif
                        </td>
						<td>{{$qna->fullname}}</td>
						<td>{{$qna->email}}</td>
						<td>{{date("Y-m-d", $qna->created)}}</td>
						<td>{{date("Y-m-d", $qna->updated)}}</td>
					</tr>
					@empty
					<tr>
						<th colspan="7">{{ __('admin_qna.nohere')}}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
			@if($qnas)
			{!! $qnas->render() !!}
			@endif
		</div>
	@endif
</div>
<div class="card-footer small text-muted">{{ $datetime }} {{ __('user.user_sentence_2') }}</div>
</div>
		
<div id="security_edit_wrap" class="jw_modal_wrap hidden">
	<div class="jw_overlay"></div>
	<div class="jw_modal_content_wrap">
		<div class="jw_modal_content">
			<div class="jw_modal_hd">
				<h5>{{ __('user.sec_check') }}</h5>
				<div><i class="fal fa-chevron-down"></i></div>
			</div>
			<div class="jw_modal_bd">
				<div class="content_box">
					<div class="section_div">
						<label class="tsa-label-st"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.email_cer') }}</label>
						<div class="text-right">
							<label for="email_verified_y" class="mr-3"><input type="radio" name="email_verified" id="email_verified_y" value="1" class="grayCheckbox mr-2"/>{{ __('user.cer') }}</label>
							<label for="email_verified_n"><input type="radio" name="email_verified" id="email_verified_n" value="0" class="grayCheckbox mr-2"/>{{ __('user.no_cer') }}</label>
						</div>
					</div>
					<div class="section_div">
						<label class="tsa-label-st"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.phone_cer') }}</label>
						<div class="text-right">
							<label for="mobile_verified_y" class="mr-3"><input type="radio" name="mobile_verified" id="mobile_verified_y" value="1" class="grayCheckbox mr-2"/>{{ __('user.cer') }}</label>
							<label for="mobile_verified_n"><input type="radio" name="mobile_verified" id="mobile_verified_n" value="0" class="grayCheckbox mr-2"/>{{ __('user.no_cer') }}</label>
						</div>
					</div>
					<div class="section_div">
						<label class="tsa-label-st"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.id_cer') }}</label>
						<div class="text-right">
							<label class="mr-3"><input type="radio" name="document_verified" id="document_verified_y" value="1" readonly="readonly" disabled class="grayCheckbox mr-2"/>{{ __('user.cer') }}</label>
							<label><input type="radio" name="document_verified" id="document_verified_n" value="0" readonly="readonly" disabled class="grayCheckbox mr-2"/>{{ __('user.no_cer') }}</label>
						</div>
					</div>
					<div class="section_div">
						<label class="tsa-label-st"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.account') }}</label>
						<div class="text-right">
							<label class="mr-3"><input type="radio" name="account_verified" id="account_verified_y" value="1" readonly="readonly" disabled class="grayCheckbox mr-2"/>{{ __('user.cer') }}</label>
							<label><input type="radio" name="account_verified" id="account_verified_n" value="0" readonly="readonly" disabled class="grayCheckbox mr-2"/>{{ __('user.no_cer') }}</label>
						</div>
					</div>
					<div class="section_div">
						<label class="tsa-label-st"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.otp_cer') }}</label>
						<div class="text-right">
							<label for="google_verified_y" class="mr-3"><input type="radio" name="google_verified" id="google_verified_y" value="1" class="grayCheckbox mr-2"/>{{ __('user.cer') }}</label>
							<label for="google_verified_y"><input type="radio" name="google_verified" id="google_verified_n" value="0" class="grayCheckbox mr-2"/>{{ __('user.no_cer') }}</label>
						</div>
					</div>
					<input type="hidden" name="ver_temp_user_id">
				</div>
			</div>
			<div class="jw_modal_ft">
			</div>
		</div>
	</div>
</div>

<div id="availble_confirm_wrap" class="jw_modal_wrap hidden">
	<div class="jw_overlay"></div>
	<div class="jw_modal_content_wrap">
		<div class="jw_modal_content">
			<div class="jw_modal_hd">
				<h5>{{ __('user.bal_ad') }}</h5>
				<div><i class="fal fa-chevron-down"></i></div>
			</div>
			<div class="jw_modal_bd">
				<div class="content_box">
					@forelse($coins as $coin)
					<div class="section_div">
						<h5><i class="fal fa-chevron-circle-right pr-2"></i>{{ ($coin->name == 'US Dollor') ? 'USDC' : $coin->name }}</h5>
						<label class="tsa-label-st">{{ __('user.bal') }}</label>
						<div class="mb-2">
							<input type="text" name="available_balance_{{ $coin->api }}" readonly="readonly" class="form-control tsa-input-st"/>
						</div>
						@if($coin->api != 'krw')
						<label class="tsa-label-st">매수 대기</label>
						<div>
							<input type="text" name="buy_ads_balance_{{ $coin->api }}" readonly="readonly" class="form-control tsa-input-st" />
						</div>
						<label class="tsa-label-st">출금대기, {{ __('user.wait_price') }}</label>
						<div>
							<input type="text" name="pending_balance_{{ $coin->api }}" readonly="readonly" class="form-control tsa-input-st" />
						</div>
						@endif
						<label class="tsa-label-st">{{ $coin->api == 'krw' ? '거래대기 금액' : '락업 금액' }}</label>
						<div>
							<input type="text" name="lock_balance_{{ $coin->api }}" readonly="readonly" class="form-control tsa-input-st" />
						</div>
					</div>
					@empty
						<p>{{ __('user.user_sentence_11') }}</p>
					@endforelse
					<input type="hidden" name="temp_user_id">
				</div>
			</div>
			<div class="jw_modal_ft">
			</div>
		</div>
	</div>
</div>

<div id="add_balance_edit_wrap" class="jw_modal_wrap hidden">
		<div class="jw_overlay"></div>
		<div class="jw_modal_content_wrap">
			<div class="jw_modal_content">
				<div class="jw_modal_hd">
					<h5>{{ __('user.price_add') }}</h5>
					<div><i class="fal fa-chevron-down"></i></div>
				</div>
				<div class="jw_modal_bd">
					<div class="content_box">
						<div class="section_div">
							<label class="tsa-label-st"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.money') }}</label>
							<div class="mb-3">
								<select class="form-control tsa-input-st" id="cointype" name="cointype">
									@foreach($coins as $coin)
									<option value="{{$coin->api}}">{{$coin->symbol}}</option>
									@endforeach
								</select>
							</div>
							<label class="tsa-label-st"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.list') }}</label>
							<div>
								<input class="form-control tsa-input-st" type="text" name="reason" id="add_balance_reason" />
							</div>
						</div>
						<label class="tsa-label-st mt-3"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.add_price') }}</label>
						<div>
							<input class="form-control tsa-input-st" type="number" step="any" name="amount" id="add_balance_amount" required />
						</div>
						<button id="add_balance_save" class="btn btn-default mint_btn mt-3">{{ __('user.add') }}</button>
					</div>
				</div>
				<div class="jw_modal_ft">
				</div>
			</div>
		</div>
	</div>
@endsection

@section('script')
<script>
	/* \resources\js\admin\user.js 사용*/
	$(function(){
		/* 보안인증확인 */
		loadPopup('.securty_edit_btn', '#security_edit_wrap', function(e) {
			var id = $(e.currentTarget).data('id');

			$.ajax({
				url : "/admin/user_security_load",
				type : "POST",
				data : {_token : CSRF_TOKEN, id : id},
				dataType : "JSON",
				success : function(data) {
					if(data.email_verified == 0){
						$('#email_verified_n').prop("checked",true);
						$('#email_verified_y').prop("checked",false);
					}else{
						$('#email_verified_y').prop("checked",true);
						$('#email_verified_n').prop("checked",false);
					}
					
					if(data.mobile_verified == 0){
						$('#mobile_verified_n').prop("checked",true);
						$('#mobile_verified_y').prop("checked",false);
					}else{
						$('#mobile_verified_y').prop("checked",true);
						$('#mobile_verified_n').prop("checked",false);
					}
					
					if(data.document_verified == 0){
						$('#document_verified_n').prop("checked",true);
						$('#document_verified_y').prop("checked",false);
					}else{
						$('#document_verified_y').prop("checked",true);
						$('#document_verified_n').prop("checked",false);
					}
					
					if(data.account_verified == 0){
						$('#account_verified_n').prop("checked",true);
						$('#account_verified_y').prop("checked",false);
					}else{
						$('#account_verified_y').prop("checked",true);
						$('#account_verified_n').prop("checked",false);
					}
					
					if(data.google_verified == 0){
						$('#google_verified_n').prop("checked",true);
						$('#google_verified_y').prop("checked",false);
					}else{
						$('#google_verified_y').prop("checked",true);
						$('#google_verified_n').prop("checked",false);
					}
					
					$('input[name="ver_temp_user_id"]').val(id);
				}
			});
		});

		/* 잔액확인 */
		loadPopup('.availble_coin_confirm', '#availble_confirm_wrap', function(e) {
			var id = $(e.currentTarget).data('id');

			@foreach($coins as $coin)
				$('input[name="available_balance_{{$coin->api}}"]').val('');
				$('input[name="pending_balance_{{$coin->api}}"]').val('');
				$('input[name="lock_balance_{{$coin->api}}"]').val('');
				$('input[name="buy_ads_balance_{{$coin->api}}"]').val('');
			@endforeach

			$.ajax({
				url : "/admin/user_available_load",
				type : "POST",
				data : {_token : CSRF_TOKEN, id : id},
				dataType : "JSON",
				success : function(data) {
					$.each(data,function(key, value){
						$('input[name="available_balance_'+value.api+'"]').val(value.available_balance);
						$('input[name="pending_balance_'+value.api+'"]').val(value.trading_pending);
						$('input[name="lock_balance_'+value.api+'"]').val(value.lock_pending);
						$('input[name="buy_ads_balance_'+value.api+'"]').val(value.buying_amt);
					});
				}
			});
		});

		/* 잔액추가 */
		loadPopup('.add_balance_confirm', '#add_balance_edit_wrap', function(e) {
			$("#cointype").prop("selectedIndex", 0);
			$('#add_balance_reason').val('');
			$('#add_balance_amount').val('');
			$('#add_balance_save').attr('disabled', false).data('id', $(e.currentTarget).data('id'));
		});

		$('#add_balance_save').click(function(e){
			var cointype = $('#cointype').val();
			var reason = $('#add_balance_reason').val();
			var amount_input =  $('#add_balance_amount').val();

			var button = $(e.currentTarget);
			var id = button.data('id');

			if(reason === '') {
				alert("{{ __('user.user_sentence_5') }}");
				return;
			}

			if(amount_input === '') {
				alert("{{ __('user.user_sentence_6') }}");
				return;
			}

			var amount = parseFloat(amount_input);
			if(amount == NaN) {
				alert("{{ __('user.user_sentence_7') }}");
				return false;
			}

			if(amount == 0) {
				alert("{{ __('user.user_sentence_8') }}");
				return false;
			}

			if(confirm('{{ __('user.user_sentence_9') }}')){
				button.attr('disabled', true);
				$.ajax({
					url : "/admin/add_balance_change",
					type : "POST",
					data : {
						_token : CSRF_TOKEN,
						id : id,
						cointype : cointype,
						reason : reason,
						amount : amount_input
					},
					dataType : "JSON",
					success : function(data) {
						if(data.status === 1) {
							button.attr('disabled', false);
							alert("{{ __('user.user_sentence_10') }}");
						}
					}
				});
			}
		});

		$('#user_memo').change(function(){
			var uid = $(this).data('id');
			var user_memo = $(this).val();

			console.log(uid);
			console.log(user_memo);
			
			$.ajax({
				url : "/admin/user/memo/change",
				type : "POST",
				data : {
					_token : CSRF_TOKEN,
					uid : uid,
					user_memo : user_memo,
				},
				dataType : "JSON",
				success : function(data) {
					if(data.status === 1) {
						alert("메모가 성공적으로 변경되었습니다.");
					}else{
						alert("메모 변경에 실패하였습니다.");
					}
				}
			});
			
		});
	});
	function loadPopup(button, popup, onload){
		$(button).click(function(e){
				$(popup).removeClass('hidden');
				setTimeout(function() { $(popup).addClass('active'); }, 300);
				onload(e);
			});

			$(popup + ' .jw_overlay, ' + popup + ' .jw_modal_hd>div').click(function(){
			$(popup).removeClass('active');
			setTimeout(function() { $(popup).addClass('hidden');}, 300);
		});
	}
</script>
@endsection