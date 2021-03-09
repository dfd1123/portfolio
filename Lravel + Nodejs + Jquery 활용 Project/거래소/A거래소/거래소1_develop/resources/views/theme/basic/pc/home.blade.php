@extends(session('theme').'.pc.layouts.app')

@section('content')

<div id="main_page_wrapper" style="overflow-x: hidden;">
<div class="main_bg">
<div class="main1_contents_wrap">
        <div class="app_download_btn_wrap">
          <button type="button" onclick="window.open('https://apps.apple.com/kr/app/%EC%8A%A4%ED%8F%AC%EC%99%80%EC%9D%B4%EB%93%9C/id1486058082')"><img src="/images/three_vision/button-app.png" alt="" /></button>
          <button type="button" onclick="window.open('https://play.google.com/store/apps/details?id=com.spowide.www')"><img src="/images/three_vision/button-google.png" alt="" /></button>
        </div>
        <div class="main_contetns">
          <div class="main_left">
            <div class="series_a">
              <span class="wow fadeIn" data-wow-delay="0.1s">
                SPOWIDE SERIES A
              </span>
            </div>
            <div class="enfect_text wow fadeIn" data-wow-delay="0.3s">
              <h1 class="highlight">3</h1>
              <h1>V</h1>
              <h1>i</h1>
              <h1>s</h1>
              <h1>i</h1>
              <h1>o</h1>
              <h1>n</h1>
            </div>
            <ul>
              <li class="wow fadeInRight" data-wow-delay="0.4s"><a>STORE</a></li>
              <li class="wow fadeInRight" data-wow-delay="0.5s"><a>STAKING</a></li>
              <li class="wow fadeInRight" data-wow-delay="0.6s"><a>STREAMING</a></li>
            </ul>
            <div class="detail_btn">
              <button type="button" onclick="location.href='/cs_etc/three_vision';" class="wow fadeInUp" data-wow-delay="0.8s">자세히보기</button>
            </div>
          </div>
          <div class="main_right">
            <img src="/images/icon/main_ele_01.svg" alt="" class="deco_star deco_star--1st"/>
            <img src="/images/icon/main_ele_02.svg" alt="" class="deco_star deco_star--2nd"/>
            <img src="/images/icon/main_ele_03.svg" alt="" class="deco_star deco_star--3rd"/>
            <img src="/images/icon/main_ele_04.svg" alt="" class="deco_star deco_star--4th"/>
            <img src="/images/icon/main_ele_05.svg" alt="" class="deco_star deco_star--5th"/>
            <img src="/images/icon/main_ele_03.svg" alt="" class="deco_star deco_star--6th"/>
            <ul>
              <li class="wow fadeInUp" data-wow-delay="0.8s">
                <img src="/images/icon/icon_store.png" alt="" />
                <h3>STORE</h3>
                <span>코인 트레이드<br/>쇼핑몰</span>
              </li>
              <li class="wow fadeInUp" data-wow-delay="1s">
                <img src="/images/icon/icon_staking.png" alt="" />
                <h3>STAKING</h3>
                <span>경기 승리 시 보유팀<br/>리워드 보상</span>
              </li>
              <li class="wow fadeInUp" data-wow-delay="0.9s">
                <img src="/images/icon/icon_streaming.png" alt="" />
                <h3>STREAMING</h3>
                <span>유명 BJ 영입<br/>스트리밍</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- SPOWIDE VIDEO -->
      <div class="spowide_video_wrap">
        <div class="video_con wow fadeInLeft" data-wow-delay="0.1s">
        <iframe id="youtube_video" src="https://www.youtube.com/embed/{{ isset($youtube_active->url) ? $youtube_active->url : 'nYUOw0IT_ec' }}?autoplay=0&amp;loop=1;playlist=nYUOw0IT_ec" style="width:100%;height:100%;" frameborder="0" allow="encrypted-media" allowfullscreen ></iframe>
        </div>
        <div class="text_con wow fadeInRight" data-wow-delay="0.1s">
          <h2 id="youtube_sub_title">{{ isset($youtube_active->sub_text) ? $youtube_active->sub_text : 'SPOWIDE YOUTUBE #2' }}</h2>
          <h1 id="youtube_big_title_first">{{ isset($youtube_active->title2) ? $youtube_active->title2 : 'SPOWIDE X' }}</h1><h1 id="youtube_big_title_second">{{ isset($youtube_active->title) ? $youtube_active->title : '이강이간다' }}</h1>
          <p id="youtube_sub_text">{{ isset($youtube_active->sub_title) ? $youtube_active->sub_title : '연예인 \'조윤호\'개그맨 축구 실력 테스트' }}</p>
          <span>CONTENTS</span>
          <ul>
            <li id="youtube_contents_a">a. {{ isset($youtube_active->contents_a) ? $youtube_active->contents_a : '조윤호는 스페인 선출?!' }}</li>
            <li id="youtube_contents_b">b. {{ isset($youtube_active->contents_b) ? $youtube_active->contents_b : '트래핑 테스트' }}</li>
            <li id="youtube_contents_c">c. {{ isset($youtube_active->contents_c) ? $youtube_active->contents_c : '발리 테스트' }}</li>
          </ul>
        </div>
       
        <div class="video_list_con wow fadeInRight">
          <h3 class="in_title">
            <img src="/images/icon/slate_icon.svg" alt="slate icon">
            <span>스포와이드 영상 리스트</span>
          </h3>
          <div class="video_list_wrap">
            <ul class="video_list_group">
              @forelse($youtube_list as $youtube_item)
              <li class="in_list youtube_list_click" data-id = "{{ $youtube_item->id }}">
                <a href="#" onclick="event.preventDefault();">{{ isset($youtube_item->title2) ? $youtube_item->title2 : 'SPOWIDE X' }} {{ isset($youtube_item->title) ? $youtube_item->title : '이강이간다' }}</a>
              </li>
              @empty
              <li class="in_list">
                <a href="#" onclick="event.preventDefault();">#2. spowide X 이강이간다</a>
              </li>
              @endforelse
              
             
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- 배너 슬라이드 -->
    <div class="wrap_slide">
      <div class="slide_inner">
          <!-- <h4 class="topic">MARKETS</h4> -->
        <div id="coin_slider" class="slick_items">

          @forelse($coins as $coin)
          <div class="slide" data-coin="{{$coin->api}}" onclick="/market_krw/{{$coin->api}}">
            <a href="/market_krw/{{$coin->api}}">
            <div class="slide_img">
              <!-- <div class="markets_banner" style="background-image: url(/images/banner_event.png)"></div> -->
              <div class="markets_con">
                <h5 class="markets_currency"><b>{{ $coin->symbol }}</b>&nbsp;&nbsp; | &nbsp;&nbsp;{{ str_replace('<small>Fanclub Coin</small>','',__('coin_name.'.$coin->api)) }}</h5>
                <p class="markets_txt">
                  {{ number_format($coin->last_trade_price_krw,0) }}<span class="market_krw">원</span>
                </p>
                @if((float) $coin->price_change_24h_krw < 0)
                <span class="markets_ratio ratio_down">{{number_format($coin->price_change_24h_krw,0)}} ({{number_format($coin->percent_change_24h_krw,2)}}%) ▼</span>
                @elseif((float) $coin->price_change_24h_krw > 0)
                <span class="markets_ratio ratio_up">+{{number_format($coin->price_change_24h_krw,0)}} (+{{number_format($coin->percent_change_24h_krw,2)}}%) ▲</span>
                @else
                <span class="markets_ratio">0 (0.00%)</span>
                @endif
              <div class="markets_chart"  style="height: 55px; width: 105px" data-coin="{{ $coin->api }}"></div>
              </div>
            </div>
            </a>
          </div>
          @empty

          @endforelse
        </div>
      </div>
    </div>
    <!-- 경기일정 -->
    <!-- <div id="game_schedule_wrap" class="wow fadeInLeft" data-wow-delay="0.1s">
      <div class="game_schedule_con">
        <div class="schedule_left">
          <h1 class="wow fadeInUp" data-wow-delay="0.3s">Game<br/>Schedule</h1>
          <p class="wow fadeInUp" data-wow-delay="0.4s">축구 경기 일정을<br/>확인해보세요!</p>
        </div>
        <div class="schedule_right wow flipInX" data-wow-delay="0.6s">
          <div class="game_schedule_slide">
          @foreach($game_schedules as $key=>$game_schedule)
          <div>
            <h5>{{date('y.m.d', strtotime($game_schedule->date))}}({{$yoil[date('w', strtotime($game_schedule->date))]}})</h5>
            <p class="hide">IN <span>Benteler-Arena</span></p>
            <div class="schedule_li_wrap">
              <ul class="schedule_li_con">
                  @forelse($all_schedules[$key] as $schedule_list)
                  <li class="schedule_li">
                    @if(isset($schedule_list->league_name))
                      <span style="top: 64px;">{{$schedule_list->game_time}}</span>
                      <div class="league_name">{{$schedule_list->league_name}}</div>
                    @else
                      <span>{{$schedule_list->game_time}}</span>
                    @endif
                    <div class="verse_con">
                      <div class="team1_box">
                        <span>{{$schedule_list->team1}}</span>
                        <div class="symbol_icon">
                          <img src="/storage/image/game_schedule{{$schedule_list->team1_symbol}}" alt="" />
                        </div>
                      </div>
                      <div class="team2_box">
                        <div class="symbol_icon">
                          <img src="/storage/image/game_schedule{{$schedule_list->team2_symbol}}" alt="" />
                        </div>
                        <span>{{$schedule_list->team2}}</span>
                      </div>
                    </div>
                  </li>
                  @empty
                  <li class="schedule_li">
                    <span class="no_game_today">AM 00:00</span>
                    <div class="verse_con">
                      <div class="no_game_today">
                        경기 일정이 없습니다.
                      </div>
                    </div>
                  </li>
                  @endforelse
              </ul>
            </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div> -->
    <!-- 191202 웹리뉴얼 게임스케쥴영역 -->
    <div id="game-schedule-wrap">
      <div class="game-schedule-inner">
        <!-- 왼쪽 운동카테고리 탭 -->
        <div class="game-category-tab">
          <button id="view_game_football" type="button" class="game-category-btn football on"></button>
          <button id="view_game_baseball" type="button" class="game-category-btn baseball"></button>
          <button id="view_game_basketball" type="button" class="game-category-btn basketball"></button>
        </div>
        <!-- E -->
        <!-- TODO: 경기일정 스케줄 -->
        <div class="game-list-area">
          <!-- 축구 -->
          <div id="game_football" class="game-list-group active">
            <!-- 날짜별 리스트 -->
            <div class="swiper-container">
              <ul class="swiper-wrapper">
                <!-- 오늘 -->
                @forelse($game_schedules as $key=>$game_schedule)
                <li class="game-list swiper-slide">
                  <div class="game-list-inner-box">
                    <div class="game-list-hddate">
                      <div class="in-arrow"><div class="swiper-button-prev"></div><b>{{ isset($game_schedules[$key-1]->date) ? date('Y.m.d', strtotime($game_schedules[$key - 1]->date)).' ('.$yoil[date('w', strtotime($game_schedules[$key - 1]->date))].')' : '' }}</b></div>
                      <h3>{{ date('Y.m.d', strtotime($game_schedule->date)) }} ({{$yoil[date('w', strtotime($game_schedule->date))]}}) <b>{{ date('Y-m-d', strtotime($game_schedule->date)) == date('Y-m-d') ? '오늘' : ''}}</b></h3>
                      <div class="in-arrow"><b>{{ isset($game_schedules[$key+1]->date) ? date('Y.m.d', strtotime($game_schedules[$key + 1]->date)).' ('.$yoil[date('w', strtotime($game_schedules[$key + 1]->date))].')' : '' }} </b><div class="swiper-button-next"></div></div>
                    </div>
                    <div class="game-card-group-wrapper">
                      <ul class="game-card-group">
                        @forelse($all_schedules[$key] as $schedule_list)
                          @if(isset($schedule_list->game_type))
                            @if($schedule_list->game_type == '축구')
                            <li class="game-schedule">
                              <div class="game-date-infos">
                                <span class="game-date">{{$schedule_list->game_time}}</span>
                                <span class="game-league-tit">{{$schedule_list->league_name}}</span>  
                              </div>
                              <div class="match-team-item">
                                <div class="match-team match-team-left"><span>{{$schedule_list->team1}}</span><img src="/storage/image/game_schedule{{$schedule_list->team1_symbol}}" alt="coin image"></div>
                                <span class="game-score">{{ isset($schedule_list->team1_score) ? $schedule_list->team1_score : 0}}</span>
                              </div>
                              <div class="game-league">
                                <img src="/images/icon/icon-vs-web.png" alt="vs image">
                              </div>
                              <div class="match-team-item">
                                <span class="game-score">{{ isset($schedule_list->team2_score) ? $schedule_list->team2_score : 0}}</span>
                                <div class="match-team match-team-right"><span>{{$schedule_list->team2}}</span><img src="/storage/image/game_schedule{{$schedule_list->team2_symbol}}" alt="coin image"></div>
                              </div>
                            </li>
                            @endif
                          @else
                          <li class="game-schedule">
                            <div class="game-date-infos">
                              <span class="game-date">{{$schedule_list->game_time}}</span>
                              <span class="game-league-tit">{{$schedule_list->league_name}}</span>  
                            </div>
                            <div class="match-team-item">
                              <div class="match-team match-team-left"><span>{{$schedule_list->team1}}</span><img src="/storage/image/game_schedule{{$schedule_list->team1_symbol}}" alt="coin image"></div>
                              <span class="game-score">{{ isset($schedule_list->team1_score) ? $schedule_list->team1_score : 0}}</span>
                            </div>
                            <div class="game-league">
                              <img src="/images/icon/icon-vs-web.png" alt="vs image">
                            </div>
                            <div class="match-team-item">
                              <span class="game-score">{{ isset($schedule_list->team2_score) ? $schedule_list->team2_score : 0}}</span>
                              <div class="match-team match-team-right"><span>{{$schedule_list->team2}}</span><img src="/storage/image/game_schedule{{$schedule_list->team2_symbol}}" alt="coin image"></div>
                            </div>
                          </li>
                          @endif
                        @empty
                        <li class="nothing-list">
                            <img src="/images/icon/icon-nothing-foot.svg" alt="icon">
                            <span>경기 일정이 없습니다.</span>
                        </li>
                        @endforelse
                        @if($football_count == 0)
                        <div class="nothing-list">
                            <img src="/images/icon/icon-nothing-foot.svg" alt="icon">
                            <span>경기 일정이 없습니다.</span>
                        </div>
                        @endif
                      </ul>
                    </div>
                  </div>
                </li>
                @empty
                <li class="nothing-list">
                    <img src="/images/icon/icon-nothing-foot.svg" alt="icon">
                    <span>경기 일정이 없습니다.</span>
                </li>
                @endforelse
                <!-- E -->
              </ul>
            </div>
            <!-- E -->
          </div>
          <!-- E -->
          <!-- 야구 -->
          <div id="game_baseball" class="game-list-group">
            <!-- 날짜별 리스트 -->
            <div class="swiper-container">
              <ul class="swiper-wrapper">
                <!-- 오늘 -->
                @forelse($game_schedules as $key=>$game_schedule)
                <li class="game-list swiper-slide">
                  <div class="game-list-inner-box">
                    <div class="game-list-hddate">
                      <div class="in-arrow"><div class="swiper-button-prev"></div><b>{{ isset($game_schedules[$key-1]->date) ? date('Y.m.d', strtotime($game_schedules[$key - 1]->date)).' ('.$yoil[date('w', strtotime($game_schedules[$key - 1]->date))].')' : '' }}</b></div>
                      <h3>{{ date('Y.m.d', strtotime($game_schedule->date)) }} ({{$yoil[date('w', strtotime($game_schedule->date))]}}) <b>{{ date('Y-m-d', strtotime($game_schedule->date)) == date('Y-m-d') ? '오늘' : ''}}</b></h3>
                      <div class="in-arrow"><b>{{ isset($game_schedules[$key+1]->date) ? date('Y.m.d', strtotime($game_schedules[$key + 1]->date)).' ('.$yoil[date('w', strtotime($game_schedules[$key + 1]->date))].')' : '' }} </b><div class="swiper-button-next"></div></div>
                    </div>
                    <div class="game-card-group-wrapper">
                      <ul class="game-card-group">
                        @forelse($all_schedules[$key] as $schedule_list)
                          @if(isset($schedule_list->game_type))
                            @if($schedule_list->game_type == '야구')
                            <li class="game-schedule">
                              <div class="game-date-infos">
                                <span class="game-date">{{$schedule_list->game_time}}</span>
                                <span class="game-league-tit">{{$schedule_list->league_name}}</span>  
                              </div>
                              <div class="match-team-item">
                                <div class="match-team match-team-left"><span>{{$schedule_list->team1}}</span><img src="/storage/image/game_schedule{{$schedule_list->team1_symbol}}" alt="coin image"></div>
                                <span class="game-score">{{ isset($schedule_list->team1_score) ? $schedule_list->team1_score : 0}}</span>
                              </div>
                              <div class="game-league">
                                <img src="/images/icon/icon-vs-web.png" alt="vs image">
                              </div>
                              <div class="match-team-item">
                                <span class="game-score">{{ isset($schedule_list->team2_score) ? $schedule_list->team2_score : 0}}</span>
                                <div class="match-team match-team-right"><span>{{$schedule_list->team2}}</span><img src="/storage/image/game_schedule{{$schedule_list->team2_symbol}}" alt="coin image"></div>
                              </div>
                            </li>
                            @endif
                          @endif
                        @empty
                        <li class="nothing-list">
                          <img src="/images/icon/icon-nothing-base.svg" alt="icon">
                          <span>경기 일정이 없습니다.</span>
                        </li>
                        @endforelse
                        @if($baseball_count == 0)
                        <div class="nothing-list">
                          <img src="/images/icon/icon-nothing-base.svg" alt="icon">
                          <span>경기 일정이 없습니다.</span>
                        </div>
                        @endif
                      </ul>
                    </div>
                  </div>
                </li>
                @empty
                <li class="nothing-list">
                    <img src="/images/icon/icon-nothing-base.svg" alt="icon">
                    <span>경기 일정이 없습니다.</span>
                </li>
                @endforelse
                <!-- E -->
              </ul>
            </div>
            <!-- E -->
          </div>
          <!-- E -->
          <!-- 농구 -->
          <div id="game_basketball" class="game-list-group">
            <!-- 날짜별 리스트 -->
            <div class="swiper-container">
              <ul class="swiper-wrapper">
                <!-- 오늘 -->
                @forelse($game_schedules as $key=>$game_schedule)
                <li class="game-list swiper-slide">
                  <div class="game-list-inner-box">
                    <div class="game-list-hddate">
                      <div class="in-arrow"><div class="swiper-button-prev"></div><b>{{ isset($game_schedules[$key-1]->date) ? date('Y.m.d', strtotime($game_schedules[$key - 1]->date)).' ('.$yoil[date('w', strtotime($game_schedules[$key - 1]->date))].')' : '' }}</b></div>
                      <h3>{{ date('Y.m.d', strtotime($game_schedule->date)) }} ({{$yoil[date('w', strtotime($game_schedule->date))]}}) <b>{{ date('Y-m-d', strtotime($game_schedule->date)) == date('Y-m-d') ? '오늘' : ''}}</b></h3>
                      <div class="in-arrow"><b>{{ isset($game_schedules[$key+1]->date) ? date('Y.m.d', strtotime($game_schedules[$key + 1]->date)).' ('.$yoil[date('w', strtotime($game_schedules[$key + 1]->date))].')' : '' }} </b><div class="swiper-button-next"></div></div>
                    </div>
                    <div class="game-card-group-wrapper">
                      <ul class="game-card-group">
                        @forelse($all_schedules[$key] as $schedule_list)
                          @if(isset($schedule_list->game_type))
                            @if($schedule_list->game_type == '농구')
                            <li class="game-schedule">
                              <div class="game-date-infos">
                                <span class="game-date">{{$schedule_list->game_time}}</span>
                                <span class="game-league-tit">{{$schedule_list->league_name}}</span>  
                              </div>
                              <div class="match-team-item">
                                <div class="match-team match-team-left"><span>{{$schedule_list->team1}}</span><img src="/storage/image/game_schedule{{$schedule_list->team1_symbol}}" alt="coin image"></div>
                                <span class="game-score">{{ isset($schedule_list->team1_score) ? $schedule_list->team1_score : 0}}</span>
                              </div>
                              <div class="game-league">
                                <img src="/images/icon/icon-vs-web.png" alt="vs image">
                              </div>
                              <div class="match-team-item">
                                <span class="game-score">{{ isset($schedule_list->team2_score) ? $schedule_list->team2_score : 0}}</span>
                                <div class="match-team match-team-right"><span>{{$schedule_list->team2}}</span><img src="/storage/image/game_schedule{{$schedule_list->team2_symbol}}" alt="coin image"></div>
                              </div>
                            </li>
                            @endif
                          @endif
                        @empty
                        <li class="nothing-list">
                          <img src="/images/icon/icon-nothing-basket.svg" alt="icon">
                          <span>경기 일정이 없습니다.</span>
                        </li>
                        @endforelse
                        @if($basketball_count == 0)
                        <div class="nothing-list">
                          <img src="/images/icon/icon-nothing-basket.svg" alt="icon">
                          <span>경기 일정이 없습니다.</span>
                        </div>
                        @endif
                      </ul>
                    </div>
                  </div>
                </li>
                @empty
                <li class="nothing-list">
                  <img src="/images/icon/icon-nothing-basket.svg" alt="icon">
                  <span>경기 일정이 없습니다.</span>
                </li>
                @endforelse
                <!-- E -->
              </ul>
            </div>
            <!-- E -->
          </div>
          <!-- E -->
        </div>
        <!-- TODO:END -->
      </div>
    </div>
    <!-- 191202 게임스케쥴영역 E -->
    <!-- 코인 리스트 슬라이드 -->
    <div id="coin_list_wrap">
      <div class="coin_list_con">
        <h1 class="wow fadeIn">Fanclub Coin</h1>
        <p class="wow fadeInDown" data-wow-delay="0.2s">세계 유명 팬클럽 코인 현황을 확인해보세요</p>
        <div class="coin_list_slide_wrap wow fadeInDown"  data-wow-delay="0.5s">
          <div class="coin_list_slide">
            <a href="/market_krw/mnu">
              <div class="coin_list_hd">
                <img src="/images/coin_img/mnu_95.png" alt="" />
              </div>
              <p class="coin_list_name">맨유</p>
              <div class="coin_list_infor">
                <span>MNU</span>
                <span>Fanclub Coin</span>
              </div>
            </a>
          </div>
          <div class="coin_list_slide">
            <a href="/market_krw/bar">
              <div class="coin_list_hd">
                <img src="/images/coin_img/bar_95.png" alt="" />
              </div>
              <p class="coin_list_name">바르셀로나</p>
              <div class="coin_list_infor">
                <span>BAR</span>
                <span>Fanclub Coin</span>
              </div>
            </a>
          </div>
          <div class="coin_list_slide">
            <a href="/market_krw/rma">
              <div class="coin_list_hd">
                <img src="/images/coin_img/rma_95.png" alt="" />
              </div>
              <p class="coin_list_name">레알</p>
              <div class="coin_list_infor">
                <span>RMA</span>
                <span>Fanclub Coin</span>
              </div>
            </a>
          </div>
          <div class="coin_list_slide">
            <a href="/market_krw/che">
              <div class="coin_list_hd">
                <img src="/images/coin_img/che_95.png" alt="" />
              </div>
              <p class="coin_list_name">첼시</p>
              <div class="coin_list_infor">
                <span>CHE</span>
                <span>Fanclub Coin</span>
              </div>
            </a>
          </div>
          <div class="coin_list_slide">
            <a href="/market_krw/brn">
              <div class="coin_list_hd">
                <img src="/images/coin_img/brn_95.png" alt="" />
              </div>
              <p class="coin_list_name">바이에른뮌헨</p>
              <div class="coin_list_infor">
                <span>BRN</span>
                <span>Fanclub Coin</span>
              </div>
            </a>
          </div>
          <div class="coin_list_slide">
            <a href="/market_krw/asn">
              <div class="coin_list_hd">
                <img src="/images/coin_img/asn_95.png" alt="" />
              </div>
              <p class="coin_list_name">아스날</p>
              <div class="coin_list_infor">
                <span>ASN</span>
                <span>Fanclub Coin</span>
              </div>
            </a>
          </div>
          <div class="coin_list_slide">
            <a href="/market_krw/mct">
              <div class="coin_list_hd">
                <img src="/images/coin_img/mct_95.png" alt="" />
              </div>
              <p class="coin_list_name">맨시티</p>
              <div class="coin_list_infor">
                <span>MCT</span>
                <span>Fanclub Coin</span>
              </div>
            </a>
          </div>
          <div class="coin_list_slide">
            <a href="/market_krw/liv">
              <div class="coin_list_hd">
                <img src="/images/coin_img/liv_95.png" alt="" />
              </div>
              <p class="coin_list_name">리버풀</p>
              <div class="coin_list_infor">
                <span>LIV</span>
                <span>Fanclub Coin</span>
              </div>
            </a>
          </div>
          <div class="coin_list_slide">
            <a href="/market_krw/int">
              <div class="coin_list_hd">
                <img src="/images/coin_img/int_95.png" alt="" />
              </div>
              <p class="coin_list_name">인테르</p>
              <div class="coin_list_infor">
                <span>INT</span>
                <span>Fanclub Coin</span>
              </div>
            </a>
          </div>
          <div class="coin_list_slide">
            <a href="/market_krw/tot">
              <div class="coin_list_hd">
                <img src="/images/coin_img/tot_95.png" alt="" />
              </div>
              <p class="coin_list_name">토트넘</p>
              <div class="coin_list_infor">
                <span>TOT</span>
                <span>Fanclub Coin</span>
              </div>
            </a>
          </div>
          <div class="coin_list_slide">
            <a href="market_krw/nap">
              <div class="coin_list_hd">
                <img src="/images/coin_img/nap_95.png" alt="" />
              </div>
              <p class="coin_list_name">나폴리</p>
              <div class="coin_list_infor">
                <span>NAP</span>
                <span>Fanclub Coin</span>
              </div>
            </a>
          </div>
          <div class="coin_list_slide">
            <a href="market_krw/atm">
              <div class="coin_list_hd">
                <img src="/images/coin_img/atm_95.png" alt="" />
              </div>
              <p class="coin_list_name">AT마드리드</p>
              <div class="coin_list_infor">
                <span>ATM</span>
                <span>Fanclub Coin</span>
              </div>
            </a>
          </div>
          <div class="coin_list_slide">
            <a href="market_krw/dor">
              <div class="coin_list_hd">
                <img src="/images/coin_img/dor_95.png" alt="" />
              </div>
              <p class="coin_list_name">도르트문트</p>
              <div class="coin_list_infor">
                <span>DOR</span>
                <span>Fanclub Coin</span>
              </div>
            </a>
          </div>
          <div class="coin_list_slide">
            <a href="market_krw/val">
              <div class="coin_list_hd">
                <img src="/images/coin_img/val_95.png" alt="" />
              </div>
              <p class="coin_list_name">발렌시아</p>
              <div class="coin_list_infor">
                <span>VAL</span>
                <span>Fanclub Coin</span>
              </div>
            </a>
          </div>
          

        </div>
      </div>
    </div>
    
    <div class="news_wrap">
      <div class="news_inner">
        <h4 class="topic wow fadeIn">NEWS</h4>
        <p class="wow fadeInDown" data-wow-delay="0.2s">스포와이드의 뉴스레터</p>
        <div class="slick_news">
            @foreach($news_lists as $news_list)
              <div class="news_box">
                  <span class="news_img" style="background-image: url(/storage/image/news{{$news_list->thumb_img}})"></span>
                  <div class="news_con">
                      <p class="news_txt">{{$news_list->title}}</p>
                    <button class="view_btn" type="button" name="view_btn" onclick="location.href='{{route('news_view', $news_list->id)}}'">VIEW</button>
                  </div>
              </div>
            @endforeach
        </div>
      </div>
    </div>
    <div class="map_wrap">
      <div class="map_inner">
        <div class="map_frame">
          <h4 class="map_topic wow fadeIn">ROAD MAP</h4>
          <div class="years wow fadeInDown" data-wow-delay="0.2s">
            <span class="map_year active map_year_space this_year">2019</span>
            <span class="map_year next_year">2020</span>
          </div>
        </div>
      </div>

      <div class="ex-roadmap-wrapper">
        <ul class="ex-roadmap-navi">
          <li class="ex-roadmap-list active"><span class="ex-roadmap-dot"></span></li>
          <li class="ex-roadmap-list"><span class="ex-roadmap-dot"></span></li>
          <li class="ex-roadmap-list"><span class="ex-roadmap-dot"></span></li>
          <li class="ex-roadmap-list"><span class="ex-roadmap-dot"></span></li>
          <li class="ex-roadmap-list"><span class="ex-roadmap-dot"></span></li>
          <li class="ex-roadmap-list"><span class="ex-roadmap-dot"></span></li>
          <li class="ex-roadmap-list"><span class="ex-roadmap-dot"></span></li>
        </ul>
      </div>
      <div class="ex-roadmap-desc-wrapper">
        <ul class="ex-roadmap-desc-group">
          <li class="ex-roadmap-desc-list">
            <span class="_month wow fadeInDown" data-wow-delay="0.2s">4월</span>
            <h5 class="_tit wow fadeInDown" data-wow-delay="0.4s">SPOWIDE 탄생</h5>
            <P class="_desc wow fadeInDown" data-wow-delay="0.5s">언제, 어디에서나 거래가 가능하도록 한<br>혁신적인 스포츠 암호화폐 네트워크 플랫폼 출시</P>
            <div class="main_more wow fadeInUp" data-wow-delay="0.6s">
                <a href="{{ route('cs_etc.show', 'intro') }}" >MORE
                  <span class="more_arrow"></span>
                </a>
            </div>
          </li>
          <li class="ex-roadmap-desc-list">
            <span class="_month">9월</span>
            <h5 class="_tit">웹 기반 그래픽 거래 플랫폼</h5>
            <P class="_desc">온라인상에서 암호화폐를 거래할 수 있는<br>최첨단 그래픽 거래 플랫폼 구축</P>
            <div class="main_more">
                <a href="{{ route('cs_etc.show', 'intro') }}" >MORE
                  <span class="more_arrow"></span>
                </a>
            </div>
          </li>
          <li class="ex-roadmap-desc-list">
              <span class="_month">9월</span>
              <h5 class="_tit">비트코인 이더리움 리플 등 상장</h5>
            <P class="_desc">시장 선도 코인 상장으로 통합 거래소 플랫폼 구축</P>
            <div class="main_more">
                <a href="{{ route('cs_etc.show', 'intro') }}" >MORE
                  <span class="more_arrow"></span>
                </a>
            </div>
          </li>
          <li class="ex-roadmap-desc-list">
            <span class="_month">11월</span>
            <h5 class="_tit">경기티켓, 팀 악세서리 구매</h5>
            <P class="_desc">티켓구매 블록체인 시스템으로 입장권 위조와 복제를 방지하며<br>팀 유니폼 및 악세서리 등 50%할인 구매 페이지…</P>
            <div class="main_more">
                <a href="{{ route('cs_etc.show', 'intro') }}" >MORE
                  <span class="more_arrow"></span>
                </a>
            </div>
          </li>
          <li class="ex-roadmap-desc-list">
            <span class="_month">1월</span>
            <h5 class="_tit">실시간 스트리밍</h5>
            <P class="_desc">방송사와의 제휴를 통해 경기 영상을 보며<br>거래할 수 있도록 시스템 구축</P>
            <div class="main_more">
                <a href="{{ route('cs_etc.show', 'intro') }}" >MORE
                  <span class="more_arrow"></span>
                </a>
            </div>
          </li>
          <li class="ex-roadmap-desc-list">
            <span class="_month">3월</span>
            <h5 class="_tit">Major League Baseball Fan club coin 상장</h5>
            <P class="_desc">야구 팬클럽코인 상장으로 <br>스포츠 팬클럽 암호화폐 거래 플랫폼 저변 확대</P>
            <div class="main_more">
                <a href="{{ route('cs_etc.show', 'intro') }}" >MORE
                  <span class="more_arrow"></span>
                </a>
            </div>
          </li>
          <li class="ex-roadmap-desc-list">
            <span class="_month">3월</span>
            <h5 class="_tit">소셜 트레이딩 시작</h5>
            <P class="_desc">파트너 따라 하기 자동 매매 시스템으로<br>Master Partner 기능을 사용하여 혁신적인 매매 플랫폼 구축</P>
            <div class="main_more">
                <a href="{{ route('cs_etc.show', 'intro') }}" >MORE
                  <span class="more_arrow"></span>
                </a>
            </div>
          </li>
        </ul>
      </div>
    </div>


    <!-- TEAM MEMBERS -->
    <!--
    <div class="team_wrap">
      <div class="team_inner">
        <h4 class="team_topic wow fadeInDown" data-wow-delay="0.2s">TEAM MEMBERS</h4>
        <div class="slick_team">
            <div class="slide_team_member">
              <img src="/images/team-1.jpg" alt="">
              <h5 class="team_name">Kwang mun Ko</h5>
              <p class="team_position">Chief executive Officer</p>
            </div>
            <div class="slide_team_member">
                <img src="/images/team-2.jpg" alt="">
              <h5 class="team_name">Young soo Shin</h5>
              <p class="team_position">Chief Operating office</p>
            </div>
            <div class="slide_team_member">
              <img src="/images/team-3.jpg" alt="">
              <h5 class="team_name">Min wook Kim</h5>
              <p class="team_position">Chief Financial Officer</p>
            </div>
            <div class="slide_team_member">
              <img src="/images/team-1.jpg" alt="">
              <h5 class="team_name">Kwang mun Ko</h5>
              <p class="team_position">Chief executive Officer</p>
            </div>
            <div class="slide_team_member">
                <img src="/images/team-2.jpg" alt="">
              <h5 class="team_name">Young soo Shin</h5>
              <p class="team_position">Chief Operating office</p>
            </div>
            <div class="slide_team_member">
              <img src="/images/team-2.jpg" alt="">
              <h5 class="team_name">Min wook Kim</h5>
              <p class="team_position">Chief Financial Officer</p>
            </div>
            <div class="slide_team_member">
              <img src="/images/team-1.jpg" alt="">
              <h5 class="team_name">Kwang mun Ko</h5>
              <p class="team_position">Chief executive Officer</p>
            </div>
            <div class="slide_team_member">
                <img src="/images/team-2.jpg" alt="">
              <h5 class="team_name">Young soo Shin</h5>
              <p class="team_position">Chief Operating office</p>
            </div>
            <div class="slide_team_member">
              <img src="/images/team-2.jpg" alt="">
              <h5 class="team_name">Min wook Kim</h5>
              <p class="team_position">Chief Financial Officer</p>
            </div>
            <div class="slide_team_member">
              <img src="/images/team-2.jpg" alt="">
              <h5 class="team_name">Min wook Kim</h5>
              <p class="team_position">Chief Financial Officer</p>
            </div>
            <div class="slide_team_member">
              <img src="/images/team-2.jpg" alt="">
              <h5 class="team_name">Min wook Kim</h5>
              <p class="team_position">Chief Financial Officer</p>
            </div>
        </div>
      </div>
    </div>
    -->
