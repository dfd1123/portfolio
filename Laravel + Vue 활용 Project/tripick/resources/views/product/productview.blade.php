@extends('layouts.app')

@section('content')


<div class="wrapper wrapper--product">
    <div class="wrapper--product__scroll-area">
        <div class="prdt-bg">
            <button type="button" class="hd-title__left-btn hd-title__left-btn--prev-wh" onClick="history.back()">
                <span class="none">이전버튼</span>
            </button>
            <figure class="prdt-bg-container swiper-container">
                <ul class="prdt-bg__group swiper-wrapper">
                    @if(json_decode($product->prd_slides, true))
                        @forelse(json_decode($product->prd_slides)->path as $item)
                        <li class="prdt-bg__list swiper-slide" style="background-image: url( /storage/fdata/product/slides/{{$item}} );background-size:100% 100%"></li>
                        @empty
                        <li class="prdt-bg__list swiper-slide" style="background-image: url(no-data)"></li>
                        @endforelse
                    @endif
                </ul>
            </figure>
            <div class="prdt-bg__pagination">
                <div class="swiper-pagination"></div>
            </div>
            <input type="file" id="input_product_picture" class="none-input"/>
            <div class="prdt-bg__button">
                <label for="input_product_picture"></label>
            </div>
        </div>

        <div class="wrapper--inner">
            <div class="prdt-main-info">
                <input type="hidden" id="hidden_prd_id" name="prd_id" value="{{ $product->prd_id }}">
                <span class="prdt-main-info__etc">{{ $product->prd_subtitle }}</span>
                <p class="prdt-main-info__tit">
                    {{ $product->prd_title }}
                </p>
                <pre class="prdt-main-info__text">{{ $product->prd_desc }}</pre>
            </div>

            <div class="prdt-sub-info">
                <fieldset class="prdt-main-info__list">
                    <legend class="prdt-sub-info__tit prdt-sub-info__tit--01">코스</legend>
                    <span class="prdt-sub-info__span">{{ $product->prd_course }}</span>
                </fieldset>
                <fieldset class="prdt-main-info__list">
                    <legend class="prdt-sub-info__tit prdt-sub-info__tit--02">일정</legend>
                    <span class="prdt-sub-info__span">{{ $product->prd_schedule }}</span>
                </fieldset>
                <fieldset class="prdt-main-info__list">
                    <legend class="prdt-sub-info__tit prdt-sub-info__tit--03">만나는 장소 및 시간</legend>
                    <span class="prdt-sub-info__span">{{ $product->prd_place_time }}</span>
                </fieldset>
            </div>

            <div class="prdt-review-rating">
                <div class="rating-stars__group is-star-01">
                    <div class="rating-stars__stars">
                        <svg class="star-svg" id="star-01" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25">
                            <path
                                class="star-svg__path star--left"
                                d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z"
                                transform="translate(-0.006 0)"
                            />
                            <path
                                class="star-svg__path star--right"
                                d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z"
                                transform="translate(-19.499 0)"
                            />
                        </svg>
                    </div>
                    <span class="rating-stars__average"><b>{{ number_format($product->prd_score,2) }}</b>/5</span>
                </div>
                <a onClick="popupOpen('prdt_review_btn_popup')" href="#" role="button" class="prdt-review-rating__button"><em>{{ $product->prd_count }}</em>개 후기 자세히 보기</a>
            </div>
        </div>
    </div>
    <div class="wrapper--product__btn">
        <button type="button" class="button" onclick="location.href='/msg/view?prd_id={{$product->prd_id}}';">플래너 문의</button>
    </div>
</div>

