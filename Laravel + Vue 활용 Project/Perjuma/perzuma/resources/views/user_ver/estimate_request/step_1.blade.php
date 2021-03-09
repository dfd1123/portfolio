@extends('user_ver.layouts.app') 
@section('content')

<div class="estimate_request_wrap" style="background:#fff;">

    @include('user_ver.estimate_request.include.step_bar')
    <input type="hidden" name="trd_no" value="{{$trd_no}}" />
    <div class="address_wrap">
        <div class="addr_w_box">
            <h3>가게명</h3>
            <div class="store_inp_wrap">
                <input type="text" id="store_name" placeholder="가게명을 입력해주세요.">
            </div>
        </div>
        <div class="addr_w_box">
            <h3>주소 검색</h3>
            <input type="hidden" id="postcode" placeholder="우편번호">
            <input type="hidden" id="extraAddress" placeholder="참고항목">
            <div class="address_inp_wrap">
                <input type="text" id="address" onclick="execDaumPostcode()" placeholder="터치하여 주소를 검색해보세요.">
                <div class="addr_search_icon">
                    <i class="far fa-search"></i>
                </div>
            </div>
            <div class="detailAddress_inp_wrap">
                <input type="text" id="detailAddress" placeholder="상세주소">
            </div>
        </div>
    </div>
        
</div>

@include('user_ver.ft_button.ft_button')

<style>
    #content{
        background:#fff;
    }
</style>

<script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
<script>

$(function() {
    var trd_no = $('input[name="trd_no"]').val();

    if(trd_no != ''){
            data_load($('#step_index').data('index'));
        }

    function data_load(step){
        $.ajax({
            type : "GET",
            dataType: "json",
            data : {trd_no : trd_no, step : step},
            url : "/api/estimate_request/load",
            success : function(data) {
                if(data.trd_name != null && data.address != null){
                    $('#store_name').val(data.trd_name);
                    $('#address').val(data.address);
                    $('#detailAddress').val(data.detail_address);
                    $('#postcode').val(data.post_num);

                    $('#address').css('background','#0079cf');
                    $('.address_wrap .address_inp_wrap .addr_search_icon').html('<i class="far fa-check"></i>');
                    $('.address_wrap .address_inp_wrap .addr_search_icon i').css('color','#fff');

                    if( $('#address').val() != '' && $('#detailAddress').val() != '' && $('#store_name').val() != '' ){
                        $('.ft_button button').addClass('active');
                    }
                }
            },
            error : function(data){
                swal({
                    title: "네트워크 오류",
                    text: "잠시 후 다시 시도해주세요.",
                    button: "확인",
                });
            }
        });
    }

    if(trd_no == ''){
        $.ajax({
            type : "GET",
            dataType: "json",
            url : "/api/estimate_request/chkhistory",
            success : function(data) {
                if(data.redirect != 0){
                    swal({
                        title: "알림",
                        text: "완료하지 못한 견적 신청이 있습니다.\n이전 견적요청을 이어서 진행하시겠습니까?",
                        buttons: {
                            yes: {
                                text: "예",
                                value: true,
                            },
                            no: {
                                text: "아니오",
                                value: false,
                            },
                        },
                    }).then((value) => {
                        if(value){
                            location.href="/user_ver/estimate_request/"+data.redirect+"?id="+data.trd_no+"";
                        }else{
                            $.ajax({
                                type : "DELETE",
                                dataType: "json",
                                url : "/api/estimate_request/"+data.trd_no+"",
                                success : function(data) {
                                    if(data.status){
                                        location.href='/user_ver';
                                    }else{
                                        swal({
                                            title: "네트워크 오류",
                                            text: "잠시 후 다시 시도해주세요.",
                                            button: "확인",
                                        });
            
                                        location.reload();
                                    }
                                },
                                error : function(data){
                                    swal({
                                        title: "삭제 불가",
                                        text: "이미 시공이 진행되었거나 견적신청이\n종료됬을 경우 삭제할 수 없습니다.",
                                        button: "확인",
                                    });
                                }
                            });
                        }
                    });
                }
            },
            error : function(data){
                swal({
                    title: "네트워크 오류",
                    text: "네트워크 오류가 발생하여 잠시 후 다시 시도해주세요.",
                    button: "확인",
                });
            }
        });
    }
    

    if( $('#address').val() != '' && $('#detailAddress').val() != '' ){
        $('.ft_button button').addClass('active');
    }else{
        $('.ft_button button').removeClass('active');
    }

    $('#store_name').on('keyup', function(){
        if( $('#address').val() != '' && $('#detailAddress').val() != '' && $('#store_name').val() != '' ){
            $('.ft_button button').addClass('active');
        }else{
            $('.ft_button button').removeClass('active');
        }
    });

    $('#detailAddress').on('keyup', function(){
        if( $('#address').val() != '' && $('#detailAddress').val() != '' && $('#store_name').val() != '' ){
            $('.ft_button button').addClass('active');
        }else{
            $('.ft_button button').removeClass('active');
        }
    });
    

    $('.ft_button button').on('click', function(){

        var estimate_request_id = $('#estimate_request_id').val();
        var address = $('#address').val();
        var detail_address = $('#detailAddress').val();
        var post_num = $('#postcode').val();
        var store_name = $('#store_name').val();

        if($(this).hasClass('active')){
            // 업종 id인 upstream_id를 Ajax의 data 값으로 전달하여 API 전달

            // Ajax 성공하면..
            $.ajax({
                type : "POST",
                dataType: "json",
                data : {step : 1, trd_no : trd_no, store_name : store_name, address : address, detail_address : detail_address, post_num : post_num},
                url : "/api/estimate_request",
                success : function(data) {
                    if(data.verify){
                        location.href="/user_ver/estimate_request/2?id="+data.id+"";
                    }else{
                        swal({
                            title: "인증 필요",
                            text: "이메일 인증을 하신 후에\n이용이 가능합니다.\n가입하신 이메일 주소의\n메일함을 확인하세요.",
                            button: "확인",
                        });
                    }
                },
                error : function(data){
                    swal({
                        title: "오류",
                        text: "죄송합니다.\n시스템 오류로 인해 정보가 저장되지 않았습니다.\n다시 시도해주세요.",
                        button: "확인",
                    });
                }
            });

        }else{
            swal({
                title: "알림",
                text: "시공 예정 장소를 입력하여 주세요.",
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
                document.getElementById("address").value = addr;

                if(addr != ''){
                    $('#address').css('background','#0079cf');
                    $('.address_wrap .address_inp_wrap .addr_search_icon').html('<i class="far fa-check"></i>');
                    $('.address_wrap .address_inp_wrap .addr_search_icon i').css('color','#fff');
                }else{
                    $('#address').css('background','#d5dcea');
                    $('.address_wrap .address_inp_wrap .addr_search_icon').html('<i class="far fa-search"></i>');
                    $('.address_wrap .address_inp_wrap .addr_search_icon i').css('color','#222');
                }
                // 커서를 상세주소 필드로 이동한다.
                document.getElementById("detailAddress").focus();
            }
        }).open();
    }
</script>

@endsection