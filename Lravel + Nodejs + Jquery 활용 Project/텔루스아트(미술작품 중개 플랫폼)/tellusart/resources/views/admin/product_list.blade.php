@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
          <ol class="breadcrumb tsa-top-tit">
            <li class="breadcrumb-item active">작품관리</li>
          </ol>

          <!-- DataTables Example -->
          <div class="card mb-3 tsa-card">
            <div class="card-header">
              	{{$title}}
              	</div>
            <div class="card-body">
            	<ul class="nav nav-tabs">
            		@if(($sell_yn)==0)
					  <li class="active"><a href="{{route('admin.product_list',0)}}">판매신청</a></li>
					  <li><a href="{{route('admin.product_list',2)}}">판매거절</a></li>
					  <li><a href="{{route('admin.product_list',1)}}">판매승인</a></li>
					  <li><a href="{{route('admin.product_list',3)}}">판매완료</a></li>
					@elseif(($sell_yn)==1)
					  <li><a href="{{route('admin.product_list',0)}}">판매신청</a></li>
					  <li><a href="{{route('admin.product_list',2)}}">판매거절</a></li>
					  <li class="active"><a href="{{route('admin.product_list',1)}}">판매승인</a></li>
					  <li><a href="{{route('admin.product_list',3)}}">판매완료</a></li>
					@elseif(($sell_yn)==2)
					  <li><a href="{{route('admin.product_list',0)}}">판매신청</a></li>
					  <li class="active"><a href="{{route('admin.product_list',2)}}">판매거절</a></li>
					  <li><a href="{{route('admin.product_list',1)}}">판매승인</a></li>
					  <li><a href="{{route('admin.product_list',3)}}">판매완료</a></li>
					@elseif(($sell_yn)==3)
					  <li><a href="{{route('admin.product_list',0)}}">판매신청</a></li>
					  <li><a href="{{route('admin.product_list',2)}}">판매거절</a></li>
					  <li><a href="{{route('admin.product_list',1)}}">판매승인</a></li>
					  <li class="active"><a href="{{route('admin.product_list',3)}}">판매완료</a></li>
					@endif
				</ul>
            	<div class="usr_search_box tsa-sch-box">
	            	<form method="get" action="{{route('admin.product_list',$sell_yn)}}">
	            		@csrf
		            	<select name="keyword_srch">
										<option value="user_name" {{($keyword_srch == 'user_name')?'selected=selected':''}}>작가이름</option>
		            		<option value="user_id" {{($keyword_srch == 'user_id')?'selected=selected':''}}>아이디</option>
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
                    	<th rowspan="2" style="width:15%;">이미지</th>
	                    <th rowspan="2">작품제목</th>
	                    <th>작가</th>
	                    <th>가격</th>
	                    <th>등록날짜</th>
	                    <th rowspan="2">조회수</th>
	                    <th rowspan="2">판매상태</th>
                    </tr>
                    <tr>
                    	<th>아이디</th>
                    	<th>코인가격</th>
                    	<th>마지막수정날짜</th>
                    </tr>
                  </thead>
                  <tbody>
                  @forelse($products as $product)
                    <tr>
                      <td rowspan="2">
                      	<img src="{{asset('storage/image/product/'.$product->image1)}}" />
                      </td>
                      <td rowspan="2">{{$product->title}}</td>
                      <td>{{$product->artist_name}}</td>
                      <td>{{$product->cash_price}}</td>
                      <td>{{date('Y.m.d',strtotime($product->created_at))}}</td>
                      <td rowspan="2">{{$product->get_hit}}</td>
                      <td rowspan="2">
                      	@if(($product->sell_yn)==0)
	                      	<form method="post" action="{{route('admin.sell_yn_change')}}">
	                      		@csrf
	                      		<input type="hidden" name="sell_yn_change" value="1" />
	                      		<input type="hidden" name="id" value="{{$product->id}}" />
	                      		<button type="submit" class="sell_yn_chage myButton ok">판매승인</button>
	                      	</form>
	                    	<button type="button" id="sell_yn_chageno{{$product->id}}" class="sell_yn_chage sell_yn_no myButton no">승인거절</button>
											@elseif(($product->sell_yn)==1)
												@if($product->batting_status != 1)
													<button type="submit" id="sell_yn_chageno{{$product->id}}" class="sell_yn_chage sell_yn_no  myButton xbtn">승인취소</button>
												@else
													<button type="button" class="sell_yn_chage  myButton xbtn" onclick="alert('베팅중인 작품은 취소가 불가합니다.')">승인취소</button>
												@endif
	                    @elseif(($product->sell_yn)==2)
	                    	<button type="button" id="sell_yn_reject{{$product->id}}" class="sell_yn_chage sell_yn_reject myButton rjt" >거절사유 보기</button>
	                    	<form method="post" action="{{route('admin.sell_yn_change')}}">
	                      		@csrf
	                      		<input type="hidden" name="sell_yn_change" value="1" />
	                      		<input type="hidden" name="id" value="{{$product->id}}" />
	                    		<button type="submit" class="sell_yn_chage myButton ok">판매승인</button>
	                    	</form>
	                    	<span class="reject_infor_soundonly">{{$product->reject_infor}}</span>
	                    @elseif(($product->sell_yn)==3)
	                    	<span>판매완료</span>
	                    @endif
                      </td>
                     </tr>
                     <tr>
                     	<td>{{$product->user->email}}</td>
                     	<td>{{ number_format($product->coin_price,8) }}</td>
                     	<td>{{date('Y.m.d',strtotime($product->updated_at))}}</td>
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
          
		  <div class="modal fade" id="myModal" role="dialog">
		    <div class="modal-dialog tsa-modal-dialog">
		    
		      <!-- Modal content-->
		      <div class="modal-content">
		        <div class="modal-header">
		          <h4 class="modal-title">거절사유</h4>
		          <button type="button" class="close" data-dismiss="modal">×</button>
		        </div>
		        <div class="modal-body">
		          <textarea name="reject_infor"  class="tsa-textarea" readonly="readonly"></textarea>
		        </div>
		        <div class="modal-footer">
		          <button type="button" class="btn btn-default org_btn" data-dismiss="modal">Close</button>
		        </div>
		      </div>
		      
		    </div>
		  </div>
		  <div class="modal fade" id="myModal2" role="dialog">
		    <div class="modal-dialog">
		    
		      <!-- Modal content-->
		      <div class="modal-content">
		      	<form method="post" action="{{route('admin.sell_yn_change')}}">
		      		@csrf
		      		<input type="hidden" name="sell_yn_change" value="2" />
		      		<input type="hidden" name="id" />
		      		<div class="modal-header">
			          <h4 class="modal-title">거절사유</h4>
			          <button type="button" class="close" data-dismiss="modal">×</button>
			        </div>
			        <div class="modal-body">
			          <textarea name="reject_infor" class="tsa-textarea"></textarea>
			        </div>
			        <div class="modal-footer">
			          <button type="submit" class="btn btn-default org_btn">확인</button>
			        </div>
		      	</form>
		      </div>
		      
		    </div>
		  </div>

@endsection

@section('script')

<script>
	
	$('.sell_yn_reject').click(function(){
		 $("#myModal").modal();
		 $("#myModal textarea[name='reject_infor']").val($(this).siblings('.reject_infor_soundonly').text());
	})
	
	$('.sell_yn_no').click(function(){
		 $("#myModal2").modal();
		 var id = $(this).attr('id');
		 id = id.replace('sell_yn_chageno','');
		 $("#myModal2 form input[name='id']").val(id);
	})

	
</script>

@endsection