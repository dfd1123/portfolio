@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
          <ol class="breadcrumb tsa-top-tit">
            <li class="breadcrumb-item active">베팅 관리</li>
          </ol>

          <!-- DataTables Example -->
          <div class="card mb-3 tsa-card">
            <div class="card-header">
              	이번주 베팅내역
              	</div>
            <div class="card-body">
            	<div class="usr_search_box tsa-sch-box">
	            	<form method="get" action="{{route('admin.week_batting')}}">
	            		@csrf
		            	<select name="keyword_srch">
		            		<option value="user_name" {{($keyword_srch == 'user_name')?'selected=selected':''}}>작가이름</option>
		            		<option value="user_email" {{($keyword_srch == 'user_email')?'selected=selected':''}}>아이디</option>
		            		<option value="title" {{($keyword_srch == 'title')?'selected=selected':''}}>작품이름</option>
		            	</select>
		            	<input type="text" name="keyword" value="{{$keyword}}" />
		            	<button type="submit">검색</button>
		            </form>
	            </div>
              <div class="table-responsive tsa-table-wrap">
                <table class="table table-bordered prd_adm_table" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                    	<th rowspan="2" style="width:15%;">순위</th>
                    	<th rowspan="2" style="width:15%;">이미지</th>
	                    <th rowspan="2">작품제목</th>
	                    <th>작가</th>
	                    <th>누적베팅건수</th>
	                    <th>베팅시작날짜</th>
	                    <th rowspan="2">조회수</th>
                    </tr>
                    <tr>
                    	<th>아이디</th>
                    	<th>누적베팅금액</th>
                    	<th>베팅종료날짜</th>
                    </tr>
                  </thead>
                  <tbody>
                  @forelse($products as $product)
                    <tr>
                      <td rowspan="2">
                      <?php
                      	if($product->coin_batting == 0){
                      		echo "-";
                      	}else{
                      		echo $ranking;
							$ranking++;                      		
                      	}
                      ?>
                      </td>
                      <td rowspan="2">
                      	<img src="{{asset('storage/image/product/'.$product->image1)}}" />
                      </td>
                      <td rowspan="2">{{$product->title}}</td>
                      <td>{{$product->artist_name}}</td>
                      <td>
                      	{{$product->battings->count()}}
                      </td>
                      <td>{{$product->start_time}}</td>
                      <td rowspan="2">{{$product->get_hit}}</td>
                     <tr>
                     	<td>{{$product->user->email}}</td>
                     	<td>{{$product->coin_batting}}</td>
                     	<td>{{$product->end_time}}</td>
                     </tr>
                    @empty
                    <tr>
                    	<td colspan="7" >작품이 존재하지 않습니다.</td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
	            @if($products_page)
					{!! $products_page->render() !!}
				@endif
            </div>
            <div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
          </div>
          

@endsection

@section('script')

<script>
	

	
</script>

@endsection