@extends(session('theme').'.mobile.layouts.app') 
@section('content')
@include(session('theme').'.mobile.p2p.include.sub_menu')
@include(session('theme').'.mobile.p2p.include.select_bar')

<!-- scrl_wrap -->
<div id="p2p_onpro_wrap" class="scrl_wrap m_p2p_wrap m_p2p_wrap-2">

    {{-- 진행중 리스트들 --}}
    <div class="p2p_onpro_wrap">
        <div id="p2p_onpro_con" class="p2p_onpro_cards" data-offset="{{$offset}}" data-count="{{$count}}" data-type="{{$type}}">
            @forelse($p2ps as $p2p)
            <!-- 구매신청 카드 -->
            <div class="list_in_con_outer">
                <div class="list_in_con">

                    <div class="in_left_con">
                        
                        <div class="hd">
                            
                            @if($p2p->b_id==Auth::user()->id)
                            
                                <label class="tit">{{ __('ptop.apply_buy') }}</label>
                                
                                <span class="etc_info pl-1 pr-1"><u>{{ __('ptop.writer') }}</u>  {{$p2p->name}}</span>
                                <span class="etc_info"><u>{{ __('ptop.application_date') }}</u>  {{date("Y-m-d",strtotime($p2p->start))}}</span>
                                
                            
                            @elseif($p2p->s_id==Auth::user()->id)
                            
                                <label class="tit">{{ __('ptop.apply_sell') }}</label>
                                
                                <span class="etc_info pl-1 pr-1"><u>{{ __('ptop.writer') }}</u>  {{$p2p->name}}</span>
                                <span class="etc_info"><u>{{ __('ptop.application_date') }}</u>  {{date("Y-m-d",strtotime($p2p->start))}}</span>
                            
                            @endif
                            
                            <!-- 구매신청 2단계일 때 떠야하는 버튼입니다. -->
                            @if($p2p->b_id==Auth::user()->id && $p2p->confirm==2)
                            
                                <p class="text-right mt-2">
                                    <a class="write_btn_st coin_in_check" onclick="location.href='{{route('p2p_confirm',$p2p->id)}}'">{{ __('ptop.coin_check_in') }}</a>    
                                </p>
                            
                                @endif
                            <!-- //구매신청 2단계일 때 떠야하는 버튼입니다. -->
                            
                        </div>

                        <div class="info_1">

                            <img src="{{ asset('/storage/image/homepage/coin_img')}}/{{$p2p->coin_type}}.png" alt="coin_img" class="coin_symbol">
                            <p class="coin_name">{{__('coin_name.'.$p2p->coin_type)}}</p>
                            <span class="coin_name_eng ml-1">{{$p2p->coin_type}}</span>
                            
                            
                            <span class="have_coin_check">
                                <a href="{{__('blockexplore.'.$p2p->coin_type).$p2p->s_coin_address}}" target="_blank">
                                {{ __('ptop.confirm_possession') }}
                                </a>
                            </span> 
                            
                        </div>

                        <div class="info_2 bb-f0f0">
                            <ul>
                                <li class="amt">
                                    <label class="mr-2">{{ __('ptop.quantity') }}</label>
                                    <span>{{$p2p->coin_amount}}<span class="currency pl-1">{{strtoupper($p2p->coin_type)}}</span></span>
                                </li>

                                <li class="prc">
                                    <label class="mr-2">{{ __('ptop.price') }}</label>
                                    <span>{{$p2p->coin_price}}<span class="currency pl-1">{{strtoupper($p2p->country_money)}}</span></span>
                                </li>
                            </ul>
                        </div>

                    </div>

                    <div class="in_right_con">
                        
                        @if($p2p->b_id==Auth::user()->id)
                        
                        

                            @if($p2p->confirm == 1)
                                <div class="step_ment active _1">
                            @else
                                <div class="step_ment">
                            @endif
                                    <p>{{ __('ptop.p_to_p_sentence1') }}</p>
                                    <span>{{$p2p->s_account}}</span>
                                </div>
                            
                            @if($p2p->confirm == 2)
                                <div class="step_ment active">
                            @else
                                <div class="step_ment">
                            @endif
                                <p>{{ __('ptop.p_to_p_sentence2') }}</p>
                                <span>{{$p2p->s_coin_address}}</span>
                            </div>
                            
                            @if($p2p->confirm == 3)
                                <div class="step_ment active _3">
                            @else
                                <div class="step_ment">
                            @endif
                                <p>{{ __('ptop.p_to_p_sentence3') }}</p>
                                <span>{{$p2p->s_coin_address}}</span>
                            </div>

                        @elseif($p2p->s_id == Auth::user()->id)
                    
                        
                            @if($p2p->confirm == 1)
                            <div class="step_ment active _1">
                            @else
                            <div class="step_ment">
                            @endif
                                    <p>{{ __('ptop.you') }} {{$p2p->country_money}} {{ __('ptop.me') }} </p>
								    <p>{{ __('ptop.sell_ment_deposit_confirm') }}</p>
                            </div>

                            @if($p2p->confirm == 2)
                            <div class="step_ment active">
                            @else
                            <div class="step_ment">
                            @endif
                                <p>{{ __('ptop.home') }} {{strtoupper($p2p->coin_type)}} {{ __('ptop.give') }}</p>
                                <span>{{$p2p->b_coin_address}}</span>
                            </div>
                            
                            @if($p2p->confirm == 3)
                            <div class="step_ment active _3">
                            @else
                            <div class="step_ment">    
                            @endif
                                <p>{{ __('ptop.p_to_p_sentence3') }}</p>
                                <span>{{$p2p->b_coin_address}}</span>
                            </div>

                        @endif
                        
                    </div>
                    <div class="in_button_con">
                    @if($p2p->s_id == Auth::user()->id && $p2p->b_id == Auth::user()->id)
                    <button class="cancel btn" onclick="confirm_cancel({{$p2p->id}})" style="width:100%">거래취소</button>
                    @elseif($p2p->s_id == Auth::user()->id)
                        <button class="confirm btn mr-1" onclick="confirm_okay({{$p2p->id}})">입금확정</button>
                        <button class="cancel btn" onclick="confirm_cancel({{$p2p->id}})">거래취소</button>
                    @else
                        <button class="cancel btn" onclick="confirm_cancel({{$p2p->id}})" style="width:100%">거래취소</button>
                    @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="non_data bd-dddd">
                <img src="/images/icon_notice.svg" alt="" class="btn_notice">{{ __('ptop.noinfo')}}
            </div>
            <!-- 구매신청 카드 -->
            @endforelse
        </div>
    </div>
    {{-- 진행중 리스트들 --}}

</div>
<!-- //scrl_wrap -->

@endsection