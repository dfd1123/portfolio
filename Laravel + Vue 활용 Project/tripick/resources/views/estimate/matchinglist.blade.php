@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--macthing">
                
    <div class="wrapper--macthing__scroll-area" id="current-matching__scroll-area">

        <div class="hd-title hd-title--03">
            <button type="button" onClick="history.back()" class="hd-title__left-btn hd-title__left-btn--prev-wh"><span class="none">이전버튼</span></button>
            <h2 class="hd-title__center">진행중인 매칭현황</h2>
            <div class="matching-hd-title">
                <h3 class="matching-hd-title__h3">{{ $ebs[0]->estm_area }}</h3>
                <span class="matching-hd-title__date">요청일자&nbsp;&nbsp;{{ date("y/m/d", strtotime($ebs[0]->updated_at)) }}</span>
            </div>
            <div class="matching-count-time">
                <button type="button" class="matching-count-time__button" id="btn_call_estmlist">
                    <img src="/img/btn/btn-overview.svg" alt="overview btn">
                    <span>상세</span>
                </button>
                <p class="matching-count-time__p">매칭 마감까지 남은시간</p>
                <span class="matching-count-time__date"><b id="time-hour"></b>시간 <b id="time-min"></b>분</span>
            </div>
        </div>

        <input type="radio" name="matching-category" id="matching_tab_01" class="none-input" value="1" checked>
        <input type="radio" name="matching-category" id="matching_tab_02" class="none-input" value="2">
        <input type="radio" name="matching-category" id="matching_tab_03" class="none-input" value="3">

        <div class="category-tab">
            <label for="matching_tab_01" class="category-tab__list is-active">전체<em>({{ $ebs_count[0]->total_count }})</em></label>
            <label for="matching_tab_02" class="category-tab__list ">개인<em>({{ $ebs_count[0]->per_count }})</em></label>
            <label for="matching_tab_03" class="category-tab__list ">업체<em>({{ $ebs_count[0]->com_count }})</em></label>
            <span class="category-tab__indicator"></span>
        </div>

        <div class="current-matching">
            <div class="current-matching-group current-matching-group--01">
                <ul>
                    @if(isset($ebs[0]->pln_id))
                        @foreach ($ebs as $eb)
                            <li class="matching-product__list {{ $eb->pln_type == 0 ? "pln_personal" : "pln_company" }}" onclick="location.href='/planner/view/{{ $eb->pln_id }}/{{ $eb->eb_id }}';">
                                <div class="matching-product__card">
                                    <figure class="matching-product__card__logo" style="background-image: url(/storage/fdata/planner/thumb/{{ $eb->pln_thumb }});">
                                        <span class="matching-product__card__msg-btn"></span>
                                    </figure>
                                    <dl class="matching-product__card__info">
                                        <dt class="matching-product__card__title"><b>{{ $eb->pln_name }}</b><span class="matching-product__card__status--type">{{ $eb->pln_type == 0 ? "개인" : "업체" }}</span></dt>
                                        <dd class="matching-product__card__desc01">{{$eb->eb_title}}</dd>
                                        <dd class="matching-product__card__desc02">
											<em class="matching-product__card__info--color">기간</em>{{$eb->estm_period}}
											<br>
											<em
											class="matching-product__card__info--color">장소</em>{{$eb->estm_area}}
										</dd>
                                        <dd class="matching-product__card__desc02">
											<em class="matching-product__card__info--color">제안내용</em>{{$eb->eb_desc}}
											<br>
											<em class="matching-product__card__info--color">제안가</em>{{ number_format($eb->estm_asking_price)}}원
										</dd>
                                    </dl>
                                    <span class="rating">{{ number_format($eb->pln_score,2) }}</span>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li class="matching-product__list matching-product__list--nothing">
                            <div class="matching-product__card">
                                <img src="/img/icon/icon-nothing-plnr.svg" class="icon">
                                <span class="caution">현재 견적에 입찰한 플래너가 없습니다.</span>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
            
        </div>

    </div>

</div>

