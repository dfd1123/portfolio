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
        <li class="{{request()->is('newsflash*')? 'active' : '' }}">
            <a href="{{ route('newsflash') }}">
                속보
			</a>
        </li>
        <li class="{{request()->is('faq*') ? 'active' : '' }}">
            <a href="{{ route('faq') }}">
            {{ __('support.common_faq') }}
            </a>
        </li>
        <li class="{{request()->is('qna*') ? 'active' : '' }}">
            <a href="{{ route('qna_list') }}">
                {{ __('support.one_to_one_ask') }}
			</a>
        </li>
        <!--
        <li class="{{request()->is('press*') ? 'active' : '' }}">
            <a href="{{ route('press.index') }}">
                {{ __('support.press') }}
			</a>
        </li>
        -->
        <li class="{{request()->is('cs_etc/limit_guide*') ? 'active' : '' }}">
            <a href="{{ route('cs_etc.show', 'limit_guide') }}">
                {{ __('support.short_limited') }}
			</a>
        </li>
        <li class="{{request()->is('cs_etc/privacy_guide*') ? 'active' : '' }}">
            <a href="{{ route('cs_etc.show', 'privacy_guide') }}">
                {{ __('support.short_Handling_policy') }}
			</a>
        </li>
        <li class="{{request()->is('cs_etc/service_guide*') ? 'active' : '' }}">
            <a href="{{ route('cs_etc.show', 'service_guide') }}">
                {{ __('support.terms_of_service') }}
            </a>
        </li>
    </ul>
</div>
<!-- //상단 (탭메뉴) -->