@extends('pc.layouts.app')

@section('content')
<div class="sub_spot center" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
	<h2 class="mt140">고객센터</h2>
</div>
<div id="container">
	<div class="my_cate">
		<ul>
			<li style="position:relative;">
				<a href="#" class="on">
					공지사항
					@auth
						@if($newnotice_cnt > 0)
							<div class="newcircle">
								{{$newnotice_cnt}}
							</div>
						@endif
					@else
						@if($count1 > 0)
							<div class="newcircle">
								{{$count1}}
							</div>
						@endif
					@endauth
				</a>
			</li>
			<li style="position:relative;">
				<a href="{{route('faq.list')}}" class="">
					FAQ
					@auth
						@if($newfaq_cnt > 0)
							<div class="newcircle">
								{{$newfaq_cnt}}
							</div>
						@endif
					@else
						@if($count2 > 0)
							<div class="newcircle">
								{{$count2}}
							</div>
						@endif
					@endauth
				</a>
			</li>
			<li style="position:relative;">
				<a href="{{route('events.index')}}" class="">
					이벤트
					@auth
						@if($newevent_cnt > 0)
							<div class="newcircle">
								{{$newevent_cnt}}
							</div>
						@endif
					@else
						@if($count3 > 0)
							<div class="newcircle">
								{{$count3}}
							</div>
						@endif
					@endauth
				</a>
			</li>
		</ul>
	</div>
	<div class="noticebox">
		<form method="get" action="" id="frm">
			<div class="period">
				<ul>
					<li>
						<div class="select">
							<select name="status">
								<option>제목+내용</option>
							</select>
							<div class="select__arrow"></div>
						</div>
					</li>
					<li>
						<input type="text" name="search" title="검색어" id="" value="" class="input_normal">
					</li>
					<li>	<a href="#" onclick="document.getElementById('frm').submit();" title="검색" class="normal_btn">검색</a></li>
				</ul>
			</div>	
		</form>
		<!-- 게시판 시작 -->
			<div class="table_responsive">
				<table class="basic">
					<thead>
						<tr>
							<th>번호 </th>
							<th>제목</th>
							<th>작성자</th>
							<th>작성일</th>
							<th>조회수</th>
						</tr>
					</thead>
					<tbody>
					@forelse($notices as $key => $notice)
						<tr>
							<td>{{$notice->id}}</td>
							<td class="left">
								<a href="{{route('notices.show',$notice->id)}}"> {{ $notice->title }}</a>
								@if(date("Y.m.d", strtotime("-2 days")) < date("Y.m.d", strtotime($notice->created_at)))
								<em style="color:#fea803">New</em>
								@endif
							</td>
							<td>관리자</td>
							<td class="num">{{ date("Y.m.d", strtotime($notice->created_at)) }}</td>
							<td class="num">{{ $notice->hit }}</td>
							
						</tr>
					@empty
					<tr>
						<td colspan="5">
							공지사항이 없습니다.
						</td>
					</tr>
					@endforelse
					</tbody>
				</table>
			</div>	

		</div>
	</div>

@if($notices)
{!! $notices->render() !!}
@endif
@endsection
