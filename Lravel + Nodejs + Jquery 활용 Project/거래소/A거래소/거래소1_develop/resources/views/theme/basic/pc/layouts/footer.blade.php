
<footer class="footer">
    <div class="footer_inner">
        <div class="footer_inner_address">
            <p class="footer_logo">
                <a href="#">
                    <img src="/images/header_logo(wh).png" alt="SPOWIDE 로고" style="height:50px;">
                </a>
                <p class="footer_info">
                    {{$company->company}} | 대표 : {{$company->ceo}}<br />
                    {{$company->address}} <br />
                    {{$company->address_detail}}<br />
                    사업자등록번호 : {{$company->business_num}}<br />
                    
                </p>
            </p>
        </div>
        <div class="footer_inner_cs">
            <h3 class="footer_cs_title">{{__('head.cs')}}</h3>
            <a class="footer_cs_tel" href="tel:1588-5808">1588-5808</a>
            <span class="footer_info">{{__('head.cs_email')}}</span>
            <span class="footer_info">{{__('head.cs_time')}}</span>
            <span class="footer_info">{{__('head.cs_lunch_time')}}</span>
            <span class="footer_info">{{__('head.cs_off_day')}}</span>
        </div>
        <div class="footer_inner_menu">
            <ul>
            <li><a href="{{ route('cs_etc.show', 'intro') }}" target="_self">{{__('head.corp_info')}}</a></li>
            <li><a href="{{ route('cs_etc.show', 'privacy_guide') }}" target="_self">{{__('head.indiv_info')}}</a></li>
            <li><a href="{{ route('cs_etc.show', 'service_guide') }}" target="_self">{{__('head.terms_of_service')}}</a></li>
            <!-- <li><a href="#" target="_self">{{__('head.sitemap')}}</a></li> -->
            </ul>
        </div>
        
        <div class="footer_inner_lang">
            <ul class="footer_inner_world">
                <li class="footer_qna hide">{{__('head.lang_list')}}</li>
                <li class="footer_qna hide">
                    <div class="footer_inner_lang">
                        <input type="checkbox" id="lang_check">
                        <label for="lang_check" class="lang_label">
                            <img class="lang_flag" src="/images/icon_lang01_KR.svg" alt="">
                            {{__('head.korean')}}</label>
                        <div class="lang_list">
                            <ul class="dropdown_list">
                            <li><a href="#">
                                <img class="lang_flag" src="/images/icon_lang01_KR.svg" alt="">
                                {{__('head.korean')}}</a>
                            </li>
                            <li>
                                <a href="#">
                                <img class="lang_flag" src="/images/icon_lang02_ENG.svg" alt="">
                                English</a>
                            </li>
                            <li>
                                <a href="#">
                                <img class="lang_flag" src="/images/icon_lang03_JP.svg" alt="">
                                日本語</a>
                            </li>
                            <li>
                                <a href="#">
                                <img class="lang_flag" src="/images/icon_lang04_CH.svg" alt="">
                                简体中文</a>
                            </li>
                            <li>
                                <a href="#">
                                <img class="lang_flag" src="/images/icon_lang05_SP.svg" alt="">
                                Español</a>
                            </li>
                            <li>
                                <a href="#">
                                <img class="lang_flag" src="/images/icon_lang06_ID.svg" alt="">
                                हिंदी</a>
                            </li>
                            </ul>
                        </div> 
                    </div>

                </li>
                    <li class="footer_qna">{{__('head.ask')}}</li>
                    <li class="footer_qna">
                        <!--<a href="#" class="sns_kakao"><img src="/images/sns_1.png" alt=""></a>-->
                        <a href="https://t.me/Spowide" target="_blank"><img src="/images/sns_2.png" alt=""></a>  
                    </li>
        </ul>
    </div>


        

    </div>
    <div class="footer_inner_copyright">
    <p class="spo_copyright">{{__('head.ticket')}}</p>
    </div>
</footer>