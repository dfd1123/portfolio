@extends('admin.layouts.header') 
@section('content')
<script>
$(function(){
var start=0;
var type = 0; // 0 : 전체 리스트 , 1 : 검색 결과 리스트
var searchname ='';
var search_no = findGetParameter('search_no');
var search_type = findGetParameter('search_type');

    if(search_no != null && search_type != null){
        searchname = search_no;
        start=0;
        type=1;
        $('#tradelist').html('');
        searchtrade();
    }
    else{
        tradelist();
    }
    function tradelist(){
        $.ajax({
            type : "GET",
            dataType: "json",
            data : {'offset' : start},
            url : "/admin/trade/list",
            success : function(data) {
                console.log(data);
                if(data.state==1 && data.query!=null){
                    var items = data.query;
                    for(var i = 0; i< items.length;i++){
                        var state='';
                        if(items[i].state==0){state='비활성';}
                        else if(items[i].state==1){state='견적중';}
                        else if(items[i].state==2){state='거래중';}
                        else if(items[i].state==3){state='에스크로 승인 대기중';}
                        else if(items[i].state==4){state='거래중';}
                        else{state='종료';}

                        var layout = '<tr style=\"cursor:pointer\" onclick=\"location.href=\'/admin/trade-detail?trd_no='+items[i].trd_no+'\';\">';
                        layout += '\n<td>'+(start+1)+'</td>';
                        layout += '\n<td>'+items[i].trd_name+'</td>';
                        layout += '\n<td>'+state+'</td>';
                        layout += '\n<td>'+items[i].created_at+'</td>';
                        layout += '</tr>';
                        $('#tradelist').append(layout);
                        start++;
                    }
                }
                else{
                    alert(data.msg);
                }
            },
            error : function(data){
            }
        });
    }
    
    function scrolling(){
        var scrollBottom = $(window).scrollTop() + $(window).height();
        if (scrollBottom == $(document).height()) {
            if(type==0){tradelist();}
            else if(type==1){searchtrade();}
        }
    }
    $(window).scroll(function() {
        scrolling();
    });
    scrolling();
    function searchtrade(){
        $.ajax({
            type : "GET",
            dataType: "json",
            data : {'offset' : start
                    ,'search_keyword' : searchname
                    ,'search_type' : search_type},
            url : "/admin/trade/search",
            success : function(data) {
                console.log(data);
                if(data.state==1 && data.query!=null){
                    var items = data.query;
                    for(var i = 0; i< items.length;i++){
                        var state='';
                        if(items[i].state==0){state='비활성';}
                        else if(items[i].state==1){state='견적중';}
                        else if(items[i].state==2){state='거래중';}
                        else{state='종료';}

                        var layout = '<tr style=\"cursor:pointer\" onclick=\"location.href=\'/admin/trade-detail?trd_no='+items[i].trd_no+'\';\">';
                        layout += '\n<td>'+(start+1)+'</td>';
                        layout += '\n<td>'+items[i].trd_name+'</td>';
                        layout += '\n<td>'+state+'</td>';
                        layout += '\n<td>'+items[i].created_at+'</td>';
                        layout += '</tr>';
                        $('#tradelist').append(layout);
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
    $('#search-btn').click(function(){
        if($('#cate').val()==-1){
            alert('검색 카테고리를 정해주세요');
            return;
        }else if($('#cate').val()==0){
            search_type=0;
        }else{
            search_type=5;
        }
        start=0;
        searchname = $('#search-value').val();
        $('#tradelist').html('');
        searchtrade();
        type=1;
    });
})
</script>
<div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor">거래 관리</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item active">거래 관리</li>
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
                                <h4 class="card-title">거래 목록</h4>
                                <h6 class="card-subtitle">스크롤 내릴 시 <code>자동 로딩</code></h6>
                                <div style="padding:1em 0;">
                                <select id="cate" class="form-control" style="width:inherit">
                                    <option value="-1">검색 카테고리</option>
                                    <option value="0">제목</option>
                                    <option value="1">카테고리 번호</option>
                                </select>
                                    <input id="search-value" type="text" class="form-control" placeholder="검색어" style="width:30em;">
                                    <button id="search-btn" class="btn waves-effect waves-light btn-info">검색</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table color-table info-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>상품명</th>
                                                <th>상태</th>
                                                <th>생성 날짜</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tradelist">
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