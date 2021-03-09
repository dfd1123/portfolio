<!-- 상단 (탭메뉴) -->
<div class="m_hd_title">
    <div class="inner">
        ICO/IEO
    </div>
</div>
    
<div class="m_tab_list">
    <ul>
        <li class="{{ ($pagename == 'ico')?'active':'' }}">
            <a href="{{ route('ico_list','all') }}">
                ICO/IEO
			</a>
        </li>
        <li class="{{ ($pagename == 'my_ico')?'active':'' }}">
            <a href="{{ route('my_ico','all') }}">
            {{ __('icoo.my_ice_list')}}
            </a>
        </li>
        <li class="{{ ($pagename == 'ico_history')?'active':'' }}">
            <a href="{{ route('ico_history') }}">
                {{ __('icoo.my_ico_list')}}
            </a>
        </li>
    </ul>
</div>
<!-- //상단 (탭메뉴) -->