@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--pay">

        <div class="hd-title hd-title--04">
            <button type="button" class="hd-title__left-btn hd-title__left-btn--close" onClick="history.back()"><span
                    class="none">닫기버튼</span></button>
            <h2 class="hd-title__center">결제페이지</h2>
        </div>
    
        <div class="wrapper--inner" >
            <fieldset class="calcul_card">

                <div class="pay_horizen_line">
                    <label class="pay_label_tit">결제금액</label>
                    <input type="text" class="pay_input_form" id="rsrv_price" value="{{ $pay_info[0]->rsrv_price }}" readonly>
                </div>

                <div class="pay_horizen_line">
                    <label class="pay_label_tit">보유포인트 (<span id="user_point">{{ $pay_info[0]->user_point }}</span> P)</label>
                    <div class="pay_input_form_group">
                        <input type="number" class="pay_input_form border_bt" id="input_point" value="0" min="0">
                        <button type="button" class="pay_mini_btn" id="use_point">포인트 사용</button>
                    </div>
                </div>
                
            </fieldset>

            <div class="pay_price_type_con">
            
                <div class="pay_horizen_line pay_total_price">
                    <label class="pay_label_tit">총 결제금액</span></label>
                    <input type="text" class="pay_input_form" id="final_price" value="{{ $pay_info[0]->rsrv_price }}" readonly>
                </div>

                <div class="pay_horizen_line">
                    <label class="pay_label_tit">결제수단</label>
                    <div class="pay_type_btn_group" id="pay_type_btn_group">
                        <input type="radio" name="pg_type" value = "creditcard" checked id="creditcard">
                        <input type="radio" name="pg_type" value = "payco" id="payco">
                        <input type="radio" name="pg_type" value = "kakaopay" id="kakaopay">
                        <input type="radio" name="pg_type" value = "banktransfer" id="banktransfer">
                        <input type="radio" name="pg_type" value = "virtualaccount" id="virtualaccount">
                        
                        <label for="creditcard" class="pay_type_btn active">신용카드</label>
                        <label for="payco" class="pay_type_btn">페이코신용카드</label>
                        <label for="kakaopay" class="pay_type_btn">카카오페이</label>
                        <label for="banktransfer" class="pay_type_btn">인터넷뱅킹</label>
                        <label for="virtualaccount" class="pay_type_btn">가상계좌</label>
                    </div>
                </div>

            </div>
            
            <div class="wrapper--pay__btn">
                <button type="button" class="button" id="btn_pay">결제하기</button>
            </div>
        </div>
        
        <div class="ft-info">
            
            <ul class="ft-info__group">
                <li class="ft-info__list">
                    <span class="ft-info__list__label">사업자명</span>
                    <p class="ft-info__list__txt">(주) 아제타<b class="ft-info__list__border">|</b></p>
                    <span class="ft-info__list__label">사업자등록번호</span>
                    <p class="ft-info__list__txt">685-81-01416</p>
                </li>
                <li class="ft-info__list">
                    <span class="ft-info__list__label">통신판매업 신고번호</span>
                    <p class="ft-info__list__txt">2019-서울강남-04282</p>
                </li>
                <li class="ft-info__list">
                    <span class="ft-info__list__label">대표자명</span>
                    <p class="ft-info__list__txt">정병욱<b class="ft-info__list__border">|</b></p>
                    <span class="ft-info__list__label">이메일</span>
                    <p class="ft-info__list__txt">tripick@naver.com</p>
                </li>
                <li class="ft-info__list">
                    <span class="ft-info__list__label">고객센터 전화번호</span>
                    <p class="ft-info__list__txt">02-567-1336</p>
                </li>
                <li class="ft-info__list">
                    <span class="ft-info__list__label">주소</span>
                    <p class="ft-info__list__txt">서울시 강남구 테헤란로 25길 6-9, 4층 404호(석암빌딩)</p>
                </li>
            </ul>
            <br>
            <br>
            <ol class="ft-info__group ft-info__group--02">
                <li class="ft-info__list--02" onclick="location.href='/terms01';">tripick 이용약관<b class="ft-info__list__border">|</b></li>
                <li class="ft-info__list--02" onclick="location.href='/terms01';">개인정보 수집 및 이용약관</li>
            </ol>
        </div>

        
    </div>
    
@endsection

