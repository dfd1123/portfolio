@extends(session('theme').'.mobile.layouts.app') 
@section('content')

<div id="store_wrapper">

    <div class="m_hd_title">
        <div class="inner">
            {{__('head.store')}}
        </div>
    </div>

    <!-- TODO: 추후 티켓,공식스토어 등등 생길 때 -->
    <!-- <div class="m_tab_list bt_line m_tab_list_store">
        <ul>
            <li class="active">
                <a href="#" onClick="alert('티켓&공식스토어는 준비 중입니다.')"> 
                    {{ __('head.ticket_official_store_mobile')}}
                </a>
            </li>
            <li>
                <a href="#" onClick="alert('티켓서비스 준비 중입니다.')"> 
                    {{ __('head.buy_ticket')}}
                </a>
            </li>
            <li>
                <a href="#" onClick="alert('공식스토어는 준비 중입니다.')"> 
                    {{ __('head.official_store')}}
                </a>
            </li>
        </ul>
    </div> -->

    <div class="scrl_wrap">

        <div class="store_intro_section">
            <div class="_inner">
                <img src="/images/example/ex_store_img_01.png" alt="ex_store_img_01">
                <div class="text_group">
                    <h4 class="_tit">{{ __('support.title_store_section_01') }}</h4>
                    <p class="_text">{!! __('support.title_store_section_01_txt') !!}</p>
                </div>
            </div>
        </div>

        <div class="store_intro_section store_intro_section--bg">
            <div class="_inner">
                <img src="/images/example/ex_store_img_02.png" alt="ex_store_img_01">
                <div class="text_group">
                    <h4 class="_tit">{{ __('support.title_store_section_02') }}</h4>
                    <p class="_text">{!! __('support.title_store_section_02_txt_mobile') !!}</p>
                </div>
            </div>
        </div>

        <div class="store_intro_section">
            <div class="_inner">
                <img src="/images/example/ex_store_img_03.png" alt="ex_store_img_01">
                <div class="text_group">
                    <h4 class="_tit">{{ __('support.title_store_section_03') }}</h4>
                    <p class="_text">{!! __('support.title_store_section_03_txt') !!}</p>
                </div>
            </div>
        </div>

    </div>  

</div>


@endsection