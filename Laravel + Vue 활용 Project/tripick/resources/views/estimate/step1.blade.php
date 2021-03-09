@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--recommend">

    <div class="hd-title hd-title--02">
        <button type="button" id="close_recommend_btn" class="hd-title__right-btn hd-title__right-btn--close"><span class="none">닫기버튼</span></button>
        <h2 class="hd-title--02__title">여행지 추천 정보 입력</h2>
        <span class="inline inline--left">날짜/장소/인원 선택</span>

        <div class="step-line">
            <ul class="step-line__group">
                <li class="step-line__list step-line__list--title">step</li>
                <li class="step-line__list is-active">
                    <em>01</em>
                </li>
                <li class="step-line__list">
                    <em>02</em>
                </li>
                <li class="step-line__list">
                    <em>03</em>
                </li>
                <li class="step-line__list">
                    <em>04</em>
                </li>
            </ul>
        </div>

    </div>

    <div class="wrapper--recommend__scroll-area">
        
        <div class="wrapper--recommend--step01__question">
            <label class="recommend-step__ask" for="ask_region">원하시는 여행지는 어디인가요?</label>
            <span class="recommend-step__input-group">
                <input type="text" id="ask_region" class="recommend-step__input" data-name="ask_region_popup" placeholder="지역을 선택해주세요." readonly>
                <input type="hidden" id="ask_area_type">
            </span>
        </div>

        <div class="wrapper--recommend--step01__question">
            <label class="recommend-step__ask" for="ask_period">여행 일정은 언제인가요?</label>
            <p class="several-nights" id="several_nights"></p>
            <span class="recommend-step__input-group">
                <input type="text" id="ask_period" class="recommend-step__input" data-name="ask_period_popup" placeholder="날짜를 선택해주세요." readonly>
            </span>
        </div>

        <div class="wrapper--recommend--step01__question">
            <label class="recommend-step__ask" for="ask_person">인원은 몇명인가요?</label>
            <span class="recommend-step__input-group">
                <input type="text" id="ask_person" class="recommend-step__input" data-name="ask_person_popup" placeholder="인원을 선택해주세요." readonly>
            </span>
        </div>

    </div>

    <div class="button-bt-fixed button-bt-fixed--02">
        <button type="button" id="temp-confirm" class="button button--disable button--recommend-st">다음</button>
    </div>

</div>