<div class="popup popup--review" id="prdt_review_btn_popup">
    <button onClick="popupClose('prdt_review_btn_popup')" type="button" class="hd-title__right-btn hd-title__right-btn--close">
        <span class="none">닫기버튼</span>
    </button>
    <div class="popup--review__hd">
        <h3 class="popup--review__tit">여행상품 후기 <em>{{ $product->prd_count }}</em>개</h3>
        <div class="rating-stars__group is-star-04-5">
            <div class="rating-stars__stars">
                <svg class="star-svg" id="star-01" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25">
                    <path
                        class="star-svg__path star--left"
                        d="M10.235,1.1,8.056,5.516A1.974,1.974,0,0,1,6.569,6.6L1.7,7.3A1.974,1.974,0,0,0,.6,10.672l3.526,3.437A1.974,1.974,0,0,1,4.7,15.857L3.863,20.71a1.974,1.974,0,0,0,2.864,2.081L11.087,20.5a1.974,1.974,0,0,1,.919-.227V0a1.951,1.951,0,0,0-1.77,1.1Z"
                        transform="translate(-0.006 0)"
                    />
                    <path
                        class="star-svg__path star--right"
                        d="M42.9,10.672A1.974,1.974,0,0,0,41.809,7.3L36.935,6.6a1.974,1.974,0,0,1-1.486-1.08L33.27,1.1A1.951,1.951,0,0,0,31.5,0V20.273a1.972,1.972,0,0,1,.919.227l4.359,2.292a1.974,1.974,0,0,0,2.864-2.081l-.832-4.854a1.974,1.974,0,0,1,.568-1.747Z"
                        transform="translate(-19.499 0)"
                    />
                </svg>
            </div>
            <span class="rating-stars__average"><b>{{ number_format($product->prd_score,2) }}</b>/5</span>
        </div>
    </div>
    <div class="popup--review__scroll-area">
        <ul class="popup--review__group" id="review_lists">
            @forelse($reviews as $review)
            <li class="popup--review__list">
                <div class="popup--review__list__hd user-list">
                    <figure class="user-list__thum" style="background-image: url({{ $review->user_thumb }});"></figure>
                    <h5 class="user-list__name">{{ $review->name }}</h5>
                    <span class="user-list__date">{{ $review->created_at }}</span>
                </div>
                <div class="popup--review__list__con">
                    <p class="popup--review__list__text">{{ $review->revw_content }}</p>
                </div>
            </li>
            @empty
            <li class="popup--review__list">
                존재하는 리뷰가 없습니다.
            </li>
            @endforelse
        </ul>
    </div>
    <div class="popup--review__btn">
        <button type="button" class="button">플래너 문의</button>
    </div>
</div>
<template id="tmpl_revw_li">
    <li class="popup--review__list">
        <div class="popup--review__list__hd user-list">
            <figure class="user-list__thum" name="user_thumb" style="background-image: url(img/example/profile-sk.jpg);"></figure>
            <h5 class="user-list__name" name="user_name">여행이좋다</h5>
            <span class="user-list__date" name="created_at">2018.07.03</span>
        </div>
        <div class="popup--review__list__con">
            <p class="popup--review__list__text" name="revw_content">에너지 넘치는 슬기씨랑 함께 여행하니 제가 낸 돈으로 뽕을 제대로 뽑았어요. 단점은 너무 에너지가 넘치시는게 제 체력이 따라가질 못했었네요.^^;;;;;</p>
        </div>
    </li>
</template>
@include('nav.nav_user')

@endsection

@section('script')
<script>
$(function(){
    
    //여행상품 사진리스트보기
    var prdtBgSwiper = new Swiper ('.prdt-bg-container', {
        grabCursor: true,
        slidesPerView: 'auto',
        pagination: {
            el: '.swiper-pagination',
            type: 'fraction'
        }
    })
    //end
    var prd_id = $('#hidden_prd_id').val();
    var revwStart = 10;
    $('.popup--review__scroll-area').bind('scroll', function(){
        if($(this).scrollTop() + $(this).innerHeight() == $(this)[0].scrollHeight){
            $.ajax({
                url: "/api/review/product",
                data: {
                    offset: revwStart,
                    prd_id: prd_id
                },
                method: "GET",
                dataType: "json"
            })
            .done(function(res) {
                if (res.state == 1) {

                    for (var i = 0; i < res.query.length; i++) {
                        var tplt_clone = $($('#tmpl_revw_li').html());
                        var revw = res.query[i];

                        if (revw.user_thumb === 'no-img') {
                            tplt_clone.find('[name=user_thumb]').css({
                                "background-image": "url(no-image)"
                            });
                        } else {
                            tplt_clone.find('[name=user_thumb]').css({
                                "background-image": "url(/storage/" + revw.user_thumb + ")", 
                                "background-size":"100% 100%"
                            });
                        }


                    
                        tplt_clone.find('[name=user_name]').html(revw.name);
                        tplt_clone.find('[name=created_at]').html(revw.created_at);
                        tplt_clone.find('[name=revw_content]').html(revw.revw_content);

                        $('#review_lists').append(tplt_clone);
                    }
                    revwStart += res.query.length;

                } else {
                    //회신오류
                }
            })
            .fail(function(xhr, status, errorThrown) {
                console.log(xhr);
            }) // 
            .always(function(xhr, status) {});
            
        }
    });
    //리뷰 무한스크롤 더보기 END
    
    var str_name = $('.prdt-main-info__text').html().replace(/(?:\r\n|\r|\n)/g, '<br />');
    $('.prdt-main-info__text').html(str_name);

})
</script>
@endsection