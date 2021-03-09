@extends('pc.layouts.app')

@section('content')
	<div class="sub_spot mypage" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
	<h2>마이페이지</h2>
</div>


<div id="container">
	@include('pc.mypage.include.my_common')
	<div class="orderbox">
		
		<div class="cartbox">
			<h3 class="mytit">베팅한 건수</h3>
			<span class="titm_txt">총 <strong>{{$search_batting_cnt}}건</strong>의 베팅한 건수내역이 있습니다.</span>
		</div>
		<div class="countbox">
			<ul>
				<li><span><i class="fal fa-chart-line"></i></span><em>베팅중<strong>{{$batting_ings->count()}}</strong></em></li>
				<li><span><i class="fal fa-cube"></i></span><em>베팅마감<strong>{{$batting_ends->count()}}</strong></em></li>
			</ul>
		</div>
		<div class="period">
			<form id="batting_target" method="get" action="{{route('mypage.mybatting_list')}}">
				<ul>
					<li><label for="dateTerm1"><input type="radio" name="dateTerm" id="dateTerm1" style="display:none;" value="1">1일</label></li>
					<li><label for="dateTerm7"><input type="radio" name="dateTerm" id="dateTerm7" style="display:none;" value="7">1주일</label></li>
					<li><label for="dateTerm30"><input type="radio" name="dateTerm" id="dateTerm30" style="display:none;" value="30">1개월</label></li>
					<li><label for="dateTerm365"><input type="radio" name="dateTerm" id="dateTerm365" style="display:none;" value="365">1년</label></li>
					<li class="cale"><span><input id = "from_date" name="from_date" class="datepicker" type = "text" size="15"></span></li>
					<li><span><input id = "to_date" name="to_date" class="datepicker" type = "text" size="15"></span></li>
					<li>
						<div class="select">
							<select name="status">
								<option value="0" {{($status==0)? 'selected="selected"':""}}>전체</option>
								<option value="1" {{($status==1)? 'selected="selected"':""}}>베팅중</option>
								<option value="2" {{($status==2)? 'selected="selected"':""}}>베팅마감</option>
							</select>
							<div class="select__arrow"></div>
						</div>
					</li>
				</ul>
			</form>
		</div>
		<!-- 장바구니 리스트 -->
		<form name="orderBasketFrm" method="post" action="">
			<div class="buy_goods_list">
				<div class="table_list">
					<table class="list_cart">
						<caption>베팅건수 리스트</caption>
						<colgroup>
							<col style="width:15%" class="mnone">
							<col style="">
							<col style="width:10%" class="mnone">
							<col style="width:10%" class="mnone">
							<col style="width:10%" class="mnone">
						</colgroup>
						<thead>
							<tr>
								<th class="t_layout">베팅한 날짜</th>
								<th class="t_layout">베팅한 그림내역</th>
								<th class="t_layout">내 베팅금액</th>
								<th class="t_layout">베팅상태</th>
								<th class="t_layout">보상금액</th>
							</tr>
						</thead>

						<tbody>
						@forelse($batting_lists as $batting_list)
							<tr class="pop_parent" >
								<td class="t_layout en web">
									<strong>{{date("Y.m.d", strtotime($batting_list->created_at))}}</strong> {{date("H:i", strtotime($batting_list->created_at))}}
								</td>
								<td>
									<div class="cart_txt web">
										<p class="btn_thumb"><a href="" target="_blank"><img src="{{asset('/storage/image/product/'.$batting_list->product->image1)}}" alt="{{$batting_list->product->title}}"></a></p>
										<p class="name">
											<em class="category">{{$batting_list->product->category->ca_name}}</em>
											<a href="{{route('products.show',$batting_list->art_id)}}" class="" target="_blank">{{$batting_list->product->title}}</a>
											<span class="option">작가명 : {{$batting_list->product->user->name}} <i></i>사이즈 : {{$batting_list->product->art_width_size}} X {{$batting_list->product->art_height_size}}cm</span>
										</p>
									</div>
									<!-- 모바일 -->
									<div class="mobile_cart mobile">
										<div class="mo_cart_txt">
											
											<p class="btn_thumb"><a href="{{route('products.show',$batting_list->art_id)}}" target="_blank"><img src="{{asset('/storage/image/product/'.$batting_list->product->image1)}}" alt="{{$batting_list->product->title}}"></a></p>
											<p class="name"><em class="category">{{$batting_list->product->category->ca_name}}</em><a href="" class="" target="_blank">{{$batting_list->product->title}}</a>
											<span class="option">작가명 : {{$batting_list->product->user->name}} <i></i>사이즈 : {{$batting_list->product->art_width_size}} X {{$batting_list->product->art_height_size}}cm</span>
										</p>
										</div>
										
										<div class="mo_cart_info">
											<dl>
												<dt>베팅금액</dt>
												<dd class="price">
													<em class="coinic">c</em> {{round($batting_list->batting_price,2)}}
												</dd>
											</dl>
										</div>
										<div class="cart_m_bt">
											<span >
												@if($batting_list->batting_status == 1)
													-
												@else
													{{$batting_list->get_price}}
												@endif
												<!--<a href="" class="m_btn_yellow"title="바로주문">보상받기</a>
												<a href=""  class="m_btn"  title="보상완료">보상완료</a>
												-->
											</span>
											<span>
												<button type="button" class="{{($batting_list->batting_status == 1)? 'betbt':'betbtno'}} kr">{{($batting_list->batting_status == 1)? '베팅중':'베팅마감'}}</button>
											</span>
										</div>
									</div>
									<!-- //모바일 -->
								</td>
								
								<td class="t_layout web price en left">
									<em class="coinic">c</em> {{round($batting_list->batting_price,2)}}
								</td>
								<td class="t_layout web">
									<div class="cart_m_bt">
										<span class="w100">
											<button type="button"  class="{{($batting_list->batting_status == 1)? 'betbt':'betbtno'}} kr">{{($batting_list->batting_status == 1)? '베팅중':'베팅마감'}}</button>
										</span>
									</div>
									
								</td>
								<td class="t_layout web">
									<div class="cart_m_bt">
										<span class="w100">
											@if($batting_list->batting_status == 1)
												-
											@else
												{{$batting_list->get_price}}
											@endif
										</span>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="5">검색된 작품이 없습니다.</td>
							</tr>
						@endforelse
						</tbody>
					</table>
				</div>
				@if($batting_lists)
					{!! $batting_lists->render() !!}
				@endif
				<!--
				<div class="paging_board">
					<span class="af">
						<a href=""><i class="fal fa-angle-double-left"></i></a>
						<a href=""><i class="fal fa-angle-left"></i></a>
					</span>
					<ul>
						<li><a href="" class="on">1</a></li>
						<li><a href="" class="">2</a></li>
						<li><a href="" class="">3</a></li>
						<li><a href="" class="">4</a></li>
						<li><a href="" class="">5</a></li>
						<li><a href="" class="">6</a></li>
						<li><a href="" class="">7</a></li>
						<li><a href="" class="">8</a></li>
						<li><a href="" class="">9</a></li>
						<li><a href="" class="">10</a></li>
					</ul>
					<span class="bf">
						<a href=""><i class="fal fa-angle-right"></i></a>
						<a href=""><i class="fal fa-angle-double-right"></i></a>
					</span>
				</div>
				-->
			</div>
		</form>
		<!-- //장바구니 리스트 -->
	</div>
</div>



@endsection

@section('main_script')
<script src="https://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />
<script>


$(function() {

   $.datepicker.setDefaults({ 
    changeMonth: true,
    changeYear:true,
    dateFormat: "yy-mm-dd",
    showOn:"button",
    buttonImage: "{{asset('/storage/image/homepage/ic_calendar.png')}}",
    buttonImageOnly:true,
    minDate: "-1Y", //최소 선택일자(-1D:하루전, -1M:한달전, -1Y:일년전),
    maxDate: "today" //최대 선택일자(+1D:하루후, -1M:한달후, -1Y:일년후), 

   });

	$("#from_date").datepicker();                    
	$("#to_date").datepicker();
	
	@if($from_date == NULL)
		$('#from_date').datepicker('setDate','-{{$dateTerm}}D');
		$('#to_date').datepicker('setDate','today');
	@else
		$('#from_date').datepicker('setDate',new Date('{{$from_date}}'));
		$('#to_date').datepicker('setDate',new Date('{{$to_date}}'));
		
	@endif
  });

$('#from_date, #to_date').change(function(){
	
	$('#batting_target').submit();
	
});

$('input[name="dateTerm"],select[name="status"]').change(function(){
	$('#batting_target').submit();
});



	
</script>
@endsection
