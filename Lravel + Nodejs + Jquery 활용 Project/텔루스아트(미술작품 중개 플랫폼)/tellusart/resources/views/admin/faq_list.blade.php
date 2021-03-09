@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
          <ol class="breadcrumb tsa-top-tit">
            <li class="breadcrumb-item active">FAQ 관리</li>
          </ol>

          <!-- DataTables Example -->
          <div class="card mb-3 tsa-card">
            <div class="card-header">
              	FAQ 리스트
              	</div>
            <div class="card-body">
            	<!--div class="usr_search_box tsa-sch-box">
	            	<form method="get" action="{{route('admin.faq_list')}}">
	            		@csrf
		            	<select name="keyword_srch">
		            		<option value="user_name" selected="selected">질문</option>
		            	</select>
		            	<input type="text" name="keyword" />
		            	<button type="submit">검색</button>
		            </form>
	            </div-->
              <div class="table-responsive tsa-table-wrap">
                <table class="table table-bordered faq-tbl" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                    	<th style="width:60%;">질문</th>
	                    <th style="width:14%;">답변여부</th>
	                    <th style="width:13%;">수정날짜</th>
	                    <th style="width:13%;">작성날짜</th>
                    </tr>
                  </thead>
                  <tbody>
                  @forelse($faqs as $faq)
                  	<tr>
	                    <td>
	                    	<a href="{{route('admin.faq_edit',$faq->id)}}">{{$faq->question}}</a>
	                    </td>
	                    <td>
	                    	@if($faq->answer != NULL)
	                    		<a href="{{route('admin.faq_edit',$faq->id)}}">
	                    			<span class="isset">답변있음</span>
	                    		</a>
	                    	@else
	                    		<a href="{{route('admin.faq_edit',$faq->id)}}">
	                    			<span class="noisset">답변없음</span>
	                    		</a>
	                    	@endif
	                    </td>
	                    <td>
	                    	{{ date("Y-m-d", strtotime($faq->created_at)) }}
	                    </td>
	                    <td>
	                    	{{date("Y-m-d", strtotime($faq->updated_at)) }}
	                    </td>
	                </tr>
                    @empty
                    <tr colspan="4" >
                    	<td>등록된 FAQ가 없습니다.</td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              <div>
				  <button type="button" class="org_btn" onclick="location.href='{{route('admin.faq_create')}}'">추가</button>
			  </div>
	            @if(count($faqs) > 10)
		            <div class="paging_board">
						<span class="af">
							<a href="/events/event_show"><i class="fas fa-angle-double-left"></i></a>
						</span>
						{!! $faqs->render() !!}
						<span class="bf">
							<a href="/events/event_show"><i class="fas fa-angle-double-right"></i></a>
						</span>
					</div>
				@endif
            </div>
            <div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
          </div>
          

@endsection

@section('script')

<script>
	

	
</script>

@endsection