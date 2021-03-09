@extends('pc.layouts.app')

@section('content')


<!--<ul>
	<li style="width:300px;"><img src="{{asset('storage/image/product/'.$product->image)}}" style="width:100%;" /></li>
	<li>작품제목: {{$product->title}}</li>
	<li>작품설명 : {{$product->introduce}}</li>
	<li>작가이름:{{$product->user->name}}</li>
	<li>가격: {{$product->cash_price}}</li>
	<li>가로사이즈: {{$product->art_width_size}}</li>
	<li>세로사이즈: {{$product->art_height_size}}</li>
</ul>
<div>
@guest
	<button type="button" class="about_cart_btn" onclick="alert('로그인하셈')">장바구니 담기</button>
@else
	<button type="button" class="about_cart_btn" onclick="cart_insert(2)">장바구니 담기</button>
@endguest
</div>
-->
<div class="sub_spot create" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
	<h2>작품보기</h2>
	<div class="search_form">
		<form id="search_item" method="get" action="{{route('products.search_list',-1)}}">
			<input type="text" name="keyword" placeholder="작가명, 회화명, 분야 등으로 검색"/>
			<span><button type="submit">search<i class="fas fa-search"></i></button></span>
		</form>
	</div>
</div>
<div id="container" class="show_product_contain">
	<div class="picview_box">
		<!-- 그림보기 -->
		<div class="betview">
			<div class="listsns_bt">
				<a href="{{ url()->previous() == env('APP_URL').'/' ? route('product_list.sel_product', 0) : url()->previous()}}"><i class="fal fa-bars"></i>list</a>
				<a href="" class="share"><i class="fal fa-share-alt"></i></a>
				<div class="sharelist">
					<a href="https://www.facebook.com/sharer/sharer.php?u={{urlencode(Request::url())}}" target="_blank" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
						<i class="fab fa-facebook-f"></i> facebook
					</a>
					<a href="https://twitter.com/share?url={{urlencode(Request::url())}}" target="_blank" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
						<i class="fab fa-twitter"></i> twitter
					</a>
					<a href="" onclick="copyTextToClipboard('{{Request::url()}}'); $('a.share').click(); return false;"><i class="fal fa-link"></i> link copy</a>
				</div>
			</div>
			<script>
				$('.sharelist').hide();
				$('a.share').click(function() {
					$(".sharelist").slideToggle(200);
					return false;
				});
			</script>
			<div class="gal_viewbox">
				<div id="rightWrap">
					<div class="rightbox" >
						<div class="artist_info">
							<p>
								<img src="{{asset('/storage/image/'.$product->user->profile_img)}}" alt=""/>
							</p>
						
							<ul>
								<li>{{$product->user->nickname}} <strong class="kr">{{$product->user->name}}</strong></li>
								<li><i class="fal fa-image"></i> 등록작품 수 : <strong class="en">{{$product_cnt->products->count()}}</strong></li>
								<li><i class="fal fa-images"></i> 판매 작품수 : <strong class="en">{{$selling_cnt}}</strong></li>
							</ul>
						</div>
						<div class="art_info_list">
							<dl class="title">
								<dt><i class="square"></i>작가명  : {{$product->artist_name}}</dt>
							</dl>
							<dl class="title">
								<dt><i class="square"></i>카테고리 / 작품명  :</dt>
								<dd><em class="cate kr">[{{$product->category->ca_name}}]</em>{{$product->title}}</dd>
							</dl>
							<div class="action_rv en">
								<ul>
									<li><i class="fas fa-heart" style="color: red; margin-left: 3px;"></i>{{number_format($product->get_like)}}</li>
									<li><i class="fal fa-eye"></i> {{number_format($product->get_hit)}}</li>
									<li class="comment_cnt"><i class="far fa-comment-alt"></i> {{number_format($product->reviews->count())}}</li>
								</ul>
							</div>
							<dl>
								<dt><i class="square"></i>제작년월  </dt>
								<dd>{{date("Y.m.d", strtotime($product->art_date))}}</dd>
								<dt><i class="square"></i>작품 사이즈  </dt>
								<dd>{{$product->art_width_size}} X {{$product->art_height_size}} (cm)</dd>
								<dt><i class="square"></i>작품 코드 </dt>
								<dd>{{sprintf('%09d',$product->id)}}</dd>
							</dl>
						</div>
						<div class="pfix">
							<div class="order_price">
								<dl>
									<dd><em class="coinic">c</em> {{number_format(round($product->coin_price,3),3)}}</dd>
									<dd><em class="kric">￦</em>{{number_format($product->cash_price)}}</dd>
									<dt>판매가 :</dt>
								</dl>
							</div>
							@if($product->sell_yn == 1)
								<div class="border_bt">
								@guest
									<button type="button" class="about_cart_btn" onclick="alert('회원만 이용 가능합니다.');"><i class="fal fa-shopping-cart"></i> 장바구니</button>
								@else
									@if(Auth::id() != $product->seller_id)
										<button type="button" class="about_cart_btn" onclick="cart_insert({{$product->id}})"><i class="fal fa-shopping-cart"></i> 장바구니</button>
									@endif
								@endguest
								
								@if(Auth::id() != $product->seller_id)
									<a href="/orders/{{$product->id}}">구매하기</a>
								@endif
								</div>
							@endif
							<!-- 베팅진행중일때 -->
							@if($product->batting_yn == 1 && $product->batting_status == 0)
								<div class="bettingbox">
									<h2>베팅예정</h2>
									<span>다음 베팅까지 남은시간</span>
									<div id="clock-ticker" class="en">
										<div class="block">
											<span class="flip-top" id="numdays">{{$days}}</span>
											<em class="label">Days</em>
										</div>
										
										<div class="block">
											<span class="flip-top" id="numhours">{{$hours}}</span>
											<em class="label">Hours</em>
										</div>
										
										<div class="block">
											<span class="flip-top" id="nummins">{{$mins}}</span>
											<em class="label">Mins</em>
										</div>
										
										<div class="block">
											<span class="flip-top" id="numsecs">{{$secs}}</span>
											<em class="label">Secs</em>
										</div>
									</div>
									<div class="remain">
										<ul>
											<li>베팅시작시간 <strong class="en">{{$product->start_time}} 00:00</strong></li>
											<li>베팅마감시간 <strong class="en">{{$product->end_time}} 23:59</strong></li>
										</ul>
									</div>
								</div>
							@elseif($product->batting_yn == 1 && $product->batting_status == 1)
								<div class="bettingbox">
									<h2>베팅하기</h2>
									<span>베팅마감까지 남은시간</span>
									<div id="clock-ticker" class="en">
										<div class="block">
											<span class="flip-top" id="numdays">{{$days}}</span>
											<em class="label">Days</em>
										</div>
										
										<div class="block">
											<span class="flip-top" id="numhours">{{$hours}}</span>
											<em class="label">Hours</em>
										</div>
										
										<div class="block">
											<span class="flip-top" id="nummins">{{$mins}}</span>
											<em class="label">Mins</em>
										</div>
										
										<div class="block">
											<span class="flip-top" id="numsecs">{{$secs}}</span>
											<em class="label">Secs</em>
										</div>
									</div>
									<div class="remain">
										<ul>
											<li>베팅시작시간 <strong class="en">{{$product->start_time}} 00:00</strong></li>
											<li>베팅마감시간 <strong class="en">{{$product->end_time}} 23:59</strong></li>
										</ul>
									</div>
									<div class="betprice_list">
										<dl class="bigprice hidden">
											<dt>베팅누적금액</dt>
											<dd><strong>-</strong></dd>
										</dl>
										<dl>
											<dt>베팅참여인원</dt>
											<dd id="bat_cnt">{{$product->get_like}}</dd>
										</dl>
	
									</div>
									<div class="betend_bt">
										@guest
											<!-- <button type="button" id="batting_btn" class="betjoin" onclick="alert('로그인을 하셔야 이용 가능합니다.');location.href='{{route('login')}}';"><i class="fal fa-coins"></i> 베팅참여하기</button> -->
											<a href="#popupcux" id="batting_btn{{$product->id}}" class="modaltrigger betjoin" onclick="batting_do({{$product->id}})">베팅참여하기</a>
										@else
											@if($batting_yn == 1)
												<!-- <button type="button" id="batting_btn" class="betjoin" onclick="batting_load({{$product->id}})"><i class="fal fa-coins"></i> 베팅참여중</button> -->
												<a href="#popupcux" id="batting_btn" class="modaltrigger betjoin" onclick="batting_load({{$product->id}})"><i class="fal fa-coins"></i> 베팅참여중</a>
											@else
												<!--<button type="button" id="batting_btn" class="betjoin" onclick="batting_do({{$product->id}})"><i class="fal fa-coins"></i> 베팅참여하기</button>-->
												<a href="#popupcux" id="batting_btn" class="modaltrigger  betjoin" onclick="batting_do({{$product->id}})"><i class="fal fa-coins"></i> 베팅참여하기</a>

											@endif
										@endguest
									</div>
								</div>
							@elseif($product->batting_yn == 1 && $product->batting_status == 2)
								<div class="bettingbox">
									<h2>베팅마감</h2>
									<span>베팅이 마감하였습니다. <br/>
											상세내역은 아래와 같습니다.
									</span>
									<div class="betprice_list end">
										<dl class="bigprice">
											<dt>베팅누적금액</dt>
											<dd><strong>{{number_format(round($product->coin_batiing, 2))}}</strong></dd>
										</dl>
										<dl>
											<dt>베팅참여인원</dt>
											<dd>{{number_format($product->get_like)}}</dd>
										</dl>
										
									</div>
								</div>
							@endif
							<!-- //베팅진행중일때 -->
							<!-- 베팅마감일때 -->
							
							<!-- //베팅마감일때 -->
						</div>
					</div>
				</div>
				<div class="leftbox">
					<div class="product_slide_wrap">
						<div class="product_img_slide">
						@for($i=1; $i<=5; $i++)
							@if($product->{'image'.$i} != NULL)
								<div>
									<img src="{{asset('storage/image/product/'.$product->{'image'.$i})}}" alt="이미지그림"/>
								</div>
							@endif
						@endfor
						</div>
					</div>
					<!-- 그림 부가정보 -->
					<div class="art_txt_tab">
						<ul>
							<li><a href="#section_1" class="on">작품소개</a></li>
							<li><a href="#section_2">작가소개</a></li>
							<li><a href="#section_3">일반 감상평</a></li>
							<li><a href="#section_4">전문가 감상평</a></li>
						</ul>
					</div>
					<div class="art_intro_txt" id="section_1">
						<h3 class="yellow_tit"><i class="fas fa-chevron-circle-right"></i> 작품소개</h3>
						<div class="kr">
							{!! str_replace("\r\n","<br />",$product->introduce) !!}
						</div>
					</div>
				
					<div class="art_artist_txt" id="section_2">
						<h3 class="yellow_tit"><i class="fas fa-chevron-circle-right"></i> 작가소개</h3>
						<div class="peobox">
							<p>
								@if($product->artist_img == '' || $product->artist_img == null)
									<img src="{{asset('storage/image/default_profile.png')}}" alt="작가프로필사진"/>
								@else
									<img src="{{asset('storage/image/'.$product->artist_img)}}" alt="작가프로필사진"/>
								@endif
							</p>
							<ul>
								<li><strong class="kr">{{$product->artist_name}}</strong></li>
							</ul>
						</div>
						<div class="petxt">
							<h4>작가소개글</h4>
							<ul>
								<li>
									{!! str_replace("\r\n","<br />",$product->artist_intro) !!}
								</li>
							</ul>
							@if($product->artist_career != '' || $product->artist_career != NULL)
							<h4>작가약력</h4>
							<ul>
								<li>
									{!! str_replace("\r\n","<br />",$product->artist_career) !!}
								</li>
							</ul>
							@endif
						</div>
					</div>
					<div id="section_3">
					<h3 class="yellow_tit"><i class="fas fa-chevron-circle-right"></i> 일반감상평<strong>{{number_format($product->reviews->count())}}개</strong></h3>
					@guest
						<div class="comment_area_box">
							<textarea readonly="readonly">로그인 하신 후 코멘트를 작성해주세요</textarea>
							<a href="{{route('login')}}"><i class="fal fa-lock"></i> 로그인</a>
						</div>
					@else
						<div class="comment_area_box">
							<textarea name="review_body" placeholder="코멘트를 작성해주세요"></textarea>
							<button type="button" onclick="review_create({{$product->id}})"><i class="fal fa-pencil"></i> 작성</a>
						</div>
					@endguest
					
					@foreach($product->reviews as $review)
						@if($review->recomend > 100)
							<div class="cobox">
								<div class="colist">
									<p><img src="{{asset('/storage/image/'.$review->profile_img)}}" alt=""/></p>
									<ul>
										<li class="kr"><em><i class="fal fa-thumbs-up"></i> 베플</em></li>
										<li class="leftnone">{{$review->unickname}}</li>
										<li>{{date("Y.m.d", strtotime($review->updated_at))}}</li>
										@guest
											<li><button type="button" class="on" onclick="alert('로그인을 하셔야 이용 가능한 기능입니다.');location.href='{{route('login')}}'"><i class="fal fa-thumbs-up"></i> {{$review->recomend}}</button></li>
											<li><button type="button" onclick="alert('로그인을 하셔야 이용 가능한 기능입니다.');location.href='{{route('login')}}'"><i class="fal fa-thumbs-down"></i> {{$review->unrecomend}}</button></li>
										@else
											@if($review->writer_id == Auth::user()->id)
												<li><button type="button" class="on" onclick="alert('본인의 코멘트를 추천할 수는 없습니다.')"><i class="fal fa-thumbs-up"></i> {{$review->recomend}}</button></li>
												<li><button type="button" onclick="alert('본인의 코멘트를 비추천할 수는 없습니다.')"><i class="fal fa-thumbs-down"></i> {{$review->unrecomend}}</button></li>
											@else
												<li><button type="button" id="recomend_btn{{$review->id}}" class="on" onclick="recomend({{$review->id}})"><i class="fal fa-thumbs-up"></i> {{$review->recomend}}</button></li>
												<li><button type="button" id="unrecomend_btn{{$review->id}}" onclick="unrecomend({{$review->id}})"><i class="fal fa-thumbs-down"></i> {{$review->unrecomend}}</button></li>
											@endif
											@if($review->writer_id == Auth::user()->id)
												<li><a href="javascript:void(0);" class="delete" onclick="review_delete({{$review->id}})"><i class="far fa-times"></i></a></li>
												<!-- <li id="commnet_{{$review->id}}"><button type="button"  onclick="location.href=#comment_modal" class="modaltrigger modify"><i class="fal fa-pencil"></i></button></li> -->
												<li data-id="{{$review->id}}"><a href="#comment_modal" class="modaltrigger  modify"><i class="fal fa-pencil"></i></a></li>
											@endif
										@endguest
									</ul>
									<ul>
										<li id="review_body{{$review->id}}" class="contxt kr">{!! str_replace("\r\n","<br />",$review->review_body) !!}</li>
									</ul>
								</div>
							</div>
						@endif
					@endforeach
					<div id="normal_review" style="display:none;">
					</div>
					@forelse($product->reviews->take(10) as $review)
						<div class="colist">
							<p><img src="{{asset('/storage/image/'.$review->profile_img)}}" alt=""/></p>
							<ul>
								<li class="none"></li>
								<li class="leftnone">{{$review->unickname}}</li>
								<li>{{date("Y.m.d", strtotime($review->updated_at))}}</li>
								@guest
											<li><button type="button" class="on" onclick="alert('로그인을 하셔야 이용 가능한 기능입니다.');location.href='{{route('login')}}'"><i class="fal fa-thumbs-up"></i> {{$review->recomend}}</button></li>
											<li><button type="button" onclick="alert('로그인을 하셔야 이용 가능한 기능입니다.');location.href='{{route('login')}}'"><i class="fal fa-thumbs-down"></i> {{$review->unrecomend}}</button></li>
								@else
									@if($review->writer_id == Auth::user()->id)
										<li><button type="button" class="on" onclick="alert('본인의 코멘트를 추천할 수는 없습니다.')"><i class="fal fa-thumbs-up"></i> {{$review->recomend}}</button></li>
										<li><button type="button" onclick="alert('본인의 코멘트를 비추천할 수는 없습니다.')"><i class="fal fa-thumbs-down"></i> {{$review->unrecomend}}</button></li>
									@else
										<li><button type="button" id="recomend_btn{{$review->id}}" class="on" onclick="recomend({{$review->id}})"><i class="fal fa-thumbs-up"></i> {{$review->recomend}}</button></li>
										<li><button type="button" id="unrecomend_btn{{$review->id}}" onclick="unrecomend({{$review->id}})"><i class="fal fa-thumbs-down"></i> {{$review->unrecomend}}</button></li>
									@endif
									@if($review->writer_id == Auth::user()->id)
										<li><a href="javascript:void(0);" class="delete" onclick="review_delete({{$review->id}})"><i class="far fa-times"></i></a></li>
										<!-- <li id="commnet_{{$review->id}}"><button type="button"onclick="location.href=#comment_modal" class="modaltrigger modify"><i class="fal fa-pencil"></i></button></li> -->
										<li data-id="{{$review->id}}"><a href="#comment_modal" class="modaltrigger  modify"><i class="fal fa-pencil"></i></a></li>
									@endif
								@endguest
							</ul>
							<ul>
								<li id="review_body{{$review->id}}" class="contxt kr">{{$review->review_body}}</li>
							</ul>
						</div>
					@empty
						<div class="non_review">자신의 의견을 남겨주세요!</div>
					@endforelse
					
					@if($product->reviews->count() > 1)
						<div style="margin-top: 30px;">
							<button type="button" id="review_jw_more_btn" style="width: 100%; background: #ddd; border: none;padding: 20px 10px;font-size: 14px;">더보기</button>
						</div>
					@endif
				</div>
				<div  id="section_4">
					<h3 class="yellow_tit"><i class="fas fa-chevron-circle-right"></i> 전문가 감상평 <strong>{{number_format($product->expert_reviews->count())}}개</strong></h3>
					@guest
						<div class="comment_area_box">
							<textarea readonly="readonly">로그인 하신 후 코멘트를 작성해주세요</textarea>
							<a href="{{route('login')}}"><i class="fal fa-lock"></i> 로그인</a>
						</div>
					@else
						@if(Auth::user()->level == 2)
							<div class="staron">
								<p>별점주기 : </p>
								<div class="starRev product_rating_star">
									<span data-rating="0.5" class="starR1 on"></span>
									<span data-rating="1.0" class="starR2"></span>
									<span data-rating="1.5" class="starR1"></span>
									<span data-rating="2.0" class="starR2"></span>
									<span data-rating="2.5" class="starR1"></span>
									<span data-rating="3.0" class="starR2"></span>
									<span data-rating="3.5" class="starR1"></span>
									<span data-rating="4.0" class="starR2"></span>
									<span data-rating="4.5" class="starR1"></span>
									<span data-rating="5.0" class="starR2"></span>
									
								</div>
								<em class="en">0.5</em>
							</div>
							<div class="comment_area_box">
								<textarea name="review_body2" placeholder="코멘트를 작성해주세요"></textarea>
								<input type="hidden" name="rating" value="0.5"  />
								<button type="button" onclick="review_create2({{$product->id}})"><i class="fal fa-pencil"></i> 작성</a>
							</div>
						@endif
					@endguest
					<div class="colum">
						@forelse($product->expert_reviews->take(10) as $key => $expert_reviews)
								<div class="columlist">
									<p><img src="{{asset('/storage/image/'.$expert_reviews->profile_img)}}" alt=""/></p>
									<ul>
										<li>칼럼니스트 : <strong>{{$expert_reviews->uname}}</strong></li>
										<!-- <li>위대한 뮤지션에 바치는 미완의 송가</li>-->
										<li id="expert_review_body{{$expert_reviews->id}}">{{$expert_reviews->review_body}}</li>
										<li class="star">
										@if($expert_reviews->rating <= 0.5)
											<i class="fas fa-star-half-alt"></i>
											<i class="fal fa-star"></i>
											<i class="fal fa-star"></i>
											<i class="fal fa-star"></i>
											<i class="fal fa-star"></i>
										@elseif($expert_reviews->rating == 1)
											<i class="fas fa-star"></i>
											<i class="fal fa-star"></i>
											<i class="fal fa-star"></i>
											<i class="fal fa-star"></i>
											<i class="fal fa-star"></i>
										@elseif($expert_reviews->rating == 1.5)
											<i class="fas fa-star"></i>
											<i class="fas fa-star-half-alt"></i>
											<i class="fal fa-star"></i>
											<i class="fal fa-star"></i>
											<i class="fal fa-star"></i>
										@elseif($expert_reviews->rating == 2)
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fal fa-star"></i>
											<i class="fal fa-star"></i>
											<i class="fal fa-star"></i>
										@elseif($expert_reviews->rating == 2.5)
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fas fa-star-half-alt"></i>
											<i class="fal fa-star"></i>
											<i class="fal fa-star"></i>
										@elseif($expert_reviews->rating == 3)
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fal fa-star"></i>
											<i class="fal fa-star"></i>
										@elseif($expert_reviews->rating == 3.5)
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fas fa-star-half-alt"></i>
											<i class="fal fa-star"></i>
										@elseif($expert_reviews->rating == 4)
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fal fa-star"></i>
										@elseif($expert_reviews->rating == 4.5)
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fas fa-star-half-alt"></i>
										@elseif($expert_reviews->rating == 5)
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
										@endif
											<span class="en">{{$expert_reviews->rating}}</span>
										</li>
									</ul>
									@auth
										@if($expert_reviews->uid == Auth::user()->id)
											<div class="expert_review_btn">
												<ul>
													<li data-id="{{$expert_reviews->id}}">
														<a href="#expert_comment_modal" class="modaltrigger modify"><i class="fal fa-pencil"></i></a>
													</li>
													<li>
													<a href="javascript:void(0);" class="delete" onclick="expert_review_delete({{$expert_reviews->id}})"><i class="far fa-times"></i></a>
													</li>
												</ul>
											</div>
										@endif
									@endauth
								</div>
								
								@if($key >= 10)
									<div style="margin-top: 30px;">
										<button type="button" style="width: 100%; background: #ddd; border: none;padding: 20px 10px;font-size: 14px;">더보기</button>
									</div>
								@endif
								
						@empty
							<div class="non_review">
								전문가 리뷰를 기다리고 있습니다.
							</div>
						@endforelse
						
					</div>
					@if($product->expert_reviews->count() > 1)
						<div style="margin-top: 30px;">
							<button type="button" id="expert_review_jw_more_btn" style="width: 100%; background: #ddd; border: none;padding: 20px 10px;font-size: 14px;">더보기</button>
						</div>
					@endif
				</div>
				<!-- //그림 부가정보 -->
				</div>

				
			</div>
		<!--// 그림보기 -->
	</div>
