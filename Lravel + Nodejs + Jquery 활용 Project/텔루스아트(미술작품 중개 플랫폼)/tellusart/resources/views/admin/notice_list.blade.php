@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
          <ol class="breadcrumb tsa-top-tit">
            <li class="breadcrumb-item active">공지사항 관리</li>
          </ol>

          <!-- DataTables Example -->
          <div class="card mb-3 tsa-card">
            <div class="card-header">
              	공지사항 리스트
              	</div>
            <div class="card-body">
              <div class="table-responsive tsa-table-wrap">
                <table class="table table-bordered faq-tbl" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                    	<th style="width:60%;">제목</th>
	                    <th style="width:14%;">작성자</th>
	                    <th style="width:13%;">작성일</th>
	                    <th style="width:13%;">조회수</th>
                    </tr>
                  </thead>
                  <tbody>
                  @forelse($notices as $notice)
                  	<tr>
	                    <td>
	                    	<a href="{{route('admin.notice_show',$notice->id)}}">{{$notice->title}}</a>
	                    </td>
	                    <td>
	                    	관리자
	                    </td>
	                    <td>
	                    	{{date("Y-m-d", strtotime($notice->created_at)) }}
	                    </td>
	                    <td>
	                    	{{$notice->hit}}
	                    </td>
	                </tr>
                    @empty
                    <tr colspan="4" >
                    	<td>등록된 공지사항이 없습니다.</td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              <div>
				  <button type="button" class="org_btn" onclick="location.href='{{route('admin.notice_create')}}'">추가</button>
			  </div>
	            @if($notices)

								{!! $notices->render() !!}

							@endif
            </div>
            <div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
          </div>
          

@endsection

@section('script')

<script>
	

	
</script>

@endsection