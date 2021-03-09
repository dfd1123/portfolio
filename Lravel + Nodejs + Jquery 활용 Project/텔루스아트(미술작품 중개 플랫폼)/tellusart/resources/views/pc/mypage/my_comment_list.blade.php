@extends('pc.layouts.app')

@section('content')
	<div class="sub_spot mypage" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
		<h2>마이페이지</h2>
	</div>
	<div id="container" class="mypage_comment_contain">
		@include('pc.mypage.include.my_common')
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
									<li><a href="javascript:void(0);" class="delete" onclick="mypage_review_delete({{$review->id}})"><i class="far fa-times"></i></a></li>
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
						<button type="button" class="wbt">삭제하기</button><button type="button" class="ylbt" onclick="mypage_review_edit()">수정하기</button>	
					</div>
					
				</div>
			</div>

			<!-- //코멘트 리스트 -->
		</div>
	</div>


@endsection