</div>

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
				<span data-rating="0.5" class="starR1 on"></span>
				<span data-rating="1.0" class="starR2"></span>
				<span data-rating="1.5" class="starR1"></span>
				<span data-rating="2.0" class="starR2"></span>
				<span data-rating="2.5" class="starR1"></span>
				<span data-rating="3.0" class="starR2"></span>
				<span data-rating="3.5" class="starR1"></span>
				<span data-rating="4.0" class="starR2"></span>
				<span data-rating="4.5" class="starR1"></span>
				<span data-rating="5.0" class="starR2"></span>
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

<div id="expert_comment_modal" style="display:none;">
	<div class="comodify">
		<p><a href="" target="_blank"><img src="{{asset('/storage/image/homepage/img_pic_sm.png')}}" alt="상품 이미지"></a></p>
		<span>
			<em class="category">-</em>
			<strong>-</strong>
			<span>작가명 : <i style="font-style: normal;">-</i></span>
		</span>
		<ul>
			<li>
				<div class="starRev expert_rating_star">
					<span data-rating="0.5" class="starR1"></span>
					<span data-rating="1.0" class="starR2"></span>
					<span data-rating="1.5" class="starR1"></span>
					<span data-rating="2.0" class="starR2"></span>
					<span data-rating="2.5" class="starR1"></span>
					<span data-rating="3.0" class="starR2"></span>
					<span data-rating="3.5" class="starR1"></span>
					<span data-rating="4.0" class="starR2"></span>
					<span data-rating="4.5" class="starR1"></span>
					<span data-rating="5.0" class="starR2"></span>
				</div>
				<em>-</em>
			</li>
			<li class="modal_rating">
				(현재 설정한 평점 :  <i style="font-style: normal;">-</i> 점 )
			</li>
		</ul>
		<div class="textarea_wrap">
			<textarea name="review_body"> - </textarea>
		</div>
		<div class="review_btn_wrap">
			<button type="button" class="wbt">삭제하기</button><button type="button" class="ylbt" onclick="expert_review_edit()">수정하기</button>	
		</div>

	</div>
