@extends('layouts.app')

@section('content')

<div class="wrapper wrapper--mypage wrapper--mypage01">
    
    <div class="hd-title hd-title--05">
        <h2 class="hd-title__center">마이페이지</h2>
    </div>

    <div class="wrapper--mypage__scroll-area">
        <div class="mypage_list_section first" id="mypage_edit_info" data-name="mypage_edit_info_popup">
            <ul class="mypage_list_group">
                <li class="mypage_list mypage_list_tit"><h3>{{ $userinfo[0]->name }}<span>{{ $userinfo[0]->email }}</span></h3></li>
                <li class="mypage_list"><a href="#" role="button">회원정보 수정하기</a></li>
            </ul>
        </div>
        <div class="mypage_list_section">
            <ul class="mypage_list_group">
                <li class="mypage_list mypage_list_tit"><h3>내 정보</h3></li>
                <li class="mypage_list"><a href="#" role="button" id="mypage_reserva" data-name="mypage_reserva_popup">플래너 예약 내역</a></li>
                <li class="mypage_list"><a href="./review" role="button">리뷰관리</a></li>
                <!--li class="mypage_list mypage_alarm_set_list">
                    <a href="#" role="button" onClick="view_more(this, '.mypage_alarm_group')">알림받기</a>
                    <ul class="mypage_alarm_group">
                        <li class="mypage_alarm_list">
                            <label for="alarm_notice">공지 알림</label>
                            <label class="switch" for="alarm_notice">
                                <input type="checkbox" id="alarm_notice">
                                <span class="slider round"></span>
                            </label>
                        </li>
                        <li class="mypage_alarm_list">
                            <label for="alarm_event">이벤트 알림</label>
                            <label class="switch" for="alarm_event">
                                <input type="checkbox" id="alarm_event">
                                <span class="slider round"></span>
                            </label>
                        </li>
                    </ul>
                </li-->
            </ul>
        </div>
        <div class="mypage_list_section">
            <ul class="mypage_list_group">
                <li class="mypage_list mypage_list_tit"><h3>고객센터</h3></li>
                <li class="mypage_list"><a href="/mypage/notice" role="button">공지사항</a></li>
                <li class="mypage_list"><a href="#" role="button">버전정보<span class="in_version"><em>현재 1.0.0 / </em>최신 1.0.0</span></a></li>
                <li class="mypage_list"><a href="/terms01" role="button">서비스 이용약관</a></li>
                <li class="mypage_list"><a href="/terms02" role="button">개인정보 처리방침</a></li>
            </ul>
        </div>
        <div class="mypage_list_section last">
            <ul class="mypage_list_group">
                <li class="mypage_list"><a href="./withdraw" role="button">회원탈퇴</a></li>
            </ul>
        </div>
        <div class="mypage_list_button">
            <button type="button" class="button" id="btn_logout">로그아웃</button>
        </div>
    </div>

</div>

