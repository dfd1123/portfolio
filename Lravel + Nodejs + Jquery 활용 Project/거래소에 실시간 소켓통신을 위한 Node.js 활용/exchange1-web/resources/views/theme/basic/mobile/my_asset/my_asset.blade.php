@extends(session('theme').'.mobile.layouts.app') 
@section('content')
<div class="m_hd_title">
    <div class="inner">
    {{ __('my_asset.asset') }}
    </div>
</div>

{{-- 내 자산 --}}
<div class="m_myasset_wrap">
    
    <!-- 상단 (탭메뉴) -->
    <div class="m_tab_list" id="my_ast_tab">
        <ul>
            <li class="active">
                <a href="#">
                    <label for="my_coin_check">
                    {{ __('my_asset.my_coin') }}
                    </label>
                </a>
            </li>
            <li>
                <a href="#">
                    <label for="trans_hitr_check">
                    {{ __('my_asset.trade_list') }}
                    </label>
                </a>
            </li>
            <li>
                <a href="#">
                    <label for="not_trans_check">
                    {{ __('my_asset.no') }}
                    </label>
                </a>
            </li>
        </ul>
    </div>
    <!-- //상단 (탭메뉴) -->

    <!-- bottom_wrap -->
    <div class="bottom_wrap">
        <input id="my_coin_check" type="radio" name="myast_view_con" class="hide" />
        <input id="trans_hitr_check" type="radio" name="myast_view_con" class="hide" />
        <input id="not_trans_check" type="radio" name="myast_view_con" class="hide" />

        {{-- START 1. 보유코인 --}}
        <div class="con_1 con_box">

            <!-- 안내문 및 보유코인만 볼 수 있는 버튼 -->
            <div class="sch_bar bb-dddd">
                <div class="coin_sch_bar">
                    <span>
                    {!! __('my_asset.property_sentence1_mb') !!}
                    </span>
                </div>
                <div class="coin_sch_checkbox">
                    <input id="my_coin" type="checkbox" class="grayCheckbox hide" />
                    <label for="my_coin">&nbsp;{{ __('my_asset.my_coin') }}</label>
                </div>
            </div>
            <!-- //안내문 및 보유코인만 볼 수 있는 버튼  -->

            {{-- 스크롤영역 --}}
            <div class="scrl_wrap">
                
                <!-- 내 자산 상태 -->
                <div class="status_bar">
                    
                    <div class="total_ast">
                        <p class="text-right">
                            <label class="float-left tit">{{ __('my_asset.my_ucss') }}</label>
                            <span class="point_clr_1">{{ number_format($coin_balance_usd,8) }}</span>
                            <span class="currency">UCSS</span>
                        </p>
                        <p class="text-right">
                            <label class="float-left tit">{{ __('my_asset.all_my_money') }}</label>
                            <span class="point_clr_1">{{ number_format($total_asset,8) }}</span>
                            <span class="currency">UCSS</span>
                        </p>
                    </div>

                    <ul class="my_ast_list bb-dddd">
                        <li class="bt-f0f0 pt-2">
                            <label>{{ __('my_asset.all_buy_price') }}</label>
                            <span>{{ number_format($total_buying,8) }}</span><span class="currency">UCSS</span>
                        </li>
                        <li class="bt-f0f0 pt-2">
                            <label>{{ __('my_asset.all_rating_revenue') }}</label>
                            <span>{{ number_format($total_eval_revenue,8) }}</span><span class="currency">UCSS</span>
                        </li>
                        <li>
                            <label>{{ __('my_asset.all_rating_price') }}</label>
                            <span>{{ number_format($total_holding,8) }}</span><span class="currency">UCSS</span>
                        </li>
                        <li>
                            <label>{{ __('my_asset.all_rating_yield') }}</label>
                            <span class="{{ $total_eval_percent >= 0 ? 'red' : 'blue'  }}">{{ number_format($total_eval_percent,2) }} %</span>
                            <!-- 오르면 red 하고 + 붙히고 떨어지면 blue 하고 - 붙혀주세요 -->
                        </li>
                    </ul>
                    
                </div>
                <!-- //내 자산 상태 -->
                
				@foreach($coins as $coin)
                    @if($coin->symbol != config('app.default_cash'))
                    
	                <!-- 코인리스트박스 -->
	                <div class="my_coin_list_con {{ $result[$coin->api]['balance'] > 0 ? 'exist_balance' : '' }}" >
	
	                    <div class="hd dp_table ">
	
	                        <div class="dp_table_cell _left"  onclick="location.href='{{ route('marketUCSS',$coin->api) }}'">
	                            <img src="{{ asset('/storage/image/homepage/coin_img/'.$coin->api.'.png') }}" alt="coin_img" class="coin_symbol"/>
	                            <p class="pt-1">{{ __('coin_name.'.$coin->api) }}</p>
                                <span class="currency">{{ $coin->symbol }}/UCSS</span> 
                                <i class="fal fa-angle-right point_clr_2"></i>
	                        </div>
	
	                        <div class="dp_table_cell _right">
	                            <p class="pt-1">{{ __('my_asset.my_quantity') }}</p>
	                            <span><b class="point_clr_1">{{ number_format($result[$coin->api]['balance'],8) }}</b> <b class="currency">{{ $coin->symbol }}</b></span>
	                        </div>
	
	                    </div>
	
	                    <ul class="my_ast_list bb-dddd">
	                        <li class="bt-f0f0 pt-2">
	                            <label>{{ __('my_asset.buy_average') }}</label>
                                <span>{{ number_format($result[$coin->api]['avg'],8) }}</span><span class="currency">UCSS</span>
	                        </li>
	                        <li class="bt-f0f0 pt-2">
	                            <label>{{ __('my_asset.buy_price') }}</label>
	                            <span>{{ number_format($result[$coin->api]['buying'],8) }}</span><span class="currency">UCSS</span>
	                        </li>
	                        <li>
	                            <label>{{ __('my_asset.rating_price') }}</label>
	                            <span>{{ number_format($result[$coin->api]['eval'],8) }}</span><span class="currency">UCSS</span>
	                        </li>
	                        <li>
	                            <label>{{ __('my_asset.rating_profit_and_loss') }}</label>
	                            <span class="{{ $result[$coin->api]['eval_percent'] >= 0 ? 'red' : 'blue'  }}">{{ number_format($result[$coin->api]['eval_percent'],2) }} %</span>
	                            <!-- 오르면 red 하고 + 붙히고 떨어지면 blue 하고 - 붙혀주세요 -->
	                        </li>
	                    </ul>
	
	                </div>
                    <!-- //코인리스트박스 -->
                    
					@endif
				@endforeach
            </div>

        </div>
        {{-- //END 1. 보유코인 --}}

        {{-- START 2. 거래내역 --}}
        <div class="con_2 con_box">

            <!-- 조회 -->
            <div class="sch_option_wrap">
                
                <div class="option_bar bb-dddd">
                    <label for="option_group_check">
                        <span>{{ __('my_asset.viewdate')}}</span>
                        <i class="fal fa-sliders-h"></i>
                    </label>
                </div>
                
                <input id="option_group_check" type="checkbox" class="hide">
                
                <div class="option_group">
                    <div class="inner_line mb-2">
                        <button class="btns" onclick="input_calendar_data(7);">{{ __('my_asset.one_week') }}</button>
                        <button class="btns" onclick="input_calendar_data(14);">{{ __('my_asset.two_weeks') }}</button>
                        <button class="btns" onclick="input_calendar_data(30);">{{ __('my_asset.one_month') }}</button>
                    </div>
                    <div class="inner_line mb-2">
                        <input class="input" type="text" id="start_sch" readonly="readonly">
                        <span>~</span>
                        <input class="input"  type="text" id="end_sch" readonly="readonly">
                    </div>
                    <div class="inner_line">
                        <button class="btn_style" onclick="search_date_history();">{{ __('my_asset.lookup') }}</button>
                    </div>
                </div>
                
            </div>
            
			<!-- 검색바 -->
            <div class="sch_bar">
                <div class="coin_sch_bar">
                    <div class="inner">
                        <input type="text" placeholder="{{ __('my_asset.search_coin') }}" id="txtFilter_history" />
                        <i class="sch_icon"></i>
                    </div>
                </div>
                <div class="coin_sch_checkbox">
                    <select>
                            <option value="5">{{ __('my_asset.all_trade') }}</option>
                            <option value="1">{{ __('my_asset.gm') }}</option>
                            <option value="0">{{ __('my_asset.pm') }}</option>
                        </select>
                </div>
            </div>
            <!-- //검색바 -->

            <div class="scrl_wrap" id="history_scroll">

                <!-- 거래내역 -->
                <div class="history_st">
                    <ul class="list" id="history_lists">
                    @forelse($historys as $history)
                    	@if($history->buyer_uid == $history->seller_uid)
                    	<li class="con self" name="{{__('coin_name.'.$history->cointype)}}/{{$history->cointype}}">
                            <p class="info _date mb-2">
                                <span>{{ $history->created_dt }}</span>
                                <span class="float-right type">{{ __('my_asset.self_trade') }}</span>
                            </p>
                            <p class="info _coin">
                                <span>{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'UCSS' }}마켓</span>
                                <span>{{ __('coin_name.'.strtolower($history->cointype)) }}(<u>{{ strtoupper($history->cointype) }}</u>)</span>
                            </p>
                            <p class="info _amt">
                                <label>{{ __('my_asset.trade_quantity') }}</label>
                                <span class="point_clr_1">{{ number_format($history->contract_coin_amt,8) }}</span>
                                <span class="currency">{{ strtoupper($history->cointype) }}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('my_asset.trade_unit_price') }}</label>
                                <span>{{ number_format($history->buy_coin_price, $history->{'decimal_'.strtolower($history->currency)}) }}</span>
                                <span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'UCSS' }}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('my_asset.trade_price') }}</label>
                                <span>{{ number_format($history->trade_total_buy, $history->{'decimal_'.strtolower($history->currency)}) }}</span>
                                <span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'UCSS' }}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('my_asset.fees') }}</label>
                                <span>{{ number_format(bcmul(bcmul($history->trade_total_buy,$setting->buy_comission,$history->{'decimal_'.strtolower($history->currency)} + 4),"0.01",$history->{'decimal_'.strtolower($history->currency)} + 4), $history->{'decimal_'.strtolower($history->currency)}) }}</span>
                                <span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'UCSS' }}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('my_asset.settlement_amount_mb') }}</label>
                                <span>{{ number_format(bcadd(bcmul(bcmul($history->trade_total_buy,$setting->buy_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}),$history->trade_total_buy,$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)}) }}</span>
                                <span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'UCSS' }}</span>
                            </p>
                        </li>
						@elseif($history->buyer_uid == Auth::user()->id)
                        <li class="con buy"  name="{{__('coin_name.'.$history->cointype)}}/{{$history->cointype}}">
                            <p class="info _date mb-2">
                                <span>{{ $history->created_dt }}</span>
                                <span class="float-right type">{{ __('my_asset.gm') }}</span>
                            </p>
                            <p class="info _coin">
                                <span>{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'UCSS' }}마켓</span>
                                <span>{{ __('coin_name.'.strtolower($history->cointype)) }}(<u>{{ strtoupper($history->cointype) }}</u>)</span>
                            </p>
                            <p class="info _amt">
                                <label>{{ __('my_asset.trade_quantity') }}</label>
                                <span class="point_clr_1">{{ number_format($history->contract_coin_amt,8) }}</span>
                                <span class="currency">{{ strtoupper($history->cointype) }}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('my_asset.trade_unit_price') }}</label>
                                <span>{{ number_format($history->buy_coin_price, $history->{'decimal_'.strtolower($history->currency)}) }}</span>
                                <span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'UCSS' }}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('my_asset.trade_price') }}</label>
                                <span>{{ number_format($history->trade_total_buy, $history->{'decimal_'.strtolower($history->currency)}) }}</span>
                                <span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'UCSS' }}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('my_asset.fees') }}</label>
                                <span>{{ number_format(bcmul(bcmul($history->trade_total_buy,$setting->buy_comission,$history->{'decimal_'.strtolower($history->currency)} + 4),"0.01",$history->{'decimal_'.strtolower($history->currency)} + 4), $history->{'decimal_'.strtolower($history->currency)}) }}</span>
                                <span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'UCSS' }}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('my_asset.settlement_amount_mb') }}</label>
                                <span>{{ number_format(bcadd(bcmul(bcmul($history->trade_total_buy,$setting->buy_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}),$history->trade_total_buy,$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)}) }}</span>
                                <span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'UCSS' }}</span>
                            </p>
                        </li>
                        @else
                        <li class="con sell"  name="{{__('coin_name.'.$history->cointype)}}/{{$history->cointype}}">
                            <p class="info _date mb-2">
                                <span>{{ $history->created_dt }}</span>
                                <span class="float-right type">{{ __('my_asset.pm') }}</span>
                            </p>
                            <p class="info _coin">
                            	<span>{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'UCSS' }}마켓</span>
                                <span>{{ __('coin_name.'.strtolower($history->cointype)) }}(<u>{{ strtoupper($history->cointype) }}</u>)</span>
                            </p>
                            <p class="info _amt">
                                <label>{{ __('my_asset.trade_quantity') }}</label>
                                <span class="point_clr_1">{{ number_format($history->contract_coin_amt,8) }}</span>
                                <span class="currency">BTC</span>
                            </p>
                            <p class="info">
                                <label>{{ __('my_asset.trade_unit_price') }}</label>
                                <span>{{ number_format($history->sell_coin_price, $history->{'decimal_'.strtolower($history->currency)}) }}</span>
                                <span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'UCSS' }}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('my_asset.trade_price') }}</label>
                                <span>{{ number_format(bcadd($history->trade_total_sell,bcmul(bcmul($history->trade_total_sell,$setting->sell_comission,$history->{'decimal_'.strtolower($history->currency)} + 4),"0.01",$history->{'decimal_'.strtolower($history->currency)} + 4), $history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)}) }}</span>
                                <span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'UCSS' }}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('my_asset.fees') }}</label>
                                <span>{{ number_format(bcmul(bcmul($history->trade_total_sell,$setting->sell_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)}) }}</span>
                                <span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'UCSS' }}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('my_asset.settlement_amount_mb') }}</label>
                                <span>{{ number_format($history->trade_total_sell, $history->{'decimal_'.strtolower($history->currency)}) }}</span>
                                <span class="currency">{{ strtoupper($history->currency) != 'USD' ? strtoupper($history->currency) : 'UCSS' }}</span>
                            </p>
                        </li>
                        @endif
					@empty
						<li class="non_data">
							<i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('my_asset.property_sentence2') }}
						</li>
					@endforelse
                    </ul>
                </div>
                <!-- 거래내역 -->

            </div>

        </div>
        {{-- //END 2. 거래내역 --}}

        <div class="con_3 con_box">

            <div class="scrl_wrap" id="wait_scroll">

                <!-- 미체결 -->
                <div class="history_st">
                    <ul class="list" id = "not_concluded_lists">
                    	@forelse($wait_trades as $wait_trade)
							@if($wait_trade->type == 'buy')
	                        <li class="con buy">
	                            <p class="info _date mb-2">
	                                <span>{{ $wait_trade->created_dt }}</span>
	                                <span class="float-right type">{{ __('my_asset.gm') }}</span>
	                            </p>
	                            <p class="info _coin">
	                            	<span>{{ strtoupper($wait_trade->currency) != 'USD' ? strtoupper($wait_trade->currency) : 'UCSS' }}마켓</span>
	                                <span>{{ __('coin_name.'.strtolower($wait_trade->cointype)) }}(<u>{{ strtoupper($wait_trade->cointype) }}</u>)</span>
	                            </p>
	                            <p class="info _amt">
	                            	<label>{{ __('my_asset.order_price') }}</label>
	                                <span class="point_clr_1">{{ number_format($wait_trade->buy_coin_price,$wait_trade->{'decimal_'.strtolower($wait_trade->currency)}) }}</span>
	                                <span class="currency">{{ strtoupper($wait_trade->currency) != 'USD' ? strtoupper($wait_trade->currency) : 'UCSS' }}</span>
	                            </p>
	                            <p class="info">
	                                <label>{{ __('my_asset.order_quntity') }}</label>
	                                <span>{{ number_format($wait_trade->buy_COIN_amt_total,8) }}</span>
	                                <span class="currency">{{ strtoupper($wait_trade->cointype) }}</span>
	                            </p>
	                            <p class="info">
	                                <label>{{ __('my_asset.conclusion_quantity') }}</label>
	                                <span>{{ number_format($wait_trade->buy_COIN_amt_finished,8) }}</span>
	                                <span class="currency">{{ strtoupper($wait_trade->cointype) }}</span>
	                            </p>
	                            <p class="info">
	                                <label>{{ __('my_asset.not_conclusion_quantity') }}</label>
	                                <span>{{ number_format($wait_trade->buy_COIN_amt,8) }}</span>
	                                <span class="currency">{{ strtoupper($wait_trade->cointype) }}</span>
	                            </p>
	                            <p class="info">
	                                <label>{{ __('my_asset.now') }}</label>
	                                <span>
	                                    <button type="button" id="btc_cancel_request_{{$wait_trade->id}}" data-id="{{$wait_trade->id}}" onclick="myasset_trade_cancel({{$wait_trade->id}})">{{ __('my_asset.cancel_trade') }}</button>
	                                </span>
	                            </p>
	                        </li>
                        	@else
	                        <li class="con sell">
	                            <p class="info _date mb-2">
	                                <span>{{ $wait_trade->created_dt }}</span>
	                                <span class="float-right type">{{ __('my_asset.pm') }}</span>
	                            </p>
	                            <p class="info _coin">
	                            	<span>{{ strtoupper($wait_trade->currency) != 'USD' ? strtoupper($wait_trade->currency) : 'UCSS' }}마켓</span>
                                	<span>{{ __('coin_name.'.strtolower($wait_trade->cointype)) }}(<u>{{ strtoupper($wait_trade->cointype) }}</u>)</span>
	                            </p>
	                            <p class="info _amt">
	                                <label>{{ __('my_asset.order_price') }}</label>
	                                <span class="point_clr_1">{{ number_format($wait_trade->sell_coin_price,$wait_trade->{'decimal_'.strtolower($wait_trade->currency)}) }}</span>
	                                <span class="currency">{{ strtoupper($wait_trade->currency) != 'USD' ? strtoupper($wait_trade->currency) : 'UCSS' }}</span>
	                            </p>
	                            <p class="info">
	                                <label>{{ __('my_asset.order_quntity') }}</label>
	                                <span>{{ number_format($wait_trade->sell_COIN_amt_total,8) }}</span>
	                                <span class="currency">{{ strtoupper($wait_trade->cointype) }}</span>
	                            </p>
	                            <p class="info">
	                                <label>{{ __('my_asset.conclusion_quantity') }}</label>
	                                <span>{{ number_format($wait_trade->sell_COIN_amt_finished,8) }}</span>
	                                <span class="currency">{{ strtoupper($wait_trade->cointype) }}</span>
	                            </p>
	                            <p class="info">
	                                <label>{{ __('my_asset.not_conclusion_quantity') }}</label>
	                                <span>{{ number_format($wait_trade->sell_COIN_amt,8) }}</span>
	                                <span class="currency">{{ strtoupper($wait_trade->cointype) }}</span>
	                            </p>
	                            <p class="info">
	                                <label>{{ __('my_asset.now') }}</label>
	                                <span>
	                                    <button type="button" id="btc_cancel_request_{{$wait_trade->id}}" data-id="{{$wait_trade->id}}" onclick="myasset_trade_cancel({{$wait_trade->id}})">{{ __('my_asset.cancel_trade') }}</button>
	                                </span>
	                            </p>
	                        </li>
                        	@endif
						@empty
							<li class="non_data">
								<i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('my_asset.property_sentence3') }}
							</li>
						@endforelse
                    </ul>
                </div>
                <!-- 거래내역 -->

            </div>

        </div>
    </div>



</div>
<script>
    if (typeof __ === 'undefined') { var __ = {}; }
    __.my_asset = {
        @foreach(__('my_asset') as $key => $value)
            '{{$key}}':'{{$value}}',
        @endforeach
    }
</script>
@endsection