<div id="floating_nav" class="left_con">

    <h1 class="left_tit">ICO/IEO</h1>

    <ul class="list_tab_ul">
        <li class="{{ ($pagename == 'ico')?'active':'' }}">
            <a href="{{ route('ico_list','all') }}">
            ICO/IEO
            <i class="fal fa-angle-right"></i>
        </a>
        </li>
        <li class="{{ ($pagename == 'my_ico')?'active':'' }}">
            <a href="{{ route('my_ico','all') }}">
            {{ __('icoo.my_submit_ico')}}
            <i class="fal fa-angle-right"></i>
        </a>
        </li>
        <li class="{{ ($pagename == 'ico_history')?'active':'' }}">
            <a href="{{ route('ico_history') }}">
            {{ __('icoo.ico_participation_history')}}
            <i class="fal fa-angle-right"></i>
        </a>
        </li>
    </ul>
    
</div>