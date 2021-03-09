@extends('admin.layouts.header') 
@section('content')
<script>
    var start=0;
    var type = 0; // 0 : 전체 리스트 , 1 : 검색 결과 리스트
    var searchname ='';
    function agentlist(){
        $.ajax({
            type : "GET",
            dataType: "json",
            data : {offset : start},
            url : "/admin/user/agentlist",
            success : function(data) {
                var items = data.query;
                for(var i = 0; i< items.length;i++){
                    var layout = '<tr style=\"cursor:pointer\" onclick=\"location.href=\'/admin/agent-detail?user_no='+items[i].user_no+'\';\">';
                    layout += '\n<td>'+(start+1)+'</td>';
                    layout += '\n<td>'+items[i].name+'</td>';
                    layout += '\n<td>'+items[i].email+'</td>';
                    layout += '\n<td>'+items[i].user_contact+'</td>';
                    layout += '\n<td>'+items[i].created_at+'</td>';
                    layout += '</tr>';
                    $('#agentlist').append(layout);
                    start++;
                }
            },
            error : function(data){
            }
        });
    }
    agentlist();

    function scrolling(){
        var scrollBottom = $(window).scrollTop() + $(window).height();
        if (scrollBottom == $(document).height()) {
            if(type==0){agentlist();}
            else if(type==1){searchagent();}
        }
    }
    $(window).scroll(function() {
        scrolling();
    });
    //scrolling();
    function searchagent(){
        $.ajax({
            type : "GET",
            dataType: "json",
            data : {'offset' : start,'name' : searchname},
            url : "/admin/user/byagentname",
            success : function(data) {
                console.log(data);
                if(data.state==1 && data.query!=null){
                    var items = data.query;
                    for(var i = 0; i< items.length;i++){
                        var layout = '<tr style=\"cursor:pointer\" onclick=\"location.href=\'/admin/agent-detail?user_no='+items[i].user_no+'\';\">';
                        layout += '\n<td>'+(start+1)+'</td>';
                        layout += '\n<td>'+items[i].name+'</td>';
                        layout += '\n<td>'+items[i].email+'</td>';
                        layout += '\n<td>'+items[i].user_contact+'</td>';
                        layout += '\n<td>'+items[i].created_at+'</td>';
                        layout += '</tr>';
                        $('#agentlist').append(layout);
                        start++;
                    }
                }
                else{
                    alert(data.msg);
                }
            },
            error : function(data){
                console.log(data);
            }
        });
    }
    $(function(){
        $('#search-btn').click(function(){
            start=0;
            searchname = $('#search-value').val();
            $('#agentlist').html('');
            searchagent();
            type=1;
        });
    });
</script>
<div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor">업체</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item active">업체 관리</li>
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
                                <h4 class="card-title">업체 목록</h4>
                                <h6 class="card-subtitle">스크롤 내릴 시 <code>자동 로딩</code></h6>
                                <div style="padding:1em 0;">
                                    <input id="search-value" type="text" class="form-control" placeholder="검색어" style="width:30em;">
                                    <button id="search-btn" class="btn waves-effect waves-light btn-info">검색</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table color-table info-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>업체명</th>
                                                <th>이메일</th>
                                                <th>전화번호</th>
                                                <th>등록일자</th>
                                            </tr>
                                        </thead>
                                        <tbody id="agentlist">
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