@section('script')
<script>
    $(function(){
        $('#btn_pay').click(function(){
            var rsrv_price = $('#rsrv_price').val();
            var input_point = $('#input_point').val();
            var final_price = $('#final_price').val();
            var pg_type = $(':input:radio[name=pg_type]:checked').val();
            var user_point = {{ $pay_info[0]->user_point }};
            
            if(parseInt(rsrv_price) - parseInt(input_point) === parseInt(final_price)){

                if(parseInt(final_price) === 0){
                    dialog.confirm({
                        title:'알림',  
                        message: '<p class="single-msg">정말 포인트로 전부 결제하시겠습니까?</p>',
                        button: "예",
                        cancel: "아니오",
                        callback: function(value){
                            if(value){
                                $.ajax({
                                    url: "/api/reserve/pay",
                                    data: {
                                        rsrv_id: {{ $pay_info[0]->rsrv_id }},
                                        use_point: input_point
                                    },
                                    method: "PUT",
                                    dataType: "json",
                                    async:false
                                })
                                .done(function(res) {
                                    if (res.state == 1) {
                                        dialog.alert({
                                            title:'알림',  
                                            message: '결제가 완료되었습니다. 마이페이지 예약내역에서 확인해주세요',
                                            button: "확인",
                                            callback: function(value){
                                                location.href='/mypage/mypage';
                                            }
                                        });
                                    } else {
                                        dialog.alert({
                                            title:'오류',  
                                            message: res.msg,
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
                }else{
                    $.ajax({
                        url: "/api/payletter",
                        data: {
                            rsrv_id: {{ $pay_info[0]->rsrv_id }},
                            use_point: input_point,
                            pg_type: pg_type,
                        },
                        method: "POST",
                        dataType: "json",
                        async:false
                    })
                    .done(function(res) {
                    	console.log(JSON.parse(res));
                        var mobile_kind = getMobileOperatingSystem();
                        if(mobile_kind == "Android"){
                            window.location.assign(JSON.parse(res).mobile_url);
                        }else if(mobile_kind == "iOS"){
                            window.location.assign(JSON.parse(res).mobile_url);
                        }else{
                            window.open(
                                JSON.parse(res).online_url,
                                "_blank",
                                "width=#, height=#"
                            );
                        }
                        
                    })
                    .fail(function(xhr, status, errorThrown) {
                        console.log(xhr);
                    }) // 
                    .always(function(xhr, status) {});
                }

            }else{
                dialog.alert({
                    title:'오류',  
                    message: '포인트를 입력하셨으나 사용버튼을 안누르셨습니다.<br>포인트를 0으로 바꾸시거나 포인트 사용 버튼을 눌러주세요.',
                    button: "확인"
                });
            }
        });
        $('#use_point').click(function(){
            var rsrv_price = $('#rsrv_price').val();
            var input_point = $('#input_point').val();
            var user_point = {{ $pay_info[0]->user_point }};
            
            if(input_point == ''){
                input_point = 0;
            }

            if(parseInt(user_point) < parseInt(input_point)){
                dialog.alert({
                    title:'오류',  
                    message: '<p class="single-msg">보유포인트보다 사용하려는 포인트가 많습니다. 보유하신 최대포인트로 적용됩니다.</p>',
                    button: "확인",
                    callback: function(value){
                        input_point = user_point;
                        if(parseInt(input_point) >= parseInt(rsrv_price)){
                            $('#final_price').val(0);
                            $('#input_point').val(rsrv_price);
                        }else{
                            $('#final_price').val(rsrv_price - input_point);
                            $('#input_point').val(user_point);
                        }
                        
                    }
                });
            }else{
                if(parseInt(input_point) >= parseInt(rsrv_price)){
                    $('#final_price').val(0);
                    $('#input_point').val(rsrv_price);
                }else{
                    $('#final_price').val(rsrv_price - input_point);
                }
            }
        });

        $('#pay_type_btn_group .pay_type_btn').click(function(e){
            $(event.target).addClass('active');
            $('#pay_type_btn_group .pay_type_btn').not(event.target).removeClass('active');
        });
    });
    function getMobileOperatingSystem() {
	    var userAgent = navigator.userAgent || navigator.vendor || window.opera;
	
	    // Windows Phone must come first because its UA also contains "Android"
	    if (/windows phone/i.test(userAgent)) {
	        return "Windows Phone";
	    }
	
	    if (/android/i.test(userAgent)) {
	        return "Android";
	    }
	
	    // iOS detection from: http://stackoverflow.com/a/9039885/177710
	    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
	        return "iOS";
	    }
	    return "unknown";
	}
</script>
@endsection