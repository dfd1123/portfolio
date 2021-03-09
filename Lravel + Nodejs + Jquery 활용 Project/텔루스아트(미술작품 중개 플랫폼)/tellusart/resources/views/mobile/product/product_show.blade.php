@extends('mobile.layouts.app')

@section('content')

<div class="sub-container">
	
	<div class="betview">
	
	<div class="gal_viewbox">
		<div class="leftbox">
			<div class="title">
				<h2><em class="cate kr">[{{$product->category->ca_name}}]</em>{{$product->title}}</h2>
				<div class="product_slide_wrap">
					<div class="product_img_slide">
					@for($i=1; $i<=5; $i++)
						@if($product->{'image'.$i} != NULL)
							<div>
								<p><img src="{{asset('storage/image/product/'.$product->{'image'.$i})}}" alt="이미지그림"/></p>
							</div>
						@endif
					@endfor
					<div>
						<p><img src="{{asset('storage/image/product/0XqVDDrROUeN9XavTTen8vrFB0GR3NhaGzHD3Ihy.jpeg')}}" alt="이미지그림"/></p>
					</div>
					</div>
				</div>
				<div class="listsns_bt en">
					<a href="" class="share hidden"><img src="{{asset('/storage/image/mobile/ic_share.png')}}" alt=""/>share</a>
					<div class="sharelist en">
						<a href=""><img src="{{asset('/storage/image/mobile/ic_facebook.png')}}" alt=""/>facebook</a>
						<a href=""><img src="{{asset('/storage/image/mobile/ic_twitter.png')}}" alt=""/>twitter</a>
						<a href=""><img src="{{asset('/storage/image/mobile/ic_link.png')}}" alt=""/>link copy</a>
					</div>
				</div>
			</div>
			<!-- 모바일베팅고정 -->
			{{--
			<div class="pfix">
				@if($product->batting_yn == 1)
				<!-- 베팅참가가능시 -->
				<div class="possible "><button type="submit" class="pixbet"><img src="{{asset('/storage/image/mobile/ic_betting.png')}}" alt=""/>베팅하기</button></div>
				@else
				<!-- 베팅참가 불가능시 -->
				<div class="impossible hidden"><span>베팅마감</span></div>
				@endif
			</div>
			--}}
			<!-- //모바일베팅고정 -->
			<!-- 작가 및 베팅 -->
			<div id="rightWrap">
				<div class="rightbox" >
					<div class="artist_info">
						<p><img src="{{asset('/storage/image/'.$product->artist_img)}}" alt=""/></p>
					
						<ul>
							<li>{{$product->user->nickname}} <strong class="kr">{{$product->artist_name}}</strong></li>
							<li> 등록작품 수 : <strong class="en">{{$product_cnt->products->count()}}</strong></li>
							<li>판매 작품수 : <strong class="en">{{$selling_cnt}}</strong></li>
						</ul>
					</div>
					<div class="art_info_list">
						
						<div class="action_rv en">
							<ul>
								<li><a href=""><img src="{{asset('/storage/image/mobile/ic_heart.png')}}" alt=""/> <!--<img src="{{asset('/storage/image/mobile/ic_heart_on.png')}}" alt=""/>--></a>{{number_format($product->get_like)}}</li>
								<li><img src="{{asset('/storage/image/mobile/ic_comment.png')}}" alt="코멘트수"/><span class="comment_cnt">{{number_format($product->reviews->count())}}</span></li>
								<li><img src="{{asset('/storage/image/mobile/ic_viewcount.png')}}" alt="뷰수"/>{{number_format($product->get_hit)}}</li>
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
					
					<div class="order_price">
						<dl>
							<dt>판매가 :</dt>
							<dd><em class="coinic">c</em> {{number_format(round($product->coin_price,3),3)}}</dd>
							<dd><em class="kric">￦</em>{{number_format($product->cash_price)}}</dd>
						</dl>
					</div>
					
					@if($product->sell_yn == 1)
						<div class="border_bt">
						@guest
						<a onclick="alert('회원만 이용 가능합니다.');">장바구니</a>
						@else
							@if(Auth::id() != $product->seller_id)
							<a onclick="cart_insert({{$product->id}})">장바구니</a>
							@endif
						@endguest
						
						@if(Auth::id() != $product->seller_id)
							<a href="/orders/{{$product->id}}">구매하기</a>
						@endif
						</div>
					@endif
				
					<!-- 베팅진행중일때 -->
					@if($product->batting_yn == 1 && $product->batting_status == 0)
					<div class="bettingbox ">
						<h2>베팅예정중</h2>
						<span>베팅시작까지 남은시간</span>
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
					</div>
					<!-- //베팅진행중일때 -->
					@elseif($product->batting_yn == 1 && $product->batting_status == 1)
					<div class="bettingbox ">
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
						<div class="betprice_list end">
							<dl class="bigprice">
								<dt>베팅누적금액</dt>
								<dd><strong>-</strong></dd>
							</dl>
							<dl>
								<dt>베팅참여인원</dt>
								<dd>{{$product->get_like}}</dd>
							</dl>
						</div>
						<div class="betend_bt">
							@guest
							<a class="betjoin" onclick="alert('회원만 이용 가능합니다.');" style="font-size: initial; text-align: center;">베팅참여하기</a>
							@else
								@if($batting_yn == 1)
									<a href="#popupcux" class="modaltrigger betjoin" onclick="batting_load({{$product->id}})" style="font-size: initial; text-align: center;">베팅참여중</a>
								@else
									<a href="#popupcux" class="modaltrigger betjoin" onclick="batting_do({{$product->id}})" style="font-size: initial; text-align: center;">베팅참여하기</a>
								@endif
							@endguest
						</div>
					</div>
					@elseif($product->batting_yn == 1 && $product->batting_status == 2)
					<!-- 베팅마감일때 -->
					<div class="bettingbox hidden ">
						<h2>베팅마감</h2>
						<span>베팅마감하였습니다. <br/>
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
					<!-- //베팅마감일때 -->
					@endif
				</div>
			</div>
			<!-- //작가 및 베팅 -->
			<!-- 그림 부가정보 -->
			<div class="art_txt_tab">
				<ul>
					<li><a href="#section_1" class="on">작품소개</a></li>
					<li><a href="#section_2">작가소개</a></li>
					<li><a href="#section_3">일반 감상평</a></li>
					<li><a href="#section_4">전문가 감상평</a></li>
				</ul>
			</div>
			<div class="art_intro_txt" id="section_1" style="background:transparent; border:none;padding-top: 10px; margin-bottom: 0;padding-bottom: 10px;">
				<h3 class="yellow_tit"><i><img src="{{asset('/storage/image/mobile/img_yellowic.png')}}" alt=""/></i> 작품소개</h3>
				<div class="peobox kr">
					{!! str_replace("\r\n","<br />",$product->introduce) !!}
				</div>
			</div>
		
			<div class="art_artist_txt" id="section_2">
				<h3 class="yellow_tit"><i><img src="{{asset('/storage/image/mobile/img_yellowic.png')}}" alt=""/></i> 작가소개</h3>
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
					<h4>작가약력</h4>
					<ul>
						<li>
							{!! str_replace("\r\n","<br />",$product->artist_career) !!}
						</li>
					</ul>
				</div>
			</div>

			<div id="section_3">
				<h3 class="yellow_tit"><i><img src="{{asset('/storage/image/mobile/img_yellowic.png')}}" alt=""/></i> 일반감상평<strong>{{number_format($product->reviews->count())}}개</strong></h3>
				
				@guest
				<div class="comment_area_box">
					<textarea readonly="readonly" style="resize:none;">로그인 하신 후 코멘트를 작성해주세요</textarea>
					<button type="button" onclick="document.location = '{{route('login')}}';">로그인</button>
				</div>
				@else
				<div class="comment_area_box">
					<textarea name="review_body" placeholder="코멘트를 작성해주세요" style="resize:none;"></textarea>
					<button type="button" onclick="review_create({{$product->id}})"><i class="fal fa-pencil"></i> 작성</a>
				</div>
				@endguest

				<div class="cobox">
					@foreach($product->reviews as $review)
						@if($review->recomend > 100)
							<div class="colist">
								<p><img src="{{asset('/storage/image/'.$review->profile_img)}}" alt=""/></p>
								<ul class="cin">
									<li class="kr"><em> 베플</em></li>
									<li>{{$review->unickname}}</li>
									<li>{{date("Y.m.d", strtotime($review->updated_at))}}</li>

									@guest
										<li><a href="javascript:void(0)"  class="on" onclick="alert('로그인을 하셔야 이용 가능한 기능입니다.');location.href='{{route('login')}}'"><i><img src="{{asset('/storage/image/mobile/ic_up.png')}}" alt=""/></i> {{$review->recomend}}</a></li>
										<li><a href="javascript:void(0)" onclick="alert('로그인을 하셔야 이용 가능한 기능입니다.');location.href='{{route('login')}}'"><i><img src="{{asset('/storage/image/mobile/ic_down.png')}}" alt=""/></i> {{$review->unrecomend}}</a></li>
									@else
										@if($review->writer_id == Auth::user()->id)
										<li><a href="javascript:void(0)" class="on" onclick="alert('본인의 코멘트를 추천할 수는 없습니다.')"><i><img src="{{asset('/storage/image/mobile/ic_up.png')}}" alt=""/></i> {{$review->recomend}}</a></li>
										<li><a href="javascript:void(0)" onclick="alert('본인의 코멘트를 비추천할 수는 없습니다.')"><i><img src="{{asset('/storage/image/mobile/ic_down.png')}}" alt=""/></i> {{$review->unrecomend}}</a></li>
										@else
										<li><a href="javascript:void(0)" id="recomend_btn{{$review->id}}" class="on" onclick="recomend({{$review->id}})"><i><img src="{{asset('/storage/image/mobile/ic_up.png')}}" alt=""/></i> {{$review->recomend}}</a></li>
										<li><a href="javascript:void(0)" id="unrecomend_btn{{$review->id}}" onclick="unrecomend({{$review->id}})"><i><img src="{{asset('/storage/image/mobile/ic_down.png')}}" alt=""/></i> {{$review->unrecomend}}</a></li>
										@endif
										@if($review->writer_id == Auth::user()->id)
										<li><a href="javascript:void(0)" class="delete" onclick="review_delete({{$review->id}})"><img src="{{asset('/storage/image/mobile/ic_sclose.png')}}" alt=""/></a></li>
										<li><a href="javascript:void(0)" class="modify" onclick="review_load({{$review->id}})"><img src="{{asset('/storage/image/mobile/ic_smodify.png')}}" alt=""/></a></li><!-- 모달구현 -->
										@endif
									@endguest
								</ul>
								<ul>
									<li id="review_body{{$review->id}}" class="contxt kr">{!! str_replace("\r\n","<br />",$review->review_body) !!}</li>
								</ul>
								@auth
									@if($review->writer_id == Auth::user()->id)
										<div>
											<div>
												<button type="button" class="delete" onclick="review_delete({{$review->id}})"><img src="{{asset('/storage/image/mobile/ic_smodify.png')}}" alt=""/>수정</button>
											</div>
											<div>
												<button type="button" class="modify" onclick="review_load({{$review->id}})"><img src="{{asset('/storage/image/mobile/ic_sclose.png')}}" alt=""/>삭제</button>
											</div>
										</div>
									@endif
								@endauth
							</div>
						@endif
					@endforeach

					<!-- 사용자 댓글 템플릿 -->
					<template id="template-colist">
						<div class="colist">
							<p><img class="colist-profile-img" src="" alt=""/></p>
							<ul class="cin">
								<li class="best_comment kr" style="display:none;"><em> 베플</em></li>
								<li class="colist-nickname"></li>
								<li class="colist-date"></li>
							</ul>
							<ul class="bottom_sub">
								<li class="recommend"><a href="javascript:void(0)" class="on" onclick="alert('본인의 코멘트를 추천할 수는 없습니다.')"><i><img src="/storage/image/mobile/ic_up.png" alt=""/></i> <span class="colist-recomend"></span></a></li>
								<li class="unrecommend"><a href="javascript:void(0)" onclick="alert('본인의 코멘트를 비추천할 수는 없습니다.')"><i><img src="/storage/image/mobile/ic_down.png" alt=""/></i> <span class="colist-unrecomend"></span></a></li>
								<li class="delete_li"><a href="javascript:void(0)" class="delete"><img src="/storage/image/mobile/ic_sclose.png" alt=""/></a></li>
								<li class="modify_li"><a href="#popupcux2" class="modaltrigger modify"><img src="/storage/image/mobile/ic_smodify.png" alt=""/></a></li>
							</ul>
							<ul>
								<li class="contxt kr colist-review"></li>
							</ul>
						</div>
					</template>

					@forelse($product->reviews->take($review_offset) as $review)
						<div class="colist" style="{{$loop->index >= 10 ? 'display: none' : ''}}">
							<p><img src="{{asset('/storage/image/'.$review->profile_img)}}" alt=""/></p>
							<ul class="cin">
								<li class="best_comment kr" style="display:none;"><em> 베플</em></li>
								<li class="colist-nickname">{{$review->unickname}}</li>
								<li class="colist-date">{{date("Y.m.d", strtotime($review->updated_at))}}</li>
							</ul>
							<ul class="bottom_sub">
								@guest
									<li class="recommend"><a href="javascript:void(0)" class="on" onclick="alert('로그인을 하셔야 이용 가능한 기능입니다.');location.href='{{route('login')}}'"><i><img src="{{asset('/storage/image/mobile/ic_up.png')}}" alt=""/></i> {{$review->recomend}}</a></li>
									<li class="unrecommend"><a href="javascript:void(0)" onclick="alert('로그인을 하셔야 이용 가능한 기능입니다.');location.href='{{route('login')}}'"><i><img src="{{asset('/storage/image/mobile/ic_down.png')}}" alt=""/></i> {{$review->unrecomend}}</a></li>
								@else
									@if($review->writer_id == Auth::user()->id)
									<li class="recommend"><a href="javascript:void(0)" class="on" onclick="alert('본인의 코멘트를 추천할 수는 없습니다.')"><i><img src="{{asset('/storage/image/mobile/ic_up.png')}}" alt=""/></i> {{$review->recomend}}</a></li>
									<li class="unrecommend"><a href="javascript:void(0)" onclick="alert('본인의 코멘트를 비추천할 수는 없습니다.')"><i><img src="{{asset('/storage/image/mobile/ic_down.png')}}" alt=""/></i> {{$review->unrecomend}}</a></li>
									@else
									<li class="recommend"><a href="javascript:void(0)" id="recomend_btn{{$review->id}}" class="on" onclick="recomend({{$review->id}})"><i><img src="{{asset('/storage/image/mobile/ic_up.png')}}" alt=""/></i> {{$review->recomend}}</a></li>
									<li class="unrecommend"><a href="javascript:void(0)" id="unrecomend_btn{{$review->id}}" onclick="unrecomend({{$review->id}})"><i><img src="{{asset('/storage/image/mobile/ic_down.png')}}" alt=""/></i> {{$review->unrecomend}}</a></li>
									@endif
									@if($review->writer_id == Auth::user()->id)
									<li class="delete_li"><a href="javascript:void(0)" class="delete" onclick="review_delete({{$review->id}})"><img src="{{asset('/storage/image/mobile/ic_sclose.png')}}" alt=""/></a></li>
									<li class="modify_li"><a href="#popupcux2" class="modaltrigger modify" onclick="review_load({{$review->id}})"><img src="{{asset('/storage/image/mobile/ic_smodify.png')}}" alt=""/></a></li>
									@endif
								@endguest
							</ul>
							<ul>
								<li id="review_body{{$review->id}}" class="contxt kr">{!! str_replace("\r\n","<br />",$review->review_body) !!}</li>
							</ul>
						</div>
					@empty
					<div class="non_review">자신의 의견을 남겨주세요!</div>
					@endforelse

					@if($product->reviews->count() > 1)
					<div>
						<a href="javascript:$('#section_3 .colist').show();" class="more" style="background: #fbfbfb;">더보기<img src="{{asset('/storage/image/mobile/ic_plust.png')}}" alt=""/></a>
					</div>
					@endif
				</div>
			</div>

			<div id="section_4">
				<h3 class="yellow_tit"><i><img src="{{asset('/storage/image/mobile/img_yellowic.png')}}" alt=""/></i> 전문가 감상평 <strong>{{number_format($product->expert_reviews->count())}}개</strong></h3>
				@guest
					<div class="comment_area_box">
						<textarea readonly="readonly">로그인 하신 후 코멘트를 작성해주세요</textarea>
						<button type="button" onclick="document.location = '{{route('login')}}';">로그인</button>
					</div>
				@else
					@if(Auth::user()->level == 2)
					<div class="staron">
						<p>별점주기 : </p>
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
						<em class="en">0.5</em>
					</div>
					<div class="comment_area_box">
						<textarea name="review_body2" placeholder="코멘트를 작성해주세요"></textarea>
						<input type="hidden" name="rating" value="0.5">
						<button type="button" onclick="review_create2({{$product->id}})">작성</button>
					</div>
					@endif
				@endguest

				<div class="colum">
					@forelse($product->expert_reviews->take($review_offset) as $key => $expert_reviews)
					<div class="columlist" style="{{$loop->index >= 10 ? 'display: none' : ''}}">
						<ul>
							<li><img src="{{asset('/storage/image/'.$expert_reviews->profile_img)}}" alt=""/> 칼럼니스트 : <strong>{{$expert_reviews->uname}}</strong>
							</li>
							<li>{{$expert_reviews->review_body}}</li>
							<li class="star">
								@if($expert_reviews->rating <= 0.5)
								<i><img src="{{asset('/storage/image/mobile/ic_star_half.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								@elseif($expert_reviews->rating == 1)
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								@elseif($expert_reviews->rating == 1.5)
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_half.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								@elseif($expert_reviews->rating == 2)
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								@elseif($expert_reviews->rating == 2.5)
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_half.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								@elseif($expert_reviews->rating == 3)
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								@elseif($expert_reviews->rating == 3.5)
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_half.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								@elseif($expert_reviews->rating == 4)
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
								@elseif($expert_reviews->rating == 4.5)
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_half.png')}}" alt=""/></i>
								@elseif($expert_reviews->rating == 5)
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
								@endif
								<span class="en">{{$expert_reviews->rating}}</span>
							</li>
						<ul>
							{{--
						@auth
							@if($expert_reviews->uid == Auth::user()->id)
								<div class="expert_review_btn">
									<ul>
										<li data-id="{{$expert_reviews->uid}}">
											<a href="#expert_comment_modal" class="modaltrigger modify"><i class="fal fa-pencil"></i></a>
										</li>
										<li>
										<a href="javascript:void(0);" class="delete" onclick="expert_review_delete({{$expert_reviews->id}})"><i class="far fa-times"></i></a>
										</li>
									</ul>
								</div>
							@endif
						@endauth
					--}}
					</div>
					<!-- 전문가 리뷰 수정 -->
					<div class="cux_modal" id="popupcux5" style="display:none;">
						<div class="cux_modal_dialog">
							<h2>코멘트 수정</h2>	    
								<!-- Modal content-->
							<dl>
								<dt><dt><i class="fal fa-chevron-circle-right"></i>별점주기</dt></dt>
								<dt>
									<div class="staron">
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
										<input type="hidden" name="rating" value="0.5"  />
									</div>
								</dt>
								<dt><i class="fal fa-chevron-circle-right"></i>내용</dt>
								<dt><textarea name="review_body" style="width: 98%; height: 100px;"></textarea></dt>
							</dl>
							<div class="footer_btn">
								<button type="button" class="cashgo">수정하기</button>
							</div>
						</div>
					</div>
					<!-- 전문가 댓글 템플릿 -->
					<template id="template-columlist">
						<div class="columlist">
							<ul>
								<li><img class="columlist-profile-img" src="" alt=""/> 칼럼니스트 : <strong class="columlist-nickname"></strong></li>
								<li class="columlist-review"></li>
								<li class="star">
									<span class="columlist-star05" style="display: none">
										<i><img src="{{asset('/storage/image/mobile/ic_star_half.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
									</span>
									<span class="columlist-star1" style="display: none">
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
									</span>
									<span class="columlist-star15" style="display: none">
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_half.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
									</span>
									<span class="columlist-star2" style="display: none">
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
									</span>
									<span class="columlist-star25" style="display: none">
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_half.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
									</span>
									<span class="columlist-star3" style="display: none">
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
									</span>
									<span class="columlist-star35" style="display: none">
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_half.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
									</span>
									<span class="columlist-star4" style="display: none">
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
									</span>
									<span class="columlist-star45" style="display: none">
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_half.png')}}" alt=""/></i>
									</span>
									<span class="columlist-star5" style="display: none">
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
										<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
									</span>
									<span class="en columlist-star-num">1.5</span>
								</li>
							<ul>
						</div>
					</template>

					@empty
						<div class="non_review">
							전문가 리뷰를 기다리고 있습니다.
						</div>
					@endforelse

					@if($product->expert_reviews->count() > 1)
					<div>
						<a href="javascript:$('#section_4 .columlist').show();" class="more">더보기<img src="{{asset('/storage/image/mobile/ic_plust.png')}}" alt=""/></a>
					</div>
					@endif
				</div>
			</div>
			<!-- //그림 부가정보 -->
		</div>
	
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