<div class="popup popup--modal">

    <div class="popup__inner" id="ask_region_popup"> 

        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>지역선택</h3>
        
        <fieldset class="popup__inner__field">

            <input type="radio" name="region_category" id="check_region_domestic" value="0" class="none-input" checked>
            <input type="radio" name="region_category" id="check_region_oversea" value="1" class="none-input" >

            <div class="tab-menu">
                <label for="check_region_domestic" class="tab-menu__list tab-menu__label--domestic is-active">국내여행지</label>
                <label for="check_region_oversea" class="tab-menu__list tab-menu__label--oversea">해외여행지</label>
                <span class="tab-menu__indicator"></span>
            </div>

            <input type="text" id="estm_area" class="sch-input" placeholder="키워드 검색 (ex. 제주도/강남/부평동)">

            <div class="result-wrapper result-wrapper--domestic">
                
            </div>
            
            <div class="result-wrapper result-wrapper--oversea">
                
            </div>
            
            <div class="recommend-step__apart-inquiry">
                <span class="recommend-step__apart-inquiry__check">
                    <input type="checkbox" id="apart_inquiry_check_region" class="input-style-02">
                    <label for="apart_inquiry_check_region"></label>
                    <label for="apart_inquiry_check_region">&nbsp;&nbsp;별도문의, 추천드리겠습니다</label>
                </span>
                <!-- <span class="recommend-step__apart-inquiry__ment">미정이거나 추천을 받고 싶으신가요?<br>걱정마세요! 전문가가 추천해드리겠습니다.</span> -->
            </div>

            <button type="button" id="btn_estm_area" class="button button--disable">선택완료</button>

        </fieldset>

    </div>

    <div class="popup__inner popup__inner--not-pd" id="ask_period_popup"> 
        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>일정선택</h3>
            
        <fieldset class="popup__inner__field">
            <div id="datepick_01" class="datepicker-here" data-range="true"></div>

            <div class="date-group">
                <span class="inline inline--center" id="datepick_01_info">
                </span>
                <textarea type="text" placeholder="추가로 문의하실 내용이 있다면 작성해주세요" class="schedule-inquiry-input" id="schedule_inquiry" rows="6"></textarea>
                <button type="button" id="btn_date_select" class="button button--disable">선택완료</button>
            </div>
        </fieldset>

    </div>

    <div class="popup__inner" id="ask_person_popup"> 
        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>인원선택</h3>
            
        <fieldset class="popup__inner__field">
            
            <span class="inline inline--left recommend-step__advice"><b>정확한 인원</b>을 입력해주세요.</span>
            
            <div class="recommend-step__person">
                <ul class="recommend-step__person__group">
                    <li class="recommend-step__person__list">
                        <label class="recommend-step__person__label">성인</label>
                        <span class="num-ctrl-span">
                            <button type="button" id="big_person_minus" class="num-ctrl-span__btn num-ctrl-span__btn--minus is-active"><b class="none">-</b></button>
                            <input type="number" id="big_person_text" class="num-ctrl-span__input" value="0">
                            <button type="button" id="big_person_plus" class="num-ctrl-span__btn num-ctrl-span__btn--plus is-active"><b class="none">+</b></button>
                        </span>
                    </li>
                    <li class="recommend-step__person__list">
                        <label class="recommend-step__person__label">어린이(만 13세 미만)</label>
                        <span class="num-ctrl-span">
                            <button type="button" id="medium_person_minus" class="num-ctrl-span__btn num-ctrl-span__btn--minus is-active"><b class="none">-</b></button>
                            <input type="number" id="medium_person_text" class="num-ctrl-span__input" value="0">
                            <button type="button" id="medium_person_plus" class="num-ctrl-span__btn num-ctrl-span__btn--plus is-active"><b class="none">+</b></button>
                        </span>
                    </li>
                    <li class="recommend-step__person__list">
                        <label class="recommend-step__person__label">유아(만 24개월 미만)</label>
                        <span class="num-ctrl-span">
                                <button type="button" id="small_person_minus" class="num-ctrl-span__btn num-ctrl-span__btn--minus is-active"><b class="none">-</b></button>
                            <input type="number" id="small_person_text" class="num-ctrl-span__input" value="0">
                            <button type="button" id="small_person_plus" class="num-ctrl-span__btn num-ctrl-span__btn--plus is-active"><b class="none">+</b></button>
                        </span>
                    </li>
                </ul>
            </div>

            <div class="recommend-step__apart-inquiry">
                <span class="recommend-step__apart-inquiry__ment">성인이 1명 이상이어야만 신청 가능합니다</span>
            </div>

            <div class="date-group">
                <span class="inline inline--center" id="estm_person_text">
                    <em>성인 <span id="big_person_span">0</span>명</em>&nbsp;·&nbsp;<em>어린이 <span  id="medium_person_span">0</span>명</em>&nbsp;·&nbsp;<em>유아 <span id="small_person_span">0</span>명</em>
                </span>
            </div>
            
            <button type="button" id="btn_estm_person" class="button button--disable">선택완료</button>

        </fieldset>

    </div>

</div>
@endsection

@section('script')

