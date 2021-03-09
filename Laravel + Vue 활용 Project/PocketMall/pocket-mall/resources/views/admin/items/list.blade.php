@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
    <h1 class="mt-4">상품 리스트</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('admin.settings')}}">상품</a></li>
        <li class="breadcrumb-item active">상품 관리</li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
          <div class="mb-10"  style="overflow:hidden;">
              <a href="{{route('admin.items.create')}}" class="btn btn-success btn-sm float-right">상품 추가</a>
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
                    <div class="tab-pane fade show {{$loop->first?'active':''}}" id="list-{{$category->ca_id}}" role="tabpanel" aria-labelledby="list-{{$category->ca_id}}-list">
                      <div id="list-example" class="list-group">  
                        @forelse($items as $item)
                          @if($category->ca_id === $item->ca_id)
                            <a class="list-group-item list-group-item-action" href="{{route('admin.items.edit', $item->item_id)}}">{{$item->title}}</a>
                          @endif
                        @empty
                            상품 없음
                        @endforelse
                      </div>
                    </div>
                @endforeach
              </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
  $('.delete_category').on('submit', function(){
    if(confirm('정말 삭제하시겠습니까?')){
      return true;
    }else{
      return false;
    }
  });
</script>

@section('script')



@endsection