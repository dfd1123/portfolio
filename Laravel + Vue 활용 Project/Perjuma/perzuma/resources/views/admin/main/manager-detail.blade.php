@extends('admin.layouts.header') 
@section('content')
<script>
var sp_no = findGetParameter('sp_no');
$(function(){
    $('#updatebtn').click(function(){
        var param = {
            'sp_no' : sp_no,
            'sp_name' : $('#sp_name').val(),
            'sp_contact' : $('#sp_contact').val()
        };
        $.ajax({
            type : "PUT",
            dataType: "json",
            data : param,
            url : "/admin/manager/normalinfo",
            success : function(data) {
                console.log(data);
                if(data.state==1 && data.query!=null){
                    alert('수정되었습니다');
                    location.reload();
                }
                else{
                    alert(data.msg);
                }
            },
            error : function(data){
            }
        });
    });
});
</script>
<div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor">매니저 상세 정보</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item">매니저 관리</li>
                        <li class="breadcrumb-item active">매니저 상세 정보</li>
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
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-4 col-xlg-3 col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <center class="m-t-30">
                                    <h4 class="card-title m-t-10">{{$query['query'][0]->sp_name}}</h4>
                                </center>
                            </div>
                            <div>
                                <hr> </div>
                            <div class="card-body">
                                <small class="text-muted p-t-30 db">전화 번호</small>
                                <h6>{{$query['query'][0]->sp_contact}}</h6>
                                <small class="text-muted p-t-30 db">매니저 상태</small>
                                @if($query['query'][0]->state ==1)
                                <h6>활성화</h6>
                                @else
                                <h6>비활성화</h6>
                                @endif
                                <small class="text-muted p-t-30 db">등록 날짜</small>
                                <h6>{{$query['query'][0]->reg_dt}}</h6>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-8 col-xlg-9 col-md-7">
                        <div class="card">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#trade" role="tab">거래 내역</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab">매니저 정보 수정</a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!--second tab-->
                                <div class="tab-pane active" id="trade" role="tabpanel">
                                    <div class="card-body">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="card-title" style="width:50%;display:inline-flex">최근 거래 내역</h4>
                                                    <a style="float:right;" href="/admin/trades?search_no={{$query['query'][0]->sp_no}}&search_type=3">거래 내역 페이지로 ></a>
                                                    <div class="table-responsive">
                                                        <table class="table color-table primary-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>거래명</th>
                                                                    <th>판매자</th>
                                                                    <th>거래</th>
                                                                    <th>구매자</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse(json_decode($query['query'][0]->trade_info) as $key =>$trade)
                                                                <tr style="cursor:pointer" onclick="location.href='/admin/trade-detail?trd_no={{$trade->trd_no}}';">
                                                                    <td>{{$trade->trd_name}}</td>
                                                                    <td>{{$trade->agent_name}}</td>
                                                                    @if($trade->construct_dt != null)
                                                                        <td>{{$trade->construct_dt}} ~ {{$trade->construct_end_dt}}</td>
                                                                    @else
                                                                        <td>거래 전</td>
                                                                    @endif
                                                                    <td>{{$trade->client_name}}</td>
                                                                </tr>
                                                                @empty
                                                                <tr>
                                                                    <td>내역 없음</td>
                                                                </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="settings" role="tabpanel">
                                    <div class="card-body">
                                            <div class="form-group">
                                                <label class="col-md-12">매니저 이름</label>
                                                <div class="col-md-12">
                                                    <input id="sp_name" type="text" placeholder="Johnathan Doe" class="form-control form-control-line" value="{{$query['query'][0]->sp_name}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12">전화번호</label>
                                                <div class="col-md-12">
                                                    <input id="sp_contact" type="text" placeholder="123 456 7890" class="form-control form-control-line" value="{{$query['query'][0]->sp_contact}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <button id="updatebtn" class="btn btn-success">정보 업데이트</button>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <!-- <div class="col-lg-8 col-xlg-9 col-md-7">
                        <div class="card">
                            <div class="card-body">
                            </div>
                            <div>
                                <hr> </div>
                            <div class="card-body">
                                
                            </div>
                        </div>
                    </div> -->
                    <!-- Column -->
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
                © 2019 Admin Press Admin by themedesigner.in
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
@include('admin.layouts.footer') 
@endsection