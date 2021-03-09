@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--planner">

    <div class="hd-title hd-title--01">
        <button type="button" onClick="history.back()" class="hd-title__left-btn hd-title__left-btn--prev"><span class="none">이전버튼</span></button>
        <h2 class="hd-title__center">플래너 프로필</h2>
    </div>

    <div class="wrapper--planner__scroll-area">
        <input type="hidden" id="pln_id" value="{{ $query->pln_id }}">
        <div class="plnr">
            <div class="plnr-picture">
                <figure class="plnr-picture__bg" style="background-image: url({{'/storage/'.config('filesystems.planner_bg').$query->pln_bg_photo}});"></figure>
                <figure class="plnr-picture__profile" style="background-image: url({{'/storage/'.config('filesystems.planner_thumb').$query->pln_thumb }});">
                    @if($query->pln_score < 1.66)
                    <span class="plnr-grade plnr-grade--03"></span>
                    @elseif($query->pln_score < 3.33)
                    <span class="plnr-grade plnr-grade--02"></span>
                    @else
                    <span class="plnr-grade plnr-grade--01"></span>
                    @endif
                </figure>
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
                <input type="checkbox" id="check_like_plnr" class="none-input" data-favid="{{isset($query->fav_id) ? $query->fav_id : ''}}" {{isset($query->fav_id) ? 'checked' : ''}}>
                <label for="check_like_plnr" class="plnr-like">
                    <span>찜</span>
                </label>
            </div>
            <!-- 견적가격표 -->
            <!-- <div class="plnr-estimate">
                <span class="plnr-estimate__label">플래너 견적</span>
                <b class="plnr-estimate__price">155,000</b><em>원</em>
            </div> -->
            <!-- end -->
            <div class="plnr-intro">
                <p class="plnr-intro__text"> {{ str_replace("\r\n", "<br/>",$query->pln_desc) }} </p>
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

            <div class="plnr-contents plnr-contents--01">

                <h4 class="plnr-contents__tit">인증</h4>

                <!-- 유저입장에서 볼때 -->
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

                <div class="plnr-grade-panel">
                    @if($query->pln_score < 1.66)
                    <span class="plnr-grade plnr-grade--03"></span>
                    @elseif($query->pln_score < 3.33)
                    <span class="plnr-grade plnr-grade--02"></span>
                    @else
                    <span class="plnr-grade plnr-grade--01"></span>
                    @endif
                    <p class="plnr-grade__text">{{ $query->pln_name }} 플래너의 등급은<br><b>{{ $query->pln_grade }}</b>입니다.</p>
                    <button type="button" onclick="location.href='/aboutgrade'" class="plnr-grade-btn">플래너 등급이란?</button>
                </div>

                <h4 class="plnr-contents__tit">정보</h4>
                <div class="plnr-desc">
                    <ul class="plnr-desc__group">
                        @if(json_decode($query->pln_info, true))
                            @forelse( json_decode($query->pln_info, true) as $item)
                            <li class="plnr-desc__list"> {{ $item }}</li>
                            @empty
                            <li class="plnr-desc__list"> 등록된 정보가 없습니다</li>
                            @endforelse
                        @else
                        <li class="plnr-desc__list"> 등록된 정보가 없습니다</li>
                        @endif
                    </ul>
                </div>

                <h4 class="plnr-contents__tit">플래너 스타일</h4>
                <div class="plnr-desc">
                    <ul class="plnr-desc__group">
                        @if(json_decode($query->pln_trip_style, true))
                            @forelse( json_decode($query->pln_trip_style, true) as $item)
                            <li class="plnr-desc__list"> {{ $item }}</li>
                            @empty
                            <li class="plnr-desc__list"> 등록된 정보가 없습니다</li>
                            @endforelse
                        @else
                        <li class="plnr-desc__list"> 등록된 정보가 없습니다</li>
                        @endif
                    </ul>
                </div>

            </div>

            <div class="plnr-contents plnr-contents--02">

                <div class="plnr-trip-product">
                    <ul id="prd-list-wrap" class="trip-product__group"></ul>
                </div>

            </div>

            <div class="plnr-contents plnr-contents--03">

                <h4 id="porfo-cnt" class="plnr-contents__tit">포트폴리오<span class="plnr-contents__amount"></span></h4>

                <div class="plnr-pplio">
                    <ul id="porfo-wrap" class="plnr-pplio__group"></ul>
                </div>

            </div>

            <div class="plnr-contents plnr-contents--04">
                <div class="plnr-review-hd">
                    <span class="plnr-review-hd__tit">리뷰 <em>{{ $query->reviews }}</em>건</span>
                    <div class="rating-stars__group is-star-01">
                        <div class="rating-stars__stars">
                            <svg class="star-svg" id="star-01" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25">
                                <path class="star-svg__path star--left"
                                    d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z"
                                    transform="translate(-0.006 0)" />
                                <path class="star-svg__path star--right"
                                    d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z"
                                    transform="translate(-19.499 0)" />
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
                    <ul id="rv-wrap" class="trip-review__group">
                        <li class="trip-review__list--nothing" style="display:none;">
                            <img src="/img/icon/icon-review-nothing.svg" alt="nothing icon">
                            <span>등록된 리뷰가 없습니다.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    @if($eb_id != 0)
    <div class="wrapper--planner__btn wrapper--planner__btn_02">
        <button type="button" class="button button--ask" onclick="location.href='/msg/view?eb_id={{ $eb_id }}';">메세지 문의하기</button>
    </div>
    @endif


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
<!-- 상품 관련 POPUP, TEMPLATE END -->
<!-- 포트폴리오 관련 POPUP, TEMPLATE START -->
<template id="porfo-tplt">
    <li name="porfo-content" class="plnr-pplio__list">
            <figure name="porfo-thumb" class="plnr-pplio__thum" style="background-image: url(img/example/img-pplio01.jpg);">
                <video style="width:100%;height:100%;">
                    <source>
                </video>
            </figure>
        <p name="porfo-title" class="plnr-pplio__tit">삿포로 여행기</p>
        <span name="porfo-info" class="plnr-pplio__etc">사진 6장</span>
    </li>
