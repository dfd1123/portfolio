@extends(session('theme').'.pc.layouts.app')

@section('content')

<!-- 정보 -->

<!--①비주얼탑부분 start-->
<div class="main_visual_wrap">

    <div class="width_align viusal_top">

        <p class="p_1st p_line">Cryptocurrency Exchange </p>


        {!!__('main.main_sentence1')!!}
        
        @if(count($events) != 0)
            <div class="banner_wrap">

                <!--①-1 배너 영역 슬라이드-->
                <div class="slider autoplay banner_slide" style="display:none;">
                    @foreach ($events as $event)
                    <div class="banner_con" onclick="window.location.href='{{route('event_view', $event->id)}}';">
                        <img src="{{asset('/storage/image/event/' . $event->image_url)}}" class="banner_img" alt="{{$event->title}}" onerror="this.src='{{asset('/storage/image/event/no_image.jpg')}}'"/>
                    </div>
                    @endforeach
                </div>
                <!--//①-1 배너 영역 슬라이드-->

            </div><!--//.banner_wrap-->
        @endif

    </div><!--//.viusal_top-->

    <!--①-2 뉴스 및 공지사항-->
    <div class="notice_bar_wrap" {{ (count($events)==0)?'style=padding-top:50px':'' }}>

        <div class="width_align">

            <ul class="ntc_ul">

                <!--공지사항영역-->
                @if(count($notices) != 0)
                    <li class="ntc_li notice_ntc_li active">

                        <span class="ntc_span"> NOTICE </span>

                        <div class="slide_group">
                            @foreach($notices as $notice)
                                <p class="ntc_p">
                                    <a href="{{route('notice_view', $notice->id)}}">- {{$notice->title}}</a>
                                </p>
                            @endforeach
                        </div>

                    </li>
                @endif
                <!--뉴스영역-->
                @if(count($events) != 0)
                    <li class="ntc_li news_ntc_li active">

                        <span class="ntc_span"> Event </span>

                        <div class="slide_group">
                            @foreach($events as $event)
                                <p class="ntc_p">
                                    <a href="{{route('event_view', $event->id)}}">- {{$event->title}}</a>
                                </p>
                            @endforeach
                        </div>

                    </li>
                @endif

            </ul>

        </div>

    </div>
    <!--//①-2 뉴스 및 공지사항-->

    <!--①-3 로그인부분-->
    @guest
    <div class="main_login_bar">

        <div class="width_align">

            <div class="main_login_wrap">

                <form method="post" action="{{route('main.login')}}">

                    @csrf
                    <div class="main_login_group main_login_before_group">
                        <div class="main_login_form login_form_first">
                            <input class="main_login-input" type="email" name="email"  value="{{ old('email') }}" required autofocus placeholder="{{ __('support.adrs')}}"/>
                            <br>
                            <input class="main_login-input" type="password" name="password" name="password" placeholder="{{ __('support.pw')}}" required />
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="display: none;">
                        </div>
                        <div class="main_login_form login_form_second">
                            <button class="main_login_form-btn login_btn" type="submit">

                            {{__('main.log_in')}}
                            </button>

                            <a class="etc_btn regi_btn" href="{{ route('register_agree') }}">{{__('main.join')}}</a>
                            <a class="etc_btn find_btn" href="{{ route('password.request') }}">{{__('main.find_password')}}</a>
                        </div>
                    </div>
                </form>

            </div>

        </div>

    </div>
    @else

    @endguest
    <!--//①-3 로그인부분-->

</div>
<!--①비주얼탑부분 end-->

