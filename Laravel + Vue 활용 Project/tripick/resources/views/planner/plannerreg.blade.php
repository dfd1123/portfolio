@extends('layouts.app')

@section('content')


<form id="plnr-reg-form" method="POST" action="/api/plnr" enctype="multipart/form-data">
    <input type = "hidden" name="req" value="plnr">
    <div class="wrapper wrapper--planner_reg wrapper--planner">

        <div class="user-msg-alarm">
            <p class="user-msg-alarm__text">영역을 터치하여 정보를 입력하세요.</p>
            <span class="user-msg-alarm__btn" id="alarm_msg_close"><b class="none">닫기버튼</b></span>
        </div>

        <div class="hd-title hd-title--01">
            <button type="button" onClick="history.back()" class="hd-title__left-btn hd-title__left-btn--prev"><span
                    class="none">이전버튼</span></button>
            <h2 class="hd-title__center">플래너 신청하기</h2>
        </div>

        <div class="wrapper--planner__scroll-area">

            <div class="plnr">

                <div class="plnr-info">
                    <span class="plnr-info__type is-set" id="check_plnr_type" data-name="check_plnr_type_popup">개인/업체 중
                        택1</span>
                    <p class="plnr-info__name">
                        <span class="plnr-info__name is-set" name="pln_name" id="check_plnrname"
                            data-name="check_plnrname_popup">플래너명</span>
                    </p>

                </div>
                <div class="plnr-intro is-set">
                    <p class="plnr-intro__text" name="pln_desc" id="check_introtext" data-name="check_plnrname_popup">
                        소개글을 입력해주세요</p>
                </div>
            </div>

            <input type="radio" name="check-plnr-category" id="check_plnr_info_01" class="none-input" checked>
            <input type="radio" name="check-plnr-category" id="check_plnr_info_02" class="none-input">
            <input type="radio" name="check-plnr-category" id="check_plnr_info_03" class="none-input">
            <input type="radio" name="check-plnr-category" id="check_plnr_info_04" class="none-input">

            <div class="plnr-contents-wrapper">

                <div class="plnr-contents plnr-contents--01">

                    <h4 class="plnr-contents__tit">인증</h4>

                    <!-- 플래너가 인증할 때 (plnr-certify__step)에 사진올리면 button 사라지고 is-checked 클래스 추가되야함 -->
                    <div class="plnr-certify">
                        <ul class="plnr-certify__group">
                            <li class="plnr-certify__step" id="plnr_mobile">
	                            <button type="button" class="plnr-certify__step__btn" id="check_certify_phone"
	                                data-name="check_certify_popup"></button>                               
                                <span>핸드폰</span>
                            </li>
                            <li class="plnr-certify__step" id="plnr_idcard">
                                <button type="button" class="plnr-certify__step__btn" id="check_certify_idcard"
                                    data-name="check_certify_popup"></button>
                                <span>신분증</span>
                            </li>
                            <li class="plnr-certify__step" id="plnr_doc">
                                <button type="button" class="plnr-certify__step__btn" id="check_certify_doc"
                                    data-name="check_certify_popup"></button>
                                <span>서류증빙</span>
                            </li>
                        </ul>
                    </div>
                    <!-- end -->

                    <h4 class="plnr-contents__tit">정보</h4>
                    <div class="plnr-desc">
                        <ul id="plnr-info-wrap" class="plnr-desc__group">
                            <li class="plnr-desc__input">
                                <input name="pln_info[]" required minlength="2" type="text"
                                    placeholder="정보 추가 (ex. 트리픽대학 관광학과 졸업)" oninvalid="this.setCustomValidity('정보를 입력해주세요.')" oninput="this.setCustomValidity('')">
                                <button name="add-plnr-info" type="button" class="plus-btn"></button>
                            </li>
                        </ul>
                    </div>

                    <h4 class="plnr-contents__tit">플래너 스타일</h4>
                    <div class="plnr-desc">
                        <ul id="plnr-style-wrap" class="plnr-desc__group">
                            <li class="plnr-desc__input">
                                <input name="pln_style[]" required="" minlength="2" type="text"
                                    placeholder="스타일 추가 (ex. 인생샷 전문가 , 대중교통 전문)">
                                <button name="add-plnr-style" type="button" class="plus-btn"></button>
                            </li>
                        </ul>
                    </div>

                    <h4 class="plnr-contents__tit">플래너 지역</h4>
                    <div class="plnr-desc">
                        <ul id="plnr-juri-wrap" class="plnr-desc__group">
                            <li class="plnr-desc__input">
                                <input name="pln_juri[]"  minlength="2" type="text" class="pln_juri"
                                    placeholder="거주지 인근 관광지 (ex.이태원, 남포동, 여수 )" required="">
                                <button name="add-plnr-juri" type="button" class="plus-btn"></button>
                            </li>
                        </ul>
                    </div>

                    <div class="wrapper--planner__btn">
                        <button type="button" class="button button--apply" id="plnr_apply_btn">플래너 신청하기 </button>
                    </div>
                </div>
            </div>
        </div>
        @include('nav.nav_user')

    </div>

    <div class="popup popup--modal">

        <div class="popup__inner" id="check_plnr_type_popup">
            <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>개인 및 업체분류</h3>
            <span class="inline inline--center">플래너로 활동하는 형태를 선택하세요.</span>
            <input type="radio" name="pln_type" checked="checked" value="0" class="none-input" id="check_plnr_type_01">
            <label class="button button--outline pln_type_01_button" name="pln_type" for="check_plnr_type_01">개인</label>

            <input type="radio" name="pln_type" value="1" class="none-input" id="check_plnr_type_02">
            <label class="button button--outline pln_type_02_button" for="check_plnr_type_02">업체</label>
        </div>

        <div class="popup__inner" id="check_plnrname_popup">
            <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>플래너명/소개글 입력</h3>
            <div class="popup__inner__field">
                <div class="field__align">
                    <label class="inline inline--left">플래너명을 입력하세요.</label>
                    <span class="input-group">
                        <input id="plnr-name" name="pln_name" type="text" required="" minlength="2" maxlength="12"
                            class="input-group__input" placeholder="플래너명 입력 (2~12자 이내)">
                    </span>
                    <span class="inline inline--right">
                        <em id="name-uav" style="display:none;" class="caution caution--disable">중복되는 플래너명이 존재합니다.</em>
                        <em id="name-av" style="display:none;" class="caution caution--able">사용가능한 플래너명입니다.</em>
                    </span>
                </div>
                <div class="field__align">
                    <label class="inline inline--left">자신을 표현하는 소개글을 입력하세요.</label>
                    <div class="intro__textarea">
                        <textarea id="plnr-popup-desc" name="pln_desc" cols="30" rows="5" 
                            placeholder="소개글을 입력해주세요.(최소2자 이상, 최대 128자)"></textarea>
                    </div>
                </div>
                <button id="plnr-nameok" type="button" class="button button--disable button--relative">입력완료</button>
            </div>
        </div>

        <div class="popup__inner" id="check_certify_popup">
            <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>인증하기</h3>
            <div class="popup__inner__field popup_scroll_y_wrap">
                <span class="inline inline--left">플래너의 신뢰를 증명하기 위한 <br>인증을 완료해주세요.</span>
                <div class="field__align">
                    <h5 class="certify__tit">01. 문자 인증<br></h5>
                    <ul class="certify__thumbnail-group" id="certify__mobile_thumbnail-group">
                    	<div style="margin-bottom:1em;" class="verify-contact-div">
                    		<input type="text" required="" id="input_mobile_01" class="contact_input" placeholder="'-'를 제외하고 입력해주세요">
                       		<button id="msg_send" class="contact_btn" type="button">전송</button>
                    	</div>
                        <div class="verify-code-div">
                    		<input type="text" required="" id="input_mobile_02" class="contact_input" placeholder="코드입력">
                       		<button id="msg_check" class="contact_btn" type="button">확인</button>
                    	</div>
                    	<input type="hidden" name="pln_mobile" />
                    	<input type="hidden" id="contact_verify" value="0"/>
                    </ul>
                </div>
                <div class="field__align">
                    <h5 class="certify__tit">02. 신분증 인증<br><em>(픽업 서비스 제공 시, 운전면허증 등록 / 최대 3개)</em></h5>
                    <ul class="certify__thumbnail-group" id="certify__idcard_thumbnail-group">
                        <input type="file" required="" name="idcard[]" id="input_idcard_pic_01"  class="none-input" accept=".jpg,.jpeg,.png,.gif,.bmp" multiple="multiple">
                        <li class="certify__thumbnail-list">
                            <label for="input_idcard_pic_01"></label>
                            <span class="certify__thumbnail-list__span">신분증 추가</span>
                        </li>
                    </ul>
                </div>
                <div class="field__align">
                    <h5 class="certify__tit">03. 서류증빙<em>(최대 5개)</em></h5>
                    <ul class="certify__thumbnail-group" id="certify__docum_thumbnail-group">
                        <input type="file" required="" name="docs[]" id="input_docum_pic_01" class="none-input" accept=".jpg,.jpeg,.png,.gif,.bmp" multiple="multiple">
                        <li class="certify__thumbnail-list">
                            <label for="input_docum_pic_01"></label>
                            <span class="certify__thumbnail-list__span">파일 추가</span>
                        </li>
                    </ul>
                </div>
                <button id="plnr_fileok" type="button" class="button button--disable is-active button--relative">입력완료</button>
                <!-- <div class="popup_bt_fixed_btn_wrap">
                </div> -->
            </div>
        </div>
