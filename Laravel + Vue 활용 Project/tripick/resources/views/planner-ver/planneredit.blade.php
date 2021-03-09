@extends('layouts.app')

@section('content')

<div class="wrapper wrapper--planner">
                    
    <div class="user-msg-alarm">
        <p class="user-msg-alarm__text">영역을 터치하여 정보를 수정하세요.</p>
        <span class="user-msg-alarm__btn" id="alarm_msg_close"><b class="none">닫기버튼</b></span>
    </div>
    
    <div class="hd-title hd-title--01">
        <button type="button" onClick="history.back()" class="hd-title__left-btn hd-title__left-btn--prev"><span class="none">이전버튼</span></button>
        <h2 class="hd-title__center">플래너 편집</h2>
    </div>
    
    <div class="wrapper--planner__scroll-area">

        <div class="plnr">
            <div class="plnr-picture">
                <form id="form_bg_photo" method="POST" action="/api/plnr/pln_bg_photo" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="put" />
                    <input type="file" name="plnrbg" id="input_plnr_background" class="none-input">
                </form>
                <form id="form_thumb" method="POST" action="/api/plnr/pln_thumb" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="put" />
                    <input type="file" name="plnrthumb" id="input_plnr_picture" class="none-input">
                </form>
                <label class="plnr-picture__bg lnr-picture__bg--set" id="input_plnr_background_temp" for="input_plnr_background" 
                	style="background-image: url({{'/storage/'.config('filesystems.planner_bg').$query->pln_bg_photo}});background-size:100% 100%">
                    <span class="plnr-picture__bg__caution">배경이미지를 변경하시려면 클릭하세요.</span>
                </label>
                <label class="plnr-picture__profile plnr-picture__profile--set" id="input_plnr_picture_temp" for="input_plnr_picture" 
                	style="z-index:10;background-image: url({{'/storage/'.config('filesystems.planner_thumb').$query->pln_thumb}});background-size:100% 100%">
                    @if($query->pln_score < 1.66)
                    <span class="plnr-grade plnr-grade--03"></span>
                    @elseif($query->pln_score < 3.33)
                    <span class="plnr-grade plnr-grade--02"></span>
                    @else
                    <span class="plnr-grade plnr-grade--01"></span>
                    @endif
                    
                </label>
            </div>
            <div class="plnr-info">
                <span class="plnr-info__type">{{ $query->pln_type ===0 ? "개인" : "업체" }}</span>
                <p class="plnr-info__name">
                    <b>{{ $query->pln_name }}</b>
                </p>
                <ul class="plnr-info__score">
                    <li class="plnr-info__score__list">
                        <span class="plnr-info__num"> {{ $query->trades}}</span>
                        <em class="plnr-info__label">가이드횟수</em>
                    </li>
                    <li class="plnr-info__score__list">
                        <span class="plnr-info__num">{{ $query->reviews}}</span>
                        <em class="plnr-info__label">전체리뷰</em>
                    </li>
                    <li class="plnr-info__score__list">
                        <span class="rating plnr-info__num">{{ number_format($query->pln_score,2) }}</span>
                        <em class="plnr-info__label">평균 별점</em>
                    </li>
                </ul>
            </div>
            <div class="plnr-intro is-set">
                <p class="plnr-intro__text" id="check_introtext" data-name="check_plnrname_popup">{{ $query->pln_desc }}</p>
            </div>
        </div>

        <input type="radio" name="check-plnr-category" id="check_plnr_info_01" value="home" class="none-input" checked>
        <input type="radio" name="check-plnr-category" id="check_plnr_info_02" value="prds" class="none-input">
        <input type="radio" name="check-plnr-category" id="check_plnr_info_03" value="porfo" class="none-input">
        <input type="radio" name="check-plnr-category" id="check_plnr_info_04" value="rvs" class="none-input">

        <ul class="plnr-category">
            <li data-tab="home" class="plnr-category__list is-active">
                <label for="check_plnr_info_01">
                    <i class="plnr-category__icon plnr-category__icon--01"></i>
                    <span class="plnr-category__tit">홈</span>
                </label>
            </li>
            <li data-tab="prds" class="plnr-category__list">
                <label for="check_plnr_info_02">
                    <i class="plnr-category__icon plnr-category__icon--02"></i>
                    <span class="plnr-category__tit">여행상품</span>
                </label>
            </li>
            <li data-tab="porfo" class="plnr-category__list">
                <label for="check_plnr_info_03">
                    <i class="plnr-category__icon plnr-category__icon--03"></i>
                    <span class="plnr-category__tit">포트폴리오</span>
                </label>
            </li>
            <li data-tab="rvs" class="plnr-category__list">
                <label for="check_plnr_info_04">
                    <i class="plnr-category__icon  plnr-category__icon--04"></i>
                    <span class="plnr-category__tit">리뷰</span>
                </label>
            </li>
            <span class="plnr-category__indicator"></span>
        </ul>
        
        <div class="plnr-contents-wrapper">
            <!-- 홈페이지 -->
            <div class="plnr-contents plnr-contents--01">

                <h4 class="plnr-contents__tit">인증</h4>

                <!-- 플래너가 인증할 때 (plnr-certify__step)에 사진올리면 button 사라지고 is-checked 클래스 추가되야함 -->
                <div class="plnr-certify">
                    <ul class="plnr-certify__group">
                        <li class="plnr-certify__step is-checked"><span>핸드폰</span></li>
                        <li class="plnr-certify__step {{ strlen($query->pln_id_card) > 0 ? 'is-checked' : '' }} ">
                            <span>신분증</span></li>
                        <li class="plnr-certify__step {{ strlen($query->pln_docs) > 0 ? 'is-checked' : '' }} ">
                            <span>서류증빙</span></li>
                    </ul>
                </div>
                <!-- end -->
                <form id="form_pln_home" method="POST" action="/api/plnr/pln_home">
                <input type="hidden" name="_method" value="put" />
                <h4 class="plnr-contents__tit">정보</h4>
                <div class="plnr-desc">
                    <ul id="plnr-info-wrap" class="plnr-desc__group">
                        @if(json_decode($query->pln_info, true))
                            @for($i=0; $i < count(json_decode($query->pln_info)); $i++)
                                <li class="plnr-desc__input mb_10">
                                    <input type="text" class="input_pln_home" name="pln_info[]" placeholder="정보 추가 (ex. 트리픽대학 관광학과 졸업)" value="{{ json_decode($query->pln_info)[$i] }}">
                                    @if($i == 0)
                                    <button name="add-plnr-info" type="button" class="plus-btn"></button>
                                    @else
                                    <button name="minus-plnr-info" type="button" class="minus-btn"></button>
                                    @endif
                                </li>
                            @endfor
                        @else
                        <li class="plnr-desc__input mb_10">
                            <input type="text" class="input_pln_home" name="pln_info[]" placeholder="정보 추가 (ex. 트리픽대학 관광학과 졸업)">
                            <button name="add-plnr-info" type="button" class="plus-btn"></button>
                        </li>
                        @endif
                    </ul>
                </div>
                
                <h4 class="plnr-contents__tit">플래너 스타일</h4>
                <div class="plnr-desc">
                    <ul id="plnr-style-wrap" class="plnr-desc__group">
                        @if(json_decode($query->pln_trip_style, true))
                            @for($i=0; $i < count(json_decode($query->pln_trip_style)); $i++)
                                <li class="plnr-desc__input mb_10">
                                    <input type="text" class="input_pln_home" name="pln_style[]" placeholder="스타일 추가 (ex. 인생샷 전문가 , 대중교통 전문)" value="{{ json_decode($query->pln_trip_style)[$i] }}">
                                    @if($i == 0)
                                    <button name="add-plnr-style" type="button" class="plus-btn"></button>
                                    @else
                                    <button name="minus-plnr-info" type="button" class="minus-btn"></button>
                                    @endif
                                </li>
                            @endfor
                        @else
                        <li class="plnr-desc__input mb_10">
                            <input type="text" class="input_pln_home" name="pln_style[]" placeholder="스타일 추가 (ex. 인생샷 전문가 , 대중교통 전문)">
                            <button name="add-plnr-style" type="button" class="plus-btn"></button>
                        </li>
                        @endif
                    </ul>
                </div>

                <h4 class="plnr-contents__tit">플래너 지역</h4>
                <div class="plnr-desc">
                    <ul id="plnr-juri-wrap" class="plnr-desc__group">
                        @if(json_decode($query->jurisdiction_area, true))
                            @for($i=0; $i < count(json_decode($query->jurisdiction_area)); $i++)
                                <li class="plnr-desc__input mb_10">
                                    <input type="text" class="input_pln_home pln_juri" name="pln_juri[]" placeholder="거주지 인근 관광지 (ex.이태원, 남포동, 여수 )" value="{{ json_decode($query->jurisdiction_area)[$i] }}">
                                    @if($i == 0)
                                    <button name="add-plnr-juri" type="button" class="plus-btn"></button>
                                    @else
                                    <button name="minus-plnr-info" type="button" class="minus-btn"></button>
                                    @endif
                                </li>
                            @endfor
                        @else
                        <li class="plnr-desc__input mb_10">
                            <input type="text" class="input_pln_home pln_juri" name="pln_juri[]" placeholder="거주지 인근 관광지 (ex.이태원, 남포동, 여수 )">
                            <button name="add-plnr-juri" type="button" class="plus-btn"></button>
                        </li>
                        @endif
                    </ul>
                </div>
                </form>
            </div>
            <!-- 상품페이지 -->
            <div class="plnr-contents plnr-contents--02">

                <div class="plnr-option">

                    <h4 class="plnr-contents__tit">여행상품<span class="plnr-contents__amount">({{ $query->products }})</span></h4>

                    <!-- 플래너일 때 여행상품 -->
                    <div class="plnr-trip-product__admit--after">
                        <p>나만의 여행상품을 등록해주세요.</p>
                        <button type="button" class="button" id="open_prdt_insert" onClick="popupOpen('input_prdt_popup');">여행상품 등록</button>
                    </div>
                    <!-- 플래너일 때 여행상품 -->

                </div>
                <div class="plnr-trip-product" style="margin-top:10px;">
                    <ul id="prd-list-wrap" class="trip-product__group">
                        
                    </ul>
                </div>
            </div>
            <!-- 포트폴리오페이지 -->
            <div class="plnr-contents plnr-contents--03"> 

                <div class="plnr-option">

                    <h4 class="plnr-contents__tit">포트폴리오<span class="plnr-contents__amount">({{ $query->portfolios }})</span></h4>

                    <div class="plnr-trip-product__admit--after">
                        <p></p>
                        <button type="button" class="button" onClick="popupOpen('input_pplio_pic_view_popup')">사진첩 추가</button>
                    </div>
                    <div class="plnr-pplio">
                        <ul id="porfo-wrap" class="plnr-pplio__group"></ul>
                    </div>
                </div>

            </div>
            <!-- 리뷰페이지 -->
            <div class="plnr-contents plnr-contents--04">
                <div class="plnr-review-hd">
                    <span class="plnr-review-hd__tit">전체 <em>{{ $query->reviews }}</em>건</span>
                    <div class="rating-stars__group is-star-01">
                        <div class="rating-stars__stars">
                            <svg class="star-svg" id="star-01" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25">
                                <path class="star-svg__path star--left" d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z" transform="translate(-0.006 0)"/>
                                <path class="star-svg__path star--right" d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z" transform="translate(-19.499 0)"/>
                            </svg>
                        </div>
                        <span class="rating-stars__average"><b>{{ number_format($query->pln_score,2) }}</b>/5</span>
                    </div>
                    <select name="review-sorting" class="plnr-review-hd__select">
                        <option value="created_at">최신순</option>
                        <option value="max_score">별점 높은 순</option>
                        <option value="min_score">별점 낮은 순</option>
                    </select>
                </div>
                <div class="plnr-review__wrapper">
                    <ul class="trip-review__group" id="rv-wrap">
                        <!-- 리뷰없을 때 -->
                        <li class="trip-review__list--nothing" style="display:none;">
                            <img src="/img/icon/icon-review-nothing.svg" alt="nothing icon">
                            <span>등록된 리뷰가 없습니다.</span>
                        </li>
                        <!-- end -->
                    </ul>
                </div>
            </div>
        </div>

    </div>

    
