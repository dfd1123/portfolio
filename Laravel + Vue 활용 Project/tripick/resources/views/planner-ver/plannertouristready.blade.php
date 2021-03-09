@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--plnr-nav01">

    <div class="hd-title hd-title--04">
        <h2 class="hd-title__center">여행객 견적현황</h2>
        <!-- <button class="top-right">MY</button> -->
        <div class="hd-title__sch">
            <input type="text" id="search_keyword" placeholder="여행지 검색">
        </div>
        <button type="button" class="top-right hd-title__right-btn hd-title__right-btn--my"><span
                class="none">마이버튼</span></button>
    </div>

    <div class="wrapper--plnr-nav01__scroll-area">
        <div class="plnr-nav01__now plnr-nav01">
            <ul class="plnr-nav01__now__group" id="estimates_ul">
                @forelse($estms as $estm)
                <li class="plnr-nav01__now__list">
                    <div class="plnr-nav01__now__profile">
                        <figure class="plnr-nav01__thum" style="background-image: url(/storage/fdata/user/thumb/{{ $estm->user_thumb }});"></figure>
                        <div class="plnr-nav01__now__date">
                            <span class="plnr-nav01__count-label">남은시간</span>
                            <span
                                class="plnr-nav01__count">{{ date("h시간 i분", (86400 - (time() - strtotime($estm->updated_at)))) }}</span>
                        </div>
                    </div>

                    <div class="plnr-nav01__now__info">
                        <dl class="plnr-nav01__now__info-group">
                            <dt class="plnr-nav01__name">{{ $estm->name }}</dt>
                            <dd class="plnr-nav01__detail plnr-nav01__detail--region"><em
                                    class="plnr-nav01__label">여행지</em><span
                                    class="plnr-nav01__span">{{ $estm->estm_area }}</span></dd>
                            <dd class="plnr-nav01__detail"><em
                                    class="plnr-nav01__label">일정</em><span>{{ $estm->estm_period }}</span></dd>
                            <dd class="plnr-nav01__detail"><em
                                    class="plnr-nav01__label">인원</em><span>{{ $estm->estm_group_qtt }}</span></dd>
                            <dd class="plnr-nav01__detail plnr-nav01__detail--budget"><em
                                    class="plnr-nav01__label">예산</em><span
                                    class="plnr-nav01__span">{{ $estm->estm_budget_max != 0 ? number_format($estm->estm_budget_max)."원 이하" : "무관" }}</span>
                            </dd>
                        </dl>
                        <p class="plnr-nav01__now__more">
                            <a href="#" role="button" data-id="{{ $estm->estm_id }}" class="plnr-nav01__now__button">자세히
                                보기</a>
                        </p>
                    </div>
                </li>
                @empty
                <li class="plnr-nav01__now__list plnr-nav01__now__list--nothing">
                    <img src="/img/icon/icon-nothing-plnr.svg" class="icon">
                    <span class="caution">진행중인 견적이 없습니다.</span>
                </li>
                @endforelse
            </ul>
        </div>
    </div>

