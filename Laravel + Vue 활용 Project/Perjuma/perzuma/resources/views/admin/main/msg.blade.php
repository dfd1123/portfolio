@extends('admin.layouts.header') 
@section('content')
<script>
    start=0;
    function msglist(){
        var email = $('#id').val();
        var password = $('#pwd').val();
        $.ajax({
            type : "GET",
            dataType: "json",
            data : {offset : start},
            url : "/admin/message/list",
            success : function(data) {
                var items = data.query;
                var state = '';
                for(var i = 0; i< items.length;i++){
                    if(items[i].msg_type==1){state='일반';}
                    else if(items[i].msg_type==2){state='긴급'}

                    var layout = '<tr style=\"cursor:pointer\" onclick=\"location.href=\'/admin/msg-detail?msg_no='+items[i].msg_no+'\';\">';
                    layout += '\n<td>'+(start+1)+'</td>';
                    layout += '\n<td>'+state+'</td>';
                    layout += '\n<td>'+items[i].msg_content+'</td>';
                    layout += '\n<td>'+items[i].send_dt+'</td>';
                    layout += '\n</tr>';
                    $('#msglist').append(layout);
                    start++;
                }
            },
            error : function(data){
            }
        });
        console.log(start);
    }
    msglist();

    function scrolling(){
        var scrollBottom = $(window).scrollTop() + $(window).height();
        if (scrollBottom == $(document).height()) {
            msglist();
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
                    <h3 class="text-themecolor">메시지 관리</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item active">메시지 관리</li>
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
                                    <h4 class="card-title">메시지 목록</h4>
                                    <h6 class="card-subtitle">스크롤 내릴 시 <code>자동 로딩</code></h6>
                                </div>
                                <div class="table-responsive">
                                    <table class="table color-table info-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>타입</th>
                                                <th>제목</th>
                                                <th>보낸 날짜</th>
                                            </tr>
                                        </thead>
                                        <tbody id="msglist">
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