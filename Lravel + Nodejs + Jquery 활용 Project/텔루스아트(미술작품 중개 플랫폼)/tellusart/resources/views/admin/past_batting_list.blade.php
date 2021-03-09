@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
  <li class="breadcrumb-item active">베팅 관리</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
  <div class="card-header">
    지난 베팅 기록
  </div>
  <div class="card-body">
    <div>
      <select id="bat_cnt_sel">
        @if($past_batlis_latest_cnt != 0)
        <option value="0" {{$bat_cnt == 0 ? 'selected' : ''}}>전체</option>
        @for($i=1; $i<=$past_batlis_latest_cnt; $i++) <option value="{{$i}}" {{$bat_cnt == $i ? 'selected' : ''}}>
          {{$i}}회차</option>
          @endfor
          @else
          <option>진행된 베팅이 없음</option>
          @endif
      </select>
    </div>

    <ul class="nav nav-tabs">
      <li {{($ca_id == 0)?'class="active"':''}}><a
          href="{{route('admin.past_batting_list',["ca_id"=>0, "bat_cnt"=>$bat_cnt])}}">전체</a></li>
      @foreach($categorys as $index => $category)
      <li {{($index == 0)?'class="active"':''}}><a
          href="{{route('admin.past_batting_list',["ca_id"=>$category->id, "bat_cnt"=>$bat_cnt])}}">{{$category->ca_name}}</a>
      </li>
      @endforeach
    </ul>

    <div class="table-responsive tsa-table-wrap">
      <table class="table table-bordered prd_adm_table" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th rowspan="2">회차</th>
            <th rowspan="2">순위</th>
            <th rowspan="2" style="width:15%;">이미지</th>
            <th rowspan="2">작품제목</th>
            <th>등록자</th>
            <th>누적베팅건수</th>
            <th>베팅시작날짜</th>
            <th rowspan="2">조회수</th>
          </tr>
          <tr>
            <th>아이디</th>
            <th>누적베팅금액</th>
            <th>베팅종료날짜</th>
          </tr>
        </thead>
        <tbody>
          @forelse($past_batlis as $past_batli)
          <tr>
            <td rowspan="2">{{$past_batli->bat_cnt}}회차</td>
            <td rowspan="2">{{$past_batli->bat_ranking}}위</td>
            <td rowspan="2">
              <img src="{{isset($past_batli->product) ? asset('storage/image/product/'.$past_batli->product->image1) : asset('storage/image/howtouse/no_image.svg') }}" />
            </td>
          <td rowspan="2">{{$past_batli->art_name}} {{ isset($past_batli->product) ? '' : '(삭제됨)' }}</td>
            <td>{{$past_batli->user->name}}</td>
            <td>
              {{$past_batli->total_hit}}
            </td>
            <td>{{$past_batli->start_time}}</td>
            <td rowspan="2">{{ isset($past_batli->product->get_hit) ? $past_batli->product->get_hit : '0' }}</td>
          </tr>
          <tr>
            <td>{{$past_batli->user->email}}</td>
            <td>
              {{$past_batli->total_price}}
            </td>
            <td>{{$past_batli->end_time}}</td>
          </tr>
          @empty
          <tr>
            <td colspan="7">작품이 존재하지 않습니다.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    {{ $past_batlis->links() }}
  </div>
  <div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
</div>

@endsection

@section('script')

<script>
  $('#bat_cnt_sel').change(function(e){
    var batCnt = $('#bat_cnt_sel').val();
    window.location.href = '/admin/past_batting_list/' + '{{$ca_id}}' + '/' + batCnt;
  });
</script>

@endsection