<div class="popup popup--modal">
    <div class="popup__inner" id="mypage_edit_info_popup">
        <form id="frm_update_usr" method="POST" action="/api/Users/usrinfo" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="put">
        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>회원정보 수정하기</h3>
        <div>
            <div class="info_edit_profile">
                <input type="file" name="thumb" id="user_thumb" style="display:none;">
                <label for="user_thumb" class="info_edit_profile__image" style="background-image: url({{'/storage/'.config('filesystems.user_thumb').$userinfo[0]->user_thumb }});background-size:100% 100%;">
                </label>
            </div>

            <fieldset class="info_edit_fieldset">
                <ul class="info_edit_fieldset__group">
                    <li class="info_edit_fieldset__list">
                        <label for="sign_up_name" class="info_edit_fieldset__list__label">이름</label>
                        <input type="text" name="name" class="info_edit_fieldset__list__input" value="{{ $userinfo[0]->name }}" readonly>
                    </li>
                    <li class="info_edit_fieldset__list">
                        <label for="sign_up_mail" class="info_edit_fieldset__list__label">이메일</label>
                        <input type="email" name="email" class="info_edit_fieldset__list__input" value="{{ $userinfo[0]->email }}" readonly>
                    </li>
                    <li class="info_edit_fieldset__list">
                        <label for="sign_up_pw" class="info_edit_fieldset__list__label">기존 비밀번호</label>
                        <input type="password"  id="o_password" name="ori_password" class="info_edit_fieldset__list__input" placeholder="입력 안하시면 변경되지 않습니다." autocomplete="off">
                    </li>
                    <li class="info_edit_fieldset__list">
                        <label for="sign_up_pw" class="info_edit_fieldset__list__label">새 비밀번호</label>
                        <input type="password"  id="n_password" name="new_password" class="info_edit_fieldset__list__input" placeholder="입력 안하시면 변경되지 않습니다." autocomplete="off">
                    </li>
                    <li class="info_edit_fieldset__list">
                        <label for="sign_up_pw" class="info_edit_fieldset__list__label">비밀번호 확인</label>
                        <input type="password"  id="c_password" class="info_edit_fieldset__list__input" placeholder="입력 안하시면 변경되지 않습니다." autocomplete="off">
                    </li>
                    <li class="info_edit_fieldset__list">
                        <label for="sign_up_pw" class="info_edit_fieldset__list__label">전화번호</label>
                        <input type="tel" class="info_edit_fieldset__list__input" value="{{ $userinfo[0]->user_contact }}" readonly>
                        <!--button type="button" class="button info_edit_fieldset--phone__button">인증번호 받기</button-->
                    </li>
                    <!--li class="info_edit_fieldset__list">
                        <input type="tel" class="info_edit_fieldset__list__input info_edit_fieldset--phone__input" placeholder="인증번호 입력">
                    </li-->
                </ul>
            </fieldset>
            <!--p class="info_edit_fieldset--phone__certify none-input">
                <span class="info_edit_fieldset--phone__certify__count">00:59</span>
                <span class="info_edit_fieldset--phone__certify__caution">내에 인증번호를 입력해주세요.</span>
            </p-->
            <button type="button" class="button info_edit_button" id="btn_update_usr">수정완료</button>
        </form>
        </div>
    </div>

    <div class="popup__inner" id="mypage_reserva_popup">
        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>예약 내역</h3>
        <div class="list_style">
            <h4 class="list_style_tit">내가 예약한 플래너</h4>
            <div class="list_style_sorting">
                <span class="_chck">
                    <input type="radio" name="pay_type" id="all_trip" class="input-style-02" value="all" checked>
                    <label for="all_trip"></label>
                    <label for="all_trip">전체</label>
                </span>
                <span class="_chck">
                    <input type="radio" name="pay_type" id="before_trip" class="input-style-02" value="after">
                    <label for="before_trip"></label>
                    <label for="before_trip">결제완료</label>
                </span>
                <span class="_chck">
                    <input type="radio" name="pay_type" id="after_trip" class="input-style-02" value="before">
                    <label for="after_trip"></label>
                    <label for="after_trip">결제전</label>
                </span>
            </div>
            <ul class="my_reserva_plnr_group" id="reserve_list_ul">
                
            </ul>
        </div>
    </div>

    <div class="popup__inner popup__inner--02" id="mypage_reserva_detail_popup">
        <h3 class="popup__inner__btn-close btn-close-popup--02"><i class="btn-half-circle"></i>최종 결제 확인</h3>
        <div class="payment_final">
            <div class="final-card">
                <h4 class="final-card__title">최종 결제 상품</h4>
                <ul class="final-card__info">
                    <li class="final-card__info__list">
                        <h5 class="final-card__info__label">상품 내용</h5>
                        <span class="final-card__info__line" id="rsrv_detail_info">후쿵오카 직항 4일<br>[시티투어+2일 자유]</span>
                        <span class="final-card__info__line" id="rsrv_detail_price">총 750,000원 (30% 선금)</span>
                    </li>
                    <li class="final-card__info__list">
                        <h5 class="final-card__info__label">최종 금액</h5>
                        <span class="final-card__info__price" id="rsrv_detail_deposit">225,000원</span>
                    </li>
                    <li class="final-card__btns" id="rsrv_detail_state">
                        <button type="button" class="final-card__btn"  onclick="refund_chck();">환불하기</button>
                    </li>
                </ul>
            </div>
            <div class="info_detail">
                <button type="button" class="info_detail_btn" onClick="view_more(this, '.info_detail_view')">내가 입력한 여행정보 확인하기</button>
                <div class="info_detail_view">
                    <div class="trip-info-detail">
                        
                        <h4 class="trip-info-detail__title">필수 입력사항</h4>
                        <ul class="trip-info-detail__ul">
                            <li class="trip-info-detail__list"><em class="trip-info-detail__label">여행지</em><br><span id="rsrv_detail_estm_area">대한민국 제주도</span></li><br>
                            <li class="trip-info-detail__list"><em class="trip-info-detail__label">날짜</em><br><span id="rsrv_detail_estm_period">2019-09-11 수 ~ 2019-09-19 목 7박 8일</span></li><br>
                            <li class="trip-info-detail__list"><em class="trip-info-detail__label" id="rsrv_type_label">인원</em><br><span id="rsrv_detail_estm_group_qtt">성인 2명·아동 0명·유아 0명</span></li><br>
                        </ul>

                    </div>

                    <div class="trip-info-detail" id="rsrv_type_display">
                        
                        <h4 class="trip-info-detail__title">선택 입력사항<span class="trip-info-detail__option" id="rsrv_detail_estm_step4">자유여행</span></h4>
                        <ul class="trip-info-detail__ul" id="rsrv_detail_estm_step5">
                            <li class="trip-info-detail__list"><em class="trip-info-detail__label">어떤 항공 스타일을 원하시나요?</em><br><span>미선택</span></li><br>
                            <li class="trip-info-detail__list"><em class="trip-info-detail__label">어떤 숙박 스타일을 원하시나요?</em><br><span>리조트</span></li><br>
                            <li class="trip-info-detail__list"><em class="trip-info-detail__label">어떤 이동수단을 원하시나요?</em><br><span>차량</span></li><br>
                            <li class="trip-info-detail__list"><em class="trip-info-detail__label">원하시는 식당/레스토랑 스타일은 무엇인가요?</em><br><span>유명맛집</span></li><br>
                        </ul>

                    </div>

                    <div class="trip-info-detail__textarea" id="rsrv_detail_estm_step5_add_div">
                            <textarea cols="30" rows="10" id="rsrv_detail_estm_step5_add" readonly>ㅎㅇㅎㅇㅎㅇㅎㅇㅎㅇㅎㅇㅇㅎㅇㅎㅇㅇ</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="popup__inner popup__inner--02 popup--review-write" id="review_write_popup">

        <h3 class="popup__inner__btn-close btn-close-popup--02"><i class="btn-half-circle"></i>리뷰 쓰기</h3>

        <div class="popup--review-write__scroll-area">
            <form id="frm_revw_insert" method="POST" action="/api/review" ecntype="multipart/form-data">
            <input type="hidden" name="rsrv_id" id="frm_revw_rsrv_id">
            <input type="hidden" name="pln_id" id="frm_revw_pln_id">
            <input type="hidden" name="prd_id" id="frm_revw_prd_id">
            <input type="hidden" name="estm_id" id="frm_revw_estm_id">
            <input type="hidden" name="revw_score" id="frm_revw_score">
            <span class="inline inline--left">별점을 눌러 만족도를 알려주세요.</span>

            <div class="rating-stars__group">
                <div class="rating-stars__stars" id="stars_group">
                    <svg class="star-svg" id="star-01" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25">
                        <path class="star-svg__path star--left"
                            d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z"
                            transform="translate(-0.006 0)" />
                        <path class="star-svg__path star--right" id="star_right_0"
                            d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z"
                            transform="translate(-19.499 0)" />
                    </svg>
                    <svg class="star-svg" id="star-02" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25">
                        <path class="star-svg__path star--left"
                            d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z"
                            transform="translate(-0.006 0)" />
                        <path class="star-svg__path star--right" id="star_right_1"
                            d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z"
                            transform="translate(-19.499 0)" />
                    </svg>
                    <svg class="star-svg" id="star-03" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25">
                        <path class="star-svg__path star--left"
                            d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z"
                            transform="translate(-0.006 0)" />
                        <path class="star-svg__path star--right" id="star_right_2"
                            d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z"
                            transform="translate(-19.499 0)" />
                    </svg>
                    <svg class="star-svg" id="star-04" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25">
                        <path class="star-svg__path star--left"
                            d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z"
                            transform="translate(-0.006 0)" />
                        <path class="star-svg__path star--right" id="star_right_3"
                            d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z"
                            transform="translate(-19.499 0)" />
                    </svg>
                    <svg class="star-svg" id="star-05" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25">
                        <path class="star-svg__path star--left"
                            d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z"
                            transform="translate(-0.006 0)" />
                        <path class="star-svg__path star--right" id="star_right_4"
                            d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z"
                            transform="translate(-19.499 0)" />
                    </svg>
                </div>
                <span class="rating-stars__average"><b id="average_num">0</b>/5</span>
            </div>

            <div class="review-pup__textarea">
                <textarea cols="30" rows="10" placeholder="리뷰 내용을 입력해주세요." name="revw_content" id="revw_content" required></textarea>
            </div>

            </form>
        </div>

        <div class="review-pup__btn">
            <button type="button" class="button button--disable is-active" id="btn_insert_review">등록하기</button>
        </div>

    </div>

