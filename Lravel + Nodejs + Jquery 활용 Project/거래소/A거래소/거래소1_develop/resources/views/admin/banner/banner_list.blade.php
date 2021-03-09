@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	{{ __('banner.b_set')}}
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('banner.b_list')}}
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;">{{ __('banner.b_id')}}</th>
						<th style="width:20%;">{{ __('banner.b_link')}}</th>
						<th style="width:5%;">{{ __('banner.b_pc')}}</th>
						<th style="width:5%;">{{ __('banner.b_mo')}}</th>
						<th style="width:25%;">{{ __('banner.b_exp')}}</th>
						<th style="width:5%;">{{ __('banner.b_pos')}}</th>
						<th style="width:5%;">{{ __('banner.b_lan')}}</th>
						<th style="width:5%;">{{ __('banner.b_ifuse')}}</th>
						<th style="width:10%;">{{ __('banner.set')}}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($banners as $banner)
					<tr>
						<td>{{$banner->id}}</td>
						<td><a href="{{$banner->target_url}}" target="_blank">{{$banner->target_url}}</a></td>
						<td>
							@if($banner->banner_url == null)
							<span>{{ __('banner.noimage')}}</span>
							@else
							<a href="{{asset('/storage/image/banner/'.$banner->banner_url)}}" target="_blank">{{ __('banner.see')}}</a>
							@endif
						</td>
						<td>
							@if($banner->banner_mobile_url == null)
							<span>{{ __('banner.noimage')}}</span>
							@else
							<a href="{{asset('/storage/image/banner/'.$banner->banner_mobile_url)}}" target="_blank">{{ __('banner.see')}}</a>
							@endif
						</td>
						<td>{{$banner->detail}}</td>
						<td>
							@if($banner->position == 'top')
							{{ __('banner.b_top')}}
							@elseif($banner->position == 'left')
							{{ __('banner.b_left')}}
							@elseif($banner->position == 'right')
							{{ __('banner.b_right')}}
							@endif
						</td>
						<td>{{strtoupper($banner->lang)}}</td>
						<td>{{$banner->active == 1 ? __('banner.b_use') : __('banner.b_nouse')}}</td>
						<td><a href="{{route('admin.banner_edit', $banner->id)}}" class="myButton edit">{{ __('banner.edit')}}</a>&nbsp<a href="{{route('admin.banner_delete', $banner->id)}}" class="link-delete myButton del" onclick="return confirm('정말 배너를 삭제하시겠습니까?');">삭제</a></td>
					</tr>
					@empty
					<tr>
						<th colspan="9">{{ __('banner.b_nohere')}}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@if(Auth::guard('admin')->user()->level <= 4)
		<div>
			<button type="button" class="mint_btn" onclick="location.href='{{route('admin.banner_create')}}'">{{ __('banner.add')}}</button>
		</div>
		@endif
		@if($banners_page)
		{!! $banners_page->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('banner.b_update')}}
</div>

@endsection

@section('script')

@endsection