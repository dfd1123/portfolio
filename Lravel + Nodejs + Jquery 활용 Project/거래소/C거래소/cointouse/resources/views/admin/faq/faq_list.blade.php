@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	{{ __('faq.set')}}
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('faq.q')}}
	</div>
	<div class="card-body">
        <ul class="nav nav-tabs">
            <li class="{{ ($types==0)?'active':'' }}"><a href="{{route('admin.faq_list', ['country'=>$country , 'types'=>0])}}">{{__('faq.all')}}</a></li>
            <li class="{{ ($types==1)?'active':'' }}"><a href="{{route('admin.faq_list', ['country'=>$country , 'types'=>1])}}">{{__('faq.1')}}</a></li>
            <li class="{{ ($types==2)?'active':'' }}"><a href="{{route('admin.faq_list', ['country'=>$country , 'types'=>2])}}">{{__('faq.2')}}</a></li>
            <li class="{{ ($types==3)?'active':'' }}"><a href="{{route('admin.faq_list', ['country'=>$country , 'types'=>3])}}">{{__('faq.3')}}</a></li>
            <li class="{{ ($types==4)?'active':'' }}"><a href="{{route('admin.faq_list', ['country'=>$country , 'types'=>4])}}">{{__('faq.4')}}</a></li>
        </ul>
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;">{{ __('faq.no')}}</th>
                        <td style="width:10%;">{{ __('faq.dv')}}</td>
						<th style="width:40%;">{{ __('faq.q1')}}</th>
						<th style="width:10%;">{{ __('faq.wr')}}</th>
						<th style="width:10%;">{{ __('faq.ed')}}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($faqs as $faq)
					<tr>
						<td>{{$faq->id}}</td>
                        <td>
                            {{__('faq.'.$faq->faq_type)}}
                        </td>
						<td><a href="{{route('admin.faq_edit', ['country' => $country, 'id' => $faq->id])}}">{{$faq->question}}</a></td>
						<td>{{date("Y-m-d", $faq->created)}}</td>
						<td>{{date("Y-m-d", $faq->updated)}}</td>
					</tr>
					@empty
					<tr>
						<th colspan="7">{{ __('faq.nohere')}}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div>
			<button type="button" class="mint_btn" onclick="location.href='{{route('admin.faq_create', $country)}}'">추가</button>
		</div>
		@if($faqs)
		{!! $faqs->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('faq.nohere')}}
	</div>
</div>



@endsection