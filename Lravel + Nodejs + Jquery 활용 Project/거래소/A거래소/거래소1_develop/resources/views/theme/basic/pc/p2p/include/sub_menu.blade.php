<div id="floating_nav" class="left_con">

    <h1 class="left_tit">{{ __('ptop.out_trade')}}</h1>

    <ul class="list_tab_ul">
        <li class="{{request()->is('p2p/*')? 'active' : '' }}">
            <a href="{{ route('p2p_list','all') }}">
            {{ __('ptop.out_trade')}}
            <i class="fal fa-angle-right"></i>
        </a>
        </li>
        <li class="{{request()->is('p2p_onprogress/*')? 'active' : '' }}">
            <a href="{{ route('p2p_onprogress','all') }}">
            {{ __('ptop.trading_info')}}
            <i class="fal fa-angle-right"></i>
        </a>
        </li>
        <li class="{{request()->is('p2p_history*')? 'active' : '' }}">
            <a href="{{ route('p2p_history') }}">
            {{ __('ptop.transaction_completion_history')}}
            <i class="fal fa-angle-right"></i>
        </a>
        </li>
    </ul>

</div>