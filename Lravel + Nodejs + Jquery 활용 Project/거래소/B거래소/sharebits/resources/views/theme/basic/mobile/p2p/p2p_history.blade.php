@extends(session('theme').'.mobile.layouts.app') 
@section('content')
@include(session('theme').'.mobile.p2p.include.sub_menu')

<!-- scrl_wrap -->
<div id="p2p_history_wrap" class="scrl_wrap m_p2p_wrap m_p2p_wrap-3">
    <form class="note_label" style="width:100%;">
    <!-- 검색바 -->
    {{-- ※ 참고: p2p 완료내역 안돌아가니, 손봐야함 --}}
    <div class="sch_bar">
        
                @csrf
            <div class="coin_sch_bar">
                <div class="inner">
                    <input type="text" id="txtFilter" placeholder="{{ __('ptop.search_coin') }}" />
                    <button type="submit" class="sch_icon"></button>
                </div>
            </div>
            <div class="coin_sch_checkbox">
                <select id="selectFilter" class="type_sort_slt">
                        <option value="">{{ __('ptop.all') }}</option>
                        <option value="{{ __('ptop.buy') }}">{{ __('ptop.buy') }}</option>
                        <option value="{{ __('ptop.sell') }}">{{ __('ptop.sell') }}</option>
                </select>
            </div>
        
    </div>
    {{-- ※ 참고: p2p 완료내역 검색기능 안돌아가니, 손봐야함 --}}
    <!-- //검색바 -->
    
    <!-- 조회 -->
    {{-- ※ 참고: p2p 완료내역 검색옵션 안돌아가니, 손봐야함 --}}
    <div class="sch_option_wrap">
                
        <div class="option_bar bb-dddd">
            <label for="option_group_check">
                <span>{{__('ptop.viewdate')}}</span>
                <i class="fal fa-sliders-h"></i>
            </label>
        </div>
        
        <input id="option_group_check" type="checkbox" class="hide">
        
        <div class="option_group">
            <div class="inner_line mb-2">
                <button class="btns" onclick="input_calendar_data(7);">{{ __('ptop.one_week') }}</button>
                <button class="btns" onclick="input_calendar_data(14);">{{ __('ptop.two_weeks') }}</button>
                <button class="btns" onclick="input_calendar_data(30);">{{ __('ptop.one_month') }}</button>
            </div>
            <div class="inner_line mb-2">
                <input class="input" type="text" id="start_sch" name="from_date" value="{{$from_date}}" readonly="readonly">
                <span>~</span>
                <input class="input"  type="text" id="end_sch" name="to_date" value="{{$to_date}}" readonly="readonly">
            </div>
            <div class="inner_line">
                <button class="btn_style" onclick="search_date_history();">{{ __('ptop.lookup') }}</button>
            </div>
        </div>
        
    </div>
    </form>
    {{-- ※ 참고: p2p 완료내역 검색옵션 안돌아가니, 손봐야함 --}}
    <!-- //조회 -->
    
<div class="history_st" id="p2p_history_con" data-count="{{$p2p_count}}" data-offset="{{$offset}}" >
    
    <ul class="list target">
        @forelse($p2ps as $p2p)
            @if($p2p->type == 'buy')
            <li class="con buy" name="{{__('coin_name.'.$p2p->coin_type)}}/{{$p2p->coin_type}}">
            @else
            <li class="con sell" name="{{__('coin_name.'.$p2p->coin_type)}}/{{$p2p->coin_type}}">
            @endif
                <p class="info _date target_detail mb-2">
                    @if($p2p->uid == $p2p->b_id)
                        <span class="float-right type">{{ __('ptop.buy') }}</span>
                    @elseif($p2p->uid == $p2p->s_id )
                        <span class="float-right type">{{ __('ptop.sell') }}</span>
                    @endif
                </p>
                <p class="info _coin">
                    {{__('coin_name.'.$p2p->coin_type)}}(<u>{{$p2p->coin_type}}</u>)
                </p>
                <p class="info">
                    <label>{{__('ptop.quantity')}}</label>
                    <span>{{$p2p->coin_amount}}</span>
                    <span class="currency">{{strtoupper($p2p->coin_type)}}</span>
                </p>
                <p class="info">
                    <label>{{ __('ptop.trade_price')}}</label>
                    <span>{{number_format($p2p->coin_price)}}</span>
                    <span class="currency">{{$p2p->country_money}}</span>
                </p>
                <p class="info">
                    <label>{{ __('ptop.selldr_buyer') }}</label>
                    <span>{{$p2p->name}}</span>
                </p>
                <p class="info">
                    <label>{{ __('ptop.due_date_trade') }}</label>
                    <span>{{$p2p->end}}</span>
                </p>
            </li>
        @empty	
        {{-- 없는 경우 --}}
        <li class="non_data">
            <i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('ptop.p_to_p_sentence6') }}
        </li>
        {{-- //없는 경우 --}}
        @endforelse
    </ul>
</div>


@endsection