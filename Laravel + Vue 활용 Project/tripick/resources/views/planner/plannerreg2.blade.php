@extends('layouts.app')

@section('content')

<div class="wrapper wrapper--planner">
                    
    <div class="user-msg-alarm">
        <p class="user-msg-alarm__text">영역을 터치하여 정보를 입력하세요.</p>
        <span class="user-msg-alarm__btn" id="alarm_msg_close"><b class="none">닫기버튼</b></span>
    </div>
    
    <div class="hd-title hd-title--01">
        <button type="button" onClick="history.back()" class="hd-title__left-btn hd-title__left-btn--prev"><span class="none">이전버튼</span></button>
        <h2 class="hd-title__center">플래너 신청하기</h2>
    </div>
    
    <div class="wrapper--planner__scroll-area">

        <div class="plnr">
            
            <div class="plnr-info">
                <span class="plnr-info__type is-set" id="check_plnr_type" data-name="check_plnr_type_popup">개인/업체 중 택1</span>
                <p class="plnr-info__name">
                    <span class="plnr-info__name is-set" id="check_plnrname" data-name="check_plnrname_popup">플래너명</span>
                </p>
                <ul class="plnr-info__score">
                    <li class="plnr-info__score__list">
                        <span class="plnr-info__num">0</span>
                        <em class="plnr-info__label">가이드횟수</em>
                    </li>
                    <li class="plnr-info__score__list">
                        <span class="plnr-info__num">0</span>
                        <em class="plnr-info__label">전체리뷰</em>
                    </li>
                    <li class="plnr-info__score__list">
                        <span class="rating plnr-info__num">0</span>
                        <em class="plnr-info__label">평균 별점</em>
                    </li>
                </ul>
            </div>
            <div class="plnr-intro is-set">
                <p class="plnr-intro__text" id="check_introtext" data-name="check_plnrname_popup">소개글을 입력해주세요</p>
            </div>
        </div>

        <input type="radio" name="check-plnr-category" id="check_plnr_info_01" class="none-input" checked>
        <input type="radio" name="check-plnr-category" id="check_plnr_info_02" class="none-input">
        <input type="radio" name="check-plnr-category" id="check_plnr_info_03" class="none-input">
        <input type="radio" name="check-plnr-category" id="check_plnr_info_04" class="none-input">

        <ul class="plnr-category">
            <li class="plnr-category__list is-active">
                <label for="check_plnr_info_01">
                    <i class="plnr-category__icon plnr-category__icon--01"></i>
                    <span class="plnr-category__tit">홈</span>
                </label>
            </li>
            <li class="plnr-category__list">
                <label for="check_plnr_info_02">
                    <i class="plnr-category__icon plnr-category__icon--02"></i>
                    <span class="plnr-category__tit">여행상품</span>
                </label>
            </li>
            <li class="plnr-category__list">
                <label for="check_plnr_info_03">
                    <i class="plnr-category__icon plnr-category__icon--03"></i>
                    <span class="plnr-category__tit">포트폴리오</span>
                </label>
            </li>
            <li class="plnr-category__list">
                <label for="check_plnr_info_04">
                    <i class="plnr-category__icon  plnr-category__icon--04"></i>
                    <span class="plnr-category__tit">리뷰</span>
                </label>
            </li>
            <span class="plnr-category__indicator"></span>
        </ul>
        
        <div class="plnr-contents-wrapper">

            <div class="plnr-contents plnr-contents--01">

                <h4 class="plnr-contents__tit">인증</h4>

                <!-- 플래너가 인증할 때 (plnr-certify__step)에 사진올리면 button 사라지고 is-checked 클래스 추가되야함 -->
                <div class="plnr-certify">
                    <ul class="plnr-certify__group">
                        <li class="plnr-certify__step is-checked">
                            <span>핸드폰</span>
                        </li>
                        <li class="plnr-certify__step">
                            <button type="button" class="plnr-certify__step__btn" id="check_certify_idcard" data-name="check_certify_popup"></button>
                            <span>신분증</span>
                        </li>
                        <li class="plnr-certify__step">
                            <button type="button" class="plnr-certify__step__btn" id="check_certify_doc" data-name="check_certify_popup"></button>
                            <span>서류증빙</span>
                        </li>
                    </ul>
                </div>
                <!-- end -->

                <h4 class="plnr-contents__tit">정보</h4>
                <div class="plnr-desc">
                    <ul class="plnr-desc__group">
                        <li class="plnr-desc__input">
                            <input type="text" placeholder="정보 추가 (ex. 트리픽대학 관광학과 졸업)">
                            <button type="button" class="plus-btn"></button>
                        </li>
                    </ul>
                </div>
                
                <h4 class="plnr-contents__tit">플래너 스타일</h4>
                <div class="plnr-desc">
                    <ul class="plnr-desc__group">
                        <li class="plnr-desc__input">
                            <input type="text" placeholder="스타일 추가 (ex. 인생샷 전문가 , 대중교통 전문)">
                            <button type="button" class="plus-btn"></button>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="plnr-contents plnr-contents--02">

                <div class="plnr-option">

                    <h4 class="plnr-contents__tit">여행상품<span class="plnr-contents__amount">(0)</span></h4>

                    <div class="plnr-option__admit">여행상품 등록은 플래너 최종 승인 후 등록 가능합니다.</div>

                    <!-- 플래너일 때 여행상품 -->
                    <div class="plnr-trip-product__admit--after">
                        <p>나만의 여행상품을 등록해주세요.</p>
                        <button type="button" class="button" onClick="popupOpen('input_prdt_popup')">여행상품 등록</button>
                    </div>
                    <!-- 플래너일 때 여행상품 -->

                </div>

            </div>

            <div class="plnr-contents plnr-contents--03"> 

                <div class="plnr-option">

                    <h4 class="plnr-contents__tit">포트폴리오<span class="plnr-contents__amount">(0)</span></h4>

                    <div class="plnr-option__admit">포트폴리오 등록은 플래너 최종 승인 후 등록 가능합니다.</div>
                    <div class="plnr-pplio-admit--after" onClick="popupOpen('input_pplio_pic_view_popup')">
                        <div class="plnr-pplio-admit__card"><span class="plnr-pplio-admit__text">사진첩 추가</span></div>
                    </div>
                </div>

            </div>

            <div class="plnr-contents plnr-contents--04">
                <div class="plnr-review-hd">
                    <span class="plnr-review-hd__tit">전체 <em>0</em>건</span>
                    <div class="rating-stars__group">
                        <div class="rating-stars__stars">
                            <svg class="star-svg" id="star-01" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25">
                                <path class="star-svg__path star--left" d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z" transform="translate(-0.006 0)"/>
                                <path class="star-svg__path star--right" d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z" transform="translate(-19.499 0)"/>
                            </svg>
                        </div>
                        <span class="rating-stars__average"><b>0.0</b>/5</span>
                    </div>
                    <select name="review-sorting" class="plnr-review-hd__select">
                        <option>최신순</option>
                        <option>별점 높은 순</option>
                        <option>별점 낮은 순</option>
                    </select>
                </div>
                <div class="plnr-review__wrapper">
                    <ul class="trip-review__group">
                        <!-- 리뷰없을 때 -->
                        <li class="trip-review__list--nothing">
                            <img src="/img/icon/icon-review-nothing.svg" alt="nothing icon">
                            <span>등록된 리뷰가 없습니다.</span>
                        </li>
                        <!-- end -->
                    </ul>
                </div>
            </div>
        </div>

    </div>
                    
    <div class="wrapper--planner__btn">
        <button type="button" class="button button--apply" id="plnr_apply_btn">플래너 신청하기</button>
    </div>
    
