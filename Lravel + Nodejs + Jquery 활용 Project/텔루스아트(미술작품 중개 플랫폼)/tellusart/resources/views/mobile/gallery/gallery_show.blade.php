@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('reviews.storet',$gallery_id) }}">
	
	@csrf
	댓글내용
	<input type="text" name="review_body" value="{{ old('review_body') }}" />
	점수
	<input type="text" name="rating" value="{{ old('rating') }}" />
	
	<button type="submit">제출</button>
</form>

@endsection