<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta property="og:title" content="Cointouse">
		<meta property="og:description" content="trademarket-Cointouse">
		<meta property="og:image" content="{{ asset('/storage/image/homepage/favicon/cointouse_thumnail.svg') }}">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<!-- Locale -->
		<meta name="locale" content="{{ config('app.country') }}">

		<title>{{ config('app.name', 'Laravel') }}</title>


	</head>
	<body>
		
		<div id="wrapper">
			<div id="main_container">		
				@yield('content')
			</div>
		</div>


	</body>

</html>