<script>
$(function() {
    //여행지 선택 부분 시작
    $('#estm_area').keyup(function(){
        var check_inquiry = $('#apart_inquiry_check_region').is(":checked");
        if(!check_inquiry){
            if($(this).val().length > 0){
                $('#btn_estm_area').addClass('is-active');
            }else{
                $('#btn_estm_area').removeClass('is-active');
            }
        }
    });

    $('#apart_inquiry_check_region').change(function(){
        if($('#estm_area').val().length == 0){
            var check_inquiry = $(this).is(":checked");
            if(check_inquiry){
                $('#btn_estm_area').addClass('is-active');
            }else{
                $('#btn_estm_area').removeClass('is-active');
            }
        }
    });

    $('#btn_estm_area').click(function(){
        if($(this).hasClass("is-active")){
            var check_inquiry = $('#apart_inquiry_check_region').is(":checked");
            if(check_inquiry){
                $('#ask_region').val('별도 문의');
                $('#ask_area_type').val($('input[name=region_category]:checked').val());
                $('#ask_region').closest('span').addClass('is-filled');
                $('.popup__inner').removeClass('is-active');
                $('.popup--modal').delay(200).fadeOut();
                if($('#ask_region').closest('span').hasClass('is-filled') 
                && $('#ask_period').closest('span').hasClass('is-filled')
                && $('#ask_person').closest('span').hasClass('is-filled'))
                {
                    $('#temp-confirm').addClass('is-active');
                }
            }else{
                var estm_area = $('#estm_area').val();
                $('#ask_region').val(estm_area);
                $('#ask_area_type').val($('input[name=region_category]:checked').val());
                $('#ask_region').closest('span').addClass('is-filled');
                $('.popup__inner').removeClass('is-active');
                $('.popup--modal').delay(200).fadeOut();
                if($('#ask_region').closest('span').hasClass('is-filled') 
                && $('#ask_period').closest('span').hasClass('is-filled')
                && $('#ask_person').closest('span').hasClass('is-filled'))
                {
                    $('#temp-confirm').addClass('is-active');
                }
            }
        }else{
            dialog.alert({
                title: "알림",
                message: "별도문의를 체크해주시거나 키워드를 입력해주세요.",
                button: "확인"
            });
        }
    });
    //end

    //인원선택
    $('#big_person_plus').click(function() {
        var number = parseInt($('#big_person_text').val()) + 1;
        $('#big_person_text').val(number);
        $('#big_person_span').text(number);
        if(number > 0){
            $('#btn_estm_person').addClass('is-active');
        }
    });
    $('#big_person_minus').click(function() {
        var number = parseInt($('#big_person_text').val()) - 1;
        if(number >= 0){
            $('#big_person_text').val(number);
            $('#big_person_span').text(number);
        }else{
            $('#btn_estm_person').removeClass('is-active');
        }
    });
    $('#big_person_text').keyup(function() {
        var number = parseInt($('#big_person_text').val());
        if(number > 0){
            $('#big_person_text').val(number);
            $('#big_person_span').text(number);
            $('#btn_estm_person').addClass('is-active');
        }else if(number == 0){
            $('#btn_estm_person').removeClass('is-active');
        }else if(isNaN(number)){
            $('#btn_estm_person').removeClass('is-active');
        }else{
            dialog.alert({
                title: "알림",
                message: "0 이상의 숫자만 입력하셔야 됩니다.",
                button: "확인"
            });
            $('#big_person_text').val(0);
            $('#big_person_span').text(0);
        }
    });

    $('#medium_person_plus').click(function() {
        var number = parseInt($('#medium_person_text').val()) + 1;
        $('#medium_person_text').val(number);
        $('#medium_person_span').text(number);
    });
    $('#medium_person_minus').click(function() {
        var number = parseInt($('#medium_person_text').val()) - 1;
        if(number >= 0){
            $('#medium_person_text').val(number);
            $('#medium_person_span').text(number);
        }
    });
    $('#medium_person_text').keyup(function() {
        var number = parseInt($('#medium_person_text').val());
        if(number > 0){
            $('#medium_person_text').val(number);
            $('#medium_person_span').text(number);
        }else if(number == 0){
        }else if(isNaN(number)){
        }else{
            dialog.alert({
                title: "알림",
                message: "0 이상의 숫자만 입력하셔야 됩니다.",
                button: "확인",
                cancel: ""
            });
            $('#medium_person_text').val(0);
            $('#medium_person_span').text(0);
        }
    });

    $('#small_person_plus').click(function() {
        var number = parseInt($('#small_person_text').val()) + 1;
        $('#small_person_text').val(number);
        $('#small_person_span').text(number);
    });
    $('#small_person_minus').click(function() {
        var number = parseInt($('#small_person_text').val()) - 1;
        if(number >= 0){
            $('#small_person_text').val(number);
            $('#small_person_span').text(number);
        }
    });
    $('#small_person_text').keyup(function() {
        var number = parseInt($('#small_person_text').val());
        if(number > 0){
            $('#small_person_text').val(number);
            $('#small_person_span').text(number);
        }else if(number == 0){
        }else if(isNaN(number)){
        }else{
            dialog.alert({
                title: "알림",
                message: "0 이상의 숫자만 입력하셔야 됩니다.",
                button: "확인"
            });
            $('#small_person_text').val(0);
            $('#small_person_span').text(0);
        }
    });
    $('#btn_estm_person').click(function(){
        if($(this).hasClass('is-active')){
            var estm_person = $('#estm_person_text').text();
            $('#ask_person').val($.trim(estm_person));
            $('#ask_person').closest('span').addClass('is-filled');
            $('.popup__inner').removeClass('is-active');
            $('.popup--modal').delay(200).fadeOut();
            if($('#ask_region').closest('span').hasClass('is-filled') 
            && $('#ask_period').closest('span').hasClass('is-filled')
            && $('#ask_person').closest('span').hasClass('is-filled'))
            {
                $('#temp-confirm').addClass('is-active');
            }
        }else{
            dialog.alert({
                title: "알림",
                message: "1명 이상의 성인을 입력하셔야됩니다.",
                button: "확인"
            });
        }
    })

    //입력도중에 취소버튼 누르면 팝업
    $("#close_recommend_btn").click(function(e) {
        dialog.confirm({
            title: "알림",
            message:
            '<p class="single-msg">나를 가장 잘 아는 여행을 추천받는<br><b>정보입력을 중단</b>하시겠습니까?</p>',
            button: "네, 중단합니다.",
            cancel: "아니오, 계속 입력합니다.",
            callback: function(value){
                if(value){
                    location.href='/af_home';
                }
            }
        });
    });//end

    //datepicker
    $("#datepick_01").datepicker({
        minDate: new Date(new Date().valueOf()),
        language: "en",
        inline: "true",
        onSelect:function(formattedDate, date, inst){
            var str = formattedDate.replace(",", " ~ ");
            var diff = new Date(date[1] - date[0]);
            if(date.length > 0){
            	if(!isNaN(diff)){
	                var days = diff/1000/60/60/24;
	                //str = str + ' ' + days + '박' + (days + 1) + '일';
	                $('#several_nights').text(days + '박' + (days + 1) + '일');
	                $('#btn_date_select').addClass('is-active');
	            }else{
	            	$('#several_nights').text('당일');
	                $('#btn_date_select').addClass('is-active');
	            }
	            $('#datepick_01_info').text(str);
            }else{
            	$('#btn_date_select').removeClass('is-active');
            	$('#several_nights').text('');
            }
        }
    }); //end

    $("#btn_date_select").click(function(){
        if($(this).hasClass("is-active")){
            $('#ask_period').closest('span').addClass('is-filled');
            $('#ask_period').val($('#datepick_01_info').text());
            $('.popup__inner').removeClass('is-active');
            $('.popup--modal').delay(200).fadeOut();
            if($('#ask_region').closest('span').hasClass('is-filled') 
            && $('#ask_period').closest('span').hasClass('is-filled')
            && $('#ask_person').closest('span').hasClass('is-filled'))
            {
                $('#temp-confirm').addClass('is-active');
            }
        }else{
            dialog.alert({
                title: "알림",
                message: "날짜를 선택해주세요.",
                button: "확인"
            });
        }
    });

    @if(isset($estm_step[0]))
    dialog.confirm({
        title: "잠깐!",
        message:
        '<p class="single-msg">이전에 입력한 정보가 있습니다.<br><b>이어서 하시겠습니까?</p>',
        button: "네, 이어서 할께요.",
        cancel: "아니오, 새로 입력할래요.",
        callback: function(value){
            if(value){
                $('#ask_region').val('{{ $estm_step[0]->estm_area }}');
                $('#ask_area_type').val('{{ $estm_step[0]->estm_area_type }}');
                //DB에 날짜와 N박 n+1일이 같이 저장되어있음. N박 n+1일 따로 분리하여 표시
                var period = '{{ $estm_step[0]->estm_period }}';
                var period_split = period.split(' ');
                $('#several_nights').text(period.split(' ')[period_split.length - 1]);
                $('#ask_period').val(period.replace(period.split(' ')[period_split.length - 1],''));
                $('#ask_person').val('{{ $estm_step[0]->estm_group_qtt }}');
                $('#ask_region').closest('span').addClass('is-filled');
                $('#ask_period').closest('span').addClass('is-filled');
                $('#ask_person').closest('span').addClass('is-filled');
                $('#temp-confirm').addClass('is-active');
            }else{
                var param = { 
                    'estm_id': '{{ $estm_step[0]->estm_id }}'
                };
                $.ajax({
                    method: "DELETE",
                    data: param,
                    dataType: 'json',
                    url: '/api/estimate/step_delete',
                    success: function(data) {
                        if(data.state ==1){
                        }else if(data.state == 100){
                        }else{
                            dialog.alert({
                                title:'오류',  
                                message: data.msg,
                                button: "확인"
                            });
                        }

                    },
                    error: function(e) {
                        console.log(e);
                    }
                });
            }
        }
    });
    @endif

    $("#temp-confirm").click(function(){
        if($(this).hasClass("is-active")){
            var param = { 
                'estm_area': $('#ask_region').val(),
                'estm_area_type': $('#ask_area_type').val(),
                'estm_period': $('#ask_period').val()+' '+$('#several_nights').text(),
                'estm_group_qtt': $('#ask_person').val(),
                'estm_period_inquiry' : $('#schedule_inquiry').val()
            };
            $.ajax({
                method: "POST",
                data: param,
                dataType: 'json',
                url: '/api/estimate',
                success: function(data) {
                    if(data.state ==1){
                        console.log(data);
                        location.href = '/estimate/step2';
                    }else if(data.state == 100){
                        
                    }else{
                        dialog.alert({
                            title:'오류',  
                            message: data.msg,
                            button: "확인"
                        });
                    }

                },
                error: function(e) {
                    console.log(e);
                }
            });
        }else{
            dialog.alert({
                title: "알림",
                message: "모든 사항이 입력되어야 합니다.",
                button: "확인"
            });
        }
    });

    $(".tab-menu__list").click(function() {
    $(this).addClass("is-active");
    $(".tab-menu__list")
        .not(this)
        .removeClass("is-active");
    indicatorMove();
    });

    $('#ask_region').click(function() {
        initialize();
        $(".tab-menu__list").eq($('#ask_area_type').val()).click();
    });
    //end
    google.maps.event.addDomListener(window, 'load', initialize);

});

//indicator 움직임효과
function indicatorMove() {
    activePos = $(".tab-menu__list.is-active").position();

    $(".tab-menu__indicator")
        .stop()
        .css({
        left: activePos.left,
        width: $(".tab-menu__list.is-active").innerWidth()
        });
    
}

function initialize() {
    var input = document.getElementById('estm_area');
    var options = {
        types: ['geocode']
    };
    var autocomplete = new google.maps.places.Autocomplete(input, options);
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCpIgX3qFRixpEox5kUaJkXlxslRZKmxWs&libraries=places&language=ko" async="async" defer="defer" type="text/javascript"></script>
<style lang="scss">
	.several-nights{
		font-size:0.8em;
		color:#00DBD8;
		margin-bottom:0.5em;
	}
	.schedule-inquiry-input{
		position:relative;
		top:0;
		width:86.5%;
		bottom:14vh;
		padding:0.8em;
		font-size:0.8em;
		left:50%;
		transform:translateX(-50%);
		border-radius:1em;
		border:#8c8c8c 1px solid;
		margin-bottom:4em;
	}
	#btn_date_select{
		position:relative;
	}
</style>
@endsection