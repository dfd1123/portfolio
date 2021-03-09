<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!--<link rel="stylesheet" href="{{asset('/css/fonts/spoqahans.css">-->

    <link href="//spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-kr.css" rel="stylesheet" type="text/css">
    <link href="//spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-jp.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core CSS -->
    <link href="/adminassets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/admin_ver/icons/themify-icons/themify-icons.css" rel="stylesheet">
    <!-- Morries chart CSS -->
    <link href="/adminassets/plugins/morrisjs/morris.css" rel="stylesheet">
    <script src="/js/vendor/jquery-3.4.1.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js" type="text/javascript"></script> 
    <!-- Custom CSS -->
    <link href="/css/admin_ver/common.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="/css/admin_ver/colors/blue.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <script src="/js/admin_ver/common.js"></script>
</head>

<body class="fix-header fix-sidebar card-no-border">


    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.html">
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="/adminassets/images/logo-icon.png" alt="homepage" class="dark-logo" />
                            <!-- Light Logo icon -->
                            <img src="/adminassets/images/logo-light-icon.png" alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text --><span>
                            <!-- dark Logo text -->
                            <img src="/adminassets/images/logo-text.png" alt="homepage" class="dark-logo" />
                            <!-- Light Logo text -->
                            <img src="/adminassets/images/logo-light-text.png" class="light-logo"
                                alt="homepage" /></span> </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a
                                class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark"
                                href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item m-l-10"> <a
                                class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark"
                                href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-small-cap">PERSONAL</li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="/admin/users" aria-expanded="false">
                            <i class="mdi mdi-gauge"></i><span class="hide-menu">사용자 관리</span></a>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="/admin/agents" aria-expanded="false">
                            <i class="mdi mdi-bullseye"></i><span class="hide-menu">업체 관리</span></a>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="/admin/superv" aria-expanded="false">
                            <i class="mdi mdi-email"></i><span class="hide-menu">감리 관리</span></a>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="/admin/manager" aria-expanded="false">
                            <i class="mdi mdi-chart-bubble"></i><span class="hide-menu">매니저 관리</span></a>
                        </li>

                        <li class="nav-small-cap">PROCESS</li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="/admin/trades" aria-expanded="false">
                            <i class="mdi mdi-file"></i><span class="hide-menu">거래 관리</span></a>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="/admin/escrow" aria-expanded="false">
                            <i class="mdi mdi-table"></i><span class="hide-menu">Escrow</span></a>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="/admin/review" aria-expanded="false">
                            <i class="mdi mdi-book-multiple"></i><span class="hide-menu">리뷰 관리</span></a>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="/admin/bbs" aria-expanded="false">
                            <i class="mdi mdi-widgets"></i><span class="hide-menu">유저 문의 관리</span></a>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="/admin/msg" aria-expanded="false">
                            <i class="mdi mdi-book-multiple"></i><span class="hide-menu">메시지 관리</span></a>
                        </li>

                        <li class="nav-small-cap">SYSTEM</li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="/admin/bl" aria-expanded="false">
                            <i class="mdi mdi-file-chart"></i><span class="hide-menu">업종</span></a>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="/admin/notice" aria-expanded="false">
                            <i class="mdi mdi-brush"></i><span class="hide-menu">공지사항</span></a>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="/admin/logs" aria-expanded="false">
                            <i class="mdi mdi-map-marker"></i><span class="hide-menu">Log 확인</span></a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->

    @yield('content')