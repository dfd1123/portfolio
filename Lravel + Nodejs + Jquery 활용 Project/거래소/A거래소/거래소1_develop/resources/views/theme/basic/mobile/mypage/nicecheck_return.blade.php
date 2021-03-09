@extends(session('theme').'.mobile.layouts.app')
@section('content')

<script>
	
	$(function(){
		var mobile_kind = getMobileOperatingSystem();
		if(mobile_kind == "Android"){
			if(typeof window.myJS !== 'undefined'){
				window.myJS.CallNiceAlert('{{ $status }}','{{ $message }}');
			}else{
				opener.parent.nicecheck_alert('{{ $status }}','{{ $message }}');
				self.close();
			}
			
		}else if(mobile_kind == "iOS"){

			if(typeof webkit !== 'undefined'){
				var message = {
					'status': '{{ $status }}',
                	'message': '{{ $message }}'
				}
				webkit.messageHandlers.CallNiceIOS.postMessage(message); 
			}else{
				opener.parent.nicecheck_alert('{{ $status }}','{{ $message }}');
				self.close();
			}
			/*
			
			
			*/
		}else{
			opener.parent.nicecheck_alert('{{ $status }}','{{ $message }}');
			self.close();
		}
	});
	
   
	/*function getMobileOperatingSystem() {
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
	}*/
   

</script>
    

@endsection