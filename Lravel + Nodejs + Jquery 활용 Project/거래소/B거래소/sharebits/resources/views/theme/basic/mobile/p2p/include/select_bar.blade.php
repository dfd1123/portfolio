<div class="select_wrap">
        <div class="select_type">
            <label @if(request()->is('p2p/*')) for="p2p_type_check1" @else for="p2p_type_check2" @endif>
                <span class="type_tit">
                    @if($type=='all') {{ __('ptop.all') }}
                    @elseif($type=='buy') {{ __('ptop.buy') }}
                    @elseif($type=='sell') {{ __('ptop.sell') }}
                    @elseif($type=='my') {{ __('ptop.my_posts') }}
                    @endif
                </span>
                <i class="fal fa-angle-down point_clr_2"></i>
            </label>
        </div>
        
        <input @if(request()->is('p2p/*')) id="p2p_type_check1" @else id="p2p_type_check2" @endif type="checkbox" class="hide">
        
        <ul class="type_list">
            <li>
                @if(request()->is('p2p/*'))
                    <a href="{{ route('p2p_list','all') }}">
                @else
                    <a href="{{ route('p2p_onprogress','all') }}">
                @endif
                
                    {{ __('ptop.all') }}
                </a>
            </li>
            <li>
                @if(request()->is('p2p/*'))
                    <a href="{{ route('p2p_list','buy') }}">
                @else
                    <a href="{{ route('p2p_onprogress','buy') }}">
                @endif
                    {{ __('ptop.buy') }}
                </a>
            </li>
            <li>
                @if(request()->is('p2p/*'))
                    <a href="{{ route('p2p_list','sell') }}">
                @else
                    <a href="{{ route('p2p_onprogress','sell') }}">
                @endif
                    {{ __('ptop.sell') }}
                </a>
            </li>
            @if(request()->is('p2p/*'))
            <li>
                <a href="{{ route('p2p_list','my') }}">
                    {{ __('ptop.my_posts') }}
                </a>
            </li>
            @else
            @endif
        </ul>
        
    </div>
    