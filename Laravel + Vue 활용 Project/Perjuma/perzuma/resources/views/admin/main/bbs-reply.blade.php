@extends('admin.layouts.header') 
@section('content')

<div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor">문의 답변</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item">유저 문의 관리</li>
                        <li class="breadcrumb-item active">문의 답변 수정</li>
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
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-4 col-xlg-3 col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <center class="m-t-30">
                                    <h4 class="card-title m-t-10">문의 보낸 유저</h4>
                                </center>
                            </div>
                            <div>
                                <hr> </div>
                            <div class="card-body">
                                <small class="text-muted p-t-30 db">보낸 날짜</small>
                                <h6>2019.7.6</h6>
                                <small class="text-muted p-t-30 db">거래 번호</small>
                                <h6>16</h6>
                                <small class="text-muted p-t-30 db">답변 여부</small>
                                <h6>X</h6>
                                <small class="text-muted p-t-30 db">답변 날짜</small>
                                <h6>2019.7.7</h6>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-8">
                        <div class="card" style="padding-top:1em;">
                            <form class="form-horizontal form-material">
                                <div class="form-group">
                                    <label class="col-md-12" style="color:#99abb4;font-size:0.5em;">문의 제목</label>
                                    <div class="col-md-12">
                                        문의 제목 테스트
                                    </div>
                                    <label class="col-md-12" style="color:#99abb4;font-size:0.5em;">문의 내용</label>
                                    <div class="col-md-12">
                                        문의 내용 테스트
                                    </div>
                                </div>
                                <div><hr></div>
                                <div class="form-group">
                                    <label class="col-md-12">답변 내용</label>
                                    <div class="col-md-12">
                                        <textarea rows="5" class="form-control form-control-line"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <input style="display:none" name="state" value="0"/>
                                        <button class="btn btn-success">보내기</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
                © 2019 Admin Press Admin by themedesigner.in1234
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>

@include('admin.layouts.footer') 
@endsection