</template>
<div class="popup popup--more-view" id="pplio_more_view_popup">

    <div class="hd-title hd-title--01">
        <button type="button" class="hd-title__right-btn hd-title__right-btn--close-wh"
            onClick="popupClose('pplio_more_view_popup')"><span class="none">닫기버튼</span></button>
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

    <div class="pplio-text">
        <p class="pplio-text__paragraph--edit" id="pplio_slide_desc" style="background-image:none;">사진에 대한 설명을 적어주세요.</p>
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
<div class="popup popup--modal">

    <div class="popup__inner popup--review-write" id="review_write_popup">

        <div class="popup--review-write__scroll-area">
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
                <textarea cols="30" rows="10" placeholder="리뷰 내용을 입력해주세요."></textarea>
            </div>

            <div class="review-pup__pictures">
                <input type="file" id="input_review_pic" class="none-input">
                <ul class="review__pictures-group">
                    <li class="review__pictures-list review__pictures-list--file">
                        <label for="input_review_pic"></label>
                        <span class="review__pictures-list__span">+ 사진추가</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="review-pup__btn">
            <button type="button" class="button button--disable">등록하기</button>
        </div>

    </div>

</div>
@include('nav.nav_user')

@endsection

@section('script')
<script>
$(function() {
    //좋아요
    $('#check_like_plnr').change(function(){
        if($(this).prop('checked')){
            $.ajax({
                url: "/api/favorite",
                data: {
                    pln_id: pln_id
                },
                method: "POST",
                dataType: "json"
            })
            .done(function(data) {
                if (data.state == 1) {
                    $('#check_like_plnr').data("favid",data.query[0].fav_id);
                    
                    dialog.alert({
                        title:'알림',  
                        message: '해당 플래너를 찜하였습니다.',
                        button: "확인"
                    });
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
        }else{
            console.log($(this).data("favid"));
            $.ajax({
                url: "/api/favorite/fav",
                data: {
                    pln_id: pln_id,
                    fav_id: $(this).data("favid")
                },
                method: "DELETE",
                dataType: "json"
            })
            .done(function(data) {
                if (data.state == 1) {
                    dialog.alert({
                        title:'알림',  
                        message: '찜을 취소하셨습니다.',
                        button: "확인"
                    });
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
	
    //카테고리 탭메뉴 움직임효과
    var plnr_indicatorWidth = $('.plnr-category__list.is-active span').innerWidth();
    var plnr_indicatorposi = $('.plnr-category__list.is-active span').position();

    $('.plnr-category__indicator').css({
        width: plnr_indicatorWidth,
        left: plnr_indicatorposi.left
    });

    $('.plnr-category__list').click(function() {

        $(this).addClass('is-active');
        $(".plnr-category__list")
            .not(this)
            .removeClass("is-active");
        categoryIndicatorMove();

    }); //end

    //리뷰작성하기팝업: 별점 주기
    $('#stars_group .star-svg').each(function(index) {
        $(this).attr('data-num', index);
    });
    $('#stars_group .star-svg').children('path').click(function(event) {

        $(event.target).parent().children('path').removeClass('on');
        $(event.target).parent().nextAll('.star-svg').children('path').removeClass('on');
        $(event.target).addClass('on').parent('.star-svg').prevAll('.star-svg').children('path')
            .addClass('on');
        $(event.target).prev('.star--left').addClass('on');

        var index_number = $(event.target).parent().attr('data-num');
        var average_value = Number(index_number);

        var star_left_on = $('.star--left').hasClass('on');
        var star_right_on = $(event.target).hasClass('star--right');
        var average = $('#average_num');

        if (star_left_on && star_right_on) {

            average.html((average_value + 1));

        } else {

            average.html(average_value + '.' + 5);

        }

    });
    //end

    //포트폴리오보기 팝업: 사진list swiper
    var pplioViewSwiper = new Swiper('.more-view', {
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
        },
    });
    //end

    // 리뷰볼 때 리뷰사진 리스트
    $('.plnr-category__list:last-of-type').click(function() {
        if ($('#check_plnr_info_04').prop('checked', true)) {
            var reviewPicSwiper = new Swiper('.plnr-review-pic', {
                slidesPerView: 'auto',
                spaceBetween: 11
            })
        }
    });
    //end



    //홈, 상품, 포폴, 리뷰 클릭
    //각각 탭 로딩

    $('.plnr-category > li').click(function() {
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
                loadRvs(rvStart);
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
function categoryIndicatorMove() {

    var plnr_indicatorposi = $('.plnr-category__list.is-active span').position();
    var plnr_indicatorWidth = $('.plnr-category__list.is-active span').innerWidth();

    $(".plnr-category__indicator").stop().css({
        left: plnr_indicatorposi.left,
        width: plnr_indicatorWidth
    })

}
var pln_id = $("#pln_id").val();
var prdStart = 0;

function loadPrds(_start) {
    $.ajax({
        url: "/api/product/list_planner",
        data: {
            offset: _start,
            pln_id: pln_id
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

                $(tplt_clone).attr('onclick',"location.href='/product/"+prd.prd_id+"';");

              
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
            offset: _start,
            pln_id: pln_id
        },
        method: "GET",
        dataType: "json",
        async:false
    })
    .done(function(res) {
        if (res.state == 1) {
            profoStart += res.query.length;
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
                    $(tplt_clone).find('[name=porfo-thumb] video source').attr("src" , "/storage/fdata/planner/portf/" + media[0].path);
                }
                
                $('#porfo-wrap').append(tplt_clone);
            }
            
            $('.plnr-pplio__list').unbind();
            $('.plnr-pplio__list').bind('click',function(){
                var porf_id = $(this).data("porfo");
                $.ajax({
                    url: "/api/portfolio/detail",
                    data: {
                        portf_id: porf_id
                    },
                    method: "GET",
                    dataType: "json",
                    async:false
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
            orderby: rvOrderBy,
            pln_id: pln_id
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
