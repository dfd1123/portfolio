@extends('admin.layouts.header') 
@section('content')
<script>
    start=0;
    function loglist(){
        $.ajax({
            type : "GET",
            dataType: "json",
            data : {offset : start},
            url : "/admin/log/list",
            success : function(data) {
                var items = data.query;
                for(var i = 0; i< items.length;i++){
                    var layout = '\n<td>'+(start+1)+'</td>';
                    layout += '\n<td>'+items[i].user_no+'</td>';
                    layout += '\n<td>'+items[i].log_type+'</td>';
                    layout += '\n<td>'+items[i].log_msg+'</td>';
                    layout += '\n<td>'+items[i].reg_dt+'</td>';
                    layout += '</tr>';
                    $('#loglist').append(layout);
                    start++;
                }
            },
            error : function(data){
            }
        });
    }
    loglist();

    function scrolling(){
        var scrollBottom = $(window).scrollTop() + $(window).height();
        if (scrollBottom == $(document).height()) {
            loglist();
        }
    }
    $(window).scroll(function() {
        scrolling();
    });
</script>
<div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor">Log 확인</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item active">Log 확인</li>
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
                                <h4 class="card-title">Log 목록</h4>
                                <h6 class="card-subtitle">스크롤 내릴 시 <code>자동 로딩</code></h6>
                                <div class="table-responsive">
                                    <table class="table color-table info-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>유저</th>
                                                <th>로그 타입</th>
                                                <th>로그 메세지</th>
                                                <th>발생일</th>
                                            </tr>
                                        </thead>
                                        <tbody id="loglist">
                                            
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