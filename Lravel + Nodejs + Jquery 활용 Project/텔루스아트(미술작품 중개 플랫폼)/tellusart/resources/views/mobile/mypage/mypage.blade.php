@extends('mobile.layouts.app')

@section('content')
<div class="sub-header ">
    <div class="swipe-tabs mypage_tab" style="display:none;">
        <div class="swipe-tab" data-index="0">내 정보수정</div>
        <div class="swipe-tab" data-index="1">내 작품</div>
        <div class="swipe-tab" data-index="2">베팅한 건수</div>
        <div class="swipe-tab" data-index="3">장바구니</div>
        <div class="swipe-tab" data-index="4">구매내역</div>
        <div class="swipe-tab" data-index="5">판매내역</div>
        <div class="swipe-tab" data-index="6">나의 코멘트</div>
        <div class="swipe-tab" data-index="7">나의 전문가 리뷰</div>
    </div>
</div>

<div class="my-container">
    <div class="swipe-tabs-container">
        <div id="myinfo-swipe" class="swipe-tab-content">@include('mobile.mypage.include.myinfo')</div>
        <div id="myart-swipe" class="swipe-tab-content">@include('mobile.mypage.include.myart')</div>
        <div id="mybetting-swipe" class="swipe-tab-content">@include('mobile.mypage.include.mybetting')</div>
        <div id="mycart-swipe" class="swipe-tab-content">@include('mobile.mypage.include.mycart')</div>
        <div id="myorder-swipe" class="swipe-tab-content">@include('mobile.mypage.include.myorder')</div>
        <div id="mysell-swipe" class="swipe-tab-content">@include('mobile.mypage.include.mysell')</div>
        <div id="mycomment-swipe" class="swipe-tab-content">@include('mobile.mypage.include.mycomment')</div>
        <div id="my_expertreview-swipe" class="swipe-tab-content">@include('mobile.mypage.include.my_expertreview')</div>
    </div>
    <div id="mypage_load" class="loading dot" style="display:none;">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>

<!-- 사용자 입력 모달 -->
<div class="cux_modal" id="popupcux" style="display:none;">
    <div class="cux_modal_dialog">
        <h2 class="cux_title"></h2>
        <!-- Modal content-->
        <dl>
            <dt><i class="fal fa-chevron-circle-right"></i><span class="cux_info"></span></dt>
            <dt><input type="text" name="modal-text" class="form-control"><dt>
        </dl>
        <div class="footer_btn">
            <button class="cashgo cux_button"></button>
        </div>
    </div>
</div>

<!-- 배송보기 모달 -->
<div class="cux_modal" id="popupcux1" style="display:none;">
    <div class="cux_modal_dialog">
        <h2 class="cux_title"></h2>
        <!-- Modal content-->
        <dl>
            <dt><i class="fal fa-chevron-circle-right"></i>주문번호: <span class="modal_view_order_id can-reset"></span></dt>
            <dt><i class="fal fa-chevron-circle-right"></i>택배사: <span class="modal_delivery_company can-reset"></span></dt>
            <dt><i class="fal fa-chevron-circle-right"></i>송장번호: <span class="modal_send_post_num can-reset"></span></dt>
        </dl>
        <div class="footer_btn">
            <button class="cashgo cux_button"></button>
        </div>
    </div>
</div>

<!-- 사유보기 모달 -->
<div class="cux_modal" id="popupcux2" style="display:none;">
    <div class="cux_modal_dialog">
        <h2 class="cux_title"></h2>
        <!-- Modal content-->
        <dl>
            <dt><i class="fal fa-chevron-circle-right"></i><span class="cux_info"></span></dt>
            <dt><input type="text" name="modal-text" class="form-control" disabled /></dt>
        </dl>
        <div class="footer_btn">
            <button class="cashgo cux_button"></button>
        </div>
    </div>
</div>

<!-- 작품발송/배송수정 모달 -->
<div class="cux_modal" id="popupcux3" style="display:none;">
    <div class="cux_modal_dialog">
        <h2 class="cux_title">작품발송정보</h2>
        <!-- Modal content-->
        <dl>
            <dt><i class="fal fa-chevron-circle-right"></i>택배사</dt>
            <dt>
                <select name="delivery_company" class="form-control" style="height: initial;" required></select>
            </dt>
            <dt><i class="fal fa-chevron-circle-right"></i>송장번호</dt>
            <dt><input type="text" name="send_post_num" class="form-control" required/></dt>
        </dl>
        <div class="footer_btn">
            <button class="cashgo cux_button">제출</button>
        </div>
    </div>
</div>

