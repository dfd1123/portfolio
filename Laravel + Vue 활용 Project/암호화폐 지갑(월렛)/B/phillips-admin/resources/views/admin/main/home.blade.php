@extends('admin.layouts.app')

@section('content')

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<div class="card-body">
		<div class="row">
			<div class="col-xl-3 col-sm-6 mb-3">
				<div class="card text-white bg-primary o-hidden h-100">
					<div class="card-body">
						<div class="card-body-icon tsa-body-icon">
							<i class="fas fa-fw fa-users"></i>
						</div>
						<div class="main_news">
							1:1 문의 미응답<span>{{$qna_counts_kr}}</span>
						</div>
					</div>
					<a class="card-footer text-white clearfix small z-1" href="{{route('admin.qna_list','kr')}}"> <span
							class="float-left">{{ __('main.see') }}</span> <span class="float-right"> <i
								class="fas fa-angle-right"></i> </span> </a>
				</div>
			</div>
		</div>
		<div class="card-footer small text-muted">{{$datetime}}{{ __('home.up')}}</div>
	</div>

	@endsection

	@section('script')
	<script>
	</script>
	@endsection