</div>
<template id="tplt_reserve_list">
    <li class="my_reserva_plnr_list user-list">
        <div class="my_reserva_plnr_list--hd">
            <figure class="user-list__thum" name="rsrv_pln_thumb" style="background-image: url(img/example/profile-jq.jpg);"></figure>
            <h5 class="user-list__name">
                <b name="rsrv_pln_name">강슬기</b>
                <span class="user-list__type" name="rsrv_pln_type">개인</span>
                <span class="user-list__type" name="rsrv_state">()</span>
            </h5>
            <p class="user-list__msg" name="rsrv_pln_desc">서울 토박이 25년차, 서울에서만 놀고먹고, 서울에서만 놀고먹고, 서울에서만 놀고먹고, 서울에서만 놀고먹고</p>
        </div>
        <div class="my_reserva_plnr_list--bt">
            <div class="my_reserva_label">
                <h6 class="_tit">결제완료 금액</h6>
                <span class="_price" name="rsrv_deposit">225,000원</span>
                <button type="button" name="rsrv_detail_btn" class="_btn btn_reserva_detail">자세히보기</button>
            </div>
            <!-- 결제완료일 때는 리뷰쓰러가기 버튼 생겨야함 -->
            <!-- end 결제완료일 때는 리뷰쓰러가기 버튼 생겨야함 -->
        </div>
    </li>