<div class="popup popup--modal">

    <div class="popup__inner" id="view_input_info_popup"> 

        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>여행정보 상세관리</h3>
        
        <div class="trip_input_wrapper">
            <ul class="trip-info" id="estm_list_ul"></ul>
        </div>

    </div>
    
    <div class="popup__inner popup__inner--02" id="view_input_info_detail_popup"> 

        <h3 class="popup__inner__btn-close btn-close-popup--02"><i class="btn-half-circle"></i>내가 입력한 여행정보</h3>
            
        <div class="trip_input_wrapper">

            <div class="trip-info-detail">
                
                <h4 class="trip-info-detail__title">필수 입력사항</h4>
                <ul class="trip-info-detail__ul">
                    <li class="trip-info-detail__list"><em class="trip-info-detail__label">여행지</em><br><br><span id="detail_area"></span></li><br>
                    <li class="trip-info-detail__list"><em class="trip-info-detail__label">날짜</em><br><br><span id="detail_period"></span></li><br>
                    <li class="trip-info-detail__list"><em class="trip-info-detail__label">인원</em><br><br><span id="detail_group_qtt"></span></li><br>
                </ul>

            </div>

            <div class="trip-info-detail">
                
                <h4 class="trip-info-detail__title">선택 입력사항<span class="trip-info-detail__option" id="detail_step4">자유여행</span></h4>
                <ul class="trip-info-detail__ul" id="detail_step5">
                    <li class="trip-info-detail__list"><em class="trip-info-detail__label">어떤 항공 스타일을 원하시나요?</em><br><br><span>미선택</span></li><br>
                    <li class="trip-info-detail__list"><em class="trip-info-detail__label">어떤 숙박 스타일을 원하시나요?</em><br><br><span>리조트</span></li><br>
                    <li class="trip-info-detail__list"><em class="trip-info-detail__label">어떤 이동수단을 원하시나요?</em><br><br><span>차량</span></li><br>
                    <li class="trip-info-detail__list"><em class="trip-info-detail__label">원하시는 식당/레스토랑 스타일은 무엇인가요?</em><br><br><span>유명맛집</span></li><br>
                </ul>

            </div>

            <div class="trip-info-detail__textarea" id="detail_step5_add">
                <textarea cols="30" rows="10"></textarea>
            </div>

        </div>

    </div>

</div>

@include('nav.nav_user')

<template id="estm_list_tmpl">
    <li class="trip-info__list">
        <span class="trip-info__country" id="estm_area_type"></span>
        <dl>
            <dt class="trip-info__place" id="estm_area"></dt>
            <dd class="trip-info__dd">
                <em class="trip-info__dd-label">일정</em><span id="estm_period"></span>
            </dd>
            <dd class="trip-info__dd">
                <em class="trip-info__dd-label">인원</em><span id="estm_group_qtt"></span>
            </dd>
        </dl>
        <div class="trip-info__btns">
            <button type="button" class="button button--gray btn_estm_detail" id="estm_detail_btn">입력정보</button>
            <button type="button" class="button button--main-color" id="estm_eblist_btn" onclick="">추천현황</button>
        </div>
    </li>
</template>

<template id="estm_step5_tmpl">
        <li class="trip-info-detail__list">
            <em class="trip-info-detail__label" id="estm_step5_group"></em>
            <br><br>
            <span id="estm_step5_title">미선택</span>
        </li>
        <br>
</template>

@endsection

