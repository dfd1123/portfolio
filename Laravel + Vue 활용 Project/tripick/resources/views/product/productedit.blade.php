@extends('layouts.app')

@section('content')

<form id="form_product" method="POST" action="/api/product/update" enctype="multipart/form-data">
<input type="hidden" name="_method" value="put">
<input type="hidden" id="hidden_prd_id" name="prd_id" value="{{ $product->prd_id }}">
<div class="wrapper wrapper--product">
    <div class="wrapper--product__scroll-area">
        <div class="prdt-bg">
            <button type="button" class="hd-title__left-btn hd-title__left-btn--prev-wh" onClick="history.back()"><span class="none">이전버튼</span></button>
            <input type="file" name="prd_slides[]" id="input_product_picture" class="none-input" multiple/>
            <div class="prdt-bg__button" style="z-index:3;">
                <label for="input_product_picture"></label>
            </div>

            <figure class="prdt-bg-container swiper-container">
                <ul class="prdt-bg__group swiper-wrapper">
                    @forelse(json_decode($product->prd_slides)->path as $item)
                    <li class="prdt-bg__list swiper-slide" style="background-image: url( /storage/fdata/product/slides/{{$item}} );background-size:100% 100%"></li>
                    @empty
                    <li class="prdt-bg__list swiper-slide" style="background-image: url(no-data)"></li>
                    @endforelse
                </ul>
            </figure>
            <div class="prdt-bg__pagination">
                <div class="swiper-pagination"></div>
            </div>

        </div>

        <div class="wrapper--inner">
            <div class="prdt-main-info"  id="check_prdt_name" data-name="input_prdt_info_popup">
                <input type="text" name="prd_subtitle" class="prdt-main-info__etc" placeholder="[예시] 자유여행 / 유명맛집" value="{{ $product->prd_subtitle }}" readonly required>
                <input type="text" name="prd_title" style="width:100%;padding-right:0;border:none;" value="{{ $product->prd_title }}" class="prdt-main-info__tit is-set" placeholder="[예시]패키지상품 트리픽 현지 맛집 투어" readonly>
                <!-- <p class="prdt-sub-info__span" name=""></p> -->
                <textarea name="prd_desc" class="prdt-sub-info__span" placeholder="{{ $product->prd_desc }}" readonly required></textarea>
            </div>
            <div class="prdt-sub-info">
                <fieldset class="prdt-main-info__list">
                    <legend class="prdt-sub-info__tit prdt-sub-info__tit--01">
                        코스
                    </legend>
                    <input type="text" name="prd_course" class="prdt-sub-info__span" value="{{ $product->prd_course }}">
                </fieldset>
                <fieldset class="prdt-main-info__list">
                    <legend class="prdt-sub-info__tit prdt-sub-info__tit--02">
                        일정
                    </legend>
                    <div class="prdt-sub-info__input-group" id="input_prdt_period" data-name="input_prdt_period_popup">
                        <input type="text" name="prd_schedule" class="prdt-sub-info__span" placeholder="일정을 입력해주세요." value="{{ $product->prd_schedule }}" readonly required>
                    </div>
                </fieldset>
                <fieldset class="prdt-main-info__list">
                    <legend class="prdt-sub-info__tit prdt-sub-info__tit--03">
                        만나는 장소 및 시간
                    </legend>
                    <input type="text" name="prd_place_time" class="prdt-sub-info__span" value="{{ $product->prd_place_time }}" required>
                </fieldset>
                <fieldset class="prdt-main-info__list">
                    <legend class="prdt-sub-info__tit prdt-sub-info__tit--04">
                        사용방법
                    </legend>
                    <input type="text" name="prd_manual" class="prdt-sub-info__span" value="{{ $product->prd_manual }}" required>
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
                <a  onClick="popupOpen('prdt_review_btn_popup')"
                    href="#"
                    role="button"
                    class="prdt-review-rating__button">
                    <em>{{ $product->prd_count }}</em>개 후기 자세히 보기
                </a>
            </div>
        </div>
    </div>
    <div class="wrapper--product__btn product_view_page_btns">
        <button type="button" class="button" id="submit_product">상품 수정</button>
        <button type="button" class="button button_02" id="btn_delete_product">상품 삭제</button>
    </div>
