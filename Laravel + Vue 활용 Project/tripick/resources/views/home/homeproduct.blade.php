@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--home wrapper--home-trip">
    <div class="hd-title hd-title--01">
        <button type="button" onClick="history.back()" class="hd-title__left-btn hd-title__left-btn--prev"><span class="none">이전버튼</span></button>
        <h2 class="hd-title__center">
            <img src="/img/logo/logo-word-tripik-navy.svg" alt="header logo" class="hd-logo">
        </h2>
    </div>

    <div class="wrapper--home-trip__scroll-area">

        <div class="recom-trip-special home-trip-contents">
            <h3 class="home-trip-contents__tit">트리픽 특별상품</h3>
            <div class="swiper-container trip-product-container">
                <ul class="trip-product__group swiper-wrapper">
                    @forelse($products as $product)
                    <li class="trip-product__list swiper-slide" onclick="location.href='/product/{{ $product->prd_id }}'">
                        <figure class="trip-product__thum" style="background-image: url(/storage/fdata/product/thumb/{{ $product->prd_thumb }});"></figure>
                        <span class="trip-product__etc">{{ $product->prd_subtitle }}</span>
                        <p class="trip-product__tit">{{ $product->prd_title }}</p>
                        <div class="rating-stars__group is-star-01">
                            <div class="rating-stars__stars">
                                <svg class="star-svg" id="star-01" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25">
                                    <path class="star-svg__path star--left" d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z" transform="translate(-0.006 0)"/>
                                    <path class="star-svg__path star--right" d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z" transform="translate(-19.499 0)"/>
                                </svg>
                            </div>
                            <span class="rating-stars__total rating-stars__total--mini">{{ number_format($product->prd_score,2) }}</span>
                            <span class="rating-stars__total rating-stars__total--mini">({{ $product->prd_count }})</span>
                        </div>
                    </li>
                    @empty
                    <li class="trip-product__list swiper-slide">
                        현재 트리픽 특별상품이 없습니다.
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="recom-trip-knowledge home-trip-contents">
            <h3 class="home-trip-contents__tit">트리픽, 추천 여행지식</h3>
            <div class="recom-trip-knlg-container swiper-container">
                <ul class="recom-trip-knlg__group swiper-wrapper">
                    <li class="recom-trip-knlg__list swiper-slide">
                        <div class="recom-trip-knlg__card">
                            <span class="recom-trip-knlg__label"></span>
                            <div class="recom-trip-knlg__con" style="background-image: url(img/example/img-seoul01.jpg);">
                                <h4 class="knlg__con__tit">쇼핑도 여행이다!<br>알고 가야 싸게 산다</h4>
                                <span class="knlg__con__date">19.07.27</span>
                                <p class="knlg__con__text">보고 먹고 즐기는 것만이 여행의 전부는 아니다. 그냥 아무 일 없이 쉬거나 자신만의 주제를 정해 원하는 곳에서 하루를 보내는 것도 여행일 수 있다</p>
                            </div>
                        </div>
                    </li>
                    <li class="recom-trip-knlg__list swiper-slide">
                        <div class="recom-trip-knlg__card">
                            <span class="recom-trip-knlg__label"></span>
                            <div class="recom-trip-knlg__con" style="background-image: url(img/example/img-seoul02.jpg);">
                                <h4 class="knlg__con__tit">쇼핑도 여행이다!<br>알고 가야 싸게 산다</h4>
                                <span class="knlg__con__date">19.07.27</span>
                                <p class="knlg__con__text">보고 먹고 즐기는 것만이 여행의 전부는 아니다. 그냥 아무 일 없이 쉬거나 자신만의 주제를 정해 원하는 곳에서 하루를 보내는 것도 여행일 수 있다</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
</div>
@include('nav.nav_user')
@endsection

@section('script')
<script>
$(function(){
    //트리픽 추천상품 
    var tripProductSwiper = new Swiper ('.trip-product-container', {
        grabCursor: true,
        spaceBetween: 15,
        slidesPerView: 'auto'
    })
    //end

    // 여행지식
    var recomTripSwiper = new Swiper ('.recom-trip-knlg-container', {
        grabCursor: true,
        slidesPerView: 'auto'
    })
    //end
})
</script>
@endsection