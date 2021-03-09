@extends('common.'.config('device.device').'.layouts.app')

@section('content')
<h1>{{ __('mobile.join')}}</h1>

<form method="get" action="{{route('register')}}" id="register_agree">
	<textarea>개인정보이용약관.....</textarea>
	<br>
	<input type="checkbox" name="register_agree1" value="0" />{{ __('mobile.agree')}}
	<br><br>
	
	<textarea>개인정보취급방침.....</textarea>
	<br>
	<input type="checkbox" name="register_agree2" value="0" />{{ __('mobile.agree')}}
	<br><br>
	
	<textarea>마케팅수신.....</textarea>
	<br>
	<input type="checkbox" name="register_agree3" value="0" />{{ __('mobile.agree')}}
	<br><br>	
	
	<button type="submit">{{ __('mobile.next')}}</button>
	
</form>


<script>
    	@if(session()->has('jsAlert'))
	        
	        $.alert({
			    title: "{{ __('mobile.alr')}}",
			    content: "{{ session()->get('jsAlert') }}",
			});
	
		@endif
		

    	
    </script>
@endsection
