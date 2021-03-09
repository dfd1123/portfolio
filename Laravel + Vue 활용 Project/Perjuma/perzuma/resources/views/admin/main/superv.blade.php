@extends('admin.layouts.header') 
@section('content')
<script>
    start=0;
    function supervlist(){
        $.ajax({
            type : "GET",
            dataType: "json",
            data : {offset : start},
            url : "/admin/supervison/list",
            success : function(data) {
                var items = data.query;
                for(var i = 0; i< items.length;i++){
                    var layout = '<tr style=\"cursor:pointer\" onclick=\"location.href=\'/admin/superv-detail?sp_no='+items[i].sp_no+'\';\">';
                    layout += '\n<td>'+(start+1)+'</td>';
                    layout += '\n<td>'+items[i].sp_name+'</td>';
                    layout += '\n<td>'+items[i].sp_contact+'</td>';
                    layout += '\n<td>'+items[i].reg_dt+'</td>';
                    layout += '\n<td>';
                    layout += '\n<button class=\"btn waves-effect waves-light btn-rounded btn-danger\" onclick=\"javascript:deletesuperv('+items[i].sp_no+')\">삭제</button>';
                    layout += '\n</td>';
                    layout += '</tr>';
                    $('#supervlist').append(layout);
                    start++;
                }
            },
            error : function(data){
            }
        });
    }
    supervlist();

    function scrolling(){
        var scrollBottom = $(window).scrollTop() + $(window).height();
        if (scrollBottom == $(document).height()) {
            supervlist();
        }
    }
    $(window).scroll(function() {
        scrolling();
    });
    scrolling();

    function deletesuperv(idx){
        event.stopPropagation();
        $.ajax({
            type : "DELETE",
            dataType: "json",
            data : {sp_no : idx},
            url : "/admin/supervison/def",
            success : function(data) {
                if(data.state==1 && data.query !=null){
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
                    <h3 class="text-themecolor">감리 관리</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item active">감리 관리</li>
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
                                    <h4 class="card-title">감리 목록</h4>
                                    <h6 class="card-subtitle">스크롤 내릴 시 <code>자동 로딩</code></h6>
                                </div>
                                <button class="btn waves-effect waves-light btn-rounded btn-outline-info" style="float:right" onclick="location.href='/admin/superv-regist';">등록</button>
                                <div class="table-responsive">
                                    <table class="table color-table info-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>이름</th>
                                                <th>전화번호</th>
                                                <th>등록일자</th>
                                                <th>비고</th>
                                            </tr>
                                        </thead>
                                        <tbody id="supervlist">
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