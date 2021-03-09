@extends('layouts.app')

@section('content')
@endsection

@section('script')
<script>
    $(function(){
        dialog.alert({
            title:'알림',
            @if($message != null)
            message: '<p class="single-msg">{{ $message }}</p>',
            @elseif($pg_type == 'creditcard' || $pg_type == 'payco' || $pg_type == 'kakaopay')
            message: '<p class="single-msg">결제가 완료되었습니다. 마이페이지의 플래너 예약 내역 에서 확인해주세요.</p>',
            @else
            message: '<p class="single-msg">결제가 진행중입니다. 인터넷뱅킹이나 가상계좌의 입금을 마무리해주세요.</p>',
            @endif
            button: "확인",
            callback: function(value){
                var mobile_kind = getMobileOperatingSystem();
                if(mobile_kind == "Android"){
                    if(typeof window.JS !== 'undefined'){
                        window.JS.RedirectPage();
                    }else{
                        window.opener.document.location.href = "/mypage/mypage";
                        window.close();
                    }
                    
                }else if(mobile_kind == "iOS"){
                    if(typeof webkit !== 'undefined'){
                        location.href = '/mypage/mypage';
                    }else{
                        window.opener.document.location.href = "/mypage/mypage";
                        window.close();
                    }
                }else{
                    window.opener.document.location.href = "/mypage/mypage";
                    window.close();
                }
            }
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