<!doctype html>

<html>

<head>
	<title>Allcoin Pay | 언제 어디서나 코인으로 결제를!</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1.0">
    <meta property="og:title" content="Allcoin Pay">
    <meta property="og:description" content="언제 어디서나 코인으로 결제를! Allcoin Pay">
    <meta property="og:image" content="{{ asset('/image/logo/allcoin_pay_thumnail.png') }}">

	<link href="{{ asset('css/landing.css') }}" rel="stylesheet">

	<!--글꼴-->
	<link href='https://cdn.rawgit.com/theeluwin/NotoSansKR-Hestia/master/stylesheets/NotoSansKR-Hestia.css' rel='stylesheet' type='text/css'>
	
	<!--font awesome-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	
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
	

</head>

<body>

	<!--pc/tap버전 왼쪽 사이드네비게이션-->
	<div class="side_nav">

		<div class="nav_wrap">

			<div class="top_nav">

				<h1 class="logo">
					<a href="{{ route('home') }}"><img src="{{ asset('/image/logo/main_logo.svg') }}" alt="main_logo"/></a>
				</h1>

				<ul>
					@if(Auth::check())
					<li><a href="{{ route('company') }}">관리자페이지</a><span class="span">Welcome</span>
                    <h2 class="h2">{{ Auth::user()->fullname }}</h2>
                    </li>
                    <!--li class="">
                    	<a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">로그아웃</a>
                    	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							@csrf
						</form>
                    </li-->
          			@else
					<li><a href="{{ route('login') }}">로그인</a></li>
					<li><a href="{{ route('register') }}">회원가입</a></li>
					@endif
				</ul>

			</div>

			<div class="middle_nav">

				<nav class="main_nav">

					<ul class="main_nav_ul">

						<li><a href="#section_1">
								
								<span class="icon_wrap">
									<img src="{{ asset('/image/icon/icon-01.svg') }}" alt="list_1" class="icon_b">
									<img src="{{ asset('/image/icon/icon-01-w.svg') }}" alt="list_1" class="icon_w">
								</span>
								올코인페이 소개
								
								</a></li>
						<li><a href="#section_2">
								
								<span>
									<img src="{{ asset('/image/icon/icon-02.svg') }}" alt="list_2" class="icon_b">
									<img src="{{ asset('/image/icon/icon-02-w.svg') }}" alt="list_2" class="icon_w">
								</span>
								이용안내
								
								</a></li>
						<li><a href="#section_3">
								
								<span>
									<img src="{{ asset('/image/icon/icon-03.svg') }}" alt="list_3" class="icon_b">
									<img src="{{ asset('/image/icon/icon-03-w.svg') }}" alt="list_3" class="icon_w">
								</span>
								문의하기
								
								</a></li>

					</ul>

				</nav>

			</div>

			
			<div class="bottom_nav">
				<label for="lang_choice">
					<p class="lang_btn">
						<span>
							<img src="{{ asset('/image/icon/kr.png') }}" alt="kr_flag"/>Korean
						</span>
					</p>
				</label>
				
				<input id="lang_choice" type="checkbox"/>
				<ul class="lang_list">
					<li><a href="#"><img src="{{ asset('/image/icon/eng.png') }}" alt="eng_flag"/>English</a></li>
					<li><a href="#"><img src="{{ asset('/image/icon/jp.png') }}" alt="jp_flag"/>Japan</a></li>
				</ul>
			</div>


		</div>

	</div>

	<!--pc,tap버전-->
	<div id="pc_container" class="section">

		<div class="section section_1">

			<div class="slide_wrapper next_advan">

				<div class="slide_con slide_con_1">

					<div class="inner_gradient"></div>

					<div class="main_title title_div">

						<span>언제 어디서나 <u class="purple">코인</u>으로 결제를 !</span>

						<span><img src="{{ asset('/image/logo/main_header_logo.svg') }}" alt="main_white_logo"/></span>

					</div>

					<div class="sub_title title_div">

						<p><span>올코인페이 서비스란?</span><b class="next_btn">▶</b></p>

					</div>

				</div>

				<div class="slide_con slide_con_2">

					<div class="inner_gradient"></div>

					<ul class="advantage_wrap">

						<li>
							<p><img src="{{ asset('/image/section_1/advan-01.svg') }}" alt="advan" /></p>
							<p><u class="purple">실질</u>적인 거래가능</p>
							<p>올코인 페이는 암호화폐로<br> 온·오프라인 상의 실질적인 거래가<br> 가능하도록 하는 PG서비스입니다.
							</p>
						</li>
						<li>
							<p><img src="{{ asset('/image/section_1/advan-02.svg') }}" alt="advan" /></p>
							<p>환전수수료 <u class="purple">부담최소</u></p>
							<p>환율의 부담이 적어<br> 해외결제시,
								<br> 수수료 부담을 덜 수 있습니다.
							</p>
						</li>
						<li>
							<p><img src="{{ asset('/image/section_1/advan-03.svg') }}" alt="advan" /></p>
							<p><u class="purple">빠른</u> 코인결제처리</p>
							<p>아이시디스에서 개발한<br> 올코인월렛어플과 연동하여<br> 더욱 빠른 코인결제처리가 가능합니다.
							</p>
						</li>

					</ul>

					<div class="back_btn_wrap">

						<p><span>←</span><span>back</span></p>

					</div>

				</div>

			</div>

		</div>

		<div class="section section_2">

			<ul class="online_or_offline pc_ver">

				<li class="active">
					<p><u>온라인</u> 결제시스템</p>
					<p>On-line Payment Gateway</p>
				</li>

				<li>
					<p><u>오프라인</u> 결제시스템</p>
					<p>Off-line Value Added Netword</p>
				</li>

			</ul>

			<div class="slide_wrapper online_wrap">

				<div class="slide_con slide_con_1">

					<ul class="online_or_offline tap_ver">

						<li>
							<p><u>온라인</u> 결제시스템</p>
							<p>On-line Payment Gateway</p>
						</li>

						<li>
							<p class="offline_btn">오프라인 결제시스템 보기 ></p>
						</li>

					</ul>

					<div class="all_wrap">

						<div class="moniter_wrap">

							<div class="moniter_area">

								<img src="{{ asset('/image/section_2/moniter.png') }}" alt="moniter" class="moniter" />

								<div class="moniter_view">

									<img src="{{ asset('/image/section_2/view_1.svg') }}" alt="view_1" />

									<img src="{{ asset('/image/section_2/alert_allcoinpay.png') }}" alt="allcoin_pay" class="allcoin_box">

									<img src="{{ asset('/image/section_2/all_com_popup.png') }}" alt="popup" class="allcoin_popup hide">

									<div class="view_darker hide"></div>

									<img src="{{ asset('/image/section_2/all_online_hand.png') }}" alt="online_hand" class="online_hand hide" />

									<img src="{{ asset('/image/section_2/popup_end.svg') }}" alt="popup_end" class="popup_end hide" />

								</div>

							</div>

							<div class="phone_area">

								<img src="{{ asset('/image/section_2/phone.png') }}" alt="phone" class="phone" />

								<div class="phone_view">

									<img src="{{ asset('/image/section_2/phone_view_1.svg') }}" alt="phone_view_1" class="phone_view_1" />

									<img src="{{ asset('/image/section_2/phone_view_1_alert.svg') }}" alt="phone_view_1" class="phone_view_1_alert" />

									<div class="view_darker hide"></div>

									<img src="{{ asset('/image/section_2/phone_popup_end.svg') }}" alt="phone_popup_end" class="phone_popup_end hide" />

								</div>

							</div>


						</div>

						<div class="middle_line pc_ver">

							<div class="con_box order_1">

								<p>
									<span class="list_circle">1</span>
									<span>결제수단 선택시,<br>　 　'올코인페이 결제'선택</span>
								</p>

								<p>
									<span>온라인 쇼핑몰에서 결제할 때<br>결제수단에서 올코인페이를 선택합니다.</span>
								</p>

								<span class="next_btn_wrap">
									<u class="purple">NEXT</u>
									<b class="next_btn">▶</b>
								</span>

							</div>

							<div class="con_box order_2 hide">

								<p>
									<span class="list_circle">2</span>
									<span>결제 QR코드 생성</span>
								</p>

								<p>
									<span>결제금액을 실시간 시세로 변환한 코인과<br>
									전송해야할 지갑주소가 합쳐진<br>
									QR코드가 생성됩니다.
									</span>
								</p>

								<span class="next_btn_wrap">
								<u class="purple">NEXT</u>
								<b class="next_btn">▶</b>
							</span>
							</div>

							<div class="con_box order_3 hide">

								<p>
									<span class="list_circle">3</span>
									<span>스마트폰의 월렛어플을 통해<br>　　 QR코드 스캔하기</span>
								</p>

								<p>
									<span>
									Allcoin Wallet 어플의<br>QR코드 스캔기능을 통해 코인을 결제합니다.
								</span>
								</p>

								<span class="next_btn_wrap">
									<u class="purple">NEXT</u>
									<b class="next_btn">▶</b>
								</span>

								<div class="noted_items">
									<div class="items">
										<u class="purple">• </u>
										<span>
											&nbsp;올코인페이 서비스는 <u class="red_color">올코인월렛 어플</u>과 최적화 연동되어있으며, <br>　타 월렛 어플보다 더욱 빠른 결제가 가능합니다. <br>　(다른 범용 월렛을 통한 코인결제도 가능합니다. <br><u class="red_color">　단, 결제처리시간이 오래 걸릴 수 있는 점 유의바랍니다.)</u>
										</span>
									</div>


									<div class="items">
										<u class="purple">• </u>
										<span>
											&nbsp;모바일에서 실행하는 온라인 결제시스템은<br>　QR코드 주소 복사/올코인 월렛 바로가기를 통해 결제가 가능합니다.
										</span>
									</div>

								</div>

							</div>

							<div class="con_box order_4 hide">

								<p>
									<span class="list_circle">4</span>
									<span>QR코드 스캔 완료시,<br>　　 결제 완료</span>
								</p>

								<p>
									<span>
									QR코드 스캔을 다 한 후,<br>입금완료 버튼을 누르면 결제가 완료됩니다.
								</span>
								</p>

								<span class="next_btn_wrap">
								<u class="purple">NEXT</u>
								<b class="next_btn">▶</b>
							</span>
							</div>


						</div>

						<div class="middle_line tap_ver online">

							<div class="con_box order_1">

								<p>
									<span class="list_circle">1</span>
									<span>결제수단 선택시,<br>　　&nbsp;&nbsp;&nbsp;'올코인페이 결제'선택</span>
								</p>

								<p>
									<span>온라인 쇼핑몰에서 결제할 때<br>결제수단에서 올코인페이를 선택합니다.</span>
								</p>

								<span class="next_btn_wrap">
									<u class="purple">NEXT</u>
									<b class="next_btn">▶</b>
								</span>

							</div>

							<div class="con_box order_2 hide">

								<p>
									<span class="list_circle">2</span>
									<span>결제 QR코드 생성</span>
								</p>

								<p>
									<span>결제금액을 실시간 시세로 변환한 코인과<br>
									전송해야할 지갑주소가 합쳐진<br>
									QR코드가 생성됩니다.
									</span>



								</p>

								<span class="next_btn_wrap">
								<u class="purple">NEXT</u>
								<b class="next_btn">▶</b>
							</span>
							</div>

							<div class="con_box order_3 hide">

								<p>
									<span class="list_circle">3</span>
									<span>스마트폰의 월렛어플을 통해<br>　　 QR코드 스캔하기</span>
								</p>

								<p>
									<span>
									All Wallet 어플의<br>QR코드 스캔기능을 통해 코인을 결제합니다.
								</span>
								</p>


								<span class="next_btn_wrap">
									<u class="purple">NEXT</u>
									<b class="next_btn">▶</b>
								</span>

								<label class="tap_show_items" for="items_check">
									<u class="red_color">!</u> &nbsp;유의사항 보기
								</label>

								<input type="checkbox" id="items_check" />
								<div class="noted_items">
									<div class="items">
										<u class="purple">• </u>
										<span>
											&nbsp;올코인페이 서비스는 <u class="red_color">올코인월렛 어플</u>과 최적화 연동되어있으며, <br>　타 월렛 어플보다 더욱 빠른 결제가 가능합니다. <br>　(다른 범용 월렛을 통한 코인결제도 가능합니다. <br><u class="red_color">　단, 결제처리시간이 오래 걸릴 수 있는 점 유의바랍니다.)</u>
										</span>
									</div>


									<div class="items">
										<u class="purple">• </u>
										<span>
											&nbsp;모바일에서 실행하는 온라인 결제시스템은<br>　QR코드 주소 복사/올코인 월렛 바로가기를 통해 결제가 가능합니다.
										</span>
									</div>

								</div>

							</div>

							<div class="con_box order_4 hide">

								<p>
									<span class="list_circle">4</span>
									<span>QR코드 스캔 완료시,<br>　　 결제 완료</span>
								</p>

								<p>
									<span>
									QR코드 스캔을 다 한 후,<br>입금완료 버튼을 누르면 결제가 완료됩니다.
								</span>
								</p>

								<span class="next_btn_wrap">
								<u class="purple">NEXT</u>
								<b class="next_btn">▶</b>
							</span>
							</div>

						</div>



					</div>


				</div>

				<div class="slide_con slide_con_2">

					<ul class="online_or_offline tap_ver">

						<li>
							<p><u>오프라인</u> 결제시스템</p>
							<p>Off-line Value Added Network</p>
						</li>

						<li>
							<p class="online_btn">온라인 결제시스템 보기 ></p>
						</li>

					</ul>

					<div class="allcoin_info">

						<img src="{{ asset('/image/logo/allcoin_symbol.png') }}" alt="symbol" />

						<ul>

							<li><u class="purple">• </u> &nbsp;오프라인 매장에서 결제할시, 올코인페이 서비스를 통해 결제 금액이 포함된 QR코드 생성</li>

							<li><u class="purple">• </u> &nbsp;올코인페이로 코인의 실질적인 거래가 가능하게 함으로써 가맹점주들은 별도의 PG 기술 개발 불필요</li>

							<li><u class="purple">• </u> &nbsp;암호화폐 결제고객을 유도함으로써 매출 증가를 기대할 수 있음</li>

						</ul>

					</div>

					<div class="middle_line">

						<ul class="offline_phone_wrap">

							<li>

								<div class="offline_phone_box">

									<img src="{{ asset('/image/section_2/all_offline_phone_1.png') }}" alt="offline_phone" />

								</div>

								<div class="phone_info">

									<p>
										<span class="list_circle">1</span>
										<span>Allcoin Pay 관리자 로그인</span>
									</p>
									<p>
										<span>Allcoin Pay 관리자 화면으로<br>
											접속하여 로그인</span>
									</p>

								</div>

							</li>

							<li>

								<div class="offline_phone_box">

									<img src="{{ asset('/image/section_2/all_offline_phone_2.png') }}" alt="offline_phone" />

								</div>

								<div class="phone_info">

									<p>
										<span class="list_circle">2</span>
										<span>결제금액 입력</span>
									</p>
									<p>
										<span>결제금액 입력 후<br>QR코드 생성</span>
									</p>

								</div>

							</li>


							<li>

								<div class="offline_phone_box">

									<img src="{{ asset('/image/section_2/all_offline_phone_3.png') }}" alt="offline_phone" />

								</div>

								<div class="phone_info">

									<p>
										<span class="list_circle">3</span>
										<span>QR코드 생성</span>
									</p>
									<p>
										<span>결제금액 QR코드를<br>고객에게 보여주기</span>
									</p>

								</div>

							</li>


						</ul>

					</div>

					<div class="phone_in_hand_wrap">

						<img src="{{ asset('/image/section_2/all_offline_phone_4.png') }}" alt="offline_phone" />

						<div class="info_refer">

							<p>
								<span class="list_circle">4</span>
								<span>QR코드 결제</span>
							</p>

							<p>
								<span>
									생성한 QR코드를<br>
									고객에게 보여주고<br>
									결제하면 끝
								</span>
							</p>

						</div>

					</div>

					<div class="all_wrap">

						<div class="moniter_wrap offline_mnt_wrap">

							<div class="tap_offline_box_1">

								<img src="{{ asset('/image/section_2/all_offline_phone_1.png') }}" alt="offline_phone" />

								<span> 》》》》》</span>

								<img src="{{ asset('/image/section_2/all_offline_phone_2.png') }}" alt="offline_phone" />

							</div>

							<div class="tap_offline_box_2 hide">
								<img src="{{ asset('/image/section_2/on_04_phone.png') }}" alt="offline_phone_2" class="phone_mini" />
								<img src="{{ asset('/image/section_2/all_offline_phone_3.png') }}" alt="offline_phone_2" class="phone_zoom" />
							</div>

						</div>

						<div class="middle_line tap_ver offline">

							<div class="con_box order_1">

								<p>
									<span class="list_circle">1</span>
									<span>Allcoin Pay 관리자 로그인 후,<br>　　&nbsp;&nbsp;&nbsp;결제금액 입력</span>
								</p>

								<p>
									<span>Allcoin pay 관리자화면으로<br>접속하여 로그인한 후, 결제금액 입력</span>
								</p>

								<span class="next_btn_wrap">
									<u class="purple">NEXT</u>
									<b class="next_btn">▶</b>
								</span>

							</div>

							<div class="con_box order_2 hide">

								<p>
									<span class="list_circle">2</span>
									<span>QR코드 생성 후 결제</span>
								</p>

								<p>
									<span>결제금액 QR코드를<br>
									고객에게 보여드린 후 결제
									</span>

								</p>

								<span class="next_btn_wrap">
								<u class="purple">REPLAY</u>
								<b class="next_btn">▶</b>
									
							</span>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

		<div class="section section_3">

			<div class="left_area">

				<ul class="cs_box">

					<h3><u class="purple">서비스</u> 문의하기</h3>

					<li>
						<span class="icon"><img src="{{ asset('/image/section_3/cs_icon-01.svg') }}" alt="time"/></span>
						<h4>상담가능시간</h4>
						<p>평일 10:00~18:00 (점심시간 12:00~13:00)</p>
						<p>주말·공휴일 휴무</p>
					</li>

					<li>
						<span class="icon"><img src="{{ asset('/image/section_3/cs_icon-01.svg') }}" alt="phone"/></span>
						<h4>연락처</h4>
						<p>고객센터 1600-4719</p>
					</li>

					<li>
						<span class="icon"><img src="{{ asset('/image/section_3/cs_icon-01.svg') }}" alt="mail"/></span>
						<h4>이메일주소</h4>
						<p>joong3333@aicdss.com</p>
					</li>

				</ul>

			</div>

			<div class="right_picture">

				<div class="inner_gradient"></div>

			</div>

			<footer>

				<img src="{{ asset('/image/logo/img_AICDSS_logo.png') }}" alt="aicdss_logo" class="pc_ver" />


				<ul>

					<li>
						<img src="{{ asset('/image/logo/img_AICDSS_logo.png') }}" alt="aicdss_logo" class="tap_ver" />
					</li>

					<li>(주)아이시디스 주식회사　　대표자 : 이윤자　　문의메일 : joong3333@aicdss.com
					</li>

					<li>사업자등록번호 : 409-86-54202　　통신판매업 번호 : 제 2018-부산중구-0200호</li>

				</ul>

				<ul>

					<li>COPYRIGHT @2018 Allcoin PAY BY AICDSS</li>

				</ul>

			</footer>

		</div>


	</div>

	<!--mobile버전-->
	<div id="m_container" class="section">

		<div class="m_nav">

			<h1>
				<a href="/">
					<img src="{{ asset('/image/logo/main_logo.svg') }}" alt="m_logo" />
				</a>
			</h1>

			<p>
				<span></span>
			</p>


		</div>

		<div class="trigger_box">
			<ul class="first_nav">
				<li><a href="#">올코인페이 소개</a></li>
				<li><a href="#">이용안내</a></li>
				<li><a href="#">문의하기</a></li>
			</ul>

			<ul class="second_nav">
				<li><a href="/login" target="_blank">로그인</a></li>
				<li><a href="/register" target="_blank">회원가입</a></li>
				<li><a href="#">
					<label for="lang_btn">
					<img src="{{ asset('/image/icon/kr.png') }}" alt="kr_flag"/>
					　&nbsp;&nbsp;korean 
					<i class="fas fa-angle-down"></i>
					</label>
					</a>
					
					<input type="checkbox" id="lang_btn"/>
					<ul class="inner_flag">
						<li><a href="/index.php?lang=english">
							<img src="{{ asset('/image/icon/eng.png') }}" alt="eng"/>
							　　english
							</a>
						</li>
						<li><a href="/index.php?lang=korean">
							<img src="{{ asset('/image/icon/jp.png') }}" alt="jp"/>
							　　japan
							</a></li>
					</ul>
				</li>
			</ul>
		</div>

		<div class="m_section_1">

			<div class="slide_con slide_con_1">

				<div class="inner_gradient"></div>

				<div class="title_div">

					<div class="main_title">

						<span>언제 어디서나 <u class="purple">코인</u>으로 결제를!</span>

						<span><img src="{{ asset('/image/logo/main_header_logo.svg') }}" alt="main_white_logo"/></span>

					</div>

				</div>

			</div>

		</div>

		<div class="m_section_2">

			<ul class="advantage_wrap">

				<li>
					<p><img src="{{ asset('/image/section_1/m-advan-01.svg') }}" alt="advan" /></p>
					<p><u class="purple">실질</u>적인 거래가능</p>
					<p>Allcoin 페이는 암호화폐로<br> 온·오프라인 상의 실질적인 거래가<br> 가능하도록 하는 PG서비스입니다.
					</p>
				</li>
				<li>
					<p><img src="{{ asset('/image/section_1/m-advan-02.svg') }}" alt="advan" /></p>
					<p>환전수수료 <u class="purple">부담최소</u></p>
					<p>환율의 부담이 적어<br> 해외결제시,
						<br> 수수료 부담을 덜 수 있습니다.
					</p>
				</li>
				<li>
					<p><img src="{{ asset('/image/section_1/m-advan-03.svg') }}" alt="advan" /></p>
					<p><u class="purple">빠른</u> 코인결제처리</p>
					<p>아이시디스에서 개발한<br> 올코인월렛어플과 연동하여<br> 더욱 빠른 코인결제처리가 가능합니다.
					</p>
				</li>

			</ul>


		</div>

		<div class="m_section_3 swiper-container">

			<ul class="online_or_offline tap_ver">

				<li>
					<p><u>온라인</u> 결제시스템</p>
					<p>On-line Payment Gateway</p>
				</li>

			</ul>

			<div class="middle_line"></div>

			<div class="slide_con_wrapper online_wrap swiper-wrapper">

				<div class="slide_con_2 swiper-slide">

					<div class="all_wrap">

						<div class="moniter_wrap">

							<div class="moniter_area">

								<img src="{{ asset('/image/section_2/moniter.png') }}" alt="moniter" class="moniter" />

								<div class="moniter_view">

									<img src="{{ asset('/image/section_2/view_1.svg') }}" alt="view_1" />

									<img src="{{ asset('/image/section_2/all_com_popup.png') }}" alt="popup" class="allcoin_popup hide">

									<div class="view_darker hide"></div>

									<img src="{{ asset('/image/section_2/all_online_hand.png') }}" alt="online_hand" class="online_hand hide" />

									<img src="{{ asset('/image/section_2/popup_end.svg') }}" alt="popup_end" class="popup_end hide" />

								</div>

							</div>

						</div>

						<div class="con_box order_1">

							<p>
								<span class="list_circle">1</span>
								<span>결제수단 선택시,<br>　　&nbsp;&nbsp;'Allcoin페이 결제'선택</span>
							</p>

							<p>
								<span>온라인 쇼핑몰에서 결제할 때<br>결제수단에서 Allcoin페이를 선택합니다.</span>
							</p>

						</div>


					</div>

				</div>

				<div class="slide_con_2 swiper-slide">

					<div class="all_wrap">

						<div class="moniter_wrap">

							<div class="moniter_area">

								<img src="{{ asset('/image/section_2/moniter.png') }}" alt="moniter" class="moniter" />

								<div class="moniter_view">

									<img src="{{ asset('/image/section_2/view_1.svg') }}" alt="view_1" />

									<img src="{{ asset('/image/section_2/all_com_popup.png') }}" alt="popup" class="allcoin_popup">

									<div class="view_darker"></div>

								</div>

							</div>



						</div>

						<div class="con_box order_2">

							<p>
								<span class="list_circle">2</span>
								<span>결제 QR코드 생성</span>
							</p>

							<p>
								<span>결제금액을 실시간 시세로 변환한 코인과<br>
									전송해야할 지갑주소가 합쳐진<br>
									QR코드가 생성됩니다.
								</span>
							</p>

						</div>


					</div>

				</div>

				<div class="slide_con_2 swiper-slide">

					<div class="all_wrap">

						<div class="moniter_wrap">

							<div class="moniter_area">

								<img src="{{ asset('/image/section_2/moniter.png') }}" alt="moniter" class="moniter" />

								<div class="moniter_view">

									<img src="{{ asset('/image/section_2/view_1.svg') }}" alt="view_1" />

									<img src="{{ asset('/image/section_2/all_com_popup.png') }}" alt="popup" class="allcoin_popup">

									<div class="view_darker"></div>

									<img src="{{ asset('/image/section_2/all_online_hand.png') }}" alt="online_hand" class="online_hand" />

								</div>

							</div>

						</div>

						<div class="con_box order_3">

							<p>
								<span class="list_circle">3</span>
								<span>스마트폰의 월렛어플을 통해<br>　　 QR코드 스캔하기</span>
							</p>

							<p>
								<span>
									Allcoin Wallet 어플의<br>QR코드 스캔기능을 통해 코인을 결제합니다.
								</span>
							</p>


							<label class="tap_show_items" for="m_items_check">
									<u class="red_color">!</u> &nbsp;유의사항 보기
								</label>

							<input type="checkbox" id="m_items_check" />
							<div class="noted_items">
								<div class="items">
									<u class="purple">• </u>
									<span>
											&nbsp;올코인페이 서비스는 <u class="red_color">올코인월렛 어플</u>과 최적화 연동되어있으며, <br>　타 월렛 어플보다 더욱 빠른 결제가 가능합니다. <br>　(다른 범용 월렛을 통한 코인결제도 가능합니다. <br><u class="red_color">　단, 결제처리시간이 오래 걸릴 수 있는 점 유의바랍니다.)</u>
										</span>
								</div>


								<div class="items">
									<u class="purple">• </u>
									<span>
											&nbsp;모바일에서 실행하는 온라인 결제시스템은<br>　QR코드 주소 복사/Allcoin 월렛 바로가기를 통해 결제가 가능합니다.
										</span>
								</div>

							</div>

						</div>



					</div>

				</div>

				<div class="slide_con_2 swiper-slide">

					<div class="all_wrap">

						<div class="moniter_wrap">

							<div class="moniter_area">

								<img src="{{ asset('/image/section_2/moniter.png') }}" alt="moniter" class="moniter" />

								<div class="moniter_view">

									<img src="{{ asset('/image/section_2/view_1.svg') }}" alt="view_1" />

									<div class="view_darker"></div>

									<img src="{{ asset('/image/section_2/popup_end.svg') }}" alt="popup_end" class="popup_end" />

								</div>

							</div>

						</div>

						<div class="con_box order_4">

							<p>
								<span class="list_circle">4</span>
								<span>QR코드 스캔 완료시,<br>　　 결제 완료</span>
							</p>

							<p>
								<span>
									QR코드 스캔을 다 한 후,<br>입금완료 버튼을 누르면 결제가 완료됩니다.
								</span>
							</p>

						</div>


					</div>

				</div>

			</div>


			<div class="swiper-button-next next_but">

				<u class="purple">NEXT</u>

				<b class="next_btn">▶</b>

			</div>

			<div class="swiper-button-prev prev_but">

				<u class="purple">PREV</u>

				<b class="next_btn">◀</b>

			</div>

		</div>

		<div class="m_section_4 swiper-container">

			<ul class="online_or_offline tap_ver">

				<li>
					<p><u>오프라인</u> 결제시스템</p>
					<p>Off-line Value Added Network</p>
				</li>

			</ul>

			<div class="middle_line"></div>

			<div class="slide_con_wrapper online_wrap swiper-wrapper">

				<div class="slide_con_2 swiper-slide">

					<div class="all_wrap">

						<div class="moniter_wrap offline_mnt_wrap">

							<div class="tap_offline_box_1">

								<img src="{{ asset('/image/section_2/all_offline_phone_1.png') }}" alt="offline_phone" />

								<span> 》》》》》</span>

								<img src="{{ asset('/image/section_2/all_offline_phone_2.png') }}" alt="offline_phone" />

							</div>

							<div class="tap_offline_box_2 hide">
								<img src="{{ asset('/image/section_2/on_04_phone.png') }}" alt="offline_phone_2" class="phone_mini" />
								<img src="{{ asset('/image/section_2/all_offline_phone_3.png') }}" alt="offline_phone_2" class="phone_zoom" />
							</div>

						</div>

						<div class="con_box order_1">

							<p>
								<span class="list_circle">1</span>
								<span>Allcoin Pay 관리자 로그인 후,<br>　　&nbsp;&nbsp;결제금액 입력</span>
							</p>

							<p>
								<span>Allcoin pay 관리자화면으로<br>접속하여 로그인한 후, 결제금액 입력</span>
							</p>

						</div>


					</div>


				</div>

				<div class="slide_con_2 swiper-slide">

					<div class="all_wrap">

						<div class="moniter_wrap offline_mnt_wrap">

							<div class="tap_offline_box_2">
								<img src="{{ asset('/image/section_2/on_04_phone.png') }}" alt="offline_phone_2" class="phone_mini" />
								<img src="{{ asset('/image/section_2/all_offline_phone_3.png') }}" alt="offline_phone_2" class="phone_zoom" />
							</div>

						</div>

						<div class="con_box order_1">

							<p>
								<span class="list_circle">2</span>
								<span>QR코드 생성 후 결제</span>
							</p>

							<p>
								<span>결제금액 입력한 QR코드를<br>고객에게 보여드린 후 결제</span>
							</p>

						</div>


					</div>


				</div>

			</div>


			<div class="swiper-button-next next_but">
				<u class="purple">NEXT</u>
				<b class="next_btn">▶</b>
			</div>
			<div class="swiper-button-prev prev_but">
				<u class="purple">PREV</u>
				<b class="next_btn">◀</b>
			</div>

		</div>


		<div class="m_section_5">

			<ul class="cs_box">

				<h3><u class="purple">서비스</u> 문의하기</h3>

				<li>
					<span class="icon"><img src="{{ asset('/image/section_3/cs_icon-01.svg') }}" alt="time"/></span>
					<h4>상담가능시간</h4>
					<p>평일 10:00~18:00 (점심시간 12:00~13:00)</p>
					<p>주말·공휴일 휴무</p>
				</li>

				<li>
					<span class="icon"><img src="{{ asset('/image/section_3/cs_icon-02.svg') }}" alt="phone"/></span>
					<h4>연락처</h4>
					<p>고객센터 1600-4719</p>
				</li>

				<li>
					<span class="icon"><img src="{{ asset('/image/section_3/cs_icon-03.svg') }}" alt="mail"/></span>
					<h4>이메일주소</h4>
					<p>joong3333@aicdss.com</p>
				</li>

			</ul>

			<footer>

				<ul>

					<li>
						<img src="{{ asset('/image/logo/img_AICDSS_logo_footer.png') }}" alt="aicdss_logo" class="m_ver" />
					</li>

					<li>(주)아이시디스 주식회사　대표자 : 이윤자　문의메일 : joong3333@aicdss.com</li>

					<li>사업자등록번호 : 409-86-54202　통신판매업 번호 : 제 2018-부산중구-0200호</li>

				</ul>

				<ul>

					<li>COPYRIGHT @2018 Allcoin PAY BY AICDSS</li>

				</ul>

			</footer>

		</div>

	</div>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
	<script src="{{ asset('/js/landing.js') }}"></script>
</body>

</html>