</div>
</form>

<!-- 상품 관련 POPUP START -->
<div class="popup popup--modal">
    <div class="popup__inner" id="input_prdt_info_popup">
        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>상품소개 입력</h3>
        <div class="popup__inner__field">
            <div class="field__align">
                <span class="inline inline--left">상품의 종류를 입력해주세요.</span>
                <div class="input-group">
                    <input type="text" class="input-group__input temp_input_intro temp_prd_input" id="temp_prd_subtitle" value="{{ $product->prd_subtitle }}" placeholder="예시) 자유여행 / 유명맛집">
                </div>
            </div>
            <div class="field__align">
                <span class="inline inline--left">상품의 제목을 입력해주세요.</span>
                <div class="input-group">
                    <input type="text" class="input-group__input temp_input_intro temp_prd_input" id="temp_prd_title" value="{{ $product->prd_title }}" placeholder="예시) 패키지상품 트리픽 현지 맛집 투어">
                </div>
            </div>
            <div class="popup--product__textarea">
                <span class="inline inline--left">소개글을 입력해주세요.</span>
                <textarea cols="30" rows="10" id="temp_prd_desc" class="temp_input_intro temp_prd_input">{{ $product->prd_desc }}</textarea>
            </div>
            <button type="button" class="button button--disable button--relative is-active mg_tb_20" id="btn_popup_intro">입력완료</button>
        </div>
    </div>

    <div class="popup__inner popup__inner--not-pd" id="input_prdt_period_popup"> 
        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>일정선택</h3>
        <fieldset class="popup__inner__field">
            <div id="datepick_02" class="datepick_02er-here" data-range="true"></div>
            <div class="date-group">
                <span class="inline inline--center temp_prd_input" id="temp_prd_schedule">{{ $product->prd_schedule }}</span>
                <button type="button" class="button button--disable" id="btn_popup_schedule">선택완료</button>
            </div>
        </fieldset>
    </div>
</div>
<!-- 상품 관련 POPUP END -->
<!-- 리뷰 POPUP START -->
<div class="popup popup--review" id="prdt_review_btn_popup">
    <button
        onClick="popupClose('prdt_review_btn_popup')"
        type="button"
        class="hd-title__right-btn hd-title__right-btn--close"
    >
        <span class="none">닫기버튼</span>
    </button>
    <div class="popup--review__hd">
        <h3 class="popup--review__tit">여행상품 후기 <em>{{ $product->prd_count }}</em>개</h3>
        <div class="rating-stars__group is-star-01">
            <div class="rating-stars__stars">
                <svg
                    class="star-svg"
                    id="star-01"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 25 25"
                >
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
            <span class="rating-stars__average"
                ><b>{{ number_format($product->prd_score,2) }}</b>/5</span
            >
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
@include('nav.nav_planner')

@endsection

@section('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
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
    //리뷰 무한스크롤 더보기
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
                dataType: "json",
                async: false
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
                                "background-image": "url('/storage/" + revw.user_thumb + " ')", 
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
    //이미지 파일 슬라이드
    $('#input_product_picture').change(function(){
        var num =  0;
        prdtBgSwiper.removeAllSlides();
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
            
            return $('#form_product').valid();
        },
        success: function(data) {
            console.log(data);
            if(data.state == 1){
                dialog.alert({
                    title:'상품 수정',  
                    message: '상품수정이 완료되었습니다.',
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
    $('#btn_delete_product').click(function(){
        dialog.confirm({
            title:'상품 삭제',  
            message: '상품을 정말 삭제하시겠습니까?',
            button: "예",
            cancel: "아니오",
            callback: function(value){
                if(value){
                    $.ajax({
                        url: "/api/product/state",
                        data: {
                            prd_id: prd_id,
                            state: 1
                        },
                        method: "PUT",
                        dataType: "json"
                    })
                    .done(function(res) {
                        if (res.state == 1) {
                            dialog.alert({
                                title:'알림',  
                                message: '해당 상품이 삭제되었습니다.',
                                button: "확인",
                                callback: function(){
                                    location.href='/pln_ver/profile';
                                }
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
            }
        });
        
    })
})
</script>
@endsection
