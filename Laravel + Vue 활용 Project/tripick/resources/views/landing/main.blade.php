<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>tripick</title>

    <link rel="stylesheet" href="/landing/css/normalize.css">
    <link rel="stylesheet" href="/landing/css/fullpage.css">
    <link rel="stylesheet" href="/landing/css/base.css">
    <link rel="stylesheet" href="/landing/css/main.css">
    <link rel="stylesheet" href="/landing/css/animation.css">
    <link rel="stylesheet" href="/landing/css/responsive.css">

    <script src="/landing/js/jquery-3.3.1.min.js"></script>
    <script src="/landing/js/fullpage.min.js"></script>
    <script src="/landing/js/main.js"></script>

</head>
<body>
    <header id="t_hd">
        <h1>
            <a href="#main" class="logo">Tripick logo</a>
        </h1>
        <nav id="t_gnb">
            <div class="gnb_icon"></div>
            <a href="#main" data-menuanchor="main" class="gnb_1 active">Main</a>
            <a href="#appdown1" data-menuanchor="appdown1" class="gnb_2">App 소개 01</a>
            <a href="#appdown2" data-menuanchor="appdown2" class="gnb_3">App 소개 02</a>
            <a href="#appdown3" data-menuanchor="appdown3" class="gnb_4">App 소개 03</a>
            <a href="#plannerreg" data-menuanchor="plannerreg" class="gnb_5">플래너 가입</a>
        </nav>
    </header>
    <div class="next_arrow active"></div>
    <div id="t_fullpage">
        <section id="t_main" class="section">
            <div class="onlybg"></div>
            <h2>
                <div>
                    <span class="ani_h2 ani_h2_1">세상에서 </span>
                    <strong class="ani_h2 ani_h2_2">나를</strong>
                </div>
                <div class="clear">
                    <strong class="ani_h2 ani_h2_3">
                        <span class="text_ani text_ani1">가</span>
                        <span class="text_ani text_ani2">장</span>
                        <span class="text_ani text_ani3">&nbsp;잘&nbsp;</span>
                        <span class="text_ani text_ani4">아</span>
                        <span class="text_ani text_ani5">는&nbsp;</span>
                    </strong> 
                    <span class="ani_h2 ani_h2_4">여행</span>
                </div>
            </h2>
            <div class="main--appdown_wrap">
                <a href="https://play.google.com/store/apps/details?id=com.xn__oy2b117blyb.www" class="main--appdown__btn main--appdown__btn1">구글플레이</a>
                <a href="#" class="main--appdown__btn main--appdown__btn2">앱스토어</a>
            </div>
            <div class="mo_planbtn"><a href="#plannerreg">플래너 신청 바로가기</a></div>
        </section>
        <section id="t_appdown1" class="section">
            <div class="bg_circle cir__bag"></div>
            <div class="bg_circle cir__hum"></div>
            <div class="bg_circle cir__cal"></div>
            <div class="bg_circle cir__map"></div>
            <div class="bg_cloud"></div>
            <div class="bg_airplane"></div>
            <div>
                <div class="tab__img tab__img1 tab__img1_1 active"></div>
                <div class="tab__img tab__img1 tab__img1_2"></div>
            </div>
            <div class="mo_content1">
                <h2>간편 입력</h2>
                <div class="t_appdown1--travel_wrap">
                    <div class="travel--tab__title clear">
                        <div class="tab1__title--wrap">
                            <div class="tab__title tab1__title tab1__title1 active">
                                <a href="#">여행정보 입력</a>
                            </div>
                            <div class="tab__title tab1__title tab1__title2">
                                <a href="#">여행테마 입력</a>
                            </div>
                        </div>
                    </div>
                    <div class="travel--tab__desc travel1--tab travel1--tab1 active">
                        <div class="tab__text">내 여행 정보를 간편, 신속하게 입력하여 맞춤형 여행정보를 받아보실 수 있습니다.</div>
                    </div>
                    <div class="travel--tab__desc travel1--tab travel1--tab2">
                        <div class="tab__text">여행 테마를 입력하여 내게 더욱 잘 맞는 여행을 추천받을 수 있습니다.</div>
                    </div>
                </div>
            </div>
        </section>
        <section id="t_appdown2" class="section">
            <div class="bg_cloud"></div>
            <div class="bg_airplane"></div>
            <div class="mo_content2">
                <h2>신속 매칭</h2>
                <div class="travel--tab__title .travel--tab__title2 clear">
                    <div class="tab2__title--wrap">
                        <div class="tab__title tab2__title tab2__title1 active"><a href="#">플래너 맞춤 제안</a></div>
                        <div class="tab__title tab2__title tab2__title2"><a href="#">맞춤 상담 문의</a></div>
                    </div>
                </div>
                <div class="t_appdown2--travel_wrap">
                    <div class="travel--tab__desc travel2--tab travel2--tab1 active">
                        <div class="tab__text">입력한 내 여행정보를 토대로 트리픽 플래너가 나만의 여행을 제안합니다.</div>
                        <div class="tab__img--wrap">
                            <div class="tab__img--ms tab__img--ms1 clear">
                                <p class="tr_name">김민희</p>
                                <p class="tr_form">개인</p>
                                <p class="tr_pl_info">가족,커플 가을 추천 여행지! 국내여행!</p>
                                <p class="tr_prace" data-num="155000">0</p>
                                <p class="tr_won">원</p>
                                <p class="tr_star" data-num="43">0</p>
                            </div>
                            <div class="tab__img--ms tab__img--ms2 clear">
                                    <p class="tr_name">김철수</p>
                                    <p class="tr_form">개인</p>
                                    <p class="tr_pl_info">여행을 가볍게</p>
                                    <p class="tr_prace" data-num="65000">0</p>
                                    <p class="tr_won">원</p>
                                    <p class="tr_star" data-num="47">0</p>
                            </div>
                            <div class="tab__img--ms tab__img--ms3 clear">
                                    <p class="tr_name">트리픽</p>
                                    <p class="tr_form">기업</p>
                                    <p class="tr_pl_info">춘천지역 5년차의 숙련된 지역 가이드!</p>
                                    <p class="tr_prace" data-num="65000">0</p>
                                    <p class="tr_won">원</p>
                                    <p class="tr_star" data-num="42">0</p>
                            </div>
                            <div class="tab__img--ms tab__img--ms4 clear">
                                    <p class="tr_name">강슬기</p>
                                    <p class="tr_form">개인</p>
                                    <p class="tr_pl_info">서울 토박이 25년차, 서울에서만...</p>
                                    <p class="tr_prace" data-num="65000">0</p>
                                    <p class="tr_won">원</p>
                                    <p class="tr_star" data-num="49">0</p>
                            </div>
                            <div class="tab__img--ms tab__img--ms5 clear">
                                    <p class="tr_name">김진구</p>
                                    <p class="tr_form">개인</p>
                                    <p class="tr_pl_info">비!교!불!가! 제주도 여행 마스터</p>
                                    <p class="tr_prace" data-num="65000">0</p>
                                    <p class="tr_won">원</p>
                                    <p class="tr_star" data-num="40">0</p>
                            </div>
                        </div>
                    </div>
                    <div class="travel--tab__desc travel2--tab travel2--tab2">
                        <div class="tab__text">입력한 내 여행 테마정보를 토대로 트리픽 플래너가 나만의 여행을 제안합니다.</div>
                        <div class="tab__img--wrap tab__img--wrap2">
                            <div class="tab__img--bg">
                                <div class="tab__img tab__img2_1 tab__img--pl tab__img--pl1"></div>
                                <div class="tab__img tab__img2_1 tab__img--sb1"></div>
                                <div class="tab__img tab__img2_2 tab__img--pl tab__img--pl2"></div>
                                <div class="tab__img tab__img2_2 tab__img--sb2">
                                    <span class="tr_allprace">
                                        <strong class="tr_prace" data-num="225000">0</strong> 원
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="t_appdown3" class="section">
            <div class="bg_airplane"></div>
            <div class="bgimg__bg"></div>
            <div class="bgimg__pop"></div>
            <div class="bg_cloud"></div>
            <div class="mo_content3">
                <h2>여행 시작!</h2>
                <div class="app3_con">매칭된 트리픽 플래너와 <br>단 하나뿐인 힐링 여행을 떠납니다.</div>
            </div>
        </section>
        <section id="t_plannerreg" class="section">
            <div class="mo_content4">
                <h2 class="plan--title">
                    <small class="fain fain_1">
                            <span>세상에서 </span>
                            <strong>나를</strong>
                            <strong>가장 잘 아는 </strong> 
                            <span>여행</span>
                    </small>
                    <p class="fain fain_2">트리픽의</p>
                    <strong class="fain fain_3">맞춤 플래너</strong>
                    <span class="fain fain_4">가</span>
                    <p class="fain fain_5">되어주세요.</p>
                </h2>
                <a href="/landing/tripick_introduce.pdf" class="btn--ghost">회사소개서 다운받기</a>
                <!--a href="/splash" class="btn--ghost">플래너 신청하기</a-->
                <div class="form__planner">
                    <form method="#" action="#">
                        <h3>플래너 신청</h3>
                        <label class="blind"></label>
                        <input type="text" placeholder="성함을 입력해주세요." class="in_textbox" maxlength="10" name="name">
                        <label class="blind"></label>
                        <input type="text" placeholder="E-Mail을 입력해주세요." class="in_textbox" name="email">
                        <label class="blind"></label>
                        <input type="tel" placeholder="연락처를 입력해주세요." class="in_textbox" maxlength="12" name="phone">
                        <label class="blind"></label>
                        <input type="text" placeholder="활동 지역을 입력해주세요." class="in_textbox" name="active_area">
                        <button class="plan_btn" type="button" id="pln_regist">제출하기</button>
                    </form>
                    <footer class="tr_ft clear">
                        <small>트리픽 채널<br>둘러보기</small>
                        <div class="tr_sns">
                            <a href="#" class="tr_nb">네이버블로그</a>
                            <a href="#" class="tr_is">인스타그램</a>
                            <a href="#" class="tr_fb">페이스북</a>
                        </div>
                    </footer>
                </div>
            </div>
        </section>
        <script>
        	$('#pln_regist').click(function(){
        		$.ajax({
					url: "/api/landingplr",
					data: {
						name: $('[name=name]').val(),
						email: $('[name=email]').val(),
						phone: $('[name=phone]').val(),
						active_area: $('[name=active_area]').val()
					},
					method: "POST",
					dataType: "json",
					async:false
				})
				.done(function(res) {
					if (res.state == 1) {
						alert('신청 되었습니다');
						location.reload();
					} else {
						alert('신청 오류(정확한 정보를 입력해주세요)');
					}
				})
				.fail(function(xhr, status, errorThrown) {
					console.log(xhr);
				}) // 
				.always(function(xhr, status) {});
        	});
			
			
        </script>
    </div>
</body>
</html>