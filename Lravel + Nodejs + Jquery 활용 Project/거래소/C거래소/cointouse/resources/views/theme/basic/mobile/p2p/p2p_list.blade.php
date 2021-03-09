@extends(session('theme').'.mobile.layouts.app') 
@section('content')
@include(session('theme').'.mobile.p2p.include.sub_menu')
@include(session('theme').'.mobile.p2p.include.select_bar')

<!-- scrl_wrap -->
<div id="p2p_list_wrap" class="scrl_wrap m_p2p_wrap m_p2p_wrap-1">
    
    <!-- 검색바 -->
    <div class="sch_bar">
        <form method="get" action="" class="form-group" style="width:100%;">
            @csrf
            <div class="coin_sch_bar">
                <div class="inner">
                    <input type="text" placeholder="{{ __('ptop.writersearch')}}" id="p2p_list_srch" class="form-control" name="srch"/>
                    <button type="submit" class="sch_icon"></button>
                </div>
            </div>
            <div class="coin_sch_checkbox">
                <select id="select_p2p_list" class="form-control mr-1" name="category">
                    <option value="all" {{($category == 'all')?'selected=selected':''}} >{{ __('ptop.all') }}</option>
                    <option value="coin_type" {{($category == 'coin_type')?'selected=selected':''}}>{{ __('ptop.coin') }}(BTC, ETH..)</option>
                    <option value="name" {{($category == 'name')?'selected=selected':''}}>{{ __('ptop.writer') }}</option>
                </select>
            </div>
        </form>
    </div>
    <!-- //검색바 -->

    {{-- 장외거래 리스트들 --}}
    <div class="p2p_list_wrap">

        <div id="p2p_list_con" class="p2p_list_group" data-offset="{{$offset}}" data-count="{{$p2p_count}}" data-type="{{$type}}">
            
            @forelse($p2ps as $p2p)
            {{-- list_in_con --}}
            
            <div class="list_in_con_outer">
                <div class="list_in_con">

                    <div class="hd">

                        @if($p2p->type=='buy')
                        <label class="tit buy_tit">
                        @elseif($p2p->type=='sell')
                        <label class="tit sell_tit" >
                        @endif
                            {{__('p2p.'.$p2p->type.'s')}}
                            {{__('p2p.'.$p2p->state)}}
                        </label>
                        <p class="etc_info">
                            <span>[ {{$p2p->name}} ]</span>
                            <span>{{date("Y-m-d",strtotime($p2p->start))}}</span>
                        </p>
                        @auth
                            @if($p2p->uid==Auth::user()->id && $p2p->confirm==0)
                                <!-- 본인 작성글 삭제하는 버튼 -->
                                <button class="del_btn" onclick="location.href='{{ route('p2p_deleted',$p2p->id) }}'">{{ __('ptop.delete') }}</button>
                                <!-- 본인 작성글 삭제하는 버튼 -->
                            @endif
                        @endauth
                    </div>

                    <div class="info_1">

                        <img src="{{ asset('/storage/image/homepage/coin_img')}}/{{$p2p->coin_type}}.png" alt="coin_img" class="coin_symbol">
                        <p class="coin_name">{{__('coin_name.'.strtolower($p2p->coin_type))}}</p>
                        <span class="coin_name_eng">{{$p2p->coin_type}}</span>

                        <!-- 판매일 경우, 블록익스플로러로 보유확인할 수 있는 버튼 -->
                        @if($p2p->type=='sell')
                        <span class="have_coin_check" id="c_addr">
                            <a href="{{__('blockexplore.'.$p2p->coin_type).$p2p->s_coin_address}}" target="_blank">
                            {{ __('ptop.confirm_possession') }}
                            </a>
                        </span> @endif
                        <!-- 판매일 경우, 블록익스플로러로 보유확인할 수 있는 버튼 -->

                    </div>

                    <div class="info_2">
                        <ul>
                            <li class="amt">
                                <label class="mr-2">{{ __('ptop.quantity') }}</label>
                                <span>{{$p2p->coin_amount}} <span class="currency pl-1">{{$p2p->coin_type}}</span></span>
                            </li>

                            <li class="prc">
                                <label class="mr-2">{{ __('ptop.price') }}</label>
                                <span>{{$p2p->coin_price}}<span class="currency pl-1">{{$p2p->country_money}}</span></span>
                            </li>
                        </ul>
                    </div>

                    <div class="info_3">
                        <textarea readonly="readonly">{!!$p2p->cont!!}</textarea>
                    </div>

                    @auth
                        @if(Auth::user()->status == 2)
                            <button class="btn_style not_active_btn stop_user_id_warning" type="button">계정 정지</button> 
                        @elseif($p2p->state == 'on')
                            <button class="btn_style apply_btn write_btn p2pApply" data-id="{{$p2p->id}}" type="button">{{__('p2p.'.$p2p->type)}} {{ __('ptop.apply1') }}</button>							
                        @elseif($p2p->state == 'onProgress')
                            <button class="btn_style not_active_btn" type="button">{{ __('ptop.ing') }}</button> 
                        @elseif($p2p->state == 'complete')
                            <button class="btn_style not_active_btn" type="button">{{ __('ptop.complete') }}</button> 
                        @elseif($p2p->state == 'stop')
                            <button class="btn_style not_active_btn" type="button">{{ __('ptop.stop') }}</button> 
                        @endif
                    @else
                        @if($p2p->state == 'on')
                            <button class="btn_style apply_btn write_btn" type="button" onclick="location.href='{{route('login')}}'">{{__('p2p.'.$p2p->type)}} {{ __('ptop.apply1') }}</button>							
                        @elseif($p2p->state == 'onProgress')
                            <button class="btn_style not_active_btn" type="button">{{ __('ptop.ing') }}</button> 
                        @elseif($p2p->state == 'complete')
                            <button class="btn_style not_active_btn" type="button">{{ __('ptop.complete') }}</button> 
                        @elseif($p2p->state == 'stop')
                            <button class="btn_style not_active_btn" type="button">{{ __('ptop.stop') }}</button> 
                        @endif
                    @endauth

                </div>
            </div>
            {{-- //list_in_con --}}
            @empty
            
            <div class="non_data">
                <i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('ptop.p_to_p_sentence6') }}
            </div>

            @endforelse

        </div>

    </div>
    {{-- //장외거래 리스트들 --}}

</div>
<!-- //scrl_wrap -->
@auth
    @if(Auth::user()->status != 2)
        @include(session('theme').'.mobile.p2p.p2p_apply_modal')
        @include(session('theme').'.mobile.p2p.p2p_create')
    @endif
@endauth
    
<script>
    if (typeof __ === 'undefined') { var __ = {}; }
    __.ptop = {
        @foreach(__('ptop') as $key => $value)
            '{{$key}}':'{{$value}}',
        @endforeach
    }
	$(window).scroll(function(){
		if($(window).scrollTop() >= $(document).height()-$(window).height()){
			more_p2p_list('{{$type}}',{{Auth::check()}});
		}
    })
    
</script>

@endsection