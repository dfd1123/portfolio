@extends('company_ver.layouts.app') 
@section('content')
<div class="sub_hd2">
    <div class="center_hd">
        <div class="header">
            <form id="profileimgform" enctype="multipart/form-data">
            <input type="hidden" name="step" value="6"/>
                @if(isset($profileimg->profile_img))
                <img id="profile_img" class="profile_thumb" data-before="/storage/fdata/agent/thumb{{$profileimg->profile_img}}" src="/storage/fdata/agent/thumb{{$profileimg->profile_img}}"/>
                <input type="file" name="profile_img" accept="image/*" style="display:none;"/>
                @else
                <img id="profile_img" class="profile_thumb" data-before="/adminassets/images/users/5.jpg" src="/adminassets/images/users/5.jpg"/>
                <input type="file" name="profile_img" accept="image/*"style="display:none;"/>
                @endif
            </form>
            @if(isset($agent[0]->agent_name))
            <h6 class="agent_name" id="agent_name" name="agent_name" data-txtmax="10" data-beforedata="{{$agent[0]->agent_name}}">{{$agent[0]->agent_name}}</h6>
            @else
            <h6 class="agent_name" id="agent_name" name="agent_name" data-edt="123" data-txtmax="10" data-beforedata="">업체명 없음</h6>
            @endif
            <div class="cnt-box">
                <p class="agent_construction_cnt">시공 
                    @if(isset($agent[0]->agent_construction_cnt))
                    <em>{{$agent[0]->agent_construction_cnt}}</em> 건</p>
                    @else
                    <em>0</em> 건</p>
                    @endif
                <p class="agent_review_cnt" onclick="javascript:rvpopup();">리뷰 
                    @if(isset($agent[0]->agent_review_cnt))
                    <em>{{$agent[0]->agent_review_cnt}}</em> 건</p>
                    @else
                    <em>0</em> 건</p>
                    @endif
            </div>
            <div class="rating-box">
                @if($agent[0]->agent_rating >= 0 && $agent[0]->agent_rating < 1)
                <img class="agent_rating_img" src="/images/star/star_rating_0.0.svg"/>
                @elseif($agent[0]->agent_rating >= 1 && $agent[0]->agent_rating < 2)
                <img class="agent_rating_img" src="/images/star/star_rating_1.0.svg"/>
                @elseif($agent[0]->agent_rating >= 2 && $agent[0]->agent_rating < 3)
                <img class="agent_rating_img" src="/images/star/star_rating_2.0.svg"/>
                @elseif($agent[0]->agent_rating >= 3 && $agent[0]->agent_rating < 4)
                <img class="agent_rating_img" src="/images/star/star_rating_3.0.svg"/>
                @elseif($agent[0]->agent_rating >= 4 && $agent[0]->agent_rating < 5)
                <img class="agent_rating_img" src="/images/star/star_rating_4.0.svg"/>
                @elseif($agent[0]->agent_rating == 5)
                <img class="agent_rating_img" src="/images/star/star_rating_5.0.svg"/>
                @endif
                <p class="agent_rating_txt">
                @if(isset($agent[0]->agent_rating))
                    <em>{{$agent[0]->agent_rating}}</em> / 5</p>
                @else
                    <em>0</em> / 5</p>
                @endif
            </div>
            <input type="button" class="udt-btn" value="프로필 수정" />
        </div>
    </div>
    <div id="info_complete" class="right_hd" style="display:none;">
        <p>완료</p>
    </div>
    <div id="info_cancel" class="left_hd" style="display:none;">
        <p>취소</p>
    </div>
    <div class="main">
        <div class="section">
            <div class="title">내정보</div>
            <div class="content">
                <div class="left">이름</div>
                <div class="right" id="user_name" name="user_name" data-txtmax="16" data-beforedata="{{$agent[0]->name}}">{{$agent[0]->name}}</div>
            </div>
            <div class="content">
                <div class="left">휴대폰 번호</div>
                <div class="right" id="user_contact" name="user_contact" data-txtmax="16" data-beforedata="{{$agent[0]->user_contact}}">{{$agent[0]->user_contact}}</div>
            </div>
            <div class="content">
                <div class="left">이메일</div>
                <div class="right" id="email" name="email" data-txtmax="64" data-beforedata="{{$agent[0]->email}}">{{$agent[0]->email}}</div>
            </div>
        </div>
        <div class="section">
            <div class="title">사업자 정보</div>
            <div class="content">
                <div class="left">사업장 전화번호</div>
                <div class="right" id="agent_tel_number" name="agent_tel_number" data-txtmax="16" data-beforedata="{{$agent[0]->agent_tel_number}}">{{$agent[0]->agent_tel_number}}</div>
            </div>
            <div class="content">
                <div class="left">사업장 소재지</div>
                <div class="right" id="agent_addr" name="agent_addr" data-txtmax="64" data-beforedata="{{$agent[0]->agent_addr}}">{{$agent[0]->agent_addr}}</div>
            </div>
            <div class="content">
                <div class="left">상세주소</div>
                <div class="right" id="agent_detailaddr" name="agent_detailaddr">{{$agent[0]->agent_detailaddr}}</div>
            </div>
            <div class="content">
                <div class="left">사업자등록증</div>
                @if(!empty(json_decode($agent[0]->agent_profile_img)->business_paper_img))
                <div class="right" id="agent_bn_img">확인</div>
                <input type="hidden" id="businesspaper_val" value="1"/>
                @else
                <div class="right" id="agent_bn_img"></div>
                <input type="hidden" id="businesspaper_val" value="0"/>
                <input type="file" id="imgs" name="imgs" style="display:none;"/>
                @endif
            </div>
            <div class="content">
                <div class="left">사업자 번호</div>
                <div class="right" id="agent_contact" name="agent_contact" data-txtmax="16" data-beforedata="{{$agent[0]->agent_contact}}">{{$agent[0]->agent_contact}}</div>
            </div>
        </div>
        <div class="section">
            <div class="title">추가 입력정보</div>
            <div class="content">
                <div class="left">주요 시공 분야</div>
                <div class="right" id="agent_main_business" name="agent_main_business">
                    @if(isset($info->bl_name))
                    {{$info->bl_name}}
                    @endif
                </div>
            </div>
            <div class="content">
                <div class="left">이동 가능 거리</div>
                @if(isset($info->agent_distance))
                    @if($info->agent_distance =='전국')
                    <div class="right" id="agent_distance" name="agent_distance">{{$info->agent_distance}}</div>
                    @else
                    <div class="right" id="agent_distance" name="agent_distance">0 ~ {{$info->agent_distance}}km</div>
                    @endif
                @else
                    <div class="right" id="agent_distance" name="agent_distance"></div>
                @endif
            </div>
            <div class="content">
                <div class="left">시공 경력</div>
                <div class="right" id="agent_career" name="agent_career">
                    @if(isset($info->agent_career))
                    {{$info->agent_career}}
                    @endif
                </div>
            </div>
            <button class="logoutbtn" id="logoutbtn">로그아웃</button>
            <button class="logoutbtn" id="unregistbtn">회원탈퇴</button>
        </div>
    </div>
