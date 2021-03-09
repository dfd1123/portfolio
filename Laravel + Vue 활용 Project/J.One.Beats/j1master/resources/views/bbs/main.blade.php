@extends('header')
@section('content')
<div class="box box-inbox full-width full-height">
    <div class="box-header with-border">
        <div class="box-title">
            <h2>비트</h2>
            <span style="">47</span>
        </div>
        <div class="box-tools pull-right">
            <i class="zmdi zmdi-more-vert waves-effect waves-teal"></i>
            <ul class="dropdown-menu">
                <li>
                    <a href="#" class="waves-effect" title="">Action</a>
                </li>
                <li>
                    <a href="#" class="waves-effect" title="">Support</a>
                </li>
                <li>
                    <a href="#" class="waves-effect" title="">FAQ</a>
                </li>
                <li>
                    <a href="#" class="waves-effect" title="">Message</a>
                </li>
            </ul>
        </div><!-- /.box-tools pull-right -->
        <div class="clearfix"></div>
    </div><!-- /.box-header -->
    <div class="box-content">
    <ul id="inbox-list" class="inbox-list">
            <li class="waves-effect" onclick ='location.href= /beat/detail' >
                <a href="#" title="">
                    <div class="left">
                        <div class="info">
                            <p class="name">닉네임</p>
                            <p>제목</p>
                        </div>
                    </div>
                    <div class="right">
                        등록일
                    </div>
                    <div class="clearfix"></div>
                </a>
            </li>
        </ul><!-- /.inbox-list --> <ul id="inbox-list" class="inbox-list">
            <li class="waves-effect">
                <a href="#" title="">
                    <div class="left">
                        <div class="info">
                            <p class="name">닉네임</p>
                            <p>제목</p>
                        </div>
                    </div>
                    <div class="right">
                        등록일
                    </div>
                    <div class="clearfix"></div>
                </a>
            </li>
        </ul><!-- /.inbox-list -->
    </div><!-- /.box-content -->
</div>
@endsection