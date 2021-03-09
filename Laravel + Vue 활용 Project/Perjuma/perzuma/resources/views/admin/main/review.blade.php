@extends('admin.layouts.header') 
@section('content')
<script>
    start=0;
    function reviewlist(){
        var email = $('#id').val();
        var password = $('#pwd').val();
        $.ajax({
            type : "GET",
            dataType: "json",
            data : {offset : start},
            url : "/admin/review/list",
            success : function(data) {
                var items = data.query;
                for(var i = 0; i< items.length;i++){
                    var layout = '<tr style=\"cursor:pointer\" onclick=\"location.href=\'/admin/review-detail?rv_no='+items[i].rv_no+'\';\">';
                    layout += '\n<td>'+(start+1)+'</td>';
                    layout += '\n<td>'+items[i].rv_title+'</td>';
                    layout += '\n<td><img src=\"/images/'+items[i].rv_point+'star.svg\"/></td>';
                    layout += '\n<td>'+items[i].ctrt_no+'</td>';
                    layout += '\n</tr>';
                    $('#reviewlist').append(layout);
                    start++;
                }
            },
            error : function(data){
            }
        });
        console.log(start);
    }
    reviewlist();

    function scrolling(){
        var scrollBottom = $(window).scrollTop() + $(window).height();
        if (scrollBottom == $(document).height()) {
            reviewlist();
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
                    <h3 class="text-themecolor">리뷰 관리</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item active">리뷰 관리</li>
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
                                <h4 class="card-title">리뷰 목록</h4>
                                <h6 class="card-subtitle">스크롤 내릴 시 <code>자동 로딩</code></h6>
                                <div class="table-responsive">
                                    <table class="table color-table info-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>제목</th>
                                                <th>별점</th>
                                                <th>계약번호</th>
                                            </tr>
                                        </thead>
                                        <tbody id="reviewlist">
                                            
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