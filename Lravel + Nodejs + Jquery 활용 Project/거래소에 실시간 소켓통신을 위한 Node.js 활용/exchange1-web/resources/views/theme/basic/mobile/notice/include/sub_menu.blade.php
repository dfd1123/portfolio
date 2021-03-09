<!-- 상단 (탭메뉴) -->
<div class="m_hd_title">
    <div class="inner">
        {{ __('support.user_center') }}
    </div>
</div>
    
<div class="m_tab_list ntc_tab_list">
    <ul class="w-120">
        <li class="{{request()->is('notice*')? 'active' : '' }}">
            <a href="{{ route('notice') }}">
                {{ __('support.notice') }}
			</a>
        </li>
        <li class="{{request()->is('event*')? 'active' : '' }}">
            <a href="{{ route('event') }}">
            {{ __('support.event') }}
            </a>
        </li>
        <li class="{{request()->is('faq*') ? 'active' : '' }}">
            <a href="{{ route('faq') }}">
                FAQ
			</a>
        </li>
        <li class="{{request()->is('qna*') ? 'active' : '' }}">
            <a href="{{ route('qna_list') }}">
                {{ __('support.one_to_one_ask') }}
			</a>
        </li>
    </ul>
</div>
<!-- //상단 (탭메뉴) -->