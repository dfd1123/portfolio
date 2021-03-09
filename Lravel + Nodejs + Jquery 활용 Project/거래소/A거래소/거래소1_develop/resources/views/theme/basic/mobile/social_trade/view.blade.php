@extends(session('theme').'.mobile.layouts.app') 
@section('content')

<div id="social_wrapper">

    <div class="m_hd_title">
        <div class="inner">
            {{__('head.social_trading')}}
        </div>
    </div>

    <div class="social_person_view_wrapper">

        <div class="social_person_info_container">
            <div class="social_person_profile">
                <img src="/images/example/mobile_social_person_profile.svg" alt="social_person_profile">
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
                    <img src="/images/example/mobile_social_person_section_img_01.svg" alt="social_person_section_img">
                </div>
            </div>
            
            <div class="social_person_info_section">
                <div class="social_person_tit_group">
                    <h4 class="_tit">{{ __('support.accumulated_rate_of_return') }}</h4>
                </div>
                <div class="ex-image">
                    <img src="/images/example/mobile_social_person_section_img_02.svg" alt="social_person_section_img">
                </div>
            </div>
            
            <div class="social_person_info_section">
                <div class="social_person_tit_group">
                    <h4 class="_tit">{{ __('support.portpilio') }}</h4>
                    <button type="button" class="more-btn">+ {{ __('support.more_view') }}</button>
                </div>
                <div class="ex-image ">
                    <img src="/images/example/mobile_social_person_section_img_03.svg" alt="social_person_section_img">
                </div>
            </div>
            
            <div class="social_person_info_section social_person_info_section--return">
                <div class="social_person_tit_group">
                    <h4 class="_tit">{{ __('support.rate_of_return') }}</h4>
                    <button type="button" class="more-btn">+ {{ __('support.more_view') }}</button>
                </div>
                <div class="ex-image scrl_x_wrapper">
                    <img src="/images/example/mobile_social_person_section_img_04.svg" alt="social_person_section_img">
                </div>
            </div>
            
            <div class="social_person_info_section social_person_info_section--col_2">
                <div class="in_first_col">
                    <div class="social_person_tit_group">
                        <h4 class="_tit">{{ __('support.asset_holding_expenses') }}</h4>
                    </div>
                    <div class="ex-image">
                        <img src="/images/example/mobile_social_person_section_img_05-1.svg" alt="social_person_section_img">
                    </div>
                </div>
            </div>

            <div class="social_person_info_section social_person_info_section--col_2">
                <div class="in_second_col">
                    <div class="social_person_tit_group">
                        <h4 class="_tit">{{ __('support.trading') }}</h4>
                    </div>
                    <div class="ex-image">
                        <img src="/images/example/mobile_social_person_section_img_05-2.svg" alt="social_person_section_img">
                    </div>
                </div>
            </div>
            
            <div class="social_person_info_section">
                <div class="social_person_tit_group">
                    <h4 class="_tit">{{ __('support.monthly_risk_score') }}</h4>
                    <span class="_now_risk_score"><em>{{ __('support.now_risk_score') }} : </em><b>4 risk</b></span>
                </div>
                <div class="ex-image">
                    <img src="/images/example/mobile_social_person_section_img_06.svg" alt="social_person_section_img">
                </div>
            </div>
        </div>

    </div>

    <div class="m_social_popup social_popup social_view_popup-01">
        <img src="/images/example/mobile_ex_social_popup_03.svg" alt="social popup image">
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