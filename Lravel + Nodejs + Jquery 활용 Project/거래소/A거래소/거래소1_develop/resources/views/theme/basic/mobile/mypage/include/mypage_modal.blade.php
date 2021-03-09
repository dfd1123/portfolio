<!-- 계좌인증 팝업창 -->
<div class="popup_page popup_page_account" id="popup_page_account">
    <div class="mypage_sc_popup_hd">
        <h3 class="mypage_sc_popup_title"> {{ __('myp.pop_certi_tit') }}</h3>
        <p class="mypage_sc_popup_ment">{!! __('myp.m_pop_certi_sub') !!}</p>
    </div>

    <div class="mypage_sc_popup_con">
        <div class="modal_content">
            <div class="bank_sch_checkbox">
                <label class="certify_label mt-3 mb-2">{{ __('myp.bank_name') }}</label>
                <label for="m_pop_bank" id="check_bank" class="bank_label"><em>{{ __('myp.select') }}</em>
                    <span class="bank_coin_name"></span>
                </label>
                <input type="checkbox" name="pop_bank_check" id="m_pop_bank" style="display: none;"  placeholder="{{ __('myp.write_account') }}">
                <div class="bank_view">
                    <ul>
                        <li data-code="002">산업</li>
                        <li data-code="003">기업</li>
                        <li data-code="004">국민</li>
                        <li data-code="007">수협</li>
                        <li data-code="011">농협</li>
                        <li data-code="012">농협중앙회</li>
                        <li data-code="020">우리</li>
                        <li data-code="023">SC제일</li>
                        <li data-code="027">한국씨티</li>
                        <li data-code="031">대구</li>
                        <li data-code="032">부산</li>
                        <li data-code="034">광주</li>
                        <li data-code="035">제주</li>
                        <li data-code="037">전북</li>
                        <li data-code="039">경남</li>
                        <li data-code="045">새마을금고중앙회</li>
                        <li data-code="048">신협중앙회</li>
                        <li data-code="050">상호저축</li>
                        <li data-code="054">HSBC</li>
                        <li data-code="055">도이치</li>
                        <li data-code="057">제이피모간체이스</li>
                        <li data-code="060">BOA</li>
                        <li data-code="062">중국공상</li>
                        <li data-code="064">산림조합중앙회</li>
                        <li data-code="071">우체국</li>
                        <li data-code="081">KEB하나</li>
                        <li data-code="088">신한은행</li>
                        <li data-code="089">K뱅크</li>
                        <li data-code="090">카카오뱅크</li>
                    </ul>
                    <input type="hidden" id="check_bank_name">
                </div>
            </div>
            <div class="act_number_box">
                <label class="certify_label">{{ __('myp.account_number') }}</label>
                <input id="account_num" type="number" class="pl-120px certifi_form_input auth_input form-control mb-2" name="mobile_certify_code" value="" placeholder="{{ __('myp.write_account') }}">
            </div>

            <!-- 계좌 인증번호 전송 전 버튼 -->
            <button type="button" id="account_certify_btn" class="btn_style mt-3 btn_sending">{{ __('myp.account_certi_sending') }}</button>

            <!-- 계좌 인증번호 전송 후 버튼 -->
            <!-- <button class="btn_style mt-3 btn_certi_before">{{ __('myp.account_certi_sending') }}</button> -->

            <div id="account_confirm_form" class="hide">
                <span class="pop_division mt-4"></span>
                <div class="check_form">
                    <p class="pop_checking_sub mt-4">
                        {!! __('myp.account_certi_sub') !!}
                    </p>
                    <div class="act_number_box">
                        <label class="certify_label mt-4">{{ __('myp.write_certi_tit') }}</label>
                        <input id="act_number_code" type="number" class="pl-120px certifi_form_input auth_input form-control mb-3 certifi_line" name="mobile_certify_code" value="" placeholder="{{ __('myp.certi_number') }}">
                        <button type="button" id="account_certify_confirm_btn" class="file_btn_style number_space">{{ __('myp.checking_number') }}</button>
                    </div>
                    
                    <span class="account_checking_division"></span>
                </div> 
            </div>
        </div>
    </div>
    <div class="cancel_btn" onClick="mypage_popup_close(this)">
        <span></span>
    </div>
