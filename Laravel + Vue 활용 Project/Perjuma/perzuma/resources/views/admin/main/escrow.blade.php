@extends('admin.layouts.header') 
@section('content')
<script>
    start=0;
    function escrowlist(){
        $.ajax({
            type : "GET",
            dataType: "json",
            data : {offset : start},
            url : "/admin/escrow/list",
            success : function(data) {
                var items = data.query;
                for(var i = 0; i< items.length;i++){
                    var layout = '<tr style=\"cursor:pointer\" onclick=\"location.href=\'/admin/escrow-detail?ecr_no='+items[i].ecr_no+'\';\">';
                    layout += '\n<td>'+items[i].ctrt_no+'</td>';
                    layout += '\n<td>'+items[i].ecr_extra_info+'</td>';
                    layout += '\n<td>'+items[i].client_no+'</td>';
                    layout += '\n<td>'+items[i].agent_no+'</td>';
                    layout += '\n<td>'+items[i].state+'</td>';
                    layout += '</tr>';
                    $('#escrowlist').append(layout);
                    start++;
                }
            },
            error : function(data){
            }
        });
    }
    escrowlist();

    function scrolling(){
        var scrollBottom = $(window).scrollTop() + $(window).height();
        if (scrollBottom == $(document).height()) {
            escrowlist();
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
                    <h3 class="text-themecolor">Escrow</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item active">Escrow</li>
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
                                <h4 class="card-title">Escrow 목록</h4>
                                <h6 class="card-subtitle">스크롤 내릴 시 <code>자동 로딩</code></h6>
                                <div class="table-responsive">
                                    <table class="table color-table info-table">
                                        <thead>
                                            <tr>
                                                <th>거래 번호</th>
                                                <th>검증키</th>
                                                <th>보내는사람</th>
                                                <th>받는사람</th>
                                                <th>상태</th>
                                            </tr>
                                        </thead>
                                        <tbody id="escrowlist">
                                            
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