</div>

<div class="cux_modal" id="popupcux" style="display:none;">
	<div class="cux_modal_dialog">
		<h2>베팅하기</h2>	    
	      <!-- Modal content-->
	 	<dl>
			<dt><i class="fal fa-chevron-circle-right"></i>베팅금액</dt>
			<dd><input type="text" name="batting_price" class="required kr"/></dd>
		</dl>
		<div class="footer_btn">
			<button type="button" class="cashgo">베팅하기</button>
		</div>
		      
   </div>
</div>

<div id="review_more_modal"  class="jw_modal_wrap hidden">
	<div class="jw_overlay"></div>
	<div class="jw_modal_content_wrap">
		<div class="jw_modal_content">
			<div class="jw_modal_hd">
				<h5>리뷰 전체보기</h5>
				<div><i class="fal fa-chevron-down"></i></div>
			</div>
			<div class="jw_modal_bd">
				<div class="content_box" data-offset="{{$review_offset}}" data-count="{{count($product->reviews)}}" data-proid="{{$product->id}}">
					@forelse($product->reviews->take($review_offset) as $review)
						<div class="colist">
							<p><img src="{{asset('/storage/image/'.$review->profile_img)}}" alt=""/></p>
							<ul>
								<li class="none"></li>
								<li class="leftnone">{{$review->unickname}}</li>
								<li>{{date("Y.m.d", strtotime($review->updated_at))}}</li>
								@guest
									<li><button type="button" class="on" onclick="alert('로그인을 하셔야 이용 가능한 기능입니다.');location.href='{{route('login')}}'"><i class="fal fa-thumbs-up"></i> {{$review->recomend}}</button></li>
									<li><button type="button" onclick="alert('로그인을 하셔야 이용 가능한 기능입니다.');location.href='{{route('login')}}'"><i class="fal fa-thumbs-down"></i> {{$review->unrecomend}}</button></li>
								@else
									@if($review->writer_id == Auth::user()->id)
										<li><button type="button" class="on" onclick="alert('본인의 코멘트를 추천할 수는 없습니다.')"><i class="fal fa-thumbs-up"></i> {{$review->recomend}}</button></li>
										<li><button type="button" onclick="alert('본인의 코멘트를 비추천할 수는 없습니다.')"><i class="fal fa-thumbs-down"></i> {{$review->unrecomend}}</button></li>
									@else
										<li><button type="button" id="trecomend_btn{{$review->id}}" class="on" onclick="recomend({{$review->id}})"><i class="fal fa-thumbs-up"></i> {{$review->recomend}}</button></li>
										<li><button type="button" id="tunrecomend_btn{{$review->id}}" onclick="unrecomend({{$review->id}})"><i class="fal fa-thumbs-down"></i> {{$review->unrecomend}}</button></li>
								@endif
									@if($review->writer_id == Auth::user()->id)
										<li><a href="javascript:void(0);" class="delete" onclick="review_delete({{$review->id}})"><i class="far fa-times"></i></a></li>
										<!-- <li id="commnet_{{$review->id}}"><button type="button"onclick="location.href=#comment_modal" class="modaltrigger modify"><i class="fal fa-pencil"></i></button></li> -->
										<li id="commnet_{{$review->id}}"><a href="#comment_modal" class="modaltrigger  modify"><i class="fal fa-pencil"></i></a></li>
									@endif
								@endguest
							</ul>
							<ul>
								<li id="treview_body{{$review->id}}" class="contxt kr">{{$review->review_body}}</li>
							</ul>
						</div>
					@empty
						<div class="non_review">자신의 의견을 남겨주세요!</div>
					@endforelse
				</div>
			</div>
		</div>
	</div>