<div class="cux_modal" id="popupcux2" style="display:none;">
	<div class="cux_modal_dialog">
		<h2>코멘트 수정</h2>	    
			<!-- Modal content-->
		<dl>
			<dt><i class="fal fa-chevron-circle-right"></i>내용</dt>
			<dt><textarea name="review_body" style="width: 98%; height: 100px;"></textarea></dt>
		</dl>
		<div class="footer_btn">
			<button type="button" class="cashgo">수정하기</button>
		</div>
	</div>
</div>

<script>
$('.sharelist').hide();
$('a.share').click(function() {
	$(".sharelist").slideToggle(100);
	return false;
});

var art_tab_scrollTop = $('.art_txt_tab').offset().top;
var section_1_scrollTop = $('#section_1').offset().top;
var section_2_scrollTop = $('#section_2').offset().top;
var section_3_scrollTop = $('#section_3').offset().top;
var section_4_scrollTop = $('#section_4').offset().top;
console.log(section_1_scrollTop);
console.log(section_2_scrollTop);
console.log(section_3_scrollTop);
console.log(section_4_scrollTop);

$('#wrap').scroll(function(event){

	if($('#wrap').scrollTop() > art_tab_scrollTop){
		$(".art_txt_tab").css({ "position": "fixed", "top": "0", "left": "0", "margin-top": "0", "z-index": "10"});
		$(".art_txt_tab ul li a").css("border-radius", "none");
	}else{
		$(".art_txt_tab").css({ "position": "", "top": "", "left": "", "margin-top": "20px", "z-index": "1" });
		$(".art_txt_tab ul li a").css("border-radius", "");
	}

	if(0 < $('#wrap').scrollTop() && $('#wrap').scrollTop() < section_1_scrollTop){
		$('.art_txt_tab ul li a').removeClass('on');
		$('.art_txt_tab ul li:nth-child(1) a').addClass('on');
		console.log('tete');
	}else if(section_1_scrollTop < $('#wrap').scrollTop()  && $('#wrap').scrollTop() < section_2_scrollTop){
		$('.art_txt_tab ul li a').removeClass('on');
		$('.art_txt_tab ul li:nth-child(2) a').addClass('on');
	}else if(section_2_scrollTop < $('#wrap').scrollTop()  && $('#wrap').scrollTop() < section_3_scrollTop){
		$('.art_txt_tab ul li a').removeClass('on');
		$('.art_txt_tab ul li:nth-child(3) a').addClass('on');
	}else if(section_3_scrollTop < $('#wrap').scrollTop() && $('#wrap').scrollTop() < section_4_scrollTop){
		$('.art_txt_tab ul li a').removeClass('on');
		$('.art_txt_tab ul li:nth-child(4) a').addClass('on');
	}
	
});