<!-- 리뷰수정 -->
<div class="cux_modal" id="popupcux4" style="display:none;">
	<div class="cux_modal_dialog">
		<h2>코멘트 수정</h2>	    
			<!-- Modal content-->
		<dl>
			<dt><i class="fal fa-chevron-circle-right"></i>내용</dt>
			<dt><textarea name="review_body" style="width: 98%; height: 100px;"></textarea></dt>
		</dl>
		<div class="footer_btn">
			<button type="button" class="cashgo">수정하기</button>
		</div>
	</div>
</div>

<!-- 전문가 리뷰 수정 -->
<div class="cux_modal" id="popupcux5" style="display:none;">
	<div class="cux_modal_dialog">
		<h2>코멘트 수정</h2>	    
			<!-- Modal content-->
		<dl>
            <dt><dt><i class="fal fa-chevron-circle-right"></i>별점주기</dt></dt>
            <dt>
                <div class="staron">
                    <div class="starRev product_rating_star">
                        <span data-rating="0.5" class="starR1 on"></span>
                        <span data-rating="1.0" class="starR2"></span>
                        <span data-rating="1.5" class="starR1"></span>
                        <span data-rating="2.0" class="starR2"></span>
                        <span data-rating="2.5" class="starR1"></span>
                        <span data-rating="3.0" class="starR2"></span>
                        <span data-rating="3.5" class="starR1"></span>
                        <span data-rating="4.0" class="starR2"></span>
                        <span data-rating="4.5" class="starR1"></span>
                        <span data-rating="5.0" class="starR2"></span>
                    </div>
                    <em class="en">0.5</em>
                    <input type="hidden" name="rating" value="0.5"  />
                </div>
            </dt>
			<dt><i class="fal fa-chevron-circle-right"></i>내용</dt>
			<dt><textarea name="review_body" style="width: 98%; height: 100px;"></textarea></dt>
		</dl>
		<div class="footer_btn">
			<button type="button" class="cashgo">수정하기</button>
		</div>
	</div>
</div>

