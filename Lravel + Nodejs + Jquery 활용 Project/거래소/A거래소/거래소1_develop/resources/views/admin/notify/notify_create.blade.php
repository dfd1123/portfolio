@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
		{{ __('notify.allalr')}}
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
		@if($type == 0)
		{{ __('notify.mail')}}
		@elseif($type == 1)
		{{ __('notify.sms')}}
		@endif
	</div>	
	<div class="card-body">
		<ul class="nav nav-tabs">
			@if($type == 0)
			<li class="active"><a href="{{route('admin.notify_create',['type'=>0, 'country'=>'kr'])}}">{{ __('notify.mail')}}</a></li>
			<li><a href="{{route('admin.notify_create',['type'=>1, 'country'=>'kr'])}}">{{ __('notify.sms')}}</a></li>
			@elseif($type == 1)
			<li><a href="{{route('admin.notify_create',['type'=>0, 'country'=>'kr'])}}">{{ __('notify.mail')}}</a></li>
			<li class="active"><a href="{{route('admin.notify_create',['type'=>1, 'country'=>'kr'])}}">{{ __('notify.sms')}}</a></li>
			@endif
		</ul>
		
		<form enctype="multipart/form-data" class="event_edit_adm_form" id="event_add_form" method="post" action="{{route('admin.notify_store', $type)}}">
			
		@csrf
		<input type="hidden" name="country" value="{{$country}}" />
		@if($type == 0)
		<div class="table-responsive tsa-event-table">
			<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
				<tbody>
					<tr>
						<th style="width:10%;">{{ __('notify.tt')}}</th>
						<td>
							<input type="text" name="title"  class="form-control tsa-input-st" />
						</td>
					</tr>
					<tr>
						<th style="width:10%;">{{ __('notify.con')}}</th>
						<td>
							<textarea id="editor" name="description" class="form-control tsa-textarea"></textarea>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="mint_btn_group">
			<button type="submit" class="btn btn-default mint_btn" onclick="return confirm('{{ __('notify.really')}}')">{{ __('notify.mailalr')}}</button>
		</div>
		@elseif($type == 1)
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							@if($check_nexmo)
							<th style="width:10%;">{{ __('notify.con')}}</th>
							<td><textarea name="description"  class="form-control" rows="6" ></textarea></td>
							@else
							<th style="width:10%;">{{ __('notify.alr')}}</th>
							<td>{{ __('notify.before')}}</td>
							@endif
						</tr>
					</tbody>
				</table>
			</div>
			@if($check_nexmo)
			<div class="mint_btn_group">
				<button type="submit" class="mint_btn" onclick="return confirm('{{ __('notify.really')}}')">{{ __('notify.smsalr')}}</button>
			</div>
			@endif
		@endif
	</div>
</div>

@endsection

@section('script')

<script>
	$('#editor')
		.summernote({
			height: 350,
			lang: 'ko-KR',
			disableDragAndDrop: true
		});
	$('.note-editing-area').css('word-break', 'break-all');
	$('.note-group-select-from-files').css('display', 'none');
</script>

@endsection