$(function(){
	$('#wrap').on('touchmove', function() {
		if($('#wrap').scrollTop() > 350){
			$(".pfix").css({ "display": "block","position": "fixed", "bottom": "65px"});
		}else{
			$(".pfix").css({ "display": "none" });
		}
	});
});
$(function(){
	$('#wrap').on('touchmove', function() {
		if($('#wrap').scrollTop() > 790) {
			$(".art_txt_tab").css({ "position": "fixed", "top": "0px", "left": "0px", "margin-top": "-1px", "z-index": "50" });
			$(".art_txt_tab").addClass("fix");
		}else{
			$(".art_txt_tab").css({ "position": "relative", "top": "0px" });
			$(".art_txt_tab").removeClass("fix");
		}
	});
});
$('.starRev span').click(function(){
	$(this).parent().children('span').removeClass('on');
	$(this).addClass('on').prevAll('span').addClass('on');
	
	var rating = $(this).attr('id');
	rating = rating.replace("star_","");
	
	$('.staron em.en').text(rating);
	$('input[name="rating"]').val(rating);
	return false;
});
	
$(function(){
	$(".art_txt_tab ul li a").click(function(e){
		var posY = $($(this).attr("href")).position();
		$(".art_txt_tab ul li a").removeClass('on');
        $(this).addClass('on');
		$('#wrap').stop().animate({'scrollTop':posY.top - 0},600, function() {
			// Animation complete.
			$('#wrap')[0].dispatchEvent(new Event('touchmove'));
		});
		return false;
	}); 
});

$(function(){
	$('.modaltrigger').leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
});

function expert_review_delete(id) {
    if (confirm("정말로 전문가 리뷰를 삭제하시겠습니까?")) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: "/mypage/mobile/mypage_expertreview_delete",
            type: "POST",
            data: { _token: CSRF_TOKEN, id: id },
            dataType: "JSON"
        })
            .done(function() {
                $('#my-expertreview-item-' + id).remove();
				$("#my_expertreview_expertreview_cnt").text($("#my-expertreview-list").children().length + '개');
                alert('전문가 리뷰 삭제 완료');
            });
    }
}

</script>

@endsection