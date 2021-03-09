@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--findpw">

    <div class="hd-title hd-title--01">
        <button type="button" onclick="location.href='/login'" class="hd-title__left-btn hd-title__left-btn--close"><span
                class="none">닫기버튼</span></button>
        <h2 class="hd-title__center">비밀번호 재설정</h2>
    </div>
    
    <div class="wrapper--inner">
        <div class="find_pw_panel">
            <form id="frm_changepw" method="POST" action="/api/pw_change">
            <input type="hidden" name="email" value="{{$email}}">
            <label for="" class="_label">비밀번호</label>
            <input type="password" name="n_password" class="_pw_input"placeholder="비밀번호를 입력해주세요." style="margin-bottom: 1.5rem;">
            
            <label for="" class="_label">비밀번호 확인</label>
            <input type="password" name="c_password" class="_pw_input "placeholder="비밀번호를 입력해주세요.">
            <button type="submit" class="button">재설정</button>
            </form>
        </div>
    </div>

    

</div>

@endsection

@section('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
$('#frm_changepw').ajaxForm({
    dataType : "json",
    beforeSubmit: function() {
        return $('#frm_changepw').valid();
    },
    success: function(data) {
        console.log(data);
        if(data.state == 1){
            dialog.alert({
                title:'알림',  
                message: '비밀번호 변경이 완료되었습니다. 해당 비밀번호로 로그인해주세요.',
                button: "확인",
                callback: function(value){
                    location.href='/login';
                } 
            });
            
        }else{
            dialog.alert({
                title:'알림',  
                message: data.msg,
                button: "확인"
            });
        }
    }    
});
</script>
@endsection