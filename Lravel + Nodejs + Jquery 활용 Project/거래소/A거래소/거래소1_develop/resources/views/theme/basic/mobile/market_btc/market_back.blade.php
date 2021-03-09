@extends(session('theme').'.mobile.layouts.app') @section('content')

<input type="hidden" name="standard_api" value="{{ $standard_info->api }}" />
<input type="hidden" name="coin_api" value="{{$trade_coin->apiname}}" />
<input type="hidden" name="coin_apiname" value="{{$trade_coin->api}}" />
<input type="hidden" name="setting_url" value="{{$market->url}}chart_new" />
<input type="hidden" name="chart_symbol" value="{{$trade_coin->api}}/{{ $symbol_text }}" />
<input type="hidden" name="decimal_usd" value="{{ $trade_coin->{'decimal_'.$standard_info->api} }}" />
<input type="hidden" name="call_unit" value="{{$trade_coin->call_unit}}" />
<input type="hidden" name="hm_cur" value="{{ $hm_cur }}" />
<input type="hidden" name="hm_usd" value="{{ $standard_info->last_trade_price_krw }}" />
<input type="hidden" name="country" value="{{ config('app.country') }}" />

<div class="m_hd_title">
    <div class="inner">
    {{ __('market.market') }}
    </div>
</div>

<!-- 코인선택바 -->
<div class="coin_select_bar" id="coin_slt_bar">
    <button type="button" class="coin_board_go_btn" onclick="location.href='/comunity?board_name={{$trade_coin->api}}'" >코인게시판</button>
    <label for="list_check">
        <p class="select_tit">
            <img src="{{ asset('/images/coin_img/'.strtolower($trade_coin->image)) }}" alt="coin_img" class="coin_symbol"/>
            {{__('coin_name.'.$trade_coin->api)}}
            <span class="currency">{{$trade_coin->symbol}} / {{ $symbol_text }}</span>
        </p>
    </label>
    <input id="list_check" type="checkbox" class="hide"/>
    <div class="market_list_n_coin_list">
        <ul class="market_list_tab">
            <li {{($trade_coin->market == 'sports')?'class=active':''}}>
                <label for="usdc_market_list">SPORTS COIN</label>
            </li>
            {{--
            <li {{($standard_info->api == 'btc')?'class=active':''}}>
                <label for="btc_market_list">BTC마켓</label>
            </li>
            <li {{($standard_info->api == 'eth')?'class=active':''}}>
                <label for="eth_market_list">ETH마켓</label>
            </li>
            --}}
            <li {{($trade_coin->market == 'public')?'class=active':''}}>
                <label for="krw_market_list">PUBLIC COIN</label>
            </li>
        </ul>

        <input id="usdc_market_list" class="hide" type="radio" name="coin_list" {{($standard_info->api == 'usdc')?'checked=checked':''}} />
        {{--
        <input id="btc_market_list" class="hide" type="radio" name="coin_list" {{($standard_info->api == 'btc')?'checked=checked':''}} />
        <input id="eth_market_list" class="hide" type="radio" name="coin_list" {{($standard_info->api == 'eth')?'checked=checked':''}} />
        --}}
        <input id="krw_market_list" class="hide" type="radio" name="coin_list" {{($standard_info->api == 'krw')?'checked=checked':''}} />

        <ul class="select_list coin_list-1">
            @foreach($coins as $coin)
            @if($coin->market == 'sports')
            <li>
                <a href="{{route('marketKRW',$coin->api)}}">
                    <img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image)) }}" alt="coin_img" class="coin_symbol"/>
                    {{__('coin_name.'.$coin->api)}}({{$coin->symbol}}/KRW)
                </a>
            </li>
            @endif
            @endforeach
        </ul>
        {{--
        <ul class="select_list coin_list-2">
            @foreach($coins as $coin)
                @if($coin->api != 'btc')
                <li>
                    <a href="{{route('marketBTC',$coin->symbol)}}">
                        <img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image)) }}" alt="coin_img" class="coin_symbol"/>
                        {{__('coin_name.'.$coin->api)}}({{$coin->symbol}}/BTC)
                    </a>
                </li>
                @endif
            @endforeach
        </ul>
        <ul class="select_list coin_list-3">
            @foreach($coins as $coin)
                @if($coin->api != 'eth')
                <li>
                    <a href="{{route('marketETH',$coin->symbol)}}">
                        <img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image)) }}" alt="coin_img" class="coin_symbol"/>
                        {{__('coin_name.'.$coin->api)}}({{$coin->symbol}}/ETH)
                    </a>
                </li>
                @endif
            @endforeach
        </ul>
        --}}
        <ul class="select_list coin_list-4">
            @foreach($coins as $coin)
            @if($coin->market == 'public')
                <li>
                    <a href="{{route('marketKRW',$coin->api)}}">
                        <img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image)) }}" alt="coin_img" class="coin_symbol"/>
                        {{__('coin_name.'.$coin->api)}}({{$coin->symbol}}/KRW)
                    </a>
                </li>
            @endif
            @endforeach
        </ul>

    </div>
