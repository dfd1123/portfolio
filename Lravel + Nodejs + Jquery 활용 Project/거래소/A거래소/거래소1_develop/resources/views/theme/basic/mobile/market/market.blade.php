@extends(session('theme').'.mobile.layouts.app') @section('content')

<input type="hidden" name="coin_api" value="{{$trade_coin->apiname}}" />
<input type="hidden" name="coin_apiname" value="{{$trade_coin->api}}" />
<input type="hidden" name="setting_url" value="{{$market->url}}chart" />
<input type="hidden" name="decimal_usd" value="{{$trade_coin->decimal_usd}}" />
<input type="hidden" name="call_unit" value="{{$trade_coin->call_unit}}" />

<div class="m_hd_title">
    <div class="inner">
    {{ __('market.market') }}
    </div>
</div>

<!-- 코인선택바 -->
<div class="coin_select_bar" id="coin_slt_bar">
    <label for="list_check">
        <p class="select_tit">
            <img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($trade_coin->image)) }}" alt="coin_img" class="coin_symbol"/>
            {{__('coin_name.'.$trade_coin->api)}}
            <span class="currency">{{$trade_coin->symbol}} /  {{ __('market.krw') }}</span>
        </p>
    </label>
    <input id="list_check" type="checkbox" class="hide"/>
    <div class="market_list_n_coin_list">
        {{-- 
        FIXME: BTC마켓탭/ETH마켓탭 주석
            <ul class="market_list_tab">
            <li class="active">
                <label for="usdc_market_list">USDC마켓</label>
            </li> 
            <li>
                <label for="btc_market_list">BTC마켓</label>
            </li> 
            <li>
                <label for="eth_market_list">ETH마켓</label>
            </li> 
        </ul> 
        --}}
        <input id="usdc_market_list" class="hide" type="radio" name="coin_list" />
        <input id="btc_market_list" class="hide" type="radio" name="coin_list" />
        <input id="eth_market_list" class="hide" type="radio" name="coin_list" />
        <ul class="select_list coin_list-1">
            @foreach($coins as $coin)
            <li>
                <a href="{{route('market',$coin->symbol)}}">
                    <img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image)) }}" alt="coin_img" class="coin_symbol"/>
                    {{__('coin_name.'.$coin->api)}}({{$coin->symbol}}/USDC)
                </a>
            </li>
            @endforeach
        </ul>
        
        {{--
        FIXME: BTC마켓리스트 주석
        <ul class="select_list coin_list-2">
            @foreach($coins as $coin)
            <li>
                <a href="{{route('market',$coin->symbol)}}">
                    <img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image)) }}" alt="coin_img" class="coin_symbol"/>
                    {{__('coin_name.'.$coin->api)}}({{$coin->symbol}}/BTC)
                </a>
            </li>
            @endforeach
        </ul>
        
        FIXME: ETH마켓리스트 주석
        <ul class="select_list coin_list-3">
            @foreach($coins as $coin)
            <li>
                <a href="{{route('market',$coin->symbol)}}">
                    <img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image)) }}" alt="coin_img" class="coin_symbol"/>
                    {{__('coin_name.'.$coin->api)}}({{$coin->symbol}}/ETH)
                </a>
            </li>
            @endforeach
        </ul> 
        --}}
        
    </div>
</div>
<!-- //코인선택바 -->

