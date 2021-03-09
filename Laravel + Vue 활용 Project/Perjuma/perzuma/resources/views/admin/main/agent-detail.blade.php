@extends('admin.layouts.header') 
@section('content')
<script>
var user_no = findGetParameter('user_no');
$(function(){
    $('#msg-send').click(function(){
        if($('#msg_type').val()==-1){
            alert('메세지 타입을 선택해 주세요');
            return;
        }
        var param = {
            'user_no' : user_no,
            'msg_title' : $('#msg_title').val(),
            'msg_content' : $('#msg_content').val(),
            'msg_type' : $('#msg_type').val()
        };
        $.ajax({
            type : "POST",
            dataType: "json",
            data : param,
            url : "/admin/message",
            success : function(data) {
                console.log(data);
                if(data.state==1 && data.query!=null){
                    alert('메세지를 보냈습니다');
                    //location.reload();
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
function state(idx){
        var param = {
            'agent_no' : user_no,
            'state' : idx
        };
        $.ajax({
            type : "PUT",
            dataType: "json",
            data : param,
            url : "/admin/user/state",
            success : function(data) {
                console.log(data);
                if(data.state==1 && data.query!=null){
                    alert('상태가 변경되었습니다');
                    location.reload();
                }
                else{
                    alert(data.msg);
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
                    <h3 class="text-themecolor">업체 상세 정보</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">메인</a></li>
                        <li class="breadcrumb-item">업체 관리</li>
                        <li class="breadcrumb-item active">업체 상세 정보</li>
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
                                <center class="m-t-30"> <img src="/adminassets/images/users/5.jpg" class="img-circle" width="150" alt="사용자 프로필 사진"/>
                                    <h4 class="card-title m-t-10">{{$query['query'][0]->name}}</h4>
                                </center>
                            </div>
                            <div>
                                <hr> </div>
                            <div class="card-body">
                            <small class="text-muted p-t-30 db">전화 번호</small>
                                <h6>{{$query['query'][0]->user_contact}}</h6>
                                <small class="text-muted">이메일</small>
                                <h6>{{$query['query'][0]->email}}</h6> 
                                <small class="text-muted p-t-30 db">이메일 인증 날짜</small>
                                <h6>{{$query['query'][0]->email_verified_at}}</h6> 
                                <small class="text-muted p-t-30 db">사용자 등급</small>
                                @if($query['query'][0]->user_grade ==1)
                                <h6>유저</h6>
                                @elseif($query['query'][0]->user_grade ==2)
                                <h6>업체</h6>
                                @endif
                                <small class="text-muted p-t-30 db">사용자 상태</small>
                                <h6>
                                @if($query['query'][0]->state == 0)
                                승인대기
                                @elseif($query['query'][0]->state == 1)
                                승인
                                @else
                                비활성화
                                @endif
                                </h6>
                                <small class="text-muted p-t-30 db">추가 정보</small>
                                <h6></h6>
                                <small class="text-muted p-t-30 db">등록 날짜</small>
                                <h6>{{$query['query'][0]->created_at}}</h6>
                                <small class="text-muted p-t-30 db">업데이트 날짜</small>
                                <h6>{{$query['query'][0]->updated_at}}</h6>
                                <small class="text-muted p-t-30 db">최근 방문 날짜</small>
                                <h6>{{$query['query'][0]->last_vt_dt}}</h6>
                                @if($query['query'][0]->state == 0)
                                <button class="btn waves-effect waves-light btn-success" onclick="javascript:state(1);">승인</button>
                                @elseif($query['query'][0]->state == 1)
                                <button class="btn waves-effect waves-light btn-danger" onclick="javascript:state(2);">비활성화</button>
                                @else
                                <button class="btn waves-effect waves-light btn-info" onclick="javascript:state(1);">활성화</button>
                                @endif
                                
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-8 col-xlg-9 col-md-7">
                        <div class="card">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#detail" role="tab">업체 상세 정보</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#trade" role="tab">거래 내역</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#contact" role="tab">문의 내역</a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!--second tab-->
                                <div class="tab-pane active" id="detail" role="tabpanel">
                                    <div class="card-body">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="card-title" style="width:50%;display:inline-flex">업체 상세 정보</h4>
                                                    <div class="table-responsive">
                                                        <table class="table color-table primary-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>title</th>
                                                                    <th>content</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            @if(json_decode($query['query'][0]->agent_info[0]) != null)
                                                                <tr>
                                                                    <td>업체 이름</td>
                                                                    <td>{{json_decode($query['query'][0]->agent_info[0]->agent_name)}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>업체 주소</td>
                                                                    <td>{{json_decode($query['query'][0]->agent_info[0]->agent_addr)}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>업체 번호</td>
                                                                    <td>{{json_decode($query['query'][0]->agent_info[0]->agent_contact)}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>업체 등록일</td>
                                                                    <td>{{json_decode($query['query'][0]->agent_info[0]->created_at)}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>업체 상태</td>
                                                                    <td>{{json_decode($query['query'][0]->agent_info[0]->state)}}</td>
                                                                </tr>
                                                            @else
                                                                <tr>
                                                                    <th>업체정보가 없습니다</th>
                                                                </tr>
                                                            @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="trade" role="tabpanel">
                                    <div class="card-body">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="card-title" style="width:50%;display:inline-flex">최근 거래 내역</h4>
                                                    <a style="float:right;" href="/admin/trades?search_no={{$query['query'][0]->user_no}}&search_type=2">거래 내역 페이지로 ></a>
                                                    <div class="table-responsive">
                                                        <table class="table color-table primary-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>거래명</th>
                                                                    <th>거래 시작일</th>
                                                                    <th>거래 종료일</th>
                                                                    <th>판매자</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse(json_decode($query['query'][0]->trade_info) as $key =>$trade)
                                                                <tr style="cursor:pointer" onclick="location.href='/admin/trade-detail?trd_no={{$trade->trd_no}}';">
                                                                    <td>{{$trade->trd_name}}</td>
                                                                    <td>{{$trade->construct_dt}}</td>
                                                                    <td>{{$trade->construct_end_dt}}</td>
                                                                    <td>{{$trade->agent_no}}</td>
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
                                <div class="tab-pane" id="contact" role="tabpanel">
                                    <div class="card-body">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="card-title" style="width:50%;display:inline-flex">최근 문의 내역</h4>
                                                    <a style="float:right;" href="/admin/bbs?user_no={{$query['query'][0]->user_no}}">문의 내역 페이지로 ></a>
                                                    <div class="table-responsive">
                                                        <table class="table color-table warning-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>문의 제목</th>
                                                                    <th>문의 내용</th>
                                                                    <th>답변</th>
                                                                    <th>등록일</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse(json_decode($query['query'][0]->bbs_info) as $key =>$bbs)
                                                                <tr style="cursor:pointer" onclick="location.href='/admin/bbs-detail?bbs_no={{$bbs->bbs_no}}';">
                                                                    <td>{{$bbs->title}}</td>
                                                                    <td>{{$bbs->content}}</td>
                                                                    <td>{{$bbs->ans!=null ? 'O' : 'X'}}</td>
                                                                    <td>{{$bbs->reg_dt}}</td>
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
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <input id="msg_title" type="text" placeholder="ex) 한식" class="form-control form-control-line">
                                <textarea id="msg_content" rows="5" style="width:100%;border-radius:1em;padding:1em;margin:1em 0;" placeholder="보내실 메시지를 작성해주세요"></textarea>
                                <select id="msg_type">
                                    <option value="-1">메세지 타입</option>
                                    <option value="0">일반</option>
                                    <option value="1">긴급</option>
                                </select>
                                <button id="msg-send" class="btn waves-effect waves-light btn-rounded btn-info" style="float:right">보내기</button>
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
