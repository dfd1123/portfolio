@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
    <h1 class="mt-4">상품 옵션 리스트</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('admin.items')}}">상품</a></li>
        <li class="breadcrumb-item">{{$item->title}}</li>
        <li class="breadcrumb-item active">상품 옵션 추가</li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
          <form method="post"  id="item_form" action="{{route('admin.item_options.insert')}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="item_id" value="{{$item->item_id}}" />
            <div class="form-group">
              <label for="op_type">옵션 타입</label>
              <input type="text" id="op_type" name="op_type"  class="form-control">
            </div>
            <div class="form-group">
              <label for="op_concept">옵션 컨셉</label>
              <input type="text" id="op_concept" name="op_concept" class="form-control">
            </div>
            <div class="form-group">
              <label for="images">옵션 이미지</label>
              <img id="image_section" src="/storage/image/items/default_image.png" alt="item image" style="width: 312px; height: 173px;"/>
              <input type="file" id="images" name="op_images[]" class="form-control">
            </div>
            <div class="form-group">
              <label for="op_simple_intro">옵션 간략 소개</label>
              <input type="text" id="op_simple_intro" name="op_simple_intro" class="form-control">
            </div>
            <div class="form-group">
              <label for="editor">옵션 상세 설명(PC)</label>
              <textarea name="op_intro" id="editor" style="width:100%;min-height: 600px;border:none;resize:none; padding:10px;"></textarea>
            </div>
            <div class="form-group">
              <label for="m_editor">옵션 상세 설명(Mobile)</label>
              <textarea name="op_m_intro" id="m_editor" style="width:100%;min-height: 600px;border:none;resize:none; padding:10px;"></textarea>
            </div>
            <div class="form-group">
              <label for="op_video_url">옵션 유튜브 영상 url</label>
              <input type="text" id="op_video_url" name="op_video_url"  class="form-control">
            </div>
            <div class="form-group" style="display:none;">
              <label for="op_origin_price">원래가격</label>
              <input type="number" id="op_origin_price" name="op_origin_price"  class="form-control">
            </div>
            <div class="form-group">
              <label for="op_sale_price">판매가격</label>
              <input type="number" id="op_sale_price" name="op_sale_price"  class="form-control">
            </div>
            <div class="form-group">
              <label for="op_sell_yn">판매여부</label>
              <select class="custom-select"  id="op_sell_yn" name="op_sell_yn" value="1">
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





@endsection
