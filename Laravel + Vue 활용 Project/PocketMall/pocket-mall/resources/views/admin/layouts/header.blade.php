<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
  <a class="navbar-brand" href="{{route('admin.settings')}}">Pocket Mall</a><button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button
  >
  <ul class="navbar-nav ml-auto ml-md-0">
      <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
              <a class="dropdown-item" href="{{route('admin.logout')}}">Logout</a>
          </div>
      </li>
  </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">설정</div>
                    <a class="nav-link" href="{{route('admin.settings')}}"
                        ><div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        회사 정보 수정</a
                    >
                    <div class="sb-sidenav-menu-heading">카테고리</div>
                    <a class="nav-link" href="{{route('admin.category')}}"
                        ><div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        카테고리 관리</a
                    >
                    <div class="sb-sidenav-menu-heading">상품</div>
                    <a class="nav-link" href="{{route('admin.items')}}"
                        ><div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        상품 관리</a
                    >
                    <a class="nav-link" href="{{route('admin.item_options')}}"
                        ><div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        상품 옵션 관리</a
                    >
                    <div class="sb-sidenav-menu-heading">견적서</div>
                    <a class="nav-link" href="{{route('admin.invoices')}}"
                        ><div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        견적서 요청</a
                    >
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>
                {{Auth::guard('admin')->user()->company_name}}
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>