</div>

<!-- 상품 관련 POPUP, TEMPLATE -->
<template id="prd-list-tplt">
    <li class="trip-product__list">
        <figure name="prd-thumb" class="trip-product__thum" style="background-size:100% 100%;"></figure>
        <span name="prd-title" class="trip-product__etc">자유여행 / 유명맛집</span>
        <p name="prd-subt" class="trip-product__tit">[투어] 현지의 유명맛집은 어디? 홍콩섬 현지 맛집투어</p>

        <div class="rating-stars__group is-star-01">
            <div class="rating-stars__stars">
                <svg class="star-svg" id="star-01" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 25 25">
                    <path class="star-svg__path star--left"
                        d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z"
                        transform="translate(-0.006 0)" />
                    <path class="star-svg__path star--right"
                        d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z"
                        transform="translate(-19.499 0)" />
                </svg>
            </div>
            <span name="prd-score" class="rating-stars__total">100</span>
            <span name="prd-count" class="rating-stars__total">(100)</span>
        </div>
    </li>
</template>
<form id="form_product" method="POST" action="/api/product" enctype="multipart/form-data">
<div class="popup popup--product" id="input_prdt_popup">
    
    <div class="popup--product__scroll-area">
        <div class="prdt-bg">
            <button type="button" onClick="popupClose('input_prdt_popup')" class="hd-title__left-btn hd-title__left-btn--prev-wh"><span class="none">이전버튼</span></button>
            <input type="file" name="prd_slides[]" id="input_product_picture" class="none-input" multiple/>
            <div class="prdt-bg__button" style="z-index:3;">
                <label for="input_product_picture"></label>
            </div>
            <div class="prdt-bg__edit" style="z-index:10;"></div>
            <!-- 사진첨부했을 때 -->
            <figure class="prdt-bg-container swiper-container">
                <ul class="prdt-bg__group swiper-wrapper" id="prd_slides_group">
                </ul>
            </figure>
            <div class="prdt-bg__pagination">
                <div class="swiper-pagination"></div>
            </div>
            <!-- end -->
            
        </div>

        <div class="wrapper--inner">
            <div class="prdt-main-info">
                <input type="text" name="prd_subtitle" class="prdt-main-info__etc" placeholder="[예시] 자유여행 / 유명맛집" readonly required>
                <input type="text" name="prd_title" style="width:100%;padding-right:0;border:none;" class="prdt-main-info__tit is-set" id="check_prdt_name" data-name="input_prdt_info_popup" placeholder="[예시]패키지상품 트리픽 현지 맛집 투어" readonly>
                
                
                <div class="prdt-sub-info__input-group" id="check_prdt_intro" data-name="input_prdt_info_popup">
                    <textarea name="prd_desc" id="prd_desc__intro" class="prdt-sub-info__span" placeholder="소개글을 입력해주세요." readonly required style="resize: none;"></textarea>
                </div>
            </div>

            <div class="prdt-sub-info">
                <fieldset class="prdt-main-info__list">
                    <legend class="prdt-sub-info__tit prdt-sub-info__tit--01">
                        코스
                    </legend>
                    <div class="prdt-sub-info__input-group">
                        <input type="text" name="prd_course" class="prdt-sub-info__span" placeholder="코스 입력_예시 :  마포구 망원동 > 양화대교 > 서교동" required>
                    </div>
                </fieldset>
                <fieldset class="prdt-main-info__list">
                    <legend
                        class="prdt-sub-info__tit prdt-sub-info__tit--02"
                    >
                        일정
                    </legend>
                    <div class="prdt-sub-info__input-group" id="input_prdt_period" data-name="input_prdt_period_popup">
                        <input type="text" name="prd_schedule" class="prdt-sub-info__span" placeholder="일정을 입력해주세요." readonly required>
                    </div>
                </fieldset>
                <fieldset class="prdt-main-info__list">
                    <legend
                        class="prdt-sub-info__tit prdt-sub-info__tit--03"
                    >
                        만나는 장소 및 시간
                    </legend>
                    <div class="prdt-sub-info__input-group">
                        <input type="text" name="prd_place_time" class="prdt-sub-info__span" placeholder="장소 및 시간을 입력해주세요." required>
                    </div>
                </fieldset>
                <fieldset class="prdt-main-info__list">
                    <legend
                        class="prdt-sub-info__tit prdt-sub-info__tit--03"
                    >
                        사용방법
                    </legend>
                    <div class="prdt-sub-info__input-group">
                        <input type="text" name="prd_manual" class="prdt-sub-info__span" placeholder="사용방법 및 환불정책" required>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="wrapper--product__btn">
        <button type="button" class="button button--disable" id="submit_product">입력완료</button>
    </div>
    