</template>
<template id="tplt_prd_estm_picture">
    <div class="review-pup__pictures">
        <input type="file" id="input_review_pic" name="revw_images[]" class="none-input" multiple>
        <ul class="review__pictures-group" id="review_multiple_pic">
            <li class="review__pictures-list review__pictures-list--file">
                <label for="input_review_pic"></label>
                <span class="review__pictures-list__span">+ 사진추가</span>
            </li>
        </ul>
    </div>
</template>
@include('nav.nav_user')

@endsection

@section('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
$('#test').click(function(){
	
});
$(function(){
    //리뷰 작성
    $('#btn_insert_review').click(function(){
        $('#frm_revw_insert').submit();
    });
    $('#frm_revw_insert').ajaxForm({
        dataType : "json",
        beforeSubmit: function() {
            //동적추가된 폼들 검증추가
            if($('#frm_revw_score').val() == 0){
                dialog.alert({
                    title:'알림',  
                    message: '별점을 설정해주세요.',
                    button: "확인"
                });
                return false;
            }else{
                return $('#frm_revw_insert').valid();
            }
        },
        success: function(data) {
            console.log(data);
            if(data.state == 1){
                dialog.alert({
                    title:'알림',  
                    message: '리뷰작성이 완료되었습니다. 리뷰관리페이지에서 확인해주세요.',
                    button: "확인"
                });
                
                var rsrv_id = $('#frm_revw_rsrv_id').val();
                $('.my_reserva_review_btn[data-rsrv='+rsrv_id+']').remove();
                $('.btn-close-popup--02').parents('.popup__inner--02').removeClass('is-active');
            }else{
                dialog.alert({
                    title:'오류',  
                    message: data.msg,
                    button: "확인"
                });
            }
        }    
    });
    
    //플래너 예약내역 정렬
    $("[name=pay_type]").change(function(){
        if($(this).val() == 'all'){
            filter_state = 'all';
            $('#reserve_list_ul').empty();
            loadRsrv(0);
        }else if($(this).val() == 'before'){
            filter_state = 'before';
            $('#reserve_list_ul').empty();
            loadRsrv(0);
        }else if($(this).val() == 'after'){
            filter_state = 'after';
            $('#reserve_list_ul').empty();
            loadRsrv(0);
        }else{
            filter_state = 'all';
            $('#reserve_list_ul').empty();
            loadRsrv(0);
        }
    });
    //플래너 예약 내역
    $('#mypage_reserva').click(function(){
        loadRsrv(rsrvStart);
    });
    $('#reserve_list_ul').bind('scroll',function(){
        if($(this).scrollTop() + $(this).innerHeight() + 0.5 == $(this)[0].scrollHeight){
            loadRsrv(rsrvStart);
            
        }
    });
    //팝업 in popup~
    
    //end
    //로그아웃
    $('#btn_logout').click(function(){
        $.ajax({
            url: "/api/logout",
            method: "POST",
            dataType: "json",
            async:false
        })
        .done(function(data) {
            if(data){
                dialog.alert({
                    title:'로그아웃',
                    message: '로그아웃 하셨습니다.',
                    button: "확인",
                    callback: function(value){
                        location.href='/login';
                    }
                });
            }
        })
        .fail(function(xhr, status, errorThrown) {
            console.log(xhr);
        }) // 
        .always(function(xhr, status) {});
    });

    //유저 정보수정
    $('#user_thumb').change(function(){
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.info_edit_profile__image').css('background-image', 'url('+e.target.result+')');
                $('.info_edit_profile__image').css('background-size', '100% 100%');
            }
            reader.readAsDataURL(this.files[0]);
        }else{
            $('.info_edit_profile__image').css('background-image', 'url(no-data)');
            $('#user_thumb').val('');
        }
        
    });
    $('#btn_update_usr').click(function(){
        $('#frm_update_usr').submit();
    });
    $('#frm_update_usr').ajaxForm({
        dataType : "json",
        beforeSubmit: function() {
            //동적추가된 폼들 검증추가
            console.log();
            if($('#o_password').val() != ''){
                if($('#n_password').val() == ''){
                    dialog.alert({
                        title:'오류',  
                        message: '비밀번호 변경 시 새 비밀번호를 넣어주세요.',
                        button: "확인"
                    });
                    return false;
                }else if($('#c_password').val() == ''){
                    dialog.alert({
                        title:'오류',  
                        message: '비밀번호 변경 시 확인 비밀번호를 넣어주세요.',
                        button: "확인"
                    });
                    return false;
                }else if($('#n_password').val() != $('#c_password').val()){
                    dialog.alert({
                        title:'오류',  
                        message: '새 비밀번호와 확인 비밀번호가 일치하지 않습니다.',
                        button: "확인"
                    });
                    return false;
                }
            }else{
                return true;
            }
        },
        success: function(data) {
            console.log(data);
            if(data.state == 1){
                dialog.alert({
                    title:'알림',  
                    message: '수정이 완료되었습니다.',
                    button: "확인"
                });
                $('#o_password').val('');
                $('#n_password').val('');
                $('#c_password').val('');
                $(this).parents('.popup__inner').removeClass('is-active');
                $('.popup--modal').delay(200).fadeOut();
            }else{
                dialog.alert({
                    title:'오류',  
                    message: data.msg,
                    button: "확인"
                });
            }
        }    
    });
    $("#stars_group .star-svg").each(function(index) {
        $(this).attr("data-num", index);
    });

    $("#stars_group .star-svg").children("path").click(function(event) {
        $(event.target).parent().children("path").removeClass("on");
        $(event.target).parent().nextAll(".star-svg").children("path").removeClass("on");
        $(event.target).addClass("on").parent(".star-svg").prevAll(".star-svg").children("path").addClass("on");
        $(event.target).prev(".star--left").addClass("on");

        var index_number = $(event.target).parent().attr("data-num");
        var average_value = Number(index_number);

        var star_left_on = $(".star--left").hasClass("on");
        var star_right_on = $(event.target).hasClass("star--right");
        var average = $("#average_num");

        if (star_left_on && star_right_on) {
            average.html(average_value + 1);
            $('#frm_revw_score').val(average_value + 1);
        } else {
            average.html(average_value + "." + 5);
            $('#frm_revw_score').val(average_value + "." + 5);
        }
    }); //end
});
//누르면 내용 나오기 함수 (html 구조 지켜야함)
function view_more(name, contents){

    var thisCon = $(name).next(contents).css('display');

    $(name).next(contents).stop().slideDown(200);

    if( thisCon == 'block' ){

        $(name).next(contents).stop().slideUp(200);

    }

}
//end 


    




