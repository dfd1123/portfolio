@extends('layouts.app')

@section('content')

<div class="wrapper wrapper--mypage">
    
    <div class="hd-title hd-title--01">
        <button type="button" onClick="history.back()" class="hd-title__left-btn hd-title__left-btn--prev"><span class="none">이전버튼</span></button>
        <h2 class="hd-title__center">회원탈퇴</h2>
    </div>
    <form id="frm_withdraw" method="POST" action="/api/Users/usrstate">
    <input type="hidden" name="_method" value="put">
    <div class="wrapper--mypage__scroll-area">
        <div class="mypage_withdraw">
            <h3>회원탈퇴 안내</h3>
            <div class="mypage_withdraw_section first">
                <h4 class="mypage_withdraw_tit">탈퇴 신청 전 아래사항을 꼭 확인해주세요.</h4>
                <span class="mypage_withdraw_text">탈퇴 시 회원정보를 포함한 유료상품 및 모든 서비스 내역은 최장 X개월까지 보관됩니다.</span>
                <span class="mypage_withdraw_text">탈퇴 시 보관된 회원정보와 서비스내역들의 복구를 원할 시, 고객센터 0000-0000으로 문의바랍니다.</span>
                <span class="mypage_withdraw_text">기타 작성사항 3</span>
            </div>
            <div class="mypage_withdraw_section">
                <h4 class="mypage_withdraw_tit">회원탈퇴 사유 선택</h4>
                <span class="mypage_withdraw_text">
                    <input type="checkbox" name="user_withdraw_reason[]" id="withdraw_reason01" value="컨텐츠 내용 부족" class="input-style-01">
                    <label for="withdraw_reason01"></label>
                    <label for="withdraw_reason01">컨텐츠 내용 부족</label>
                </span>
                <span class="mypage_withdraw_text">
                    <input type="checkbox" name="user_withdraw_reason[]" id="withdraw_reason02" value="서비스 이용 불편" class="input-style-01">
                    <label for="withdraw_reason02"></label>
                    <label for="withdraw_reason02">서비스 이용 불편</label>
                </span>
                <span class="mypage_withdraw_text">
                    <input type="checkbox" name="user_withdraw_reason[]" id="withdraw_reason03" value="가격 불만족" class="input-style-01">
                    <label for="withdraw_reason03"></label>
                    <label for="withdraw_reason03">가격 불만족</label>
                </span>
                <span class="mypage_withdraw_text">
                    <input type="checkbox" name="user_withdraw_reason[]" id="withdraw_reason04" value="방문 횟수 거의 없음" class="input-style-01">
                    <label for="withdraw_reason04"></label>
                    <label for="withdraw_reason04">방문 횟수 거의 없음</label>
                </span>
                <span class="mypage_withdraw_text">
                    <input type="checkbox" name="user_withdraw_reason[]" id="withdraw_reason05" value="기타" class="input-style-01">
                    <label for="withdraw_reason05"></label>
                    <label for="withdraw_reason05">기타</label>
                </span>
            </div>
            <div class="mypage_withdraw_section last">
                <span class="mypage_withdraw_text">
                    <input type="checkbox" id="withdraw_final_ok" class="input-style-01">
                    <label for="withdraw_final_ok"></label>
                    <label for="withdraw_final_ok">안내사항을 모두 확인하였으며, 이에 동의합니다.</label>
                </span>
            </div>
            <div class="mypage_withdraw_button">
                <button type="button" class="button" onclick="withdraw_chck();">탈퇴하기</button>
            </div>   
        </div> 
    </div>
    </form>
</div>

@include('nav.nav_user')

@endsection

@section('script')
<script>
$(function(){
    $('#frm_withdraw').ajaxForm({
        dataType : "json",
        success: function(data) {
            console.log(data);
            if(data.state == 1){
                
                $.ajax({
                    url: "/api/logout",
                    method: "POST",
                    dataType: "json",
                    async:false
                })
                .done(function(data) {
                    if(data){
                        setTimeout(function(){
                            dialog.alert({
                                title:'회원탈퇴',
                                message: '회원 탈퇴가 진행되었습니다.',
                                button: "확인",
                                callback: function(value){
                                    location.href='/login';
                                }
                            });
                        },500);
                    }
                })
                .fail(function(xhr, status, errorThrown) {
                    console.log(xhr);
                }) // 
                .always(function(xhr, status) {});

            }else{
                dialog.alert({
                    title:'오류',  
                    message: data.msg,
                    button: "확인"
                });
            }
        }    
    });
});
// 탈퇴 확인 팝업
function withdraw_chck(){
    var checkbox_chk = 0;
    if($('#withdraw_final_ok').prop("checked")){
        $('input:checkbox[name="user_withdraw_reason[]"]:checked').each(function(){
            checkbox_chk++;
        });
        if(checkbox_chk > 0){
            dialog.confirm({
                title:'알림',  
                message: '<p class="single-msg">회원탈퇴를 하시겠습니까?</p>',
                button: "네",
                cancel: "아니오",
                callback: function(value){
                    if(value){
                        $('#frm_withdraw').submit();
                    }
                }
            });
        }else{
            dialog.alert({
                title:'알림',  
                message: '<p class="single-msg">사유를 체크해 주세요.</p>',
                button: "네"
            });
        }
    }else{
        dialog.alert({
            title:'알림',  
            message: '<p class="single-msg">안내사항 동의를 체크해주세요.</p>',
            button: "네"
        });
    }
}
// end

</script>
@endsection