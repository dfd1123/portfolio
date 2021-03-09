@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	{{ __('admin_qna.inq')}}
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('admin_qna.list')}}
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;">{{ __('admin_qna.no')}}</th>
						<th style="width:30%;">{{ __('admin_qna.tt')}}</th>
						<th style="width:10%;">{{ __('admin_qna.act')}}</th>
						<th style="width:10%;">{{__('admin_qna.ask_user')}}</th>
						<th style="width:10%;">E-mail</th>
						<th style="width:10%;">{{ __('admin_qna.wr')}}</th>
						<th style="width:10%;">{{ __('admin_qna.edit')}}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($qnas as $qna)
					<tr>
						<td>{{$qna->id}}</td>
						<td>
                            @if($qna->answered == 0)
                                <a  href="{{route('admin.qna_answer_create', $qna->id)}}">{{$qna->title}}</a>
                            @else
                                <a href="{{route('admin.qna_answer_edit', $qna->id)}}">{{$qna->title}}</a>
                            @endif
                        </td>
						<td>
                            @if($qna->answered == 0)
                                <a class="wait_ans" href="{{route('admin.qna_answer_create', $qna->id)}}">{{ __('admin_qna.answer5')}}</a>
                            @else
                                <a class="complete_ans" href="{{route('admin.qna_answer_edit', $qna->id)}}">{{ __('admin_qna.answer6')}}</a>
                            @endif
                        </td>
						<td>{{$qna->fullname}}</td>
						<td>{{$qna->email}}</td>
						<td>{{date("Y-m-d", $qna->created)}}</td>
						<td>{{date("Y-m-d", $qna->updated)}}</td>
					</tr>
					@empty
					<tr>
						<th colspan="7">{{ __('admin_qna.nohere')}}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@if($qnas)
		{!! $qnas->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('admin_qna.up')}}
	</div>
</div>



@endsection