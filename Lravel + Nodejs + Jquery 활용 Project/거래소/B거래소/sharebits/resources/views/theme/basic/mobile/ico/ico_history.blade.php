@extends(session('theme').'.mobile.layouts.app') 
@section('content')
    @include(session('theme').'.mobile.ico.include.sub_menu')

{{-- 셀렉바(원래 자산현황 있어야하는데 보류) --}}
<div class="select_wrap">
    <div class="select_type">
        <label for="ico_type_check">
            <span class="type_tit">
               {{ __('icoo.ico_participation_history')}}
        </label>
    </div>
</div>
{{-- //셀렉바(원래 자산현황 있어야하는데 보류) --}}

<!-- scrl_wrap -->
<div id="ico_history_wrap" class="scrl_wrap m_ico_wrap">
    
    <div id="ico_history_li" class="history_st" data-offset="15" data-count="{{$count}}">
        <ul class="list">
            @forelse($icos as $ico)
            <li class="con buy">
                <p class="info _date mb-2">
                    <span>{{$ico->order_time}}</span>
                </p>
                <p class="info _coin">
                    {{ __('coin_name.'.strtolower($ico->order_coin)) }}(<u>{{$ico->order_coin}}</u>)
                </p>
                <p class="info">
                    <label>{{ __('icoo.buy_quantity')}}</label>
                    <span>{{$ico->buy_amount}}</span>
                    <span class="currency">{{$ico->order_coin}}</span>
                </p>
                <p class="info">
                    <label>{{ __('icoo.buy_price')}}</label>
                    <span>{{$ico->order_price}}</span>
                    <span class="currency">{{$ico->buy_pay}}</span>
                </p>
                <p class="info">
                    <label>{{ __('icoo.pay_price')}}</label>
                    <span>{{$ico->buy_price}}</span>
                    <span class="currency">{{$ico->buy_pay}}</span>
                </p>
                <p class="info">
                    <label>{{ __('icoo.total_payable_')}}</label>
                    <span>{{$ico->buy_amount}}</span>
                    <span class="currency">{{$ico->order_coin}}</span>
                </p>
            </li>
            @empty
                {{-- 없는 경우 --}}
                <li class="non_data">
                    <i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('icoo.no_ico')}}
                </li>
                {{-- //없는 경우 --}}
            @endforelse
        </ul>
    </div>

</div>
<!-- //scrl_wrap -->

@endsection