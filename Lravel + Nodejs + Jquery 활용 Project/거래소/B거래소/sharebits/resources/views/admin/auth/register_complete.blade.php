@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">{{ __('auth2.join')}}</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('auth2.complete')}}
	</div>
	<div class="card-body">
		<h1>{{ __('auth2.wow')}}</h1>

	</div>
	<div class="card-footer small text-muted">{{ __('auth2.update')}}</div>
</div>

@endsection

@section('script')



@endsection