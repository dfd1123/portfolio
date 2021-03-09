@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	{{__('airdrop.airdrop')}}
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{__('airdrop.airadd')}}
	</div>
	<div class="card-body">
		<form enctype="multipart/form-data" class="event_edit_adm_form" id="event_add_form" method="post" action="{{route('admin.airdrop_update', $airdrop->id)}}">
			@csrf
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;">{{__('airdrop.title')}}</th>
							<td>
								<input type="text" name="title" class="form-control tsa-input-st" value="{{$airdrop->title}}"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{__('airdrop.kind')}}</th>
							<td>
								<select name="coin">
									@foreach ($coins as $coin)
									<option value="{{$coin->api}}" {{$coin->api == $airdrop->coin ? 'selected' : ''}}>{{$coin->symbol}}</option>
									@endforeach
								</select>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{__('airdrop.work')}}</th>
							<td>
								<select name="cases">
									<option value="everybody" {{$airdrop->cases == 'everybody' ? 'selected' : ''}}>{{__('airdrop.who')}}</option>
									<option value="register" {{$airdrop->cases == 'register' ? 'selected' : ''}}>{{__('airdrop.join')}}</option>
									<option value="security_otp" {{$airdrop->cases == 'security_otp' ? 'selected' : ''}}>{{__('airdrop.otp')}}</option>
									<option value="transaction" {{$airdrop->cases == 'transaction' ? 'selected' : ''}}>{{__('airdrop.first')}}</option>
								</select>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{__('airdrop.twice')}}</th>
							<td>
								<select name="overlap_yn">
									<option value="0" {{$airdrop->overlap_yn == '0' ? 'selected' : ''}}>{{__('airdrop.once')}}</option>
									<option value="1" {{$airdrop->overlap_yn == '1' ? 'selected' : ''}}>{{__('airdrop.jook')}}</option>
								</select>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{__('airdrop.no')}}</th>
							<td>
								<input type="number" name="send_cnt" class="form-control tsa-input-st" required="required" value="{{$airdrop->send_cnt}}" />
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{__('airdrop.all')}}</th>
							<td>
								<input type="number" name="all_cnt" class="form-control tsa-input-st" required="required" value="{{$airdrop->all_cnt}}" />
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{__('airdrop.data')}}</th>
							<td>
								<input id="startTime" type="text" name="start_time" class="form-control datepicker tsa-input-st" required="required" value="{{date("Y-m-d", strtotime($airdrop->start_time))}}" />
								~
								<input id="endTime" type="text" name="end_time" class="form-control datepicker tsa-input-st" required="required" value="{{date("Y-m-d", strtotime($airdrop->end_time))}}" />
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
				{{__('airdrop.add')}}
				</button>
				<button type="button" class="btn btn-default mint_btn mint_btn_cancel" onclick="location.href='{{url()->previous()}}'">
				{{__('airdrop.cancel')}}
				</button>
			</div>
		</form>
	</div>
</div>

@endsection

@section('script')
<script>
	$(function() {
		var dateFormat = 'yy-mm-dd';
		$.datepicker.setDefaults({
			dateFormat: dateFormat,
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 3,
			onClose: function(value) {
				var datepicker = this;
				if(value && !moment(value, 'YYYY-MM-DD',true).isValid()){
					alert('{{__('airdrop.wrongdate')}}');
					$(datepicker).val('');
				}
			}
		});

		var from = $("#startTime")
			.datepicker()
			.on("change", function() {
				to.datepicker("option", "minDate", $(this).val());
			});
			
		var	to = $("#endTime")
			.datepicker()
			.on("change", function() {
				from.datepicker("option", "maxDate", $(this).val());
			});
	});
</script>
@endsection