</div>
    
<div class="popup popup--product" id="input_prdt_popup">
    <div class="popup--product__scroll-area">
        <div class="prdt-bg">
            <button type="button" onClick="popupClose('input_prdt_popup')" class="hd-title__left-btn hd-title__left-btn--prev-wh"><span class="none">이전버튼</span></button>
            <!-- 사진첨부했을 때 -->
            <!-- <figure class="prdt-bg-container swiper-container">
                <ul class="prdt-bg__group swiper-wrapper">
                    <li class="prdt-bg__list swiper-slide"></li>
                    <li class="prdt-bg__list swiper-slide"></li>
                    <li class="prdt-bg__list swiper-slide"></li>
                    <li class="prdt-bg__list swiper-slide"></li>
                </ul>
            </figure>
            <div class="prdt-bg__pagination">
                <div class="swiper-pagination"></div>
            </div> -->
            <!-- end -->
            <input
                type="file"
                id="input_product_picture"
                class="none-input"
            />
            <div class="prdt-bg__button">
                <label for="input_product_picture"></label>
            </div>
        </div>

        <div class="wrapper--inner">
            <div class="prdt-main-info">
                <span class="prdt-main-info__etc">[예시] 자유여행 / 유명맛집</span>
                <h5 class="prdt-main-info__tit is-set" id="check_prdt_name" data-name="input_prdt_info_popup">[예시]패키지상품 트리픽 현지 맛집 투어</h5>
                
                <div class="prdt-sub-info__input-group" id="check_prdt_intro" data-name="input_prdt_info_popup">
                    <span class="prdt-sub-info__input">소개글을 입력해주세요.</span>
                    <button type="button" class="plus-btn"></button>
                </div>
            </div>

            <div class="prdt-sub-info">
                <fieldset class="prdt-main-info__list">
                    <legend
                        class="prdt-sub-info__tit prdt-sub-info__tit--01"
                    >
                        코스
                    </legend>
                    <div class="prdt-sub-info__input-group" id="input_prdt_course" data-name="input_prdt_course_popup">
                        <span class="prdt-sub-info__input">코스 입력_예시 :  마포구 망원동 > 양화대교 > 서교동</span>
                        <button type="button" class="plus-btn"></button>
                    </div>
                </fieldset>
                <fieldset class="prdt-main-info__list">
                    <legend
                        class="prdt-sub-info__tit prdt-sub-info__tit--02"
                    >
                        일정
                    </legend>
                    <div class="prdt-sub-info__input-group" id="input_prdt_period" data-name="input_prdt_period_popup">
                        <span class="prdt-sub-info__input">일정을 입력해주세요.</span>
                        <button type="button" class="plus-btn"></button>
                    </div>
                </fieldset>
                <fieldset class="prdt-main-info__list">
                    <legend
                        class="prdt-sub-info__tit prdt-sub-info__tit--03"
                    >
                        만나는 장소 및 시간
                    </legend>
                    <div class="prdt-sub-info__input-group" id="input_prdt_time" data-name="input_prdt_time_popup">
                        <span class="prdt-sub-info__input">장소 및 시간을 입력해주세요.</span>
                        <button type="button" class="plus-btn"></button>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="wrapper--product__btn">
        <button type="button" class="button">입력완료</button>
    </div>
