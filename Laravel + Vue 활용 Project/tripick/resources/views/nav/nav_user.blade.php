<nav class="bt-fixed-nav">
    <ul class="bt-fixed-nav__group user">
        <li class="bt-fixed-nav__list {{ $page_css == 'home' ? 'is-active' : '' }}" onClick="location.href='/af_home'">
            <img src="/img/btn/btn_menu_01home_off.svg" alt="icon off" class="bt-fixed-nav__icon--off">
            <img src="/img/btn/btn_menu_01home_on.svg" alt="icon on" class="bt-fixed-nav__icon--on">
            <span class="bt-fixed-nav__span">홈</span>
        </li>
        <li class="bt-fixed-nav__list {{ $page_css == 'like' ? 'is-active' : '' }}" onClick="location.href='/favorite'">
            <img src="/img/btn/btn_menu_02heart_off.svg" alt="icon off" class="bt-fixed-nav__icon--off">
            <img src="/img/btn/btn_menu_02heart_on.svg" alt="icon on" class="bt-fixed-nav__icon--on">
            <span class="bt-fixed-nav__span">찜 목록</span>
        </li>
        <li class="bt-fixed-nav__list {{ $page_css == 'planner' ? 'is-active' : '' }}" onClick="location.href='/planner/intro'">
            <img src="/img/btn/btn_menu_03planner_off.svg" alt="icon off" class="bt-fixed-nav__icon--off">
            <img src="/img/btn/btn_menu_03planner_on.svg" alt="icon on" class="bt-fixed-nav__icon--on">
            <span class="bt-fixed-nav__span">플래너</span>
        </li>
        <li class="bt-fixed-nav__list {{ $page_css == 'message' ? 'is-active' : '' }}" onClick="location.href='/msg/list'">
            <img src="/img/btn/btn_menu_04message_off.svg" alt="icon off" class="bt-fixed-nav__icon--off">
            <img src="/img/btn/btn_menu_04message_on.svg" alt="icon on" class="bt-fixed-nav__icon--on">
            <span class="bt-fixed-nav__span">메세지</span>
        </li>
        <li class="bt-fixed-nav__list {{ $page_css == 'mypage' ? 'is-active' : '' }}" onClick="location.href='/mypage/mypage'">
            <img src="/img/btn/btn_menu_05mypage_off.svg" alt="icon off" class="bt-fixed-nav__icon--off">
            <img src="/img/btn/btn_menu_05mypage_on.svg" alt="icon on" class="bt-fixed-nav__icon--on">
            <span class="bt-fixed-nav__span">마이페이지</span>
        </li>
    </ul>
</nav>