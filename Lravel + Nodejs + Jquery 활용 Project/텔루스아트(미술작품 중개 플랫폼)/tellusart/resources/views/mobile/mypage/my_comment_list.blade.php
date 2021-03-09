@extends('layouts.app')

@section('content')
	<div class="sub_spot mypage">
		<h2>마이페이지</h2>
	</div>
	<div id="container">
		@include('mypage.include.my_common')
		<div class="orderbox">
			
			<div class="cartbox">
				<h3 class="mytit">나의 코멘트</h3>
				<span class="titm_txt">총 <strong>{{$review_cnt}}건</strong>의 코멘트가 있습니다.</span>
			</div> 
			<!-- 코멘트 리스트 -->
			<form>
				<div class="mycomment_box">
					@forelse($reviews as $review)
						<div id="commlist{{$review->id}}" class="commlist">
							<p><a href="{{route('products.show',$review->art_id)}}" target="_blank"><img src="{{asset('/storage/image/product/'.$review->product->image1)}}" alt="상품 이미지"></a></p>
							<span>
								<em class="category">{{$review->product->category->ca_name}}</em>
								<strong>{{$review->product->title}}</strong>
								작가명 : {{$review->product->artist_name}}
							</span>
							<div class="mycolist">
								<ul>
									<li>{{$review->unickname}}</li>
									<li>{{date("Y.m.d",strtotime($review->updated_at))}}</li>
									<li><a href="#" class="on"><i class="fal fa-thumbs-up"></i> {{$review->recomend}}</a></li>
									<li><a href="#"><i class="fal fa-thumbs-down"></i> {{$review->unrecomend}}</a></li>
									<li><a href="javascript:void(0);" class="delete" onclick="review_delete({{$review->id}})"><i class="far fa-times"></i></a></li>
									<li id="commnet_{{$review->id}}"><a href="#comment_modal" class="modaltrigger modify"  ><i class="fal fa-pencil"></i></a></li>
								</ul>
								<ul>
									<li id="review_body{{$review->id}}" class="contxt kr">{{$review->review_body}}</li>
								</ul>
							</div>
						</div>
					@empty
						<p>작성하신 코멘트가 없습니다.</p>
					@endforelse

					<div class="paging_board">
						@if($reviews)
							{!! $reviews->render() !!}
						@endif
					</div>
				</div>
			</form>
			<div id="comment_modal" style="display:none;">
				<div class="comodify">
					<p><a href="" target="_blank"><img src="{{asset('/storage/image/homepage/img_pic_sm.png')}}" alt="상품 이미지"></a></p>
					<span>
						<em class="category">-</em>
						<strong>-</strong>
						<span>작가명 : <i style="font-style: normal;">-</i></span>
					</span>
					<ul>
						<li class="hidden">
						<div class="starRev">
							<span id="star_0.5" class="starR1 on"></span>
							<span id="star_1.0" class="starR2"></span>
							<span id="star_1.5" class="starR1"></span>
							<span id="star_2.0" class="starR2"></span>
							<span id="star_2.5" class="starR1"></span>
							<span id="star_3.0" class="starR2"></span>
							<span id="star_3.5" class="starR1"></span>
							<span id="star_4.0" class="starR2"></span>
							<span id="star_4.5" class="starR1"></span>
							<span id="star_5.0" class="starR2"></span>
						</div>
							<em>1.5</em>
						</li>
						<li class="hidden">
							(현재 설정한 평점 :  <i style="font-style: normal;">2.5</i> 점 )
						</li>
					</ul>
					<div class="textarea_wrap">
						<textarea name="review_body"> - </textarea>
					</div>
					<div class="review_btn_wrap">
						<button type="button" class="wbt">삭제하기</button><button type="button" class="ylbt" onclick="review_edit()">수정하기</button>	
					</div>
					
				</div>
			</div>

			<!-- //코멘트 리스트 -->
		</div>
	</div>


@endsection

@section('main_script')
<script type="text/javascript" charset="utf-8" src="{{asset('/js/modal/jquery.leanModal.min.js')}}"></script>
<script>
	$(function(){
	  
	  $('.modaltrigger').leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
	  
	  $('.modaltrigger').click(function(){
	  	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	  	var review_id = $(this).parent().attr('id');
	    review_id = review_id.replace("commnet_","");
	    
	  	 $.ajax({
               url: '/mypage/mypage_commnet_show',
               type: 'POST',
               /* send the csrf-token and the input to the controller */
               data: {_token: CSRF_TOKEN, review_id:review_id},
               dataType: 'JSON',
               /* remind that 'data' is the response of the AjaxController */
               success: function (data) { 
               	
               	$('#comment_modal .comodify p img').attr("src","{{asset('/storage/image/product/')}}" + "/" + data.product_image1);
               	$('#comment_modal .comodify span em').text(data.ca_name);
               	$('#comment_modal .comodify span strong').text(data.title);
               	$('#comment_modal .comodify span span i').text(data.artist_name);
               	$('#comment_modal .comodify ul li:last-child i').text(data.rating);
               	$('#comment_modal .comodify ul li em').text(data.rating);
               	$('#comment_modal .comodify textarea').val(data.review_body);
               	
               	$("#star_" + data.rating).addClass('active');
               	
               	$("#star_" + data.rating).parent().children('span').removeClass('on');
               	$("#star_"+data.rating).addClass('on').prevAll('span').addClass('on');
               	
               	$('.review_btn_wrap button.wbt').attr("onclick","review_delete("+review_id+")");
               	
               	$('.review_btn_wrap button.ylbt').attr("onclick","review_edit("+review_id+")");
            }
         }); 
	  });
	});
	
	function review_edit(review_id){
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var review_body = $('textarea[name="review_body"]').val();
		$.ajax({
               url: '/mypage/mypage_comment_edit',
               type: 'POST',
               /* send the csrf-token and the input to the controller */
               data: {_token: CSRF_TOKEN, review_id:review_id, review_body:review_body },
               dataType: 'JSON',
               /* remind that 'data' is the response of the AjaxController */
               success: function (data) { 
               	
               	 $('#comment_modal').css("display","none");
               	 $("#lean_overlay").css("display","none");
               	
               	$('#review_body'+review_id).text(data.review_body);
            }
         });
	}
	
	
	function review_delete(review_id){
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		
		if(confirm("정말로 삭제하시겠습니까?")){
			$.ajax({
	               url: '/mypage/mypage_comment_delete',
	               type: 'POST',
	               /* send the csrf-token and the input to the controller */
	               data: {_token: CSRF_TOKEN, review_id:review_id},
	               dataType: 'JSON',
	               /* remind that 'data' is the response of the AjaxController */
	               success: function (data) { 
	               	
	               	 alert("삭제하였습니다.");
	               	 location.href="/mypage/my_comment_list";
	            }
	         });
	    }
	}
	
	$('.starRev span').click(function(){
		$(this).parent().children('span').removeClass('on');
		$(this).addClass('on').prevAll('span').addClass('on');
		
		var rating = $(this).attr('id');
	    rating = rating.replace("star_","");
	    
	    $('#comment_modal .comodify ul li em').text(rating);
	    
		return false;
	});
	
</script>
@endsection