</div>
                
<div class="popup popup--modal">

    <div class="popup__inner" id="check_plnr_type_popup">
        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>개인 및 업체분류</h3>
        <span class="inline inline--center">플래너로 활동하는 형태를 선택하세요.</span>
        <input type="radio" name="check-plnr-type" class="none-input" id="check_plnr_type_01">
        <label class="button button--outline" for="check_plnr_type_01">개인</label>
        <input type="radio" name="check-plnr-type" class="none-input" id="check_plnr_type_02">
        <label class="button button--outline" for="check_plnr_type_02">업체</label>
    </div>
    
    <div class="popup__inner" id="check_plnrname_popup">
        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>플래너명/소개글 입력</h3>
        <div class="popup__inner__field">
            <div class="field__align">
                <label class="inline inline--left">플래너명을 입력하세요.</label>
                <span class="input-group">
                    <input type="text" class="input-group__input" placeholder="플래너명 입력 (최대 n자)">
                </span>
                <span class="inline inline--right">
                    <em class="caution caution--disable">중복되는 플래너명이 존재합니다.</em>
                    <em class="caution caution--able">사용가능한 플래너명입니다.</em>
                </span>
            </div>
            <div class="field__align">
                <label class="inline inline--left">자신을 표현하는 소개글을 입력하세요.</label>
                <div class="intro__textarea">
                    <textarea cols="30" rows="5" placeholder="소개글을 입력해주세요."></textarea>
                </div>
            </div>
            <button type="button" class="button button--disable">입력완료</button>
        </div>
    </div>
    
    <div class="popup__inner" id="check_certify_popup">
        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>인증하기</h3>
        <div class="popup__inner__field">
            <span class="inline inline--left">플래너의 신뢰를 증명하기 위한 <br>인증을 완료해주세요.</span>
            <div class="field__align">
                <h5 class="certify__tit">01. 신분증 인증<em>(픽업 서비스 제공 시, 운전면허증 등록)</em></h5>
                <ul class="certify__thumbnail-group">
                    <input type="file" id="input_idcard_pic_01" class="none-input">
                    <li class="certify__thumbnail-list">
                        <label for="input_idcard_pic_01"></label>
                        <span class="certify__thumbnail-list__span">신분증 추가</span>
                    </li>
                    <input type="file" id="input_idcard_pic_02" class="none-input">
                    <li class="certify__thumbnail-list">
                        <label for="input_idcard_pic_02"></label>
                        <span class="certify__thumbnail-list__span">면허증 추가</span>
                    </li>
                </ul>
            </div>
            <div class="field__align">
                <h5 class="certify__tit">02. 서류증빙<em>(최대 3개)</em></h5>
                <ul class="certify__thumbnail-group">
                    <input type="file" id="input_docum_pic_01" class="none-input">
                    <li class="certify__thumbnail-list">
                        <label for="input_docum_pic_01"></label>
                        <span class="certify__thumbnail-list__span">사진 추가</span>
                    </li>
                    <input type="file" id="input_docum_pic_02" class="none-input">
                    <li class="certify__thumbnail-list">
                        <label for="input_docum_pic_02"></label>
                        <span class="certify__thumbnail-list__span">사진 추가</span>
                    </li>
                    <input type="file" id="input_docum_pic_03" class="none-input">
                    <li class="certify__thumbnail-list">
                        <label for="input_docum_pic_03"></label>
                        <span class="certify__thumbnail-list__span">사진 추가</span>
                    </li>
                </ul>
            </div>
            <button type="button" class="button button--disable">입력완료</button>
        </div>
    </div>

    <div class="popup__inner" id="input_prdt_info_popup">
        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>상품소개 입력</h3>
        <div class="popup__inner__field">
            <div class="field__align">
                <span class="inline inline--left">상품의 종류를 입력해주세요.</span>
                <div class="input-group">
                    <input type="text" class="input-group__input" placeholder="예시) 자유여행 / 유명맛집">
                </div>
            </div>
            <div class="field__align">
                <span class="inline inline--left">상품의 제목을 입력해주세요.</span>
                <div class="input-group">
                    <input type="text" class="input-group__input" placeholder="예시) 패키지상품 트리픽 현지 맛집 투어">
                </div>
            </div>
            <div class="popup--product__textarea">
                <span class="inline inline--left">소개글을 입력해주세요.</span>
                <textarea cols="30" rows="10"></textarea>
            </div>
            <button type="button" class="button button--disable">입력완료</button>
        </div>
    </div>

    <div class="popup__inner popup__inner--not-pd" id="input_prdt_period_popup"> 
        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>일정선택</h3>
        <fieldset class="popup__inner__field">
            <div id="datepick_02" class="datepick_02er-here" data-range="true"></div>
            <div class="date-group">
                <span class="inline inline--center">
                    <em>07월 17일 (수)</em>&nbsp;~&nbsp;<em>07월 24일 (수)</em>&nbsp;&nbsp;
                    <b>99박 99일</b>
                </span>
                <button type="button" class="button button--disable">선택완료</button>
            </div>
        </fieldset>

    </div>

    <div class="popup__inner" id="input_prdt_course_popup">
        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>코스 입력</h3>
        <div class="popup__inner__field">
            <div class="field__align">
                <span class="inline inline--left">코스를 입력해주세요.</span>
                <div class="input-group">
                    <input type="text" class="input-group__input" placeholder="예시) 마포구 망원동 > 양화대교 > 서교동">
                </div>
            </div>
            <button type="button" class="button button--disable">입력완료</button>
        </div>
    </div>

    <div class="popup__inner" id="input_prdt_time_popup">
        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>장소 및 시간입력</h3>
        <div class="popup__inner__field">
            <div class="field__align">
                <div id="timepicker" class="datepicker-here" data-timepicker="true" data-language='en'></div>
            </div>
            <div class="field__align">
                <span class="inline inline--left">장소를 입력해주세요.</span>
                <div class="input-group">
                    <input type="text" class="input-group__input" placeholder="예시) 망원동">
                </div>
            </div>
            <div class="field__align">
            </div>
            <button type="button" class="button button--disable">입력완료</button>
        </div>
    </div>

