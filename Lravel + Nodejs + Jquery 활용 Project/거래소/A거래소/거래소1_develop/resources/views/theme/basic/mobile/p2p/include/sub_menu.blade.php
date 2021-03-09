<!-- 상단 (탭메뉴) -->
<div class="m_hd_title">
        <div class="inner">
            {{ __('ptop.out_trade')}}
            @if(request()->is('p2p/*'))
                @auth
					@if(Auth::user()->status != 2)
                    {{--
                    <span id="p2pWrite" class="write_btn_st write_btn">
                        <img src="{{ asset('/storage/image/homepage/mobile_icon/m_btn_write.svg')}}" alt="tab">
                    </span> 
                    --}}
                    @endif
                @endauth 
            @endif
        </div>
    </div>
        
    <div class="m_tab_list out_trade_line">
        <ul>
            <li class="{{request()->is('p2p/*')? 'active' : '' }}">
                <a href="{{ route('p2p_list','all') }}">
                    {{ __('ptop.out_trade')}}
                </a>
            </li>
            <li class="{{request()->is('p2p_onprogress/*')? 'active' : '' }}">
                <a href="{{ route('p2p_onprogress','all') }}">
                    {{ __('ptop.trading_info')}}
                </a>
            </li>
            <li class="{{request()->is('p2p_history*')? 'active' : '' }}">
                <a href="{{ route('p2p_history') }}">
                    {{ __('ptop.transaction_completion_history')}}
                </a>
            </li>
        </ul>
    </div>
    <!-- //상단 (탭메뉴) -->