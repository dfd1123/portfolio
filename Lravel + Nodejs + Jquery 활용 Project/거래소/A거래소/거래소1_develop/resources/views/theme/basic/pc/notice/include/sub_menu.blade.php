<div class="left_con">

    <h1 class="left_tit">{{ __('support.user_center') }}</h1>

    <ul class="list_tab_ul">
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
        <!-- <li class="{{request()->is('event*')? 'active' : '' }}">
            <a href="{{ route('event') }}">
            {{ __('support.event') }}
            </a>
        </li> -->
        <li class="{{request()->is('faq*') ? 'active' : '' }}">
            <a href="{{ route('faq') }}">
            {{ __('support.faq') }}
            </a>
        </li>
        <li class="{{request()->is('qna*') ? 'active' : '' }}">
            <a href="{{ route('qna_list') }}">
            {{ __('support.one_to_one_ask') }}
            </a>
        </li>
        <li class="{{request()->is('news*') & !request()->is('newsflash*') ? 'active' : '' }}">
            <a href="{{ route('news') }}">
            {{ __('support.press') }}
            </a>
        </li>
        <li class="{{request()->is('cs_etc/limit_guide*') ? 'active' : '' }}">
            <a href="{{ route('cs_etc.show', 'limit_guide') }}">
            {{ __('support.limited') }}
            </a>
        </li>
        <li class="{{request()->is('cs_etc/privacy_guide*') ? 'active' : '' }}">
            <a href="{{ route('cs_etc.show', 'privacy_guide') }}">
            {{ __('support.Handling_policy') }}
            </a>
        </li>
        <li class="{{request()->is('cs_etc/service_guide*') ? 'active' : '' }}">
            <a href="{{ route('cs_etc.show', 'service_guide') }}">
            {{ __('support.terms_of_service') }}
            </a>
        </li>
    </ul>

</div>