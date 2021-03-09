@extends('theme.basic.pc.layouts.app')

@section('content')
<div id="mypage_page_wrapper">
    <div class="topic_mypage">
        <h2 class="mypage_topic_txt">마이페이지</h2>
    </div>
    <div class="marketing_agree_cont">
        <div class="container">
        
        <ul class="tab_list">
            <li class="tab-link current" data-tab="tab-1">알림설정</li>
            <li class="tab-link" data-tab="tab-2">보안인증</li>
            <li class="tab-link" data-tab="tab-3">락리워드</li>
            <li class="tab-link" data-tab="tab-3">회원정보 변경</li>
            
        </ul>
        
        <div id="tab-1" class="tab-content current">
            <div class="mypage_alarm">
                <p class="alarm_notice">알림을 받고싶은 항목에 체크 후 변경합니다.</p>
                    <div class="event_box">
                        <span class="event_txt">이벤트/마케팅 알림</span>
                        <div class="agree_check_box">
                        <input type="radio" id="agreement" name="tab"/> 
                        <label class="check_box_space" for="agreement">이메일</label>
                        <input type="radio" id="agreement_SMS" name="tab"/> 
                        <label class="check_box_space" for="agreement_SMS">SMS</label>  
                        </div>
                    </div>
                    <div class="event_box">
                        <div class="withdraw_box">
                        <span class="event_txt">출금 알림</span>
                        <div class="agree_check_box">
                            <input type="radio" id="agreement_email_2" name="tab"/> 
                            <label class="check_box_space" for="agreement_email_2">이메일</label>
                            <input type="radio" id="agreement_SMS_2" name="tab"/> 
                            <label class="check_box_space" for="agreement_SMS_2">SMS</label>  
                        </div>  
                        </div>                     
                    </div>
                    <a href="#modalLayer" class="btn_alarm_setting_btn  modal_link">간단한 모달 창 만들기</a>
                    <div id="modal_layer">
                    <div class="modal_content">
                        <img src="images/icon_modal_complete.png" alt="">
                        <p class="class_set_end_txt">
                            알림설정 변경 완료
                        </p>
                        <span class="set_end_sub_txt"></span>
                        <button type="set_end_confirm_button">확인</button>
                    </div>
                    </div>
                    </div>
            </div>
            <div id="tab-2" class="tab-content">
            ---- ---- ★------ ---- ---- ---- ---- ---- ---- -------- ---- ---- ---- ---- ---- ---- -------- ---- ---- ---- ★-- ---- ---- ------★ ---- ---- ---- ---- ---- ---- -------- ---- ---- ---- ---- ---- ---- ★------ ---- ---- ---- ----
            </div>
            <div id="tab-3" class="tab-content">
            ---- ★-- -------- ---- ---- ---- -★- ---- ---- -------- ---- -★- ---- ---- ---- ---- -------- ---- ---- ---- ---- ---- --★ -------- ★-- ---- ---- ---- ---- ---- -------- ---- ---- --★ ---- ---- ---- -------- ---- ---- ---- --★
            </div>
            
            </div>

    </div>
    </div>

@endsection