@extends('pc.layouts.app')

@section('content')
<div class="sub_spot mypage" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
	<h2 class="pt30">구매하기</h2>
</div>
<div id="container">
	<div class="orderbox">
			<div class="cartbox">
				<h3 class="mytit">{{$title}}</h3>
			</div> 
			<!-- 주문완료 -->
			<form name="" method="post" action="">
				<div class="resultbox">
					<p><i class="fal fa-box-full"></i></p>
					<span>{{$message}}</span>
					<ul>
						<li>
							고객님의 주문번호는
							<a href="{{route('order.bil',$order_id)}}">
								<strong>
									{{$order_number}}
								</strong> 
							</a>
							입니다. 
						</li>
						<li>상세한 내역은 고객님의 <a href="{{route('mypage.my_order_list')}}"><strong>마이페이지 주문내역</strong></a>에서 확인하실 수 있습니다. </li>
					</ul>
					<a href="{{route('mypage.my_order_list')}}" class="btmy" style="width:40%;">마이페이지 가기</a><a href="{{route('home')}}" class="btbk ml10" style="width:20%">메인으로</a>
				</div>
			</form>
			<!-- //주문완료 -->
		</div>
</div>
@endsection