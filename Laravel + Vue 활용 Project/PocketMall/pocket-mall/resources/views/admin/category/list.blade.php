@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
    <h1 class="mt-4">카테고리 리스트</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('admin.settings')}}">카테고리</a></li>
        <li class="breadcrumb-item active">카테고리 관리</li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
          <div class="mb-10"  style="overflow:hidden;">
            <form method="post" action="{{route('admin.category.insert')}}">
              @csrf
              <button type="submit" class="btn btn-success btn-sm float-right">카테고리 추가</button>
            </form>
          </div>
          <div class="row">
            <div class="col-4">
              <div class="list-group" id="list-tab" role="tablist">
                @foreach($categorys as $category)
                  @if ($loop->first)
                    <a class="list-group-item list-group-item-action active" id="list-{{$category->ca_id}}-list" data-toggle="list" href="#list-{{$category->ca_id}}" role="tab" aria-controls="{{$category->ca_id}}">{{$category->ca_name}}</a>
                  @else
                    <a class="list-group-item list-group-item-action" id="list-{{$category->ca_id}}-list" data-toggle="list" href="#list-{{$category->ca_id}}" role="tab" aria-controls="{{$category->ca_id}}">{{$category->ca_name}}</a>
                  @endif
                @endforeach
              </div>
            </div>
            <div class="col-8">
              <div class="tab-content" id="nav-tabContent">
                @foreach($categorys as $category)
                  @if ($loop->first)
                    <div class="tab-pane fade show active" id="list-{{$category->ca_id}}" role="tabpanel" aria-labelledby="list-{{$category->ca_id}}-list">
                      <form method="post" action="{{route('admin.category.update', $category->ca_id)}}">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="form-group">
                          <label for="ca_name">카테고리명</label>
                          <input type="text" id="ca_name" name="ca_name" value="{{$category->ca_name}}"  class="form-control">
                        </div>
                        <div class="form-group">
                          <label for="ca_intro">카테고리 인사글</label>
                          <textarea id="ca_intro" name="ca_intro"  class="form-control">{!! $category->ca_intro !!}</textarea>
                        </div>
                        <div class="form-group">
                          <label for="ca_use">사용유무</label>
                          <select class="custom-select"  id="ca_use" name="ca_use" value="{{$category->ca_use}}">
                            <option value="1">사용</option>
                            <option value="0">사용안함</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="ca_order">노출순서</label>
                          <input type="text" id="ca_order" name="ca_order" value="{{$category->ca_order}}"  class="form-control">
                        </div>
                        <div class="form-group">
                          <label for="created_at">생성날짜</label>
                          <input type="text" id="created_at" name="created_at" value="{{$category->created_at}}"  class="form-control" readonly>
                        </div>
                        <div class="form-group">
                          <label for="updated_at">수정날짜</label>
                          <input type="text" id="updated_at" name="updated_at" value="{{$category->updated_at}}"  class="form-control" readonly>
                        </div>
                        <div>
                          <button type="submit" class="btn btn-primary btn-lg btn-block">수정</button>
                        </div>
                      </form>
                      <div>
                        <form method="post" class="delete_category" action="{{route('admin.category.destroy', $category->ca_id)}}">
                          @csrf
                          {{ method_field('DELETE') }}
                          <button type="submit" class="btn btn-danger btn-lg btn-block">삭제</button>
                        </form>
                      </div>
                    </div>
                  @else
                    <div class="tab-pane fade show" id="list-{{$category->ca_id}}" role="tabpanel" aria-labelledby="list-{{$category->ca_id}}-list">
                    <form method="post" action="{{route('admin.category.update', $category->ca_id)}}">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="form-group">
                          <label for="ca_name">카테고리명</label>
                          <input type="text" id="ca_name" name="ca_name" value="{{$category->ca_name}}"  class="form-control">
                        </div>
                        <div class="form-group">
                          <label for="ca_intro">카테고리 인사글</label>
                          <textarea id="ca_intro" name="ca_intro"  class="form-control">{!! $category->ca_intro !!}</textarea>
                        </div>
                        <div class="form-group">
                          <label for="ca_use">사용유무</label>
                          <select class="custom-select"  id="ca_use" name="ca_use" value="{{$category->ca_use}}">
                            <option value="1">사용</option>
                            <option value="0">사용안함</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="ca_order">노출순서</label>
                          <input type="text" id="ca_order" name="ca_order" value="{{$category->ca_order}}"  class="form-control">
                        </div>
                        <div class="form-group">
                          <label for="created_at">생성날짜</label>
                          <input type="text" id="created_at" name="created_at" value="{{$category->created_at}}"  class="form-control" readonly>
                        </div>
                        <div class="form-group">
                          <label for="updated_at">수정날짜</label>
                          <input type="text" id="updated_at" name="updated_at" value="{{$category->updated_at}}"  class="form-control" readonly>
                        </div>
                        <div>
                          <button type="submit" class="btn btn-primary btn-lg btn-block">수정</button>
                        </div>
                      </form>
                      <div>
                        <form method="post" class="delete_category" action="{{route('admin.category.destroy', $category->ca_id)}}">
                          @csrf
                          {{ method_field('DELETE') }}
                          <button type="submit" class="btn btn-danger btn-lg btn-block">삭제</button>
                        </form>
                      </div>
                    </div>
                  @endif
                @endforeach
              </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
  $( document ).ready(function() {
    $('.delete_category').bind('submit', function(e){
      e.preventDefault();
      if(confirm('정말 삭제하시겠습니까?')){
        return false;
      }else{
        return false;
      }
    });
  });
</script>

@section('script')



@endsection