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

    <!--<link rel="stylesheet" href="{{asset('/css/fonts/spoqahans.css')}}>-->

    <link href="//spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-kr.css" rel="stylesheet" type="text/css">
    <link href="//spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-jp.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core CSS -->
    <link href="/adminassets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/admin_ver/icons/themify-icons/themify-icons.css" rel="stylesheet">
    <!-- Morries chart CSS -->
    <link href="/adminassets/plugins/morrisjs/morris.css" rel="stylesheet">
    <script src="/js/vendor/jquery-3.4.1.min.js"></script>
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

</head>
<body>
    <div class="wrap">
        <div class="bg">
            <div class="main">
                <div class="outer-div">
                    <div class="emptyform"></div>
                    <div class="inner-div">
                        <div class="loginform">
                            <img src="/images/perzuman_round_02.svg" style="margin-right:2em;"/>
                            <form method="POST" action="{{route('admin.login_attemp')}}">
                                @csrf
                                <div class="mg">
                                    <input class="form-control" type="text" name="email" placeholder="아이디">
                                </div>
                                <div class="mg">
                                    <input class="form-control" type="password" name="password" placeholder="비밀번호">
                                </div>
                                <div class="mg">
                                    <button class="btn waves-effect waves-light btn-block btn-info" type="submit">로그인</button>
                                </div>
                            </form>
                        </div>
                        <p class="copy">© PURZUMA</p>
                    </div>
                    <div class="emptyform"></div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .wrap{
            height:100%;
        }
        .bg{
            display: flex;
            min-height: 100vh;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            flex-wrap: wrap;
            padding: 60px 15px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .main{
            width: 100%;
            max-width: 920px;
        }
        .outer-div{
            margin-right: 0;
            margin-left: 0;
            display: flex;
            flex-wrap: wrap;
        }
        .inner-div{
            padding-right: 0;
            padding-left: 0;
            flex: 0 0 50%;
            max-width: 50%;
            position: relative;
            width: 100%;
            min-height: 1px;
        }
        .emptyform{
            padding-right: 0;
            padding-left: 0;
            flex: 0 0 25%;
            max-width: 25%;
            position: relative;
            width: 100%;
            min-height: 1px;
        }
        .loginform{
            display: flex;
            min-height: 100%;
            padding: 60px 25px;
            color: #ccc;
            background-color: #1b2223;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
        }
        .mg{
            margin:1em 0;
        }
        .copy{
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</body>
@include('admin.layouts.footer') 
