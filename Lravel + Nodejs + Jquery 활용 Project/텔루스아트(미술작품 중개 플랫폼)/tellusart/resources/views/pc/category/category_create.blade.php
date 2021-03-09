@extends('pc.layouts.app')

@section('content')
<form enctype="multipart/form-data" method="POST" action="{{ route('categorys.store') }}">
	
	@csrf
	카테고리명
	<input type="text" name="ca_name" value="{{ old('ca_name') }}" />
	 카테고리설명
	<input type="text" name="ca_discript" value="{{ old('ca_discript') }}" />
	카테고리사진
	<input type="file" name="ca_icon" value="{{ old('ca_icon') }}" />
	
	<button type="submit">제출</button>
</form>

@endsection