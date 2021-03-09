@extends('pc.layouts.app')

@section('content')
<div class="sub_spot mypage" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
	<h2>고객센터</h2>
</div>
<div id="container">
	<div class="my_cate">
		<ul>
			<li><a href="#" class="on">공지사항</a></li>
			<li><a href="{{route('faq.list')}}" class="">FAQ</a></li>
			<li><a href="{{route('events.index')}}" class="">이벤트</a></li>
		</ul>
	</div>
		<div class="noticebox pt30">
			<div class="basic_view">
				<div class="view_subject">
					<h4>{{ $notice->title }}</h4>
				</div>
				<div style="overflow:hidden;">
					<div class="view_info left">
						<dl>
							<dt>작성자 :</dt>
							<dd>관리자</dd>
						</dl>
					</div>
					<div class="view_info right">
						<dl>
							<dt>작성일 :</dt>
							<dd>{{ date("Y.m.d", strtotime($notice->created_at)) }}</dd>
						</dl>
						<dl>
							<dt>조회수 :</dt>
							<dd>{{$notice->hit }}</dd>
						</dl>
					</div>
				</div>
				@if($notice->file1 != NULL)
				<div class="attachment">
					<span><i class="fal fa-file-download"></i> 첨부파일 :</span>
					<span><a href="{{asset('/storage/notice/'.$notice->file1)}}" target="_blank">{{ $notice->file1 }}</a></span>
				</div>
				@endif
				<div class="write_view">
					{!! str_replace("\n","<br>",$notice->body); !!}
				</div>
				<div class="btleft pt10">
					<div class="btn_area" style="position: relative;top: 0;"><a href="{{url()->previous()}}" class="listgo">목록</a></div>
				</div>
			</div>
		</div>			
</div>

@endsection