// 환불 확인 팝업
function refund_chck(id){
    dialog.confirm({
        title:'',  
        message: '<p class="single-msg">선택하신 상품을<br>환불처리하시겠습니까?</p>',
        button: "네",
        cancel: "아니오",
        callback: function(value){
            if(value){
                $.ajax({
                    url: "/api/reserve/state",
                    data: {
                        rsrv_id: id,
                        state: 2
                    },
                    method: "PUT",
                    dataType: "json"
                })
                .done(function(data) {
                    if(data){
                        setTimeout(function(){
                            dialog.alert({
                                title:'환불',
                                message: '해당 상품을 환불신청하셨습니다.',
                                button: "확인",
                                callback: function(value){
                                    var btn_str = '<button type="button" class="final-card__btn">환불요청중</button>';
                                    $('#rsrv_detail_state').html(btn_str);
                                }
                            });
                        },500);
                        
                    }
                })
                .fail(function(xhr, status, errorThrown) {
                    console.log(xhr);
                }) // 
                .always(function(xhr, status) {});
            }
        }
    });
}
function cancel_chck(id){
    dialog.confirm({
        title:'',  
        message: '<p class="single-msg">선택하신 상품을<br>취소처리하시겠습니까?</p>',
        button: "네",
        cancel: "아니오",
        callback: function(value){
            if(value){
                $.ajax({
                    url: "/api/reserve/state",
                    data: {
                        rsrv_id: id,
                        state: 6
                    },
                    method: "PUT",
                    dataType: "json"
                })
                .done(function(data) {
                    if(data){
                        setTimeout(function(){
                            dialog.alert({
                                title:'취소',
                                message: '해당 상품을 취소하셨습니다.',
                                button: "확인",
                                callback: function(value){
                                    var btn_str = '<button type="button" class="final-card__btn">취소완료</button>';
                                    $('#rsrv_detail_state').html(btn_str);
                                }
                            }); 
                        },500);
                    }
                })
                .fail(function(xhr, status, errorThrown) {
                    console.log(xhr);
                }) // 
                .always(function(xhr, status) {});
            }
        }
    });
}
// end
var rsrvStart = 0;
var filter_state = 'all';
function loadRsrv(_start) {
    $.ajax({
        url: "/api/reserve/list_user",
        data: {
            offset: _start,
            filter: filter_state
        },
        method: "GET",
        dataType: "json",
        async:false
    })
    .done(function(res) {
        if (res.state == 1) {
            
            for (var i = 0; i < res.query.length; i++) {
                var tplt_clone = $($('#tplt_reserve_list').html());
                var rsrv = res.query[i];
                if(rsrv.prd_schedule == null){
                    var DateSplit = rsrv.estm_period.split(' ');
                }else{
                    var DateSplit = rsrv.prd_schedule.split(' ');
                }
                var StartDate = new Date(DateSplit[0]);
                var EndDate = new Date(DateSplit[3]);
                var NowDate = new Date();
                if(rsrv.state == 0){
                    tplt_clone.addClass('pay_before');
                    tplt_clone.find('[name=rsrv_state]').html('(미결제)');
                }else if(rsrv.state == 1){
                    tplt_clone.addClass('pay_after');
                    if(NowDate < StartDate){
                        tplt_clone.find('[name=rsrv_state]').html('(여행전)');
                    }else if(NowDate < EndDate){
                        tplt_clone.find('[name=rsrv_state]').html('(여행중)');
                    }else{
                        if(rsrv.is_revw == 0){
                        tplt_clone.find('.my_reserva_plnr_list--bt').append('<button type="button" data-rsrv="'+rsrv.rsrv_id+'" data-pln="'+rsrv.pln_id+'"  data-prd="'+rsrv.prd_id+'"  data-estm="'+rsrv.estm_id+'" class="button my_reserva_review_btn">리뷰 쓰러가기</button>');
                        }
                        tplt_clone.find('[name=rsrv_state]').html('(여행완료)');
                    }
                }else if(rsrv.state == 2){
                    tplt_clone.addClass('pay_after');
                    tplt_clone.find('[name=rsrv_state]').html('(환불요청)');
                }else if(rsrv.state == 3){
                    tplt_clone.addClass('pay_after');
                    tplt_clone.find('[name=rsrv_state]').html('(환불완료)');
                }else if(rsrv.state == 4){
                    tplt_clone.addClass('pay_after');
                    if(NowDate < StartDate){
                        tplt_clone.find('[name=rsrv_state]').html('(여행전)');
                    }else if(NowDate < EndDate){
                        tplt_clone.find('[name=rsrv_state]').html('(여행중)');
                    }else{
                        if(rsrv.is_revw == 0){
                            tplt_clone.find('.my_reserva_plnr_list--bt').append('<button type="button" data-rsrv="'+rsrv.rsrv_id+'" data-pln="'+rsrv.pln_id+'"  data-prd="'+rsrv.prd_id+'"  data-estm="'+rsrv.estm_id+'" class="button my_reserva_review_btn">리뷰 쓰러가기</button>');
                        }
                        tplt_clone.find('[name=rsrv_state]').html('(여행완료)');
                    }
                }else if(rsrv.state == 5){
                    tplt_clone.addClass('pay_after');
                    if(NowDate < StartDate){
                        tplt_clone.find('[name=rsrv_state]').html('(여행전)');
                    }else if(NowDate < EndDate){
                        tplt_clone.find('[name=rsrv_state]').html('(여행중)');
                    }else{
                        if(rsrv.is_revw == 0){
                            tplt_clone.find('.my_reserva_plnr_list--bt').append('<button type="button" data-rsrv="'+rsrv.rsrv_id+'" data-pln="'+rsrv.pln_id+'"  data-prd="'+rsrv.prd_id+'"  data-estm="'+rsrv.estm_id+'" class="button my_reserva_review_btn">리뷰 쓰러가기</button>');
                        }
                        tplt_clone.find('[name=rsrv_state]').html('(여행완료)');
                    }
                }else{
                    tplt_clone.addClass('pay_before');
                    tplt_clone.find('[name=rsrv_state]').html('(결제취소)');
                }

                tplt_clone.children('[name=rsrv_pln_thumb]').css({
                    "background": "url('/storage/fdata/product/slides/" + rsrv.pln_thumb + " ')", 
                    "background-size":"100% 100%"
                });

                tplt_clone.find('[name=rsrv_pln_name]').html(rsrv.pln_name);
                if(rsrv.pln_type == 0){
                    tplt_clone.find('[name=rsrv_pln_type]').html('개인');
                }else{
                    tplt_clone.find('[name=rsrv_pln_type]').html('업체');
                }
                
                tplt_clone.find('[name=rsrv_pln_desc]').html(rsrv.pln_desc);
                tplt_clone.find('[name=rsrv_deposit]').html(numberWithCommas(rsrv.rsrv_price)+'원');

                tplt_clone.find('[name=rsrv_detail_btn]').data("id",rsrv.rsrv_id);
                

                $('#reserve_list_ul').append(tplt_clone);
            }
            rsrvStart += res.query.length;
            //예약한 리스트 End
            //리뷰쓰기 버튼클릭
            $('.my_reserva_review_btn').unbind();
            $('.my_reserva_review_btn').bind('click',function(){
                $('.popup--modal').fadeIn();
                $('#review_write_popup').addClass('is-active');

                $('.star-svg__path').removeClass('on');
                $('#average_num').text(0);
                $('#revw_score').val(0);
                $('#revw_content').val('');

                $('#frm_revw_rsrv_id').val($(this).data("rsrv"));
                $('#frm_revw_pln_id').val($(this).data("pln"));
                $('#frm_revw_prd_id').val($(this).data("prd"));
                $('#frm_revw_estm_id').val($(this).data("estm"));

                var prd_estm_pic = $($('#tplt_prd_estm_picture').html());

                if($(this).data("estm") == null){
                    $('.review-pup__pictures').remove();
                }else{
                    $('.review-pup__pictures').remove();
                    $('#frm_revw_insert').append(prd_estm_pic);
                    $('#input_review_pic').unbind();
                    $('#input_review_pic').bind('change',function(){
                        $('.review__pictures-list').not('.review__pictures-list--file').remove();
                        for (var i = 0; i < this.files.length; i++) {
                            var fr = new FileReader();
                            fr.onload = function(e) {
                                $('#review_multiple_pic').append('<li class="review__pictures-list" style="background-image: url('+e.target.result+');background-size:100% 100%"></li>');
                            }
                            fr.readAsDataURL(this.files[i]);
                        }
                    });
                }
                
            })
            
            //예약한 리스트 클릭시 상세정보 보여줌
            $('.btn_reserva_detail').unbind();
            $('.btn_reserva_detail').bind('click',function(){
                var rsrv_id = $(this).data("id");
                $.ajax({
                    url: "/api/reserve/user_detail",
                    data: {
                        rsrv_id: rsrv_id
                    },
                    method: "GET",
                    dataType: "json",
                    async:false
                })
                .done(function(res) {
                    console.log(res);
                    if (res.state == 1) {
                        var rsrv_info = res.query[0];
                        console.log(rsrv_info);
                        
                        
                        $('#rsrv_detail_price').html('총 '+numberWithCommas(rsrv_info.rsrv_price)+'원');
                        $('#rsrv_detail_deposit').html(numberWithCommas(rsrv_info.rsrv_price)+'원');
                        
                        if(rsrv_info.state == 0){
                            var btn_str = '<button type="button" class="final-card__btn" onclick="location.href=\'/pay?rsrv_id='+rsrv_info.rsrv_id+'\'">결제하기</button>';
                            btn_str += '<button type="button" class="final-card__btn" onclick="cancel_chck('+rsrv_info.rsrv_id+');">취소하기</button>';
                            $('#rsrv_detail_state').html(btn_str);
                        }else if( rsrv_info.state == 1){
                            var btn_str = '<button type="button" class="final-card__btn" onclick="refund_chck('+rsrv_info.rsrv_id+');">환불하기</button>';
                            $('#rsrv_detail_state').html(btn_str);
                        }else if( rsrv_info.state == 2){
                            var btn_str = '<button type="button" class="final-card__btn">환불요청중</button>';
                            $('#rsrv_detail_state').html(btn_str);
                        }else if( rsrv_info.state == 3){
                            var btn_str = '<button type="button" class="final-card__btn">환불완료</button>';
                            $('#rsrv_detail_state').html(btn_str);
                        }else if( rsrv_info.state == 4){
                            var btn_str = '<button type="button" class="final-card__btn">결제완료</button>';
                            $('#rsrv_detail_state').html(btn_str);
                        }else if( rsrv_info.state == 5){
                            var btn_str = '<button type="button" class="final-card__btn">결제완료</button>';
                            $('#rsrv_detail_state').html(btn_str);
                        }else if( rsrv_info.state == 6){
                            var btn_str = '<button type="button" class="final-card__btn">취소완료</button>';
                            $('#rsrv_detail_state').html(btn_str);
                        }
                        if(rsrv_info.estm_area != null){
                            $('#rsrv_detail_info').html(rsrv_info.estm_area+'<br>['+rsrv_info.estm_step4+']');
                            $('#rsrv_detail_estm_area').html(rsrv_info.estm_area);
                            $('#rsrv_detail_estm_period').html(rsrv_info.estm_period);
                            $('#rsrv_type_label').text('인원');
                            $('#rsrv_detail_estm_group_qtt').html(rsrv_info.estm_group_qtt);
                            $('#rsrv_detail_estm_step4').html(rsrv_info.estm_step4);
                            
                            $('#rsrv_type_display').css("display","block");
                            $('#rsrv_detail_estm_step5').empty();
                            var estm_step5 = JSON.parse(rsrv_info.estm_step5);
                            $.each(estm_step5 ,function(index, item){
                                var step5_list = '<li class="trip-info-detail__list"><em class="trip-info-detail__label">'+item.estm_group+'</em><br><span>'+item.estm_title+'</span></li><br>';
                                $('#rsrv_detail_estm_step5').append(step5_list);
                            });

                            if(rsrv_info.estm_step5_add == null){
                                $('#rsrv_detail_estm_step5_add_div').css("display","none");
                            }else{
                                $('#rsrv_detail_estm_step5_add_div').css("display","block");
                            }
                            
                            $('#rsrv_detail_estm_step5_add').val(rsrv_info.estm_step5_add);
                        }else{
                            $('#rsrv_detail_info').html(rsrv_info.prd_title+'<br>['+rsrv_info.prd_subtitle+']');
                            $('#rsrv_detail_estm_area').html(rsrv_info.prd_course);
                            $('#rsrv_detail_estm_period').html(rsrv_info.prd_schedule);
                            $('#rsrv_type_label').text('만나는 장소 및 시간');
                            $('#rsrv_detail_estm_group_qtt').html(rsrv_info.prd_place_time);
                            $('#rsrv_detail_estm_step4').html(rsrv_info.prd_subtitle);
                            
                            $('#rsrv_type_display').css("display","none");
                            $('#rsrv_detail_estm_step5_add_div').css("display","none");
                        }
                        
                        
                        
                        $('.popup--modal').fadeIn();
                        $('#mypage_reserva_detail_popup').addClass('is-active');
                    } else {
                        //회신오류
                    }
                })
                .fail(function(xhr, status, errorThrown) {
                    console.log(xhr);
                }) // 
                .always(function(xhr, status) {});
                

            });

        } else {
            //회신오류
        }
    })
    .fail(function(xhr, status, errorThrown) {
        console.log(xhr);
    }) // 
    .always(function(xhr, status) {});
}

function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}


</script>
@endsection