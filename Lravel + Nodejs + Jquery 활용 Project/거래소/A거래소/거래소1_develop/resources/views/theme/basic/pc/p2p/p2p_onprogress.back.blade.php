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
			
				<h1 class="main_tit">{{ __('ptop.trading_info') }}</h1>

				<div class="tab_menu_bar">
					<ul>
						<li @if($type=='all' ) class="active" @endif>
							<a href="{{ route('p2p_onprogress','all') }}">
								<i class="fal fa-bars mr-1"></i>{{ __('ptop.all') }}
							</a>
						</li>

						<li @if($type=='buy' ) class="active" @endif>
							<a href="{{ route('p2p_onprogress','buy') }}">
							{{ __('ptop.apply_buy') }}
							</a>
						</li>

						<li @if($type=='sell' ) class="active" @endif>
							<a href="{{ route('p2p_onprogress','sell') }}">
							{{ __('ptop.apply_sell') }}
							</a>
						</li>
					</ul>

				</div>

				<!-- 진행중 리스트들 -->
				<div class="p2p_onpro_wrap mt-3">

					<div class="p2p_onpro_cards">
						@forelse($p2ps as $p2p)
						<!-- 구매신청 카드 -->
						<div class="list_in_con">

							<div class="in_left_con">

								<div class="hd">
									@if($p2p->b_id==Auth::user()->id)
									<label class="tit">{{ __('ptop.apply_buy') }}</label>
									<span class="have_coin_check">
										<a href="{{__('blockexplore.'.$p2p->coin_type).$p2p->s_coin_address}}" target="_blank">
										{{ __('ptop.confirm_possession') }}
										</a>
									</span> 
									<span class="go_chat">
										<a href="/chat?p2p_id={{$p2p->id}}" target='chat-frame' onclick="$('#chat_div_frame').show();">
										CHAT
										</a>
									</span> 
									@elseif($p2p->s_id==Auth::user()->id)
									<label class="tit">{{ __('ptop.apply_sell') }}</label>

									<span class="have_coin_check">

										<a href="{{__('blockexplore.'.$p2p->coin_type).$p2p->s_coin_address}}" target="_blank">
										{{ __('ptop.confirm_possession') }}
										</a>
									</span> 
									<span class="go_chat">
										<a href="/chat?p2p_id={{$p2p->id}}" target='chat-frame' onclick="$('#chat_div_frame').show();">
										CHAT
										</a>
									</span> 
									@endif
									<!-- 구매신청 2단계일 때 떠야하는 버튼입니다. -->

									@if($p2p->b_id==Auth::user()->id && $p2p->confirm==2)
									<a class="write_btn_st coin_in_check" onclick="confirm_okay({{$p2p->id}})">{{ __('ptop.coin_check_in') }}</a>									@endif
									<!-- //구매신청 2단계일 때 떠야하는 버튼입니다. -->

								</div>

								<div class="info_1 mt-3 mb-2">

									<img src="{{ asset('/storage/image/homepage/coin_img')}}/{{$p2p->coin_type}}.png" alt="coin_img" class="coin_symbol">
									<p class="coin_name">{{__('coin_name.'.$p2p->coin_type)}}</p>
									<span class="coin_name_eng ml-1">{{$p2p->coin_type}}</span>
								</div>

								<div class="info_2 mt-1 mb-3">
									<ul>
										<li class="amt">
											<label class="mr-2">{{ __('ptop.quantity') }}</label>

											<span>{{$p2p->coin_amount}}<span class="currency pl-1">{{strtoupper($p2p->coin_type)}}</span></span>
										</li>

										<li class="prc">
											<label class="mr-2">{{ __('ptop.price') }}</label>

											<span>{{$p2p->coin_price}}<span class="currency pl-1">{{strtoupper($p2p->country_money)}}</span></span>
										</li>
									</ul>
								</div>

							</div>

							@if($p2p->b_id==Auth::user()->id)

								<div class="step_ment">
									<p>{{ __('ptop.p_to_p_sentence1') }}</p>
									<span>{{$p2p->s_account}}</span>
								</div>
								<div class="writer_n_created_infor">

									<span><u>{{ __('ptop.writer') }}:</u> {{$p2p->name}}</span>
									<span><u>{{ __('ptop.application_date') }}:</u> {{date("Y-m-d",strtotime($p2p->start))}}</span>
								</div>
								<div>
									<button class="cancel btn" onclick="confirm_cancel({{$p2p->id}})">거래취소</button>
								</div>

							@elseif($p2p->s_id == Auth::user()->id)
								<div class="step_ment">
									<p>{{ __('ptop.you') }} {{$p2p->country_money}} {{ __('ptop.me') }} </p>
									<p>{{ __('ptop.sell_ment_deposit_confirm') }}</p>
								</div>
								<div class="writer_n_created_infor">

									<span><u>{{ __('ptop.writer') }}:</u> {{$p2p->name}}</span>
									<span><u>{{ __('ptop.application_date') }}:</u> {{date("Y-m-d",strtotime($p2p->start))}}</span>
								</div>
								<div>
									<button class="confirm btn" onclick="confirm_okay({{$p2p->id}})">입금확정</button>
									<button class="cancel btn" onclick="confirm_cancel({{$p2p->id}})">거래취소</button>
								</div>
							@endif

							{{--
							<div class="in_right_con" style="display:none">
								
								@if($p2p->b_id==Auth::user()->id)

								<ul>


									@if($p2p->confirm == 1)
										<li class="active">
									@elseif($p2p->confirm > 1)
										<li class="active complt">
									@endif
											<span class="status_icon"></span>
											<span class="status_tit">
											
												<span class="ing">
													<span class="currency">{{$p2p->country_money}}</span> {{ __('ptop.ptop_ing') }}
												</span>

												<span class="complt">
													<span class="currency">{{$p2p->country_money}}</span> {{ __('ptop.ptop_end') }}
												</span>

											</span>

											<div class="step_ment">
												<p>{{ __('ptop.p_to_p_sentence1') }}</p>
												<span>{{$p2p->s_account}}</span>
											</div>

										</li>
										{{-- 1단계 --}}

									@if($p2p->confirm == 2)
										<li class="active">
									@elseif($p2p->confirm > 2)
										<li class="active complt">
									@else
										<li>
									@endif
											<span class="status_icon"></span>
											<span class="status_tit">

												<span class="ing">
													<span class="currency">{{strtoupper($p2p->coin_type)}}</span> {{ __('ptop.ptop_outing') }}
												</span>

												<span class="complt">
													<span class="currency">{{strtoupper($p2p->coin_type)}}</span> {{ __('ptop.ptop_c')}}
												</span>

											</span>


											<div class="step_ment">
												<p>{{ __('ptop.p_to_p_sentence2') }}</p>
												<span>{{$p2p->s_coin_address}}</span>
											</div>

										</li>
										{{-- 2단계 --}}


									@if($p2p->confirm == 3)
										<li class="active">
									@else
										<li>
									@endif
											<span class="status_icon"></span>
											<span class="status_tit">{{ __('ptop.settlement_in_progress') }}</span>

											<div class="step_ment">
												<p>{{ __('ptop.p_to_p_sentence3') }}</p>
												<span>{{$p2p->s_coin_address}}</span>
											</div>
											<button class="cancel btn" onclick="confirm_cancel({{$p2p->id}})">거래취소</button>
										</li>
										{{-- 3단계 --}}

								</ul>

								@elseif($p2p->s_id == Auth::user()->id)
								
								<ul>
									@if($p2p->confirm == 1)
										<li class="active">
									@elseif($p2p->confirm > 1)
										<li class="active complt">
									@endif
											<span class="status_icon"></span>
											<span class="status_tit">
											
												<span class="ing">
													<span class="currency">{{$p2p->country_money}}</span> {{ __('ptop.ptop_ing') }}
												</span>

												<span class="complt">
													<span class="currency">{{$p2p->country_money}}</span> {{ __('ptop.ptop_end') }}
												</span>

											</span>


											<div class="step_ment">
												<p>{{ __('ptop.you') }} {{$p2p->country_money}} {{ __('ptop.me') }} </p>
												<p>{{ __('ptop.sell_ment_deposit_confirm') }}</p>
											</div>

										</li>
										{{-- 1단계 --}}


									@if($p2p->confirm == 2)
										<li class="active">
									@elseif($p2p->confirm > 2)
										<li class="active complt">
									@else
										<li>
									@endif
											<span class="status_icon"></span>
											<span class="status_tit">

												<span class="ing">
													<span class="currency">{{strtoupper($p2p->coin_type)}}</span> {{ __('ptop.ptop_ing') }}
												</span>
												<span class="complt">
													<span class="currency">{{strtoupper($p2p->coin_type)}}</span> {{ __('ptop.ptop_end') }}
												</span>

											</span>
											
											<div class="step_ment">
												<p>{{ __('ptop.home') }} {{strtoupper($p2p->coin_type)}} {{ __('ptop.give') }}</p>
												<span>{{$p2p->b_coin_address}}</span>
											</div>

										</li>
										{{-- 2단계 --}}

									@if($p2p->confirm == 3)
										<li class="active">
									@else
										<li>
									@endif
											<span class="status_icon"></span>
											<span class="status_tit">{{ __('ptop.settlement_in_progress') }}</span>
											
											<div class="step_ment">
												<p>{{ __('ptop.p_to_p_sentence3') }}</p>
												<span>{{$p2p->b_coin_address}}</span>
											</div>				
											<button class="confirm btn" onclick="confirm_okay({{$p2p->id}})">입금확정</button>
											<button class="cancel btn" onclick="confirm_cancel({{$p2p->id}})">거래취소</button>
										</li>
										{{-- 3단계 --}}

								</ul>
								@endif
								
							</div>
							--}}

						</div>
							@empty
								<div class="none_transac bd-dddd">
									<img src="/images/icon_notice.svg" alt="" class="btn_notice">{{ __('ptop.noinfo')}}
								</div>
							<!-- 구매신청 카드 -->
							@endforelse
							<!-- 판매신청 카드 -->
					</div>

				</div>
				<!-- //진행중 리스트들 -->

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

<div id="chat_div_frame" style="position: fixed; right: 0; bottom: 0; z-index:100; display:none;">
	<iframe id="chat-frame" name="chat-frame" style="width: 360px; height: 630px;" src="about:blank"></iframe>
</div>

@endsection