</div>
</form>
<!-- 상품 관련 POPUP, TEMPLATE END -->
<!-- 서브 POPUP START -->
<div class="popup popup--modal">
    <!-- 홈 관련 POPUP START -->
    <div class="popup__inner" id="check_plnrname_popup">
        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>소개글 입력</h3>
        <div class="popup__inner__field">
            <div class="field__align">
                <label class="inline inline--left">자신을 표현하는 소개글을 입력하세요.</label>
                <div class="intro__textarea">
                    <textarea id="plnr-popup-desc" name="pln_desc" cols="30" rows="5" placeholder="소개글을 입력해주세요.">{{ $query->pln_desc }}</textarea>
                </div>
            </div>
            <button id="plnr-nameok" type="button" class="button button--disable is-active button--relative">입력완료</button>
        </div>
    </div>
    <!-- 홈 관련 POPUP END -->
    <!-- 상품 관련 POPUP START -->
    <div class="popup__inner" id="input_prdt_info_popup">
        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>상품소개 입력</h3>
        <div class="popup__inner__field">
            <div class="field__align">
                <span class="inline inline--left">상품의 종류를 입력해주세요.</span>
                <div class="input-group">
                    <input type="text" class="input-group__input temp_input_intro temp_prd_input" id="temp_prd_subtitle" placeholder="예시) 일일투어/숙소/교통 등">
                </div>
            </div>
            <div class="field__align">
                <span class="inline inline--left">상품의 제목을 입력해주세요.</span>
                <div class="input-group">
                    <input type="text" class="input-group__input temp_input_intro temp_prd_input" id="temp_prd_title" placeholder="예시) 패키지상품 트리픽 현지 맛집 투어">
                </div>
            </div>
            <div class="popup--product__textarea">
                <span class="inline inline--left">소개글을 입력해주세요.</span>
                <textarea cols="30" rows="10" id="temp_prd_desc" class="temp_input_intro temp_prd_input"></textarea>
            </div>
            <button type="button" class="button button--relative button--disable is-active mg_tb_20" id="btn_popup_intro">입력완료</button>
        </div>
    </div>

    <div class="popup__inner popup__inner--not-pd" id="input_prdt_period_popup"> 
        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>일정선택</h3>
        <fieldset class="popup__inner__field">
            <div id="datepick_02" class="datepick_02er-here" data-range="true"></div>
            <div class="date-group">
                <span class="inline inline--center temp_prd_input" id="temp_prd_schedule"></span>
                <button type="button" class="button button--disable" id="btn_popup_schedule">선택완료</button>
            </div>
        </fieldset>

    </div>
    <!-- 상품 관련 POPUP END -->
</div>
<!-- 상품 서브 POPUP END -->

<!-- 포트폴리오 관련 POPUP, TEMPLATE START -->
<template id="porfo-tplt">
    <li name="porfo-content" class="plnr-pplio__list">
            <figure name="porfo-thumb" class="plnr-pplio__thum" style="background-image: url(img/example/img-pplio01.jpg);">
            </figure>
        <p name="porfo-title" class="plnr-pplio__tit">삿포로 여행기</p>
        <span name="porfo-info" class="plnr-pplio__etc">사진 6장</span>
    </li>
</template>