<!--②하단 테이블영역 start-->
<div class="main_table_wrap">

    <div class="width_align">

        <div class="main_chart_top_tab">
            <ul>
                <li class="active">
                    <label for="ucssmarket_check">
                        UCSS {{__('main.market')}}
                    </label>
                </li>
                <li>
                    <label for="btcmarket_check">
                        BTC {{__('main.market')}}
                    </label>
                </li>
                <li>
                    <label for="ethmarket_check">
                        ETH {{__('main.market')}}
                    </label>
                </li>
            </ul>
        </div>
        
        <input id="ucssmarket_check" class="hide" type="radio" name="market_check" />
        <input id="btcmarket_check" class="hide" type="radio" name="market_check" />
        <input id="ethmarket_check" class="hide" type="radio" name="market_check" />

        {{-- UCSS마켓 코인테이블 --}}
        <table id="coin_table_usd" class="coin_chart_tbl main_chart_1">
            <thead>
                <tr>
                    <th>UCSS {{__('main.market')}} {{ __('main.coin') }}</th>
                    <th>{{__('main.yesterday')}}</th>
                    <th>{{__('main.price')}}</th>
                    <th>{{__('main.high_price')}}</th>
                    <th>{{__('main.low_price')}}</th>
                    <th>{{__('main.day_trade')}}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coins as $coin)
                	@if($coin->percent_change_24h_usd >= 0)
	                <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketUCSS', $coin->api)}}';">
	                    <td class="coin_td"><img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image))}}" alt="{{__('coin_name.'.$coin->api)}}" class="coin_symbol"/><span class="coin_name">{{__('coin_name.'.$coin->api)}}</span><span class="coin_name_eng">{{$coin->symbol}}/UCSS</span></td>
	                    <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_usd == 0 ? '' : 'red'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_usd == NULL)?number_format('0',2):number_format($coin->percent_change_24h_usd,2)}}% {{(float) $coin->percent_change_24h_usd == 0 ? '' : '▲'}}</span></div></td>
	                    <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_usd == NULL)?number_format('0',$coin->decimal_usd):number_format($coin->last_trade_price_usd,$coin->decimal_usd)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data max_price">{{ ($coin->max_price_usd == NULL)?number_format('0',$coin->decimal_usd):number_format($coin->max_price_usd,$coin->decimal_usd)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data min_price">{{ ($coin->min_price_usd == NULL)?number_format('0',$coin->decimal_usd):number_format($coin->min_price_usd,$coin->decimal_usd)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_usd'} == NULL)?'0':number_format($coin->{'24h_volume_usd'},8) }}</span></td>
	                </tr>
	                @else
	                <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketUCSS', $coin->api)}}';">
	                    <td class="coin_td"><img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image))}}" alt="{{__('coin_name.'.$coin->api)}}" class="coin_symbol"/><span class="coin_name">{{__('coin_name.'.$coin->api)}}</span><span class="coin_name_eng">{{$coin->symbol}}/UCSS</span></td>
	                    <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_usd == 0 ? '' : 'blue'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_usd == NULL)?number_format('0',2):number_format($coin->percent_change_24h_usd,2)}}% {{(float) $coin->percent_change_24h_usd == 0 ? '' : '▼'}}</span></div></td>
	                    <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_usd == NULL)?number_format('0',2):number_format($coin->last_trade_price_usd,$coin->decimal_usd)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data max_price">{{ ($coin->max_price_usd == NULL)?number_format('0',$coin->decimal_usd):number_format($coin->max_price_usd,$coin->decimal_usd)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data min_price">{{ ($coin->min_price_usd == NULL)?number_format('0',$coin->decimal_usd):number_format($coin->min_price_usd,$coin->decimal_usd)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_usd'} == NULL)?number_format('0',2):number_format($coin->{'24h_volume_usd'},8) }}</span></td>
	                </tr>
	                @endif
                @empty
                <tr>

                    <td colspan="6">{{__('main.nodata')}}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        {{-- BTC마켓 코인테이블 --}}
        <table id="coin_table_btc" class="coin_chart_tbl main_chart_2">
            <thead>
                <tr>


                    <th>{{__('main.btc_market')}} {{ __('main.coin') }}</th>
                    <th>{{__('main.yesterday')}}</th>

                    <th>{{__('main.gg')}}(BTC)</th>
                    <th>{{__('main.high')}}(BTC)</th>
                    <th>{{__('main.low')}}(BTC)</th>
                    <th>{{__('main.day_trade')}}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coins as $coin)

                @if($coin->api != 'btc')
	                @if($coin->percent_change_24h_btc >= 0)
	                <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketBTC', $coin->api)}}';">
	                    <td class="coin_td"><img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image))}}" alt="{{__('coin_name.'.$coin->api)}}" class="coin_symbol"/><span class="coin_name">{{__('coin_name.'.$coin->api)}}</span><span class="coin_name_eng">{{$coin->symbol}}/BTC</span></td>
	                    <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_btc == 0 ? '' : 'red'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_btc == NULL)?number_format('0',2):number_format($coin->percent_change_24h_btc,2)}}% {{(float) $coin->percent_change_24h_btc == 0 ? '' : '▲'}}</span></div></td>
	                    <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_btc == NULL)?number_format('0',$coin->decimal_btc):number_format($coin->last_trade_price_btc,$coin->decimal_btc)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data max_price">{{ ($coin->max_price_btc == NULL)?number_format('0',$coin->decimal_btc):number_format($coin->max_price_btc,$coin->decimal_btc)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data min_price">{{ ($coin->min_price_btc == NULL)?number_format('0',$coin->decimal_btc):number_format($coin->min_price_btc,$coin->decimal_btc)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_btc'} == NULL)?'0':number_format($coin->{'24h_volume_btc'},8) }}</span></td>
	                </tr>
	                @else
	                <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketBTC', $coin->api)}}';">
	                    <td class="coin_td"><img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image))}}" alt="{{__('coin_name.'.$coin->api)}}" class="coin_symbol"/><span class="coin_name">{{__('coin_name.'.$coin->api)}}</span><span class="coin_name_eng">{{$coin->symbol}}/BTC</span></td>
	                    <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_btc == 0 ? '' : 'blue'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_btc == NULL)?number_format('0',2):number_format($coin->percent_change_24h_btc,2)}}% {{(float) $coin->percent_change_24h_btc == 0 ? '' : '▼'}}</span></div></td>
	                    <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_btc == NULL)?number_format('0',2):number_format($coin->last_trade_price_btc,$coin->decimal_btc)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data max_price">{{ ($coin->max_price_btc == NULL)?number_format('0',$coin->decimal_btc):number_format($coin->max_price_btc,$coin->decimal_btc)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data min_price">{{ ($coin->min_price_btc == NULL)?number_format('0',$coin->decimal_btc):number_format($coin->min_price_btc,$coin->decimal_btc)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_btc'} == NULL)?number_format('0',2):number_format($coin->{'24h_volume_btc'},8) }}</span></td>
	                </tr>
	                @endif
	            @endif
                @empty
                <tr>

                    <td colspan="6">{{__('main.nodata')}}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        {{-- ETH마켓 코인테이블 --}}
        <table id="coin_table_eth" class="coin_chart_tbl main_chart_3">
            <thead>
                <tr>
                    <th>{{__('main.eth_market')}} {{ __('main.coin') }}</th>
                    <th>{{__('main.yesterday')}}</th>
                   	<th>{{__('main.gg')}}(ETH)</th>
                    <th>{{__('main.high')}}(ETH)</th>
                    <th>{{__('main.low')}}(ETH)</th>
                    <th>{{__('main.day_trade')}}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coins as $coin)

                @if($coin->api != 'eth')
	                @if($coin->percent_change_24h_eth >= 0)
	                <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketETH', $coin->api)}}';">
	                    <td class="coin_td"><img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image))}}" alt="{{__('coin_name.'.$coin->api)}}" class="coin_symbol"/><span class="coin_name">{{__('coin_name.'.$coin->api)}}</span><span class="coin_name_eng">{{$coin->symbol}}/ETH</span></td>
	                    <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_eth == 0 ? '' : 'red'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_eth == NULL)?number_format('0',2):number_format($coin->percent_change_24h_eth,2)}}% {{(float) $coin->percent_change_24h_eth == 0 ? '' : '▲'}}</span></div></td>
	                    <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_eth == NULL)?number_format('0',$coin->decimal_eth):number_format($coin->last_trade_price_eth,$coin->decimal_eth)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data max_price">{{ ($coin->max_price_eth == NULL)?number_format('0',$coin->decimal_eth):number_format($coin->max_price_eth,$coin->decimal_eth)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data min_price">{{ ($coin->min_price_eth == NULL)?number_format('0',$coin->decimal_eth):number_format($coin->min_price_eth,$coin->decimal_eth)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_eth'} == NULL)?'0':number_format($coin->{'24h_volume_eth'},8) }}</span></td>
	                </tr>
	                @else
	                <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketETH', $coin->api)}}';">
	                    <td class="coin_td"><img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image))}}" alt="{{__('coin_name.'.$coin->api)}}" class="coin_symbol"/><span class="coin_name">{{__('coin_name.'.$coin->api)}}</span><span class="coin_name_eng">{{$coin->symbol}}/ETH</span></td>
	                    <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_eth == 0 ? '' : 'blue'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_eth == NULL)?number_format('0',2):number_format($coin->percent_change_24h_eth,2)}}% {{(float) $coin->percent_change_24h_eth == 0 ? '' : '▼'}}</span></div></td>
	                    <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_eth == NULL)?number_format('0',2):number_format($coin->last_trade_price_eth,$coin->decimal_eth)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data max_price">{{ ($coin->max_price_eth == NULL)?number_format('0',$coin->decimal_eth):number_format($coin->max_price_eth,$coin->decimal_eth)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data min_price">{{ ($coin->min_price_eth == NULL)?number_format('0',$coin->decimal_eth):number_format($coin->min_price_eth,$coin->decimal_eth)}}<span class="currency"></span></span></td>
	                    <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_eth'} == NULL)?number_format('0',2):number_format($coin->{'24h_volume_eth'},8) }}</span></td>
	                </tr>
	                @endif
	            @endif
                @empty
                <tr>

                    <td colspan="6">{{__('main.nodata')}}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
