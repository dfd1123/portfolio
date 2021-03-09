@extends('admin.layouts.header') 
@section('content')
<script>
$(function(){
    
});
function superv_in_state(trd_no, idx){
        $.ajax({
            type : "PUT",
            dataType: "json",
            data : {'supervison_no' : idx
            ,'trd_no' : trd_no},
            url : "/admin/trade/superv_in_state",
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
                console.log(data);
            }
        });
    }
    function superv_out_state(trd_no){
        $.ajax({
            type : "PUT",
            dataType: "json",
            data : {'trd_no' : trd_no},
            url : "/admin/trade/superv_out_state",
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
                console.log(data);
            }
        });
    }
    function manager_in_state(trd_no, idx){
        $.ajax({
            type : "PUT",
            dataType: "json",
            data : {'staff_no' : idx
            ,'trd_no' : trd_no},
            url : "/admin/trade/manager_in_state",
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
                console.log(data);
            }
        });
    }
    function manager_out_state(trd_no){
        $.ajax({
            type : "PUT",
            dataType: "json",
            data : {'trd_no' : trd_no},
            url : "/admin/trade/manager_out_state",
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
                console.log(data);
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
                    <h3 class="text-themecolor">거래 상세 정보</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item">거래 관리</li>
                        <li class="breadcrumb-item active">거래 상세 정보</li>
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
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <center class="m-t-30"> <img src="/adminassets/images/users/5.jpg" class="img-circle" width="150" alt="구매자 프로필 사진"/>
                                            <h4 class="card-title m-t-10">구매자 : 
                                            @if($query['query'][0]->client_name != null)
                                            {{$query['query'][0]->client_name}}
                                            @else
                                            없음
                                            @endif
                                            </h4>
                                        </center>
                                    </div>
                                    <div>
                                        <hr> </div>
                                    <div class="card-body">
                                        <small class="text-muted p-t-30 db">전화 번호</small>
                                        <h6>
                                            @if($query['query'][0]->client_contact != null)
                                            {{$query['query'][0]->client_contact}}
                                            @else
                                            없음
                                            @endif
                                        </h6>
                                        <small class="text-muted">이메일</small>
                                        <h6>
                                            @if($query['query'][0]->client_email != null)
                                            {{$query['query'][0]->client_email}}
                                            @else
                                            없음
                                            @endif
                                        </h6> 
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <center class="m-t-30"> <img src="/adminassets/images/users/5.jpg" class="img-circle" width="150" alt="판매업체 프로필 사진"/>
                                            <h4 class="card-title m-t-10">판매자 : 
                                            @if($query['query'][0]->agent_name != null)
                                            {{$query['query'][0]->agent_name}}
                                            @else
                                            없음
                                            @endif
                                            </h4>
                                        </center>
                                    </div>
                                    <div>
                                        <hr> </div>
                                    <div class="card-body">
                                        <small class="text-muted p-t-30 db">전화 번호</small>
                                        <h6>
                                            @if($query['query'][0]->agent_contact != null)
                                            {{$query['query'][0]->agent_contact}}
                                            @else
                                            없음
                                            @endif
                                        </h6>
                                        <small class="text-muted">이메일</small>
                                        <h6>
                                            @if($query['query'][0]->agent_email != null)
                                            {{$query['query'][0]->agent_email}}
                                            @else
                                            없음
                                            @endif
                                        </h6> 
                                    </div>
                                </div>
                            </div>
                             <!-- Column -->
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title" style="width:50%;display:inline-flex">담당 매니저</h4>
                                        <div class="chat-box">
                                            <table class="table color-table warning-table">
                                                <thead>
                                                    <tr>
                                                        <th>이름</th>
                                                        <th>번호</th>
                                                        <th>비고</th>
                                                    </tr>
                                                </thead>
                                                    @forelse(json_decode($query['query'][0]->staff_info) as $key =>$staff)
                                                    <tr>
                                                        <td>{{$staff->sp_name}}</td>
                                                        <td>{{$staff->sp_contact}}</td>
                                                        <td>
                                                        @if($query['query'][0]->staff_no == $staff->sp_no)
                                                        <button class="btn waves-effect waves-light btn-rounded btn-danger" 
                                                        onclick="javascript:manager_out_state({{$query['query'][0]->trd_no}});">해제</button>
                                                        @else
                                                        <button class="btn waves-effect waves-light btn-rounded btn-info"
                                                        onclick="javascript:manager_in_state({{$query['query'][0]->trd_no}},{{$staff->sp_no}});">배정</button>
                                                        @endif
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td>매니저 없음</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <!-- Column -->
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title" style="width:50%;display:inline-flex">담당 감리</h4>
                                        <div class="chat-box">
                                            <table class="table color-table warning-table">
                                                <thead>
                                                    <tr>
                                                        <th>이름</th>
                                                        <th>번호</th>
                                                        <th>비고</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse(json_decode($query['query'][0]->superv_info) as $key =>$superv)
                                                    <tr>
                                                        <td>{{$superv->sp_name}}</td>
                                                        <td>{{$superv->sp_contact}}</td>
                                                        <td>
                                                        @if($query['query'][0]->supervison_no == $superv->sp_no)
                                                        <button class="btn waves-effect waves-light btn-rounded btn-danger"
                                                        onclick="javascript:superv_out_state({{$query['query'][0]->trd_no}});">해제</button>
                                                        @else
                                                        <button class="btn waves-effect waves-light btn-rounded btn-info"
                                                        onclick="javascript:superv_in_state({{$query['query'][0]->trd_no}},{{$superv->sp_no}});">배정</button>
                                                        @endif
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td>매니저 없음</td>
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
                    
                    <!-- Column -->
                    <div class="col-md-6">
                        <div class="card">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#trade" role="tab">거래 상세 내역</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#contact" role="tab">거래 코멘트</a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!--second tab-->
                                <div class="tab-pane active" id="trade" role="tabpanel">
                                    <div class="card-body">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table color-table primary-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Title</th>
                                                                    <th>Content</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>거래명</td>
                                                                    <td>{{$query['query'][0]->trd_name}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>입찰 시작일</td>
                                                                    <td>{{$query['query'][0]->bidding_dt}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>입찰 마감일</td>
                                                                    <td>{{$query['query'][0]->bidding_end_dt}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>거래 시작일</td>
                                                                    <td>{{$query['query'][0]->construct_dt}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>거래 종료일</td>
                                                                    <td>{{$query['query'][0]->construct_end_dt}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>거래 상태</td>
                                                                    @if($query['query'][0]->state ==0)
                                                                    <td>비활성화</td>
                                                                    @elseif($query['query'][0]->state ==1)
                                                                    <td>견적중</td>
                                                                    @elseif($query['query'][0]->state ==2)
                                                                    <td>계약대기중</td>
                                                                    @elseif($query['query'][0]->state ==3)
                                                                    <td>에스크로 승인대기중</td>
                                                                    @elseif($query['query'][0]->state ==4)
                                                                    <td>거래중</td>
                                                                    @elseif($query['query'][0]->state ==5)
                                                                    <td>종료</td>
                                                                    @else
                                                                    <td>확인 불가</td>
                                                                    @endif
                                                                </tr>
                                                                <tr>
                                                                    <td>조회수</td>
                                                                    <td>{{$query['query'][0]->view_cnt}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>카레고리</td>
                                                                    <td>{{$query['query'][0]->bl_name}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>예산</td>
                                                                    <td>{{$query['query'][0]->trd_budget}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>면적</td>
                                                                    <td>{{$query['query'][0]->trd_area}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>도면</td>
                                                                    <td>
                                                                    @if($query['query'][0]->trd_draw !=null)
                                                                        @forelse(json_decode($query['query'][0]->trd_draw) as $key => $draw)
                                                                            <p>{{$draw}}</p>
                                                                        @empty
                                                                            <p>없음</p>
                                                                        @endforelse
                                                                    @else
                                                                        <p>없음</p>
                                                                    @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>파일</td>
                                                                    <td>
                                                                    @if($query['query'][0]->trd_file !=null)
                                                                        @forelse(json_decode($query['query'][0]->trd_file) as $key => $file)
                                                                            <p>{{$file}}</p>
                                                                        @empty
                                                                            <p>없음</p>
                                                                        @endforelse
                                                                    @else
                                                                        <p>없음</p>
                                                                    @endif
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="contact" role="tabpanel">
                                    <div class="card-body">
                                        <div class="chat-box">
                                            <!--chat Row -->
                                            <ul class="chat-list">
                                                @forelse(json_decode($query['query'][0]->comment_info) as $key =>$comment)
                                                    @if($comment->client_no != null && $comment->agent_no == null)
                                                    <li>
                                                        <div class="chat-content">
                                                            <h5>구매자</h5>
                                                            <div class="box bg-light-info">{{$comment->ucc_comment}}</div>
                                                        </div>
                                                        <div class="chat-time">{{$comment->reg_dt}}</div>
                                                    </li>
                                                    @else
                                                    <li class="reverse">
                                                        <div class="chat-content">
                                                            <h5>판매자</h5>
                                                            <div class="box bg-light-inverse">{{$comment->ucc_comment}}</div>
                                                        </div>
                                                        <div class="chat-time">{{$comment->reg_dt}}</div>
                                                    </li>
                                                @endif
                                                @empty
                                                <li>코멘트 없음</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                    </div>
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
                © 2019 Admin Press Admin by themedesigner.in
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
@include('admin.layouts.footer') 
@endsection