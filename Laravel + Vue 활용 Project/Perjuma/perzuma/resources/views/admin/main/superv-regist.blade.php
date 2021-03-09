@extends('admin.layouts.header') 
@section('content')
<script>
$(function(){
    $('#regist_btn').click(function(){
        var param = {
            'sp_name' : $('#sp_name').val(),
            'sp_contact' : $('#sp_contact').val()
        }
        $.ajax({
            type : "POST",
            dataType: "json",
            data : param,
            url : "/admin/supervison",
            success : function(data) {
                if(data.state==1 && data.query !=null){
                    alert('등록되었습니다!');
                    location.href='/admin/superv';
                }
                else{
                    alert(data.msg);
                }
            },
            error : function(data){
                alert(data.msg);
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
                    <h3 class="text-themecolor">감리 등록</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item active">감리 등록</li>
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
                                    <label class="col-md-12">감리 이름</label>
                                    <div class="col-md-12">
                                        <input id="sp_name" type="text" placeholder="Johnathan Doe" class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">전화번호</label>
                                    <div class="col-md-12">
                                        <input id="sp_contact" type="text" placeholder="123 456 7890" class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <input style="display:none" name="state" value="0"/>
                                        <button id="regist_btn" class="btn btn-success">등록</button>
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