@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	언론보도 관리
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	언론보도 리스트
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
					@forelse($news_lists as $news)
					<tr>
						<td>{{$news->id}}</td>
						<td><a href="{{route('admin.news_edit', ['country' => $country, 'id' => $news->id])}}">{{$news->title}}</a></td>
						<td>{{date("Y-m-d", $news->created)}}</td>
						<td>{{date("Y-m-d", $news->updated)}}</td>
						<td>{{$news->view}}</td>
					</tr>
					@empty
					<tr>
						<th colspan="7"> {{ __('notice.nohere')}}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div>
			<button type="button" class="mint_btn" onclick="location.href='{{route('admin.news_create', $country)}}'">추가</button>
		</div>
		@if($news_lists)
		{!! $news_lists->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }} {{ __('notice.update')}}
	</div>
</div>



@endsection