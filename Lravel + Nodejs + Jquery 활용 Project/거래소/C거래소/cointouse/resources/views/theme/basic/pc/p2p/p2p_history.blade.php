@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div class="board_st_wrap p2p_ico_wrap adv_wrap">

	<div class="board_st_inner">
		
		{{-- 왼쪽 광고배너영역 --}}
		<div class="left_adv_banner_wrap">
			<div class="adv_banners">
				@foreach($left_banners as $left_banner)
				<a href='{{$left_banner->target_url}}'><img src="{{asset('/storage/image/banner/' . $left_banner->banner_url)}}" onerror="this.src='{{asset('/storage/image/banner/left_right_no_image.jpg')}}'" /></a>
				@endforeach
			</div>
		</div>
		{{-- //왼쪽 광고배너영역 --}}

		<div class="board_st_con">
			
			@include(session('theme').'.pc.p2p.include.sub_menu')

			<div class="right_con">
				
				<!-- 상단 광고배너영역 -->
				@foreach($top_banners as $top_banner)
				<div class="adv_banner_wrap">
					<a href='{{$top_banner->target_url}}'><img src="{{asset('/storage/image/banner/' . $top_banner->banner_url)}}" onerror="this.src='{{asset('/storage/image/banner/top_no_image.jpg')}}'"/></a>
				</div>
				@endforeach
				<!-- //상단 광고배너영역 -->

				<h1 class="main_tit">{{ __('ptop.transaction_completion_history') }}</h1>

				{{-- 테이블 --}}
				<div class="p2p_history_wrap">

					<div class="p2p_history_con history_con">

						<form class="note_label" style="width:100%;">
							
							<div class="increase_btn_group">
								<button class="btn_style mr-1" name='during' value="7">{{ __('ptop.one_week') }}</button>
								<button class="btn_style mr-1" name='during' value="14">{{ __('ptop.two_weeks') }}</button>
								<button class="btn_style" name='during' value="30">{{ __('ptop.one_month') }}</button>
							</div>

							<div class="period_sch_group ml-3">
								<label class="pr-2">{{ __('ptop.term') }}</label>
								<input type="text" class="period_sch_input" id="start_sch" name="from_date" value="{{$from_date}}" readonly="readonly"> ~ <input type="text" class="period_sch_input" id="end_sch" name="to_date" value="{{$to_date}}" readonly="readonly">
								<button class="btn_style sch_btn ml-1">{{ __('ptop.lookup') }}</button>
							</div>

							<div class="coin_sch_group ml-3">
								<input type="text" id="txtFilter" placeholder="{{ __('ptop.search_coin') }}"/>
							</div>

							<select id="selectFilter" class="type_sort_slt">
								<option value="">{{ __('ptop.all_trade') }}</option>
								<option value="{{ __('ptop.buy') }}">{{ __('ptop.buy') }}</option>
								<option value="{{ __('ptop.sell') }}">{{ __('ptop.sell') }}</option>
							</select>

						</form>

						<div id="p2p_history_wrap" class="p2p_table_wrap" data-count="{{$p2p_count}}" data-offset="{{$offset}}">

							<table class="table_label">
								<thead>
									<tr>
										<th>{{ __('ptop.trade_kind') }}</th>
										<th>{{ __('ptop.coin') }}</th>
										<th>{{ __('ptop.trade_price') }}</th>
										<th>{{ __('ptop.quantity') }}</th>
										<th>{{ __('ptop.selldr_buyer') }}</th>
										<th>{{ __('ptop.due_date_trade') }}</th>
									</tr>
								</thead>
							</table>

							<table id="p2p_history_tbl" class="coin_chart_tbl target">
								<tbody>
								@forelse($p2ps as $p2p)
									@if($p2p->type == 'buy')
									<tr class="p2p_type-buy" name="{{__('coin_name.'.$p2p->coin_type)}}/{{$p2p->coin_type}}">
									@else
									<tr class="p2p_type-sell" name="{{__('coin_name.'.$p2p->coin_type)}}/{{$p2p->coin_type}}">
									@endif
										<td>
											<p>
												@if($p2p->uid == $p2p->b_id)
												<span>{{ __('ptop.buy') }}</span>
												@elseif($p2p->uid == $p2p->s_id )
												<span>{{ __('ptop.sell') }}</span>
												@endif
											</p>
										</td>

										<td class="coin_td">
											<img class="coin_symbol" src="{{ asset('/storage/image/homepage/coin_img')}}/{{$p2p->coin_type}}.png" alt="coin_img">
											<span class="coin_name">{{__('coin_name.'.$p2p->coin_type)}}</span><span class="coin_name_eng">{{$p2p->coin_type}}</span>
										</td>

										<td>
											<p>
												<span>{{number_format($p2p->coin_price)}}</span>
												<span class="currency">{{$p2p->country_money}}</span>
											</p>
										</td>

										<td>
											<p>
												<span>{{$p2p->coin_amount}}</span>
												<span class="currency">{{strtoupper($p2p->coin_type)}}</span>
											</p>
										</td>

										<td>
											<p>
												<span>{{$p2p->name}}</span>
											</p>
										</td>

										<td>
											<p>
												<span>{{$p2p->end}}</span>
											</p>
										</td>
									</tr>
								@empty	
									<!-- p2p 거래내역이 존재하지 않을 때 -->
									<tr class="none_transac">
										<td colspan="8">
											<i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('ptop.datano') }}
										</td>

										</tr>
										<!-- p2p 거래내역이 존재하지 않을 때 -->

										@endforelse

								</tbody>
							</table>

						</div>
						
						@if($p2p_count > $offset)
							<!--페이지 많아지면 더보기-->
							<div class="table_view_more mt-3">
								<button type="button" id="p2p_history_more"><i class="fal fa-plus"></i> {{ __('ptop.more') }}</button>
							</div>
							<!--//페이지 많아지면 더보기-->
						@endif
						
					</div>

				</div>
				{{-- //테이블 --}}

			</div>

		</div>
		
		{{-- 오른쪽 광고배너영역 --}}
		<div class="right_adv_banner_wrap">
			<div class="adv_banners">
				@foreach($right_banners as $right_banner)
				<a href='{{$right_banner->target_url}}'><img src="{{asset('/storage/image/banner/' . $right_banner->banner_url)}}" onerror="this.src='{{asset('/storage/image/banner/left_right_no_image.jpg')}}'" /></a>
				@endforeach
			</div>
		</div>
		{{-- //오른쪽 광고배너영역 --}}

	</div>

</div>
@endsection