@extends('admin.layouts.header') 
@section('content')
<script>
$(function(){
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
    $('#bl_regist').click(function(){
        $('#registform').ajaxForm({
            type : "POST",
            dataType: "json",
            url : "/admin/bllist",
            processData : false,
			contentType : false,
            success : function(data) {
                console.log(data);
                if(data.state==1 && data.query!=null){
                    alert('등록되었습니다');
                    location.reload();
                }
                else{
                    alert(data.msg);
                }
            },
            error : function(data){
                console.log(data);
            },
            beforeSubmit : function(){
                if($('#bl_thumb').val()=='' || $('#bl_name').val()==''){
                    alert('제목, 사진 필수');
                    return false;
                }
            }
        });
        //$('#registform').submit();
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
                            <form id="registform" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="col-md-12">업종 이름</label>
                                    <div class="col-md-12">
                                        <input id="bl_name" name="bl_name" type="text" placeholder="ex) 한식" class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">업종사진</label>
                                    <div class="col-md-12">
                                        <input id="bl_thumb" type="file" name="bl_thumb" accept="image/*"
                                         value="icon_01.svg">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">미리보기</label>
                                    <div class="col-md-12" id="brow">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <input style="display:none" name="state" value="0"/>
                                        <button id="bl_regist" class="btn btn-success">등록</button>
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