<form id="form_portfolio" method="POST" action="/api/portfolio" enctype="multipart/form-data">
<div class="popup popup--input-pplio" id="input_pplio_pic_view_popup">
    <div class="hd-title hd-title--01">
        <button type="button" onClick="popupClose('input_pplio_pic_view_popup')" class="hd-title__right-btn hd-title__right-btn--close"><span class="none">닫기버튼</span></button>
        <h2 class="hd-title__center">포트폴리오 추가</h2>
    </div>

    <span class="input-group">
        <input type="text" name="portf_title" class="input-group__input" placeholder="사진첩 제목을 입력해주세요. (최대 10글자)" required>
    </span>

    <div class="input-pplio-pic">
        <input type="file" id="input_pplio_pic" name="portf_files[]" multiple="multiple" class="none-input">
        <ul class="review__pictures-group" id="pplio_multiple_pic"> 
            <li class="review__pictures-list review__pictures-list--file" id="pplio_multiple_pic_btn">
                <label for="input_pplio_pic"></label>
                <span class="review__pictures-list__span">사진추가</span>
            </li>
        </ul>
    </div>

    <div class="input-pplio-pic__btns is-edit">
        <span class="input-pplio-pic__btns__amount">총 <em id="portf_count">1</em>개</span>
        <button type="button" class="button" id="btn_submit_portfolid">등록하기</button>
    </div>
</div>
</form>

<div class="popup popup--more-view" id="pplio_more_view_popup">

    <div class="hd-title hd-title--01">
        <button type="button" class="hd-title__right-btn hd-title__right-btn--close-wh"
            onClick="popupClose('pplio_more_view_popup')"><span class="none">닫기버튼</span></button>
        <!-- 플래너가 볼때 -->
        <button type="button" class="hd-title__left-btn">삭제</button>
        <!-- end 플래너가 볼때 -->
        <h2 class="hd-title__center">
            <b>삿포로 여행기</b>
            <span class="more-view-pagination swiper-pagination"></span>
        </h2>

    </div>

    <div class="more-view swiper-container" id="portfolio_more_view_container">
        <ul id="porfo-more-view" class="more-view__group swiper-wrapper">


        </ul>
        <div class="more-view__btn more-view__btn--next swiper-button-next"></div>
        <div class="more-view__btn more-view__btn--prev swiper-button-prev"></div>
    </div>

    <div class="pplio-bubble">
        <div class="pplio-bubble__group">
            <div class="pplio-bubble__hd">
                <button type="button" class="pplio-bubble__hd__btn pplio-bubble__hd__btn--left"
                    onClick="popupMiniClose('pplio-bubble');">취소</button>
                <h5 class="pplio-bubble__hd__center">설명 입력</h5>
                <button type="button" id="submit_portf_desc" data-sort="0" data-porfo="" class="pplio-bubble__hd__btn pplio-bubble__hd__btn--right">확인</button>
            </div>
            <fieldset class="pplio-bubble__hd__textarea">
                <textarea cols="30" rows="10" id="textarea_portf_desc"></textarea>
            </fieldset>
        </div>
    </div>

    <div class="pplio-text">
        <!-- <p class="pplio-text__paragraph">김포공항에서 만난 '아이린' 회원님입니다! 혼자 여행오셨고 방문지마다 한장 이상의 인생샷을 건지기 원하셔서 찍어드린 사진입니다!</p> -->
        <p class="pplio-text__paragraph--edit" id="pplio_slide_desc" onClick="popupMiniOpen('pplio-bubble');">사진에 대한 설명을 적어주세요.</p>
    </div>

</div>
<!-- 포트폴리오 관련 POPUP, TEMPLATE END -->
<!-- 리뷰 관련 TEMPLATE, POPUP START -->
<template id="revw-list-tplt">
    <li id="rv-tplt" class="trip-review__list">
        <div class="trip-review__list__hd user-list">
            <figure class="user-list__thum"></figure>
            <h5 name="rv-usr-name" class="user-list__name">여행이좋다</h5>
            <span name='rv-reg-dt' class="user-list__date">2018.07.03</span>

            <div class="rating-stars__group">
                <div class="rating-stars__stars">
                    <svg class="star-svg" id="star-01" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 25 25">
                        <path class="star-svg__path star--left"
                            d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z"
                            transform="translate(-0.006 0)" />
                        <path class="star-svg__path star--right"
                            d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z"
                            transform="translate(-19.499 0)" />
                    </svg>
                    <svg class="star-svg" id="star-02" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 25 25">
                        <path class="star-svg__path star--left"
                            d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z"
                            transform="translate(-0.006 0)" />
                        <path class="star-svg__path star--right"
                            d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z"
                            transform="translate(-19.499 0)" />
                    </svg>
                    <svg class="star-svg" id="star-03" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 25 25">
                        <path class="star-svg__path star--left"
                            d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z"
                            transform="translate(-0.006 0)" />
                        <path class="star-svg__path star--right"
                            d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z"
                            transform="translate(-19.499 0)" />
                    </svg>
                    <svg class="star-svg" id="star-04" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 25 25">
                        <path class="star-svg__path star--left"
                            d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z"
                            transform="translate(-0.006 0)" />
                        <path class="star-svg__path star--right"
                            d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z"
                            transform="translate(-19.499 0)" />
                    </svg>
                    <svg class="star-svg" id="star-05" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 25 25">
                        <path class="star-svg__path star--left"
                            d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z"
                            transform="translate(-0.006 0)" />
                        <path class="star-svg__path star--right"
                            d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z"
                            transform="translate(-19.499 0)" />
                    </svg>
                </div>
                <span class="rating-stars__total">3.5</span>
            </div>
        </div>
        <div class="trip-review__list__con">
            <p name="rv-comment" class="trip-review__list__text">-</p>
            <!-- 리뷰사진 있을 경우 -->
            <div class="plnr-review-pic swiper-container">
                <ul name="rv-img-wrap" class="review__pictures-group swiper-wrapper">


                </ul>
            </div>
            <!-- 리뷰사진 있을 경우 -->
        </div>
    </li>
</template>
<div class="popup popup--more-view" id="review_more_view_popup">

    <div class="hd-title hd-title--01">
        <button type="button" class="hd-title__right-btn hd-title__right-btn--close-wh" onClick="popupClose('review_more_view_popup')"><span class="none">닫기버튼</span></button>
        <h2 class="hd-title__center">
            <b>후기사진</b>
            <span class="more-view-pagination swiper-pagination"></span>
        </h2>
    </div>

    <div class="more-view swiper-container" id="review_more_view_container">
        <ul class="more-view__group swiper-wrapper" >
            
        </ul>
        <div class="more-view__btn more-view__btn--next swiper-button-next"></div>
        <div class="more-view__btn more-view__btn--prev swiper-button-prev"></div>
    </div>

