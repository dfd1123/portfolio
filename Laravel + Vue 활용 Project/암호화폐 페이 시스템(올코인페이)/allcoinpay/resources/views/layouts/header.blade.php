<div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="{{ route('home') }}" target="_blank" class="site_title">
                            <span><img src="{{ asset('/image/logo/main_logo.svg') }}"/></span>
                        </a>

                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_info">
                            @if(Auth::check())
                            <p>올코인페이 관리자 페이지</p>
                            <span>Welcome,</span>
                            <h2>{{Auth::user()->fullname}}</h2>
                            @else
                            <p>올코인페이 관리자 페이지</p>
                            <span>로그인 해주세요</span>
                            @endif
                        </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <ul class="nav side-menu">
                                <li>
                                    <a>
                                    	<span class="icon_wrap">
                                    	<img src="{{ asset('/image/icon/admin_icon_02.svg') }}" alt="icon_2" class="icon_b"/>
                                    	<img src="{{ asset('/image/icon/admin_icon_02-w.svg') }}" alt="icon_2" class="icon_w"/>
                                    	</span>
                                    	결제모듈</a>
                                    <ul class="nav child_menu">
                                    	@if($company_confirm == 1)
                                    	<li><a href="{{ route('payment') }}">결제하기</a></li>
                                    	<li><a href="{{ route('payment_history') }}">결제현황</a></li>
                                    	<li><a href="{{ route('api_detail') }}">API</a></li>
                                    	@endif
                                        <li><a href="{{ route('company') }}">사업자정보 관리</a></li>
                                    </ul>
                                </li>
                                
                                <!--li>
                                    <a>
                                    	<span class="icon_wrap">
                                    	<img src="{{ asset('/image/icon/admin_icon_02.svg') }}" alt="icon_1" class="icon_b"/>
                                    	<img src="{{ asset('/image/icon/admin_icon_02-w.svg') }}" alt="icon_1" class="icon_w"/>
                                    	</span>
                                    	지갑</span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="#">출금신청</a></li>
                                        <li><a href="#">마이 페이지</a></li>
                                    </ul>
                                </li-->
                                
                                <!--li>
                                    <a> <span class="icon_wrap">
                                    	<img src="{{ asset('/image/icon/admin_icon_03.svg') }}" alt="icon_3" class="icon_b"/>
                                    	<img src="{{ asset('/image/icon/admin_icon_03-w.svg') }}" alt="icon_3" class="icon_w"/>
                                    	</span>
                                    	고객센터 </a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('notice') }}">공지사항</a></li>
                                    </ul>
                                </li-->
                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->


					<div class="bottom_nav">
						<label for="lang_choice">
							<p class="lang_btn">
								<span>
									<img src="{{ asset('/image/icon/kr.png') }}" alt="kr_flag"/>Korean
								</span>
							</p>
						</label>
				
						<input id="lang_choice" type="checkbox"/>
						<!--ul class="lang_list">
							<li><a href="#"><img src="{{ asset('/image/icon/eng.png') }}" alt="eng_flag"/>English</a></li>
							<li><a href="#"><img src="{{ asset('/image/icon/jp.png') }}" alt="jp_flag"/>Japan</a></li>
						</ul-->
					</div>
	
                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">

                            <!--login 됬을때-->
							@if(Auth::check())
                            <li class="">
		                    	<a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">로그아웃</a>
		                    	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									@csrf
								</form>
                            </li>
                            @else
                            <!-- login 아닐때 -->
                            <li><a href="{{ route('login') }}">로그인</a></li>
                            <li><a href="{{ route('register') }}">회원가입</a></li>
							@endif

                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->
            <!-- page content -->
            <div class="right_col" role="main">


                <input type="hidden" name="url" id="url" value=''>