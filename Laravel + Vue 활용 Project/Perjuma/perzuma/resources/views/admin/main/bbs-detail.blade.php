@extends('admin.layouts.header') 
@section('content')
<script>
var bbs_no = findGetParameter('bbs_no');
$(function(){
    $('#replybtn').click(function(){
        var param = {
            'bbs_no' : bbs_no,
            'ans' : $('#bbs_reply').val()
        };
        $.ajax({
            type : "PUT",
            dataType: "json",
            data : param,
            url : "/admin/bbs/answer",
            success : function(data) {
                console.log(data);
                if(data.state==1 && data.query!=null){
                    alert('답글 완료');
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
                    <h3 class="text-themecolor">문의 상세 정보</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item">유저 문의 관리</li>
                        <li class="breadcrumb-item active">문의 상세 정보</li>
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
                                    <h4 class="card-title m-t-10">{{$query['query'][0]->user_name}}</h4>
                                </center>
                            </div>
                            <div>
                                <hr> </div>
                            <div class="card-body">
                                <small class="text-muted p-t-30 db">제목</small>
                                <h6>{{$query['query'][0]->title}}</h6>
                                <small class="text-muted p-t-30 db">내용</small>
                                <h6>{{$query['query'][0]->content}}</h6>
                                <small class="text-muted p-t-30 db">거래 번호</small>
                                <h6>{{$query['query'][0]->trade_no}}</h6>
                                <small class="text-muted p-t-30 db">발신일</small>
                                <h6>{{$query['query'][0]->reg_dt}}</h6>
                                <!-- small class="text-muted p-t-30 db">문의 상태</small>
                                <h6>{{$query['query'][0]->state}}</h6> -->
                                <small class="text-muted p-t-30 db">답변일</small>
                                <h6>{{$query['query'][0]->ans_dt}}</h6>
                                <small class="text-muted p-t-30 db">답변</small>
                                <textarea id="bbs_reply" rows="5" class="form-control form-control-line" placeholder="답변을 적어주세요">{{$query['query'][0]->ans}}</textarea>
                                <button id="replybtn" class="btn waves-effect waves-light btn-rounded btn-info" style="margin-top:1em">답변 보내기</button>
                                
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