</div>
<!-- 리뷰 관련 TEMPLATE END -->
@include('nav.nav_planner')

@endsection

@section('script')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCpIgX3qFRixpEox5kUaJkXlxslRZKmxWs&libraries=places" type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
$(function(){
    google.maps.event.addDomListener(window, 'load', initialize);
    initialize();
    //배경이미지 편집
    $('#input_plnr_background').change(function(){
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#input_plnr_background_temp').css('background-image', 'url('+e.target.result+')');
                $('#input_plnr_background_temp').css('background-size', '100% 100%');
            }
            reader.readAsDataURL(this.files[0]);
            $('#form_bg_photo').submit();
        }else{
            $('#input_plnr_background_temp').css('background-image', 'url(no-data)');
        }
        
    });
    $('#form_bg_photo').ajaxForm({
        dataType : "json",
        success: function(data) {
            if(data.state == 1){
                dialog.alert({
                    title:'',  
                    message: '<p class="single-msg">플래너 배경사진 변경이 완료되었습니다.</p>',
                    button: "확인"
                });
            }else{
                dialog.alert({
                    title:'오류',  
                    message: data.msg,
                    button: "확인"
                });
            }
        }    
    });
    // 배경이미비 편집 END

    // $('#form_product').validate({
        
    //     messages: {
    //             required: '필수 입력 항목입니다.'
    //     }
    // });

    //프로필사진 편집
    $('#input_plnr_picture').change(function(){
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#input_plnr_picture_temp').css('background-image', 'url('+e.target.result+')');
                $('#input_plnr_picture_temp').css('background-size', '100% 100%');
            }
            reader.readAsDataURL(this.files[0]);
            $('#form_thumb').submit();
        }else{
            $('#input_plnr_picture_temp').css('background-image', 'url(no-data)');
        }
        
    });
    $('#form_thumb').ajaxForm({
        dataType : "json",
        success: function(data) {
            if(data.state == 1){
                dialog.alert({
                    title:'',  
                    message: '<p class="single-msg">플래너 프로필사진 변경이 완료되었습니다.</p>',
                    button: "확인"
                });
            }else{
                dialog.alert({
                    title:'오류',  
                    message: data.msg,
                    button: "확인"
                });
            }
        }    
    });
    //프로필사진 편집 END

    //소개글 편집
    $('#plnr-popup-desc').keyup(function() {
        changebtnstate();
    });
    var str_name = $("#plnr-popup-desc").val().replace(/(?:\r\n|\r|\n)/g, '<br />');
    $("#check_introtext").html(str_name);
    $('#plnr-nameok').click(function() {
        if (changebtnstate()) {
            $.ajax({
                url: "/api/plnr/pln_desc",
                data: {
                    pln_desc: $('#plnr-popup-desc').val()
                },
                method: "PUT",
                dataType: "json"
            })
            .done(function(res) {
                if (res.state == 1) {
                    $('.plnr-intro').removeClass('is-set');
                    $(this).parents('.popup__inner').removeClass('is-active');
                    $('.popup--modal').delay(200).fadeOut();
                    str_name = $("#plnr-popup-desc").val().replace(/(?:\r\n|\r|\n)/g, '<br />');
                    $("#check_introtext").html(str_name);
                } else {
                    dialog.alert({
                        title:'오류',  
                        message: data.msg,
                        button: "확인"
                    });
                }
            })
            .fail(function(xhr, status, errorThrown) {
                console.log(xhr);
            }) // 
            .always(function(xhr, status) {});
        }
    });
    //소개글 편집 END

    //home 정보 편집
    $('[name=minus-plnr-info]').bind('click',function(){
        $(this).parents('.plnr-desc__input').remove();
        $('#form_pln_home').submit();
    });
    $('.input_pln_home').bind('change',function(){
        $('#form_pln_home').submit();
    });
    $('#form_pln_home').ajaxForm({
        dataType : "json",
        success: function(data) {
            if(data.state == 1){
                
            }else{
                dialog.alert({
                    title:'오류',  
                    message: data.msg,
                    button: "확인",
                    callback: function(value){
                        location.reload();
                    }
                });
            }
        }    
    });
    //home 정보 편집 END

    //상품 정보 삽입 및 편집 START
    //이미지 파일 슬라이드
    $('#input_product_picture').change(function(){
        var num =  0;
        //980미만에서 여러번 이미지 삽입가능
        if($("html").width()>=980){
            prdtBgSwiper.removeAllSlides();
        }
        $(".prdt-bg__edit").click(function(){
            prdtBgSwiper.removeAllSlides();
        })

        for (var i = 0; i < this.files.length; i++) {
            
            var fr = new FileReader();

            fr.onload = function(e) {
                prdtBgSwiper.appendSlide('<li class="prdt-bg__list swiper-slide" style="background-image: url('+e.target.result+');background-size:100% 100%;"></li>');
            }

            fr.readAsDataURL(this.files[i]);
        
        }
        
    });
    //소개
    $('.temp_input_intro').keyup(function(){
        if($('#temp_prd_subtitle').val().length > 3 && $('#temp_prd_title').val().length > 3 && $('#temp_prd_desc').val().length > 3){
            $('#btn_popup_intro').addClass('is-active');
        }else{
            $('#btn_popup_intro').removeClass('is-active');
        }
    });
    $('#btn_popup_intro').click(function(){
        if($(this).hasClass('is-active')){
            $('input[name=prd_subtitle]').val($('#temp_prd_subtitle').val());
            $('input[name=prd_title]').val($('#temp_prd_title').val());
            $('textarea[name=prd_desc]').val($('#temp_prd_desc').val());

            $(this).parents('.popup__inner').removeClass('is-active');
            $('.popup--modal').delay(200).fadeOut();
        }
    });
    //일정
    $("#datepick_02").datepicker({
        minDate: new Date(new Date().valueOf() + 1000 * 3600 * 240),
        language: "en",
        inline: "true",
        onSelect:function(formattedDate, date, inst){
            var str = formattedDate.replace(",", " ~ ");
            var diff = new Date(date[1] - date[0]);
            if(!isNaN(diff)){
                var days = diff/1000/60/60/24;
                str = str + ' ' + days + '박' + (days + 1) + '일';
                $('#btn_popup_schedule').addClass('is-active');
            }else{
                $('#btn_popup_schedule').removeClass('is-active');
            }
            $('#temp_prd_schedule').text(str);
        }    
    });
    $('#btn_popup_schedule').click(function(){
        if($(this).hasClass('is-active')){
            $('input[name=prd_schedule]').val($('#temp_prd_schedule').text());

            $(this).parents('.popup__inner').removeClass('is-active');
            $('.popup--modal').delay(200).fadeOut();
        }
    });
    //제출

    $('#submit_product').click(function(){

        $('#form_product').submit();

    });

    $('#form_product').ajaxForm({
        dataType : "json",
        beforeSubmit: function() {

            $('#form_product').validate();
            $.extend($.validator.messages,{
                required: '필수 항목입니다.'
            })

            //동적추가된 폼들 검증추가
            if(!$('[name ^= prd_slides]').val()){
                dialog.alert({
                    title:'오류',  
                    message: '최소 사진 1장은 넣어주셔야됩니다.',
                    button: "확인"
                });
                return false;
            }else{
                return $('#form_product').valid();
            }
        },
        success: function(data) {
            console.log(data);
            if(data.state == 1){
                dialog.alert({
                    title:'상품 등록',  
                    message: '상품등록이 완료되었습니다.',
                    button: "확인",
                    callback: function(value){
                        if(value){
                            $('#prd-list-wrap').empty();
                            prdStart = 0;
                            loadPrds(prdStart);
                            prdtBgSwiper.removeAllSlides();
                            $('input[name^=prd_]').val('');
                            $('.temp_prd_schedule').val();
                            $('.datepicker--cell').removeClass('-range-from- -range-to- -selected- -focus- -in-range-');
                            popupClose('input_prdt_popup');
                        }
                    }
                });
            }else{
                dialog.alert({
                    title:'오류',  
                    message: data.msg,
                    button: "확인"
                });
            }
        }    
    });
    
    //상품 정보 삽입 및 편집 END
    //포트폴리오 삽입 및 삭제 START
    $('#input_pplio_pic').change(function(){
        var num =  1;
        $('.review__pictures-list').not('.review__pictures-list--file').remove();
        for (var i = 0; i < this.files.length; i++) {
            
            var fr = new FileReader();

            fr.onload = function(e) {
                console.log(e.target.result);
                if(e.target.result.indexOf('video') == -1){
                    $('#pplio_multiple_pic').append('<li class="review__pictures-list" style="background-image: url('+e.target.result+');background-size:100% 100%"></li>');
                }else{
                    $('#pplio_multiple_pic').append('<li class="review__pictures-list" id="pplio_img_'+num+'" style="background-image: url('+e.target.result+');background-size:100% 100%">영상 '+num+' </li>');
                    num++;
                }
                
            }

            fr.readAsDataURL(this.files[i]);

        }
        $('#portf_count').text(this.files.length);
    });
    $('#btn_submit_portfolid').click(function(){
        $('#form_portfolio').submit();
    });
    $('#form_portfolio').ajaxForm({
        dataType : "json",
        beforeSubmit: function() {
            //동적추가된 폼들 검증추가
            if(!$('[name ^= portf_files]').val()){
                dialog.alert({
                    title:'오류',  
                    message: '최소 사진 1장은 넣어주셔야됩니다.',
                    button: "확인"
                });
                return false;
            }else{
                return $('#form_portfolio').valid();
            }
        },
        success: function(data) {
            console.log(data);
            if(data.state == 1){
                dialog.alert({
                    title:'',  
                    message: '포트폴리오 등록이 완료되었습니다.',
                    button: "확인",
                    callback: function(value){
                        if(value){
                            $('.review__pictures-list').not('.review__pictures-list--file').remove();
                            $('#input_pplio_pic').val('');
                            $('input[name=portf_title]').val('');
                            $('#portf_count').text(0);
                            $('.plnr-pplio__group').empty();
                            popupClose('input_pplio_pic_view_popup');
                            profoStart = 0;
                            loadPorfo(profoStart);
                            
                        }
                    }
                });
            }else{
                dialog.alert({
                    title:'오류',  
                    message: data.msg,
                    button: "확인"
                });
            }
        }    
    });
    //포트폴리오 이미지, 영상별 설명
    $('#submit_portf_desc').click(function(){
        var portf_id = $(this).data("porfo");
        var portf_sort = $(this).data("sort");
        var portf_desc = $('#textarea_portf_desc').val();


        $.ajax({
            url: "/api/portfolio/desc",
            data: {
                portf_id: portf_id,
                portf_sort: portf_sort,
                portf_desc: portf_desc
            },
            method: "PUT",
            dataType: "json",
            async:false
        })
        .done(function(res) {
            if (res.state == 1) {
                path[portf_sort].desc = portf_desc;
                $('#pplio_slide_desc').text(portf_desc);
                popupMiniClose('pplio-bubble');
            } else {
                dialog.alert({
                    title:'오류',  
                    message: data.msg,
                    button: "확인"
                });
            }
        })
        .fail(function(xhr, status, errorThrown) {
            console.log(xhr);
        }) // 
        .always(function(xhr, status) {});
    });
    //포트폴리오 list, view, 삽입 및 삭제 END

    //카테고리 탭메뉴 움직임효과
    var plnr_indicatorWidth = $('.plnr-category__list.is-active span').innerWidth();
    var plnr_indicatorposi = $('.plnr-category__list.is-active span').position();

    $('.plnr-category__indicator').css({
        width: plnr_indicatorWidth,
        left: plnr_indicatorposi.left
    })

    

    $('.plnr-category__list').click(function(e){

        $(this).addClass('is-active');
        $(".plnr-category__list")
            .not(this)
            .removeClass("is-active");
        categoryIndicatorMove();

    });
    //end

    //영역을터치하여 정보입력 닫기
    $('#alarm_msg_close').click(function(e){
        $(event.target).parent('.user-msg-alarm').animate({
            top: 0,
            opacity: 0
        })
    })
    //end
  
    //플래너 정보 + 클릭
    $('[name=add-plnr-info]').click(function() {
        var info_input = '<li class="plnr-desc__input mb_10">\
                            <input name="pln_info[]" required="" minlength="2" class="input_pln_home"  name="plnr-info" type="text" placeholder="정보 추가 (ex. 트리픽대학 관광학과 졸업)">\
                            <button type="button" name="minus-plnr-info" class="minus-btn"></button>\
                            </li>';
        $('#plnr-info-wrap').append(info_input);
        $('[name=minus-plnr-info]').unbind();
        $('[name=minus-plnr-info]').bind('click',function(){
            $(this).parents('.plnr-desc__input').remove();
            $('#form_pln_home').submit();
        });
        $('.input_pln_home').unbind();
        $('.input_pln_home').bind('change',function(){
            $('#form_pln_home').submit();
        });

    });

    //플래너 스타일 + 클릭
    $('[name=add-plnr-style]').click(function() {
        var info_input =
            ' <li class="plnr-desc__input">\
                            <input name ="pln_style[]" required="" minlength="2" class="input_pln_home"  name="plnr-style" type="text" placeholder="스타일 추가 (ex. 인생샷 전문가 , 대중교통 전문)">\
                            <button type="button" name="minus-plnr-info" class="minus-btn"></button>\
            </li>';
        $('#plnr-style-wrap').append(info_input);
        $('[name=minus-plnr-info]').unbind();
        $('[name=minus-plnr-info]').bind('click',function(){
            $(this).parents('.plnr-desc__input').remove();
            $('#form_pln_home').submit();
        });
        $('.input_pln_home').unbind();
        $('.input_pln_home').bind('change',function(){
            $('#form_pln_home').submit();
        });

    });

    // 플래너관할구역 + 클릭
    $('[name=add-plnr-juri]').click(function() {
        var info_input =
            ' <li class="plnr-desc__input">\
                <input name ="pln_juri[]" required="" minlength="2"  name="plnr-style" type="text" class="input_pln_home pln_juri" placeholder="거주지 인근 관광지 (ex.이태원, 남포동, 여수 )">\
                            <button type="button" name="minus-plnr-info" class="minus-btn"></button>\
                </li>';
        $('#plnr-juri-wrap').append(info_input);
        initialize();
        $('[name=minus-plnr-info]').unbind();
        $('[name=minus-plnr-info]').bind('click',function(){
            $(this).parents('.plnr-desc__input').remove();
            $('#form_pln_home').submit();
        });
        $('.input_pln_home').unbind();
        $('.input_pln_home').bind('change',function(){
            $('#form_pln_home').submit();
        });

    });


    var prdtBgSwiper = new Swiper ('.prdt-bg-container', {
        grabCursor: true,
        slidesPerView: 'auto',
        pagination: {
            el: '.swiper-pagination',
            type: 'fraction'
        }
    });

    

    

    //홈, 상품, 포폴, 리뷰 클릭
    //각각 탭 로딩
    $('.plnr-category > li').bind('click',function() {
        var tab = $(this).data('tab');
        switch (tab) {
            case 'home':
                break;
            case 'prds':
                loadPrds(prdStart);
                break;
            case 'porfo':
                loadPorfo(profoStart);
                break;
            case 'rvs':
                if ( $('#check_plnr_info_04').prop('checked', true) ){
                    loadRvs(rvStart);
                }
                break;

        }
    });

    // 상품, 포폴, 리뷰 무한스크롤
    $('.wrapper--planner__scroll-area').bind('scroll',function(){
        if($(this).scrollTop() + $(this).innerHeight() == $(this)[0].scrollHeight){
            var tab = $('input:radio[name=check-plnr-category]:checked').val();
            switch (tab) {
                case 'home':
                    break;
                case 'prds':
                    loadPrds(prdStart);
                    break;
                case 'porfo':
                    loadPorfo(profoStart);
                    break;
                case 'rvs':
                    loadRvs(rvStart);
                    break;
            }
        }
    });
    //리뷰 sorting
    $('.plnr-review-hd__select').change(function(){
        rvOrderBy = $(this).children("option:selected").val();
        rvStart = 0;
        $('#rv-wrap').empty();
        loadRvs(rvStart);
    });
    

});