</div>
<div class="popup popup--plnr-nav01" id="msg_store_view_popup">

    <div class="hd-title hd-title--01">
        <button type="button" onClick="popupClose('msg_store_view_popup')"
            class="hd-title__left-btn hd-title__left-btn--prev is-not-edit"><span class="none">이전버튼</span></button>
        <!-- 편집모드 누르면 wrapper--msg-store에 is-edit-mode 클래스추가 -->
        <h2 class="hd-title__center is-not-edit">나의 입찰 견적</h2>
    </div>

    <div class="popup--plnr-nav01__scroll-area">
        <p class="sub-ment">유저가 다른 플래너를 낙찰하면 견적이 안보일 수 있습니다</p>
        <div class="plnr-nav01__desc plnr-nav01">
            <ul class="user-message__group" id="my-list">
                @forelse($ebs as $eb)
                <li class="plnr-nav01__desc-panel" style="padding: 1.5rem 2rem;">
                    <div class="plnr-nav01__now__info">
                        <dl class="plnr-nav01__now__info-group">
                            <dt class="plnr-nav01__name">{{ $eb->name }}</dt>
                            <dd class="plnr-nav01__detail plnr-nav01__detail--region"><em
                                    class="plnr-nav01__label">여행지</em><span
                                    class="plnr-nav01__span">{{ $eb->estm_area }}</span></dd>
                            <dd class="plnr-nav01__detail"><em
                                    class="plnr-nav01__label">일정</em><span>{{ $eb->estm_period }}</span></dd>
                            <dd class="plnr-nav01__detail"><em
                                    class="plnr-nav01__label">인원</em><span>{{ $eb->estm_group_qtt }}</span></dd>
                            <dd class="plnr-nav01__detail"><em
                                    class="plnr-nav01__label">예산</em><span>{{ $eb->estm_budget_max != 0 ? number_format($eb->estm_budget_max)."원 이하" : "무관" }}</span>
                            </dd>
                            <dd class="plnr-nav01__detail plnr-nav01__detail--budget"><em class="plnr-nav01__label">입찰
                                    금액</em><span class="plnr-nav01__span">{{ number_format($eb->estm_asking_price) }}
                                    원</span></dd>
                        </dl>
                    </div>
                </li>
                @empty
                <li class="plnr-nav01__now__list plnr-nav01__now__list--nothing">
                    <img src="/img/icon/icon-nothing-plnr.svg" class="icon">
                    <span class="caution">진행중인 입찰건이 없습니다.</span>
                </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
<div class="popup popup--plnr-nav01" id="plnr_nav01_desc_view_popup">

    <div class="hd-title hd-title--04">
        <button type="button" onClick="popupClose('plnr_nav01_desc_view_popup')"
            class="hd-title__left-btn hd-title__left-btn--prev"><span class="none">이전버튼</span></button>
        <h2 class="hd-title__center">여행객 상세내용</h2>
    </div>

    <div class="popup--plnr-nav01__scroll-area">
        <div class="plnr-nav01__desc plnr-nav01">
            <div class="plnr-nav01__desc-panel">
                <div class="plnr-nav01__desc__profile">
                    <figure class="plnr-nav01__thum" id="detail_userthumb"
                        style="background-image: url(img/example/profile-kmh.png);">

                    </figure>
                    <div class="plnr-nav01__desc__date">
                        <h4 class="plnr-nav01__name" id="detail_username"></h4>
                        <span class="plnr-nav01__count-label">남은시간</span>
                        <span class="plnr-nav01__count" id="detail_remaintime"></span>
                    </div>
                </div>
                <div class="plnr-nav01__now__map" id="area_map">
                    <!-- 지도 들어가야할 공간 -->
                </div>
                <div class="plnr-nav01__desc__info">
                    <dl class="plnr-nav01__desc__info-group">
                        <dd class="plnr-nav01__detail plnr-nav01__detail--region"><em
                                class="plnr-nav01__label">여행지</em><span class="plnr-nav01__span" id="detail_area"></span></dd>
                        <dd class="plnr-nav01__detail"><em class="plnr-nav01__label">일정</em><span
                                id="detail_period"></span></dd>
                        <dd class="plnr-nav01__detail"><em class="plnr-nav01__label">인원</em><span
                                id="detail_group_qtt"></span></dd>
                        <dd class="plnr-nav01__detail"><em class="plnr-nav01__label">예산</em><span
                                class="plnr-nav01__span" id="detail_budget"></span></dd>
                    </dl>
                    <dl class="plnr-nav01__desc__info-group">
                        <dd class="plnr-nav01__detail plnr-nav01__detail--option" id="detail_step">
                            <em class="plnr-nav01__label">선택<br>사항</em>
                            <span class="plnr-nav01__span">세미패키지 / 패키지 여행</span>
                            <span class="plnr-nav01__span--op">- 항공 스타일 : 직항</span>
                            <span class="plnr-nav01__span--op">- 숙박 스타일 : 호텔 </span>
                            <span class="plnr-nav01__span--op">- 음식 스타일 : 길거리 음식</span>
                            <span class="plnr-nav01__span--op">- 추가 경험 요청 : 이색 체험</span>
                        </dd>
                    </dl>
                    <dl class="plnr-nav01__desc__info-group">
                        <dd class="plnr-nav01__detail plnr-nav01__detail--option" id="detail_step_add">
                            <em class="plnr-nav01__label">전달<br>사항</em>
                            <span class="plnr-nav01__span">해외 여행은 처음이고, 자유롭게 돌아다니고 싶습니다. 예산이 적은편이라 패키지여행 상품을 통해 저렴하고 가성비
                                좋은 여행을 하고 싶은데 추천부탁드릴게요!</span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper--plnr-nav01__btn">
        <button type="button" class="button" id="request_pay" data-name="request_pay_popup">금액 제안하기</button>
    </div>

