@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	속보 관리
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	속보 목록
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;"> {{ __('notice.no')}}</th>
						<th style="width:40%;"> {{ __('notice.tt')}}</th>
						<th style="width:10%;"> {{ __('notice.wr')}}</th>
						<th style="width:10%;"> {{ __('notice.ed')}}</th>
						<th style="width:10%;"> {{ __('notice.view')}}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($newsflashs as $newsflash)
					<tr>
						<td>{{$newsflash->id}}</td>
						<td><a href="{{route('admin.newsflash_edit', ['country' => $country, 'id' => $newsflash->id])}}">{{$newsflash->title}}</a></td>
						<td>{{date("Y-m-d", $newsflash->created)}}</td>
						<td>{{date("Y-m-d", $newsflash->updated)}}</td>
						<td>{{$newsflash->view}}</td>
					</tr>
					@empty
					<tr>
						<th colspan="7">현재 속보가 존재하지 않습니다.</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div>
			<button type="button" class="mint_btn" onclick="location.href='{{route('admin.newsflash_create', $country)}}'">추가</button>
		</div>
		@if($newsflashs)
		{!! $newsflashs->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }} {{ __('notice.update')}}
	</div>
</div>



@endsection