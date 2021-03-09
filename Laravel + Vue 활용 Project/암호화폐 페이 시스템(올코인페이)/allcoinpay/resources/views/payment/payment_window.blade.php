<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	    <!-- Meta, title, CSS, favicons, etc. -->
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta property="og:title" content="AllCoin Pay">
	    <meta property="og:description" content="언제 어디서나 코인으로 결제를! AllCoin Pay">
	    <meta property="og:image" content="{{ asset('/image/logo/allcoin_pay_thumnail.png') }}">
	
		<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/image/favicon/apple-touch-icon.png') }}">
		<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/image/favicon/favicon-32x32.png') }}">
		<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/image/favicon/favicon-16x16.png') }}">
		<link rel="manifest" href="{{ asset('/image/favicon/site.webmanifest') }}">
		<link rel="mask-icon" href="{{ asset('/image/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
		<link rel="shortcut icon" href="{{ asset('/image/favicon/favicon.ico') }} ">
		<meta name="apple-mobile-web-app-title" content="AllCoin Payment">
		<meta name="application-name" content="Allcoin Payment">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="msapplication-config" content="{{ asset('/image/favicon/browserconfig.xml') }}">
		<meta name="theme-color" content="#ffffff">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
	    <!--<title> EasyCoinPay | </title>  -->
	    <title> 언제 어디서나 코인으로 결제를! Allcoin Pay</title>

	    <link href="{{ asset('/vendor/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
	    <!-- Font Awesome -->
	    <!--link href="{{ asset('/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
	    <link href='https://cdn.rawgit.com/theeluwin/NotoSansKR-Hestia/master/stylesheets/NotoSansKR-Hestia.css' rel='stylesheet' type='text/css'-->
		
	    <!-- NProgress -->
	    <link href="{{ asset('/vendor/nprogress/nprogress.css') }}" rel="stylesheet">
	    <!-- iCheck -->
	    <link href="{{ asset('/vendor/iCheck/skins/flat/green.css') }}" rel="stylesheet">
	
	    <!-- bootstrap-progressbar -->
	    <link href="{{ asset('/vendor/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">
	    <!-- JQVMap -->
	    <link href="{{ asset('/vendor/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet" />
	    <!-- bootstrap-daterangepicker -->
	    <link href="{{ asset('/vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
	    
	    <!-- Styles -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<link href="{{ asset('css/payment_window.css') }}" rel="stylesheet">
	</head>
	<body class="nav-md">
		<div class="container body">
			<div class="container ex_padding payment_wrapper">
				<!--배경 까맣게-->
				<div class="overlay_effect"></div>

				<div class="row pg_row" >

					<div class="qr_wrapper">

						<div class="panel panel-default panel_inner_1">

							<img src="{{ asset('/image/logo/payment_logo.png') }}" alt="logo" class="pg_logo"/>

							<p class="pg_status">
								결제하기
							</p>

							<div class="panel panel-default panel_inner_2" id = "coin_pay_request" >

								<div class="panel-body panel_inner_body">

									<h3 id = "coin_title">{{ $symbol }} 결제</h3>

									<span class="right_top_price" id="coin_quote">시세 : {{ number_format($price,0) }}원/1 {{$symbol}} 적용</span>

									<p class="inner_text">

										<b id="coin_price">{{ number_format($amount,0) }} </b>원을 결제하기 위해
										<br>
										아래 {{ $symbol }} 주소로
										<br>
										<b id = "coin_amt_text">{{ number_format($qty,8) }} 코인</b>을 결제합니다.

									</p>

									<center>

										<img id="coin_qrcode" src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl={{ $symbol }}:{{ $address }}?amount={{ $qty }}%26cointype={{ $symbol }}%26pay_order_id={{ $last_id }}%26choe=UTF-8">

										<div class="form_align">
											<!-- <input type="text" class="form-control text-center form_align_inner" value=""  onclick="this.select();" style="position: absolute; left: 0; top: 0; height: 100%; width: 100%; padding-right: 80px;"> -->
											<input type="text" class="form-control text-center" id="coin_address" value="{{ $address }}"  onclick="this.select();" readonly>
										</div>

										<div class="form_align">
											<input type="text" class="form-control text-center" id="coin_amt" value="{{ $qty }} {{ $symbol }}" readonly>
										</div>

										<div class="qrbtn_wrap">

											<div class="form_align" >
												<button type="button" class="btn cancel_btn" id="btn_cancel_btn" style="position:relative" onclick="cancel_payment({{ $last_id }});">
													취소
												</button>
												<button type="button" class="btn cancel_btn" id="btn_timeout_btn" style="position:relative">
													<span id="countTimeMinute">01</span>:<span id="countTimeSecond">00</span> 후에 자동취소됩니다.
												</button>
											</div>

										</div>

										<div class="m_qrbtn_wrap">

											<div class="form_align">
												<button type="button" class="btn cancel_btn" id="m_btn_cancel_btn" style="position:relative" onclick="cancel_payment({{ $last_id }});">
													취소
												</button>
												<button type="button" class="btn cancel_btn" id="m_btn_cancel_btn" style="position:relative">
													<span id="m_countTimeMinute">01</span>:<span id="m_countTimeSecond">00</span> 후에 자동취소됩니다.
												</button>
										

											</div>

										</div>

									</center>

									<p>
										올코인월렛을 이용한 지불만이 가능합니다.
									</p>

									<p>
										타 지갑어플을 사용하시는 경우,
										<br>
										지갑주소를 복사 후 정확한 입금 금액을 입력하신 후 입금해주세요.
									</p>

								</div>

							</div>
							<center class="pg_footer">
								powered by Allcoin Payment
							</center>

							<div class="lang_btn_wrap">

								<label for="lang_list"> <span class="flag_span"> <img src="{{ asset('/image/icon/kr.png') }}" alt="flag"/> </span>KR <i class="fas fa-angle-up"></i> </label>

							</div>

							<input type="checkbox" id="lang_list"/>

							<ul class="lang_list_wrap">

								<li class="lang_li">

									<a href="#"> <span class="flag_span"> <img src="{{ asset('/image/icon/eng.png') }}" alt="flag"/> </span>ENG </a>

								</li>

								<li class="lang_li">

									<a href="#"> <span class="flag_span"> <img src="{{ asset('/image/icon/jp.png') }}" alt="flag"/> </span>JP </a>

								</li>

							</ul>

						</div>

					</div>
				</div>
			</div>
		</div>
		
		<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
		<!-- jQuery -->
	    <script src="{{ asset('/vendor/jquery/dist/jquery.min.js') }}"></script>
	    <!-- Bootstrap -->
	    <script src="{{ asset('/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
	    <!-- FastClick -->
	    <script src="{{ asset('/vendor/fastclick/lib/fastclick.js') }}"></script>
	    <!-- NProgress -->
	    <script src="{{ asset('/vendor/nprogress/nprogress.js') }}"></script>
	    <!-- Chart.js -->
	    <script src="{{ asset('/vendor/Chart.js/dist/Chart.min.js') }}"></script>
	    <!-- gauge.js -->
	    <script src="{{ asset('/vendor/gauge.js/dist/gauge.min.js') }}"></script>
	    <!-- bootstrap-progressbar -->
	    <script src="{{ asset('/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
	    <!-- iCheck -->
	    <script src="{{ asset('/vendor/iCheck/icheck.min.js') }}"></script>
	    <!-- Skycons -->
	    <script src="{{ asset('/vendor/skycons/skycons.js') }}"></script>
	    <!-- Flot -->
	    <script src="{{ asset('/vendor/Flot/jquery.flot.js') }}"></script>
	    <script src="{{ asset('/vendor/Flot/jquery.flot.pie.js') }}"></script>
	    <script src="{{ asset('/vendor/Flot/jquery.flot.time.js') }}"></script>
	    <script src="{{ asset('/vendor/Flot/jquery.flot.stack.js') }}"></script>
	    <script src="{{ asset('/vendor/Flot/jquery.flot.resize.js') }}"></script>
	    <!-- Flot plugins -->
	    <script src="{{ asset('/vendor/flot.orderbars/js/jquery.flot.orderBars.js') }}"></script>
	    <script src="{{ asset('/vendor/flot-spline/js/jquery.flot.spline.min.js') }}"></script>
	    <script src="{{ asset('/vendor/flot.curvedlines/curvedLines.js') }}"></script>
	    <!-- DateJS -->
	    <script src="{{ asset('/vendor/DateJS/build/date.js') }}"></script>
	    <!-- JQVMap -->
	    <script src="{{ asset('/vendor/jqvmap/dist/jquery.vmap.js') }}"></script>
	    <script src="{{ asset('/vendor/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
	    <script src="{{ asset('/vendor/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>
	    <!-- bootstrap-daterangepicker -->
	    <script src="{{ asset('/vendor/moment/min/moment.min.js') }}"></script>
	    <script src="{{ asset('/vendor/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
		<script src="{{ asset('/js/app.js') }}"></script>
		<script>
			var first_minute = 1;
			var first_second = 0
			
			var minute = 1;
			var second = 0;
			
			var check_timer = null;
			var check_order = null;
			var check_timeout = null;
			
			$( document ).ready(function() {
				$(document).bind("contextmenu", function(e){
					return false;
				});

				$('img').bind("contextmenu",function(e){ 
					return false; 
				}); 

				$('img').bind("selectstart",function(e){ 
					return false;  
				}); 
				if(check_order === null) {
					check_order = setInterval(function(){ //결제 체크
						check_payment_status({{ $last_id }});
					}, 3000);
				}
				if(check_timer === null){
					check_timer = setInterval(function(){ //결제 체크
						time_decrese();
						
					}, 1000);
				}
			});

			document.oncontextmenu=function(){return false;} // 우클릭 방지
			document.onselectstart=function(){return false;} // 드래그 방지
			document.ondragstart=function(){return false;} // 선택 방지
			document.onmousedown=function(){return false;}

			function check_payment_status(id){
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					url : "/api/check_status",
					type : "POST",
					data : {_token:CSRF_TOKEN, id : id},
					dataType : "JSON"
				}).done(function(data) {
					if(data == 1){
						alert("결제가 완료되었습니다.");
						clearInterval(check_timer); // 타임아웃 타이머 stop
						check_timer = null;
						clearInterval(check_order); 
						check_order = null;
						self.close();
					}else if(data == 0){
						console.log("결제 대기중....");
					}
					
				}).error(function(){
					console.log("error");
				});
			}
			function time_decrese(){
				if(minute == 0 && second == 0){
					timeout_payment({{ $last_id }});
					clearInterval(check_timer); // 타임아웃 타이머 stop
					check_timeout = null;
				} else if(minute < 0) {
					timeout_payment({{ $last_id }});
					clearInterval(check_timer); // 타임아웃 타이머 stop
					check_timeout = null;
				}

				$("#countTimeMinute").html(minute);
				$("#countTimeSecond").html(second);
				$("#m_countTimeMinute").html(minute);
				$("#m_countTimeSecond").html(second);
				
				if(second == 0){
					minute--;
					second = 59;
				}else{
					second = second - 1;
				}
			}
			
			function timeout_payment(id){
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					url : "/api/timeout",
					type : "POST",
					data : {_token : CSRF_TOKEN, id : id},
					dataType : "JSON"
				}).done(function(data) {
					if(data > 0){
						alert("시간초과로 인해 취소되었습니다.");
						clearInterval(check_order); // 결제 확인 타이머 stop
						check_timer = null;
						self.close();
					}else{
						alert("취소에 실패하셨습니다. 다시 시도해 주세요.");
					}
					
				}).error(function(){
					console.log("error");
				});
			}
			
			function cancel_payment(id){
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					url : "/api/cancel",
					type : "POST",
					data : {_token : CSRF_TOKEN, id : id},
					dataType : "JSON"
				}).done(function(data) {
					if(data > 0){
						alert("취소되었습니다.");
						clearInterval(check_order); // 결제 확인 타이머 stop
						check_timer = null;
						self.close();
					}else{
						alert("취소에 실패하셨습니다. 다시 시도해 주세요.");
					}
					
				}).error(function(){
					console.log("error");
				});
			}
			@if(session()->has('error_alert'))
				alert("{{ session()->get('error_alert') }}");
				self.close();
			@endif
		</script>
	</body>
</html>	
