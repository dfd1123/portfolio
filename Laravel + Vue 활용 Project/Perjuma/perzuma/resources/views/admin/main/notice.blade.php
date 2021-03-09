@extends('admin.layouts.header') 
@section('content')
<script>
    start=0;
    function noticelist(){
        var email = $('#id').val();
        var password = $('#pwd').val();
        $.ajax({
            type : "GET",
            dataType: "json",
            data : {offset : start},
            url : "/admin/notice/list",
            success : function(data) {
                var items = data.query;
                for(var i = 0; i< items.length;i++){
                    var layout = '<tr>';
                    layout += '\n<td>'+(start+1)+'</td>';
                    layout += '\n<td>'+items[i].notice_title+'</td>';
                    layout += '\n<td>'+items[i].notice_content+'</td>';
                    layout += '\n<td>'+items[i].reg_dt+'</td>';
                    layout += '\n<td>';
                    layout += '\n<button class=\"btn btn-rounded btn-block btn-outline-info\" style=\"width:40%;display:inline-block;\"onclick=\"location.href=\'/admin/notice-update?notice_no='+items[i].notice_no+'\';\">수정</button>';
                    layout += '\n<button class=\"btn btn-rounded btn-block btn-danger\" onclick=\"javascript:noticedelete('+items[i].notice_no+')\" style=\"width:40%;display:inline-block;margin-top:0;\">삭제</button>';
                    layout += '\n</td>';
                    layout += '\n</tr>';
                    $('#noticelist').append(layout);
                    start++;
                }
            },
            error : function(data){
            }
        });
        console.log(start);
    }
    noticelist();

    function scrolling(){
        var scrollBottom = $(window).scrollTop() + $(window).height();
        if (scrollBottom == $(document).height()) {
            noticelist();
        }
    }
    $(window).scroll(function() {
        scrolling();
        console.log(start);
    });
    scrolling();
    function noticedelete(idx){
            $.ajax({
                type : "DELETE",
                dataType: "json",
                data : {notice_no : idx},
                url : "/admin/notice/def",
                success : function(data) {
                    console.log(data);
                    if(data.query !=null && data.state==1){
                        alert('삭제되었습니다');
                        location.reload();
                    }
                },
                error : function(data){
                }
            });
        }
    
</script>
<div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor">공지사항</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item active">공지사항</li>
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
                                <div style="width:50%;display:inline-block;">
                                    <h4 class="card-title">공지사항 목록</h4>
                                    <h6 class="card-subtitle">스크롤 내릴 시 <code>자동 로딩</code></h6>
                                </div>
                                <button class="btn waves-effect waves-light btn-rounded btn-outline-info" style="float:right" onclick="location.href='/admin/notice-regist';">등록</button>
                                <div class="table-responsive">
                                    <table class="table color-table info-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>제목</th>
                                                <th>내용</th>
                                                <th>등록일자</th>
                                                <th>비고</th>
                                            </tr>
                                        </thead>
                                        <tbody id="noticelist">
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