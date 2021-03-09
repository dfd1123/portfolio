<!-- HEADER 시작-->
<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko-KR" lang="ko-KR">
	<!--<![endif]-->

	<head>
		<!-- Basic Page Needs -->
		<meta charset="UTF-8">
		<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
		<title>J1beatz Admin</title>

		<meta name="author" content="master.j1beatz.com">

		<!-- Mobile Specific Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

		<!-- Boostrap style -->
		<!-- <link rel="stylesheet" type="text/css" href="stylesheets/bootstrap.min.css"> -->

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" type="text/css" href=" {{asset('vendor/css/bootstrap4-alpha3.min.css')}} ">

		<!-- FONTS-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

		<!-- Theme style -->
		<link rel="stylesheet" type="text/css" href=" {{asset('vendor/css/style.css')}} ">

		<!-- Calendar -->
		<link href=" {{asset('vendor/css/fullcalendar.min.css')}} "         rel='stylesheet' />
		<link href=" {{asset('vendor/css/fullcalendar.print.min.css')}} "   rel='stylesheet' media='print' />

		<!-- Responsive -->
		<link rel="stylesheet" type="text/css" href=" {{asset('vendor/css/responsive.css')}}">

		<!-- Favicon -->
		<link href="{{asset('vendor/images/favicon.png')}}" rel="shortcut icon">
		<!-- jQuery 3 -->
		<script src="{{ asset('vendor/js/jquery.min.js') }} "></script>
		<script src="https://malsup.github.io/jquery.form.js" type="text/javascript"></script>

	</head>

	<body>

		<!-- Loader -->
		<div class="loader">
			<div class="inner one"></div>
			<div class="inner two"></div>
			<div class="inner three"></div>
		</div>

		<header id="header" class="header fixed">
			<div class="navbar-top">
				<div class="curren-menu info-left">
					<div class="logo">
						<a href="/dashboard" title=""> <img src="{{asset('vendor/images/logo.png')}}" alt="One Admin"> </a>
					</div><!-- /.logo -->
					<div class="top-button">
						<span></span>
					</div><!-- /.top-button -->
				</div><!-- /.curren-menu -->

				<div class="clearfix"></div>
			</div>
			<!-- /.navbar-top -->
		</header><!-- /header <-->

		<script>
			//좌측 메뉴 활성화 js
			function menuactive(name) {
				var menu = $('.sidebar-nav li');
				for (var i = 0; i < menu.length; i++) {
					$(menu[i]).removeClass('active');
				}
				$('.' + name).addClass('active');
			}

			$(function() {
				var button = $(".top-button");
				button.on('click', function() {
					$(this).closest('body').children(".vertical-navigation").toggleClass('active').delay(800);
					$(this).closest('body').children('main').toggleClass('active');
					$(this).parent('.curren-menu').children('.logo').toggleClass('active');
					button.toggleClass('active');
					$(this).closest('body').children(".vertical-navigation").toggleClass('show');
				});
				var buttonNav = $('.vertical-navigation.left ul.sidebar-nav > li');
				buttonNav.on('click', function(event) {
					$(this).closest('body').children(".vertical-navigation").removeClass('active');
					$(this).closest('body').children('main').removeClass('active');
					$(this).closest('body').find('.curren-menu').children('.logo').removeClass('active');
					event.preventDefault();
				});
				$('#template_close_btn').click(function() {
					$('.bg-disabled').removeClass('active');
				});
				$('#template_open_btn').click(function() {
					$('.bg-disabled').addClass('active');
				});
			});
			// Navigation Active
		</script>
		<section class="vertical-navigation left">

			<ul class="sidebar-nav">
				<!-- 차후 클릭된 메뉴로 active 추가해줘야함 -->
				<li class="dashboard waves-effect waves-teal active" onclick="location.href='/beat';">
					<div class="img-nav">
						<img src="{{asset('vendor/images/icon/monitor.png')}}" alt="">
					</div>
					<span>Beat</span> </a>
				</li>
				<li class="message waves-effect waves-teal" onclick="location.href='/maker';">
					<div class="img-nav">
						<img src="{{asset('vendor/images/icon/apps.png')}}" alt="">
					</div>
					<span>Makers</span> </a>
				</li>
				<li class="calendar waves-effect waves-teal" onclick="location.href='/users';">
					<div class="img-nav">
						<img src="{{asset('vendor/images/icon/apps.png')}}" alt="">
					</div>
					<span>Users</span> </a>
				</li>
				<li class="pages waves-effect waves-teal" onclick="location.href='/genre';">
					<div class="img-nav">
						<img src="{{asset('vendor/images/icon/message.png')}}" alt="">
					</div>
					<span>Genre</span> </a>
				</li>
				<li class="apps waves-effect waves-teal" onclick="location.href='/theme';">
					<div class="img-nav">
						<img src="{{asset('vendor/images/icon/calendar.png')}}" alt="">
					</div>
					<span>Theme</span> </a>
				</li>
				<li class="setting waves-effect waves-teal" onclick="location.href='/settings';">
					<div class="img-nav">
						<img src="{{asset('vendor/images/icon/pages.png')}}" alt="">
					</div>
					<span>Settings</span> </a>
				</li>
			</ul>
		</section><!-- /.vertical-navigation -->

		<main>
			<section id="dashboard">
				@yield('content')
			</section><!-- /#dashboard -->

		</main><!-- /main -->

		<!-- FOOTER 시작-->

		<!-- Bootstrap 4 -->
		<script src="{{ asset('vendor/js/tether.min.js')}}"></script>
		<script src="{{ asset('vendor/js/bootstrap4-alpha3.min.js')}} "></script>

		<!-- Map chart
		각 페이지에 필요한 js 끌어다 쓰면 됨--
		<script src="{{ asset('vendor/js/ammap.js')}}"></script>
		<script src="{{ asset('vendor/js/worldLow.js')}}"></script>

		<!-- Morris.js charts --
		<script src="{{ asset('vendor/js/raphael.min.js')}}"></script>
		<script src="{{ asset('vendor/js/morris.min.js')}}"></script>

		<!-- Chart --
		<script src="{{ asset('vendor/js/Chart.min.js')}}"></script>

		<!-- Calendar --
		<script src="{{ asset('vendor/js/moment.min.js')}}"></script>
		<script src="{{ asset('vendor/js/jquery-ui.js')}} "></script>
		<script src="{{ asset('vendor/js/fullcalendar.min.js')}}"></script>

		<script type="text/javascript" src="{{ asset('vendor/js/jquery.mCustomScrollbar.js')}}"></script>
		<script src="{{ asset('vendor/js/smoothscroll.js')}}"></script>
		<script src="{{ asset('vendor/js/waypoints.min.js')}}"></script>
		<script src="{{ asset('vendor/js/jquery-countTo.js')}}"></script>
		<script src="{{ asset('vendor/js/waves.min.js')}}"></script>
		<script src="{{ asset('vendor/js/canvasjs.min.js')}}"></script>
		-->
		<div class="bg-disabled"></div>
	</body>
</html>
<!--FOOTER 끝-->