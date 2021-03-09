<header class="header {{($pagename[1] == 'home' || $pagename[1] == '')?'main_header':''}}">
    <div class="sub-menu-wrap">
        <div class="sub-menu-inner">
            <ul class="cs_menu_list">
                <li><a href="/cs_etc/guide">{{ __('head.customer_service') }}</a></li>
                <li><a href="{{ route('notice') }}">{{ __('head.user_center') }}</a></li>
            </ul>
        </div>
    </div>

    <div class="top_menu">

            <div class="main_logo">
                <a href="{{ url('/?country='.config('app.country')) }}">
                    <img class="logo_img" src="/images/header_logo(wh)-2.png" alt="">
                </a>
            </div>
            <ul class="main_menu">
                <li><a href="/comunity?board_name=free" class="on">{{ __('head.community') }}</a>
                    <ul class="sub_menu">
                        <li><a href="/comunity?board_name=free">{{ __('head.board') }}</a></li>
                        <!-- FIXME: 추후 코인게시판으로 라우트 바꿔야함 -->
                        <li><a href="/comunity?board_name=mnu">{{ __('head.coin_board') }}</a></li>
                        <li><a href="{{ route('notice') }}">{{ __('head.notice')}}</a></li>
                        <li><a href="{{ route('newsflash') }}">속보</a></li>
                        <li><a href="{{ route('event') }}">{{ __('head.event')}}</a></li>
                    </ul>
                </li>
                <li class="{{request()->is('market*') ? 'active' : ''}}"><a href="{{  route('marketKRW','mnu')  }}" >{{ __('head.trademarket') }}</a></li>
                
                <li class="{{request()->is('trans_wallet*') ? 'active' : ''}}"><a href="{{ route('trans_wallet') }}">{{ __('head.inout') }}</a></li>
                
                <li class="{{request()->is('my_asset*') ? 'active' : ''}}"><a href="{{ route('my_asset.index') }}">{{ __('head.my_asset') }}</a></li>
                <li>
                    <a href="{{ route('ico_list','all') }}">IEO</a>
                    <ul class="sub_menu sub_location">
                        <li><a href="{{ route('ico_list','all') }}">IEO</a></li>
                        <li><a href="{{ route('my_ico','all') }}">{{ __('head.my_ieo') }}</a></li>
                        <li><a href="{{ route('ico_history') }}">{{ __('head.participation_ieo') }}</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('home') }}">{{ __('head.out_trade') }}</a>
                    <ul class="sub_menu">
                        <li><a href="{{ route('home') }}">{{ __('head.out_trade') }}</a></li>
                        <li><a href="{{ route('home') }}">{{ __('head.progress_info') }}</a></li>
                        <li><a href="{{ route('home') }}">{{ __('head.tr_complete') }}</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('social_trade.index') }}">{{ __('head.social_trading') }}</a></li>
                <!-- <li><a href="{{ route('store.index') }}">{{ __('head.store') }}</a> -->
                    <!-- <ul class="sub_menu">
                        <li><a href="#">{{ __('head.ticket_official_store') }}</a></li>
                        <li><a href="#">{{ __('head.buy_ticket') }}</a></li>
                        <li><a href="#">{{ __('head.official_store') }}</a></li>
                    </ul> -->
                </li>
                <!-- <li>
                    <a href="{{ route('notice') }}">{{ __('head.user_center') }}</a>
                    <ul class="sub_menu">
                        <li><a href="{{ route('notice') }}">{{ __('head.notice') }}</a></li>
                        <li><a href="{{ route('faq') }}">자주묻는 질문</a></li>
                        <li><a href="{{ route('qna_list') }}">{{ __('head.contact') }}</a></li>
                        <li><a href="{{ route('event') }}">{{ __('head.event') }}</a></li>
                        <li><a href="#">보도자료</a></li>
                        <li><a href="#">입출금 한도 안내</a></li>
                        <li><a href="#">개인정보 취급방침</a></li>
                        <li><a href="#">이용약관</a></li>
                    </ul>
                </li> -->
            </ul>
            <button type="button" name="button"  onclick="window.open('http://fanclubcoin.com')">{{ __('head.download') }}</button>
            @guest
                <ul class="join_nav">
                    <li><a href="{{ route('register') }}">{{ __('head.register') }}</a></li>
                    <li><a href="{{ route('login') }}">{{ __('head.login') }}</a></li>
                </ul>
            @else
                <div class="after_login">
                    <ul class="login_menu">
                        <li class="after_login_info"><a href="#"><b>{{Auth::user()->nickname}} 님</b></a>
                        <ul class="sub_menu login_sub_location">
                            <li><a href="{{ route('mypage.alarm_setting') }}">{{ __('head.mypage') }}</a></li>
                            <li><a href="{{ route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('head.logout') }}</a></li>
                        </ul>
                        </li>
                    </ul>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endguest



    </div>
</header>