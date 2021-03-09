@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div class="board_st_wrap p2p_ico_wrap adv_wrap">

    <div class="board_st_inner">
        
        {{-- 왼쪽 광고배너영역 --}}
        <div class="left_adv_banner_wrap">
            <div class="adv_banners">
                @if(isset($left_banners) && count($left_banners) != 0)
                    @foreach($left_banners as $left_banner)
                    <a href='{{$left_banner->target_url}}'><img src="{{asset('/storage/image/banner/' . $left_banner->banner_url)}}" onerror="this.src='{{asset('/storage/image/banner/left_right_no_image.jpg')}}'" /></a>
                    @endforeach
                @endif
            </div>
        </div>
        {{-- //왼쪽 광고배너영역 --}}

        <div class="board_st_con">
        
            @include(session('theme').'.pc.ico.include.sub_menu')

            <div class="right_con">
                
                <!-- 상단 광고배너영역 -->
                @if(isset($top_banners) && count($top_banners) != 0)
                    <div class="adv_banner_wrap">
                        @foreach($top_banners as $top_banner)
                        <a href='{{$top_banner->target_url}}'><img src="{{asset('/storage/image/banner/' . $top_banner->banner_url)}}" onerror="this.src='{{asset('/storage/image/banner/top_no_image.jpg')}}'"/></a>
                        @endforeach
                    </div>
                @endif
                <!-- //상단 광고배너영역 -->

                <h1 class="main_tit">
                {{ __('icoo.my_ico_list')}}
                </h1>

                <div class="tab_menu_bar eng_w_long">
                    <ul>
                        <li class="active">
                            <a href="#">
                            {{ __('icoo.ico_participation_history')}}
                            </a>
                        </li>
                        {{--
                        <li>
                            <a href="#">
                            {{ __('icoo.ico_asset_status')}}
                            </a>
                        </li> --}}
                    </ul>
                </div>

                <div class="ico_list_wrap ico_hstry_wrap mt-3">

                    <table class="table_label">
                        <thead>
                            <tr>
                                <th>{{ __('icoo.buy_time')}}</th>
                                <th>{{ __('icoo.coin')}}</th>
                                <th>{{ __('icoo.buy_quantity')}}</th>
                                <th>{{ __('icoo.buy_price')}}</th>
                                <th>{{ __('icoo.pay_price')}}</th>
                                <th>{{ __('icoo.total_payable_')}}</th>
                            </tr>
                        </thead>
                    </table>

                    <table class="coin_chart_tbl">
                        <tbody>
                            @forelse($icos as $ico)
                            <tr>
                                <td>
                                    <p>
                                        {{-- 구매시간 --}}
                                        <span>{{$ico->order_time}}</span>
                                    </p>
                                </td>
                                <td class="coin_td">
                                    {{-- 코인 --}}
                                    <span class="coin_name">{{ __('coin_name.'.strtolower($ico->order_coin)) }}</span>
                                    <span class="coin_name_eng">{{$ico->order_coin}}</span>
                                </td>
                                <td>
                                    <p>
                                        {{-- 구매수량 --}}
                                        <span>{{$ico->buy_amount}}</span>
                                        <span class="currency">{{$ico->order_coin}}</span>
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        {{-- 구매단가 --}}
                                        <span>{{$ico->order_price}}</span>
                                        <span class="currency">{{$ico->buy_pay}}</span>
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        {{-- 결제금액 --}}
                                        <span>{{$ico->buy_price}}</span>
                                        <span class="currency">{{$ico->buy_pay}}</span>
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        {{-- 총 지급수량 --}}
                                        <span>{{$ico->buy_amount}}</span>
                                        <span class="currency">{{$ico->order_coin}}</span>
                                    </p>
                                </td>
                            </tr>
                            @empty
                            {{-- 없는 경우 --}}
                            <tr class="none_transac">
                                <td colspan="8">
                                    <i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('icoo.ico_sentence2')}}
                                </td>
                            </tr>
                            {{-- 없는 경우 --}}
                            @endforelse
                        </tbody>
                    </table>

                </div>

            </div>

        </div>
        
        {{-- 오른쪽 광고배너영역 --}}
        <div class="right_adv_banner_wrap">
            <div class="adv_banners">
                @if(isset($right_banners) && count($right_banners) != 0)
                    @foreach($right_banners as $right_banner)
                    <a href='{{$right_banner->target_url}}'><img src="{{asset('/storage/image/banner/' . $right_banner->banner_url)}}" onerror="this.src='{{asset('/storage/image/banner/left_right_no_image.jpg')}}'" /></a>
                    @endforeach
                @endif
            </div>
        </div>
        {{-- //오른쪽 광고배너영역 --}}

    </div>

</div>
@endsection