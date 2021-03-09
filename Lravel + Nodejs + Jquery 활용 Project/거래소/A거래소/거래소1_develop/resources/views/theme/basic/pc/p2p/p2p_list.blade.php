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

				<h1 class="main_tit">{{ __('ptop.out_trade') }}</h1>

				<div class="tab_menu_bar">
					<ul>
						<li @if($type=='all' ) class="active" @endif>
							<a href="{{ route('p2p_list','all') }}">
								<i class="fal fa-bars mr-1"></i>{{ __('ptop.all') }}
							</a>
						</li>

						<li @if($type=='sell' ) class="active" @endif>
							<a href="{{ route('p2p_list','sell') }}">
							{{ __('ptop.sell') }}
							</a>
						</li>
						<li @if($type=='buy' ) class="active" @endif>
							<a href="{{ route('p2p_list','buy') }}">
							{{ __('ptop.buy') }}
							</a>
						</li>
						@auth
							<li @if($type=='my' ) class="active" @endif>
								<a href="{{ route('p2p_list','my') }}">
								{{ __('ptop.my_posts') }}
								</a>
							</li>
						@endauth
					</ul>
					@auth
						@if(Auth::user()->status != 2)
							{{-- <a href="#" id="p2pWrite" class="write_btn_st write_btn p2pWrite"> {{ __('ptop.entollment_out_trade') }}</a> --}}
						@endif
					@endauth
				</div>

				{{-- 장외거래 리스트들 --}}
				<div class="p2p_list_wrap mt-3">

					<div class="p2p_sch_bar mb-3">
						<div class="form-group">
							<form method="get" action="" class="form-group" style="width:100%;">
							@csrf
								<select id="select_p2p_list" class="form-control mr-1" name="category">
									<option value="all" {{($category == 'all')?'selected=selected':''}} >{{ __('ptop.all') }}</option>
									<option value="coin_type" {{($category == 'coin_type')?'selected=selected':''}}>{{ __('ptop.coin') }}(BTC, ETH..)</option>
									<option value="name" {{($category == 'name')?'selected=selected':''}}>{{ __('ptop.writer') }}</option>
								</select>
								<input placeholder="{{ __('ptop.con_n_writer_search') }}" type="text" id="p2p_list_srch" class="form-control" name="srch">
								<button type="submit" class="ml-1">
									<img src="/images/button/btn_white_search.svg" alt="">
								</button>
							</form>
						</div>
					</div>

					<div id="p2p_list_con" class="p2p_list_group" style="display: flex;" data-offset="{{$offset}}" data-count="{{$p2p_count}}">
						@forelse($p2ps as $p2p)
						<!-- 구매박스 -->
						<div class="list_in_con">

							<div class="hd">

								@if($p2p->type=='buy')
								<label class="tit buy_tit">
								@elseif($p2p->type=='sell')
								<label class="tit sell_tit" >
								@endif
									{{__('p2p.'.$p2p->type.'s')}}
									{{__('p2p.'.$p2p->state)}}
								</label>
								<p class="etc_info">
									<span>[ {{$p2p->name}} ]</span>
									<span>{{date("Y-m-d",strtotime($p2p->start))}}</span>
								</p>
								@auth
									@if($p2p->uid==Auth::user()->id && $p2p->confirm==0)
										<!-- 본인 작성글 삭제하는 버튼 -->
										<button class="del_btn" onclick="location.href='{{ route('p2p_deleted',$p2p->id) }}'">{{ __('ptop.delete') }}</button>
										<!-- 본인 작성글 삭제하는 버튼 -->
									@endif
								@endauth
							</div>

							<div class="info_1">

								<img src="{{ asset('/storage/image/homepage/coin_img')}}/{{$p2p->coin_type}}.png" alt="coin_img" class="coin_symbol">
								<p class="coin_name">{{__('coin_name.'.strtolower($p2p->coin_type))}}</p>
								<span class="coin_name_eng">{{$p2p->coin_type}}</span>

								<!-- 판매일 경우, 블록익스플로러로 보유확인할 수 있는 버튼 -->
								@if($p2p->type=='sell')
								<span class="have_coin_check" id="c_addr">
									<a href="{{__('blockexplore.'.$p2p->coin_type).$p2p->s_coin_address}}" target="_blank">
									{{ __('ptop.confirm_possession') }}
									</a>
								</span> @endif
								<!-- 판매일 경우, 블록익스플로러로 보유확인할 수 있는 버튼 -->

							</div>

							<div class="info_2">

								<ul>
									<li class="amt">
										<label class="mr-2">{{ __('ptop.quantity') }}</label>
										<span>{{$p2p->coin_amount}} <span class="currency pl-1">{{$p2p->coin_type}}</span></span>
									</li>

									<li class="prc">
										<label class="mr-2">{{ __('ptop.price') }}</label>
										<span>{{$p2p->coin_price}}<span class="currency pl-1">{{$p2p->country_money}}</span></span>
									</li>
								</ul>

							</div>

							<div class="info_3">

								<textarea readonly="readonly">{!!$p2p->cont!!}</textarea>

							</div>


							@auth
								
								@if(Auth::user()->status == 2)
									<button class="not_active_btn stop_user_id_warning" type="button">계정 정지</button> 
								@elseif($p2p->state == 'on')
									<button class="apply_btn write_btn p2pApply" data-id="{{$p2p->id}}" type="button">{{__('p2p.'.$p2p->type)}} {{ __('ptop.apply1') }}</button>							
								@elseif($p2p->state == 'onProgress')
									<button class="not_active_btn" type="button">{{ __('ptop.ing') }}</button> 
								@elseif($p2p->state == 'complete')
									<button class="not_active_btn" type="button">{{ __('ptop.complete') }}</button> 
								@elseif($p2p->state == 'stop')
									<button class="not_active_btn" type="button">{{ __('ptop.stop') }}</button> 
								@endif
							@else
								@if($p2p->state == 'on')
									<button class="apply_btn write_btn" type="button" onclick="location.href='{{route('login')}}'">{{__('p2p.'.$p2p->type)}} {{ __('ptop.apply1') }}</button>							
								@elseif($p2p->state == 'onProgress')
									<button class="not_active_btn" type="button">{{ __('ptop.ing') }}</button> 
								@elseif($p2p->state == 'complete')
									<button class="not_active_btn" type="button">{{ __('ptop.complete') }}</button> 
								@elseif($p2p->state == 'stop')
									<button class="not_active_btn" type="button">{{ __('ptop.stop') }}</button> 
								@endif
							@endauth

						</div>
						<!-- //구매박스 -->
						@empty

						<div class="none_transac bd-dddd">
							<img src="/images/icon_notice.svg" alt="" class="btn_notice">{{ __('ptop.noinfo')}}
						</div>

						@endforelse

					</div>

				</div>
				{{-- //장외거래 리스트들 --}}

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
	@auth
		@if(Auth::user()->status != 2)
			@include(session('theme').'.pc.p2p.p2p_apply_modal')
			@include(session('theme').'.pc.p2p.p2p_create')
		@endif
	@endauth
<script>
	//$(window).scrollTop() => 0
	//$(document).height()  => 1372
	//$(window).height()    => 1077
	if (typeof __ === 'undefined') { var __ = {}; }
    __.ptop = {
        @foreach(__('ptop') as $key => $value)
            '{{$key}}':'{{$value}}',
        @endforeach
    }
	$(window).scroll(function(){
		if($(window).scrollTop() >= $(document).height()-$(window).height()){
			more_p2p_list('{{$type}}',{{Auth::check()}});
		}
	})
</script>
@endsection