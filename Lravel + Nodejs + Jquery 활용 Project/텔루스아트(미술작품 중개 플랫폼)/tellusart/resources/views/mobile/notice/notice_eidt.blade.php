@extends('layouts.app')

@section('content')
<form enctype="multipart/form-data" method="POST" action="{{ route('notices.update',$notice->id) }}">
	
	@csrf
	{{ method_field('PUT')}}
	제목
	<input type="text" id="title" name="title" value="{{ $notice->title }}" />
	내용
	<input type="text" name="body" value="{{ $notice->body }}" />
	파일
	<input type="file" name="file1" value="{{ $notice->file1 }}" /><br />
	<button type="submit">제출</button>
</form>

@endsection