<!-- 코인 종가 및 전일대비 등 정보영역 -->
<div id="top_ticker" class="coin_num_data" data-coin="{{$trade_coin->api}}">
    <p class="first_line {{$up_down_color}}">
        <span class="last_trade_price {{$up_down_color}}">{{number_format($trade_coin->last_trade_price_usd, $trade_coin->decimal_usd)}}</span>
        <span class="currency">{{ __('market.krw') }</span>
    </p>
    <div class="mini_chart">미니차트</div>       
        


    <p class="second_line {{$up_down_color}}">
        <span class="tit">{{ __('market.yesterday') }}</span>
        <span class="price_change_24h {{$up_down_color}}">{{(float)$trade_coin->price_change_24h > 0 ? '+' : ''}}{{number_format($trade_coin->price_change_24h, $trade_coin->decimal_usd)}}</span>
        <span class="percent_change_24h {{$up_down_color}}">({{((float)$trade_coin->percent_change_24h == 0) ? '' : ((float)$trade_coin->percent_change_24h > 0 ? '▲' : '▼')}}{{number_format($trade_coin->percent_change_24h, 2)}}%)</span>
        <label for="view_24h_ul_2" class="view_24h_label">
        {{ __('market.see') }}
        </label>
    </p>

    <input type="checkbox" class="hide" id="view_24h_ul_2" />

    {{-- 24시간 거래량보기 --}}
    <ul class="view_24h_list">
        <li>
            <a href="#">
                <p>{{ __('market.high') }}</p>
                <span class="high_price_24h"><b class="number_price">{{number_format($trade_coin->max_price, $trade_coin->decimal_usd)}}</b></span>
            </a>
        </li>
        <li>
            <a href="#">
                <p>{{ __('market.low') }}</p>
                <span class="row_price_24h"><b class="number_price">{{number_format($trade_coin->min_price, $trade_coin->decimal_usd)}}</b></span>
            </a>
        </li>
        <li>
            <a href="#">
                <p>{{ __('market.volume') }}</p>
                <span class="trade_volume_24h"> <b class="number_price">{{number_format($trade_coin->{'24h_volume'},8)}}</b></span>
            </a>
        </li>
    </ul>
    {{-- //24시간 거래량보기 --}}
    
</div>
<!-- //코인 종가 및 전일대비 등 정보영역 -->

<!--START 1.주문-->
<div class="m_tab_menu_con trans_order_con">

    <!-- 하단의 스크롤영역 (주문창) -->
    <div class="bottom_wrap trade_wrap">
        <div class="left_con">
            <div class="up_wrap wait_wrap">
                <ul id="sell_wait" class="sell_wait_ul wait_ul">
                    @foreach($sell_adss->reverse() as $sell_ads) {{-- CSS 속성 적용을 위해 역순으로 출력 --}}
                    <li data-price="{{round($sell_ads->price, $trade_coin->decimal_usd)}}" data-amt="{{round($sell_ads->amt, 8)}}">
                        <span class="orderbook_price price_span">{{number_format($sell_ads->price, $trade_coin->decimal_usd)}}</span>
                        <span class="orderbook_amt amt_span">{{number_format($sell_ads->amt, 8)}}</span>
                        <div class="per_bar"></div>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="sell_wait_tit wait_tit">{{ __('market.pd') }}</div>

            <div class="tratbl_total_box">
                <span id="orderbook_middle" class="{{$last_trade_kind}}">-</span>
            </div>

            <div class="buy_wait_tit wait_tit">{{ __('market.gd') }}</div>

            <div class="down_wrap wait_wrap">
                <ul id="buy_wait" class="buy_wait_ul wait_ul">
                    @foreach($buy_adss as $buy_ads)
                    <li data-price="{{round($buy_ads->price, $trade_coin->decimal_usd)}}" data-amt="{{round($buy_ads->amt, 8)}}">
                        <span class="orderbook_price price_span">{{number_format($buy_ads->price, $trade_coin->decimal_usd)}}</span>
                        <span class="orderbook_amt amt_span">{{number_format($buy_ads->amt, 8)}}</span>
                        <div class="per_bar"></div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="right_con">
            <div class="trade_hd">
                <ul>
                    <li class="li_buy active">
                    {{ __('market.ms') }}<i class="fal fa-angle-down"></i>
                    </li>
                    <li class="li_sell">
                    {{ __('market.md') }}<i class="fal fa-angle-down"></i>
                    </li>
                </ul>
            </div>

            <div id="coin_buy_con" class="deal_con coin_buy_con">
                <input type="hidden" name="my_asset_cash" value="{{$user_current_cash_balance}}" />
                <span class="balance_state">
                    {{ __('market.buying_available') }} <u>{{number_format($user_current_cash_balance,$trade_coin->decimal_usd)}} {{ __('market.krw') }</u>
                </span>

                <div class="buysell_wrap">
                    <table class="buysell_table">
                        <tbody>
                            <tr class="form-group">
                                <td>
                                    <label>{{ __('market.price_krw') }}</label>
                                    <input type="number" id="buy_coin_price" class="form-control mb-2 coin_price buysell_price_inp" data-decimal="{{$trade_coin->decimal_usd}}" value="{{round($trade_coin->last_trade_price_usd, $trade_coin->decimal_usd)}}" placeholder="0" />
                                    <span class="currency">{{ __('market.krw') }</span>
                                </td>
                            </tr>

                            <tr class="form-group updown_btns">
                                <td>
                                    <ul class="mb-2">
                                        <li class="up_btn updown_btn" data-dealtype="buy">
                                            <button>+</button>
                                        </li>
                                        <li class="down_btn updown_btn" data-dealtype="buy">
                                            <button>-</button>
                                        </li>
                                    </ul>
                                </td>
                            </tr>

                            <tr class="percent_btns">
                                <td>
                                    <ul class="buy_percent mb-3">
                                        <li>
                                            <button>10%</button>
                                        </li>
                                        <li>
                                            <button>25%</button>
                                        </li>
                                        <li>
                                            <button>50%</button>
                                        </li>
                                        <li>
                                            <button>100%</button>
                                        </li>
                                    </ul>
                                </td>
                            </tr>

                            <tr class="form-group">
                                <td>
                                    <label>{{ __('market.quantity') }}</label>
                                    <input type="number" id="buy_max_amount" class="form-control" value="" placeholder="0" />
                                    <span class="currency">{{$trade_coin->symbol}}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--//buysell_wrap-->

                <div class="total_sum_wrap mt-3 mb-3">
                    <span class="label">{{ __('market.all_buy_price') }}</span>

                    <p>
                        <b id="buy_cash_amt">0.00</b>
                        <span class="currency">{{ __('market.krw') }</span>
                    </p>
                </div>

                <div class="buysell_btn buy_btn">
                    @auth
                        @if(Auth::user()->status == 2)
                            <button type="button" class="not_active_btn btn_style stop_user_id_warning">계정 정지</button>
                        @else
                            @if($security_lv < 2)
                                @if($security_lv == 0)
                                    <a class="not_active_btn btn_style" href="{{route('mypage.security_setting')}}">{{ __('market.email') }}</a>
                                @else
                                    <a class="not_active_btn btn_style" href="{{route('mypage.security_setting')}}">{{ __('market.phone') }}</a>
                                @endif
                            @else
                                <button type="button" class="btn_style" onclick="buysell_coin_data('buy','USD','{{$trade_coin->symbol}}');">{{ __('market.buying') }}</button>
                            @endif
                        @endif
                    @else
                        <a class="not_active_btn btn_style" href="{{route('login')}}">{{ __('market.login') }}</a>
                    @endauth
                </div>
            </div>
            <!--//coin_buy_con 구매-->

            <div id="coin_sell_con" class="deal_con coin_sell_con hide">
                <input type="hidden" name="my_asset_coin" value="{{$user_current_coin_balance}}" />
                <span id="use_balance_sell" class="balance_state">
                {{$trade_coin->symbol}} {{ __('market.krw') }} : 
                <u>{{number_format($user_current_coin_balance,8)}}</u> {{$trade_coin->symbol}}
                </span>

                <div class="buysell_wrap">
                    <table class="buysell_table">
                        <tbody>
                            <tr class="form-group">
                                <td>
                                    <label>{{ __('market.price') }}</label>
                                    <input type="number" id="sell_coin_price" class="form-control mb-2 coin_price buysell_price_inp" data-decimal="{{$trade_coin->decimal_usd}}" value="{{round($trade_coin->last_trade_price_usd, $trade_coin->decimal_usd)}}" placeholder="0" />
                                    <span class="currency">{{ __('market.krw') }</span>
                                </td>
                            </tr>

                            <tr class="form-group updown_btns">
                                <td>
                                    <ul class="mb-2">
                                        <li class="up_btn updown_btn" data-dealtype="sell">
                                            <button>+</button>
                                        </li>
                                        <li class="down_btn updown_btn" data-dealtype="sell">
                                            <button>-</button>
                                        </li>
                                    </ul>
                                </td>
                            </tr>

                            <tr class="percent_btns">
                                <td>
                                    <ul class="sell_percent mb-3">
                                        <li>
                                            <button>10%</button>
                                        </li>
                                        <li>
                                            <button>25%</button>
                                        </li>
                                        <li>
                                            <button>50%</button>
                                        </li>
                                        <li>
                                            <button>100%</button>
                                        </li>
                                    </ul>
                                </td>
                            </tr>

                            <tr class="form-group">
                                <td>
                                    <label>{{ __('market.quantity') }}</label>
                                    <input type="number" id="sell_max_amount" class="form-control" value="" placeholder="0" />
                                    <span class="currency">{{$trade_coin->symbol}}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--//buysell_wrap-->

                <div class="total_sum_wrap mt-3 mb-3">
                    <span class="label">{{ __('market.all_sell_price') }}</span>

                    <p>
                        <b id="sell_cash_amt">0.00</b>
                        <span class="currency">{{ __('market.krw') }</span>
                    </p>
                </div>

                <div class="buysell_btn sell_btn">
                    @auth
                        @if(Auth::user()->status == 2)
                            <button type="button" class="not_active_btn btn_style stop_user_id_warning">계정 정지</button>
                        @else
                            @if($security_lv < 2)
                                @if($security_lv == 0)
                                    <a class="not_active_btn btn_style" href="{{route('verification.notice')}}">{{ __('market.email') }}</a>
                                @else
                                    <a class="not_active_btn btn_style" href="{{route('security_lv.index')}}">{{ __('market.phone') }}</a>
                                @endif
                            @else
                                <button type="button" class="btn_style" onclick="buysell_coin_data('sell','USD','{{$trade_coin->symbol}}');">{{ __('market.selling') }}</button>
                            @endif
                        @endif
                    @else
                        <a class="not_active_btn btn_style" href="{{route('login')}}">{{ __('market.login') }}</a>
                    @endauth
                </div>
            </div>
            <!--//coin_sell_con 구매-->
        </div>
        <!--//right_con-->
    </div>
    <!-- 하단의 스크롤영역 (주문창) -->
</div>
<!--END 1.주문-->

<!--START 2.호가-->
<div class="m_tab_menu_con hide asking_price_con">
    <!-- 상단 (탭메뉴) -->
    <div class="m_tab_list" id="asking_price_tab">
        <ul>
            <li class="active">
                <a href="#">
                    <label for="total_wait_check">
                    {{ __('market.all') }}
                    </label>
                </a>
            </li>
            <li>
                <a href="#">
                    <label for="sell_wait_check">
                    {{ __('market.pd') }}
                    </label>
                </a>
            </li>
            <li>
                <a href="#">
                    <label for="buy_wait_check">
                    {{ __('market.gd') }}
                    </label>
                </a>
            </li>
        </ul>
    </div>
    <!-- //상단 (탭메뉴) -->

    <!-- 하단의 스크롤영역 (테이블) -->
    <div class="bottom_wrap table_wrap">
        
        <table class="table_label">
            <thead>
                <tr>
                    <th>{{ __('market.price_krw') }}</th>
                    <th>{{ __('market.quantity') }}({{$trade_coin->symbol}})</th>
                    <th>{{ __('market.total_amount') }}</th>
                </tr>
            </thead>
        </table>
        
        <input id="total_wait_check" type="radio" name="wait_check" class="hide"/>
        <input id="sell_wait_check" type="radio" name="wait_check" class="hide"/>
        <input id="buy_wait_check" type="radio" name="wait_check" class="hide"/>
        
        {{-- 호가창 (판매대기) --}}
        <div class="up updownarea">
            <div class="inner">
                <ul id="sell_wait2">
                    @foreach($sell_adss as $sell_ads)
                        <li data-price="{{round($sell_ads->price, $trade_coin->decimal_usd)}}" data-amt="{{round($sell_ads->amt, 8)}}">
                            <span class="orderbook_price">
                                {{number_format($sell_ads->price, $trade_coin->decimal_usd)}}
                            </span>
                            <span class="orderbook_amt">
                                {{number_format($sell_ads->amt, 8)}}
                            </span>
                            <span>
                                {{number_format(($sell_ads->price * $sell_ads->amt), $trade_coin->decimal_usd)}}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        {{-- //호가창 (판매대기) --}}
        
        <div id="orderbook_middle2" class="total_sum blue {{$last_trade_kind}}">-</div>
        
        {{-- 호가창 (구매대기) --}}
        <div class="down updownarea">
            <div class="inner">
                <ul id="buy_wait2">
                    @foreach($buy_adss as $buy_ads)
                        <li data-price="{{round($buy_ads->price, $trade_coin->decimal_usd)}}" data-amt="{{round($buy_ads->amt, 8)}}">
                            <span class="orderbook_price">
                                {{number_format($buy_ads->price, $trade_coin->decimal_usd)}}
                            </span>
                            <span class="orderbook_amt">
                                {{number_format($buy_ads->amt, 8)}}
                            </span>
                            <span>
                                {{number_format(($buy_ads->price * $buy_ads->amt), $trade_coin->decimal_usd)}}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        {{-- //호가창 (구매대기) --}}
        
    </div>
    <!-- //하단의 스크롤영역 (테이블) -->
</div>
<!--END 2.호가-->

<!--START 3.차트-->
<div class="m_tab_menu_con hide market_chart_con">

    <!-- 하단의 스크롤영역 (차트창) -->
    <div class="bottom_wrap trade_wrap">
        <input type="hidden" name="trade_fee" value="{{$market->buy_comission/100}}" />
        <div id="chartdiv" class="chart_con">
        </div>
    </div>
    <!-- //하단의 스크롤영역 (차트창) -->
    
</div>
<!--END 3.차트-->

<!--START 4.시세-->
<div class="m_tab_menu_con hide market_price_con">
    <!-- 상단없이 스크롤영역 (테이블) -->
    <div class="bottom_wrap table_wrap">
        <table class="table_label">
            <thead>
                <tr>
                    <th>{{ __('market.quantity') }}</th>
                    <th>{{ __('market.price_krw') }}</th>
                    <th>{{ __('market.time') }}</th>
                </tr>
            </thead>
        </table>

        <table class="coin_chart_tbl">
            <tbody id="trade_list">
                @foreach($trade_historys as $trade_history)
                    <tr class="{{ ($trade_history->last_trade_kind != null)? $trade_history->last_trade_kind:'' }}">
                        <td><span>{{number_format($trade_history->contract_coin_amt, 8)}}</span></td>
                        <td><span>{{number_format(bcmul($trade_history->contract_coin_amt, $trade_history->sell_coin_price, 2),2)}}</span></td>
                        @if(config('app.locale') == 'kr' || config('app.locale') == 'jp')
                            <td><span>{{date("m-d H:i:s", strtotime("+9 hours", strtotime($trade_history->created_dt)))}}</span></td>
                        @elseif(config('app.locale') == 'ch')
                            <td><span>{{date("m-d H:i:s", strtotime("+8 hours", strtotime($trade_history->created_dt)))}}/<span></td>
                        @elseif(config('app.locale') == 'th')
                            <td><span>{{date("m-d H:i:s", strtotime("+7 hours", strtotime($trade_history->created_dt)))}}</span></td>
                        @else
                            <td><span>{{date("m-d H:i:s", strtotime($trade_history->created_dt))}}</span></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- //상단없이 스크롤영역 (테이블) -->
</div>
<!--END 4.시세-->

<!--START 5.거래내역-->
<div class="m_tab_menu_con hide trans_history_con">
    <!-- 상단 (탭메뉴) -->
    <div class="m_tab_list" id="trans_history_tab">
        <ul>
            <li class="active">
                <a href="#">
                {{ __('market.wait_order') }}
                </a>
            </li>
            <li>
                <a href="#">
                {{ __('market.day_order') }}
                </a>
            </li>
        </ul>
    </div>
    <!-- //상단 (탭메뉴) -->

    <!-- 하단의 스크롤영역 1(대기주문) -->
    <div id="readyorder_wrap" class="wait_order_wrap bottom_wrap history_st">
        <ul id="ready_order_queue" class="list">
            @auth
                @forelse($wait_trades as $wait_trade)
                    <li class="con {{$wait_trade->type}}">
                        <p class="info _date">
                            <span>{{date("Y-m-d H:i:s", strtotime("+9 hours", strtotime($wait_trade->created_dt)))}}</span>
                            <span class="float-right type">{{__('market.'.$wait_trade->type)}}</span>
                        </p>
                        <p class="info _coin">
                        {{ __('coin_name.'.strtolower($wait_trade->cointype)) }}(<u>{{strtoupper($wait_trade->cointype)}}</u>)
                        </p>
                        <p class="info _amt">
                            <label>{{ __('market.quantity') }}</label>
                            <span class="point_clr_1">{{number_format($wait_trade->{$wait_trade->type.'_COIN_amt'},8)}}</span>
                            <span class="currency">{{strtoupper($wait_trade->cointype)}}</span>
                        </p>
                        <p class="info">
                            <label>{{ __('market.price') }}</label>
                            <span>{{number_format($wait_trade->{$wait_trade->type.'_coin_price'}, $trade_coin->decimal_usd)}}</span>
                            <span class="currency">{{ __('market.krw') }</span>
                        </p>
                        <p class="info">
                            <label>{{ __('market.all_amount') }}</label>
                            <span>{{number_format(($wait_trade->{$wait_trade->type.'_coin_price'} * $wait_trade->{$wait_trade->type.'_COIN_amt'}), $trade_coin->decimal_usd)}}</span>
                            <span class="currency">{{ __('market.krw') }</span>
                        </p>
                        <p class="info">
                            <label>{{ __('market.conclusion_rate') }}</label>
                            <span>{{number_format($wait_trade->trade_percentage,2)}}</span>
                            <span>%</span>
                        </p>
                        <p class="info">
                            <label>{{ __('market.now') }}</label>
                            @if($wait_trade->status == 'OnProgress')
                                <button type="submit" id="btc_cancel_request_{{$wait_trade->id}}" class="btc_cancel_request mr-1" data-id="{{$wait_trade->id}}" onclick="trade_cancel({{$wait_trade->id}})">
                                {{ __('market.order_cancel') }}
                                </button>
                            @endif
                            <span class="status_type">{{__('market.'.$wait_trade->status)}}</span>
                        </p>
                    </li>
                @empty
                    <li class="non_data"><img src="/images/icon_notice.svg" alt="" class="btn_notice">{{ __('market.no_wait') }}</li>
                @endforelse
            @else
                <li class="non_data"><img src="/images/icon_notice.svg" alt="" class="btn_notice">{{ __('market.no_wait') }}</li>
            @endauth
        </ul>
    </div>
    <!-- //하단의 스크롤영역 1(대기주문) -->

    <!-- 하단의 스크롤영역 2(24시간 주문내역) -->
    <div class="order_24h_wrap bottom_wrap hide history_st">
        <ul id="ready_history_queue" class="list">
            @auth
                @forelse($today_historys as $today_history)
                    @if($today_history->buyer_username == $username)
                        <li class="con buy">
                            <p class="info _date">
                                <span>{{date("Y-m-d H:i:s", strtotime("+9 hours", strtotime($today_history->created_dt)))}}</span>
                                <span class="float-right type">{{ __('market.buy') }}</span>
                            </p>
                            <p class="info _coin">
                                {{ __('coin_name.'.$today_history->cointype) }}(<u>{{strtoupper($today_history->cointype)}}</u>)
                            </p>
                            <p class="info _amt">
                                <label>{{ __('market.quantity') }}</label>
                                <span>{{number_format($today_history->contract_coin_amt, 8)}}</span>
                                <span class="currency">{{strtoupper($today_history->cointype)}}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('market.price') }}</label>
                                <span>{{number_format($today_history->buy_coin_price, $trade_coin->decimal_usd)}}</span>
                                <span class="currency">{{ __('market.krw') }</span>
                            </p>
                            <p class="info">
                                <label>{{ __('market.all_amount') }}</label>
                                <span>{{number_format($today_history->trade_usd_amt_buy, $trade_coin->decimal_usd)}}</span>
                                <span class="currency">{{ __('market.krw') }</span>
                            </p>
                        </li>
                    @else
                        <li class="con sell">
                            <p class="info _date">
                                <span>{{date("Y-m-d H:i:s", strtotime("+9 hours", strtotime($today_history->created_dt)))}}</span>
                                <span class="float-right type">{{ __('market.sell') }}</span>
                            </p>
                            <p class="info _coin">
                                {{ __('coin_name.'.$today_history->cointype) }}(<u>{{strtoupper($today_history->cointype)}}</u>)
                            </p>
                            <p class="info _amt">
                                <label>{{ __('market.quantity') }}</label>
                                <span>{{number_format($today_history->contract_coin_amt, 8)}}</span>
                                <span class="currency">{{strtoupper($today_history->cointype)}}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('market.price') }}</label>
                                <span>{{number_format($today_history->sell_coin_price, $trade_coin->decimal_usd)}}</span>
                                <span class="currency">{{ __('market.krw') }</span>
                            </p>
                            <p class="info">
                                <label>{{ __('market.all_amount') }}</label>
                                <span>{{number_format($today_history->trade_usd_amt_sell, $trade_coin->decimal_usd)}}</span>
                                <span class="currency">{{ __('market.krw') }</span>
                            </p>
                        </li>
                    @endif
                @empty
                    <li class="non_data">
                        <img src="/images/icon_notice.svg" alt="" class="btn_notice">
                        {{ __('market.no_today') }}
                    </li>
                @endforelse
            @else
                <li class="non_data">
                    <img src="/images/icon_notice.svg" alt="" class="btn_notice">
                    {{ __('market.no_today') }}
                </li>
            @endauth
        </ul>
    </div>
    <!-- //하단의 스크롤영역 2(24시간 주문내역) -->
</div>
<!--END 5.거래내역-->

<!-- 하단 고정 네비게이션 -->
<div class="m_tab_menu">
    <ul>
        <li>
            <a href="#">
            {{ __('market.my_balance') }}
            </a>
        </li>
        <li class="active">
            <a href="#">
            {{ __('market.order') }}
            </a>
        </li>
        <li>
            <a href="#">
            {!! __('market.m_hg') !!}
            </a>
        </li>
        <li>
            <a href="#">
            {{ __('market.ct') }}
            </a>
        </li>
        <li>
            <a href="#">
            {{ __('market.gr') }}
            </a>
        </li>
        <li>
            <a href="#">
            {!! __('market.m_gr') !!}
            </a>
        </li>
    </ul>
</div>
<!--// 하단 고정 네비게이션 -->

@endsection
