@extends('admin.layouts.header') 
@section('content')
<script>
var rv_no = findGetParameter('rv_no');
$(function(){
    $('#rv_delete').click(function(){
        if($('#delete_type').val()== -1){
            alert('삭제 사유를 선택하세요');
            return;
        }
        var param = {
            'rv_no' : rv_no
        };
        $.ajax({
            type : "PUT",
            dataType: "json",
            data : param,
            url : "/admin/review/statedelete",
            success : function(data) {
                console.log(data);
                if(data.state==1 && data.query!=null){
                    alert('삭제 처리 되었습니다');
                    location.href='/admin/review';
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
                    <h3 class="text-themecolor">리뷰 상세 페이지</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item">리뷰 관리</li>
                        <li class="breadcrumb-item active">리뷰 상세 페이지</li>
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
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <center class="m-t-30">
                                    <h4 class="card-title m-t-10">작성자 : {{$query['query'][0]->client_name}}</h4>
                                </center>
                            </div>
                            <div>
                                <hr> </div>
                            <div class="card-body">
                                <small class="text-muted p-t-30 db">업체</small>
                                <h6>{{$query['query'][0]->agent_name}}</h6>
                                <small class="text-muted p-t-30 db">제목</small>
                                <h6>{{$query['query'][0]->rv_title}}</h6>
                                <small class="text-muted p-t-30 db">내용</small>
                                <h6>{{$query['query'][0]->rv_content}}</h6>
                                <small class="text-muted p-t-30 db">리뷰 이미지</small>
                                <h6><img src="{{$query['query'][0]->rv_imgs}}" alt="리뷰 이미지"/></h6>
                                <small class="text-muted p-t-30 db">별점</small>
                                <h6><img src="/images/{{$query['query'][0]->rv_point}}star.svg"/></h6>
                                <small class="text-muted p-t-30 db">계약번호</small>
                                <h6>{{$query['query'][0]->ctrt_no}}</h6>
                                <small class="text-muted p-t-30 db">등록일</small>
                                <h6>{{$query['query'][0]->reg_dt}}</h6>
                                <small class="text-muted p-t-30 db">리뷰 삭제</small>
                                <select id="delete_type">
                                    <option value="-1">사유 선택</option>
                                    <option value="0">욕설</option>
                                    <option value="1">음란글</option>
                                    <option value="2">비적절한 리뷰</option>
                                </select>
                                <button id="rv_delete" class="btn waves-effect waves-light btn-rounded btn-info">삭제</button>
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