</div>
<script src="{{asset('js/pc/main_slide.js?')}}"></script>

{{-- KRW마켓 코인테이블 --}}
{{--
<table id="coin_table_krw" class="coin_chart_tbl main_chart_4" style="display:none;">
    <thead>
        <tr>
            <th>KRW {{__('main.market')}} {{ __('main.coin') }}</th>
            <th>{{__('main.yesterday')}}</th>
            <th>{{__('main.gg')}}(KRW)</th>
            <th>{{__('main.high')}}(KRW)</th>
            <th>{{__('main.low')}}(KRW)</th>
            <th>{{__('main.day_trade')}}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($coins as $coin)
          @if($coin->api != 'krw')
            @if($coin->percent_change_24h_krw >= 0)
            <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketKRW', $coin->api)}}';">
                <td class="coin_td"><img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image))}}" alt="{{ str_replace('<small>Fanclub Coin</small>','',__('coin_name.'.$coin->api)) }}" class="coin_symbol"/><span class="coin_name">{{ str_replace('<small>Fanclub Coin</small>','',__('coin_name.'.$coin->api)) }}</span><span class="coin_name_eng">{{$coin->symbol}}/KRW</span></td>
                <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_krw == 0 ? '' : 'red'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_krw == NULL)?number_format('0',2):number_format($coin->percent_change_24h_krw,2)}}% {{(float) $coin->percent_change_24h_krw == 0 ? '' : '▲'}}</span></div></td>
                <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_krw == NULL)?number_format('0',$coin->decimal_krw):number_format($coin->last_trade_price_krw,$coin->decimal_krw)}}<span class="currency"></span></span></td>
                <td><span class="table_num_data max_price">{{ ($coin->max_price_krw == NULL)?number_format('0',$coin->decimal_krw):number_format($coin->max_price_krw,$coin->decimal_krw)}}<span class="currency"></span></span></td>
                <td><span class="table_num_data min_price">{{ ($coin->min_price_krw == NULL)?number_format('0',$coin->decimal_krw):number_format($coin->min_price_krw,$coin->decimal_krw)}}<span class="currency"></span></span></td>
                <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_krw'} == NULL)?'0':number_format($coin->{'24h_volume_krw'},8) }}</span></td>
            </tr>
            @else
            <tr id="row_{{$coin->api}}" onclick="window.location.href = '{{route('marketKRW', $coin->api)}}';">
                <td class="coin_td"><img src="{{ asset('/storage/image/homepage/coin_img/'.strtolower($coin->image))}}" alt="{{ str_replace('<small>Fanclub Coin</small>','',__('coin_name.'.$coin->api)) }}" class="coin_symbol"/><span class="coin_name">{{ str_replace('<small>Fanclub Coin</small>','',__('coin_name.'.$coin->api)) }}</span><span class="coin_name_eng">{{$coin->symbol}}/KRW</span></td>
                <td><div class="cell"><span class="{{(float) $coin->percent_change_24h_krw == 0 ? '' : 'blue'}} table_num_data percent_change_24h">{{($coin->percent_change_24h_krw == NULL)?number_format('0',2):number_format($coin->percent_change_24h_krw,2)}}% {{(float) $coin->percent_change_24h_krw == 0 ? '' : '▼'}}</span></div></td>
                <td><span class="table_num_data last_trade_price_usd">{{ ($coin->last_trade_price_krw == NULL)?number_format('0',2):number_format($coin->last_trade_price_krw,$coin->decimal_krw)}}<span class="currency"></span></span></td>
                <td><span class="table_num_data max_price">{{ ($coin->max_price_krw == NULL)?number_format('0',$coin->decimal_krw):number_format($coin->max_price_krw,$coin->decimal_krw)}}<span class="currency"></span></span></td>
                <td><span class="table_num_data min_price">{{ ($coin->min_price_krw == NULL)?number_format('0',$coin->decimal_krw):number_format($coin->min_price_krw,$coin->decimal_krw)}}<span class="currency"></span></span></td>
                <td><span class="table_num_data h24h_volume">{{ ($coin->{'24h_volume_krw'} == NULL)?number_format('0',2):number_format($coin->{'24h_volume_krw'},8) }}</span></td>
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
--}}

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
/* ---------------- 191202 메인리뉴얼 jquery ---------------- */
// 게임 카테고리 탭 누르면 보이는 화면 바뀜
$('#view_game_football').click(function(){
    $('#game-schedule-wrap .game-category-btn').not(this).removeClass('on');
    $(this).addClass('on');

    $('#game_football').addClass('active');
    $('.game-list-group').not('#game_football').removeClass('active');
});
$('#view_game_baseball').click(function(){
    $('#game-schedule-wrap .game-category-btn').not(this).removeClass('on');
    $(this).addClass('on');

    $('#game_baseball').addClass('active');
    $('.game-list-group').not('#game_baseball').removeClass('active');
});
$('#view_game_basketball').click(function(){
    $('#game-schedule-wrap .game-category-btn').not(this).removeClass('on');
    $(this).addClass('on');

    $('#game_basketball').addClass('active');
    $('.game-list-group').not('#game_basketball').removeClass('active');
});
// E
$('.youtube_list_click').click(function(){
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var youtube_id = $(this).data("id");
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

// 게임스케줄 리스트
var swiper = new Swiper('.swiper-container', {
    loop: true,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
});

// E
</script>

<script>
    new WOW().init();

    if (typeof __ === 'undefined') { var __ = {}; }
    __.coin_name = {
      @foreach(__('coin_name') as $key => $value)
        '{{$key}}':'{{$value}}',
      @endforeach
    };
    
</script>

<script src="{{ asset('/js/pc/main_sm_chart.js') }}"></script>
<script src="{{ asset('/js/pc/main_md_chart.js') }}"></script>

@endsection
