<div class="m_toggle_btn">
    <label for="m_nav_check_box">
        <span>
        </span>
    </label>
</div>

<input type="radio" name="nav_radio" id="m_nav_check_box" class="hide">

<input type="radio" name="nav_radio" id="m_nav_close_box" class="hide">

<div id="m_nav_page">

    <div class="nav_sub_btns text-right">
        <a href="{{ url('/?country='.config('app.country')) }}" class="nav_home_btn"></a>
        <label for="m_nav_close_box" class="nav_x_btn">
            <span></span>
        </label>
    </div>
        
        @auth
        
    <!--//nav_sub_btns (회원일 때)-->
    <div class="nav_sub_btns login_status_after">

        <div class="after">
            <label class="label hide" for="lang_choice_check">
                <img src="{{asset('/storage/image/homepage/mobile_icon/flags_'.config('app.country').'.svg')}}" alt="flag_country" class="label_flag">
            </label>
            <input type="checkbox" id="lang_choice_check" class="hide">
            <ul class="lang_choice_list hide">
                <li>
                    <a href="/?country=en">
                        <img src="{{asset('/storage/image/homepage/mobile_icon/flags_en.svg')}}" alt="flag_country" class="flag_img">
                        English
                    </a>
                </li>
                <li>
                    <a href="/?country=kr">
                        <img src="{{asset('/storage/image/homepage/mobile_icon/flags_kr.svg')}}" alt="flag_country" class="flag_img">
                        한국어
                    </a>
                </li>
                <li>
                    <a href="/?country=jp">
                        <img src="{{asset('/storage/image/homepage/mobile_icon/flags_jp.svg')}}" alt="flag_country" class="flag_img">
                        日本語
                    </a>
                </li>
                <li>
                    <a href="/?country=ch">
                        <img src="{{asset('/storage/image/homepage/mobile_icon/flags_ch.svg')}}" alt="flag_country" class="flag_img">
                        中国語
                    </a>
                </li>
            </ul>
            <span class="after_name_group">
                <a href="{{ route('mypage.alarm_setting') }}">
                    @csrf
                    <b>{{Auth::user()->nickname}}</b> {!! __('head.mypage_01')!!} <img src="{{asset('/storage/image/homepage/mobile_icon/btn_mypage.svg')}}" alt="nickname_arrow" class="nickname_right_arrow">
                </a>
            </span>
        </div>

        <div class="my_total_asset dp_table">
            <div class="dp_table_cell">
                <span class="label">{{ __('head.allmyasset')}}</span>
                {<span class="label" onclick="location.href='{{ route('mypage.event_coupon') }}'" style="float:right; border-style: solid; border-width: 1px; padding: 5px; font-size: 0.75rem;">이벤트 당첨코인 확인하기!!</span>
                <!-- 회원일 경우 -->
                <p class="ast_num">{{ number_format($total_holding,0) }}<span class="ml-2 currency">원</span></p>
                <!-- //회원일 경우 -->
            </div>
        </div>

        @else

    <!--//nav_sub_btns (비회원일 때)-->
    <div class="nav_sub_btns">

        <div class="before">
            <label class="label hide" for="lang_choice_check">
                <img src="{{asset('/storage/image/homepage/mobile_icon/flags_'.config('app.country').'.svg')}}" alt="flag_country" class="label_flag">
                <i class="fal fa-angle-down point_clr_2 ml-1"></i>
            </label>
            <input type="checkbox" id="lang_choice_check" class="hide">
            <ul class="lang_choice_list">
                <li>
                    <a href="/?country=en">
                        <img src="{{asset('/storage/image/homepage/mobile_icon/flags_en.svg')}}" alt="flag_country" class="flag_img">
                        English
                    </a>
                </li>
                <li>
                    <a href="/?country=kr">
                        <img src="{{asset('/storage/image/homepage/mobile_icon/flags_kr.svg')}}" alt="flag_country" class="flag_img">
                        한국어
                    </a>
                </li>
                <li>
                    <a href="/?country=jp">
                        <img src="{{asset('/storage/image/homepage/mobile_icon/flags_jp.svg')}}" alt="flag_country" class="flag_img">
                        日本語
                    </a>
                </li>
                <li>
                    <a href="/?country=ch">
                        <img src="{{asset('/storage/image/homepage/mobile_icon/flags_ch.svg')}}" alt="flag_country" class="flag_img">
                        中国語
                    </a>
                </li>
            </ul>
        </div>

        <div class="login_status dp_table login_status_before">
            <div class="login_status_before_group">
                <span>{{ __('head.need_login')}}</span>
                <div class="login_register_btns">
                    <span><a href="{{ route('login') }}">{{ __('head.login')}}/</a></span>
                    <span><a href="{{ route('register') }}">{{ __('head.register')}}</a></span>
                </div>
            </div>
        </div>

        @endauth

    </div>
    <!--//nav_sub_btns (회원일때 or 비회원일 때) 닫음-->
    
    <div class="scrl_wrap">

        <nav class="nav_group">

            <ul class="nav_ul">
                <li class="main_menu drop_menu">
                    <a href="#">
                        <label for="sub_menu1">
                            <i class="nav-menu-icon"></i> {{ __('head.community')}}

                            <i class="fal fa-angle-down point_clr_2"></i>
                        </label>
                    </a>
                    <input name="sub_menus" class="hide" type="checkbox" id="sub_menu1">
                    <ul class="drop_down">
                        <li>
                            <a href="/comunity?board_name=free">
                                {{ __('head.board')}}
                            </a>
                        </li>
                        <li>
                        <!-- FIXME: 추후 코인게시판으로 라우트 바꿔야함 -->
                            <a href="/comunity?board_name=mnu">
                                {{ __('head.coin_board')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('notice') }}">
                            {{ __('head.notice')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('newsflash') }}">
                            속보
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('event') }}">
                            {{ __('head.event')}}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="main_menu drop_menu">
                    <a href="#">
                        <label for="sub_menu2">
                            <i class="nav-menu-icon"></i> 이용 가이드

                            <i class="fal fa-angle-down point_clr_2"></i>
                        </label>
                    </a>
                    <input name="sub_menus" class="hide" type="checkbox" id="sub_menu2">
                    <ul class="drop_down">
                        <li>
                            <a href="/guide/guide_cash">
                                원화 입출금
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="alert('준비 중입니다.')">
                            <!-- <a href="/guide/guide_coin"> -->
                                코인 입출금
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="alert('준비 중입니다.')">
                            <!-- <a href="/guide/guide_trade"> -->
                                매수&매도
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="main_menu">
                    <a href="{{ route('marketKRW','mnu') }}">
                        <i class="nav-menu-icon"></i>
                        {{ __('head.trademarket')}}
                    </a>
                </li>

                <li class="main_menu">
                    <a href="{{ route('trans_wallet') }}">
                        <i class="nav-menu-icon"></i>{{ __('head.inout')}}
                    </a>
                </li>

                <li class="main_menu">
                    <a href="{{ route('my_asset.index') }}">
                        <i class="nav-menu-icon"></i> {{ __('head.my_asset')}}
                    </a>
                </li>

                <li class="main_menu drop_menu">
                    <a href="#">
                        <label for="sub_menu4">
                            <i class="nav-menu-icon"></i>IEO
                            <i class="fal fa-angle-down point_clr_2"></i>
                        </label>
                    </a>
                    <input name="sub_menus" class="hide" type="checkbox" id="sub_menu4">
                    <ul class="drop_down">
                        <li>
                            <a href="{{ route('ico_list','all') }}">
                                IEO
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('my_ico','all') }}">
                                {{ __('head.submit')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('ico_history') }}">
                                {{ __('head.m_with')}}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="main_menu drop_menu">
                    <a href="#">
                        <label for="sub_menu5">
                            <i class="nav-menu-icon"></i> {{ __('head.out_trade')}}
                            <i class="fal fa-angle-down point_clr_2"></i>
                        </label>
                    </a>
                    <input name="sub_menus" class="hide" type="checkbox" id="sub_menu5">
                    <ul class="drop_down">
                        <li>
                            <a href="{{ route('home') }}">
                            {{ __('head.out_trade')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('home') }}">
                            {{ __('head.progress_info')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('home') }}">
                            {{ __('head.tr_complete')}}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="main_menu">
                    <a href="{{ route('social_trade.index') }}">
                        <i class="nav-menu-icon"></i>
                        {{ __('head.social_trading')}}
                    </a>
                </li>
                {{--
                <li class="main_menu">
                    <a href="{{ route('store.index') }}">
                        <i class="nav-menu-icon"></i>
                        {{ __('head.store')}}
                    </a>
                </li>
                --}}
                <li class="main_menu">
                    <a href="/cs_etc/guide">
                        <i class="nav-menu-icon"></i>
                        {{ __('head.customer_service')}}
                    </a>
                </li>
                <li class="main_menu drop_menu">
                    <a href="#">
                        <label for="sub_menu6">
                            <i class="nav-menu-icon"></i> {{ __('head.user_center')}}
                            <i class="fal fa-angle-down point_clr_2"></i>
                        </label>
                    </a>
                    <input name="sub_menus" class="hide" type="checkbox" id="sub_menu6">
                    <ul class="drop_down">
                        <li>
                            <a href="{{ route('notice') }}">
                            {{ __('head.notice')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('faq') }}">
                            {{ __('head.faq')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('qna_list') }}">
                            {{ __('head.contact')}}
                            </a>
                        </li>
                    {{--<li>
                            <a href="{{ route('press.index') }}">
                            {{ __('support.press') }}
                            </a>
                        </li>--}}
                        <li>
                            <a href="{{ route('cs_etc.show', 'limit_guide') }}">
                            {{ __('support.short_limited') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('cs_etc.show', 'privacy_guide') }}">
                            {{ __('support.short_Handling_policy') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('cs_etc.show', 'service_guide') }}">
                            {{ __('support.terms_of_service') }}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="main_menu">
                    <a href="{{ route('cs_etc.show', 'et_cetera') }}">
                        <i class="nav-menu-icon"></i>
                        기타정보
                    </a>
                </li>
                
                @if( config('app.country') == 'ch' )
                <li class="main_menu">
                    <a href="{{ route('game_ch') }}">游戏</a>
                </li>
                @else
                
                @endif
                
                <!-- <li class="main_menu drop_menu">
                    <a href="#">
                        <label for="sub_menu7">
                            <img src="{{asset('/storage/image/homepage/mobile_icon/flags_'.config('app.country').'.svg')}}" alt="flag_country" class="flag_country">
                            Language
                            <i class="fal fa-angle-down point_clr_2"></i>
                        </label>
                    </a>
                    <input name="sub_menus" class="hide" type="checkbox" id="sub_menu7">
                    <ul class="drop_down">
                        <li>
                            <a href="/?country=en">
                                <img src="{{asset('/storage/image/homepage/mobile_icon/flags_en.svg')}}" alt="flag_country" class="flag_country">
                                English
                            </a>
                        </li>
                        <li>
                            <a href="/?country=kr">
                                <img src="{{asset('/storage/image/homepage/mobile_icon/flags_kr.svg')}}" alt="flag_country" class="flag_country">
                                한국어
                            </a>
                        </li>
                        <li>
                            <a href="/?country=jp">
                                <img src="{{asset('/storage/image/homepage/mobile_icon/flags_jp.svg')}}" alt="flag_country" class="flag_country">
                                日本語
                            </a>
                        </li>
                        <li>
                            <a href="/?country=ch">
                                <img src="{{asset('/storage/image/homepage/mobile_icon/flags_ch.svg')}}" alt="flag_country" class="flag_country">
                                中国語
                            </a>
                        </li>
                    </ul>
                </li> -->
            </ul>

            <div class="nav_sub_btn_group">

                @auth

                    <a href="http://fanclubcoin.com" target="_blank" class="download_btn">{{ __('head.download')}}</a>

                    <!-- 회원일 때 -->
                    <ul class="sub_etc_btns">
                        <li><a href="{{ route('cs_etc.show', 'intro') }}">{{ __('head.corp_info')}}</a></li>
                        <li><a href="{{ route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('head.logout')}}</a></li>
                        <li><a href="{{ route('mypage.member_out') }}" style="margin-left:0.3rem;padding-left: 0.3rem;border-left: 1px solid #969696;">회원탈퇴</a></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                        </form>
                    </ul>
                    <!-- 회원일 때 -->

                @else

                    <a href="http://fanclubcoin.com" target="_blank" class="download_btn">{{ __('head.download')}}</a>

                    <!-- 비회원일 때 -->
                    <ul class="sub_etc_btns">
                        <li><a href="{{ route('cs_etc.show', 'intro') }}">{{ __('head.corp_info')}}</a></li>
                    </ul>
                    <!-- 비회원일 때 -->
                @endauth
            </div>

        </nav>

    </div>

</div>