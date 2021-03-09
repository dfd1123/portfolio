@extends('layouts.app')

@section('content')

<div class="wrapper wrapper--mypage wrapper--mypage-review">
    
    <div class="hd-title hd-title--01">
        <button type="button" onClick="history.back()" class="hd-title__left-btn hd-title__left-btn--prev"><span class="none">이전버튼</span></button>
        <h2 class="hd-title__center">리뷰관리</h2>
    </div>

    <h3 class="mypage_review_tit">내가 쓴 리뷰 모아보기</h3>

    <div class="wrapper--mypage-review__scroll-area">
        <ul class="my_review_history_group" id="review_group">
            @forelse($reviews as $review)
            <li class="trip-review__list">
                <div class="trip-review__list-card">
                    <div class="trip-review__list__hd user-list">
                        <figure class="user-list__thum" style="background-image:url(/storage/{{ $review->user_thumb }})"></figure>
                        <h5 name="rv-usr-name" class="user-list__name">{{ $review->name }}</h5>
                        <span name='rv-reg-dt' class="user-list__date">{{ $review->created_at }}</span>

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
                            <span class="rating-stars__total">{{ number_format($review->revw_score,2) }}</span>
                        </div>
                    </div>
                    <div class="trip-review__list__con" style="margin-bottom:12px;">
                        <p name="rv-comment" class="trip-review__list__text">{{ $review->revw_content }}</p>
                    </div>
                </div>
            </li>
            @empty
            <li class="trip-review__list">
                <div class="trip-review__list-card">
                    작성하신 리뷰가 없습니다.
                </div>
            </li>
            @endforelse
        </ul>
    </div>

</div>
<template id="tplt_review_list">
    <li class="trip-review__list">
        <div class="trip-review__list-card">
            <div class="trip-review__list__hd user-list">
                <figure class="user-list__thum" name="review_thumb" style="background-image:url(/storage/)"></figure>
                <h5 name="rv-usr-name" class="user-list__name"></h5>
                <span name='rv-reg-dt' class="user-list__date"></span>

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
                    <span class="rating-stars__total" name="review_score"></span>
                </div>
            </div>
            <div class="trip-review__list__con" style="margin-bottom:12px;">
                <p name="rv-comment" class="trip-review__list__text">asdfasdfasdf</p>
            </div>
        </div>
    </li>
</template>
@include('nav.nav_user')

@endsection

@section('script')
<script>
$(function(){
    $('.wrapper--mypage-review__scroll-area').bind('scroll',function(){
        if($(this).scrollTop() + $(this).innerHeight() == $(this)[0].scrollHeight){
            loadReviews(ReviewStart);
        }
    });
});
var ReviewStart = 10;
function loadReviews(_start) {
    $.ajax({
        url: "/api/review/user",
        data: {
            offset: _start,
        },
        method: "GET",
        dataType: "json",
        async:false
    })
    .done(function(res) {
        if (res.state == 1) {  
            
            
            for (var i = 0; i < res.query.length; i++) {
                var tplt_clone = $($('#tplt_review_list').html());
                var review = res.query[i];
                
                tplt_clone.find('[name=review_thumb]').css("background-image","url(/storage/"+review.user_thumb+")");
                tplt_clone.find('[name=rv-usr-name]').html(review.name);
                tplt_clone.find('[name=rv-reg-dt]').html(review.created_at);
                tplt_clone.find('[name=review_score]').html(review.revw_score);
                tplt_clone.find('[name=rv-comment]').html(review.revw_content);

                $('#review_group').append(tplt_clone);
            }
            ReviewStart += res.query.length;
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