@extends('mobile.layouts.app')

@section('content')
<div class="sub-container">
	
	<div class="board_list">
	
		<div class="blist_box">
			<div class="date en">
				{{ date("Y.m.d", strtotime($notice->created_at)) }}
			</div>
			<h3>{{ $notice->title }}</h3>
			<div class="conbox">
				<p><img src="{{asset('/storage/image/event/'.$notice->file1)}}" alt=""/></p>
				{!! str_replace("\n","<br>",$notice->body) !!}
			</div>
			
			<dl>
				<dt>조회수 : </dt>
				<dd>{{$notice->hit}}</dd>
			</dl>
			@if($notice->file1 !=null)
			<dl>
				<dt>첨부파일 1 : </dt>
				<dd><img src="{{asset('/storage/image/event/'.$notice->file1)}}" alt=""/>img_pic_view.png </dd>
			</dl>
			@endif
		</div>
		<button type="button" class="joinbt" onclick="history.back();">목록으로 돌아가기</button>
	</div>
</div>

@endsection
