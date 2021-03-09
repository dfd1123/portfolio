@extends('pc.layouts.app')

@section('content')
	<div class="spot">
		<div id="touchSlider6">
			<ul>
				<li style="background:url(/storage/image/slide/{{$slide_default->slide_file}}) no-repeat center top; background-size: cover; background-color: #222;background-color: #222;">
					<div class="vis01">
						<h2 class="en">Tellus Art<br/>Platform</h2>
						<p><img src="{{asset('storage/image/homepage/img_tellus.png')}}" alt=""/></p>
						<span>
							텔루스 아트는 미술시장의 새로운 유통 패러다임을 구축합니다.<br/>
							플랫폼을 통해 다양한 스펙트럼의 작품,작가들을 만날 수 있습니다.<br/>
							또한, 모두가 미술분야에 쉽게 접근할 수 있도록 여러가지 서비스를 제공합니다.<br/>
						</span>
					</div>
				</li>
				@foreach($slides as $slide)
				<li style="background:url(/storage/image/slide/{{$slide->slide_file}}) no-repeat center top; background-size: cover;">
				</li>
				@endforeach
			</ul>
		</div>
		<div class="btn_area">
			<button type="button" class="btn_page">paging</button>
		</div>
		<div class="search_form">
			<form id="search_item" method="get" action="{{route('products.search_list',-1)}}">
				<input type="text" name="keyword" placeholder="작가명, 회화명, 분야 등으로 검색"/>
				<span><button type="submit">search<i class="fas fa-search"></i></button></span>
			</form>
		</div>
	</div>
	<div id="main_container">
		<div class="main_category ">
			<h2>gallery category</h2>
			<div class="center web">
				@for($i=1;$i<=2;$i++)
					@foreach($categorys as $category)
						<div>
							<a href="{{route('products.search_list',$category->id)}}">
								<h3><span><strong class="kr">{{$category->ca_name}}</strong>{{$category->ca_sm_name}}</span><img src="{{asset('storage/image/'.$category->ca_icon)}}" alt="{{$category->ca_name}}"/></h3>
								<p>{{$category->ca_discript}}</p>
							</a>
						</div>
					@endforeach
				@endfor
			</div>
			<div id="touchSlider4" class="center_mo mobile">
				<ul>
				@for($i=1;$i<=2;$i++)
					@foreach($categorys as $category)
						<li>
							<div>
								<a href="{{route('products.search_list',$category->id)}}">
									<h3><span><strong class="kr">{{$category->ca_name}}</strong>{{$category->ca_sm_name}}</span><img src="{{asset('storage/image/'.$category->ca_icon)}}" alt="{{$category->ca_name}}"/></h3>
									<p>{{$category->ca_discript}}</p>
								</a>
							</div>
						</li>
					@endforeach
				@endfor
				</ul>
			</div>
			<div class="btn_tcarea mobile">
				<button type="button" class="btn_prev"><i class="fal fa-arrow-circle-left"></i></button>
				<button type="button" class="btn_next"><i class="fal fa-arrow-circle-right"></i></button>
			</div>
 
		</div>
		<div class="now_peo">
			<p>현재까지 {{number_format($artist_cnt)}}명의 아티스트가 {{number_format(count($battings))}}건의 베팅을 받았습니다.</p>
			<ul>
				<li><strong class="en">{{number_format($bat_product)}}</strong>총 전시 작품수</li>
				<li><strong class="en">{{number_format(count($battings))}}</strong>누적 베팅수</li>
				<li><strong class="en">{{number_format($batting_sum)}}</strong>총 누적 베팅금액</li>
			</ul>
		</div>
		<div class="betting_pic">
			<h2 class="maintit">베팅중인 작품</h2>
			<span class="et_txt">베팅킹은 좋아요 숫자가 아닌 베팅 총액 기준입니다.</span>
				<div class="btpic_box">
					@forelse($product_battings as $key => $product_batting)
						@if($key == 0)
							<div class="big_bpic">
								@if($product_batting->get_like != 0)
									<span class="mark"><img src="{{asset('storage/image/homepage/img_best_mark.png')}}" alt="best"/></span>								
								@endif
								<p class="bigthumb"><a href="{{route('products.show',$product_batting->id)}}" target="_blank"><img src="{{asset('storage/image/product/'.$product_batting->image1)}}" alt=""/></a></p>
								<div class="peo_txt">
			
									<p>
										@if($product_batting->artist_img == '' || $product_batting->artist_img == null)
											<img src="{{asset('storage/image/default_profile.png')}}" alt="작가프로필사진"/>
										@else
											<img src="{{asset('storage/image/'.$product_batting->artist_img)}}" alt="작가프로필사진"/>
										@endif
									</p>
									<ul>
										<li>{{$product_batting->title}}</li>
										<li>작가명 : {{$product_batting->artist_name}}</li>
									</ul>
									@auth
										@if(count($product_batting->battings) > 0)
											<a href="#popupcux" id="batting_btn{{$product_batting->id}}" class="modaltrigger batting_btn betbt kr" onclick="batting_load({{$product_batting->id}})">완료</a>
										@else
											<a href="#popupcux" id="batting_btn{{$product_batting->id}}" class="modaltrigger batting_btn betbt kr" onclick="batting_do({{$product_batting->id}})">베팅</a>
										@endif
									@else
										<a href="javascript:void(0);" onclick="alert('로그인을 하셔야 가능합니다.');" class="betbt">베팅</a>
									@endauth
								</div>
								<div class="artist_co">
									<p>작품설명 </p>
									<span>
										{{$product_batting->introduce}}
									</span>
								</div>
								<div class="action_rv en">
									<ul>
										<li><i class="far fa-heart"></i>{{$product_batting->get_like}}</li>
										<li><i class="far fa-comment-alt"></i> {{$product_batting->reviews->count()}}</li>
									</ul>
								</div>
							</div>
						@elseif($key == 1)
							<div class="md_bpic">
								@if($product_batting->get_like != 0)
									<span class="mark"><img src="{{asset('storage/image/homepage/img_no2_mark.png')}}" alt="no2"/></span>
								@endif
								<p class="mdthumb"><a href="{{route('products.show',$product_batting->id)}}" target="_blank"><img src="{{asset('storage/image/product/'.$product_batting->image1)}}" alt=""/></a></p>
								<div class="peo_txt">
			
									<p>
										@if($product_batting->artist_img == '' || $product_batting->artist_img == null)
											<img src="{{asset('storage/image/default_profile.png')}}" alt="작가프로필사진"/>
										@else
											<img src="{{asset('storage/image/'.$product_batting->artist_img)}}" alt="작가프로필사진"/>
										@endif
									</p>
									<ul>
										<li>{{$product_batting->title}}</li>
										<li>작가명 : {{$product_batting->artist_name}}</li>
									</ul>
									@auth
										@if(count($product_batting->battings) > 0)
											<a href="#popupcux" id="batting_btn{{$product_batting->id}}" class="modaltrigger batting_btn betbt kr" onclick="batting_load({{$product_batting->id}})">완료</a>
										@else
											<a href="#popupcux" id="batting_btn{{$product_batting->id}}" class="modaltrigger batting_btn betbt kr" onclick="batting_do({{$product_batting->id}})">베팅</a>
										@endif
									@else
										<a href="javascript:void(0);" onclick="alert('로그인을 하셔야 가능합니다.');" class="betbt">베팅</a>
									@endauth
								</div>
								<div class="action_rv en">
									<ul>
										<li><i class="far fa-heart"></i>{{$product_batting->get_like}}</li>
										<li><i class="far fa-comment-alt"></i> {{$product_batting->reviews->count()}}</li>
									</ul>
								</div>
							</div>
						@elseif($key == 2)
							<div class="md_bpic">
								@if($product_batting->get_like != 0)
									<span class="mark"><img src="{{asset('storage/image/homepage/img_no3_mark.png')}}" alt="no2"/></span>
								@endif
								<p class="mdthumb"><a href="{{route('products.show',$product_batting->id)}}" target="_blank"><img src="{{asset('storage/image/product/'.$product_batting->image1)}}" alt=""/></a></p>
								<div class="peo_txt">
			
									<p>
										@if($product_batting->artist_img == '' || $product_batting->artist_img == null)
											<img src="{{asset('storage/image/default_profile.png')}}" alt="작가프로필사진"/>
										@else
											<img src="{{asset('storage/image/'.$product_batting->artist_img)}}" alt="작가프로필사진"/>
										@endif
									</p>
									<ul>
										<li>{{$product_batting->title}}</li>
										<li>작가명 : {{$product_batting->artist_name}}</li>
									</ul>
									@auth
										@if(count($product_batting->battings) > 0)
											<a href="#popupcux" id="batting_btn{{$product_batting->id}}" class="modaltrigger batting_btn betbt kr" onclick="batting_load({{$product_batting->id}})">완료</a>
										@else
											<a href="#popupcux" id="batting_btn{{$product_batting->id}}" class="modaltrigger batting_btn betbt kr" onclick="batting_do({{$product_batting->id}})">베팅</a>
										@endif
									@else
										<a href="javascript:void(0);" onclick="alert('로그인을 하셔야 가능합니다.');" class="betbt">베팅</a>
									@endauth
								</div>
								<div class="action_rv en">
									<ul>
										<li><i class="far fa-heart"></i>{{$product_batting->get_like}}</li>
										<li><i class="far fa-comment-alt"></i> {{$product_batting->reviews->count()}}</li>
									</ul>
								</div>
							</div>
						@else
							<div class="md_bpic">
								<p class="mdthumb"><a href="{{route('products.show',$product_batting->id)}}"><img src="{{asset('storage/image/product/'.$product_batting->image1)}}" alt=""/></a></p>
								<div class="peo_txt">
			
									<p><img src="{{asset('storage/image/'.$product_batting->artist_img)}}" alt="작가프로필사진"/></p>
									<ul>
										<li>{{$product_batting->title}}</li>
										<li>작가명 : {{$product_batting->artist_name}}</li>
									</ul>
									@auth
										@if(count($product_batting->battings) > 0)
											<a href="#popupcux" id="batting_btn{{$product_batting->id}}" class="modaltrigger batting_btn betbt kr" onclick="batting_load({{$product_batting->id}})">완료</a>
										@else
											<a href="#popupcux" id="batting_btn{{$product_batting->id}}" class="modaltrigger batting_btn betbt kr" onclick="batting_do({{$product_batting->id}})">베팅</a>
										@endif
									@else
										<a href="javascript:void(0);" onclick="alert('로그인을 하셔야 가능합니다.');" class="betbt">베팅</a>
									@endauth
								</div>
								<div class="action_rv en">
									<ul>
										<li><i class="far fa-heart"></i>{{$product_batting->get_like}}</li>
										<li><i class="far fa-comment-alt"></i> {{$product_batting->reviews->count()}}</li>
									</ul>
								</div>
							</div>
						@endif
							
					@empty
						베팅중인 작품이 없습니다.
					@endforelse
			</div>
		</div>
		<div class="salebox">
			<h2 class="maintit">판매중인 작품</h2>
			<span class="et_txt">실시간 조회수가 가장 높은 작품 top 10입니다</span>
			<div class="salelist">
				@foreach($products as $product)
					<a href="{{route('products.show',$product->id)}}">
						<div class="salepic">
							<p class="sm_thumb"><img src="{{asset('storage/image/product/'.$product->image1)}}" alt=""/></p>
							<div class="peo_txt">
								<ul>
									<li>{{$product->title}}</li>
									<li>작가명 : {{$product->artist_name}}</li>
								</ul>
							</div>
							<div class="price en">
								<ul>
									<li><em class="coinic">c</em> {{number_format(($product->coin_price),3)}}</li>
									<li><em class="kric">￦</em> {{number_format($product->cash_price)}}</li>
								</ul>
							</div>
						</div>
					</a>
				@endforeach
			</div>
			<span class="allpic_go kr"><a href="{{route('products.search_list',-1)}}">전체작품 보러가기</a></span>
		</div>
		<div class="main_bottom">
			<div class="mcenter">
				<div class="mnotice">
					<h3 class="en">notice</h3>
					<a href="{{route('notice.list')}}" class="more"><i class="fas fa-plus"></i></a>
					<ul>
						@foreach($notices as $notice)
							<li onclick="location.href='{{route('notices.show',$notice->id)}}'">
								<strong>{{$notice->title}}</strong>
							</li>
						@endforeach
					</ul>
				</div>
				<div class="mevent">
					<h3 class="en">event</h3>
					<a href="{{route('events.index')}}" class="more"><i class="fas fa-plus"></i></a>
					<ul>
						@forelse($events as $event)
							<li><a href="{{route('events.show',$event->id)}}"><img src="{{asset('storage/event/'.$event->pc_banner)}} " alt=""/></a></li>
						@empty
							
						@endforelse
						
					</ul>
				</div>
			</div>
			@if(isset($video->video_link))
			<div class="mmovie">
				<p>텔루스아트 홍보영상</p>
				<span><a href="{{ $video->video_link }}" target="_blank" style="color:#fff;">홍보 영상 보러가기<i class="far fa-play-circle"></i></a></span>
				<div class="overlay"></div>
				<iframe width="100%" height="225" src="{{ $video->video_link }}?autoplay=1&controls=0&showinfo=0&wmode=opaque&autohide=1&loop=1&playsinline=0&mute=1&rel=0&modestbranding=1&playlist=cZcsko5a9lE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
			@endif
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
@endsection
		