</div>
<!-- //코인선택바 -->

<!-- 코인 종가 및 전일대비 등 정보영역 -->
<div id="top_ticker" class="coin_num_data" data-coin="{{$trade_coin->api}}" data-market="{{$standard_info->api}}">
    <p class="first_line {{$up_down_color}}">
        <span class="last_trade_price {{$up_down_color}}">{{number_format($trade_coin->{'last_trade_price_'.$standard_info->api}, $trade_coin->{'decimal_'.$standard_info->api})}}</span>
        <span class="currency">{{ $symbol_text }}</span>
        <div class="yesterday_compare">
            <span class="tit">{{ __('market.yesterday') }}</span>
            <span class="price_change_24h {{$up_down_color}}">{{$price_change_24h_number_symbol}}{{number_format($trade_coin->{'price_change_24h_'.$standard_info->api}, $trade_coin->{'decimal_'.$standard_info->api})}}</span>
            <span class="percent_change_24h {{$up_down_color}}">({{$percent_change_24h_indicate_symbol}}{{$price_change_24h_number_symbol}}{{number_format($trade_coin->{'percent_change_24h_'.$standard_info->api}, 2)}}%)</span>
        </div>
    </p>
    <span id="mini_chart" class="mini_chart" data-coin="{{$trade_coin->api}}"></span>
    <p class="second_line {{$up_down_color}}">
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
                <span class="high_price_24h"><b class="number_price">{{number_format($trade_coin->{'max_price_'.$standard_info->api}, $trade_coin->{'decimal_'.$standard_info->api})}}</b></span>
            </a>
        </li>
        <li>
            <a href="#">
                <p>{{ __('market.low') }}</p>
                <span class="row_price_24h"><b class="number_price">{{number_format($trade_coin->{'min_price_'.$standard_info->api}, $trade_coin->{'decimal_'.$standard_info->api})}}</b></span>
            </a>
        </li>
        <li>
            <a href="#">
                <p>{{ __('market.volume') }}</p>
                <span class="trade_volume_24h"> <b class="number_price">{{number_format($trade_coin->{'24h_volume_'.$standard_info->api},8)}}</b></span>
            </a>
        </li>
    </ul>
    {{-- //24시간 거래량보기 --}}

</div>
<!-- //코인 종가 및 전일대비 등 정보영역 -->

<!-- 하단 고정 네비게이션 -->
<div class="m_tab_menu">
    <ul>
        <li class="active"  data-index="0">
            <a href="#">
            {{ __('market.order') }}
            </a>
        </li>
        <li data-index="1">
            <a href="#">
            {!! __('market.m_hg') !!}
            </a>
        </li>
        <li data-index="2">
            <a href="#">
            {{ __('market.ct') }}
            </a>
        </li>
        <li data-index="4">
            <a href="#">
            {{ __('market.gr') }}
            </a>
        </li>
        @auth
        <li data-index="3">
            <a href="#">
            {{ __('market.my_balance') }}
            </a>
        </li>
        @endauth
        @auth
        <li data-index="5">
            <a href="#">
            {!! __('market.m_gr') !!}
            </a>
        </li>
        @endauth
    </ul>
</div>
<!--// 하단 고정 네비게이션 -->

<!--START 0.주문-->
<div class="m_tab_menu_con trans_order_con">

    <!-- 하단의 스크롤영역 (주문창) -->
    <div class="bottom_wrap trade_wrap">
        <div class="left_con">
            <div class="up_wrap wait_wrap">
                <ul id="sell_wait" class="sell_wait_ul wait_ul">
                    @foreach($sell_adss->reverse() as $sell_ads) {{-- CSS 속성 적용을 위해 역순으로 출력 --}}
                    <li data-price="{{round($sell_ads->price, $trade_coin->{'decimal_'.$standard_info->api})}}" data-amt="{{round($sell_ads->amt, $trade_coin->{'decimal_'.$standard_info->api})}}">
                        <span class="orderbook_price price_span">{{number_format($sell_ads->price, $trade_coin->{'decimal_'.$standard_info->api})}}</span>
                        <span class="orderbook_amt amt_span">{{number_format($sell_ads->amt, 4)}}</span>
                        <div class="per_bar"></div>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="sell_wait_tit wait_tit">{{ __('market.pd') }}</div>

            <div id="orderbook_middle" class="tratbl_total_box">-</div>

            <div class="buy_wait_tit wait_tit">{{ __('market.gd') }}</div>

            <div class="down_wrap wait_wrap">
                <ul id="buy_wait" class="buy_wait_ul wait_ul">
                    @foreach($buy_adss as $buy_ads)
                    <li data-price="{{round($buy_ads->price, $trade_coin->{'decimal_'.$standard_info->api})}}" data-amt="{{round($buy_ads->amt, $trade_coin->{'decimal_'.$standard_info->api})}}">
                        <span class="orderbook_price price_span">{{number_format($buy_ads->price, $trade_coin->{'decimal_'.$standard_info->api})}}</span>
                        <span class="orderbook_amt amt_span">{{number_format($buy_ads->amt, 4)}}</span>
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
                    {{ __('market.ms') }}
                    </li>
                    <li class="li_sell">
                    {{ __('market.md') }}
                    </li>
                </ul>
            </div>

            <div id="coin_buy_con" class="deal_con coin_buy_con">
                <input type="hidden" name="my_asset_cash" value="{{$user_current_cash_balance}}" />
                <span id="use_balance_buy" class="balance_state">
                {{ __('market.buying_available') }} <u>{{number_format($user_current_cash_balance,$trade_coin->{'decimal_'.$standard_info->api})}} {{ __('market.krw') }}</u>
                </span>

                <div class="buysell_wrap">
                    <table class="buysell_table">
                        <tbody>
                            <tr class="form-group">
                                <td>
                                    <label>{{ __('market.price_krw') }}</label>
                                    <input type="number" id="buy_coin_price" class="form-control mb-2 coin_price buysell_price_inp" data-decimal="{{ $trade_coin->{'decimal_'.$standard_info->api} }}" value="{{ number_format($trade_coin->{'last_trade_price_'.$standard_info->api}, $trade_coin->{'decimal_'.$standard_info->api},'.','') }}" placeholder="0" />
                                    <span class="currency">{{ __('market.krw') }}</span>
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
                                    <ul class="buy_percent mb-4">
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
                        <span class="currency">{{ __('market.krw') }}</span>
                    </p>
                </div>

                <div class="buysell_btn buy_btn">
                    @auth
                        @if(Auth::user()->status == 2)
                            <button type="button" class="not_active_btn btn_style stop_user_id_warning">계정 정지</button>
                        @else
                            @if($security_lv < 2)
                                @if($security_lv == 0)
                                    <a class="not_active_btn btn_style" href="{{route('mypage.security_setting')}}">{{ __('market.buying') }}</a>
                                @else
                                    <a class="not_active_btn btn_style" href="{{route('mypage.security_setting')}}">{{ __('market.phone') }}</a>
                                @endif
                            @else
                                <button type="button" class="btn_style" onclick="buysell_coin_data('buy','{{ $standard_info->api }}','{{$trade_coin->symbol}}');">{{ __('market.buying') }}</button>
                            @endif
                        @endif
                    @else
                        <a class="not_active_btn btn_style" href="{{route('login')}}">{{ __('market.login') }}</a>
                    @endauth
                </div>
            </div>
            <!--//coin_buy_con 구매-->

            <div id="coin_sell_con" class="deal_con coin_sell_con"  style="display:none;">
                <input type="hidden" name="my_asset_coin" value="{{$user_current_coin_balance}}" />
                <span id="use_balance_sell" class="balance_state">
                {{$trade_coin->symbol}} {{ __('market.balance') }} :
                <u>{{number_format($user_current_coin_balance,8)}}</u> {{$trade_coin->symbol}}
                </span>

                <div class="buysell_wrap">
                    <table class="buysell_table">
                        <tbody>
                            <tr class="form-group">
                                <td>
                                    <label>{{ __('market.price') }}</label>
                                    <input type="number" id="sell_coin_price" class="form-control mb-2 coin_price buysell_price_inp" data-decimal="{{ $trade_coin->{'decimal_'.$standard_info->api} }}" value="{{ number_format($trade_coin->{'last_trade_price_'.$standard_info->api},$trade_coin->{'decimal_'.$standard_info->api},'.','') }}" placeholder="0" />
                                    <span class="currency">{{ $symbol_text }}</span>
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
                        <span class="currency">{{ $symbol_text }}</span>
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
                                <button type="button" class="btn_style" onclick="buysell_coin_data('sell','{{ $standard_info->api }}','{{$trade_coin->symbol}}');">{{ __('market.selling') }}</button>
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

<!--START 1.호가-->
<div class="m_tab_menu_con asking_price_con" style="display:none;">
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
                    <th>{{ __('market.price') . '(' . $symbol_text . ')' }}</th>
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
                        <li data-price="{{round($sell_ads->price, $trade_coin->{'decimal_'.$standard_info->api})}}" data-amt="{{round($sell_ads->amt, $trade_coin->{'decimal_'.$standard_info->api})}}">
                            <span class="orderbook_price">
                                {{number_format($sell_ads->price, $trade_coin->{'decimal_'.$standard_info->api})}}
                            </span>
                            <span class="orderbook_amt">
                                {{number_format($sell_ads->amt, 8)}}
                            </span>
                            <span>
                                {{number_format(($sell_ads->price * $sell_ads->amt), $trade_coin->{'decimal_'.$standard_info->api})}}
                            </span>
                            <div class="per_bar" style="width: {{$sell_ads->amt/$total_amt_sell->total_amt_sell*600}}%;"></div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        {{-- //호가창 (판매대기) --}}

        <div id="orderbook_middle2" class="total_sum">-</div>

        {{-- 호가창 (구매대기) --}}
        <div class="down updownarea">
            <div class="inner">
                <ul id="buy_wait2">
                    @foreach($buy_adss as $buy_ads)
                        <li data-price="{{round($buy_ads->price, $trade_coin->{'decimal_'.$standard_info->api})}}" data-amt="{{round($buy_ads->amt, $trade_coin->{'decimal_'.$standard_info->api})}}">
                            <span class="orderbook_price">
                                {{number_format($buy_ads->price, $trade_coin->{'decimal_'.$standard_info->api})}}
                            </span>
                            <span class="orderbook_amt">
                                {{number_format($buy_ads->amt, 8)}}
                            </span>
                            <span>
                                {{number_format(($buy_ads->price * $buy_ads->amt), $trade_coin->{'decimal_'.$standard_info->api})}}
                            </span>
                            <div class="per_bar" style="width: {{$buy_ads->amt/$total_amt_buy->total_amt_buy*600}}%;"></div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        {{-- //호가창 (구매대기) --}}

    </div>
    <!-- //하단의 스크롤영역 (테이블) -->
</div>
<!--END 1.호가-->

<!--START 2.차트-->
<div class="m_tab_menu_con market_chart_con" style="display:none;">

    <!-- 하단의 스크롤영역 (차트창) -->
    <div class="bottom_wrap trade_wrap">
        <input type="hidden" name="trade_fee" value="{{$market->buy_comission/100}}" />
        <div id="chartdiv" class="chart_con">
        </div>
    </div>
    <!-- //하단의 스크롤영역 (차트창) -->

</div>
<!--END 2.차트-->



<!--START 4.시세-->
<div class="m_tab_menu_con market_price_con" style="display:none;">
    <!-- 상단없이 스크롤영역 (테이블) -->
    <div class="bottom_wrap table_wrap">
        <table class="table_label">
            <thead>
                <tr>
                    <th>{{ __('market.quantity') }}</th>
                    <th>{{ __('market.price') . '(' . $symbol_text . ')' }}</th>
                    <th>{{ __('market.time') }}</th>
                </tr>
            </thead>
        </table>

        <table class="coin_chart_tbl">
            <tbody id="trade_list" data-latest="{{isset($trade_historys[0]) ? $trade_historys[0]->created : ''}}">
                @foreach($trade_historys as $trade_history)
                    <tr class="{{ ($trade_history->last_trade_kind != null)? $trade_history->last_trade_kind:'' }}">
                        <td><span>{{number_format($trade_history->contract_coin_amt, 8)}}</span></td>
                        <td><span>{{ number_format($trade_history->sell_coin_price ,$trade_coin->{'decimal_'.$standard_info->api}) }}</span></td>
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

<!--START 3. 잔고-->
@auth
<div class="m_tab_menu_con m_myasset_wrap " style="display:none;">
    <div class="m_myasset_wrap">
        <div class="bottom_wrap">
            <input id="my_coin_check" type="radio" name="myast_view_con" class="hide">
            <input id="trans_hitr_check" type="radio" name="myast_view_con" class="hide">
            <input id="not_trans_check" type="radio" name="myast_view_con" class="hide">
            <div class="con_1 con_box">
                <div class="scrl_wrap">
                    <!-- 내 자산 상태 -->
                    <div class="status_bar">
                        <div class="total_ast">
                            <p class="text-right">
                                <label class="float-left tit">총 보유원화</label>
                                <span class="point_clr_3" id="total_balance_krw">{{ number_format(bcmul($coin_balance_krw,1,0),0) }}</span>
                                <span class="currency">KRW</span>
                            </p>
                            <p class="text-right">
                                <label class="float-left tit">총 추정자산</label>
                                <span class="point_clr_3" id="total_asset">{{ number_format(bcmul($total_asset,1,0),0) }}</span>
                                <span class="currency">KRW</span>
                            </p>
                        </div>
                        <ul class="my_ast_list bb-dddd">
                            <li class="bt-f0f0 pt-2">
                                <label>총 매수금액</label>
                                <span id="total_buying">{{ number_format(bcmul($total_buying,1,0),0) }}</span><span class="currency">KRW</span>
                            </li>
                            <li class="bt-f0f0 pt-2">
                                <label>총 평가수익</label>
                                <span id="total_eval_revenue">{{ number_format(bcmul($total_eval_revenue,1,0),0) }}</span><span class="currency">KRW</span>
                            </li>
                            <li>
                                <label>총 평가금액</label>
                                <span id="total_holding">{{ number_format(bcmul($total_holding,1,0),0) }}</span><span class="currency">KRW</span>
                            </li>
                            <li>
                                <label>총 평가수익률</label>
                                <span class="red"><b id="total_eval_percent">{{ number_format(bcmul($total_eval_percent,1,2),2) }}</b> %</span>
                                <!-- 오르면 red 하고 + 붙히고 떨어지면 blue 하고 - 붙혀주세요 -->
                            </li>
                        </ul>

                    </div>
                    <!-- //내 자산 상태 -->


                    <!-- 코인리스트박스 -->
                    @foreach($coins as $coin)
                    <div class="my_coin_list_con exist_balance" data-symbol="{{ $coin->symbol }}" data-kind="public">

                        <div class="hd dp_table ">

                            <div class="dp_table_cell _left added_arrow" onclick="location.href='http://spowide_develop.local/market_krw/{{ $coin->api }}';">
                                <img src="http://spowide_develop.local/images/coin_img/{{ $coin->image }}" alt="coin_img" class="coin_symbol">
                                <p class="pt-1">{{ __('coin_name.'.$coin->api) }}</p>
                                <span class="currency">{{ $coin->symbol }}/KRW</span>
                                <i class="fal fa-angle-right point_clr_2" aria-hidden="true"></i>
                            </div>

                            <div class="dp_table_cell _right">
                                <p class="pt-1">보유수량</p>
                                <span><b class="point_clr_1" name="asset_balance">{{ number_format($result[$coin->api]['balance'],8) }}</b> <b class="currency">{{ $coin->symbol }}</b></span>
                            </div>

                        </div>

                        <ul class="my_ast_list bb-dddd">
                            <li class="bt-f0f0 pt-2">
                                <label>매수평균가</label>
                                <span name="asset_avg">{{ number_format($result[$coin->api]['avg'],0) }}</span><span class="currency">KRW</span>
                            </li>
                            <li class="bt-f0f0 pt-2">
                                <label>매수금액</label>
                                <span name="asset_buying">{{ number_format($result[$coin->api]['buying'],0) }}</span><span class="currency">KRW</span>
                            </li>
                            <li>
                                <label>평가금액</label>
                                <span name="asset_eval">{{ number_format($result[$coin->api]['eval'],0) }}</span><span class="currency">KRW</span>
                            </li>
                            <li>
                                <label>평가손익</label>
                                <span class="{{ $result[$coin->api]['eval_percent'] < 0 ? "blue" : "red" }}" name="asset_percent">{{ $result[$coin->api]['eval_percent'] < 0 ? number_format($result[$coin->api]['eval_percent'],2) : "+".number_format($result[$coin->api]['eval_percent'],2)}} %</span>
                                <!-- 오르면 red 하고 + 붙히고 떨어지면 blue 하고 - 붙혀주세요 -->
                            </li>
                        </ul>

                    </div>
                    @endforeach
                    <!-- //코인리스트박스 -->


                </div>
            </div>
        </div>
    </div>
</div>
@endauth
<!--END 3. 잔고-->

<!--START 5.거래내역-->
<div class="m_tab_menu_con trans_history_con" style="display:none;">
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
                    <li class="con {{strtoupper($wait_trade->cointype)}}">
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
                            <span>{{number_format($wait_trade->{$wait_trade->type.'_coin_price'}, $trade_coin->{'decimal_'.$standard_info->api})}}</span>
                            <span class="currency">{{ $symbol_text }}</span>
                        </p>
                        <p class="info">
                            <label>{{ __('market.all_amount') }}</label>
                            <span>{{number_format(($wait_trade->{$wait_trade->type.'_coin_price'} * $wait_trade->{$wait_trade->type.'_COIN_amt'}), $trade_coin->{'decimal_'.$standard_info->api})}}</span>
                            <span class="currency">{{ $symbol_text }}</span>
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
    <div class="order_24h_wrap bottom_wrap history_st" style="display:none;">
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
                                {{ __('coin_name.'.strtolower($today_history->cointype)) }}(<u>{{strtoupper($today_history->cointype)}}</u>)
                            </p>
                            <p class="info _amt">
                                <label>{{ __('market.quantity') }}</label>
                                <span>{{number_format($today_history->contract_coin_amt, 8)}}</span>
                                <span class="currency">{{strtoupper($today_history->cointype)}}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('market.price') }}</label>
                                <span>{{number_format($today_history->buy_coin_price, $trade_coin->{'decimal_'.$standard_info->api})}}</span>
                                <span class="currency">{{ $symbol_text }}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('market.all_amount') }}</label>
                                <span>{{number_format($today_history->trade_total_buy, $trade_coin->{'decimal_'.$standard_info->api})}}</span>
                                <span class="currency">{{ $symbol_text }}</span>
                            </p>
                        </li>
                    @else
                        <li class="con sell">
                            <p class="info _date">
                                <span>{{date("Y-m-d H:i:s", strtotime("+9 hours", strtotime($today_history->created_dt)))}}</span>
                                <span class="float-right type">{{ __('market.sell') }}</span>
                            </p>
                            <p class="info _coin">
                                {{ __('coin_name.'.strtolower($today_history->cointype)) }}(<u>{{strtoupper($today_history->cointype)}}</u>)
                            </p>
                            <p class="info _amt">
                                <label>{{ __('market.quantity') }}</label>
                                <span>{{number_format($today_history->contract_coin_amt, 8)}}</span>
                                <span class="currency">{{strtoupper($today_history->cointype)}}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('market.price') }}</label>
                                <span>{{number_format($today_history->sell_coin_price, $trade_coin->{'decimal_'.$standard_info->api})}}</span>
                                <span class="currency">{{ $symbol_text }}</span>
                            </p>
                            <p class="info">
                                <label>{{ __('market.all_amount') }}</label>
                                <span>{{number_format($today_history->trade_total_sell, $trade_coin->{'decimal_'.$standard_info->api})}}</span>
                                <span class="currency">{{ $symbol_text }}</span>
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

<style>
#m_wrapper #m_main_container {
    background: #f8f8f8;
}
</style>

<script type="text/javascript" src="{{ asset('/js/mobile/market_sm_chart.js') }}"></script>

<script>
    $('.trans_order_con .left_con').animate({//Scroll된 Target

        //위치값 가져올 Target
        scrollTop:$(this).outerHeight() / 4

    }, 500);
</script>

@endsection
