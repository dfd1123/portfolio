@extends('company_ver.layouts.app') 
@section('content')

<div class="estimate_request_wrap">

    @include('company_ver.company_regist.include.step_bar')
    <form id="profileform" enctype="multipart/form-data">
        <input name="step" value="1" style="display:none;"/>
        <div class="sqm_wrap">
            <div class="regist1_div">
                <p>상호명 및 이름</p>
                <div class="input_div">
                    <input type="text" placeholder="상호명을 입력하세요" class="name" name="agent_name" id="agent_name"/>
                </div>
            </div>
        </div>
        <div class="sqm_wrap">
            <div class="regist1_div">
                <p>사업자 번호</p>
                <div class="input_div">
                    <input type="text" placeholder="'-'포함 10자리" class="name" name="agent_contact" id="agent_contact"/>
                </div>
            </div>
        </div>
        <div class="sqm_wrap">
            <div class="regist1_div">
                <p>사업장 전화번호</p>
                <div class="input_div">
                    <input type="text" placeholder="'-'포함 10자리" class="name" name="agent_tel_number" id="agent_tel_number"/>
                </div>
            </div>
        </div>
        <div class="sqm_wrap">
            <div class="regist1_div">
                <div class="address_wrap">
                    <input type="hidden" name="estimate_request_id" />
                    <h3>업체 주소</h3>
                    <input type="hidden" id="postcode" placeholder="우편번호">
                    <input type="hidden" id="extraAddress" placeholder="참고항목">
                    <div class="address_inp_wrap">
                        <input type="text" id="agent_addr" name="agent_addr" onclick="execDaumPostcode()" placeholder="터치하여 주소를 검색해보세요.">
                        <div class="addr_search_icon">
                            <i class="far fa-search"></i>
                        </div>
                    </div>
                    <div class="detailAddress_inp_wrap">
                        <input type="text" id="agent_detailaddr" name="agent_detailaddr" placeholder="상세주소">
                    </div>
                </div>
            </div>
        </div>
        <div class="sqm_wrap">
            <input type="hidden" name="estimate_request_id" />
            <div class="regist1_div">
                <p>사진 등록</p>
                <div class="add_img_wrap">
                    <div style="display:flex;">
                        <div style="width:66.6%;display:inline-block;">
                            <p class="title">사업자 등록증</p>
                            <p class="content">위에 입력하신 정보와 사업자 등록증의 정보가 일치하지 않을 시, 등록이 취소됩니다.</p>
                        </div>
                        <div class="add_img_li" style="inline-blick;float:right">
                            <div class="box">
                                <div class="content">
                                    <div id="first_img_addbtn" class="img_wrap">
                                        <label for="business_paper_img" data-index="1">
                                            <i class="fal fa-plus"></i><br />
                                            이미지 등록
                                        </label>
                                    </div>
                                    <div class="img_wrap" id="first_img" style="display:none;">
                                        <input type="file" name="business_paper_img" id="business_paper_img" class="hide" accept="/image/*" capture="camera" />
                                        <img id="img1" src="/images/default_add_img.png" id="preview_contruct" class="default_img" alt="default_img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex;">
                        <div style="width:66.6%;display:inline-block;">
                            <p class="title">프로필 사진</p>
                            <p class="content">시공업체 정보에 등록되는 프로필 사진입니다.</p>
                        </div>
                        <div class="add_img_li" style="inline-blick;float:right">
                            <div class="box">
                                <div class="content">
                                    <div id="second_img_addbtn" class="img_wrap">
                                        <label for="profile_img" data-index="1">
                                            <i class="fal fa-plus"></i><br />
                                            이미지 등록
                                        </label>
                                    </div>
                                    <div class="img_wrap" id="second_img" style="display:none;">
                                        <input type="file" name="profile_img" id="profile_img" class="hide" accept="/image/*" capture="camera" />
                                        <img id="img2" src="/images/default_add_img.png" id="preview_contruct" class="default_img" alt="default_img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="extra_info" value="{}"/>
    </form>
</div>

@include('company_ver.ft_button.ft_button')

