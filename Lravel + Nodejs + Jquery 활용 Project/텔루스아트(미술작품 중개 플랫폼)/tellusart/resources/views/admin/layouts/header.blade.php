<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
     @csrf
</form>

<nav class="navbar navbar-expand navbar-dark bg-tsa static-top">
	
      <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars org_color"></i>&nbsp;&nbsp;
      </button>

      <a class="navbar-brand mr-1 tsa_h1" href="{{ route('admin.main') }}"><img src="{{asset('storage/image/homepage/h1_admin_logo_new.png')}}" alt="텔루스아트"/></a>

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

      <!-- Navbar -->
      <ul class="navbar-nav ml-auto ml-md-0">
      	<!--
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
       -->
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user-circle fa-fw"></i>
            <span>{{Auth::guard('admin')->user()->fullname}} 관리자님 (보안등급 : {{Auth::guard('admin')->user()->level}}등급)</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
          </div>
        </li>
      </ul>

</nav>
<div id="wrapper">
 <!-- Sidebar -->
      <ul class="sidebar navbar-nav tsa-sidebar">
      	<li class="nav-item active">
          <a class="nav-link" href="/admin">
            <i class="fas fa-fw fa-home"></i>
            <span> 홈</span>
          </a>
        </li>
        @if(Auth::guard('admin')->user()->level <= 1)
        <li class="nav-item">
          <a class="nav-link" href="{{route('admin.company')}}">
            <i class="org_color">&nbsp;•&nbsp;</i>
            <span> &nbsp;사업자정보</span>
          </a>
        </li>
        @endif
        <li class="nav-item">
          <a class="nav-link" href="{{route('admin.admin_user_list')}}">
            <i class="org_color">&nbsp;•&nbsp;</i>
            <span> &nbsp;관리자 계정</span>
          </a>
        </li>
        @if(Auth::guard('admin')->user()->level <= 2)
        <li class="nav-item">
          <a class="nav-link" href="{{route('admin.batting_set')}}">
            <i class="org_color">&nbsp;•&nbsp;</i>
            <span> &nbsp;베팅설정</span>
          </a>
        </li>
        @endif
        @if(Auth::guard('admin')->user()->level <= 4)
        <li class="nav-item">
          <a class="nav-link" href="{{route('admin.user_list')}}">
            <i class="org_color">&nbsp;•&nbsp;</i>
            <span> &nbsp;회원관리</span>
          </a>
        </li>
        @endif
        @if(Auth::guard('admin')->user()->level <= 4)
        <li class="nav-item">
          <a class="nav-link" href="{{route('admin.category_list')}}">
            <i class="org_color">&nbsp;•&nbsp;</i>
            <span> &nbsp;카테고리관리</span>
          </a>
        </li>
        @endif
        @if(Auth::guard('admin')->user()->level <= 3)
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="org_color">&nbsp;•&nbsp;</i>
            <span> &nbsp;코인관리</span>
          </a>
          <div class="dropdown-menu" aria-labelledby="pagesDropdown">
            <a class="dropdown-item" href="{{route('admin.io_list',0)}}">입출금내역</a>
            @if(Auth::guard('admin')->user()->level <= 2)
              <a class="dropdown-item" href="{{route('admin.fee_list',1)}}">수수료 관리</a>
            @endif
          </div>
        </li>
        @endif
        @if(Auth::guard('admin')->user()->level <= 4)
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="org_color">&nbsp;•&nbsp;</i>
            <span> &nbsp;작품관리</span>
          </a>
          <div class="dropdown-menu" aria-labelledby="pagesDropdown">
            <a class="dropdown-item" href="{{route('admin.product_list',0)}}">판매대기</a>
            <a class="dropdown-item" href="{{route('admin.product_list',1)}}">판매중</a>
            <a class="dropdown-item" href="{{route('admin.product_list',2)}}">판매거절</a>
            <a class="dropdown-item" href="{{route('admin.product_list',3)}}">판매완료</a>
          </div>
        </li>
        @endif
        @if(Auth::guard('admin')->user()->level <= 3)
     	  <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="org_color">&nbsp;•&nbsp;</i>
            <span> &nbsp;베팅관리</span>
          </a>
          <div class="dropdown-menu" aria-labelledby="pagesDropdown">
            <a class="dropdown-item" href="{{route('admin.week_batting')}}">이번주 베팅현황</a>
            <a class="dropdown-item" href="{{route('admin.batting_product',1)}}">베팅작품</a>
            <a class="dropdown-item" href="{{route('admin.batting_list')}}">베팅내역</a>
            <a class="dropdown-item" href="{{route('admin.past_batting_list',["ca_id"=>0, "bat_cnt"=>0])}}">지난 베팅기록</a>
            <!-- <a class="dropdown-item" href="register.html">보상내역</a> -->
          </div>
        </li>
        @endif
        @if(Auth::guard('admin')->user()->level <= 4)
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="org_color">&nbsp;•&nbsp;</i>
            <span> &nbsp;주문/배송</span>
          </a>
          <div class="dropdown-menu" aria-labelledby="pagesDropdown">
            <a class="dropdown-item" href="{{route('admin.order_list')}}">주문내역</a>
            <a class="dropdown-item" href="{{route('admin.refund_list')}}">환불내역</a>
          </div>
        </li>
        @endif
        @if(Auth::guard('admin')->user()->level <= 4)
        <li class="nav-item">
          <a class="nav-link" href="{{route('admin.result_calculate',0)}}">
            <i class="org_color">&nbsp;•&nbsp;</i>
            <span> &nbsp;정산</span>
          </a>
        </li>
        @endif
        @if(Auth::guard('admin')->user()->level <= 5)
        <li class="nav-item">
          <a class="nav-link" href="{{route('admin.popup_list')}}">
            <i class="org_color">&nbsp;•&nbsp;</i>
            <span> &nbsp;팝업관리</span>
          </a>
        </li>
        @endif
        @if(Auth::guard('admin')->user()->level <= 5)
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="org_color">&nbsp;•&nbsp;</i>
            <span> &nbsp;마케팅/홍보</span>
          </a>
          <div class="dropdown-menu" aria-labelledby="pagesDropdown">
            <!--<a class="dropdown-item" href="login.html">쿠폰관리</a>-->
            <a class="dropdown-item" href="{{route('admin.event_list',0)}}">이벤트관리</a>
            <a class="dropdown-item" href="{{route('admin.banner_list')}}">배너관리</a>
            <a class="dropdown-item" href="{{route('admin.video_list')}}">홍보영상관리</a>
            <a class="dropdown-item" href="{{route('admin.slide_list')}}">슬라이드관리</a>
          </div>
        </li>
        @endif
        @if(Auth::guard('admin')->user()->level <= 5)
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="org_color">&nbsp;•&nbsp;</i>
            <span>고객센터</span>
          </a>
          <div class="dropdown-menu" aria-labelledby="pagesDropdown">
            <a class="dropdown-item" href="{{route('admin.notice_list')}}">공지사항</a>
            <a class="dropdown-item" href="{{route('admin.faq_list')}}">FAQ</a>
            <a class="dropdown-item" href="{{route('admin.privacy_edit')}}">개인정보취급방침</a>
            <a class="dropdown-item" href="{{route('admin.policy_edit')}}">서비스운영정책</a>
            <a class="dropdown-item" href="{{route('admin.howtouse_edit')}}">이용방법안내</a>
          </div>
        </li>
        @endif
      </ul>
<div id="content-wrapper">

<div class="container-fluid">

