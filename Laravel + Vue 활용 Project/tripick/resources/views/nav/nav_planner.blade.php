<nav class="bt-fixed-nav">
    <ul class="bt-fixed-nav__group bt-fixed-nav__group--plnr">
        <li class="bt-fixed-nav__list {{ $page_css == 'plnr-ver' ? 'is-active' : '' }}" onclick="location.href='/pln_ver/touristready';">
            <img src="/img/btn/btn_menu_01consumer_now_off.svg" alt="icon off" class="bt-fixed-nav__icon--off">
            <img src="/img/btn/btn_menu_01consumer_now_on.svg" alt="icon on" class="bt-fixed-nav__icon--on">
            <span class="bt-fixed-nav__span">여행객 현황</span>
        </li>
        <li class="bt-fixed-nav__list" onclick="location.href='/af_home';" id="change_tripper">
            <img src="/img/btn/btn_menu_02change_trip_off.svg" alt="icon off" class="bt-fixed-nav__icon--off">
            <img src="/img/btn/btn_menu_02change_trip_on.svg" alt="icon on" class="bt-fixed-nav__icon--on">
            <span class="bt-fixed-nav__span">여행객 전환</span>
        </li>
        <li class="bt-fixed-nav__list {{ $page_css == 'message' ? 'is-active' : '' }}" onClick="location.href='/pln_ver/msg/list'">
            <img src="/img/btn/btn_menu_04message_off.svg" alt="icon off" class="bt-fixed-nav__icon--off">
            <img src="/img/btn/btn_menu_04message_on.svg" alt="icon on" class="bt-fixed-nav__icon--on">
            <span class="bt-fixed-nav__span">메세지</span>
        </li>
        <li class="bt-fixed-nav__list {{ $page_css == 'planner' ? 'is-active' : '' }}" onclick="location.href='/pln_ver/profile';">
            <img src="/img/btn/btn_menu_03planner_off.svg" alt="icon off" class="bt-fixed-nav__icon--off">
            <img src="/img/btn/btn_menu_03planner_on.svg" alt="icon on" class="bt-fixed-nav__icon--on">
            <span class="bt-fixed-nav__span">플래너 정보</span>
        </li>
        <li class="bt-fixed-nav__list {{ $page_css == 'calculate' ? 'is-active' : '' }}" onclick="location.href='/calcul';">
            <img src="/img/btn/btn_menu_00calcul_off.svg" alt="icon off" class="bt-fixed-nav__icon--off calcul">
            <img src="/img/btn/btn_menu_00calcul_on.svg" alt="icon on" class="bt-fixed-nav__icon--on calcul">
            <span class="bt-fixed-nav__span">정산</span>
        </li>
    </ul>
</nav>