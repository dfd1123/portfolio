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
                <img src="{{ asset('/storage/image/homepage/spowide_header_logo.png')}}" style="    width: 106px;" alt="logo"/>

            </a>
        </h1>
    </div>
</div>
<!-- 헤더타이틀 -->

<div id="main_wrap">

    <!-- 모바일버전 공지사항바 -->
    <div class="notice_bar dp_table ntc_bar">
        <div class="dp_table_cell">
            <span class="ntc_tit">{{ __('main.emergency')}}</span>

            <span id="ntc_next_btn" class="hide">NEXT</span>

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

    <!-- 191202 1) 모바일메인리뉴얼 - 이벤트배너영역 -->
    <div id="banner_list" class="m-main-event-area swiper-container">
        <ul class="m-main-event-group swiper-wrapper">
            <li class="m-main-event-list swiper-slide">
                <a href="https://spowide.co.kr/event/4" class="in-toucharea">
                    <img src="/images/popup/event_1.png" class="only_img">
                </a>
            </li>
            <li class="m-main-event-list swiper-slide">
                <a href="https://spowide.co.kr/event/3" class="in-toucharea">
                <img src="/images/popup/event_2.png" class="only_img">
                </a>
            </li>
            <li class="m-main-event-list swiper-slide">
                <a href="https://spowide.co.kr/event/2" class="in-toucharea">
                <img src="/images/popup/event_3.png" class="only_img">
                </a>
            </li>
            <li class="m-main-event-list swiper-slide">
                <a href="https://spowide.co.kr/event/1" class="in-toucharea">
                <img src="/images/popup/event_4.png" class="only_img">
                </a>
            </li>
        </ul>
        <div class="swiper-pagination"></div>
    </div>
    <!-- 191202 1) E -->

    <div class="m_coin_chart">

        <div class="market_list_tab_bar">
            <ul class="market_list_tab">
                <li data-kind="sports" class="active">
                    <label for="usdc_market_list">SPORTS COIN</label>
                </li>
                <li data-kind="public">
                    <label for="krw_market_list">PUBLIC COIN</label>
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
            <input id="usdc_market_list" class="hide" type="radio" name="coin_list" checked/>
            <input id="btc_market_list" class="hide" type="radio" name="coin_list" />
            <input id="eth_market_list" class="hide" type="radio" name="coin_list" />
            <input id="krw_market_list" class="hide" type="radio" name="coin_list" />
            <table id="coin_table_usd" class="coin_chart_tbl target table_label coin_table_renew">
                <thead>
                    <tr>
                        <th>{{ __('main.m_coinname')}}</th>
                        <th>{{ __('main.m_day')}}</th>
                        <th>{{ __('main.price')}}(원)</th>
                        <th>{{ __('main.day_trade')}}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($sports_coins as $coin)
                <tr id="row_{{$coin->api}}" data-kind="{{$coin->market}}" onclick="window.location.href = '{{route('marketKRW', $coin->api)}}';" name="{!! __('coin_name.'.$coin->api) !!}/{{$coin->api}}">
                    <td class="coin_td">
                        <p class="coin_name">{!! __('coin_name.'.$coin->api) !!}</p>
                        <span class="coin_name_eng">{{$coin->symbol}}/<b>KRW</b></span>
                    </td>
                    <td>
                        <div class="cell">
                            <span class="{{($coin->percent_change_24h_krw == 0) ? '' : ($coin->percent_change_24h_krw > 0 ? 'red' : 'blue')}} table_num_data percent_change_24h">
                                {{($coin->percent_change_24h_krw == 0) ? number_format('0', 2) : (($coin->percent_change_24h_krw > 0 ? '+' : '').number_format($coin->percent_change_24h_krw, 2))}}% {{$coin->percent_change_24h_krw == 0 ? '' : ($coin->percent_change_24h_krw > 0 ? '▲' : '▼')}}
                            </span>
                        </div>
                    </td>
                    <td>
                        <span class="table_num_data last_trade_price_usd">
                            {{ ($coin->last_trade_price_krw == 0) ? number_format('0', $coin->decimal_krw) : number_format($coin->last_trade_price_krw, $coin->decimal_krw)}}
                        </span>
                    </td>
                    <td>
                        <span class="table_num_data h24h_volume">
                            {{ ($coin->{'24h_volume_krw'} == 0) ? number_format('0', 3) : number_format($coin->{'24h_volume_krw'}, 3) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">{{__('main.nodata')}}</td>
                </tr>
                @endforelse
                </tbody>
            </table>
            <table id="coin_table_krw" class="coin_chart_tbl target table_label coin_table_renew">
                <thead>
                    <tr>
                        <th>{{ __('main.m_coinname')}}</th>
                        <th>{{ __('main.m_day')}}</th>
                        <th>{{ __('main.price')}} (KRW)</th>
                        <th>{{ __('main.day_trade')}}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($public_coins as $coin)
                <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketKRW', $coin->api)}}';" name="{!! __('coin_name.'.$coin->api) !!}/{{$coin->api}}">
                    <td class="coin_td">
                        <p class="coin_name">{!! __('coin_name.'.$coin->api) !!}</p>
                        <span class="coin_name_eng">{{$coin->symbol}}/<b>KRW</b></span>
                    </td>
                    <td>
                        <div class="cell">
                            <span class="{{($coin->percent_change_24h_krw == 0) ? '' : ($coin->percent_change_24h_krw > 0 ? 'red' : 'blue')}} table_num_data percent_change_24h">
                                {{($coin->percent_change_24h_krw == 0) ? number_format('0', 2) : (($coin->percent_change_24h_krw > 0 ? '+' : '').number_format($coin->percent_change_24h_krw, 2))}}% {{$coin->percent_change_24h_krw == 0 ? '' : ($coin->percent_change_24h_krw > 0 ? '▲' : '▼')}}
                            </span>
                        </div>
                    </td>
                    <td>
                        <span class="table_num_data last_trade_price_usd">
                            {{ ($coin->last_trade_price_krw == 0) ? number_format('0', $coin->decimal_krw) : number_format($coin->last_trade_price_krw, $coin->decimal_krw)}}
                        </span>
                    </td>
                    <td>
                        <span class="table_num_data h24h_volume">
                            {{ ($coin->{'24h_volume_krw'} == 0) ? number_format('0', 3) : number_format($coin->{'24h_volume_krw'}, 3) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">{{__('main.nodata')}}</td>
                </tr>
                @endforelse
                </tbody>
            </table>
            {{--
            <table id="coin_table_btc" class="coin_chart_tbl target table_label">
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
                            <td class="coin_td"><p class="coin_name">{!! __('coin_name.'.$coin->api) !!}</p><span class="coin_name_eng">{{$coin->symbol}}/<b>BTC</b></span></td>
                            <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_btc == 0 ? '' : 'red'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_btc == NULL)?number_format('0',2):number_format($coin->percent_change_24h_btc,2)}}% {{(float) $coin->percent_change_24h_btc == 0 ? '' : '▲'}}</span></div></td>
                            <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_btc == NULL)?number_format('0',$coin->decimal_btc):number_format($coin->last_trade_price_btc,$coin->decimal_btc)}}</span></td>
                            <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_btc'} == NULL)?'0':number_format($coin->{'24h_volume_btc'},8) }}</span></td>
                        </tr>
                        @else
                        <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketBTC', $coin->api)}}';" name="{{ __('main.m_'.$coin->api)}}/{{$coin->api}}">
                            <td class="coin_td"><p class="coin_name">{!! __('coin_name.'.$coin->api) !!}</p><span class="coin_name_eng">{{$coin->symbol}}/<b>BTC</b></span></td>
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
            <table id="coin_table_eth" class="coin_chart_tbl target table_label">
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
                            <td class="coin_td"><p class="coin_name">{!! __('coin_name.'.$coin->api) !!}</p><span class="coin_name_eng">{{$coin->symbol}}/<b>ETH</b></span></td>
                            <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_eth == 0 ? '' : 'red'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_eth == NULL)?number_format('0',2):number_format($coin->percent_change_24h_eth,2)}}% {{(float) $coin->percent_change_24h_eth == 0 ? '' : '▲'}}</span></div></td>
                            <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_eth == NULL)?number_format('0',$coin->decimal_eth):number_format($coin->last_trade_price_eth,$coin->decimal_eth)}}</span></td>
                            <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_eth'} == NULL)?'0':number_format($coin->{'24h_volume_eth'},3) }}</span></td>
                        </tr>
                        @else
                        <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketETH', $coin->api)}}';" name="{{ __('main.m_'.$coin->api)}}/{{$coin->api}}">
                            <td class="coin_td"><p class="coin_name">{!! __('coin_name.'.$coin->api) !!}</p><span class="coin_name_eng">{{$coin->symbol}}/<b>ETH</b></span></td>
                            <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_eth == 0 ? '' : 'blue'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_eth == NULL)?number_format('0',2):number_format($coin->percent_change_24h_eth,2)}}% {{(float) $coin->percent_change_24h_eth == 0 ? '' : '▼'}}</span></div></td>
                            <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_eth == NULL)?number_format('0',2):number_format($coin->last_trade_price_eth,$coin->decimal_eth)}}</span></td>
                            <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_eth'} == NULL)?number_format('0',2):number_format($coin->{'24h_volume_eth'},3) }}</span></td>
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
    
    <!-- 191202 3) 모바일메인리뉴얼 - 공지사항링크영역 -->
    <div class="m-main-notice-area">
        <a href="{{route('notice_view', $notices[0]->id)}}">
            <h3 class="m-main-ntc-title">공지사항</h3>
            <p class="m-main-ntc-text">{{$notices[0]->title}}</p>
            <span class="m-main-ntc-date">{{date("Y-m-d H:i:s",$notices[0]->created)}}</span>
        </a>
    </div>
    <!-- 191202 3) E -->

    <!-- 191202 4) 모바일메인리뉴얼 - 메인컨텐츠영역 -->
    <div id="m-main-contents-area">
        <div class="m-main-hdcon">
            <span class="sub-title">SPOWIDE SERIES A</span>
            <h2 class="big-title"><b>3</b><span>Vision</span></h2>
            <ul class="three-s">
                <li>store</li>
                <li>staking</li>
                <li>streaming</li>
            </ul>
            <ul class="three-s-visual">
              <li class="three-s-visual-list">
                <img src="/images/icon/icon_store.png" alt="">
                <h3>STORE</h3>
                <span>코인 트레이드<br>쇼핑몰</span>
              </li>
              <li class="three-s-visual-list">
                <img src="/images/icon/icon_staking.png" alt="">
                <h3>STAKING</h3>
                <span>경기 승리 시 보유팀<br>리워드 보상</span>
              </li>
              <li class="three-s-visual-list">
                <img src="/images/icon/icon_streaming.png" alt="">
                <h3>STREAMING</h3>
                <span>유명 BJ 영입<br>스트리밍</span>
              </li>
            </ul>
            <input type="button" onclick="location.href='/cs_etc/three_vision';" value="자세히보기" class="indetail-btn">
        </div>
        <div class="m-main-videocon">
            <span class="sub-title" id="youtube_sub_title">{{ isset($youtube_active->sub_text) ? $youtube_active->sub_text : 'SPOWIDE YOUTUBE #2' }}</span>
            <h2 class="big-title"><span id="youtube_big_title_first">{{ isset($youtube_active->title2) ? $youtube_active->title2 : 'SPOWIDE X' }}</span><br><span id="youtube_big_title_second">{{ isset($youtube_active->title) ? $youtube_active->title : '이강이간다' }}</span></h2>
            <div class="video-container">
                <iframe id="youtube_video" src="https://www.youtube.com/embed/{{ isset($youtube_active->url) ? $youtube_active->url : 'nYUOw0IT_ec' }}?autoplay=0&amp;loop=1;playlist=nYUOw0IT_ec" style="width:100%;height:100%;" frameborder="0" allow="encrypted-media" allowfullscreen ></iframe>
            </div>
            <p class="sub-text" id="youtube_sub_text">{{ isset($youtube_active->sub_title) ? $youtube_active->sub_title : '연예인 \'조윤호\'개그맨 축구 실력 테스트' }}</p>
            <dl class="video-contents">
                <dt class="video-contents-tit">contents</dt>
                <dd id="youtube_contents_a">a. {{ isset($youtube_active->contents_a) ? $youtube_active->contents_a : '조윤호는 스페인 선출?!' }}</dd>
                <dd id="youtube_contents_b">b. {{ isset($youtube_active->contents_b) ? $youtube_active->contents_b : '트래핑 테스트' }}</dd>
                <dd id="youtube_contents_c">c. {{ isset($youtube_active->contents_c) ? $youtube_active->contents_c : '발리 테스트' }}</dd>
            </dl>
            <!-- TODO: 스포와이드 영상 리스트 -->
            <div class="video-list-area">
                <h3 class="in-title">스포와이드 영상 리스트</h3>
                <!-- 영상 리스트 -->
                <div id="spowide_video_list" class="swiper-container">
                    <ul class="video-list-group swiper-wrapper" id="youtube_list">
                        <!-- 영상 링크 -->
                        @forelse($youtube_list as $youtube_item)
                        <li class="video-list swiper-slide" data-id = "{{ $youtube_item->id }}">
                            <a href="#" target="_blank">
                                <div class="video-list-card"><span class="in-txt">{{ isset($youtube_item->title2) ? $youtube_item->title2 : 'SPOWIDE X' }} {{ isset($youtube_item->title) ? $youtube_item->title : '이강이간다' }}</span></div>
                            </a>
                        </li>
                        @empty
                        <li class="video-list swiper-slide">
                            <a href="#" target="_blank">
                                <div class="video-list-card"><span class="in-txt">spowide X 이강이간다</span></div>
                            </a>
                        </li>
                        @endforelse
                        <!-- E -->
                        <!-- 영상 링크 -->
                        <!-- <li class="video-list swiper-slide">
                            <a href="#">
                                <div class="video-list-card"><span class="in-txt">#2. spowide X 이강이 간다</span></div>
                            </a>
                        </li> -->
                        <!-- E -->
                    </ul>
                    <div class="vd-swiper-button-next swiper-button-next"></div>
                    <div class="vd-swiper-button-prev swiper-button-prev"></div>
                </div>
                <!-- E -->
            </div>
            <!-- TODO:END -->
        </div>
    </div>
    <!-- 191202 4) E -->

    <!-- 191202 5) 모바일메인리뉴얼 - 게임스케쥴영역 -->
    <div class="m-main-gameschedule-area">
        <div class="_inner">
            <h3 class="big-title">Game Schedule</h3>
            <p class="sub-text">경기 일정을 확인해보세요!</p>
            <!-- 왼쪽 운동카테고리 탭 -->
            <div class="game-category-tab">
                <button id="view_game_football" type="button" class="game-category-btn football active">축　 구</button>
                <button id="view_game_baseball" type="button" class="game-category-btn baseball">야　 구</button>
                <button id="view_game_basketball" type="button" class="game-category-btn basketball">농　 구</button>
            </div>
            <!-- E -->
            <!-- TODO: 경기일정 스케줄 -->
            <div class="game-content-wrap">
                <!-- 축구 -->
                <div id="game_football" class="game-list-group active">
                    <!-- 날짜별 리스트 -->
                    <div class="game-list-area game-swiper-container swiper-container">
                        <ul class="swiper-wrapper">
                            @forelse($game_schedules as $key=>$game_schedule)
                            <!-- DAY -->
                            <li class="game-list swiper-slide">
                                <div class="game-list-card">
                                    <h4 class="game-date">{{ date('y.m.d', strtotime($game_schedule->date)) }}({{$yoil[date('w', strtotime($game_schedule->date))]}})</h4>
                                    @forelse($all_schedules[$key] as $schedule_list)
                                        @if(isset($schedule_list->game_type))
                                            @if($schedule_list->game_type == '축구')
                                            <div class="game-schedule">
                                                <span class="game-league">{{$schedule_list->league_name}}</span>
                                                <small class="game-time">{{$schedule_list->game_time}}</small>
                                                <div class="match-team">
                                                    <span>{{$schedule_list->team1}}</span>
                                                    <img src="/storage/image/game_schedule{{$schedule_list->team1_symbol}}" alt="coin image">
                                                    <span class="game-score-mobi">{{ isset($schedule_list->team1_score) ? $schedule_list->team1_score : 0}}</span>
                                                </div>
                                                <img src="/images/icon/icon-vs.png" alt="vs image" class="vs-image">
                                                <div class="match-team">
                                                    <span>{{$schedule_list->team2}}</span>
                                                    <img src="/storage/image/game_schedule{{$schedule_list->team2_symbol}}" alt="coin image">
                                                    <span class="game-score-mobi">{{ isset($schedule_list->team2_score) ? $schedule_list->team2_score : 0}}</span>
                                                </div>
                                            </div>
                                            @endif
                                        @else
                                            <div class="game-schedule">
                                                <span class="game-league">{{$schedule_list->league_name}}</span>
                                                <small class="game-time">{{$schedule_list->game_time}}</small>
                                                <div class="match-team">
                                                    <span>{{$schedule_list->team1}}</span>
                                                    <img src="/storage/image/game_schedule{{$schedule_list->team1_symbol}}" alt="coin image">
                                                    <span class="game-score-mobi">{{ isset($schedule_list->team1_score) ? $schedule_list->team1_score : 0}}</span>
                                                </div>
                                                <img src="/images/icon/icon-vs.png" alt="vs image" class="vs-image">
                                                <div class="match-team">
                                                    <span>{{$schedule_list->team2}}</span>
                                                    <img src="/storage/image/game_schedule{{$schedule_list->team2_symbol}}" alt="coin image">
                                                    <span class="game-score-mobi">{{ isset($schedule_list->team2_score) ? $schedule_list->team2_score : 0}}</span>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                    <div>경기 일정이 없습니다.</div>
                                    @endforelse
                                    @if($football_count == 0)
                                    <div>경기 일정이 없습니다.</div>
                                    @endif
                                </div>
                            </li>
                            <!-- E -->
                            @empty
                            <div>경기 일정이 없습니다.</div>
                            @endforelse
                            <!-- DAY -->
                            <!-- E -->
                        </ul>
                        <div class="gm-swiper-button-next swiper-button-prev"></div>
                        <div class="gm-swiper-button-prev swiper-button-prev"></div>
                    </div>
                    <!-- E -->
                </div>
                <!-- E -->
                <!-- 야구 -->
                <div id="game_baseball" class="game-list-group">
                    <!-- 날짜별 리스트 -->
                    <div class="game-list-area game-swiper-container swiper-container">
                        <ul class="swiper-wrapper">
                            @forelse($game_schedules as $key=>$game_schedule)
                            <!-- DAY -->
                            <li class="game-list swiper-slide">
                                <div class="game-list-card">
                                    <h4 class="game-date">{{ date('y.m.d', strtotime($game_schedule->date)) }}({{$yoil[date('w', strtotime($game_schedule->date))]}})</h4>
                                    @forelse($all_schedules[$key] as $schedule_list)
                                        @if(isset($schedule_list->game_type))
                                            @if($schedule_list->game_type == '야구')
                                            <div class="game-schedule">
                                                <span class="game-league">{{$schedule_list->league_name}}</span>
                                                <small class="game-time">{{$schedule_list->game_time}}</small>
                                                <div class="match-team">
                                                    <span>{{$schedule_list->team1}}</span>
                                                    <img src="/storage/image/game_schedule{{$schedule_list->team1_symbol}}" alt="coin image">
                                                    <span class="game-score-mobi">{{ isset($schedule_list->team1_score) ? $schedule_list->team1_score : 0}}</span>
                                                </div>
                                                <img src="/images/icon/icon-vs.png" alt="vs image" class="vs-image">
                                                <div class="match-team">
                                                    <span>{{$schedule_list->team2}}</span>
                                                    <img src="/storage/image/game_schedule{{$schedule_list->team2_symbol}}" alt="coin image">
                                                    <span class="game-score-mobi">{{ isset($schedule_list->team2_score) ? $schedule_list->team2_score : 0}}</span>
                                                </div>
                                            </div>
                                            @endif
                                        @endif
                                    @empty
                                    <div>경기 일정이 없습니다.</div>
                                    @endforelse
                                    @if($baseball_count == 0)
                                    <div>경기 일정이 없습니다.</div>
                                    @endif
                                </div>
                            </li>
                            <!-- E -->
                            @empty
                            <div>경기 일정이 없습니다.</div>
                            @endforelse
                        </ul>
                        <div class="gm-swiper-button-next swiper-button-prev"></div>
                        <div class="gm-swiper-button-prev swiper-button-prev"></div>
                    </div>
                    <!-- E -->
                </div>
                <!-- E -->
                <!-- 농구 -->
                <div id="game_basketball" class="game-list-group">
                    <!-- 날짜별 리스트 -->
                    <div class="game-list-area game-swiper-container swiper-container">
                        <ul class="swiper-wrapper">
                                @forelse($game_schedules as $key=>$game_schedule)
                                <!-- DAY -->
                                <li class="game-list swiper-slide">
                                    <div class="game-list-card">
                                        <h4 class="game-date">{{ date('y.m.d', strtotime($game_schedule->date)) }}({{$yoil[date('w', strtotime($game_schedule->date))]}})</h4>
                                        @forelse($all_schedules[$key] as $schedule_list)
                                            @if(isset($schedule_list->game_type))
                                                @if($schedule_list->game_type == '농구')
                                                <div class="game-schedule">
                                                    <span class="game-league">{{$schedule_list->league_name}}</span>
                                                    <small class="game-time">{{$schedule_list->game_time}}</small>
                                                    <div class="match-team">
                                                        <span>{{$schedule_list->team1}}</span>
                                                        <img src="/storage/image/game_schedule{{$schedule_list->team1_symbol}}" alt="coin image">
                                                        <span class="game-score-mobi">{{ isset($schedule_list->team1_score) ? $schedule_list->team1_score : 0}}</span>
                                                    </div>
                                                    <img src="/images/icon/icon-vs.png" alt="vs image" class="vs-image">
                                                    <div class="match-team">
                                                        <span>{{$schedule_list->team2}}</span>
                                                        <img src="/storage/image/game_schedule{{$schedule_list->team2_symbol}}" alt="coin image">
                                                        <span class="game-score-mobi">{{ isset($schedule_list->team2_score) ? $schedule_list->team2_score : 0}}</span>
                                                    </div>
                                                </div>
                                                @endif
                                            @endif
                                        @empty
                                        <div>경기 일정이 없습니다.</div>
                                        @endforelse
                                        @if($basketball_count == 0)
                                        <div>경기 일정이 없습니다.</div>
                                        @endif
                                    </div>
                                </li>
                                <!-- E -->
                                @empty
                                <div>경기 일정이 없습니다.</div>
                                @endforelse
                        </ul>
                        <div class="gm-swiper-button-next swiper-button-prev"></div>
                        <div class="gm-swiper-button-prev swiper-button-prev"></div>
                    </div>
                    <!-- E -->
                </div>
                <!-- E -->
            </div>
            <!-- TODO:END -->
        </div>
    </div>
    <!-- 191202 5) E -->

    <!-- 191202 6) 모바일메인리뉴얼 - 푸터영역 -->
    <div class="m-main-footer-area">
        <img src="/images/logos/header_logo_blue-2.svg" alt="logo" class="m-main-footer-logo">
        <span>(주)스포홀딩스</span>
        <ul class="m-main-footer-info">
            <li>서울시 강남구 삼성동 77-11 랜드마크빌딩 14F</li>
            <li>대표  고광문  |  사업자등록번호 476-88-01107</li>
        </ul>
        <small>Copyright 2019 spowide - All Rights Reserved</small>
    </div>
    <!-- 191202 6) E -->

