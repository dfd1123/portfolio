<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
     @csrf
</form>

<nav class="navbar navbar-expand navbar-dark bg-tsa static-top">

      <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars mint_color"></i>&nbsp;&nbsp;
      </button>

      <a class="navbar-brand mr-1 tsa_h1" href="{{route('admin.main')}}">월렛 관리시스템</a>

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
			<li class="nav-item">
					<a class="nav-link" href="{{route('admin.company_info')}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;{{ __('layout.info')}}</span>
					</a>
			</li>
			<li class="nav-item">
					<a class="nav-link" href="{{route('admin.ico_edit')}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;ICO 설정</span>
					</a>
			</li>
			<li class="nav-item">
					<a class="nav-link" href="{{route('admin.ico_qna_list')}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;ICO 문의</span>
					</a>
			</li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
			    <span> &nbsp;{{ __('layout.coiner')}}</span>
			  </a>
			  <div class="dropdown-menu" aria-labelledby="pagesDropdown">
					<a class="dropdown-item" href="{{route('admin.user_list')}}">{{ __('layout.userinfo')}}</a>
					<a class="dropdown-item" href="{{route('admin.document_list',5)}}">{{ __('layout.idcard')}}</a>
					<a class="dropdown-item" href="{{route('admin.account_list',5)}}">{{ __('layout.book')}}</a>
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
			    <span> &nbsp;{{ __('layout.revenue_manage')}}</span>
			  </a>
			  <div class="dropdown-menu" aria-labelledby="pagesDropdown">
			    <a class="dropdown-item" href="{{route('admin.users_revenue_manage')}}">{{ __('layout.user_revenue_manage')}}</a>
					<a class="dropdown-item" href="{{route('admin.monthly_revenue_edit')}}">{{ __('layout.revenue_update')}}</a>
			  </div>
			</li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
			    <span> &nbsp;고객센터(한국)</span>
			  </a>
			  <div class="dropdown-menu" aria-labelledby="pagesDropdown">
			    <a class="dropdown-item" href="{{route('admin.notice_list', 'kr')}}">{{ __('layout.1')}}</a>
			    <a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'kr', 'types'=>0])}}">{{ __('layout.3')}}</a>
			    <a class="dropdown-item" href="{{route('admin.qna_list','kr')}}">{{ __('layout.4')}}</a>
			  </div>
			</li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
			    <span> &nbsp;고객센터(영문)</span>
			  </a>
			  <div class="dropdown-menu" aria-labelledby="pagesDropdown">
			    <a class="dropdown-item" href="{{route('admin.notice_list', 'en')}}">{{ __('layout.1')}}</a>
			    <a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'en', 'types'=>0])}}">{{ __('layout.3')}}</a>
			    <a class="dropdown-item" href="{{route('admin.qna_list','en')}}">{{ __('layout.4')}}</a>
			  </div>
			</li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
			    <span> &nbsp;고객센터(스페인)</span>
			  </a>
			  <div class="dropdown-menu" aria-labelledby="pagesDropdown">
			    <a class="dropdown-item" href="{{route('admin.notice_list', 'spain')}}">{{ __('layout.1')}}</a>
			    <a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'spain', 'types'=>0])}}">{{ __('layout.3')}}</a>
			    <a class="dropdown-item" href="{{route('admin.qna_list','spain')}}">{{ __('layout.4')}}</a>
			  </div>
			</li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
			    <span> &nbsp;고객센터(일본)</span>
			  </a>
			  <div class="dropdown-menu" aria-labelledby="pagesDropdown">
			    <a class="dropdown-item" href="{{route('admin.notice_list', 'jp')}}">{{ __('layout.1')}}</a>
			    <a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'jp', 'types'=>0])}}">{{ __('layout.3')}}</a>
			    <a class="dropdown-item" href="{{route('admin.qna_list','jp')}}">{{ __('layout.4')}}</a>
			  </div>
			</li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="mint_color">&nbsp;•&nbsp;</i>
			    <span> &nbsp;고객센터(중국)</span>
			  </a>
			  <div class="dropdown-menu" aria-labelledby="pagesDropdown">
			    <a class="dropdown-item" href="{{route('admin.notice_list', 'ch')}}">{{ __('layout.1')}}</a>
			    <a class="dropdown-item" href="{{route('admin.faq_list', ['country'=>'ch', 'types'=>0])}}">{{ __('layout.3')}}</a>
			    <a class="dropdown-item" href="{{route('admin.qna_list','ch')}}">{{ __('layout.4')}}</a>
			  </div>
			</li>
			<li class="nav-item">
					<a class="nav-link" href="{{route('admin.term_service', 'kr')}}">
						<i class="mint_color">&nbsp;•&nbsp;</i>
						<span> &nbsp;{{ __('layout.term_manage')}}</span>
					</a>
			</li>
		</ul>
	@endif
<div id="content-wrapper">

<div class="container-fluid">

