@extends('admin.layouts.header') 
@section('content')
<script>
var user_no = findGetParameter('user_no');
    start=0;
    function bbslist(){
        $.ajax({
            type : "GET",
            dataType: "json",
            data : {offset : start},
            url : "/admin/bbs/list",
            success : function(data) {
                var items = data.query;
                for(var i = 0; i< items.length;i++){
                    var layout = '<tr style=\"cursor:pointer\" onclick=\"location.href=\'/admin/bbs-detail?bbs_no='+items[i].bbs_no+'\';\">';
                    layout += '\n<td>'+(start+1)+'</td>';
                    layout += '\n<td>'+items[i].title+'</td>';
                    layout += '\n<td>'+items[i].content+'</td>';
                    layout += '\n<td>'+items[i].reg_dt+'</td>';
                    if(items[i].ans!=null){
                        layout += '\n<td>O</td>';
                    }
                    else{
                        layout += '\n<td>X</td>';
                    }
                    layout += '</tr>';
                    $('#bbslist').append(layout);
                    start++;
                }
            },
            error : function(data){
            }
        });
    }
    function userbbslist(){
        $.ajax({
            type : "GET",
            dataType: "json",
            data : {offset : start, user_no : user_no},
            url : "/admin/bbs/userlist",
            success : function(data) {
                var items = data.query;
                for(var i = 0; i< items.length;i++){
                    var layout = '<tr style=\"cursor:pointer\" onclick=\"location.href=\'/admin/bbs-detail?bbs_no='+items[i].bbs_no+'\';\">';
                    layout += '\n<td>'+(start+1)+'</td>';
                    layout += '\n<td>'+items[i].title+'</td>';
                    layout += '\n<td>'+items[i].content+'</td>';
                    layout += '\n<td>'+items[i].name+'</td>';
                    layout += '\n<td>'+items[i].reg_dt+'</td>';
                    if(items[i].ans!=null){
                        layout += '\n<td>O</td>';
                    }
                    else{
                        layout += '\n<td>X</td>';
                    }
                    layout += '</tr>';
                    $('#bbslist').append(layout);
                    start++;
                }
            },
            error : function(data){
            }
        });
    }
    if(user_no != null){
        userbbslist();
    }
    else{
        bbslist();
    }
    

    function scrolling(){
        var scrollBottom = $(window).scrollTop() + $(window).height();
        if (scrollBottom == $(document).height()) {
            if(user_no != null){
                userbbslist();
            }
            else{
                bbslist();
            }
        }
    }
    $(window).scroll(function() {
        scrolling();
    });
    scrolling();
</script>
<div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor">유저 문의 관리</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item active">유저 문의 관리</li>
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
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">문의 목록</h4>
                                <h6 class="card-subtitle">스크롤 내릴 시 <code>자동 로딩</code></h6>
                                <div class="table-responsive">
                                    <table class="table color-table info-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>제목</th>
                                                <th>내용</th>
                                                <th>받는유저</th>
                                                <th>등록일자</th>
                                                <th>답변여부</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bbslist">
                                            
                                        </tbody>
                                    </table>
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