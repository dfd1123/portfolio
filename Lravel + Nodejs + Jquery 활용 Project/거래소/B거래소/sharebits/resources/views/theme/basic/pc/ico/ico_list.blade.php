
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
                        {{($pagename == 'ico')? 'ICO' : __('icoo.my_submit_ico')}} 
                    </h1>


                    <div class="tab_menu_bar">
                        <ul>
                            <li class="{{ ($category=='all')?'active':'' }}">
                                <a href="{{ ($pagename=='ico')?route('ico_list','all'):route('my_ico','all') }}">
                                    <i class="fal fa-bars mr-1"></i>{{ __('icoo.all')}}
                                </a>
                            </li>
                            <li class="{{ ($category=='1')?'active':'' }}">
                                <a href="{{ ($pagename=='ico')?route('ico_list','1'):route('my_ico','1') }}">
                                {{ __('icoo.ing')}}
                                </a>
                            </li>
                            <li class="{{ ($category=='3')?'active':'' }}">
                                <a href="{{($pagename=='ico')?route('ico_list','3'):route('my_ico','3') }}">
                                {{ __('icoo.end')}}
                                </a>
                            </li>
                            <li class="{{ ($category=='2')?'active':'' }}">
                                <a href="{{($pagename=='ico')?route('ico_list','2'):route('my_ico','2') }}">
                                {{ __('icoo.will_ing')}}                                </a>
                            </li>
                            <li class="{{ ($category=='4')?'active':'' }}">
                                <a href="{{($pagename=='ico')?route('ico_list','4'):route('my_ico','4') }}">
                                {{ __('icoo.sold_out')}}
                                </a>
                            </li>
                        </ul>
                        @auth
                            @if($pagename == 'ico')
                            <a href="{{route('ico_create')}}" class="write_btn_st"><i class="fal fa-plus"></i> {{ __('icoo.ico_submit')}}</a>
                            @endif
                        @endauth
                    </div>


                    <div class="ico_list_wrap mt-4">

                        <div class="ico_list_group">

                            @forelse($icos as $ico)
                            <div class="list_in_con">
                                <a href="{{route('ico_show',$ico->id)}}">
                                    <div class="thumnail @if ($ico->active == 0)waiting @elseif ($ico->ico_category == 1 && $ico->active==1)oncoming confirm @elseif ($ico->ico_category == 2 && $ico->active==1)upcoming confirm @elseif ($ico->ico_category == 3 && $ico->active==1)end confirm elseif($ico->ico_category == 4 && $ico->active==1)soldout confirm @endif">
                                        @if($ico->ico_thumnail == NULL)
                                        <img src="{{asset('/storage/image/ico/no_image.jpg')}}" alt="" /><br> 
                                        @else
                                        <img src="{{asset('/storage/image/ico'.$ico->ico_thumnail)}}" alt="" /><br>
                                        @endif
                                    </div>

                                    <div class="infos">
                                        <p class="hd">
                                            {{-- 파는 코인 / 글제목 --}}
                                            <span>{{$ico->ico_symbol}}</span>
                                            <span>{{$ico->ico_title}}</span>
                                        </p>

                                        <p class="info _txt">
                                            {{-- 소개내용 --}} {{$ico->ico_intro}}
                                        </p>

                                        <p class="info _period">
                                            {{-- 시작기간 시간 ~ 마감기간 시간 --}} {{$ico->ico_from}} ~ {{$ico->ico_to}}
                                        </p>

                                        <p class="info _psbcoin">
                                            {{-- 구매가능한 코인 --}}
                                            <label>{{$ico->ico_collect}}</label>
                                            <span>1 {{$ico->ico_symbol}} ≈ {{$ico->ico_coin_p}} {{$ico->ico_collect}}</span>
                                        </p>

                                        <p class="info _minimal">
                                            <label>{{ __('icoo.small_buy')}}</label>
                                            <span>{{$ico->ico_min}} {{$ico->ico_symbol}}</span>
                                        </p>

                                        <p class="info text-right _date">
                                            {{-- 작성자 및 작성일 --}} [ {{$ico->w_name}} ] {{$ico->created_at}}
                                        </p>
                                    </div>

                                </a>
                            </div>
                            @empty
                            <div class="none_transac bd-dddd">
                                <i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('icoo.no_submit')}}
                            </div>
                            @endforelse

                        </div>

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