</div>
<script src="/vendor/datatables/jquery.dataTables.js"></script>
<script>
$(document).ready(function() {
        
    $('#coin_table_usd').DataTable({
        paging:false,
        searching:false,
        info:false,
        lengthChange:false
    });

    $('#coin_table_krw').DataTable({
        paging:false,
        searching:false,
        info:false,
        lengthChange:false
    });
});
/* ---------------- 191202 메인리뉴얼 jquery ---------------- */
// 게임 카테고리 탭 누르면 보이는 화면 바뀜
$('#view_game_football').click(function(){
    $('.game-category-btn').removeClass('active');
    $(this).addClass('active');
    $('#game_football').addClass('active');
    $('.game-list-group').not('#game_football').removeClass('active');
})
$('#view_game_baseball').click(function(){
    $('.game-category-btn').removeClass('active');
    $(this).addClass('active');
    $('#game_baseball').addClass('active');
    $('.game-list-group').not('#game_baseball').removeClass('active');
})
$('#view_game_basketball').click(function(){
    $('.game-category-btn').removeClass('active');
    $(this).addClass('active');
    $('#game_basketball').addClass('active');
    $('.game-list-group').not('#game_basketball').removeClass('active');
});
// E
// 이벤트배너영역
var swiper_banner = new Swiper('#banner_list', {
    loop: true,
    pagination: {
    el: '.swiper-pagination',
    },
    autoplay: {
        delay: 4000,
        disableOnInteraction: false,
    }
})
// E
// 영상 리스트
var swiper_video = new Swiper('#spowide_video_list', {
    navigation: {
        nextEl: '.vd-swiper-button-next',
        prevEl: '.vd-swiper-button-prev',
    },
});
swiper_video.on('slideChange',function(){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var youtube_id = $('#youtube_list').children().eq(swiper_video.activeIndex).data("id");
    $.ajax({
        url : "/youtube/list",
        type : "POST",
        data : {_token : CSRF_TOKEN, youtube_id : youtube_id},
        dataType : "JSON"
    }).done(function(data) {
        console.log(data);
        $('#youtube_sub_title').text(data.sub_text);
        $('#youtube_big_title_first').text(data.title2);
        $('#youtube_big_title_second').text(data.title);
        $('#youtube_video').attr("src","https://www.youtube.com/embed/"+data.url+"?autoplay=0&amp;loop=1;playlist=nYUOw0IT_ec");
        $('#youtube_sub_text').text(data.sub_title);
        $('#youtube_contents_a').text("a. " + data.contents_a);
        $('#youtube_contents_b').text("b. " + data.contents_b);
        $('#youtube_contents_c').text("c. " + data.contents_c);
    }).fail(function(){
        console.log("error");
    });
   
});
// E
// 게임스케줄 리스트
var swiper_game = new Swiper('.game-swiper-container', {
    loop: true,
    navigation: {
        nextEl: '.gm-swiper-button-next',
        prevEl: '.gm-swiper-button-prev',
    },
});
// E
/* ---------------- END ---------------- */
</script>
@endsection
