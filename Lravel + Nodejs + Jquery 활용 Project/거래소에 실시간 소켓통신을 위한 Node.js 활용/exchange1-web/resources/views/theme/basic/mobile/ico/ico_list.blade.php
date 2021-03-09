
@extends(session('theme').'.mobile.layouts.app') 
@section('content')
	@include(session('theme').'.mobile.ico.include.sub_menu')
	@include(session('theme').'.mobile.ico.include.select_bar')
	
<!-- scrl_wrap -->
<div id="ico_list_wrap{{$my}}" class="scrl_wrap m_ico_wrap">

	<div id="ico_list_div{{$my}}" class="ico_list_group" data-offset="{{$offset}}" data-count="{{$count}}" data-category="{{$category}}">

		@forelse($icos as $ico)
		
		<div class="list_in_con_outer">
			<div class="list_in_con">
				<a href="{{route('ico_show',$ico->id)}}">
					<div class="thumnail @if ($ico->active == 0)waiting @elseif ($ico->ico_category == 1 && $ico->active==1)oncoming confirm @elseif ($ico->ico_category == 2 && $ico->active==1)upcoming confirm @elseif ($ico->ico_category == 3 && $ico->active==1)end confirm @elseif($ico->ico_category == 4 && $ico->active==1)soldout confirm @endif">
						@if($ico->ico_thumnail == NULL)
						<img src="{{asset('/storage/image/ico/no_image.jpg')}}" alt="" /><br> 
						@else
						<img src="{{asset('/storage/image/ico'.$ico->ico_thumnail)}}" alt="" /><br>
						@endif
					</div>

					<div class="infos">
						<p class="hd">
							{{-- 파는 코인 / 글제목 --}}
							<span>{{$ico->ico_symbol}}</span>
							<span>{{$ico->ico_title}}</span>
						</p>

						<p class="info _txt">
							{{-- 소개내용 --}} {{$ico->ico_intro}}
						</p>

						<p class="info _period">
							{{-- 시작기간 시간 ~ 마감기간 시간 --}} {{date("Y-m-d", strtotime($ico->ico_from))}} ~ {{date("Y-m-d", strtotime($ico->ico_to))}}
						</p>

						<p class="info _psbcoin">
							{{-- 구매가능한 코인 --}}
							<label>{{$ico->ico_collect}}</label>
							<span>1 {{$ico->ico_symbol}} ≈ {{$ico->ico_coin_p}} {{$ico->ico_collect}}</span>
						</p>

						<p class="info _minimal">
							<label>{{ __('icoo.minimum_buy')}}</label>
							<span>{{$ico->ico_min}} {{$ico->ico_symbol}}</span>
						</p>

						<p class="info text-right _date">
							{{-- 작성자 및 작성일 --}} [ {{$ico->w_name}} ] {{$ico->created_at}}
						</p>
					</div>

				</a>
			</div>
		</div>
		@empty
		<div class="non_data">
			<i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('icoo.no_submit')}}
		</div>
		@endforelse

	</div>
	
</div>
<!-- //scrl_wrap -->

@endsection