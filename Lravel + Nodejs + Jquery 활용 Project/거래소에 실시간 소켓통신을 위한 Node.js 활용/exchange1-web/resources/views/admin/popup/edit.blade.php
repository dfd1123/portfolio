@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	{{ __('popup.popset')}}
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('popup.editpop')}}
	</div>
	<div class="card-body">
		<form method="post" action="{{route('admin.popup_update',$popup->id)}}" enctype="multipart/form-data">
			@csrf
            <input type="hidden" name="country" value="{{$country}}">
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;"> {{ __('popup.title')}}</th>
							<td>
								<input type="text" name="title" class="form-control tsa-input-st" value="{{$popup->title}}" required="required"/>
							</td>
						</tr>
						<tr>
							<th>{{ __('popup.pc')}}</th>
							<td>
                                <input type="file" id="pc_img" name="pc_img" style="display:none;" value="{{$popup->pc_img}}" />
								@if(!empty($popup->pc_img))
									<img src="{{asset('/storage/image/popup'.$popup->pc_img)}}" alt="" id="foo" />
								@else
									<img src="" alt="" id="foo" />
								@endif
								<label for="pc_img" class="myButton use">{{ __('popup.up')}}</label>
							</td>
						</tr>
						<tr>
							<th>{{ __('popup.mo')}}</th>
							<td>
								<input type="file" id="mb_img" name="mb_img" style="display:none;" value="{{$popup->mb_img}}" />
								@if(!empty($popup->mb_img))
									<img src="{{asset('/storage/image/popup'.$popup->mb_img)}}" alt="" id="foo2" />
								@else
									<img src="" alt="" id="foo" />
								@endif
								<label for="mb_img" class="myButton use">{{ __('popup.up')}}</label>	
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('popup.datepop')}}</th>
							<td>
								<input id="startTime" type="text" name="start_time" class="form-control datepicker tsa-input-st" style="width: 105px; display: inline-block;" value="{{$popup->start_time}}" required="required" value="" />
								~
								<input id="endTime" type="text" name="end_time" class="form-control datepicker tsa-input-st" style="width: 105px; display: inline-block;" value="{{$popup->end_time}}" required="required" value="" />
							</td>
						</tr>
						<tr>
							<th style="width:10%;"> {{ __('popup.con')}}</th>
							<td>
								<span>{{ __('popup.nocontents')}}</span>
								<textarea rows="15" name="body" id="editor" class="form-control">{!! $popup->body !!}</textarea>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
				{{ __('popup.edit')}}
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.popup_delete', ['id' => $popup->id, 'country' => $country])}}'">
				{{ __('popup.del')}}
				</button>
			</div>
		</form>
	</div>
</div>

<script>
	$('#editor')
		.summernote({
			height: 350,
			lang: 'ko-KR',
			disableDragAndDrop: true
		});
	$('.note-editing-area').css('word-break', 'break-all');
	$('.note-group-select-from-files').css('display', 'none');

	$.datepicker.setDefaults({
		dateFormat: 'yy-mm-dd',
		prevText: '{{ __('event.bfmonth')}}',
		nextText: '{{ __('event.nxmonth')}}',
		monthNames: ['{{ __('event.1')}}', '{{ __('event.2')}}', '{{ __('event.3')}}', '{{ __('event.4')}}', '{{ __('event.5')}}', '{{ __('event.6')}}', '{{ __('event.7')}}',
		 '{{ __('event.8')}}', '{{ __('event.9')}}', '{{ __('event.10')}}', '{{ __('event.11')}}', '{{ __('event.12')}}'],
		monthNamesShort: ['{{ __('event.1')}}', '{{ __('event.2')}}', '{{ __('event.3')}}', '{{ __('event.4')}}', '{{ __('event.5')}}', '{{ __('event.6')}}', '{{ __('event.7')}}',
		 '{{ __('event.8')}}', '{{ __('event.9')}}', '{{ __('event.10')}}', '{{ __('event.11')}}', '{{ __('event.12')}}'],
		dayNames: ['{{ __('event.01')}}', '{{ __('event.02')}}', '{{ __('event.03')}}', '{{ __('event.04')}}', '{{ __('event.05')}}', '{{ __('event.06')}}', '{{ __('event.07')}}'],
		dayNamesShort: ['{{ __('event.01')}}', '{{ __('event.02')}}', '{{ __('event.03')}}', '{{ __('event.04')}}', '{{ __('event.05')}}', '{{ __('event.06')}}', '{{ __('event.07')}}'],
		dayNamesMin: ['{{ __('event.01')}}', '{{ __('event.02')}}', '{{ __('event.03')}}', '{{ __('event.04')}}', '{{ __('event.05')}}', '{{ __('event.06')}}', '{{ __('event.07')}}'],
		showMonthAfterYear: true,
		yearSuffix: '{{ __('event.year')}}',
		beforeShow: function() {
			setTimeout(function(){
				$('.ui-datepicker').css('z-index', 999);
			}, 0);
		},
		onClose: function(value) {
			var datepicker = this;
			if(value && !moment(value, 'YYYY-MM-DD',true).isValid()){
				alert('{{ __('event.wrong')}}');
				$(datepicker).val('');
			}
		}
	});

	$('#startTime').datepicker();
	$('#endTime').datepicker();

	function checkInputs(){
		if(moment($('#startTime').val()).isAfter($('#endTime').val())){
			alert('{{ __('event.sobig')}}.');
			$('#startTime').focus();
			return false;
		}
		return true;
	}

	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#foo').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
	}
	
	function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#foo2').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#pc_img").change(function() {
        readURL(this);
	});

	$("#mb_img").change(function() {
        readURL2(this);
	});
</script>

@endsection