</div>

<div id="expert_review_more_modal"  class="jw_modal_wrap hidden">
	<div class="jw_overlay"></div>
	<div class="jw_modal_content_wrap">
		<div class="jw_modal_content">
			<div class="jw_modal_hd">
				<h5>전문가평 전체보기</h5>
				<div><i class="fal fa-chevron-down"></i></div>
			</div>
			<div class="jw_modal_bd">
				<div class="content_box" data-offset="{{$review_offset}}" data-count="{{count($product->expert_reviews)}}" data-proid="{{$product->id}}">
					@foreach($product->expert_reviews->take($review_offset) as $expert_reviews)
						<div class="colum">
							<div class="columlist">
								<p><img src="{{asset('/storage/image/'.$expert_reviews->profile_img)}}" alt=""/></p>
								<ul>
									<li>칼럼니스트 : <strong>{{$expert_reviews->uname}}</strong></li>
									<li>{{$expert_reviews->review_body}}</li>
									<li class="star">
									@if($expert_reviews->rating >= 0.5)
										<i class="fas fa-star-half-alt"></i>
										<i class="fal fa-star"></i>
										<i class="fal fa-star"></i>
										<i class="fal fa-star"></i>
										<i class="fal fa-star"></i>
									@elseif($expert_reviews->rating >= 1)
										<i class="fas fa-star"></i>
										<i class="fal fa-star"></i>
										<i class="fal fa-star"></i>
										<i class="fal fa-star"></i>
										<i class="fal fa-star"></i>
									@elseif($expert_reviews->rating >= 1.5)
										<i class="fas fa-star"></i>
										<i class="fas fa-star-half-alt"></i>
										<i class="fal fa-star"></i>
										<i class="fal fa-star"></i>
										<i class="fal fa-star"></i>
									@elseif($expert_reviews->rating >= 2)
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fal fa-star"></i>
										<i class="fal fa-star"></i>
										<i class="fal fa-star"></i>
									@elseif($expert_reviews->rating >= 2.5)
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star-half-alt"></i>
										<i class="fal fa-star"></i>
										<i class="fal fa-star"></i>
									@elseif($expert_reviews->rating >= 3)
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fal fa-star"></i>
										<i class="fal fa-star"></i>
									@elseif($expert_reviews->rating >= 3.5)
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star-half-alt"></i>
										<i class="fal fa-star"></i>
									@elseif($expert_reviews->rating >= 4)
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fal fa-star"></i>
									@elseif($expert_reviews->rating >= 4.5)
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star-half-alt"></i>
									@elseif($expert_reviews->rating >= 5)
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
									@endif
										<span class="en">{{$expert_reviews->rating}}</span>
									</li>
								</ul>
								@auth
									@if($expert_reviews->uid == Auth::user()->id)
										<div class="expert_review_btn">
											<ul>
												<li data-id="{{$expert_reviews->id}}">
													<a href="#expert_comment_modal" class="modaltrigger modify"><i class="fal fa-pencil"></i></a>
												</li>
												<li>
												<a href="javascript:void(0);" class="delete" onclick="expert_review_delete({{$expert_reviews->id}})"><i class="far fa-times"></i></a>
												</li>
											</ul>
										</div>
									@endif
								@endauth
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>