</div>
<div class="editbox" data-edt="" style="display:none;">
    <div class="editroot">
        <div class="editabdiv">
            <input class="editinput" type="text" placeholder="입력해 주세요" />
            <span class="txtclearbtn">X</span>
        </div>
        <p class="txtcnt">
            <span id="txtlength"></span>/<em id="txtmax" style="font-weight:inherit"></em></p>
        </p>
    </div>
    <div id="edit_complete" class="right_hd">
        <p>완료</p>
    </div>
    <div id="edit_cancel" class="left_hd">
        <p>취소</p>
    </div>
</div>

@include('company_ver.layouts.footer')
<script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
<script>
var user_no = {{auth()->user()->user_no}};
$(function(){
    $('.editinput').width($(window).width() * 0.7);
    //글자입력수 체크
    var ipt = $('.editinput');
    var clearIpt = $('.txtclearbtn');

    ipt.keyup(function(){
        clearIpt.toggle(!!ipt.val());
        $('#txtlength').html(ipt.val().length);
    });

    clearIpt.click(function(){
        ipt.val('').keyup().focus();
    });
    
    //이미지 변경(업로드파일 이름)
    $('#imgs').change(function(e){
        var imgname = this.value.split('\\');
        $('#agent_bn_img').html(imgname[imgname.length-1]);
    });
});
//리뷰팝업창 띄우기
function rvpopup(){
    $('.contruct_status_wrap').addClass('active');
    $('#content').on('scroll touchmove mousewheel', function(event) {
        event.preventDefault();
        event.stopPropagation();
        return false;
    });
}
//수정팝업창 띄우기
function edtboxOpen(dataname){
   // $('#footer').hide();
    $('.editbox').attr('style','display:block;height:'+$(window).height()+'px');
    $('.editbox').attr('data-edt',$(dataname).attr('id'));
    $('#content').on('scroll touchmove mousewheel', function(event) {
        event.preventDefault();
        event.stopPropagation();
        return false;
    });
    $('.editinput').val($(dataname).text());
    $('.editinput').attr('maxlength',$(dataname).data('txtmax'));
    $('#txtlength').html($(dataname).text().length);
    $('#txtmax').html($(dataname).data('txtmax'));
}
//수정 팝업 완료버튼
$('#edit_complete').click(function(){
    //$('#footer').show();
    if($('.editinput').val().length){
        event.stopPropagation();
        $('#content').off('scroll touchmove mousewheel');
        $('#'+$('.editbox').attr('data-edt')).html($('.editinput').val());
        $('.editbox').attr('style','display:none;');
        $('.editbox').attr('data-edt','');
    }else{
        swal({
            title: "수정",
            text: "공백으로 수정할 수 없습니다",
            button: "확인",
        });
    }
});
//수정 팝업 취소버튼
$('#edit_cancel').click(function(){
    $('#footer').show();
    event.stopPropagation();
    $('#content').off('scroll touchmove mousewheel');
    $('.editbox').attr('style','display:none;');
    $('.editbox').attr('data-edt','');
});
// var _URL = window.URL || window.webkitURL;
$('[name=profile_img]').change(function(){
    /* var file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function () {
            alert(this.width + " " + this.height);
        };
        img.src = _URL.createObjectURL(file);
    } */

    readURL(this);
    $('#profileimgform').ajaxForm({
        type : "POST",
        dataType: "json",
        url : "/api/agentinfo",
        processData : false,
        contentType : false,
        success : function(data) {
            if(data.state==1 && data.query != null){
                swal({
                    title: "시스템",
                    text: "프로필이 변경되었습니다.",
                    button: "확인",
                });
            }
            else{
                swal({
                    title: "오류",
                    text: "죄송합니다.\n시스템 오류로 인해 사진수정이 실패하였습니다.",
                    button: "확인",
                });
            }
        },
        error : function(data){
            swal({
                title: "오류",
                text: "죄송합니다.\n시스템 오류로 인해 사진수정이 실패하였습니다.",
                button: "확인",
            });
        }
    });
    $('#profileimgform').submit();
});

