@extends('admin.layouts.header') 
@section('content')
<script>
var notice_no = findGetParameter('notice_no');
$(function(){
    $('#updatebtn').click(function(){
        var param = {
            'notice_no' : notice_no,
            'notice_title' : $('#notice_title').val(),
            'notice_content' : $('#notice_content').val()
        };
        $.ajax({
            type : "PUT",
            dataType: "json",
            data : param,
            url : "/admin/notice/notice",
            success : function(data) {
                console.log(data);
                if(data.state==1 && data.query!=null){
                    alert('수정되었습니다');
                    location.reload();
                }
                else{
                    alert(data.msg);
                }
            },
            error : function(data){
            }
        });
    });
});
</script>
<div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor">공지사항 등록</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item">공지사항</li>
                        <li class="breadcrumb-item active">공지사항 등록</li>
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
                    <div class="col-lg-12">
                        <div class="card" style="padding-top:1em;">
                                <div class="form-group">
                                    <label class="col-md-12">제목</label>
                                    <div class="col-md-12">
                                        <input id="notice_title" type="text" placeholder="ex) 공지사항 제목입니다" class="form-control form-control-line" value="{{$query['query'][0]->notice_title}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">내용</label>
                                    <div class="col-md-12">
                                        <textarea id="notice_content" rows="5" class="form-control form-control-line">{{$query['query'][0]->notice_content}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button id="updatebtn" class="btn btn-success">수정</button>
                                    </div>
                                </div>
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