</div>

<template id="account_in_popup_content">
    <div class="mypage_sc_popup_hd">
        <h3 class="mypage_sc_popup_title"> {{ __('myp.pop_certi_tit') }}</h3>
        <p class="mypage_sc_popup_ment">{!! __('myp.m_pop_certi_sub') !!}</p>
    </div>

    <div class="mypage_sc_popup_con">
        <div class="modal_content">
            <div class="bank_sch_checkbox">
                <label class="certify_label mt-3 mb-2">{{ __('myp.bank_name') }}</label>
                <label for="m_pop_bank" id="check_bank" class="bank_label"><em>{{ __('myp.select') }}</em>
                    <span class="bank_coin_name"></span>
                </label>
                <input type="checkbox" name="pop_bank_check" id="m_pop_bank" style="display: none;"  placeholder="{{ __('myp.write_account') }}">
                <div class="bank_view">
                    <ul>
                        <li data-code="002">산업</li>
                        <li data-code="003">기업</li>
                        <li data-code="004">국민</li>
                        <li data-code="007">수협</li>
                        <li data-code="011">농협</li>
                        <li data-code="012">농협중앙회</li>
                        <li data-code="020">우리</li>
                        <li data-code="023">SC제일</li>
                        <li data-code="027">한국씨티</li>
                        <li data-code="031">대구</li>
                        <li data-code="032">부산</li>
                        <li data-code="034">광주</li>
                        <li data-code="035">제주</li>
                        <li data-code="037">전북</li>
                        <li data-code="039">경남</li>
                        <li data-code="045">새마을금고중앙회</li>
                        <li data-code="048">신협중앙회</li>
                        <li data-code="050">상호저축</li>
                        <li data-code="054">HSBC</li>
                        <li data-code="055">도이치</li>
                        <li data-code="057">제이피모간체이스</li>
                        <li data-code="060">BOA</li>
                        <li data-code="062">중국공상</li>
                        <li data-code="064">산림조합중앙회</li>
                        <li data-code="071">우체국</li>
                        <li data-code="081">KEB하나</li>
                        <li data-code="088">신한은행</li>
                        <li data-code="089">K뱅크</li>
                        <li data-code="090">카카오뱅크</li>
                    </ul>
                    <input type="hidden" id="check_bank_name">
                </div>
            </div>
            <div class="act_number_box">
                <label class="certify_label">{{ __('myp.account_number') }}</label>
                <input id="account_num" type="number" class="pl-120px certifi_form_input auth_input form-control mb-2" name="mobile_certify_code" value="" placeholder="{{ __('myp.write_account') }}">
            </div>

            <!-- 계좌 인증번호 전송 전 버튼 -->
            <button type="button" id="account_certify_btn" class="btn_style mt-3 btn_sending">{{ __('myp.account_certi_sending') }}</button>

            <!-- 계좌 인증번호 전송 후 버튼 -->
            <!-- <button class="btn_style mt-3 btn_certi_before">{{ __('myp.account_certi_sending') }}</button> -->

            <div id="account_confirm_form" class="hide">
                <span class="pop_division mt-4"></span>
                <div class="check_form">
                    <p class="pop_checking_sub mt-4">
                        {!! __('myp.account_certi_sub') !!}
                    </p>
                    <div class="act_number_box">
                        <label class="certify_label mt-4">{{ __('myp.write_certi_tit') }}</label>
                        <input id="act_number_code" type="number" class="pl-120px certifi_form_input auth_input form-control mb-3 certifi_line" name="mobile_certify_code" value="" placeholder="{{ __('myp.certi_number') }}">
                        <button type="button" id="account_certify_confirm_btn" class="file_btn_style number_space">{{ __('myp.checking_number') }}</button>
                    </div>
                    
                    <span class="account_checking_division"></span>
                </div> 
            </div>
        </div>
    </div>
    <div class="cancel_btn" onClick="mypage_popup_close(this)">
        <span></span>
    </div>
</template>