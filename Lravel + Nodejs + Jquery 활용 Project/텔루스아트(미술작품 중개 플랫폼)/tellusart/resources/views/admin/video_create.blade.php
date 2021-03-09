@extends('admin.layouts.app')

@section('content')

<form method="post" action="{{route('admin.video_store')}}">

	@csrf

  <div class="form-group">
    <label for="service_name">영상제목:</label>
    <input type="text" id="service_name" name="title" class="form-control" required="required" />
  </div>
  <div class="form-group">
    <label for="company_name">영상링크(유튜브):</label>
    <input type="text" id="company_name" name="video_link" class="form-control" required="required" />
  </div>
  <button type="submit" class="btn btn-default">추가</button>
  <button type="button" class="btn btn-default" onclick="location.href='{{route('admin.video_list')}}'">목록</button>
</form>

@endsection