@extends('admin.layouts.header') 
@section('content')
<script>
var bl_no = findGetParameter('bl_no');
$.ajax({
    type : "GET",
    dataType: "json",
    data : {'bl_no' : bl_no},
    url : "/admin/bllist/detail",
    success : function(data) {
        console.log(data);
        if(data.state==1 && data.query!=null){
            $('#bl_name').val(data.query[0].bl_name);
        }
        else{
            alert(data.msg);
        }
    },
    error : function(data){
    }
});
$(function(){
    $('#bl_no').val(bl_no);
    var sel_files = [];
    $('#bl_thumb').on("change", handleImgFileSelect1);

    function handleImgFileSelect1(e) {
        var files = e.target.files;
        var filesArr = Array.prototype.slice.call(files);
        var x = document.getElementById('bl_thumb');

        $('#brow').empty();
        filesArr.forEach(function (f) {
            sel_files.push(f);

            var reader = new FileReader();
            reader.onload = function (e) {
                var img_html = "<img style=\"width:300px;height:300px;margin:10px 0px\" src = \"" + e.target.result + "\" />";
                $('#brow').append(img_html);
            }
            reader.readAsDataURL(f);
        });
    }
    $('#udt-btn').click(function(){
        var param = {
            'bl_no' : bl_no,
            'bl_name' : $('#bl_name').val()
        };
        $.ajax({
            type : "PUT",
            dataType: "json",
            data : param,
            url : "/admin/bllist/def",
            success : function(data) {
                console.log(data);
                if(data.state==1 && data.query!=null){
                    alert('수정 되었습니다');
                    location.href='/admin/bl';
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
                    <h3 class="text-themecolor">업종 등록</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item active">업종 등록</li>
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
                                    <label class="col-md-12">업종 이름</label>
                                    <div class="col-md-12">
                                        <input id="bl_name" name="bl_name" type="text"  placeholder="ex) 한식" class="form-control form-control-line"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button id="udt-btn" class="btn btn-success">수정</button>
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