<style>

.colist ul li:nth-of-type(4) button,
.colist ul li:nth-of-type(5) button{color:#ccc;background: transparent; border: none;}
.colist ul li:nth-of-type(1) em{padding:7px;border-radius:5px;background:#fea803;color:#fff;}

.colist ul li button.on,
.colist ul li button.on,
.colist ul li button:hover,
.colist ul li button:hover{color:#fea803;    background: transparent; border: none;}
.colist ul li button i{display:inline-block;margin-right:5px;}

.comment_area_box > button{display:inline-block;float:left;border:1px solid #d3d1d1;padding:0 20px;line-height:35px;border-radius:5px;color:#888;background:#fff;}
.comment_area_box > button:hover{border:1px solid #222;color:#222;}
.comment_area_box > button:hover i{color:#222}

.colist ul li:nth-of-type(6) button,
.colist ul li:nth-of-type(7) button{display:block;background:#fff;border:1px dotted #ddd;border-radius:5px;width:29px;height:29px;text-align:center;color:#888;}

.colist ul li:nth-of-type(6) button i,
.colist ul li:nth-of-type(7) button i{margin-right:0px;}

#normal_review{border-bottom: 1px dotted #a9a9a9;background: #fafafa;}

.border_bt button{float:left;border-radius:5px;text-align:center;}
.border_bt button:first-child{margin-right:2%;width:38%;line-height:49px;border:1px solid #d4d4d4;font-size:15px;color:#888888;}
.border_bt button:hover:first-child{border:1px solid #222;color:#222;}
.border_bt button:hover:first-child i{color:#222;}
.border_bt button:last-child{width:58%;font-size:18px;color:#fff;background:#fea803;border:1px solid #c88505;line-height:49px;}
.betend_bt button{display:block;width:100%;padding:15px 0;border:none;}
.betend_bt button.betjoin{background:#fe3803;color:#fff;font-size:18px;}
.non_review{background: #fafafa; font-size: 14px; color: #777; padding: 45px 0;}


</style>

<script src="{{asset('/js/pc/review_infinite_scroll.js')}}"></script>


@endsection