<!--②하단 테이블영역 end-->

<!--③about sharebits_1 start-->
<div class="main_section_wrap_1">
    <div class="width_align">
        <img src="{{ asset('/storage/image/homepage/main/img_about.png')}}" alt="main_img1" class="main_img1"/>
        <div class="text_box">

        {!! __('main.main_sentence6') !!}
        <p>
            {!! __('main.main_sentence7') !!}
        </p>
        </div>
    </div>
</div>
<!--③about sharebits_1 end-->

<!--④about sharebits_2 start-->
<div class="main_section_wrap_2">
    <div class="width_align">
        <ul class="sharebits_advan_ul">
            <li>
                <span class="icon"></span>

                <span class="sub_txt">{!! __('main.main_sentence8_sub') !!}</span>
                {!! __('main.main_sentence8') !!}			</li>
            <li>
                <span class="icon"></span>

                <span class="sub_txt">{!! __('main.main_sentence9_sub') !!}</span>
                {!! __('main.main_sentence9') !!}

            </li>
            <li>
                <span class="icon"></span>

                <span class="sub_txt">{!! __('main.main_sentence10_sub') !!}</span>
                {!! __('main.main_sentence10') !!}
            </li>
        </ul>
    </div>
</div>
<!--④about sharebits_2 end-->

<!--⑤final_section start-->
<div class="main_section_wrap_3">

<h3 class="title">{!! __('main.main_sentence11') !!}</h3>

@auth
    <a href="{{ route('marketUCSS','btc') }}" class="direct_btn mt-4">{{__('main.go_trademarket')}}</a></button>
@else
    <p class="sub_txt">{{ __('main.main_sentence12') }}</p>
    <a href="{{ route('register_agree') }}" class="direct_btn">{!! __('main.join')!!}</a></button>
@endauth

</div>


<!--⑤final_section end-->

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

@endsection