function changebtnstate() {
    if ($('#plnr-popup-desc').val().length > 2) {
        $('#plnr-nameok').addClass('is-active');
        return true;
    }
    $('#plnr-nameok').removeClass('is-active');
    return false;
}

function categoryIndicatorMove() {

    var plnr_indicatorposi = $('.plnr-category__list.is-active span').position();
    var plnr_indicatorWidth = $('.plnr-category__list.is-active span').innerWidth();

    $(".plnr-category__indicator").stop().css({
        left: plnr_indicatorposi.left,
        width: plnr_indicatorWidth
    })
}

//구글 gelocation 사용
function initialize() {
    var inputs = document.getElementsByClassName('pln_juri');
    var options = {
        types: ['geocode'] //this should work !
    }


    for (var i = 0; i < inputs.length; i++) {
        var autocomplete = new google.maps.places.Autocomplete(inputs[i], options);
    }
}

//홈, 상품, 포폴, 리뷰 클릭
//각각 탭 로딩
var prdStart = 0;

function loadPrds(_start) {
    $.ajax({
        url: "/api/product/list_planner",
        data: {
            offset: _start
        },
        method: "GET",
        dataType: "json",
        async:false
    })
    .done(function(res) {
        if (res.state == 1) {
            
            for (var i = 0; i < res.query.length; i++) {
                var tplt_clone = $($('#prd-list-tplt').html());
                var prd = res.query[i];

                if (prd.prd_thumb === 'no-image') {
                    tplt_clone.children('[name=prd-thumb]').css({
                        "background": "url(no-image)"
                    });

                } else {
                    tplt_clone.children('[name=prd-thumb]').css({
                        "background": "url('/storage/fdata/product/slides/" + prd.prd_thumb + " ')", 
                        "background-size":"100% 100%"
                    });
                }

                $(tplt_clone).attr('onclick',"location.href='/pln_ver/product/"+prd.prd_id+"';");

              
                tplt_clone.find('[name=prd-score]').html('' + parseFloat(prd.prd_score).toFixed(2));
                tplt_clone.find('[name=prd-count]').html('(' + prd.prd_count + ')');

                tplt_clone.children('[name=prd-title]').html(prd.prd_title);
                tplt_clone.children('[name=prd-subt]').html(prd.prd_subtitle);

                tplt_clone.show();
                $('#prd-list-wrap').append(tplt_clone);
            }
            prdStart += res.query.length;
            

        } else {
            //회신오류
        }
    })
    .fail(function(xhr, status, errorThrown) {
        console.log(xhr);
    }) // 
    .always(function(xhr, status) {});
}

