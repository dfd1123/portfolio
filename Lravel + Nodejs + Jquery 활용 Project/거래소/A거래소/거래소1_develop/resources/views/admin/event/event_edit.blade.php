@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	{{ __('event.set')}}
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('event.add')}}
	</div>
	<div class="card-body">
		<form enctype="multipart/form-data" class="event_edit_adm_form" id="event_add_form" method="post" action="{{route('admin.event_update', $event->id)}}">
			@csrf
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;">{{ __('event.pc')}}</th>
							<td>
								<div class="filebox">
									<label for="file1" class="myButton use">{{ __('event.up')}}</label> 
									<input type="file" id="file1" name="file1" accept="image/*"/>
									<span class="filename">
									@if(isset($event))
										@if($event->image_url == NULL)
										{{ __('event.noimg')}}
										@else
											<a href="{{asset('/storage/image/event/'.$event->image_url)}}" target="_blank" class="myButton xbtn">{{ __('event.seeimg')}}</a>
										@endif
									@endif
									{{ __('event.img_pc')}}
									</span>
								</div>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('event.mo')}}</th>
							<td>
								<div class="filebox">
									<label for="file2" class="myButton use">{{ __('event.up')}}</label> 
									<input type="file" id="file2" name="file2" accept="image/*"/>
									<span class="filename">
									@if(isset($event))
										@if($event->image_mobile_url == NULL)
										{{ __('event.noimg')}}
										@else
											<a href="{{asset('/storage/image/event/'.$event->image_mobile_url)}}" target="_blank" class="myButton xbtn">{{ __('event.seeimg')}}</a>
										@endif
									@endif
									{{ __('event.img_mo')}}
									</span>
								</div>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('event.title')}}</th>
							<td>
								<input type="text" name="title" class="form-control tsa-input-st" value="{{$event->title}}" required="required"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('event.con')}}</th>
							<td>
								<textarea rows="5" name="description" class="form-control" required="required">{{$event->description}}</textarea>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('event.conimg1')}}</th>
							<td>
								<div class="filebox">
									<label for="file3" class="myButton use">{{ __('event.up')}}</label> 
									<input type="file" id="file3" name="file3" accept="image/*"/>
									<span class="filename">
									@if(isset($event))
										@if($event->image1 == NULL)
										{{ __('event.noimg')}}
										@else
											<a href="{{asset('/storage/image/event/'.$event->image1)}}" target="_blank" class="myButton xbtn">{{ __('event.seeimg')}}</a>
										@endif
									@endif
									</span>
								</div>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('event.conimg2')}}</th>
							<td>
								<div class="filebox">
									<label for="file4" class="myButton use">{{ __('event.up')}}</label> 
									<input type="file" id="file4" name="file4" accept="image/*"/>
									<span class="filename">
									@if(isset($event))
										@if($event->image2 == NULL)
										{{ __('event.noimg')}}
										@else
											<a href="{{asset('/storage/image/event/'.$event->image2)}}" target="_blank" class="myButton xbtn">{{ __('event.seeimg')}}</a>
										@endif
									@endif
									</span>
								</div>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('event.conimg3')}}</th>
							<td>
								<div class="filebox">
									<label for="file5" class="myButton use">{{ __('event.up')}}</label> 
									<input type="file" id="file5" name="file5" accept="image/*"/>
									<span class="filename">
									@if(isset($event))
										@if($event->image3 == NULL)
										{{ __('event.noimg')}}
										@else
											<a href="{{asset('/storage/image/event/'.$event->image3)}}" target="_blank" class="myButton xbtn">{{ __('event.seeimg')}}</a>
										@endif
									@endif
									</span>
								</div>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('event.date')}}</th>
							<td>
								<input id="startTime" type="text" name="start_time" class="form-control datepicker tsa-input-st" required="required" value="{{$event->start_time}}" />
								~
								<input id="endTime" type="text" name="end_time" class="form-control datepicker tsa-input-st" required="required" value="{{$event->end_time}}" />
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('event.lan')}}</th>
							<td>
								<select class="tsa-select" name="lang">
									<option value="en" {{$event->lang == 'en' ? 'selected' : ''}}>EN</option>
									<option value="kr" {{$event->lang == 'kr' ? 'selected' : ''}}>KR</option>
									<option value="jp" {{$event->lang == 'jp' ? 'selected' : ''}}>JP</option>
									<option value="ch" {{$event->lang == 'ch' ? 'selected' : ''}}>CH</option>
								</select>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('event.ifuse')}}</th>
							<td>
								<select class="tsa-select" name="active">
									<option value="1" {{$event->active == 1 ? 'selected' : ''}}>{{ __('event.use')}}</option>
									<option value="0" {{$event->active == 0 ? 'selected' : ''}}>{{ __('event.nouse')}}</option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn" onclick="return checkInputs();">
				{{ __('event.chan')}}
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{url()->previous()}}'">
				{{ __('event.gg')}}
				</button>
			</div>
		</form>
	</div>
</div>

@endsection

@section('script')
<script>
	$.datepicker.setDefaults({
		dateFormat: 'yy-mm-dd',
		prevText: '{{ __('event.bfmonth')}}',
		nextText: '{{ __('event.nxfmonth')}}',
		monthNames: ['{{ __('event.1')}}', '{{ __('event.2')}}', '{{ __('event.3')}}', '{{ __('event.4')}}', '{{ __('event.5')}}', '{{ __('event.6')}}', '{{ __('event.7')}}',
		 '{{ __('event.8')}}', '{{ __('event.9')}}', '{{ __('event.10')}}', '{{ __('event.11')}}', '{{ __('event.12')}}'],
		monthNamesShort: ['{{ __('event.1')}}', '{{ __('event.2')}}', '{{ __('event.3')}}', '{{ __('event.4')}}', '{{ __('event.5')}}', '{{ __('event.6')}}', '{{ __('event.7')}}',
		 '{{ __('event.8')}}', '{{ __('event.9')}}', '{{ __('event.10')}}', '{{ __('event.11')}}', '{{ __('event.12')}}'],
		dayNames:['{{ __('event.01')}}', '{{ __('event.02')}}', '{{ __('event.03')}}', '{{ __('event.04')}}', '{{ __('event.05')}}', '{{ __('event.06')}}', '{{ __('event.07')}}'],
		dayNamesShort: ['{{ __('event.01')}}', '{{ __('event.02')}}', '{{ __('event.03')}}', '{{ __('event.04')}}', '{{ __('event.05')}}', '{{ __('event.06')}}', '{{ __('event.07')}}'],
		dayNamesMin: ['{{ __('event.01')}}', '{{ __('event.02')}}', '{{ __('event.03')}}', '{{ __('event.04')}}', '{{ __('event.05')}}', '{{ __('event.06')}}', '{{ __('event.07')}}'],
		showMonthAfterYear: true,
		yearSuffix: '{{ __('event.y')}}',
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
</script>
@endsection