//수정하기 버튼
$('.udt-btn').click(function(){
    $('#profile_img').attr('onclick',"javascript:profileclick();")

    $('.udt-btn').attr('style','display:none;');
    $('#info_complete').attr('style','display:block');
    $('#info_cancel').attr('style','display:block');

    $('#agent_name').addClass('edit');
    $('#agent_name').attr('onclick',"edtboxOpen(this)");

    $('#agent_addr').addClass('info_edit');
    $('#agent_addr').attr('onclick',"execDaumPostcode()");

    udtclick(user_name);
    udtclick(user_contact);
    udtclick(email);
    udtclick(agent_tel_number);
    udtclick(agent_detailaddr);
    udtclick(agent_contact);
    agentinfoudt(agent_main_business);
    agentinfoudt(agent_distance);
    agentinfoudt(agent_career);
});
function udtclick(name){
    $(name).addClass('info_edit');
    $(name).attr('onclick',"javascript:edtboxOpen("+$(name).attr('id')+")");
}
function agentinfoudt(name){
    $(name).addClass('info_edit');
    $(name).attr('onclick',"javascript:edtpage('"+$(name).attr('id')+"',"+user_no+")");
}
function edtpage(name,user_no){
    switch(name){
        case 'agent_main_business':
            location.href='/company_ver/company_regist/2?user_no='+user_no+'&type=1';
        break;
        case 'agent_distance':
            location.href='/company_ver/company_regist/3?user_no='+user_no+'&type=1';
        break;
        case 'agent_career':
            location.href='/company_ver/company_regist/4?user_no='+user_no+'&type=1';
        break;
    }
}
function profileclick(){
    $('[name=profile_img]').click();
}
//수정하기 완료, 완료되면 저장하고 DB업데이트 ajax
$('#info_complete').click(function(){
    
    var param={
        'user_no' : user_no,
        'name' : $('#user_name').text(),
        'user_contact' : $('#user_contact').text(),
        'email' : $('#email').text(),
        'agent_name' : $('#agent_name').text(),
        'agent_tel_number' : $('#agent_tel_number').text(),
        'agent_addr' : $('#agent_addr').text(),
        'agent_detailaddr' : $('#agent_detailaddr').text(),
        'agent_contact' : $('#agent_contact').text(),
        //'business_paper_img' : $('#imgs')[0].files[0]
    }
    $.ajax({
        type : "PUT",
        dataType: "json",
        data: param,
        url : "/api/agentinfo/normalinfo",
        success : function(data) {
            if(data.query!=null && data.state==1){
                swal({
                    title: "시스템",
                    text: "정보가 수정되었습니다.",
                    button: "확인",
                });
                $('#profile_img').attr('onclick',"");

                $('.udt-btn').attr('style','display:block;');
                $('#info_complete').attr('style','display:none');
                $('#info_cancel').attr('style','display:none');

                $('#agent_name').removeClass('edit');
                $('#agent_name').attr('onclick',"");
                $('#agent_name').attr('data-beforedata',$('#agent_name').text());

                $('#agent_bn_img').removeClass('img_edit');
                $('#agent_bn_img').attr('onclick',"");
                $('#agent_bn_img').attr('data-beforedata',$('#agent_bn_img').text());

                complete(user_name);
                complete(user_contact);
                complete(email);
                complete(agent_tel_number);
                complete(agent_detailaddr);
                complete(agent_addr);
                complete(agent_contact);
                complete(agent_main_business);
                complete(agent_distance);
                complete(agent_career);
            }
            else{
                swal({
                    title: "오류",
                    text: "프로필 정보를 공백없이 입력해주세요",
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
});
function complete(name){
    $(name).removeClass('info_edit');
    $(name).attr('onclick',"");
    $(name).attr('data-beforedata',$(name).text());
}
//수정하기 취소. 원상복귀
$('#info_cancel').click(function(){
    $('[name=profile_img]').val('');
    $('#profile_img').attr('onclick',"");
    // $('#profile_img').attr('src',$('#profile_img').attr('data-before'));
    $('.udt-btn').attr('style','display:block;');
    $('#info_complete').attr('style','display:none');
    $('#info_cancel').attr('style','display:none');

    $('#agent_name').removeClass('edit');
    $('#agent_name').attr('onclick',"");
    $('#agent_name').html($('#agent_name').attr('data-beforedata'));
    $('[name=agent_name]').val($('#agent_name').attr('data-beforedata'));

    $('#agent_bn_img').removeClass('img_edit');
    $('#agent_bn_img').attr('onclick',"");
    cancel(user_name);
    cancel(user_contact);
    cancel(email);
    cancel(agent_tel_number);
    cancel(agent_detailaddr);
    cancel(agent_addr);
    cancel(agent_contact);
    cancel(agent_main_business);
    cancel(agent_distance);
    cancel(agent_career);
});
function cancel(name){
    $(name).removeClass('info_edit');
    $(name).attr('onclick',"");
    $(name).html($(name).attr('data-beforedata'));
}
function readURL(input) {
        
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            
            $('#profile_img').attr('src', e.target.result);
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
$('#logoutbtn').click(function(){
    logout();
});
$('#unregistbtn').click(function(){
    unregist();
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
                    //document.getElementById("extraAddress").value = extraAddr;
                
                } else {
                    //document.getElementById("extraAddress").value = '';
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                //document.getElementById('postcode').value = data.zonecode;
                $("#agent_addr").html(addr);

                // 커서를 상세주소 필드로 이동한다.
                //document.getElementById("detailAddress").focus();
            }
        }).open();
    }
</script>
@endsection