@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div id="social_wrapper">
    
    <div class="hd_container">
        <div class="tit_group">
            <h2 class="_tit">{{ __('support.social_trading') }}</h2>
            <p class="_text">{!! __('support.social_trading_bt_ment') !!}</p>
            <button type="button" class="_guide-btn">{{ __('support.risk_step_guide') }}</button>
        </div>
    </div>

    <div class="social_person_view_wrapper">

        <div class="social_person_info_container">
            <div class="social_person_profile">
                <img src="/images/example/social_person_profile.png" alt="social_person_profile">
            </div>

            <div class="social_person_info_section">
                <div class="social_person_tit_group">
                    <h4 class="_tit">COPIERS</h4>
                    <span class="_what_copier" onClick="social_popup('social_view_popup-01')">{{ __('support.what_copier') }}</span>
                    <ul class="_copier_info_group">
                        <li class="copier_info_list">
                            <em>copiers</em><i>999</i>
                        </li>
                        <li class="copier_info_list">
                            <em>copiers {{ __('support.asset') }}</em><i>30억~50억</i>
                        </li>
                        <li class="copier_info_list">
                            <em>{{ __('support.last_week') }}</em><i class="red-color">▲ 00.0%</i>
                        </li>
                    </ul>
                </div>
                <div class="ex-image">
                    <img src="/images/example/social_person_section_img_01.png" alt="social_person_section_img">
                </div>
            </div>
            
            <div class="social_person_info_section">
                <div class="social_person_tit_group">
                    <h4 class="_tit">{{ __('support.accumulated_rate_of_return') }}</h4>
                </div>
                <div class="ex-image">
                    <img src="/images/example/social_person_section_img_02.png" alt="social_person_section_img">
                </div>
            </div>
            
            <div class="social_person_info_section">
                <div class="social_person_tit_group">
                    <h4 class="_tit">{{ __('support.portpilio') }}</h4>
                    <button type="button" class="more-btn">+ {{ __('support.more_view') }}</button>
                </div>
                <div class="ex-image">
                    <img src="/images/example/social_person_section_img_03.png" alt="social_person_section_img">
                </div>
            </div>
            
            <div class="social_person_info_section">
                <div class="social_person_tit_group">
                    <h4 class="_tit">{{ __('support.rate_of_return') }}</h4>
                    <button type="button" class="more-btn">+ {{ __('support.more_view') }}</button>
                </div>
                <div class="ex-image">
                    <img src="/images/example/social_person_section_img_04.png" alt="social_person_section_img">
                </div>
            </div>
            
            <div class="social_person_info_section social_person_info_section--col_2">
                <div class="in_first_col">
                    <div class="social_person_tit_group">
                        <h4 class="_tit">{{ __('support.asset_holding_expenses') }}</h4>
                    </div>
                    <div class="ex-image">
                        <img src="/images/example/social_person_section_img_05-1.png" alt="social_person_section_img">
                    </div>
                </div>
                <div class="in_second_col">
                    <div class="social_person_tit_group">
                        <h4 class="_tit">{{ __('support.trading') }}</h4>
                    </div>
                    <div class="ex-image">
                        <img src="/images/example/social_person_section_img_05-2.png" alt="social_person_section_img">
                    </div>
                </div>
            </div>
            
            <div class="social_person_info_section">
                <div class="social_person_tit_group">
                    <h4 class="_tit">{{ __('support.monthly_risk_score') }}</h4>
                    <span class="_now_risk_score"><em>{{ __('support.now_risk_score') }} : </em><b>4 risk</b></span>
                    <ul class="now_risk_info_group">
                        <li class="now_risk_info_list">
                            <em>{{ __('support.risk_panel_ment') }}</em><i class="red-color">{{ __('support.benefit') }} ↑</i> <i class="blue-color">{{ __('support.risks') }} ↓</i>
                        </li>
                        <li class="now_risk_info_list">
                            <em>{{ __('support.risk_panel_ment') }}</em><i class="red-color">{{ __('support.benefit') }} ↑</i> <i class="blue-color">{{ __('support.risks') }} ↓</i>
                        </li>
                    </ul>
                </div>
                <div class="ex-image">
                    <img src="/images/example/social_person_section_img_06.png" alt="social_person_section_img">
                </div>
            </div>
        </div>

        <div class="social_person_popup_container">
            <div class="popup">
                <p><b>박현상</b>{!! __('support.strategic_copy_ment') !!}</p>
                <button type="button" class="copy-btn">
                    <img src="/images/example/copy_text.svg" alt="copy_text">
                </button>
            </div>
        </div>

    </div>
    
    <div class="social_popup social_view_popup-01">
        <img src="/images/example/ex_popup_03.png" alt="social popup image">
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