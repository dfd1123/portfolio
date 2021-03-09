@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
          <ol class="breadcrumb tsa-top-tit">
            <li class="breadcrumb-item active">베팅 관리</li>
          </ol>

          <!-- DataTables Example -->
          <div class="card mb-3 tsa-card">
            <div class="card-header">
              	베팅내역
              	</div>
            <div class="card-body">
            	<div class="usr_search_box tsa-sch-box">
	            	<form method="get" action="{{route('admin.batting_list')}}">
	            		@csrf
		            	<select name="keyword_srch">
		            		<option value="user_name" {{($keyword_srch == 'user_name')?'selected=selected':''}}>이름(닉네임)</option>
		            		<option value="user_email" {{($keyword_srch == 'user_email')?'selected=selected':''}}>이메일</option>
                    <option value="mobile" {{($keyword_srch == 'mobile')?'selected=selected':''}}>휴대폰 뒷자리</option>
		            	</select>
		            	<input type="text" name="keyword" value="{{$keyword}}" />
		            	<button type="submit">검색</button>
		            </form>
	            </div>
              <div class="table-responsive tsa-table-wrap">
                <table class="table table-bordered prd_adm_table" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                    	<th style="width:15%;">이름(닉네임)</th>
	                    <th style="width:10%;">이미지</th>
	                    <th rowspan="2">베팅금액</th>
	                    <th rowspan="2">베팅날짜</th>
                    </tr>
                    <tr>
                    	<th>아이디</th>
                    	<th>작품제목</th>
                    </tr>
                  </thead>
                  <tbody>
                  @forelse($battings_page as $batting)
                    <tr>
                      <td>
                      	{{ $batting->user->name }}({{$batting->user->nickname}})
                      </td>
                      <td style="width:15%;"><img src="{{asset('storage/image/product/'.$batting->product->image1)}}" /></td>
                      <td rowspan="2">{{$batting->batting_price}}</td>
                      <td rowspan="2">
                      	{{$batting->created_at->format('Y.m.d')}}
                      </td>
                     </tr>
                     <tr>
                     	<td>{{$batting->user->email}}</td>
                     	<td>{{$batting->product->title}}</td>
                     </tr>
                    @empty
                    <tr>
                    	<td colspan="7" >작품이 존재하지 않습니다.</td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
	            @if($battings_page)
					{!! $battings_page->render() !!}
				@endif
            </div>
            <div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
          </div>
          

@endsection

@section('script')

<script>
	

	
</script>

@endsection