var profoStart = 0;
var porfoArr = new Array();
var portfoViewSwiper = new Swiper('#portfolio_more_view_container', {
    autoHeight: true, //enable auto height
    spaceBetween: 0,
    slidesPerView: 'auto',
    pagination: {
        el: '.swiper-pagination',
        type: 'fraction',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    }
});
var path;
function loadPorfo(_start) {
    $.ajax({
        url: "/api/portfolio/list",
        data: {
            offset: _start
        },
        method: "GET",
        dataType: "json",
        async:false
    })
    .done(function(res) {
        if (res.state == 1) {
            
            for (var i = 0; i < res.query.length; i++) {
                var tplt_clone = $($('#porfo-tplt').html());
                var portfolio = res.query[i];

                porfoArr[portfolio.portf_id] = portfolio;

                tplt_clone.children('[name=porfo-title]').html(portfolio.portf_title);
                $(tplt_clone).attr('data-porfo', portfolio.portf_id);
                $(tplt_clone).attr('id', 'porfo-' + portfolio.portf_id);

                var media = JSON.parse(portfolio.protfo);
                var mediainfo = "";

                mediainfo += "포트폴리오 : " + media.length + "장";

                $(tplt_clone).find('[name=porfo-info]').text(mediainfo);

                if(media[0].path.indexOf('mp4') == -1 && media[0].path.indexOf('avi') == -1){
                    $(tplt_clone).find('[name=porfo-thumb]').css({
                        "background-image": "url(/storage/fdata/planner/portf/" + media[0].path + ")",
                        "background-size": "100% 100%"    
                    });
                }else{
                    $(tplt_clone).find('[name=porfo-thumb]').html('<video><source src="/storage/fdata/planner/portf/"'+ media[0].path+'"></video>');
                }

                $('#porfo-wrap').append(tplt_clone);
            }
            profoStart += res.query.length;
            $('.plnr-pplio__list').unbind();
            $('.plnr-pplio__list').bind('click',function(){
                var porf_id = $(this).data("porfo");
                $.ajax({
                    url: "/api/portfolio/detail",
                    data: {
                        portf_id: porf_id
                    },
                    method: "GET",
                    dataType: "json"
                })
                .done(function(data) {
                    if (data.state == 1) {
                        var portf_detail = data.query[0];
                        $('#pplio_more_view_popup .hd-title__center b').text(portf_detail.portf_title);
                        $('#submit_portf_desc').data("porfo",porf_id);
                        path = JSON.parse(portf_detail.portf_file);
                        portfoViewSwiper.removeAllSlides();
                        $.each(path,function(index, item){
                            if(index == 0){
                                if(item.desc !== 'no-desc'){
                                    $('#pplio_slide_desc').text(item.desc);
                                    $('#textarea_portf_desc').val(item.desc);
                                    $('#submit_portf_desc').data("sort",0);
                                    
                                }
                            }
                            if(item.path.indexOf('mp4') == -1 && item.path.indexOf('avi') == -1){
                                var portfviewtag = '<li class="more-view__list swiper-slide ">';
                                    portfviewtag += '<img src="/storage/fdata/planner/portf/' + item.path +'" class="swiper" alt="portfolio img">';
                                    portfviewtag += '</li>';
                                portfoViewSwiper.appendSlide(portfviewtag);
                            }else{
                                var portfviewtag = '<li class="more-view__list swiper-slide ">';
                                    portfviewtag += '<video controls="controls" style="width:100%;">';
                                    portfviewtag += '<source src="/storage/fdata/planner/portf/' + item.path +'">';
                                    portfviewtag += '</video>';
                                    portfviewtag += '</li>';
                                portfoViewSwiper.appendSlide(portfviewtag);
                            }
                        });
                        portfoViewSwiper.on('slideChangeTransitionEnd',function(){
                            if(path[this.realIndex].desc !== 'no-desc'){
                                $('#pplio_slide_desc').text(path[this.realIndex].desc);
                                $('#textarea_portf_desc').val(path[this.realIndex].desc);
                            }else{
                                $('#pplio_slide_desc').text('');
                                $('#textarea_portf_desc').val('');
                            }
                            $('#submit_portf_desc').data("sort",this.realIndex);
                        });
                        popupOpen('pplio_more_view_popup');
                        setTimeout(function() {
                            
                            portfoViewSwiper.updateAutoHeight();
                        },100);
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
//리뷰
var rvStart = 0;
var rvArr = new Array();
var rvOrderBy = 'created_at';
var reviewViewSwiper = new Swiper('#review_more_view_container', {
    autoHeight: true, //enable auto height
    spaceBetween: 0,
    slidesPerView: 'auto',
    pagination: {
        el: '.swiper-pagination',
        type: 'fraction',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    }
});
function loadRvs(_start) {
    $.ajax({
        url: "/api/review/planner",
        data: {
            offset: _start,
            orderby: rvOrderBy
        },
        method: "GET",
        dataType: "json",
        async:false
    })
    .done(function(res) {
        if (res.state == 1) {
            rvStart += res.query.length;
            for (var i = 0; i < res.query.length; i++) {
                var tplt_clone = $($('#revw-list-tplt').html());
                var review = res.query[i];
                
                rvArr[review.review_id] = review;

                tplt_clone.attr('data-review', review.review_id);
                tplt_clone.find('[name=rv-usr-name]').html(review.user_nick);
                tplt_clone.find('[name=rv-reg-dt]').html(review.created_at);
                tplt_clone.find('[name=rv-comment]').html(review.revw_content);


                //후기사진추가
                var review_imgs = JSON.parse(review.revw_img);
                if(review_imgs.length == 0){
                    tplt_clone.find('.plnr-review-pic').hide();
                }else{
                    
                    for (var j = 0; j < review_imgs.length; j++) {
                        var imgtag = '<li class="review__pictures-list swiper-slide" style="background-image: url(/storage/fdata/review/planner/' + review_imgs[j].path + ' );min-height:150px;"></li>';
                        tplt_clone.find('[name=rv-img-wrap]').append(imgtag);  
                    }
                    var reviewPicSwiper = new Swiper(tplt_clone.find('.plnr-review-pic'), {
                        slidesPerView: 'auto',
                        spaceBetween: 11,
                        observer : true,
                        observeParents: true
                    });
                }

                var revw_score = review.revw_score.split('.');
                if(revw_score[1] == 0){
                    tplt_clone.find('.rating-stars__group').addClass('is-star-0' + revw_score[0]);
                }else{
                    if(revw_score[0] == 0){
                        tplt_clone.find('.rating-stars__group').addClass('is-star-' + revw_score[0] + '-' + revw_score[1]);
                    }else{
                        tplt_clone.find('.rating-stars__group').addClass('is-star-0' + revw_score[0] + '-' + revw_score[1]);
                    }
                    
                }
                tplt_clone.find('.rating-stars__total').html(review.revw_score);

                $('#rv-wrap').append(tplt_clone);

                $('[name=rv-img-wrap]').unbind();
                $('[name=rv-img-wrap]').bind('click',function(){
                    var review_title_hd = $(this).closest('li').find('[name=rv-usr-name]').text()
                    $('#review_more_view_popup').find('.hd-title__center b').text(review_title_hd + ' 후기사진');
                    reviewViewSwiper.removeAllSlides();
                    $(this).find('li').each(function(){
                        
                        var imgSrc = $(this).css('background-image').split('"')[1];
                        var imgviewtag = '<li class="more-view__list swiper-slide "><img src="'+imgSrc+'" class="swiper" alt="review img"></li>';
                        reviewViewSwiper.appendSlide(imgviewtag);
                        
                    });
                    popupOpen('review_more_view_popup');
                    setTimeout(function() {
                        reviewViewSwiper.updateAutoHeight();
                    });
                    
                });
            }

            
            if(rvStart == 0){
                $('.trip-review__list--nothing').show();
            }else{
                $('.trip-review__list--nothing').hide();
            }

        } else {
            //회신오류
        }
    })
    .fail(function(xhr, status, errorThrown) {
        console.log(xhr);
    }) // 
    .always(function(xhr, status) {});
}

</script>
@endsection