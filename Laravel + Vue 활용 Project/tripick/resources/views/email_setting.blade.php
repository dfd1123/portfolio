@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--findpw">
    
    <div class="wrapper--inner">
        <div class="find_pw_panel">
        	이메일이 오지 않았다면 이메일 재전송을 통해 다시 이메일 인증을 해주세요.<br><br>이메일 인증을 하지 않는다면 트리픽 이용이 불가합니다.
            <button type="button" id="send_email" class="button">재전송</button>
        </div>
        <div class="find_pw_panel">
            완료되었다면 이버튼을 눌러주세요.
            <button type="button" class="button" onclick="location.reload();">완료</button>
        </div>
    </div>

    <div class="loading_wrap" style="display:none;">
        <div id="loading"></div>
    </div>

</div>

@endsection

@section('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
$('#send_email').click(function(){
    $('.loading_wrap').css('display','block');
    $.ajax({
        method: "POST",
        dataType: 'json',
        url: 'api/email_verified',
        success: function(data) {
            console.log(data);
            
            if(data.state == 1){
                console.log(data);
                dialog.alert({
                    title:'이메일 재전송',  
                    message: '이메일이 재전송 되었습니다. 메일을 확인해주세요.',
                    button: "확인"
                });
            }else{
                dialog.alert({
                    title:'이메일 재전송',  
                    message: data.msg,
                    button: "확인"
                });
            }
            $('.loading_wrap').css('display','none');
        },
        error: function(e) {
            console.log(e);
            $('.loading_wrap').css('display','none');
        }
    });
});
</script>
@endsection