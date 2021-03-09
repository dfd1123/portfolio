<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
     @csrf
</form>

<nav class="navbar navbar-expand navbar-dark bg-tsa static-top">
	
      <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars mint_color"></i>&nbsp;&nbsp;
      </button>

      <a class="navbar-brand mr-1 tsa_h1" href="{{route('admin.main')}}">{{ __('layout.sys')}}</a>

      <!-- Navbar Search -->
     <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
        
        <!--<div class="input-group tsa_sch_bar">
          <input type="text" class="form-control" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
          <div class="input-group-append">
            <button class="btn org_btn" type="button">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>-->
        
      </form>
			<a class="hd-logout-btn" href="#" data-target="#logoutModal" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
				Logout
			</a>

      <!-- Navbar -->
      <!--<ul class="navbar-nav ml-auto ml-md-0">
      	
        <li class="nav-item dropdown no-arrow mx-1">
          <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell fa-fw"></i>
            <span class="badge badge-danger">9+</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li>
			 
			 
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user-circle fa-fw"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="#">Settings</a>
            <a class="dropdown-item" href="#">Activity Log</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
          </div>
        </li>
			</ul>
			-->

</nav>
<div id="wrapper">
	@if(Auth::guard('admin')->check())
		<!-- Sidebar -->
		<ul class="sidebar navbar-nav tsa-sidebar">
			<li class="nav-item active">
				<a class="nav-link" href="{{route('admin.main')}}">
					<i class="fas fa-fw fa-home"></i>
					<span> {{ __('layout.home')}}</span>
				</a>
			</li>
			@if(Auth::guard('admin')->user()->level == 1)
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="mint_color">&nbsp;•&nbsp;</i>
					<span> &nbsp;{{ __('layout.ex')}}</span>
				</a>
				<div class="dropdown-menu" aria-labelledby="pagesDropdown">
					<a class="dropdown-item" href="{{route('admin.market_edit')}}">{{ __('layout.info')}}</a>
					<a class="dropdown-item" href="{{route('admin.rights_management_list')}}">{{ __('layout.set')}}</a>
					<a class="dropdown-item" href="{{route('admin.admin_activity')}}">{{ __('layout.activity')}}</a>
					<a class="dropdown-item" href="{{route('admin.fee_edit')}}">{{ __('layout.feeset')}}</a>
					<a class="dropdown-item" href="{{route('admin.recommender_edit')}}">{{ __('layout.rec')}}</a>
				</div>
			</li>
			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mint_color">&nbsp;•&nbsp;</i>
				<span> &nbsp;{{ __('layout.coiner')}}</span>
			</a>
			<div class="dropdown-menu" aria-labelledby="pagesDropdown">
				<a class="dropdown-item" href="{{route('admin.user_list')}}">{{ __('layout.userinfo')}}</a>
					<!--
					<a class="dropdown-item" href="{{route('admin.document_list',5)}}">{{ __('layout.idcard')}}</a>
					-->
					<a class="dropdown-item" href="{{route('admin.account_list',5)}}">{{ __('layout.book')}}</a>
					
					<a class="dropdown-item" href="{{route('admin.user_balance_activity')}}">{{ __('layout.wallet')}}</a>
					<a class="dropdown-item" href="{{route('admin.user_trace_list')}}">회원 로그인 이력</a>
					<a class="dropdown-item" href="{{route('admin.user_list_new')}}">이달 신규유저 관리</a>
					<a class="dropdown-item" href="{{route('admin.user_list_now')}}">현재 접속 유저</a>
			</div>
			</li>
			<li class="nav-item">
					<a class="nav-link" href="{{route('admin.cash_list')}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;{{ __('layout.set12')}}</span>
					</a>
			</li>

			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mint_color">&nbsp;•&nbsp;</i>
				<span> &nbsp;{{ __('layout.coin')}}</span>
			</a>
			<div class="dropdown-menu" aria-labelledby="pagesDropdown">
				<a class="dropdown-item" href="{{route('admin.coin_listing_list')}}">{{ __('layout.set1')}}</a>
					<a class="dropdown-item" href="{{route('admin.coin_out_history','all')}}">{{ __('layout.set2')}}</a>
					<a class="dropdown-item" href="{{route('admin.user_balance_coin','krw')}}">코인별 유저 보유량</a>
					<a class="dropdown-item" href="{{route('admin.coin_lock_list', 0)}}">{{ __('layout.set3')}}</a>
					<a class="dropdown-item" href="{{route('admin.airdrop_list')}}">{{ __('layout.set4')}}</a>
					<a class="dropdown-item" href="{{route('admin.coin_tr_list')}}">{{ __('layout.set5')}}</a>
					<a class="dropdown-item" href="{{route('admin.coin_has_list')}}">코인현황</a>
			</div>
			</li>

			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mint_color">&nbsp;•&nbsp;</i>
				<span> &nbsp;{{ __('layout.set6')}}</span>
			</a>
			<div class="dropdown-menu" aria-labelledby="pagesDropdown">
				<!--a class="dropdown-item" href="{{route('admin.deposite_withdraw_list')}}">{{ __('layout.set7')}}</a-->
					<a class="dropdown-item" href="{{route('admin.trade_history')}}">{{ __('layout.set8')}}</a>
					<a class="dropdown-item" href="{{route('admin.trade_error')}}">거래 체결 오류 목록</a>
					<a class="dropdown-item" href="{{route('admin.auto_setting')}}">자동거래 셋팅</a>
			</div>
			</li>
			<li class="nav-item">
					<a class="nav-link" href="{{route('admin.p2p_list',0)}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;{{ __('layout.set9')}}</span>
					</a>
			</li>
			<li class="nav-item">
					<a class="nav-link" href="{{route('admin.ico_list')}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;{{ __('layout.set11')}}</span>
					</a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="mint_color">&nbsp;•&nbsp;</i>
					<span> &nbsp;커뮤니티</span>
				</a>
				<div class="dropdown-menu" aria-labelledby="pagesDropdown">
					<a class="dropdown-item" href="{{route('admin.comunity_manage_list')}}">커뮤니티 관리</a>
					<a class="dropdown-item" href="{{route('admin.comunity_admin_list')}}">커뮤니티 관리자</a>
				</div>
			</li>
			<li class="nav-item">
					<a class="nav-link" href="{{route('admin.game_schedule_list')}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;경기일정 관리</span>
					</a>
			</li>
			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mint_color">&nbsp;•&nbsp;</i>
				<span> &nbsp;{{ __('layout.set10')}}</span>
			</a>
			<div class="dropdown-menu" aria-labelledby="pagesDropdown">
				<a class="dropdown-item" href="{{route('admin.event_list')}}">{{ __('layout.ev')}}</a>
					<a class="dropdown-item" href="{{route('admin.banner_list')}}">{{ __('layout.bn')}}</a>
					<a class="dropdown-item" href="{{route('admin.youtube_list')}}">Youtube</a>
			</div>
			</li>
			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mint_color">&nbsp;•&nbsp;</i>
				<span> &nbsp;{{ __('layout.setpop')}}</span>
			</a>
			<div class="dropdown-menu" aria-labelledby="pagesDropdown">
				<a class="dropdown-item" href="{{route('admin.popup_list', 'kr')}}">{{ __('layout.korea')}}</a>
				<a class="dropdown-item" href="{{route('admin.popup_list', 'jp')}}">{{ __('layout.japan')}}</a>
				<a class="dropdown-item" href="{{route('admin.popup_list', 'ch')}}">{{ __('layout.china')}}</a>
				<a class="dropdown-item" href="{{route('admin.popup_list', 'en')}}">{{ __('layout.usa')}}</a>
			</div>
			</li>
			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mint_color">&nbsp;•&nbsp;</i>
				<span> &nbsp;{{ __('layout.ko')}}</span>
			</a>
			<div class="dropdown-menu" aria-labelledby="pagesDropdown">
				<a class="dropdown-item" href="{{route('admin.notice_list', 'kr')}}">{{ __('layout.1')}}</a>
				<a class="dropdown-item" href="{{route('admin.newsflash_list', 'kr')}}">속보</a>
				<a class="dropdown-item" href="{{route('admin.news_list', 'kr')}}">{{ __('layout.2')}}</a>
				<a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'kr', 'types'=>0])}}">{{ __('layout.3')}}</a>
				<a class="dropdown-item" href="{{route('admin.qna_list','kr')}}">{{ __('layout.4')}}</a>
				<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'kr'])}}">{{ __('layout.5')}}</a>
			</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="mint_color">&nbsp;•&nbsp;</i>
					<span> &nbsp;{{ __('layout.jp')}}</span>
				</a>
				<div class="dropdown-menu" aria-labelledby="pagesDropdown">
				<a class="dropdown-item" href="{{route('admin.notice_list', 'jp')}}">{{ __('layout.1')}}</a>
				<a class="dropdown-item" href="{{route('admin.newsflash_list', 'jp')}}">NEWSFLASH</a>
				<a class="dropdown-item" href="{{route('admin.news_list', 'jp')}}">{{ __('layout.2')}}</a>
				<a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'jp', 'types'=>0])}}">{{ __('layout.3')}}</a>
				<a class="dropdown-item" href="{{route('admin.qna_list','jp')}}">{{ __('layout.4')}}</a>
				<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'jp'])}}">{{ __('layout.5')}}</a>
			</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="mint_color">&nbsp;•&nbsp;</i>
					<span> &nbsp;{{ __('layout.ch')}}</span>
				</a>
				<div class="dropdown-menu" aria-labelledby="pagesDropdown">
					<a class="dropdown-item" href="{{route('admin.notice_list', 'ch')}}">{{ __('layout.1')}}</a>
					<a class="dropdown-item" href="{{route('admin.newsflash_list', 'ch')}}">NEWSFLASH</a>
					<a class="dropdown-item" href="{{route('admin.news_list', 'ch')}}">{{ __('layout.2')}}</a>
					<a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'ch', 'types'=>0])}}">{{ __('layout.3')}}</a>
					<a class="dropdown-item" href="{{route('admin.qna_list','ch')}}">{{ __('layout.4')}}</a>
					<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'ch'])}}">{{ __('layout.5')}}</a>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="mint_color">&nbsp;•&nbsp;</i>
					<span> &nbsp;{{ __('layout.en')}}</span>
				</a>
				<div class="dropdown-menu" aria-labelledby="pagesDropdown">
					<a class="dropdown-item" href="{{route('admin.notice_list', 'en')}}">{{ __('layout.1')}}</a>
					<a class="dropdown-item" href="{{route('admin.newsflash_list', 'en')}}">NEWSFLASH</a>
					<a class="dropdown-item" href="{{route('admin.news_list', 'en')}}">{{ __('layout.2')}}</a>
					<a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'en', 'types'=>0])}}">{{ __('layout.3')}}</a>
					<a class="dropdown-item" href="{{route('admin.qna_list','en')}}">{{ __('layout.4')}}</a>
					<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'en'])}}">{{ __('layout.5')}}</a>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{route('admin.term_service', 'kr')}}">
					<i class="mint_color">&nbsp;•&nbsp;</i>
					<span> &nbsp;{{ __('layout.term_manage')}}</span>
				</a>
			</li>
				@if(Auth::guard('admin')->user()->id == 7 || Auth::guard('admin')->user()->id == 9 || Auth::guard('admin')->user()->id == 10
					|| Auth::guard('admin')->user()->id == 19 || Auth::guard('admin')->user()->id == 21)
					<li class="nav-item dropdown">
						<li class="nav-item">
							<a class="nav-link" href="{{route('admin.n_event_current')}}">
								<i class="mint_color">&nbsp;•&nbsp;</i>
								<span> &nbsp;{{ __('layout.n_current')}}</span>
							</a>
						</li>
					</li>
				@endif
			<li class="nav-item dropdown">
				<li class="nav-item">
					<a class="nav-link" href="{{route('admin.n_event_winners_list')}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;{{ __('layout.n_winners_list')}}</span>
					</a>
				</li>
			</li>
			@elseif(Auth::guard('admin')->user()->level == 2)
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
			    <span> &nbsp;{{ __('layout.ex')}}</span>
			  </a>
			  <div class="dropdown-menu" aria-labelledby="pagesDropdown">
					<a class="dropdown-item" href="{{route('admin.rights_management_list')}}">{{ __('layout.set')}}</a>
			  </div>
			</li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
			    <span> &nbsp;{{ __('layout.coiner')}}</span>
			  </a>
			  <div class="dropdown-menu" aria-labelledby="pagesDropdown">
			    <a class="dropdown-item" href="{{route('admin.user_list')}}">{{ __('layout.userinfo')}}</a>
					<a class="dropdown-item" href="{{route('admin.account_list',5)}}">{{ __('layout.book')}}</a>
					<a class="dropdown-item" href="{{route('admin.user_balance_activity')}}">{{ __('layout.wallet')}}</a>
					<a class="dropdown-item" href="{{route('admin.user_trace_list')}}">회원 로그인 이력</a>
					<a class="dropdown-item" href="{{route('admin.user_list_new')}}">이달 신규유저 관리</a>
					<a class="dropdown-item" href="{{route('admin.user_list_now')}}">현재 접속 유저</a>
			  </div>
			</li>
			<li class="nav-item">
					<a class="nav-link" href="{{route('admin.cash_list')}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;{{ __('layout.set12')}}</span>
					</a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="mint_color">&nbsp;•&nbsp;</i>
					<span> &nbsp;{{ __('layout.coin')}}</span>
				</a>
				<div class="dropdown-menu" aria-labelledby="pagesDropdown">
					<a class="dropdown-item" href="{{route('admin.coin_listing_list')}}">{{ __('layout.set1')}}</a>
					<a class="dropdown-item" href="{{route('admin.coin_out_history','all')}}">{{ __('layout.set2')}}</a>
					<a class="dropdown-item" href="{{route('admin.user_balance_coin','krw')}}">코인별 유저 보유량</a>
					<a class="dropdown-item" href="{{route('admin.coin_lock_list', 0)}}">{{ __('layout.set3')}}</a>
					<a class="dropdown-item" href="{{route('admin.airdrop_list')}}">{{ __('layout.set4')}}</a>
					<a class="dropdown-item" href="{{route('admin.coin_tr_list')}}">{{ __('layout.set5')}}</a>
					<a class="dropdown-item" href="{{route('admin.coin_has_list')}}">코인현황</a>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="mint_color">&nbsp;•&nbsp;</i>
					<span> &nbsp;{{ __('layout.set6')}}</span>
				</a>
				<div class="dropdown-menu" aria-labelledby="pagesDropdown">
					<!--a class="dropdown-item" href="{{route('admin.deposite_withdraw_list')}}">{{ __('layout.set7')}}</a-->
					<a class="dropdown-item" href="{{route('admin.trade_history')}}">{{ __('layout.set8')}}</a>
					<a class="dropdown-item" href="{{route('admin.trade_error')}}">거래 체결 오류 목록</a>
					<a class="dropdown-item" href="{{route('admin.auto_setting')}}">자동거래 셋팅</a>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{route('admin.p2p_list',0)}}">
					<i class="mint_color">&nbsp;•&nbsp;</i>
					<span> &nbsp;{{ __('layout.set9')}}</span>
				</a>
			</li>
			<li class="nav-item">
					<a class="nav-link" href="{{route('admin.ico_list')}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;{{ __('layout.set11')}}</span>
					</a>
			</li>
			<li class="nav-item">
					<a class="nav-link" href="{{route('admin.comunity_manage_list')}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;커뮤니티 관리</span>
					</a>
			</li>
			<li class="nav-item">
					<a class="nav-link" href="{{route('admin.game_schedule_list')}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;경기일정 관리</span>
					</a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="mint_color">&nbsp;•&nbsp;</i>
					<span> &nbsp;{{ __('layout.set10')}}</span>
				</a>
				<div class="dropdown-menu" aria-labelledby="pagesDropdown">
					<a class="dropdown-item" href="{{route('admin.event_list')}}">{{ __('layout.ev')}}</a>
					<a class="dropdown-item" href="{{route('admin.banner_list')}}">{{ __('layout.bn')}}</a>
					<a class="dropdown-item" href="{{route('admin.youtube_list')}}">Youtube</a>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="mint_color">&nbsp;•&nbsp;</i>
					<span> &nbsp;{{ __('layout.setpop')}}</span>
				</a>
				<div class="dropdown-menu" aria-labelledby="pagesDropdown">
					<a class="dropdown-item" href="{{route('admin.popup_list', 'kr')}}">{{ __('layout.korea')}}</a>
					<a class="dropdown-item" href="{{route('admin.popup_list', 'jp')}}">{{ __('layout.japan')}}</a>
					<a class="dropdown-item" href="{{route('admin.popup_list', 'ch')}}">{{ __('layout.china')}}</a>
					<a class="dropdown-item" href="{{route('admin.popup_list', 'en')}}">{{ __('layout.usa')}}</a>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="mint_color">&nbsp;•&nbsp;</i>
					<span> &nbsp;{{ __('layout.ko')}}</span>
				</a>
				<div class="dropdown-menu" aria-labelledby="pagesDropdown">
					<a class="dropdown-item" href="{{route('admin.notice_list', 'kr')}}">{{ __('layout.1')}}</a>
					<a class="dropdown-item" href="{{route('admin.newsflash_list', 'kr')}}">속보</a>
						<a class="dropdown-item" href="{{route('admin.news_list', 'kr')}}">{{ __('layout.2')}}</a>
					<a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'kr', 'types'=>0])}}">{{ __('layout.3')}}</a>
					<a class="dropdown-item" href="{{route('admin.qna_list','kr')}}">{{ __('layout.4')}}</a>
						<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'kr'])}}">{{ __('layout.5')}}</a>
				</div>
			</li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
					<span> &nbsp;{{ __('layout.jp')}}</span>
				</a>
					<div class="dropdown-menu" aria-labelledby="pagesDropdown">
			    <a class="dropdown-item" href="{{route('admin.notice_list', 'jp')}}">{{ __('layout.1')}}</a>
				<a class="dropdown-item" href="{{route('admin.newsflash_list', 'jp')}}">NEWSFLASH</a>
					<a class="dropdown-item" href="{{route('admin.news_list', 'jp')}}">{{ __('layout.2')}}</a>
			    <a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'jp', 'types'=>0])}}">{{ __('layout.3')}}</a>
			    <a class="dropdown-item" href="{{route('admin.qna_list','jp')}}">{{ __('layout.4')}}</a>
					<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'jp'])}}">{{ __('layout.5')}}</a>
			  </div>
			</li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
			    <span> &nbsp;{{ __('layout.ch')}}</span>
			  </a>
			  <div class="dropdown-menu" aria-labelledby="pagesDropdown">
			    <a class="dropdown-item" href="{{route('admin.notice_list', 'ch')}}">{{ __('layout.1')}}</a>
				<a class="dropdown-item" href="{{route('admin.newsflash_list', 'ch')}}">NEWSFLASH</a>
					<a class="dropdown-item" href="{{route('admin.news_list', 'ch')}}">{{ __('layout.2')}}</a>
			    <a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'ch', 'types'=>0])}}">{{ __('layout.3')}}</a>
			    <a class="dropdown-item" href="{{route('admin.qna_list','ch')}}">{{ __('layout.4')}}</a>
					<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'ch'])}}">{{ __('layout.5')}}</a>
			  </div>
			</li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
			    <span> &nbsp;{{ __('layout.en')}}</span>
			  </a>
			  <div class="dropdown-menu" aria-labelledby="pagesDropdown">
			    <a class="dropdown-item" href="{{route('admin.notice_list', 'en')}}">{{ __('layout.1')}}</a>
				<a class="dropdown-item" href="{{route('admin.newsflash_list', 'en')}}">NEWSFLASH</a>
					<a class="dropdown-item" href="{{route('admin.news_list', 'en')}}">{{ __('layout.2')}}</a>
			    <a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'en', 'types'=>0])}}">{{ __('layout.3')}}</a>
			    <a class="dropdown-item" href="{{route('admin.qna_list','en')}}">{{ __('layout.4')}}</a>
					<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'en'])}}">{{ __('layout.5')}}</a>
			  </div>
			</li>
				@if(Auth::guard('admin')->user()->id == 7 || Auth::guard('admin')->user()->id == 9 || Auth::guard('admin')->user()->id == 10
					|| Auth::guard('admin')->user()->id == 19 || Auth::guard('admin')->user()->id == 21)
					<li class="nav-item dropdown">
						<li class="nav-item">
							<a class="nav-link" href="{{route('admin.n_event_current')}}">
								<i class="mint_color">&nbsp;•&nbsp;</i>
								<span> &nbsp;{{ __('layout.n_current')}}</span>
							</a>
						</li>
					</li>
				@endif
			<li class="nav-item dropdown">
				<li class="nav-item">
					<a class="nav-link" href="{{route('admin.n_event_winners_list')}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;{{ __('layout.n_winners_list')}}</span>
					</a>
				</li>
			</li>
			@elseif(Auth::guard('admin')->user()->level == 3)
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mint_color">&nbsp;•&nbsp;</i>
				<span> &nbsp;{{ __('layout.ex')}}</span>
				</a>
				<div class="dropdown-menu" aria-labelledby="pagesDropdown">
					<a class="dropdown-item" href="{{route('admin.rights_management_list')}}">{{ __('layout.set')}}</a>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="mint_color">&nbsp;•&nbsp;</i>
					<span> &nbsp;{{ __('layout.coiner')}}</span>
				</a>
				<div class="dropdown-menu" aria-labelledby="pagesDropdown">
					<a class="dropdown-item" href="{{route('admin.user_list')}}">{{ __('layout.userinfo')}}</a>
					<a class="dropdown-item" href="{{route('admin.account_list',5)}}">{{ __('layout.book')}}</a>
					<a class="dropdown-item" href="{{route('admin.user_balance_activity')}}">{{ __('layout.wallet')}}</a>
					<a class="dropdown-item" href="{{route('admin.user_trace_list')}}">회원 로그인 이력</a>
					<a class="dropdown-item" href="{{route('admin.user_list_new')}}">이달 신규유저 관리</a>
					<a class="dropdown-item" href="{{route('admin.user_list_now')}}">현재 접속 유저</a>
				</div>
			</li>

			<li class="nav-item">
					<a class="nav-link" href="{{route('admin.comunity_manage_list')}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;커뮤니티 관리</span>
					</a>
			</li>
			<li class="nav-item">
					<a class="nav-link" href="{{route('admin.game_schedule_list')}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;경기일정 관리</span>
					</a>
			</li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
			    <span> &nbsp;{{ __('layout.set10')}}</span>
			  </a>
			  <div class="dropdown-menu" aria-labelledby="pagesDropdown">
			    <a class="dropdown-item" href="{{route('admin.event_list')}}">{{ __('layout.ev')}}</a>
					<a class="dropdown-item" href="{{route('admin.banner_list')}}">{{ __('layout.bn')}}</a>
					<a class="dropdown-item" href="{{route('admin.youtube_list')}}">Youtube</a>
			  </div>
			</li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
			    <span> &nbsp;{{ __('layout.setpop')}}</span>
			  </a>
			  <div class="dropdown-menu" aria-labelledby="pagesDropdown">
			    <a class="dropdown-item" href="{{route('admin.popup_list', 'kr')}}">{{ __('layout.korea')}}</a>
				<a class="dropdown-item" href="{{route('admin.popup_list', 'jp')}}">{{ __('layout.japan')}}</a>
				<a class="dropdown-item" href="{{route('admin.popup_list', 'ch')}}">{{ __('layout.china')}}</a>
				<a class="dropdown-item" href="{{route('admin.popup_list', 'en')}}">{{ __('layout.usa')}}</a>
			  </div>
			</li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
			    <span> &nbsp;{{ __('layout.ko')}}</span>
			  </a>
			  <div class="dropdown-menu" aria-labelledby="pagesDropdown">
			    <a class="dropdown-item" href="{{route('admin.notice_list', 'kr')}}">{{ __('layout.1')}}</a>
				<a class="dropdown-item" href="{{route('admin.newsflash_list', 'kr')}}">속보</a>
					<a class="dropdown-item" href="{{route('admin.news_list', 'kr')}}">{{ __('layout.2')}}</a>
			    <a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'kr', 'types'=>0])}}">{{ __('layout.3')}}</a>
			    <a class="dropdown-item" href="{{route('admin.qna_list','kr')}}">{{ __('layout.4')}}</a>
					<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'kr'])}}">{{ __('layout.5')}}</a>
			  </div>
			</li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
					<span> &nbsp;{{ __('layout.jp')}}</span>
				</a>
					<div class="dropdown-menu" aria-labelledby="pagesDropdown">
			    <a class="dropdown-item" href="{{route('admin.notice_list', 'jp')}}">{{ __('layout.1')}}</a>
				<a class="dropdown-item" href="{{route('admin.newsflash_list', 'jp')}}">NEWSFLASH</a>
					<a class="dropdown-item" href="{{route('admin.news_list', 'jp')}}">{{ __('layout.2')}}</a>
			    <a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'jp', 'types'=>0])}}">{{ __('layout.3')}}</a>
			    <a class="dropdown-item" href="{{route('admin.qna_list','jp')}}">{{ __('layout.4')}}</a>
					<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'jp'])}}">{{ __('layout.5')}}</a>
			  </div>
			</li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
			    <span> &nbsp;{{ __('layout.ch')}}</span>
			  </a>
			  <div class="dropdown-menu" aria-labelledby="pagesDropdown">
			    <a class="dropdown-item" href="{{route('admin.notice_list', 'ch')}}">{{ __('layout.1')}}</a>
				<a class="dropdown-item" href="{{route('admin.newsflash_list', 'ch')}}">NEWSFLASH</a>
					<a class="dropdown-item" href="{{route('admin.news_list', 'ch')}}">{{ __('layout.2')}}</a>
			    <a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'ch', 'types'=>0])}}">{{ __('layout.3')}}</a>
			    <a class="dropdown-item" href="{{route('admin.qna_list','ch')}}">{{ __('layout.4')}}</a>
					<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'ch'])}}">{{ __('layout.5')}}</a>
			  </div>
			</li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
			    <span> &nbsp;{{ __('layout.en')}}</span>
			  </a>
			  <div class="dropdown-menu" aria-labelledby="pagesDropdown">
			    <a class="dropdown-item" href="{{route('admin.notice_list', 'en')}}">{{ __('layout.1')}}</a>
				<a class="dropdown-item" href="{{route('admin.newsflash_list', 'en')}}">NEWSFLASH</a>
					<a class="dropdown-item" href="{{route('admin.news_list', 'en')}}">{{ __('layout.2')}}</a>
			    <a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'en', 'types'=>0])}}">{{ __('layout.3')}}</a>
			    <a class="dropdown-item" href="{{route('admin.qna_list','en')}}">{{ __('layout.4')}}</a>
					<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'en'])}}">{{ __('layout.5')}}</a>
			  </div>
			</li>
			<li class="nav-item dropdown">
				<li class="nav-item">
					<a class="nav-link" href="{{route('admin.n_event_winners_list')}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;{{ __('layout.n_winners_list')}}</span>
					</a>
				</li>
			</li>
			@elseif(Auth::guard('admin')->user()->level == 4)
			<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;{{ __('layout.ex')}}</span>
					</a>
					<div class="dropdown-menu" aria-labelledby="pagesDropdown">
							<a class="dropdown-item" href="{{route('admin.rights_management_list')}}">{{ __('layout.set')}}</a>
					</div>
					</li>
					<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;{{ __('layout.coiner')}}</span>
					</a>
					<div class="dropdown-menu" aria-labelledby="pagesDropdown">
						<a class="dropdown-item" href="{{route('admin.user_list')}}">{{ __('layout.userinfo')}}</a>
							<a class="dropdown-item" href="{{route('admin.account_list',5)}}">{{ __('layout.book')}}</a>
							<a class="dropdown-item" href="{{route('admin.user_balance_activity')}}">{{ __('layout.wallet')}}</a>
							<a class="dropdown-item" href="{{route('admin.user_trace_list')}}">회원 로그인 이력</a>
							<a class="dropdown-item" href="{{route('admin.user_list_new')}}">이달 신규유저 관리</a>
							<a class="dropdown-item" href="{{route('admin.user_list_now')}}">현재 접속 유저</a>
					</div>
					</li>
					<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;{{ __('layout.set6')}}</span>
					</a>
					<div class="dropdown-menu" aria-labelledby="pagesDropdown">
							<a class="dropdown-item" href="{{route('admin.trade_error')}}">거래 체결 오류 목록</a>
					</div>
					</li>
					<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;{{ __('layout.ko')}}</span>
					</a>
					<div class="dropdown-menu" aria-labelledby="pagesDropdown">
						<a class="dropdown-item" href="{{route('admin.qna_list','kr')}}">{{ __('layout.4')}}</a>
							<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'kr'])}}">{{ __('layout.5')}}</a>
					</div>
					</li>
					<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="mint_color">&nbsp;•&nbsp;</i>
							<span> &nbsp;{{ __('layout.jp')}}</span>
						</a>
							<div class="dropdown-menu" aria-labelledby="pagesDropdown">
						<a class="dropdown-item" href="{{route('admin.qna_list','jp')}}">{{ __('layout.4')}}</a>
							<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'jp'])}}">{{ __('layout.5')}}</a>
					</div>
					</li>
					<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;{{ __('layout.ch')}}</span>
					</a>
					<div class="dropdown-menu" aria-labelledby="pagesDropdown">
						<a class="dropdown-item" href="{{route('admin.qna_list','ch')}}">{{ __('layout.4')}}</a>
							<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'ch'])}}">{{ __('layout.5')}}</a>
					</div>
					</li>
					<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;{{ __('layout.en')}}</span>
					</a>
					<div class="dropdown-menu" aria-labelledby="pagesDropdown">
						<a class="dropdown-item" href="{{route('admin.qna_list','en')}}">{{ __('layout.4')}}</a>
							<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'en'])}}">{{ __('layout.5')}}</a>
					</div>
					</li>
					@if(Auth::guard('admin')->user()->id == 7 || Auth::guard('admin')->user()->id == 9 || Auth::guard('admin')->user()->id == 10
						|| Auth::guard('admin')->user()->id == 19 || Auth::guard('admin')->user()->id == 21)
					<li class="nav-item dropdown">
						<li class="nav-item">
							<a class="nav-link" href="{{route('admin.n_event_current')}}">
								<i class="mint_color">&nbsp;•&nbsp;</i>
								<span> &nbsp;{{ __('layout.n_current')}}</span>
							</a>
						</li>
					</li>
					@endif
			@elseif(Auth::guard('admin')->user()->level == 5)
			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mint_color">&nbsp;•&nbsp;</i>
				<span> &nbsp;{{ __('layout.ex')}}</span>
			</a>
			<div class="dropdown-menu" aria-labelledby="pagesDropdown">
					<a class="dropdown-item" href="{{route('admin.rights_management_list')}}">{{ __('layout.set')}}</a>
			</div>
			</li>
			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mint_color">&nbsp;•&nbsp;</i>
				<span> &nbsp;{{ __('layout.coiner')}}</span>
			</a>
			<div class="dropdown-menu" aria-labelledby="pagesDropdown">
				<a class="dropdown-item" href="{{route('admin.user_list')}}">{{ __('layout.userinfo')}}</a>
					<a class="dropdown-item" href="{{route('admin.account_list',5)}}">{{ __('layout.book')}}</a>
					<a class="dropdown-item" href="{{route('admin.user_balance_activity')}}">{{ __('layout.wallet')}}</a>
					<a class="dropdown-item" href="{{route('admin.user_trace_list')}}">회원 로그인 이력</a>
					<a class="dropdown-item" href="{{route('admin.user_list_new')}}">이달 신규유저 관리</a>
					<a class="dropdown-item" href="{{route('admin.user_list_now')}}">현재 접속 유저</a>
			</div>
			</li>

			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mint_color">&nbsp;•&nbsp;</i>
				<span> &nbsp;{{ __('layout.coin')}}</span>
			</a>
			<div class="dropdown-menu" aria-labelledby="pagesDropdown">
				<a class="dropdown-item" href="{{route('admin.coin_tr_list')}}">{{ __('layout.set5')}}</a>
			</div>
			</li>

			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mint_color">&nbsp;•&nbsp;</i>
				<span> &nbsp;{{ __('layout.set6')}}</span>
			</a>
			<div class="dropdown-menu" aria-labelledby="pagesDropdown">
					<a class="dropdown-item" href="{{route('admin.trade_history')}}">{{ __('layout.set8')}}</a>
					<a class="dropdown-item" href="{{route('admin.trade_error')}}">거래 체결 오류 목록</a>
			</div>
			</li>
			
			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mint_color">&nbsp;•&nbsp;</i>
				<span> &nbsp;{{ __('layout.ko')}}</span>
			</a>
			<div class="dropdown-menu" aria-labelledby="pagesDropdown">
				<a class="dropdown-item" href="{{route('admin.qna_list','kr')}}">{{ __('layout.4')}}</a>
					<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'kr'])}}">{{ __('layout.5')}}</a>
			</div>
			</li>
			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mint_color">&nbsp;•&nbsp;</i>
					<span> &nbsp;{{ __('layout.jp')}}</span>
				</a>
					<div class="dropdown-menu" aria-labelledby="pagesDropdown">
				<a class="dropdown-item" href="{{route('admin.qna_list','jp')}}">{{ __('layout.4')}}</a>
					<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'jp'])}}">{{ __('layout.5')}}</a>
			</div>
			</li>
			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mint_color">&nbsp;•&nbsp;</i>
				<span> &nbsp;{{ __('layout.ch')}}</span>
			</a>
			<div class="dropdown-menu" aria-labelledby="pagesDropdown">
				<a class="dropdown-item" href="{{route('admin.qna_list','ch')}}">{{ __('layout.4')}}</a>
					<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'ch'])}}">{{ __('layout.5')}}</a>
			</div>
			</li>
			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mint_color">&nbsp;•&nbsp;</i>
				<span> &nbsp;{{ __('layout.en')}}</span>
			</a>
			<div class="dropdown-menu" aria-labelledby="pagesDropdown">
				<a class="dropdown-item" href="{{route('admin.qna_list','en')}}">{{ __('layout.4')}}</a>
					<a class="dropdown-item" href="{{route('admin.notify_create',['type'=>0, 'country'=>'en'])}}">{{ __('layout.5')}}</a>
			</div>
			</li>
			@endif
		</ul>
	@endif
<div id="content-wrapper">

<div class="container-fluid">

