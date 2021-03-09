<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">
	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<title>{{ config('app.name', 'Laravel') }}</title>
	<link rel="stylesheet" type="text/css" href="{{asset('/css/vendor/vendor_mobile.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('/css/mobile/tellusart.css')}}">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-NJXGk7R+8gWGBdutmr+/d6XDokLwQhF1U3VA7FhvBDlOq7cNdI69z7NQdnXxcF7k" crossorigin="anonymous">
	<script type="text/javascript" src="{{asset('/js/vendor/vendor_mobile.js')}}"></script>
	<script type="text/javascript" src="{{asset('/vendor/moment/moment.min.js')}}"></script>
	
	
	<!--[if lt IE 9]>
	<script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script src="https://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<![endif]-->
</head>
<body>
	<div id="wrap" class="clearFix">
		@if(count($popups) > 0)
			@include(session('theme').'.'.$device.'.'.'layouts.popup')
		@endif
		
		@if($main)
			@include($device.'.layouts.main_header')
		@else
			@include($device.'.layouts.sub_header')
		@endif

		@include($device.'.layouts.side_bar')
	
		@yield('content')

		@include($device.'.layouts.bottom')
		
	</div>
	@include($device.'.modal.review_edit')
  
	@yield('script')

	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
		@csrf
	</form>
	@if(strpos($pagename[1], 'mobile_mypage') === false && $pagename[1] != 'sel_product' && $pagename[1] != 'bat_product')
	<style>
		#wrap {
			padding: 40px 0 60px 0;
		}
	</style>
	@endif
	<script type="text/javascript" src="{{asset('/js/mobile/platform.js')}}"></script>
	<script>
		$(".commm_modify").poppy("comm");
		$(".pixbet").poppy("pixb_comm");
		@if(session()->has('jsAlert'))

	        alert('{{ session()->get('jsAlert') }}');
	
		@endif
	</script>

</body>
</html>