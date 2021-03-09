<div class="left_con">

    <h1 class="left_tit mb-2">{{ __('support.user_center') }}</h1>

    <ul class="list_tab_ul">
        <li class="{{request()->is('notice*')? 'active' : '' }}">
            <a href="{{ route('notice') }}">
            {{ __('support.notice') }}
                <i class="fal fa-angle-right"></i>
            </a>
        </li>
        <li class="{{request()->is('event*')? 'active' : '' }}">
            <a href="{{ route('event') }}">
            {{ __('support.event') }}
                <i class="fal fa-angle-right"></i>
            </a>
        </li>
        <li class="{{request()->is('faq*') ? 'active' : '' }}">
            <a href="{{ route('faq') }}">
            {{ __('support.faq') }}
                <i class="fal fa-angle-right"></i>
            </a>
        </li>
        <li class="{{request()->is('qna*') ? 'active' : '' }}">
            <a href="{{ route('qna_list') }}">
            {{ __('support.one_to_one_ask') }}
                <i class="fal fa-angle-right"></i>
            </a>
        </li>
    </ul>

</div>