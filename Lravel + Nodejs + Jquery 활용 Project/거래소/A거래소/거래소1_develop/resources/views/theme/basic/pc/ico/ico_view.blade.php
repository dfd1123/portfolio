@extends(session('theme').'.pc.layouts.app') 
@section('content')


<div class="board_st_wrap p2p_ico_wrap">

    <div class="board_st_inner">

        <div class="board_st_con ico_view_con">

            {{-- 상단 메인정보(썸네일, 구매옵션창) --}}
            <div class="main_info">

                <div class="thumnail">
                    @if($ico->ico_thumnail == NULL)
                        <img src="{{asset('/storage/image/ico/no_image.jpg')}}" alt="{{$icos->ico_symbol}}" /> 
                    @else
                        <img src="{{asset('/storage/image/ico/'.$ico->ico_thumnail)}}" alt="{{$ico->ico_symbol}}" />
                    @endif
                </div>

                <section class="infos">

                    <span class="hd_coin">{{strtoupper($ico->ico_symbol)}}</span>
                    <h1 class="hd_tit">{{$ico->ico_title}}</h1>

                    <p class="info">
                        {{$ico->ico_intro}}
                    </p>
                    
                    <p class="info text-right _date">
                    {{ __('icoo.writer')}} <span class="idbywriten">{{$ico->w_name}}</span> {{ __('icoo.write_date')}} <span class="datewriten">{{$ico->created_at}}</span>
                    </p>

                    <p class="info _period">
                        <i class="img"></i>{{$ico->ico_from}} ~ {{$ico->ico_to}}
                    </p>

                    <p class="info s_info _psbcoin">
                        <label>{{ __('icoo.available_coin')}}</label>
                        <span>{{$ico->ico_collect}}</span>
                    </p>

                    <p class="info s_info _price">
                        <label>{{ __('icoo.buy_price')}}</label>
                        <span class="point_clr_1">1 {{strtoupper($ico->ico_symbol)}} ≈ <span id="get_ico_coin_p">{{$ico->ico_coin_p}}</span> {{strtoupper($ico->ico_collect)}}</span>
                    </p>

                    <p class="info s_info _myast">
                        <label>{{ __('icoo.my_asset')}}</label>
                        <span id="my_all_asset">{{$available_coin}} <span class="currency">{{$ico->ico_collect}}</span></span>
                    </p>
                    
                    <form method="POST" action="{{route('ico_buy',$ico->id)}}" name="ico_buy" id="ico_buy_form">
                        @csrf
                        <input type="hidden" name="ico_remain" value="{{$ico->ico_remain}}" />
                    <p class="info s_info _minimal">
                        <label>{{ __('icoo.minimum_buy')}}</label>
                        <span id="min_buy">{{$ico->ico_min}} <span class="currency">{{$ico->ico_collect}}</span></span>
                    </p>

                    <div class="info _totalsum">

                        <label class="point_clr_1">{{ __('icoo.all_payment_price')}}</label>
                        <div class="total-price-group">
                            <input id="ico_total_buy"type="number" name="total_buy" value="0" >
                            <span class="currency">{{$ico->ico_collect}}</span>
                            <span id="up_btn" class="up_btn updown_btn"></span>
                            <span id="down_btn" class="down_btn updown_btn"></span>
                        </div>
                        <ul class="buy_percent" style="display: none;">
                            <li>
                                <button type="button" class="left" id="per_10"></button>
                                <button type="button" id="per_25"></button>
                            </li>
                            <li>
                                <button type="button" id="per_50"></button>
                            </li>
                            <li>
                                <button type="button" id="per_75"></button>
                            </li>
                            <li>
                                <button type="button" id="per_100"></button>
                            </li>
                        </ul>
                        <p class="text-right pctvalue" id="pct_value" style="display: none;"><b id="is_percent">0</b>%</p>

                    </div>

                    <p class="info s_info _buyamt">
                        <label>{{ __('icoo.buy_quantity')}}</label>
                        <span>
                            <b id="change_price" class="point_clr_1">0</b>
                            <span class="currency">{{strtoupper($ico->ico_symbol)}}</span>
                        </span>
                    </p>

                    <p class="info_agree">
                        <label>
                            <input id="info_agree" type="checkbox" name="info_agree" class="grayCheckbox"/>{{ __('icoo.ico_sentence1')}}
                        </label>
                    </p>

                    @auth
                        @if(Auth::user()->status == 2)
                            <button type="button" class="btn_style mt-4 not_active_btn stop_user_id_warning">계정 정지</button> 
                        @else
                            <button type="button" class="bg_blue_btn mt-20  @if($ico->ico_category != 1 || $ico->active != 1) not_active_btn @endif" onclick="func_buy()">{{ __('icoo.buy')}}</button> 
                        @endif
                    @else
                        <button type="button" class="btn_style mt-4 not_active_btn">{{ __('icoo.login')}}</button> 
                    @endauth
                    </form>
                </section>

            </div>
            {{-- //상단 메인정보(썸네일, 구매옵션창) --}}

            {{-- 섹션1 (유의사항, 혜택, 관련기사 및 인터뷰) --}}
            <section class="info_section info_section1">
                <h6>{{ __('icoo.notice')}}</h6>
                <ul>
                    <li></li>
                </ul>

                <h6>{{ __('icoo.benefits')}}</h6>
                <ul>
                    @for($i=1; $i<=10; $i++) 
                        @if($ico->{'ico_benefit'.$i} != NULL)
                        <li>- {{$ico->{'ico_benefit'.$i} }}</li>
                    @endif @endfor
                </ul>
                
                <h6>{{ __('icoo.article_interview')}}</h6>
                <ul>
        
                    @for($i=1; $i <= 10; $i++)
                        @if( $ico->{'news'.$i} != NULL)
                        <?php $arr=explode('__',$ico->{'news'.$i});?>
                    <li>- <a href="{{$arr[1]}}" target="_blank">{{$arr[0]}}</a></li>
                        @endif 
                    @endfor
                    

                </ul>

            </section>
            {{-- //섹션1 (유의사항, 혜택, 관련기사 및 인터뷰) --}}
            
            {{-- 섹션2 (세부정보) --}}
            <section class="info_section info_section2">
                <h6>{{ __('icoo.details')}}</h6>
                <table>
                    <tr class="hp_tr">
                        <th>{{ __('icoo.symbol')}}</th>
                        <td>{{strtoupper($ico->ico_symbol)}}</td>
                        <th>{{ __('icoo.homepage')}}</th>
                        <td><a href="{{$ico->ico_url}}" target="_blank">{{$ico->ico_url}}</a></td>
                    </tr>
                    <tr class="down_tr">
                        <th>{{ __('icoo.calendar')}}</th>
                        <td>{{$ico->ico_from}} ~ {{$ico->ico_to}}</td>
                        <th>{{ __('icoo.white_paper')}}</th>
                        <td>
                            @if(isset($ico->ico_whitepaper_en))
                            <button type="button" id="wh_en" class="down_btn" onclick="window.open('{{asset('/storage/doc/ico'.$ico->ico_whitepaper_en)}}')">EN</button>
                            @endif 
                            @if(isset($ico->ico_whitepaper_jp))
                            <button type="button" id="wh_jp" class="down_btn" onclick="window.open('{{asset('/storage/doc/ico'.$ico->ico_whitepaper_jp)}}')">JP</button>
                            @endif
                            @if(isset($ico->ico_whitepaper_ch))
                            <button type="button" id="wh_ch" class="down_btn" onclick="window.open('{{asset('/storage/doc/ico'.$ico->ico_whitepaper_ch)}}')">CH</button>
                            @endif
                            @if(isset($ico->ico_whitepaper_kr))
                            <button type="button" id="wh_kr" class="down_btn" onclick="window.open('{{asset('/storage/doc/ico'.$ico->ico_whitepaper_kr)}}')">KR</button>
                            @endif
                        </td>
                    </tr>
                    <tr class="down_tr">
                        <th>{{ __('icoo.price')}}</th>
                        <td>{{$ico->ico_coin_p}} {{$ico->ico_collect}}</td>
                        <th>{{ __('icoo.analtsis_report')}}</th>
                        <td>
                            @if($ico->ico_report3)
                            <button type="button" id="rp_en" class="down_btn" onclick="window.open('{{asset('/storage/doc/ico'.$ico->ico_report3)}}')">EN</button>
                            @endif
                            @if($ico->ico_report2)
                            <button type="button" id="rp_jp" class="down_btn" onclick="window.open('{{asset('/storage/doc/ico'.$ico->ico_report2)}}')">JP</button>
                            @endif
                            @if($ico->ico_report1)
                            <button type="button" id="rp_ch" class="down_btn" onclick="window.open('{{asset('/storage/doc/ico'.$ico->ico_report1)}}')">CH</button>
                            @endif
                            @if($ico->ico_report4)
                            <button type="button" id="rp_kr" class="down_btn" onclick="window.open('{{asset('/storage/doc/ico'.$ico->ico_report4)}}')">KR</button>
                            @endif
                        </td>
                    </tr>
                    <tr class="sns_tr">
                        <th>{{ __('icoo.field_of_use')}}</th>
                        <td>{{$ico->ico_use_kind}}</td>
                        <th>{{ __('icoo.formula_sns')}}</th>
                        <td>
                            <a href="{{$ico->ico_sns1}}" target="_blank">FACEBOOK</a>
                            <a href="{{$ico->ico_sns2}}" target="_blank">TWITTER</a>
                            <a href="{{$ico->ico_sns3}}" target="_blank">BLOG</a>
                            <a href="{{$ico->ico_sns4}}" target="_blank">YOUTUBE</a>
                            <a href="{{$ico->ico_sns5}}" target="_blank">ETC</a>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('icoo.technology_base')}}</th>
                        <td>{{$ico->ico_tech}}</td>
                        <th></th>
                        <td></td>
                    </tr>
                </table>
            </section>
            {{-- //섹션2 (세부정보) --}}
            
            {{-- 섹션3 (코인소개 이미지) --}}
            <section class="info_section info_section3">
                <!-- 코인소개 이미지 첨부 자리 -->
                <div style="width:100%">
                    @for($i=1; $i<=3; $i++) 
                        @if($ico->{'ico_image'.$i} != NULL)
                            <img src="{{asset('/storage/image/ico'.$ico->{'ico_image'.$i}) }}" alt="" /></li>
                        @endif 
                    @endfor
                </div>

            </section>
            {{-- //섹션3 (코인소개 이미지) --}}

            <div class="text-right mt-3">
                <span class="mr-3">{{ __('icoo.manager_submit_02')}}</span>
                <button type="button" class="btn_style cancel_btn write_btn mr-2"  style="display: none;">{{ __('icoo.edit')}}</button>
                <button type="button" class="btn_style" onclick="location.href='{{ route('ico_list','all') }}'">{{ __('icoo.list')}}</button>
            </div>
            
        </div>

    </div>

</div>


@endsection