</form>



@endsection

@section('script')
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCpIgX3qFRixpEox5kUaJkXlxslRZKmxWs&libraries=places"
    type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
<script>
var nameflag = 0;

$(function() {
    google.maps.event.addDomListener(window, 'load', initialize);
	initialize();
	
	$('#msg_send').click(function(){
		var number = $('#input_mobile_01').val();
		var params = {
			'user_contact' : number
		}
		$.ajax({
			url:'/api/plnr/msgsend',
			data: params,
			type: 'PUT',
			dataType : 'json'
		}).done(function(res){
			console.log(res);
			if(res.state == 1 && res.query == 1){
				dialog.alert({
	                title:'안내',  
	                message: "메세지를 전송했습니다. 인증번호를 확인해주세요",
	                button: "확인"
	            });
	            $('.verify-code-div').addClass('active');
			}else{
				dialog.alert({
	                title:'안내',  
	                message: "메세지 전송 실패",
	                button: "확인"
	            });
			}
		})
	});
	$('#msg_check').click(function(){
		var code = $('#input_mobile_02').val();
		var params = {
			'verify_code' : code
		}
		$.ajax({
			url:'/api/plnr/msg_verify_check',
			data: params,
			type: 'GET',
			dataType : 'json'
		}).done(function(res){
			if(res.state == 1 && res.query.length > 0){
				$('#contact_verify').val('1');
				$('[name=pln_mobile]').val($('#input_mobile_01').val());
				$('#input_mobile_01').attr('disabled','disabled');
				$('#input_mobile_02').attr('disabled','disabled');
				dialog.alert({
	                title:'안내',  
	                message: "확인되었습니다",
	                button: "확인"
	            });
	            
			}else{
				dialog.alert({
	                title:'안내',  
	                message: "인증번호가 틀렸습니다",
	                button: "확인"
	            });
			}
		})
	});
    //플래너신청 클릭
    $('#plnr_apply_btn').click(function(e) {
        dialog.confirm({
            title: '플래너 신청',
            message: '<div class="single-msg">입력하신 정보로 플래너를 신청하시겠습니까?</div>\n<div>(신분증, 서류도 체크해주세요!)</div>',
            cancel: "아니오",
            button: "예",
            callback: function(value){
                if(value){
                	if($('#contact_verify').val() == 0){
                		dialog.alert({
			                title:'안내',  
			                message: "핸드폰 인증을 완료해주세요",
			                button: "확인"
			            });
                		return false;
                	}else{
                		$('#plnr-reg-form').submit();
                	}
                    //console.log($('#plnr-reg-form'));
                }
            }
        });
    });

    $('#plnr-reg-form').ajaxForm({
        type : "POST",
        dataType : "json",
        
        beforeSubmit: function() {
            //동적추가된 폼들 검증추가
            $('[name ^=pln-]').each(function() {
                $(this).rules("add", {
                    required: true,
                    messages: {
                        required: "Name is required"
                    }
                });
            });

            return $('#plnr-reg-form').valid();
        },
        success: function(data) {
            console.log(data);
            if(data.state == 1){
                dialog.alert({
                    title:'플래너 신청',  
                    message: '플래너 신청이 완료되었습니다. 심사 결과를 기다려주세요~',
                    button: "확인",
                    callback: function(value){
                        location.href='/planner/intro';
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


    //플래너 정보 + 클릭
    $('[name=add-plnr-info]').click(function() {
        var info_input = '<li class="plnr-desc__input">\
                            <input name="pln_info[]" required="" minlength="2"  name="plnr-info" type="text" placeholder="정보 추가 (ex. 트리픽대학 관광학과 졸업)">\
                            <button type="button" name="minus-plnr-info" class="minus-btn"></button>\
                            </li>';
        $('#plnr-info-wrap').append(info_input);
        $('[name=minus-plnr-info]').unbind();
        $('[name=minus-plnr-info]').bind('click',function(){
            $(this).parents('.plnr-desc__input').remove();
        });

    });
    //플래너 스타일 + 클릭
    $('[name=add-plnr-style]').click(function() {
        var info_input =
            ' <li class="plnr-desc__input">\
                            <input name ="pln_style[]" required="" minlength="2"  name="plnr-style" type="text" placeholder="스타일 추가 (ex. 인생샷 전문가 , 대중교통 전문)">\
                            <button type="button" name="minus-plnr-info" class="minus-btn"></button>\
            </li>';
        $('#plnr-style-wrap').append(info_input);
        $('[name=minus-plnr-info]').unbind();
        $('[name=minus-plnr-info]').bind('click',function(){
            $(this).parents('.plnr-desc__input').remove();
        });

    });


    // 플래너관할구역 + 클릭
    $('[name=add-plnr-juri]').click(function() {
        var info_input =
            ' <li class="plnr-desc__input">\
                <input name ="pln_juri[]" required="" minlength="2"  name="plnr-style" type="text" class="pln_juri" placeholder="거주지 인근 관광지 (ex.이태원, 남포동, 여수 )">\
                            <button type="button" name="minus-plnr-info" class="minus-btn"></button>\
                </li>';
        $('#plnr-juri-wrap').append(info_input);
        initialize();
        $('[name=minus-plnr-info]').unbind();
        $('[name=minus-plnr-info]').bind('click',function(){
            $(this).parents('.plnr-desc__input').remove();
        });

    });


    //글 다적으면
    $('#plnr-popup-desc').focusout(function() {

        changebtnstate();
    });

    $('#plnr-nameok').click(function() {


        if( $('#plnr-popup-desc').val().length >128 || $('#plnr-popup-desc').val().length < 2 ){
            dialog.alert({
                        title: '알림',
                        message: '소개글 2자이상 128자 이하',
                        button: "확인"
                    });
                return;
        }

        if (changebtnstate()) {
            $(this).parents('.popup__inner').removeClass('is-active');
            $('.popup--modal').delay(200).fadeOut();

            $('#check_plnrname').html($('#plnr-name').val()).removeClass('is-set');
            //클릭시 줄바꿈
            var str_name = $("#plnr-popup-desc").val().replace(/(?:\r\n|\r|\n)/g, '<br />');
            $("#check_introtext").html(str_name);

            $('.plnr-intro').removeClass('is-set');
        } else {

        }
    });
    $('#plnr_fileok').click(function() {
        $(this).parents('.popup__inner').removeClass('is-active');
        $('.popup--modal').delay(200).fadeOut();
		
		
		if($('#input_mobile_01').val() != '' && $('#input_mobile_02').val() != '' && $('#contact_verify').val() == 1){
			$('#plnr_mobile').addClass('is-checked');
			$('#check_certify_phone').attr('style','display:none;');
		}else{
			$('#plnr_mobile').removeClass('is-checked');
			$('#check_certify_phone').attr('style','display:block;');
		}
		
		
		if($('#input_idcard_pic_01').val() != ''){
			$('#plnr_idcard').addClass('is-checked');
			$('#check_certify_idcard').attr('style','display:none;');
		}else{
			$('#plnr_idcard').removeClass('is-checked');
			$('#check_certify_idcard').attr('style','display:block;');
		}
		
		if($('#input_docum_pic_01').val() != ''){
			$('#plnr_doc').addClass('is-checked');
			$('#check_certify_doc').attr('style','display:none;');
		}else{
			$('#plnr_doc').removeClass('is-checked');
			$('#check_certify_doc').attr('style','display:block;');
		}
		
        
    });

    

    
    
    
    //플래너명 사용가능확인
    $('#plnr-name').focusout(function() {

        $('#name-uav').hide();
        $('#name-av').hide();
        if ($('#plnr-name').val() < 2) {
            return;
        }

        $.ajax({
                url: "/api/plnr/plnrlist",
                data: {
                    pln_name_exists: $('#plnr-name').val()
                },
                method: "GET",
                dataType: "json"
            })
            .done(function(res) {

                if (res.state == 1) {
                    if (res.query.length == 0) {
                        nameflag = 1;
                        $('#name-av').show();
                    } else {
                        nameflag = 0;
                        $('#name-uav').show();
                    }
                    changebtnstate();
                } else {
                    //회신오류
                }
            })
            .fail(function(xhr, status, errorThrown) {
                console.log(xhr);
            }) // 
            .always(function(xhr, status) {});
    });


    //영역을터치하여 정보입력 닫기
    $('#alarm_msg_close').click(function(e) {
        $(event.target).parent('.user-msg-alarm').animate({
            top: 0,
            opacity: 0
        })
    });
    //end

    //신분증 인증, 서류 증빙리스트
    var certify_input_array = [
        '#input_idcard_pic_01, #input_docum_pic_01'
    ];

    //end
    
    // 개인/업체중 택1
    $('input[name="pln_type"]').click(function(){

        if( $(this).val() == 0 ) {

            pln_type_choice('.pln_type_01_button','.pln_type_02_button');

        }else if ( $(this).val() == 1 ){

            pln_type_choice('.pln_type_02_button','.pln_type_01_button');

        }

    });
    //end

    // 신분증 추가
    $('#input_idcard_pic_01').change(function(){
        if (this.files.length > 3){
            alert('파일은 최대 3개까지 가능합니다.');
            $(this).val('');
            return ;
        }

        // 980미만에서 한장씩 여러개 업로드, 3개이상일때 초기화
        if($("html").width()>=980 || $("#certify__idcard_thumbnail-group .certify__thumbnail-list").last().index()>=4) {
            $('#certify__idcard_thumbnail-group .new_thumnail').remove();
        }
        var j = 0;
        for (var i = 0; i < this.files.length; i++ ){
            var fr = new FileReader();
            fr.onload = function(e){
                $('#certify__idcard_thumbnail-group').prepend('<li class="certify__thumbnail-list is-filled new_thumnail" style="background-image: url('+e.target.result+')"></li>');

            }
            fr.readAsDataURL(this.files[i]);
        }

    });
    //end

    //기타 파일추가
    $('#input_docum_pic_01').change(function(){
        if (this.files.length > 5){
            alert('파일은 최대 5개까지 가능합니다.');
            $(this).val('');
            return ;
        }

        // 980미만에서 한장씩 여러개 업로드, 5개이상일때 초기화
        if($("html").width()>=980 || $("#certify__docum_thumbnail-group .certify__thumbnail-list").last().index()>=6) {
            $('#certify__docum_thumbnail-group .new_thumnail').remove();
        }
        var j = 0;
        for (var i = 0; i < this.files.length; i++ ){
        	var fr = new FileReader();
        	fr.onload = function(e){
                $('#certify__docum_thumbnail-group').prepend('<li class="certify__thumbnail-list is-filled new_thumnail" style="background-image: url('+e.target.result+')"></li>');
            }
            fr.readAsDataURL(this.files[i]);
        }

    });
    //end

    //플래너신청하기 인풋 누르면 하단네비안보이게
    $('#plnr-reg-form input').focus(function(){
        $('.wrapper--planner__scroll-area').addClass('active');
        $('.wrapper--planner_reg .bt-fixed-nav').hide();
    })

    //플래너신청하기 인풋 취소하면 하단네비보이개
    $('#plnr-reg-form input').blur(function(){
        $('.wrapper--planner__scroll-area').removeClass('active');
        $('.wrapper--planner_reg .bt-fixed-nav').show();
    })

});

//신분증인증 썸네일
function certifyURL(name) {
    if (name.files && name.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $(name).next('li').css('background-image', 'url(' + e.target.result + ')');
            $(name).next('li').addClass('is-filled')
        }
        reader.readAsDataURL(name.files[0]);
    }
}
//end

// 개인/업체중 택1
function pln_type_choice (select, noSelect){

    var pln_type_name = $(select).text();

    $(select).addClass('is-active');
    $(noSelect).removeClass('is-active');

    $('#check_plnr_type').text(pln_type_name).removeClass('is-set');

    $(select).parents('.popup__inner').removeClass('is-active');
    $('.popup--modal').delay(200).fadeOut();

}

function changebtnstate() {
        //console.log($('#plnr-popup-desc').val().length + " " + nameflag);

        if ($('#plnr-popup-desc').val().length > 2 &&
            nameflag == 1) {
            $('#plnr-nameok').addClass('is-active');
            return true;
        }
        $('#plnr-nameok').removeClass('is-active');
        return false;
}
//end

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

</script>
<style>
	.contact_input{
		border: 1px solid #E0DFDF;
	    border-radius: 7px;
	    background: transparent;
	    color: #7E7E7E;
	    padding: 0.4em 1em;
	    margin-bottom:0.5em;
	}
	.contact_input::placeholder{
		font-size:0.7em;
	}
	.contact_input:disabled{
		background:#7E7E7E;
		color:#fff;
	}
	/*문자인증 번호div*/
	.verify-contact-div{
	    width: 100%;
	    display: flex;
	    flex-direction: column;
	}
	.verify-contact-div .contact_btn{
		border: solid 1px #00244C;
	    border-radius: 5px;
	    color: #00244C;
	    font-size: 0.7em;
	    display: inline-block;
	    text-align: center;
	    background-color: #fff;
	    padding: 0.5em 1em;
	}
	/*문자인증코드 div*/
	.verify-code-div{
		display:none;
	}
	.verify-code-div.active{
		display:block;
	}
	.verify-code-div input{
		width:69%;
		display:inline-block
	}
	.verify-code-div .contact_btn{
		width:29%;
		display:inline-block
		border: 0;
	    border-radius: 5px;
	    color: #fff;
	    font-size: 0.8em;
	    display: inline-block;
	    text-align: center;
	    background-color: #00244C;
	    padding: 0.5em 1em;
	}
	
</style>
@endsection