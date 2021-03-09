@extends('admin.layouts.header') 
@section('content')

<div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor">메시지 상세 정보</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item">메시지 관리</li>
                        <li class="breadcrumb-item active">메시지 상세 정보</li>
                    </ol>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-6 col-xlg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <center class="m-t-30">
                                    <h4 class="card-title m-t-10">받는 사람 : {{$query['query'][0]->user_name}}</h4>
                                </center>
                            </div>
                            <div>
                                <hr> </div>
                            <div class="card-body">
                                <small class="text-muted p-t-30 db">타입</small>
                                @if($query['query'][0]->msg_type==1)
                                    <h6>일반</h6>
                                @elseif($query['query'][0]->msg_type==2)
                                    <h6>긴급</h6>
                                @endif
                                <small class="text-muted p-t-30 db">제목</small>
                                <h6>{{$query['query'][0]->msg_title}}</h6>
                                <small class="text-muted p-t-30 db">내용</small>
                                <h6>{{$query['query'][0]->msg_content}}</h6>
                                <small class="text-muted p-t-30 db">거래 번호</small>
                                <h6>{{$query['query'][0]->trd_no}}</h6>
                                <small class="text-muted p-t-30 db">발신일</small>
                                <h6>{{$query['query'][0]->send_dt}}</h6>
                                <small class="text-muted p-t-30 db">확인일</small>
                                <h6>{{$query['query'][0]->read_dt}}</h6>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    
                </div>
                <!-- Row -->
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer">
                © 2019 Admin Press Admin by themedesigner.in
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
@include('admin.layouts.footer') 
@endsection