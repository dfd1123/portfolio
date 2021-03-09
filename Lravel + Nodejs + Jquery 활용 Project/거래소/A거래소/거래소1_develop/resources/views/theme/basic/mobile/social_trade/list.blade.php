@extends(session('theme').'.mobile.layouts.app') 
@section('content')

<div id="social_wrapper">

    <div class="m_hd_title">
        <div class="inner">
            {{__('head.social_trading')}}
        </div>
    </div>
    
    <div class="hd_container">
        <div class="tit_group">
            <p class="_text">{!! __('support.social_trading_bt_ment') !!}</p>
            <button type="button" class="_guide-btn" onClick="social_popup('social_popup-02')">{{ __('support.risk_step_guide') }} ></button>
        </div>
    </div>

    <div class="social_section social_section_01">
        <h4 class="_tit">{{ __('support.top_10_popular') }}</h4>
        <p class="_text">{{ __('support.top_10_popular_bt_ment') }}</p>
    
        <div class="popular_card_wrapper">
            <ul class="popular_card_group">
                <li class="popular_card_list">
                    <img src="/images/example/card_popular_01.png" alt="good popular card" onClick="social_popup('social_popup-01')">
                </li>
                <li class="popular_card_list">
                    <img src="/images/example/card_popular_02.png" alt="good popular card" onClick="social_popup('social_popup-01')">
                </li>
                <li class="popular_card_list">
                    <img src="/images/example/card_popular_03.png" alt="good popular card" onClick="social_popup('social_popup-01')">
                </li>
                <li class="popular_card_list">
                    <img src="/images/example/card_popular_04.png" alt="good popular card" onClick="social_popup('social_popup-01')">
                </li>
                <li class="popular_card_list">
                    <img src="/images/example/card_popular_05.png" alt="good popular card" onClick="social_popup('social_popup-01')">
                </li>
                <li class="popular_card_list">
                    <img src="/images/example/card_popular_06.png" alt="good popular card" onClick="social_popup('social_popup-01')">
                </li>
                <li class="popular_card_list">
                    <img src="/images/example/card_popular_07.png" alt="good popular card" onClick="social_popup('social_popup-01')">
                </li>
            </ul>
        </div>
    </div>

    <div class="social_section social_section_02">

        <div class="card_wrapper">

            <div class="social_cards_title_group">
                <h4 class="_tit"><img src="/images/icon/social_cards_wrap_01.svg" alt="icon" class="tit_in_icon">{{ __('support.high_popular') }}</h4>
                <a href="#">+ {{ __('support.more_view') }}</a>
            </div>
            
            <!-- 인기급상승 -->
            <div class="social_cards_wrapper">
                <img src="/images/example/card_good_group.png" alt="" onClick="location.href='{{route('social_trade.show','1')}}'">
            </div>

        </div>

        <div class="card_wrapper">
            
            <div class="social_cards_title_group">
                <h4 class="_tit"><img src="/images/icon/social_cards_wrap_02.svg" alt="icon" class="tit_in_icon">{{ __('support.ranking_benefit') }}</h4>
                <a href="#">+ {{ __('support.more_view') }}</a>
            </div>

            <!-- 수익률 순위 -->
            <div class="social_cards_wrapper">
                <img src="/images/example/card_good_group.png" alt="" onClick="location.href='{{route('social_trade.show','1')}}'">
            </div>

        </div>
        
        <div class="card_wrapper">
            
            <div class="social_cards_title_group">
                <h4 class="_tit"><img src="/images/icon/social_cards_wrap_03.svg" alt="icon" class="tit_in_icon">{{ __('support.ranking_copy') }}</h4>
                <a href="#">+ {{ __('support.more_view') }}</a>
            </div>

            <!-- 수익률 순위 -->
            <div class="social_cards_wrapper">
                <img src="/images/example/card_good_group.png" alt="" onClick="location.href='{{route('social_trade.show','1')}}'">
            </div>

        </div>
        
        
        <div class="card_wrapper">
            
            <div class="social_cards_title_group">
                <h4 class="_tit"><img src="/images/icon/social_cards_wrap_04.svg" alt="icon" class="tit_in_icon">{{ __('support.ranking_risk_investment') }}</h4>
                <a href="#">+ {{ __('support.more_view') }}</a>
            </div>

            <!-- 투자 위험도 순위 -->
            <div class="social_cards_wrapper">
                <img src="/images/example/card_bad_group.png" alt="" onClick="location.href='{{route('social_trade.show','1')}}'">
            </div>

        </div>

    </div>

    <div class="m_social_popup social_popup social_popup-01">
        <img src="/images/example/mobile_ex_social_popup_02.svg" alt="social popup image">
        <button type="button" class="_x-btn" onClick="social_popup_close()"></button>
    </div>

    <div class="m_social_popup social_popup social_popup-02">
        <img src="/images/example/mobile_ex_social_popup_01.svg" alt="social popup image">
        <button type="button" class="_x-btn" onClick="social_popup_close()"></button>
    </div>

</div>


<!-- 임시팝업 -->
<script>

    function social_popup(name){
        $('.'+name).fadeIn(300).removeClass('popup_close_ani').addClass('popup_open_ani');
    }

    function social_popup_close(){

        $('.social_popup').addClass('popup_close_ani').removeClass('popup_open_ani').fadeOut(200);

    }
</script>
<!-- end -->

@endsection