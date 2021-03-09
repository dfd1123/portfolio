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
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-NJXGk7R+8gWGBdutmr+/d6XDokLwQhF1U3VA7FhvBDlOq7cNdI69z7NQdnXxcF7k" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="{{asset('css/pc/tellusart.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('css/vendor/vendor.css')}}">
	<script type="text/javascript" src="{{asset('js/vendor/vendor.js')}}"></script>
	
	
	<!--[if lt IE 9]>
	<script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script src="https://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<![endif]-->
</head>
<body>
	<div id="wrap">
		@if(count($popups) > 0)
			@include(session('theme').'.'.$device.'.'.'layouts.popup')
		@endif
		
		@include($device.'.layouts.header')
	
		@yield('content')
	
		@include($device.'.layouts.footer')
		
		@yield('main_modal')
  
		@yield('script')
		
</div>
<script type="text/javascript" src="{{asset('vendor/flick/jquery.event.drag-1.5.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/flick/jquery.touchSlider.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/clipboard/clipboard.min.js')}}"></script>

<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="{{asset('vendor/slick/slick.min.js')}}"></script>
<script type="text/javascript" charset="utf-8" src="{{asset('vendor/modal/jquery.leanModal.min.js')}}"></script>
@yield('main_script')
<script type="text/javascript" src="{{asset('js/pc/platform.js')}}"></script>
<script>
	@if(session()->has('jsAlert'))
	alert('{{ session()->get('jsAlert') }}');
	@endif
</script>
</body>
</html>