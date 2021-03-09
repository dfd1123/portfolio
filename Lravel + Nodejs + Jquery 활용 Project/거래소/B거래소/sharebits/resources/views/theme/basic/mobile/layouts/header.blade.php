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
        <!-- 회원일 경우-->
        @auth
        <div class="after">
            <label class="label" for="lang_choice_check">
                <img src="{{asset('/storage/image/homepage/mobile_icon/flags_'.config('app.country').'.svg')}}" alt="flag_country" class="label_flag">
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
            <span>
                <a href="{{ route('mypage.alarm_setting') }}">
                    @csrf
                    <b>{{Auth::user()->fullname}}</b> {!! __('head.mypage_01')!!} <i class="fal fa-angle-right point_clr_2 ml-1"></i>
                </a>
            </span>
        </div>
        
        @else
        <div class="before">
            <label class="label" for="lang_choice_check">
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

        
        @endauth
        
        <!-- //회원일 경우-->
        <a href="{{ url('/?country='.config('app.country')) }}" class="nav_home_btn"></a>
        <label for="m_nav_close_box" class="nav_x_btn">
            <span></span>
        </label>
    </div>

    <div class="scrl_wrap">
        
            @auth
            
            @else
            <div class="login_status dp_table">
                    <!-- 비회원일 경우 -->
                    <div class="before dp_table_cell">
                        <span>{{ __('head.need_login')}}</span>
                        <a href="{{ route('login') }}" class="border_btn">{{ __('head.login')}}</a>
                    </div>
                    <!-- //비회원일 경우 -->
            </div>
            @endauth
        
            <!-- 회원일 경우 -->
            @auth 
            <div class="my_total_asset dp_table">
                <div class="dp_table_cell">
                    <span class="label">{{ __('head.allmyasset')}}</span>
                    <!-- 회원일 경우 -->
                    <p class="ast_num point_clr_1">{{number_format($total_holding,8)}}<span class="ml-2 currency">UCSS</span></p>
                    <!-- //회원일 경우 -->
                </div>
            </div>
            @else
                <!-- 비회원일 경우 -->
                {{-- <p class="ast_num">0.00<span class="ml-2 currency">UCSS</span></p> --}}
                <!-- //비회원일 경우 -->
            @endauth
        
            <nav class="nav_group">
                <ul class="nav_ul">
                    <li class="main_menu">
                        <a href="{{ route('marketUCSS','btc') }}">
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
                            <i class="nav-menu-icon"></i>{{ __('head.my_asset')}}
                        </a>
                    </li>
                    <li class="main_menu drop_menu">
                        <a href="#">
                            <label for="sub_menu4">
                                <i class="nav-menu-icon"></i>ICO
                                <i class="fal fa-angle-down point_clr_2"></i>
                            </label>
                        </a>
                        <input name="sub_menus" class="hide" type="checkbox" id="sub_menu4">
                        <ul class="drop_down">
                            <li>
                                <a href="{{ route('ico_list','all') }}">
                                    ICO
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
                                <a href="{{ route('p2p_list','all') }}">
                                {{ __('head.out_trade')}}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('p2p_onprogress','all') }}">
                                {{ __('head.progress_info')}}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('p2p_history') }}">
                                {{ __('head.tr_complete')}}
                                </a>
                            </li>
                        </ul>
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
                                <a href="{{ route('event') }}">
                                {{ __('head.event')}}
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
                        </ul>
                    </li>
                    
                    
                    @if( config('app.country') == 'ch' )
                    <li class="main_menu">
                        <a href="{{ route('game_ch') }}">游戏</a>
                    </li>
                    @else
                    
                    @endif
                    
                    <li class="main_menu drop_menu">
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
                    </li>
                </ul>
            </nav>
    </div>

    <div class="bt_fix_btn">
        @auth
            <a href="{{ route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="nav-menu-icon"></i> {{ __('head.logout')}}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

        @else

            <!-- 비회원일 때 -->
            <a href="{{ route('login') }}"><i class="nav-menu-icon"></i> {{ __('head.login')}}</a>
            <!-- 비회원일 때 -->
		@endauth
    </div>

</div>