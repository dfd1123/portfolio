@extends('pc.layouts.app')

@section('content')
	
<div class="sub_spot mypage" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
	<h2>마이페이지</h2>
</div>
<div id="container">
	@include('pc.mypage.include.my_common')
	<div class="orderbox">
		
		<div class="cartbox">
			<h3 class="mytit">장바구니</h3>
			<span class="titm_txt">총 <strong>{{$cart_cnt}}건</strong>의 작품이 내 장바구니에 있습니다.</span>
		</div> 
		<!-- 장바구니 리스트 -->
			<div class="buy_goods_list">
				<div class="table_list">
					<table class="list_cart">
						<caption>장바구니 리스트</caption>
						<colgroup>
							<col style="width:5%" class="mnone">
							<col style="">
							<col style="width:30%" class="mnone">
							<col style="width:20%" class="mnone">
						</colgroup>
						<thead>
							<tr>
								<th class="t_layout">
									<div class="check" >
										<input type="checkbox" id="check0" name="check0" onclick="CheckAll()" />
										<label for="check0"><span></span></label>
									</div>
								</th>
								<th class="t_layout">작품정보</th>
								<th class="t_layout">작품판매가격(tlc / krw)</th>
								<th class="t_layout">주문처리</th>
							</tr>
						</thead>

						<tbody>
							@forelse($carts as $cart)
							<tr class="pop_parent" >
								<td class="t_layout web">
									<div class="check" >
										<input type="checkbox" id="check{{$cart->id}}" name="del_unit[]" value="{{$cart->id}}" />
										<label for="check{{$cart->id}}"><span></span></label>
									</div>
								</td>
								<td>
									<div class="cart_txt web">
										<p class="btn_thumb"><a href="{{route('products.show',$cart->product_id)}}" target="_blank"><img src="{{asset('/storage/image/product/'.$cart->product->image1)}}" alt="상품 이미지"></a></p>
										<p class="name"><a href="" class="" target="_blank">{{$cart->product->title}}</a>
											<span class="option">작가명 : {{$cart->product->user->name}} <i></i>사이즈 : {{$cart->product->art_width_size}} X {{$cart->product->art_height_size}}cm</span>
										</p>
									</div>
									<!-- 모바일 -->
									<div class="mobile_cart mobile">
										<div class="mo_cart_txt">
											<p class="btn_thumb"><a href="{{route('products.show',$cart->product_id)}}" target="_blank"><img src="{{asset('/storage/image/product/'.$cart->product->image1)}}" alt="상품 이미지"></a></p>
											<p class="name"><a href="" class="" target="_blank">{{$cart->product->title}}</a>
											<span class="option">작가명 : {{$cart->product->user->name}} <i></i>사이즈 : {{$cart->product->art_width_size}} X {{$cart->product->art_height_size}}cm</span>
										</p>
										</div>
										
										<div class="mo_cart_info">
											<dl>
												<dt>상품 금액</dt>
												<dd>202,500</dd>
											</dl>
										</div>
										<div class="cart_m_bt">
											<span>
												<a href="{{route('products.show',$cart->product_id)}}" class="m_btn_gray"title="바로주문">보러가기</a>
												<a href="/orders/{{$cart->product_id}}"  class="m_btn"  title="구매하기">구매하기</a>
											</span>
											<span><a href="#"onClick="functionName();return false;" class="betbtno kr">베팅불가</a></span>
										</div>
									</div>
									<!-- //모바일 -->
								</td>
								
								<td class="t_layout web price en">
									<em class="coinic">c</em> {{number_format(round($cart->product->coin_price,3),3)}}
									<em class="kric">￦</em> {{number_format($cart->product->cash_price)}}
								</td>
								<td class="t_layout web">
									<div class="cart_m_bt">
										@if($cart->product->sell_yn != 3)
											<span>
												<a href="{{route('products.show',$cart->product_id)}}" class="m_btn_gray"title="바로주문">보러가기</a>
												<a href="/orders/{{$cart->product_id}}"  class="m_btn"  title="구매하기">구매하기</a>
											</span>
										@endif
										<span>
											@if($cart->product->batting_status == 0 || $cart->product->batting_status == 2)
												<a href="#" onClick="alert('베팅 불가 작품입니다.');return false;" class="betbtno kr">베팅불가</a>
											@else
												<a href="{{route('products.show',$cart->product_id)}}" class="betbtno kr">베팅하기</a>
											@endif
										</span>
									</div>
								</td>
							</tr>
							@empty
								<tr>
									<td colspan="4">장바구니가 비어있습니다.</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
				
				<div class="btnOrder">
					<form id="cart_delete" method="post" action="{{route('mypage.cart_delete')}}">
						
						@csrf
						<input type="hidden" name="delete_num" />
						<button type="submit" class="betgobt">삭제하기</button>
					</form>
				</div>
				
				@if($carts)
					{!! $carts->render() !!}
				@endif
				
			</div>
		<!-- //장바구니 리스트 -->
	</div>
</div>


	
	
@endsection
