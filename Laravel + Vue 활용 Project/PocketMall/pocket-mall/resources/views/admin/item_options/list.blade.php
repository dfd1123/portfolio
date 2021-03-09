@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
    <h1 class="mt-4">상품 옵션 리스트</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('admin.item_options')}}">상품</a></li>
        <li class="breadcrumb-item active">상품 옵션 관리</li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
        <select id="category_select" class="custom-select" style="margin-bottom:30px;" value="{{$ca_id}}">
          @foreach($categorys as $category)
            <option value="{{$category->ca_id}}" {{$category->ca_id == $ca_id?'selected':''}}>{{$category->ca_name}}</option>
          @endforeach
        </select>
          <div class="row">
            <div class="col-4">
              <div class="list-group" id="list-tab" role="tablist">
                @foreach($items as $item)
                  @if ($loop->first)
                    <a class="list-group-item list-group-item-action active" id="list-{{$item->item_id}}-list" data-toggle="list" href="#list-{{$item->item_id}}" role="tab" aria-controls="{{$item->item_id}}">{{$item->title}}</a>
                  @else
                    <a class="list-group-item list-group-item-action" id="list-{{$item->item_id}}-list" data-toggle="list" href="#list-{{$item->item_id}}" role="tab" aria-controls="{{$item->item_id}}">{{$item->title}}</a>
                  @endif
                @endforeach
              </div>
            </div>
            <div class="col-8">
              <div class="tab-content" id="nav-tabContent">
                @foreach($items as $item)
                    <div class="tab-pane fade show {{$loop->first?'active':''}}" id="list-{{$item->item_id}}" role="tabpanel" aria-labelledby="list-{{$item->item_id}}-list">
                      <div id="list-example" class="list-group">  
                        @foreach($item_options as $option)
                          @if($item->item_id === $option->item_id)
                            <a class="list-group-item list-group-item-action" href="{{route('admin.item_options.edit', $option->op_id)}}">{{$option->op_type}}({{$option->op_concept}})/{{$option->op_simple_intro}}/추가비용:+{{number_format($option->op_sale_price, 0)}}원</a>
                          @endif
                        @endforeach
                        <a class="btn btn-success btn-lg btn-block" href="{{route('admin.item_options.create')}}?item_id={{$item->item_id}}">+ 추가</a>
                      </div>
                    </div>
                @endforeach
              </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')



@endsection