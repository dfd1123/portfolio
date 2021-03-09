@extends('admin.layouts.app')

@section('content')
<!-- Breadcrumbs-->
          <ol class="breadcrumb tsa-top-tit">
            <li class="breadcrumb-item active">관리자 계정 정보</li>
          </ol>

          <!-- DataTables Example -->
          <div class="card mb-3 tsa-card">
            <!-- <div class="card-header">
              	회원리스트</div> -->
            <div class="card-body">
              <div class="table-responsive tsa-table-wrap">
                <table class="table table-bordered tlc_usertbl" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
	                    <th class="tsa-label-st" rowspan="2">이름</th>
	                    <th class="tsa-label-st" rowspan="2">아이디</th>
	                    <th class="tsa-label-st" rowspan="2">전화번호</th>
	                    <th class="tsa-label-st" rowspan="2">보안등급</th>
                        <th class="tsa-label-st">최근 로그인</th>
                        <th class="tsa-label-st" rowspan="2">설정</th>
                    </tr>
                    <tr>
                        <th class="tsa-label-st">최근 접속IP</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($admin_users as $admin_user)
                        <tr>
                            <td rowspan="2">
                                {{$admin_user->fullname}}
                            </td>
                            <td rowspan="2">
                                {{$admin_user->email}}
                            </td>
                            <td rowspan="2">
                                {{$admin_user->mobile_number ?: '-'}}
                            </td>
                            <td rowspan="2">
                                @if(Auth::guard('admin')->user()->level <= 2)
                                <select class="adm_user_level" data-id="{{$admin_user->id}}">
                                    @for($i=5;$i>=1;$i--)
                                        <option value="{{$i}}" {{($admin_user->level == $i)?'selected=selected':''}}>{{$i}}</option>
                                    @endfor
                                </select>
                                @else
                                    {{$admin_user->level}}
                                @endif
                            </td>
                            <td>
                                {{$admin_user->time_signin}}
                            </td>
                            <td rowspan="2">
                                @if(Auth::guard('admin')->user()->id == $admin_user->id)
                                    <button type="button" onclick="location.href='{{route('admin.admin_user_password_edit',$admin_user->id)}}'">비밀번호변경</button>
                                @endif

                                @if(Auth::guard('admin')->user()->level <= 2 && $admin_user->level != 1 )
                                    <button type="button" onclick="admin_user_delete({{$admin_user->id}})">삭제</button>
                                @endif

                            </td>
                        </tr>
                        <tr>
                            <td>{{$admin_user->ip ?: '-'}}</td>
                        </tr>
                    @empty
                        <tr rowspan="2">
                            <td>
                                관리자 계정이 존재하지 않습니다.
                            </td>
                        </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
                @if($admin_user->level <= 2)
                <div>
                    <button type="button" onclick="location.href='{{route('admin.admin_user_create')}}'" class="org_btn">추가</button>
                </div>
                @endif
            </div>
            <div class="card-footer small text-muted">{{ $datetime }} 에 업데이트된 정보입니다.</div>
          </div>
@endsection
