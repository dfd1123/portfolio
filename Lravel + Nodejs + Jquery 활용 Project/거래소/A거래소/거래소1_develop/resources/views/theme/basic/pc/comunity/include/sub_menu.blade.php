<div class="left_con">

    <h1 class="left_tit">{{ __('head.community') }}</h1>

    <ul class="list_tab_ul">
        <li class="{{($board_name == 'free')? 'active' : '' }}">
            <a href="{{ route('comunity.index') }}?board_name=free">
                {{ __('head.board') }}
                <i class="fal fa-angle-right"></i>
            </a>
        </li>
        <li class="{{($board_name != 'free')? 'active' : '' }}">
            <!-- FIXME: 추후 코인게시판으로 라우트 바꿔야함 -->
            <a href="{{ route('comunity.index') }}?board_name=mnu">
                {{ __('head.coin_board') }}
                <i class="fal fa-angle-right"></i>
            </a>
        </li>
        <li class="">
            <!-- FIXME: 추후 코인게시판으로 라우트 바꿔야함 -->
            <a href="{{ route('notice') }}">
                {{ __('support.notice') }}
                <i class="fal fa-angle-right"></i>
            </a>
        </li>
    </ul>

</div>