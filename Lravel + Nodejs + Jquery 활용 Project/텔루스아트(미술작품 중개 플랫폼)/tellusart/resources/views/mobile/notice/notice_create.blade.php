@extends('layouts.app')

@section('content')
<form enctype="multipart/form-data" method="POST" action="{{ route('notices.store') }}">
	
	@csrf
	
	제목
	<input type="text" id="title" name="title" value="{{ old('title') }}" />
	내용
	<input type="text" name="body" value="{{ old('body') }}" />
	파일
	<input type="file" name="file1" value="{{ old('file1') }}" /><br />
	<button type="submit">제출</button>
</form>

@endsection