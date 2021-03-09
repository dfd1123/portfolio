@extends(session('theme').'.mobile.layouts.app')

@section('content')

<!--
<img src="{{ asset('/storage/image/homepage/server.jpg')}}" />
-->
<!-- 헤더타이틀 -->
<div class="m_hd_title">
    <div class="inner">
        <h1 class="logo">
            <a href="{{ url('/?country='.config('app.country')) }}">
                <img src="{{ asset('/images/header_cointouse.svg')}}" alt="logo"/>
            </a>
        </h1>
    </div>
</div>
<!-- 헤더타이틀 -->

<div id="main_wrap">

    <!-- 모바일버전 공지사항바 -->
    <div class="notice_bar dp_table ntc_bar">
        <div class="dp_table_cell">
            
            <span id="ntc_next_btn" class="hide">NEXT</span>
            
            <i class="fal fa-bullhorn"></i>
            
            <div class="ntc_bar_mask">
                <ul class="ntc_ul">
                    @foreach($notices as $notice)
                        <li>
                            <a href="{{route('notice_view', $notice->id)}}">{{$notice->title}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            
        </div>
    </div>
    <!-- //모바일버전 공지사항바 -->

    <!-- 거래소-입출금-내자산 바로가기버튼 -->
    <div class="m_direct_btn">
        <ul>
            <li>
                <a href="{{ route('marketKRW','btc') }}">
                    <span class="icon"></span>
                    <span class="label">{{ __('main.m_exchange')}}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('trans_wallet') }}">
                    <span class="icon"></span>
                    <span class="label">{!! __('main.m_inout')!!}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('my_asset.index') }}">
                    <span class="icon"></span>
                    <span class="label">{{ __('main.m_asset')}}</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- //거래소-입출금-내자산 바로가기버튼 -->

    <div class="m_coin_chart">

        <div class="market_list_tab_bar">
            <ul class="market_list_tab">
                <li class="active">
                    <label for="krw_market_list">KRW마켓</label>
                </li>
                <li>
                    <label for="usdc_market_list">USDC마켓</label>
                </li>
                {{--
                <li>
                    <label for="btc_market_list">BTC마켓</label>
                </li>
                <li>
                    <label for="eth_market_list">ETH마켓</label>
                </li>
                --}}
                
            </ul>
        </div> 

        <div class="sch_bar">
            <div class="coin_sch_bar">
                <div class="inner">
                    <input type="text" id="txtFilter" placeholder="{{ __('main.m_scrh')}}">
                    <i class="sch_icon"></i>
                </div>
            </div>
            <div class="coin_sch_checkbox">
                <input id="my_coin" type="checkbox" class="grayCheckbox hide">
                <label for="my_coin">&nbsp;{{ __('main.m_havecoin')}}</label>
            </div>
        </div>
        
        <div class="coin_table_wrap">
            <input id="usdc_market_list" class="hide" type="radio" name="coin_list" />
            <input id="btc_market_list" class="hide" type="radio" name="coin_list" />
            <input id="eth_market_list" class="hide" type="radio" name="coin_list" />
            <input id="krw_market_list" class="hide" type="radio" name="coin_list" />
            <table id="coin_table_krw" class="coin_chart_tbl target coin_table-1 table_label">
                <thead>
                    <tr>
                        <th>{{ __('main.m_coinname')}}</th>
                        <th>{{ __('main.m_day')}}</th>
                        <th>{{ __('main.price')}} (KRW)</th>
                        <th>{{ __('main.day_trade')}}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($coins as $coin)
                    @if($coin->api != 'krw')
                        @if($coin->percent_change_24h_krw >= 0)
                        <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketKRW', $coin->api)}}';" name="{{ __('main.m_'.$coin->api)}}/{{$coin->api}}">
                            <td class="coin_td"><p class="coin_name">{{__('coin_name.'.$coin->api)}}</p><span class="coin_name_eng">{{$coin->symbol}}/<b>KRW</b></span></td>
                            <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_krw == 0 ? '' : 'red'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_krw == NULL)?number_format('0',2):number_format($coin->percent_change_24h_krw,2)}}% {{(float) $coin->percent_change_24h_krw == 0 ? '' : '▲'}}</span></div></td>
                            <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_krw == NULL)?number_format('0',$coin->decimal_krw):number_format($coin->last_trade_price_krw,$coin->decimal_krw)}}</span></td>
                            <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_krw'} == NULL)?'0':number_format($coin->{'24h_volume_krw'},8) }}</span></td>
                        </tr>
                        @else
                        <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketKRW', $coin->api)}}';" name="{{ __('main.m_'.$coin->api)}}/{{$coin->api}}">
                            <td class="coin_td"><p class="coin_name">{{__('coin_name.'.$coin->api)}}</p><span class="coin_name_eng">{{$coin->symbol}}/<b>KRW</b></span></td>
                            <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_krw == 0 ? '' : 'blue'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_krw == NULL)?number_format('0',2):number_format($coin->percent_change_24h_krw,2)}}% {{(float) $coin->percent_change_24h_krw == 0 ? '' : '▼'}}</span></div></td>
                            <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_krw == NULL)?number_format('0',2):number_format($coin->last_trade_price_krw,$coin->decimal_krw)}}</span></td>
                            <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_krw'} == NULL)?number_format('0',2):number_format($coin->{'24h_volume_krw'},8) }}</span></td>
                        </tr>
                        @endif
                    @endif
                @empty
                <tr>
                    <td colspan="4">{{__('main.nodata')}}</td>
                </tr>
                @endforelse
                </tbody>
            </table>
            <table id="coin_table_usd" class="coin_chart_tbl target coin_table-4 table_label">
            	<thead>
                    <tr>
                        <th>{{ __('main.m_coinname')}}</th>
                        <th>{{ __('main.m_day')}}</th>
                        <th>{{ __('main.price')}} (USDC)</th>
                        <th>{{ __('main.day_trade')}}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($coins as $coin)
                    @if($coin->percent_change_24h_usd >= 0)
                    <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketUSDC', $coin->api)}}';" name="{{ __('main.m_'.$coin->api)}}/{{$coin->api}}">
                        <td class="coin_td"><p class="coin_name">{{__('coin_name.'.$coin->api)}}</p><span class="coin_name_eng">{{$coin->symbol}}/<b>USDC</b></span></td>
                        <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_usd == 0 ? '' : 'red'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_usd == NULL)?number_format('0',2):number_format($coin->percent_change_24h_usd,2)}}% {{(float) $coin->percent_change_24h_usd == 0 ? '' : '▲'}}</span></div></td>
                        <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_usd == NULL)?number_format('0',$coin->decimal_usd):number_format($coin->last_trade_price_usd,$coin->decimal_usd)}}</span></td>
                        <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_usd'} == NULL)?'0':number_format($coin->{'24h_volume_usd'},8) }}</span></td>
                    </tr>
                    @else
                    <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketUSDC', $coin->api)}}';" name="{{ __('main.m_'.$coin->api)}}/{{$coin->api}}">
                        <td class="coin_td"><p class="coin_name">{{__('coin_name.'.$coin->api)}}</p><span class="coin_name_eng">{{$coin->symbol}}/<b>USDC</b></span></td>
                        <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_usd == 0 ? '' : 'blue'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_usd == NULL)?number_format('0',2):number_format($coin->percent_change_24h_usd,2)}}% {{(float) $coin->percent_change_24h_usd == 0 ? '' : '▼'}}</span></div></td>
                        <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_usd == NULL)?number_format('0',2):number_format($coin->last_trade_price_usd,$coin->decimal_usd)}}</span></td>
                        <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_usd'} == NULL)?number_format('0',2):number_format($coin->{'24h_volume_usd'},8) }}</span></td>
                    </tr>
                    @endif
                @empty
                <tr>
                    <td colspan="4">{{__('main.nodata')}}</td>
                </tr>
                @endforelse
                </tbody>
            </table>
            {{--
            <table id="coin_table_btc" class="coin_chart_tbl target coin_table-2 table_label">
            	<thead>
                    <tr>
                        <th>{{ __('main.m_coinname')}}</th>
                        <th>{{ __('main.m_day')}}</th>
                        <th>{{ __('main.price') (BTC)}}</th>
                        <th>{{ __('main.day_trade')}}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($coins as $coin)
                    @if($coin->api != 'btc')
                        @if($coin->percent_change_24h_btc >= 0)
                        <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketBTC', $coin->api)}}';" name="{{ __('main.m_'.$coin->api)}}/{{$coin->api}}">
                            <td class="coin_td"><p class="coin_name">{{__('coin_name.'.$coin->api)}}</p><span class="coin_name_eng">{{$coin->symbol}}/<b>BTC</b></span></td>
                            <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_btc == 0 ? '' : 'red'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_btc == NULL)?number_format('0',2):number_format($coin->percent_change_24h_btc,2)}}% {{(float) $coin->percent_change_24h_btc == 0 ? '' : '▲'}}</span></div></td>
                            <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_btc == NULL)?number_format('0',$coin->decimal_btc):number_format($coin->last_trade_price_btc,$coin->decimal_btc)}}</span></td>
                            <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_btc'} == NULL)?'0':number_format($coin->{'24h_volume_btc'},8) }}</span></td>
                        </tr>
                        @else
                        <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketBTC', $coin->api)}}';" name="{{ __('main.m_'.$coin->api)}}/{{$coin->api}}">
                            <td class="coin_td"><p class="coin_name">{{__('coin_name.'.$coin->api)}}</p><span class="coin_name_eng">{{$coin->symbol}}/<b>BTC</b></span></td>
                            <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_btc == 0 ? '' : 'blue'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_btc == NULL)?number_format('0',2):number_format($coin->percent_change_24h_btc,2)}}% {{(float) $coin->percent_change_24h_btc == 0 ? '' : '▼'}}</span></div></td>
                            <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_btc == NULL)?number_format('0',2):number_format($coin->last_trade_price_btc,$coin->decimal_btc)}}</span></td>
                            <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_btc'} == NULL)?number_format('0',2):number_format($coin->{'24h_volume_btc'},8) }}</span></td>
                        </tr>
                        @endif
                    @endif
                @empty
                <tr>
                    <td colspan="4">{{__('main.nodata')}}</td>
                </tr>
                @endforelse
                </tbody>
            </table>
            <table id="coin_table_eth" class="coin_chart_tbl target coin_table-3 table_label">
            	<thead>
                    <tr>
                        <th>{{ __('main.m_coinname')}}</th>
                        <th>{{ __('main.m_day')}}</th>
                        <th>{{ __('main.price') }} (ETH)</th>
                        <th>{{ __('main.day_trade')}}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($coins as $coin)
                    @if($coin->api != 'eth')
                        @if($coin->percent_change_24h_eth >= 0)
                        <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketETH', $coin->api)}}';" name="{{ __('main.m_'.$coin->api)}}/{{$coin->api}}">
                            <td class="coin_td"><p class="coin_name">{{__('coin_name.'.$coin->api)}}</p><span class="coin_name_eng">{{$coin->symbol}}/<b>ETH</b></span></td>
                            <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_eth == 0 ? '' : 'red'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_eth == NULL)?number_format('0',2):number_format($coin->percent_change_24h_eth,2)}}% {{(float) $coin->percent_change_24h_eth == 0 ? '' : '▲'}}</span></div></td>
                            <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_eth == NULL)?number_format('0',$coin->decimal_eth):number_format($coin->last_trade_price_eth,$coin->decimal_eth)}}</span></td>
                            <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_eth'} == NULL)?'0':number_format($coin->{'24h_volume_eth'},8) }}</span></td>
                        </tr>
                        @else
                        <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketETH', $coin->api)}}';" name="{{ __('main.m_'.$coin->api)}}/{{$coin->api}}">
                            <td class="coin_td"><p class="coin_name">{{__('coin_name.'.$coin->api)}}</p><span class="coin_name_eng">{{$coin->symbol}}/<b>ETH</b></span></td>
                            <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_eth == 0 ? '' : 'blue'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_eth == NULL)?number_format('0',2):number_format($coin->percent_change_24h_eth,2)}}% {{(float) $coin->percent_change_24h_eth == 0 ? '' : '▼'}}</span></div></td>
                            <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_eth == NULL)?number_format('0',2):number_format($coin->last_trade_price_eth,$coin->decimal_eth)}}</span></td>
                            <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_eth'} == NULL)?number_format('0',2):number_format($coin->{'24h_volume_eth'},8) }}</span></td>
                        </tr>
                        @endif
                    @endif
                @empty
                <tr>
                    <td colspan="4">{{__('main.nodata')}}</td>
                </tr>
                @endforelse
                </tbody>
            </table> 
            --}}
             
        </div>
    </div>
    

</div>

@endsection
