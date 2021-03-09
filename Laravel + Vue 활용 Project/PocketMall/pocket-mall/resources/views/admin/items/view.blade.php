@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
    <h1 class="mt-4">상품 리스트</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('admin.items')}}">상품</a></li>
        <li class="breadcrumb-item active">상품 수정</li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
          <form method="post" action="{{route('admin.items.update', $item->item_id)}}" enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}
            <div class="form-group">
              <label for="ca_id">카테고리</label>
              <select class="custom-select"  id="ca_id" name="ca_id" value="{{$item->ca_id}}">
                @foreach($categorys as $category)
                  <option value="{{$category->ca_id}}" {{$category->ca_id == $item->ca_id?'selected':''}}>{{$category->ca_name}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="title">상품명</label>
              <input type="text" id="title" name="title" value="{{$item->title}}"  class="form-control">
            </div>
            <div class="form-group">
              <label for="images">상품이미지</label>
              @if($images)
                <img id="image_section" src="/storage/{{$images[0]}}" alt="item image" style="width: 312px; height: 173px;"/>
              @else
              <img id="image_section" src="/storage/image/items/default_image.png" alt="item image" style="width: 312px; height: 173px;"/>
              @endif
              <input type="file" id="images" name="images[]" class="form-control">
            </div>
            <div class="form-group">
              <label for="simple_intro">상품 간략 소개</label>
              <input type="text" id="simple_intro" name="simple_intro" value="{{$item->simple_intro}}"  class="form-control">
            </div>
            <div class="form-group">
              <label for="editor">상품 소개(PC)</label>
              <textarea name="intro" id="editor" style="width:100%;min-height: 600px;border:none;resize:none; padding:10px;">{!! $item->intro !!}</textarea>
            </div>
            <div class="form-group">
              <label for="m_editor">상품 소개(Mobile)</label>
              <textarea name="m_intro" id="m_editor" style="width:100%;min-height: 600px;border:none;resize:none; padding:10px;">{!! $item->m_intro !!}</textarea>
            </div>
            <div class="form-group">
              <label for="video_url">유튜브 영상 url</label>
              <input type="text" id="video_url" name="video_url" value="{{$item->video_url}}"  class="form-control">
            </div>
            <div class="form-group">
              <label for="origin_price">원래가격</label>
              <input type="number" id="origin_price" name="origin_price" value="{{$item->origin_price}}"  class="form-control">
            </div>
            <div class="form-group">
              <label for="sale_price">판매가격</label>
              <input type="number" id="sale_price" name="sale_price" value="{{$item->sale_price}}"  class="form-control">
            </div>
            <div class="form-group">
              <label for="sell_yn">판매여부</label>
              <select class="custom-select"  id="sell_yn" name="sell_yn" value="{{$item->sell_yn}}">
                <option value="1">판매중</option>
                <option value="0">판매중지</option>
              </select>
            </div>
            <div class="form-group">
              <label for="tax_mny">공급가액</label>
              <input type="number" id="tax_mny" name="tax_mny" value="{{$item->tax_mny}}"  class="form-control" readonly>
            </div>
            <div class="form-group">
              <label for="vat_mny">부가세액</label>
              <input type="number" id="vat_mny" name="vat_mny" value="{{$item->vat_mny}}"  class="form-control" readonly>
            </div>
            <div class="form-group">
              <label for="created_at">생성날짜</label>
              <input type="text" id="created_at" name="created_at" value="{{$item->created_at}}"  class="form-control" readonly>
            </div>
            <div class="form-group">
              <label for="updated_at">수정날짜</label>
              <input type="text" id="updated_at" name="updated_at" value="{{$item->updated_at}}"  class="form-control" readonly>
            </div>
            <div>
              <button type="submit" class="btn btn-primary btn-lg btn-block">수정</button>
            </div>
          </form>
          <div>
            <form method="post" action="{{route('admin.items.destroy', $item->item_id)}}">
              @csrf
              {{ method_field('DELETE') }}
              <button type="submit" class="btn btn-danger btn-lg btn-block">삭제</button>
            </form>
          </div>
        </div>
    </div>
</div>


<style>
.ck.ck-editor__main>.ck-editor__editable{
  height:400px;
}
</style>


@endsection