</div>
                
<div class="popup popup--input-pplio" id="input_pplio_pic_view_popup">
    
    <div class="hd-title hd-title--01">
        <button type="button" onClick="popupClose('input_pplio_pic_view_popup')" class="hd-title__right-btn hd-title__right-btn--close"><span class="none">닫기버튼</span></button>
        <h2 class="hd-title__center">포트폴리오 추가</h2>
    </div>

    <span class="input-group">
        <input type="text" class="input-group__input" placeholder="사진첩 제목을 입력해주세요. (최대 10글자)">
    </span>

    <div class="input-pplio-pic">
        <input type="file" id="input_pplio_pic" multiple class="none-input">
        <ul class="review__pictures-group" id="pplio_multiple_pic"> 
            <li class="review__pictures-list review__pictures-list--file" id="pplio_multiple_pic_btn">
                <label for="input_pplio_pic"></label>
                <span class="review__pictures-list__span">사진추가</span>
            </li>
        </ul>
    </div>

    <div class="input-pplio-pic__btns is-edit">
        <span class="input-pplio-pic__btns__amount">총 <em>1</em>개</span>
        <button type="button" class="button">삭제하기</button>
    </div>

</div>
                
@include('nav.nav_user')

@endsection

@section('script')
<script>
$(function(){
        
    //카테고리 탭메뉴 움직임효과
    var plnr_indicatorWidth = $('.plnr-category__list.is-active span').innerWidth();
    var plnr_indicatorposi = $('.plnr-category__list.is-active span').position();

    $('.plnr-category__indicator').css({
        width: plnr_indicatorWidth,
        left: plnr_indicatorposi.left
    })

    function categoryIndicatorMove() {

        var plnr_indicatorposi = $('.plnr-category__list.is-active span').position();
        var plnr_indicatorWidth = $('.plnr-category__list.is-active span').innerWidth();

        $(".plnr-category__indicator").stop().css({
            left: plnr_indicatorposi.left,
            width: plnr_indicatorWidth
        })

    }

    $('.plnr-category__list').click(function(e){

        $(this).addClass('is-active');
        $(".plnr-category__list")
            .not(this)
            .removeClass("is-active");
        categoryIndicatorMove();

    });
    //end

    //플래너 신청하기 버튼 누를 때
    $('#plnr_apply_btn').click(function(e){
        dialog.confirm({
            title:'',
            message: '<div class="single-msg">입력하신 정보로 플래너를 신청하시겠습니까?</div>',
            cancel: "아니오",
            button: "예"
        })
    })
    //end

    //영역을터치하여 정보입력 닫기
    $('#alarm_msg_close').click(function(e){
        $(event.target).parent('.user-msg-alarm').animate({
            top: 0,
            opacity: 0
        })
    })
    //end
    
    //여행상품 일정/만나는시간 입력 datepick
    $("#datepick_02, #timepicker").datepicker({
        minDate: new Date(new Date().valueOf() + 1000 * 3600 * 240),
        language: "en",
        inline: "true"
    });
    //end

    //신분증 인증, 서류 증빙리스트
    var certify_input_array = ['#input_idcard_pic_01, #input_idcard_pic_02, #input_docum_pic_01, #input_docum_pic_02, #input_docum_pic_03'];

    for(i=0;i<certify_input_array.length;i++){
        
        $(certify_input_array[i]).change(function(){
            certifyURL(this);
        });

    }
    //end

    //포트폴리오 사진 추가
    $('#input_pplio_pic').change(function(){

        var num =  0;

        for (var i = 0; i < this.files.length; i++) {
            
            var fr = new FileReader();

            fr.onload = function(e) {
                $('#pplio_multiple_pic').append('<li class="review__pictures-list" id="pplio_img_'+num+'" style="background-image: url('+e.target.result+')"><button type="button" class="pic-del-btn"></button></li>');
                $('.pic-del-btn').bind('click',function(){
                    $(this).parents('.review__pictures-list').remove();
                });
                num++;
            }

            fr.readAsDataURL(this.files[i]);
        
        }

    })
    //end

})

//신분증인증 썸네일
function certifyURL (name){

    if (name.files && name.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $(name).next('li').css('background-image', 'url('+e.target.result+')');
            $(name).next('li').addClass('is-filled')
        }

        reader.readAsDataURL(name.files[0]);
    }

}
//end

</script>
@endsection