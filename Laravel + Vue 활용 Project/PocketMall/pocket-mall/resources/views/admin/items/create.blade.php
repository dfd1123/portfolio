@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
    <h1 class="mt-4">상품 리스트</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('admin.items')}}">상품</a></li>
        <li class="breadcrumb-item active">상품 추가</li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
          <form method="post"  id="item_form" action="{{route('admin.items.insert')}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="ca_id">카테고리</label>
              <select class="custom-select"  id="ca_id" name="ca_id">
                @foreach($categorys as $category)
                  <option value="{{$category->ca_id}}">{{$category->ca_name}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="title">상품명</label>
              <input type="text" id="title" name="title"  class="form-control">
            </div>
            <div class="form-group">
              <label for="images">상품이미지</label>
              <img id="image_section" src="/storage/image/items/default_image.png" alt="item image" style="width: 312px; height: 173px;"/>
              <input type="file" id="images" name="images[]" class="form-control">
            </div>
            <div class="form-group">
              <label for="simple_intro">상품 간략 소개</label>
              <input type="text" id="simple_intro" name="simple_intro" class="form-control">
            </div>
            <div class="form-group">
              <label for="editor">상품 소개(PC)</label>
              <textarea name="intro" id="editor" style="width:100%;min-height: 600px;border:none;resize:none; padding:10px;"></textarea>
            </div>
            <div class="form-group">
              <label for="m_editor">상품 소개(Mobile)</label>
              <textarea name="m_intro" id="m_editor" style="width:100%;min-height: 600px;border:none;resize:none; padding:10px;"></textarea>
            </div>
            <div class="form-group">
              <label for="video_url">유튜브 영상 url</label>
              <input type="text" id="video_url" name="video_url"  class="form-control">
            </div>
            <div class="form-group">
              <label for="origin_price">원래가격</label>
              <input type="number" id="origin_price" name="origin_price"  class="form-control">
            </div>
            <div class="form-group">
              <label for="sale_price">판매가격</label>
              <input type="number" id="sale_price" name="sale_price"  class="form-control">
            </div>
            <div class="form-group">
              <label for="sell_yn">판매여부</label>
              <select class="custom-select"  id="sell_yn" name="sell_yn" value="1">
                <option value="1">판매중</option>
                <option value="0">판매중지</option>
              </select>
            </div>
            <div>
              <button type="submit" class="btn btn-primary btn-lg btn-block">추가</button>
            </div>
          </form>
        </div>
    </div>
</div>


<style>
.ck.ck-editor__main>.ck-editor__editable{
  height:400px;
}
</style>


@endsection