@section('main_script')

<script>
	$(".sear_mo_box").hide();
	$(document).ready(function(){
	  $("#hamburger-1").click(function(){
		$(this).toggleClass("is-active");
		$(".sear_mo_box").slideToggle();
	  });
	});
	$(document).ready(function() {
		
		$("#touchSlider4").touchSlider({
			btn_prev : $("#touchSlider4").next().find(".btn_prev"),
			btn_next : $("#touchSlider4").next().find(".btn_next")
		});
		
		
	});
	
	
	$(function(){
		$(window).scroll(function() {
		   if($(this).scrollTop() > 200){
				$(".mo_category ").css({ "position": "fixed", "z-index": "50", "top": "53px" });
		   }else{
				$(".mo_category ").css({ "position": "relative", "top": "0px" });
		   }
		});
	});
	$(function(){
		$(window).scroll(function() {
		   if($(this).scrollTop() > 200){
				$(".swiper-container ").css({ "position": "fixed", "z-index": "50", "top": "98px" }).addClass("gra");
		   }else{
				$(".swiper-container ").css({ "position": "relative", "top": "0px" }).removeClass("gra");
		   }
		});
	});
	$('.center').slick({
			centerMode: true,
			centerPadding: '40px',
			slidesToShow: 3,
			responsive: [
				{
				  breakpoint: 768,
				  settings: {
					arrows: false,
					centerMode: true,
					centerPadding: '40px',
					slidesToShow: 3
				  }
				},
				{
				  breakpoint: 480,
				  settings: {
					arrows: false,
					centerMode: true,
					centerPadding: '40px',
					slidesToShow: 1
				  }
				}
			]
		});
		
	$('#search_item').submit(function(e){
		if($('.search_form form input[type="search"]').val() == ''){
			e.preventDefault();
			
			return false;
		}else{
			return true;
		}
	});
	
	$(function(){
	  $('.modaltrigger').leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
	});
	
	function batting_do(art_id){
		$("#popupcux .cux_modal_dialog .footer_btn").html('<button type="button" id="batting_price_submit" class="cashgo">베팅하기</button>');
		var already_bat_price = $('#already_bat_price').text();
		$('input[name="batting_price"]').val(already_bat_price);
		$("#popupcux").show();
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var bat_cnt = $('#bat_cnt'+art_id).text();
		
		$('#batting_price_submit').click(function(){
			var batting_price = $('input[name="batting_price"]').val();
			if(batting_price > 0){
				$.ajax({
		             url: '/batting/do',
		             type: 'POST',
		             /* send the csrf-token and the input to the controller */
		             data: {_token: CSRF_TOKEN, art_id: art_id, batting_price: batting_price},
		             dataType: 'JSON',
		             /* remind that 'data' is the response of the AjaxController */
		             success: function (data) { 
		             	if(data == 'balance'){
		             		alert('수수료를 포함한 베팅 금액이 보유한 코인보다 적습니다.');
		             	}else if(data == 'network'){
		             		alert('네트워크 문제로 인해 실패하셨습니다. 잠시 후 다시 시도해주세요.');
		             	}else{
			             	$('#bat_cnt'+art_id).text(parseInt(bat_cnt)+1);
			             	$('#batting_btn'+art_id).attr("onclick","batting_load("+art_id+")");
			             	$('#batting_btn'+art_id).text('완료');
			                alert('해당 작품에 베팅이 완료 되었습니다!'); 
			                $('#bat_item'+art_id+'#already_bat_price').text(data.batting_price);
			             	$('#popupcux').hide();
		             	}
		             }
		         });
			}else{
				alert('베팅금액은 0보다 커야 합니다.');
			}
		})
	}
	
	
	function batting_load(art_id){
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
	        url: '/batting/load',
	        type: 'POST',
	        /* send the csrf-token and the input to the controller */
	        data: {_token: CSRF_TOKEN, art_id: art_id},
	        dataType: 'JSON',
	             /* remind that 'data' is the response of the AjaxController */
	        success: function (data) { 
	        	$('input[name="batting_price"]').val(data.batting_price);
	        	<?php
	        		//<button type="button" id="batting_cancel" class="btn btn-default" onclick="batting_cancel(' + art_id + ')">베팅취소</button>
	        	?>
	        	$("#popupcux .cux_modal_dialog .footer_btn").html('<button type="button" id="batting_price_submit" class="cashgo" onclick="batting_edit(' + art_id + ')">베팅수정</button>');
				$("#popupcux").show();
	        }
	    });

	}
	
	
	function batting_edit(art_id){
			var batting_price = $('input[name="batting_price"]').val();
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
			if(batting_price > 0){
				$.ajax({
		             url: '/batting/edit',
		             type: 'POST',
		             /* send the csrf-token and the input to the controller */
		             data: {_token: CSRF_TOKEN, art_id: art_id, batting_price: batting_price},
		             dataType: 'JSON',
		             /* remind that 'data' is the response of the AjaxController */
		             success: function (data) { 
		             	if(data == 'balance'){
		             		alert('수수료를 포함한 베팅 금액이 보유한 코인보다 적습니다.');
		             	}else if(data == 'network'){
		             		alert('네트워크 문제로 인해 실패하셨습니다. 잠시 후 다시 시도해주세요.');
		             	}else{
			             	$('#batting_btn'+art_id).attr("onclick","batting_load("+art_id+")");
			             	$('#batting_btn'+art_id).text('완료');
			             	alert('해당 작품에 베팅이 완료 되었습니다!'); 
			             	$('#already_bat_price'+art_id).text(data.batting_price);
			             	$('#popupcux').hide();
		             	}
		             }
		         });
	         }else{
	         	alert('베팅금액은 0보다 커야 합니다.');
	         }
	}
	
	function batting_cancel(art_id){
			var batting_price = $('input[name="batting_price"]').val();
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
			$('input[name="batting_price"]').val(batting_price);
			var bat_cnt = $('#bat_cnt'+art_id).text();
			
			$.ajax({
	             url: '/batting/cancel',
	             type: 'POST',
	             /* send the csrf-token and the input to the controller */
	             data: {_token: CSRF_TOKEN, art_id: art_id},
	             dataType: 'JSON',
	             /* remind that 'data' is the response of the AjaxController */
	             success: function (data) { 
	             	if(data.dateover){
	             		alert('베팅하신 날부터 24시간이 지나 취소가 불가합니다.'); 
	             	}else{
	             		$('#bat_cnt'+art_id).text(parseInt(bat_cnt)-1);
	             		$('#batting_btn'+art_id).attr("onclick","batting_do("+art_id+")");
	             		$('#batting_btn'+art_id).text('신청');
	             		alert('해당 작품에 신청한 베팅이 취소되었습니다.');
	             		$('#already_bat_price'+art_id).text(data.batting_price);
	             	}
	             	$('#popupcux').hide();
	             }
	         });
	}
	
</script>
@endsection