<script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
<script>
$(function() {
    swal({
        title: "알림",
        text: "업체정보를 등록하셔야 이용이 가능합니다",
        button: "확인",
    });
    function readURL(input, imgorderby) {
            var dataImg = new FormData();

            $.each(input.files, function(i, file) {
                dataImg.append('file', file);
            });
            
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e){
                    if(imgorderby==1){
                        $('#first_img_addbtn').remove();
                        $('#first_img').attr('style','display:block;');
                        $('#img1').attr('src', e.target.result);
                        $('#img1').removeClass('default_img');
                    }
                    else{
                        $('#second_img_addbtn').remove();
                        $('#second_img').attr('style','display:block;');
                        $('#img2').attr('src', e.target.result);
                        $('#img2').removeClass('default_img');
                    }
                    
                    // 성공하면 실행
                    //$('#preview_contruct'+index).removeClass('default_img');
                    // $('#preview_contruct'+index).attr('src', e.target.result);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    $("#business_paper_img").change(function() {
        readURL(this, 1);
        bottomactive();
    });
    $("#profile_img").change(function() {
        readURL(this, 2);
        bottomactive();
    });

    $('#agent_name').on('keyup', function(){
        bottomactive();
    });
    $('#agent_contact').on('keyup', function(){
        bottomactive();
    });
    $('#agent_detailaddr').on('keyup', function(){
        bottomactive();
    });
    $('#agent_tel_number').on('keyup', function(){
        bottomactive();
    });

    //하단 '다음'버튼 활성화 function
    function bottomactive(){
        if( $('#agent_name').val() != '' 
        && $('#agent_contact').val() != ''
        && $('#agent_addr').val() != ''
        && $('#agent_detailaddr').val() !=''
        && $('#business_paper_img').val() !=''
        && $('#agent_tel_number').val() !=''
        && $('#profile_img').val() !=''){
            $('.ft_button button').addClass('active');
        }
        else{
            $('.ft_button button').removeClass('active');
        }
    }
    $('.ft_button button').on('click', function(){

        if($(this).hasClass('active')){
            $('#profileform').ajaxForm({
                type : "POST",
                dataType: "json",
                url : "/api/agentinfo",
                processData : false,
		    	contentType : false,
                success : function(data) {
                    if(data.query!=null && data.state==1){
                        location.href="/company_ver/company_regist/2?user_no="+data.query[0].agent_no+"";
                    }
                    else{
                        swal({
                            title: "오류",
                            text: "죄송합니다.<br/>시스템 오류로 인해 업종이 저장되지 않았습니다.<br/>다시 시도해주세요.",
                            button: "확인",
                        });
                    }
                },
                error : function(data){
                    swal({
                        title: "오류",
                        text: "죄송합니다.<br/>시스템 오류로 인해 업종이 저장되지 않았습니다.<br/>다시 시도해주세요.",
                        button: "확인",
                    });
                }
            });
            $('#profileform').submit();
        }else{
            swal({
                title: "알림",
                text: "입력값을 모두 입력해주세요",
                button: "확인",
            });
        }

    });
});
function execDaumPostcode() {
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var addr = ''; // 주소 변수
                var extraAddr = ''; // 참고항목 변수

                //사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    addr = data.roadAddress;
                } else { // 사용자가 지번 주소를 선택했을 경우(J)
                    addr = data.jibunAddress;
                }

                // 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
                if(data.userSelectedType === 'R'){
                    // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                    // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                    if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있고, 공동주택일 경우 추가한다.
                    if(data.buildingName !== '' && data.apartment === 'Y'){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                    if(extraAddr !== ''){
                        extraAddr = ' (' + extraAddr + ')';
                    }
                    // 조합된 참고항목을 해당 필드에 넣는다.
                    document.getElementById("extraAddress").value = extraAddr;
                
                } else {
                    document.getElementById("extraAddress").value = '';
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById('postcode').value = data.zonecode;
                document.getElementById("agent_addr").value = addr;

                if(addr != ''){
                    $('#agent_addr').css('background','#0079cf');
                    $('.address_wrap .address_inp_wrap .addr_search_icon').html('<i class="far fa-check"></i>');
                    $('.address_wrap .address_inp_wrap .addr_search_icon i').css('color','#fff');
                }else{
                    $('#agent_addr').css('background','#d5dcea');
                    $('.address_wrap .address_inp_wrap .addr_search_icon').html('<i class="far fa-search"></i>');
                    $('.address_wrap .address_inp_wrap .addr_search_icon i').css('color','#222');
                }
                // 커서를 상세주소 필드로 이동한다.
                document.getElementById("agent_detailaddr").focus();
            }
        }).open();
    }
</script>

@endsection