@extends('pc.layouts.app')

@section('content')
<div class="sub_spot mypage" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
	<h2>마이페이지</h2>
</div>
<div id="container">
	@include('pc.mypage.include.my_common')
		<div class="cartbox">
			<h3 class="mytit">내 작품</h3>
			<span class="titm_txt">총 <strong>{{$product_cnt}}건</strong>의 등록된 내 작품이 있습니다.</span>
		</div>
	
		<!-- 내 작품 리스트 -->
		<div class="myartbox">
			<div class="glist">
			@forelse($products->limit(16)->get() as $product)
				<div id="pro_{{$product->id}}" class="item">
					<div class="myart_img_wrap">
						<a href="{{route('products.show',$product->id)}}"><p class="is-loading"><img src="{{asset('/storage/image/product/'.$product->image1)}}" alt=""/></p></a>
						@if($product->sell_yn == 0)
							<div class="intro_smd wait">판매대기</div>
						@elseif($product->sell_yn == 1)
							<div class="intro_smd apply">판매승인</div>
						@elseif($product->sell_yn == 2)
							<div class="intro_smd reject">판매거절</div>
						@elseif($product->sell_yn == 3)
							<div class="intro_smd complete">판매완료</div>
						@endif
					</div>
					<div class="peo_txt">
						<p><img src="{{asset('/storage/image/'.Auth::user()->profile_img)}}" alt="작가프로필사진"/></p>
						<ul>
							<li>{{$product->title}}</li>
							<li><em>작가명 : {{$product->artist_name}}</em><em>사이즈 : {{$product->art_width_size}} X {{$product->art_height_size}}cm</em></li>
							<li><em>등록 날짜 : {{explode(' ',$product->created_at)[0]}}</em></li>
							<li>
								@if($product->batting_yn == 1)
									@if($product->batting_status == 0)
										<em>베팅상태 : <b class="ready">예정</b></em>
									@elseif($product->batting_status == 1)
										<em>베팅상태 : <b class="ing">진행중</b></em>
									@elseif($product->batting_status == 2)
										<em>베팅상태 : <b class="end">마감</b></em>
									@endif
								@endif
							</li>
						</ul>
					</div>
					<div class="action_rv en">
						<ul>
							<li><a href=""><i class="far fa-heart"></i><i class="fas fa-heart"></i></a>{{$product->battings->count()}}</li>
							<li><a href=""><i class="far fa-comment-alt"></i></a> {{$product->reviews->count()}}</li>
						</ul>
					</div>
					<div class="price en">
						<ul>
							<li><em class="coinic">c</em> {{number_format(round($product->coin_price,3),3)}}</li>
							<li><em class="kric">￦</em> {{$product->cash_price}}</li>
						</ul>
					</div>
					<div class="del">
						@if($product->sell_yn != 3)
							@if($product->batting_yn == 1)
								@if($product->batting_status != 1)
									<a href="{{route('products.edit',$product->id)}}">수정</a><a href="{{route('product.delete',$product->id)}}">삭제</a>
								@endif
							@else
								<a href="{{route('products.edit',$product->id)}}">수정</a><a href="{{route('product.delete',$product->id)}}">삭제</a>
							@endif
						@else
							<a href="#" class="sell_complete">판매완료</a>
						@endif
					</div>
					<div class="reject">
						@if($product->sell_yn == 2)
							<button type="button" class="myart_reject_btn" >거절사유 보기</button>
							<input type="hidden" class="reject_infor_soundonly" value="{{ $product->reject_infor }}">
						@endif
					</div>
				</div>
			@empty
				해당되는 작품이 없습니다.	
			@endforelse
			</div>
		</div>
	</div>
</div>
<div id="myart_reject_modal"  class="jw_modal_wrap hidden">
	<div class="jw_overlay"></div>
	<div class="jw_modal_content_wrap like_cux">
		<div class="jw_modal_content">
			<div class="jw_modal_hd">
				<h5>거절 사유</h5>
				<div><i class="fal fa-chevron-down"></i></div>
			</div>
			<div class="jw_modal_bd">
				<div class="content_box">
					<h5><i class="fal fa-chevron-circle-right"></i>거절 사유</h5>
					<textarea name="reject_infor" class="tsa-textarea" style="width: 100%;height: 149px;font-size: 18px;" readonly>ㅁㄴㅇㄹ</textarea>
				</div>
			</div>
		</div>
	</div>
</div>



@endsection

@section('script')

<script>
	//$('.modaltrigger').leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
	
	$('button.myart_reject_btn').click(function(){
		$("#myart_reject_modal textarea[name='reject_infor']").val($(this).siblings('.reject_infor_soundonly').val());
		$('#myart_reject_modal').removeClass('hidden');
		setTimeout(function() {
			$('#myart_reject_modal').addClass('active');
		}, 100);
	});
	
	$('#myart_reject_modal .jw_overlay, #order_cancel_reason_modal .jw_modal_hd>div').click(function(){
		$('#myart_reject_modal').removeClass('active');
		
		setTimeout(function() {
			$('#myart_reject_modal').addClass('hidden');
		}, 300);
	});
	
	
</script>

@endsection