</div>
<div class="popup popup--modal">

    <div class="popup__inner" id="request_pay_popup">

        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>결제 요청</h3>

        <fieldset class="popup__inner__field">

            <span class="inline inline--left">추천 상품 금액을 입력해주세요.</span>

            <span class="request-pay__input-group">
                <input type="text" id="estm_asking_price" class="request-pay__input" placeholder="금액을 입력해주세요."
                    onkeyup="inputNumberFormat(this)">
                <span class="request-pay__unit">원</span>
            </span>


            <span class="request-pay__input-group_null">
                <input type="text" id="estm_title" class="request-pay__input" placeholder="상품명(30자이내)">
                <span class="request-pay__unit"></span>
            </span>
            <span class="request-pay__input-group_null">
                <input type="text" id="estm_desc" class="request-pay__input" placeholder="상품 간략설명(50자이내)">
                <span class="request-pay__unit"></span>
            </span>
            <span class="tourist_ready_caution">
                <b>[주의사항]</b>
                <span>여행객이 24시간 내로 해당 요청 금액에 대해 처리하지 않거나 회신이 없으면 자동 취소 처리 됩니다.</span>
            </span>

            <button type="button" class="button button--disable button--relative mg_tb_20" id="btn_suggest_budget" data-id="">입력완료</button>

        </fieldset>

    </div>

</div>
<template id="estimate_lists">
    <li class="plnr-nav01__now__list">
        <div class="plnr-nav01__now__profile">

            <figure class="plnr-nav01__thum" name="tplt_user_thumb">
            </figure>
            <div class="plnr-nav01__now__date">
                <span class="plnr-nav01__count-label">남은시간</span>
                <span class="plnr-nav01__count" name="tplt_remain_time"></span>
            </div>
        </div>
        <div class="plnr-nav01__now__info">
            <dl class="plnr-nav01__now__info-group">
                <dt class="plnr-nav01__name" name="tplt_user_name"></dt>
                <dd class="plnr-nav01__detail plnr-nav01__detail--region"><em class="plnr-nav01__label">여행지</em><span
                        class="plnr-nav01__span" name="tplt_estm_area"></span></dd>
                <dd class="plnr-nav01__detail"><em class="plnr-nav01__label">일정</em><span
                        name="tplt_estm_period"></span></dd>
                <dd class="plnr-nav01__detail"><em class="plnr-nav01__label">인원</em><span
                        name="tplt_estm_group_qtt"></span></dd>
                <dd class="plnr-nav01__detail plnr-nav01__detail--budget"><em class="plnr-nav01__label">예산</em><span
                        class="plnr-nav01__span" name="tplt_estm_budget_max"></span></dd>
            </dl>
            <p class="plnr-nav01__now__more">
                <a href="#" role="button" name="tplt_btn_estm_id" data-id="" class="plnr-nav01__now__button">자세히 보기</a>
            </p>
        </div>
    </li>
</template>
@include('nav.nav_planner')
@endsection