@section('script')
<script>
$(function(){
    dailyMissionTimer({{$duration}});

    //카테고리 탭메뉴 움직임효과
    var indicatorWidth = $('.category-tab__list.is-active').innerWidth();

    $('.category-tab__indicator').css({
        width: indicatorWidth
    })

    function categoryIndicatorMove() {

        var indicatorposi = $('.category-tab__list.is-active').position();

        $(".category-tab__indicator").stop().css({
            left: indicatorposi.left
        })

    }

    $('.category-tab__list').click(function(){
        var labelID = $(this).attr('for');
        if($('#'+labelID).val() == 2){
            $('.pln_personal').removeClass('none-input');
            $('.pln_company').addClass('none-input');
        }else if($('#'+labelID).val() == 3){
            $('.pln_personal').addClass('none-input');
            $('.pln_company').removeClass('none-input');
        }else{
            $('.pln_personal').removeClass('none-input');
            $('.pln_company').removeClass('none-input');
        }
        $(this).addClass('is-active');
        $(".category-tab__list")
            .not(this)
            .removeClass("is-active");
        categoryIndicatorMove();

    });
    //end

    //스크롤내리면 메뉴 바뀜
    var ww = $(window).width();

    if( ww < 1025 ){

        $('#current-matching__scroll-area').scroll(function(){
            var nowScrl = $('#current-matching__scroll-area').scrollTop();
            if( nowScrl > 80 ){
                $(this).addClass('is-active');
            }else{
                $(this).removeClass('is-active');
            }

        })
    }
    //end

    //견적 리스트 보는 모달
    $('#btn_call_estmlist').click(function(){
        
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/api/estimate/list_mine",
            success: function(data){
                $('#estm_list_ul').empty();
                
                $.each(data.query, function(index, item){
                    var templete = $($('#estm_list_tmpl').html());
                    if(item.estm_area_type == 0){
                        templete.find('#estm_area_type').text('국내');
                    }else{
                        templete.find('#estm_area_type').text('해외');
                    }
                    templete.find('#estm_area').text(item.estm_area);
                    templete.find('#estm_period').text(item.estm_period);
                    templete.find('#estm_group_qtt').text(item.estm_group_qtt);
                    templete.find('#estm_detail_btn').data("estm_id",item.estm_id);
                    templete.find('#estm_eblist_btn').attr('onclick',"location.href='/estimate/match/" + item.estm_id + "';");
                
                    $('#estm_list_ul').append(templete);
                });

                //해당 견적 상세정보 보는 모달
                $('.btn_estm_detail').bind('click',function(){
                    var estm_id = $(this).data("estm_id");
                    var param = {
                        'estm_id' : estm_id,
                    };
                    $.ajax({
                        type: "GET",
                        data: param,
                        dataType: "json",
                        url: "/api/estimate/estimate_detail",
                        success: function(data){
                            var step5 = JSON.parse(data.query[0].estm_step5);
                            $('#detail_step5').empty();
                            $('#detail_area').text(data.query[0].estm_area);
                            $('#detail_period').text(data.query[0].estm_period);
                            $('#detail_group_qtt').text(data.query[0].estm_group_qtt);
                            
                            if(data.query[0].estm_step4 == null){
                                $('#detail_step4').text('미선택');
                            }else{
                                $('#detail_step4').text(data.query[0].estm_step4);
                            }
                            if(data.query[0].estm_step5_add == null){
                                $('#detail_step5_add').css('display','none');
                            }else{
                                $('#detail_step5_add').css('display','block');
                                $('#detail_step5_add textarea').val(data.query[0].estm_step5_add);
                            }

                            $.each(step5, function(index, item){
                                var templete = $($('#estm_step5_tmpl').html());
                                templete.find('#estm_step5_group').text(item.estm_group);
                                templete.find('#estm_step5_title').text(item.estm_title);

                                $('#detail_step5').append(templete);
                            });
                            
                            
                        }
                    });
                    $('.popup--modal').fadeIn();
                    $('#view_input_info_detail_popup').addClass('is-active');
                });
            }
        });

        $('.popup--modal').fadeIn();
        $('#view_input_info_popup').addClass('is-active');
    });

});
function dailyMissionTimer(duration) {
    
    var timer = duration;
    var hours, minutes, seconds;
    
    var interval = setInterval(function(){
        hours	= parseInt(timer / 3600, 10);
        minutes = parseInt(timer / 60 % 60, 10);
        seconds = parseInt(timer % 60, 10);
		
        hours 	= hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
		
        $('#time-hour').text(hours);
        $('#time-min').text(minutes);

        if (--timer < 0) {
            timer = 0;
            clearInterval(interval);
        }
    }, 1000);
}
</script>
@endsection