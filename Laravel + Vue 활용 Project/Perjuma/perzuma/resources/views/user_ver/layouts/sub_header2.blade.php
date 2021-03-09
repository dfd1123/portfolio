<header id="sub_hd2">
    <div class="left_hd go_home">
        <img src="{{asset('/images/btn_home.svg')}}" alt="menu">
    </div>
    <div class="center_hd">
        <h1>{{$title}}</h1>
        <h1 class="menu_tit" style="display:none;">메뉴</h1>
    </div>
    <div class="right_hd">
        <a class="menu-trigger" href="#">
            <span></span>
            <span></span>
            <span></span>
        </a>
    </div>
</header>

<style>
    #content {
        padding: 3.3em 0;
        padding-top: 9.8em;
        min-height: 100%;
        background: url(/images/bg_main_tile.png);
        background-size: 100% auto;
        background-repeat-x: repeat;
        background-repeat-y: repeat;
    }
</style>