@section('script')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCpIgX3qFRixpEox5kUaJkXlxslRZKmxWs"></script>
<script>
    function inputNumberFormat(obj) {
        obj.value = comma(uncomma(obj.value));
    }

    function comma(str) {
        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }

    function uncomma(str) {
        str = String(str);
        return str.replace(/[^\d]+/g, '');
    }

    function replaceAll(str, searchStr, replaceStr) {
        return str.split(searchStr).join(replaceStr);
    }
    $(function () {
        //나의 입찰 견적
        $('.top-right').click(function () {
            popupOpen('msg_store_view_popup');
        });
        //자세히보기 클릭시
        $('.plnr-nav01__now__button').click(function () {
            var estm_id = $(this).data("id");
            call_estm_detail(estm_id);

        });
        //금액제안하기
        $('#estm_asking_price').keyup(function () {
            var input_price = $(this).val();
            if (input_price.length > 4) {
                $('#btn_suggest_budget').addClass('is-active');
            } else {
                $('#btn_suggest_budget').removeClass('is-active');
            }
        });
        $('#btn_suggest_budget').click(function () {
            if ($('#btn_suggest_budget').hasClass('is-active')) {
                var estm_id = $(this).data("id");
                var max_budget = $(this).data("budget");
                var input_price = replaceAll($('#estm_asking_price').val(), ',', '');

                var eb_title = $('#estm_title').val();
                var eb_desc = $('#estm_desc').val();


                //변수들 유효성검사하기.

                dialog.confirm({
                    title: '알림',
                    message: '해당 금액으로 제시하시겠습니까?',
                    cancel: '아니오',
                    button: "예",
                    callback: function (value) {
                        if (value) {
                            $.ajax({
                                    url: "/api/estimate_bid",
                                    data: {
                                        'estm_id': estm_id,
                                        'estm_asking_price': input_price,
                                        'eb_title': eb_title,
                                        'eb_desc': eb_desc
                                    },
                                    method: "POST",
                                    dataType: "json"
                                })
                                .done(function (data) {
                                    //유저에게 견적서 입찰 알림push
                                    var param = {
                                        'req': 'toUser',
                                        'push_title': 'Tripick',
                                        'push_content': '등록하신 \'추천\'에 플래너가 입찰했어요!',
                                        'estm_id': estm_id
                                    };
                                    $.ajax({
                                        method: "POST",
                                        data: param,
                                        dataType: 'json',
                                        url: '/api/push',
                                        success: function (data) {
                                            console.log(data);
                                        },
                                        error: function (e) {
                                            console.log(e);
                                        }
                                    });
                                    setTimeout(function () {
                                        if (data.state == 1) {
                                            dialog.alert({
                                                title: '알림',
                                                message: '해당 견적에 입찰하셨습니다.',
                                                button: "확인",
                                                callback: function (
                                                    value2) {
                                                    location.reload();
                                                }
                                            });
                                        } else {
                                            dialog.alert({
                                                title: '오류',
                                                message: data.msg,
                                                button: "확인"
                                            });
                                        }
                                    }, 800);

                                })
                                .fail(function (xhr, status, errorThrown) {
                                    console.log(xhr);
                                }) // 
                                .always(function (xhr, status) {});
                        }
                    }
                });

            } else {
                dialog.alert({
                    title: '알림',
                    message: '최소 10000원 이상의 금액을 입력하셔야됩니다.',
                    button: "확인"
                });
            }
        });
        var _offset = 10;
        $('.wrapper--plnr-nav01__scroll-area').bind('scroll', function () {
            if ($(this).scrollTop() + $(this).innerHeight() == $(this)[0].scrollHeight) {
                $.ajax({
                        url: "/api/estimate/estimate_planner",
                        data: {
                            offset: _offset,
                            keyword: $('#search_keyword').val()
                        },
                        method: "GET",
                        dataType: "json",
                        async: false
                    })
                    .done(function (data) {
                        console.log(data);
                        if (data.state == 1) {
                            _offset += data.query.length;
                            $.each(data.query, function (index, item) {
                                var tplt_clone = $($('#estimate_lists').html());

                                tplt_clone.find('[name=tplt_user_thumb]').css(
                                    "background-image", "url(/storage/fdata/user/thumb/" + item
                                    .user_thumb + ")");
                                tplt_clone.find('[name=tplt_user_name]').html(item.name);
                                tplt_clone.find('[name=tplt_estm_area]').html(item
                                    .estm_area);
                                tplt_clone.find('[name=tplt_estm_period]').html(item
                                    .estm_period);
                                tplt_clone.find('[name=tplt_estm_group_qtt]').html(item
                                    .estm_group_qtt);
                                if (item.estm_budget_max == 0) {
                                    tplt_clone.find('[name=tplt_estm_budget_max]').html(
                                        "무관");
                                } else {
                                    tplt_clone.find('[name=tplt_estm_budget_max]').html(
                                        numberWithCommas(item.estm_budget_max) + "원 이하");
                                }
                                tplt_clone.find('[name=tplt_btn_estm_id]').data("id", item
                                    .estm_id);

                                $('#estimates_ul').append(tplt_clone);
                            });
                            $('.plnr-nav01__now__button').unbind();
                            $('.plnr-nav01__now__button').bind('click', function () {
                                var estm_id = $(this).data("id");
                                call_estm_detail(estm_id);
                            });
                        } else {
                            dialog.alert({
                                title: '오류',
                                message: data.msg,
                                button: "확인"
                            });
                        }

                    })
                    .fail(function (xhr, status, errorThrown) {
                        console.log(xhr);
                    }) // 
                    .always(function (xhr, status) {});
            }
        });

        $('#search_keyword').keyup(delay(function () {
            _offset = 0;
            $('#estimates_ul').empty();
            $.ajax({
                    url: "/api/estimate/estimate_planner",
                    data: {
                        offset: _offset,
                        keyword: $(this).val()
                    },
                    method: "GET",
                    dataType: "json",
                    async: false
                })
                .done(function (data) {
                    console.log(data);
                    if (data.state == 1) {
                        _offset += data.query.length;
                        $.each(data.query, function (index, item) {
                            var tplt_clone = $($('#estimate_lists').html());

                            tplt_clone.find('[name=tplt_user_thumb]').css(
                                "background-image", "url(/storage/fdata/user/thumb" + item
                                .user_thumb + ")");
                            tplt_clone.find('[name=tplt_user_name]').html(item.name);
                            tplt_clone.find('[name=tplt_estm_area]').html(item
                                .estm_area);
                            tplt_clone.find('[name=tplt_estm_period]').html(item
                                .estm_period);
                            tplt_clone.find('[name=tplt_estm_group_qtt]').html(item
                                .estm_group_qtt);
                            if (item.estm_budget_max == 0) {
                                tplt_clone.find('[name=tplt_estm_budget_max]').html(
                                    "무관");
                            } else {
                                tplt_clone.find('[name=tplt_estm_budget_max]').html(
                                    numberWithCommas(item.estm_budget_max) + "원 이하");
                            }
                            tplt_clone.find('[name=tplt_btn_estm_id]').data("id", item
                                .estm_id);

                            $('#estimates_ul').append(tplt_clone);
                        });
                        $('.plnr-nav01__now__button').unbind();
                        $('.plnr-nav01__now__button').bind('click', function () {
                            var estm_id = $(this).data("id");
                            call_estm_detail(estm_id);
                        });
                    } else {
                        dialog.alert({
                            title: '오류',
                            message: data.msg,
                            button: "확인"
                        });
                    }

                })
                .fail(function (xhr, status, errorThrown) {
                    console.log(xhr);
                }) // 
                .always(function (xhr, status) {});

        }, 500));
    });

    function call_estm_detail(estm_id) {
        $.ajax({
                url: "/api/estimate/pln_estimate_detail",
                data: {
                    estm_id: estm_id
                },
                method: "GET",
                dataType: "json"
            })
            .done(function (data) {
                if (data.state == 1) {
                    $('#detail_step').empty();
                    $('#detail_step_add').empty();



                    var estm_detail = data.query[0];


                    $('#detail_userthumb').css("background-image", "url(/storage/fdata/user/thumb/" + estm_detail.user_thumb + ")");

                    $('#detail_username').text(estm_detail.name);



                    var remaintime = new Date(86400000 - (new Date().getTime() - new Date(estm_detail.updated_at)
                        .getTime()));
                    $('#detail_remaintime').text(remaintime.getHours() + "시간" + remaintime.getHours() + "분");

                    $('#detail_area').text(estm_detail.estm_area);
                    $('#detail_period').text(estm_detail.estm_period);
                    $('#detail_group_qtt').text(estm_detail.estm_group_qtt);
                    if (estm_detail.estm_budget_max == 0) {
                        $('#detail_budget').text("무관");
                    } else {
                        $('#detail_budget').text(numberWithCommas(estm_detail.estm_budget_max) + "원 이하");
                    }


                    var estm_step5 = JSON.parse(estm_detail.estm_step5);

                    $('#detail_step').append('<em class="plnr-nav01__label">선택<br>사항</em>');
                    $('#detail_step').append('<span class="plnr-nav01__span">' + estm_detail.estm_step4 +
                        '</span>');
                    $.each(estm_step5, function (index, item) {
                    	if(item.estm_title == false){
                    		$('#detail_step').append('<span class="plnr-nav01__span--op">- ' + item.estm_group + ' <br> 요구사항 없음</span><br><br>');
                    	}else{
                    		$('#detail_step').append('<span class="plnr-nav01__span--op">- ' + item.estm_group + ' <br> ' + item.estm_title + '</span><br><br>');
                    	}
                        
                    });

                    if (estm_detail.estm_step5_add == null || estm_detail.estm_step5_add == '') {
                        $('#detail_step_add').append('<em class="plnr-nav01__label">전달<br>사항</em>');
                        $('#detail_step_add').append('<span class="plnr-nav01__span">없음</span>');
                    } else {
                        $('#detail_step_add').append('<em class="plnr-nav01__label">전달<br>사항</em>');
                        $('#detail_step_add').append('<span class="plnr-nav01__span">' + estm_detail
                            .estm_step5_add + '</span>');
                    }


                    $('#btn_suggest_budget').data("id", estm_detail.estm_id);
                    $('#btn_suggest_budget').data("budget", estm_detail.estm_budget_max);

                    //구글맵 넣어야해
                    initMap();

                    popupOpen('plnr_nav01_desc_view_popup');
                }
            })
            .fail(function (xhr, status, errorThrown) {
                console.log(xhr);
            }) // 
            .always(function (xhr, status) {});
    }

    function numberWithCommas(x) {
        x = x.toString();
        var pattern = /(-?\d+)(\d{3})/;
        while (pattern.test(x))
            x = x.replace(pattern, "$1,$2");
        return x;
    }

    function delay(callback, ms) {
        var timer = 0;
        return function () {
            var context = this,
                args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, ms || 0);
        };
    }
    var geocoder;
    var map;

    function initMap() {
        var myLatLng = {
            lat: -25.363,
            lng: 131.044
        };

        var map = new google.maps.Map(document.getElementById('area_map'), {
            zoom: 12,
            center: {
                lat: -34.397,
                lng: 150.644
            }
        });

        var geocoder = new google.maps.Geocoder();

        geocodeAddress(geocoder, map);
    }

    function geocodeAddress(geocoder, resultsMap) {
        var address = document.getElementById('detail_area').textContent;
        geocoder.geocode({
            'address': address
        }, function (results, status) {
            if (status === 'OK') {
                resultsMap.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: resultsMap,
                    position: results[0].geometry.location
                });
            } else {
                alert('Geocode was not successful for the following reason: ' + status);
            }
        });
    }
</script>
<style>
    .sub-ment {
        padding: 1em 2em;
        background: #fff;
        color: #00cada;
        text-align: center
    }
</style>
@endsection