<script src="https://ssl.daumcdn.net/dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    $('#user_delete').on('click', function(){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        if(confirm('정말 회원 탈퇴를 하시겠습니까?\n한번 회원 탈퇴를 하신 경우 다시는 해당 메일주소와 계정을 사용할 수 없으며\n고객님의 개인정보는 삭제됩니다.\n그래도 동의하시겠습니까?')){
            $.ajax({
                url : "/user_delete",
                type : "POST",
                data: {_token: CSRF_TOKEN},
                dataType: 'JSON',
                success : function(data) {
                    if(data.status){
                        alert('회원 탈퇴가 완료되었습니다.');
                        document.getElementById('logout-form').submit();
                    }else{
                        alert('회원 탈퇴 오류 발생\n관리자에게 문의하세요.');
                    }
                }
            });
        }
    })
    
    $(function () {
        'use strict';

        var $swipeTabsContainer = $('.swipe-tabs'),
            $swipeTabs = $('.swipe-tab'),
            $swipeTabsContentContainer = $('.swipe-tabs-container'),
            currentIndex = 0,
            activeTabClassName = 'active-tab';

        $swipeTabsContainer.on('init', function(event, slick) {
            $swipeTabsContentContainer.removeClass('invisible');
            $swipeTabsContainer.removeClass('invisible');

            currentIndex = slick.getCurrent();
            $swipeTabs.removeClass(activeTabClassName);
            $('.swipe-tab[data-slick-index=' + currentIndex + ']').addClass(activeTabClassName);
        });

        $swipeTabsContainer.slick({
            //slidesToShow: 3.25,
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: false,
            infinite: false,
            swipeToSlide: true,
            touchThreshold: 10
        });

        $swipeTabsContentContainer.slick({
            asNavFor: $swipeTabsContainer,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            infinite: false,
            swipeToSlide: true,
        draggable: false,
            touchThreshold: 10
        });


        $swipeTabs.on('click', function(event) {
            // gets index of clicked tab
            currentIndex = $(this).data('slick-index');
            $swipeTabs.removeClass(activeTabClassName);
            $('.swipe-tab[data-slick-index=' + currentIndex +']').addClass(activeTabClassName);
            $swipeTabsContainer.slick('slickGoTo', currentIndex);
            $swipeTabsContentContainer.slick('slickGoTo', currentIndex);

            if (currentIndex == 0) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #myinfo-swipe .content").css("max-height", "none");
            } else if (currentIndex == 1) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #myart-swipe .content").css("max-height", "none");
            } else if (currentIndex == 2) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #mybetting-swipe .content").css("max-height", "none");
            } else if (currentIndex == 3) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #mycart-swipe .content").css("max-height", "none");
            } else if (currentIndex == 4) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #myorder-swipe .content").css("max-height", "none");
            } else if (currentIndex == 5) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #mysell-swipe .content").css("max-height", "none");
            } else if (currentIndex == 6) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #mycomment-swipe .content").css("max-height", "none");
            } else if (currentIndex == 7) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #my_expertreview-swipe .content").css("max-height", "none");
            }
        });

        //initializes slick navigation tabs swipe handler
        $swipeTabsContentContainer.on('swipe', function(event, slick, direction) {
            currentIndex = $(this).slick('slickCurrentSlide');
            $swipeTabs.removeClass(activeTabClassName);
            $('.swipe-tab[data-slick-index=' + currentIndex + ']').addClass(activeTabClassName);

            if (currentIndex == 0) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #myinfo-swipe .content").css("max-height", "none");
            } else if (currentIndex == 1) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #myart-swipe .content").css("max-height", "none");
            } else if (currentIndex == 2) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #mybetting-swipe .content").css("max-height", "none");
            } else if (currentIndex == 3) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #mycart-swipe .content").css("max-height", "none");
            } else if (currentIndex == 4) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #myorder-swipe .content").css("max-height", "none");
            } else if (currentIndex == 5) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #mysell-swipe .content").css("max-height", "none");
            } else if (currentIndex == 6) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #mycomment-swipe .content").css("max-height", "none");
            } else if (currentIndex == 7) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #my_expertreview-swipe .content").css("max-height", "none");
            }
        });

        @if(isset($index))
            currentIndex = {{$index}};
            $swipeTabs.removeClass(activeTabClassName);
            $('.swipe-tab[data-slick-index=' + currentIndex +']').addClass(activeTabClassName);
            $swipeTabsContainer.slick('slickGoTo', currentIndex);
            $swipeTabsContentContainer.slick('slickGoTo', currentIndex);

            if (currentIndex == 0) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #myinfo-swipe .content").css("max-height", "none");
            } else if (currentIndex == 1) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #myart-swipe .content").css("max-height", "none");
            } else if (currentIndex == 2) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #mybetting-swipe .content").css("max-height", "none");
            } else if (currentIndex == 3) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #mycart-swipe .content").css("max-height", "none");
            } else if (currentIndex == 4) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #myorder-swipe .content").css("max-height", "none");
            } else if (currentIndex == 5) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #mysell-swipe .content").css("max-height", "none");
            } else if (currentIndex == 6) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #mycomment-swipe .content").css("max-height", "none");
            } else if (currentIndex == 7) {
                $(".my-container .content").css("max-height", "100px");
                $(".my-container #my_expertreview-swipe .content").css("max-height", "none");
            }
        @endif
        
        
        
        mobile_mypage_my_info();
		mobile_mypage_product();
		mobile_mypage_batting(0,0);
		mobile_mypage_cart();
		mobile_mypage_buy_list(0,0);
		mobile_mypage_sell_list(0,0);
        mobile_mypage_comment_list();
        mobile_mypage_expertreview_list();
        
        
        $('.sub-header>div').show();
    });
    
    function Postcode() {
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 도로명 주소의 노출 규칙에 따라 주소를 표시한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var roadAddr = data.roadAddress; // 도로명 주소 변수
                var extraRoadAddr = ''; // 참고 항목 변수

                // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                    extraRoadAddr += data.bname;
                }
                // 건물명이 있고, 공동주택일 경우 추가한다.
                if(data.buildingName !== '' && data.apartment === 'Y'){
                   extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                }
                // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                if(extraRoadAddr !== ''){
                    extraRoadAddr = ' (' + extraRoadAddr + ')';
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById('post_num').value = data.zonecode;
                document.getElementById("mb_addr1").value = roadAddr;
                
                // 참고항목 문자열이 있을 경우 해당 필드에 넣는다.
                if(roadAddr !== ''){
                    document.getElementById("extra_addr").value = extraRoadAddr;
                } else {
                    document.getElementById("extra_addr").value = '';
                }

                var guideTextBox = document.getElementById("guide");
                // 사용자가 '선택 안함'을 클릭한 경우, 예상 주소라는 표시를 해준다.
                if(data.autoRoadAddress) {
                    var expRoadAddr = data.autoRoadAddress + extraRoadAddr;
                    guideTextBox.innerHTML = '(예상 도로명 주소 : ' + expRoadAddr + ')';
                    guideTextBox.style.display = 'block';

                } else if(data.autoJibunAddress) {
                    var expJibunAddr = data.autoJibunAddress;
                    guideTextBox.innerHTML = '(예상 지번 주소 : ' + expJibunAddr + ')';
                    guideTextBox.style.display = 'block';
                } else {
                    guideTextBox.innerHTML = '';
                    guideTextBox.style.display = 'none';
                }
            }
        }).open();
    }
</script>
@endsection