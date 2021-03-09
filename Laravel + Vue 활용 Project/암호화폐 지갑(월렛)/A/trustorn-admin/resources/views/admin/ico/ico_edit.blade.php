@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
  ICO 설정
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	ICO 마감일 설정
	</div>
	<div class="card-body">
		<form method="post" action="{{route('admin.ico_update')}}">
			@csrf
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
            <tr>
                <th style="width:10%;">ICO 진행상태</th>
                <td>
                  {{$status}}
                </td>
            </tr>
						<tr>
                <th style="width:10%;">1TRU 가격</th>
                <td>
                  <input type="number" name="tru_per_eth" class="form-control tsa-input-st" value="{{$tru_per_eth}}" style="width:300px;display: inline-block;" required="required"/> ETH
                </td>
            </tr>
            <tr>
                <th style="width:10%;">ICO 시작일</th>
                <td>
                  <input type="text" id="startTime" name="ico_start_date" class="form-control tsa-input-st" value="{{date('Y-m-d',strtotime($ico_start_date))}}" required="required"/>
                </td>
            </tr>
            <tr>
                <th style="width:10%;">ICO 마감일</th>
                <td>
                  <input type="text" id="endTime" name="ico_end_date" class="form-control tsa-input-st" value="{{date('Y-m-d',strtotime($ico_end_date))}}" required="required"/>
                </td>
            </tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
          수정
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="history.back()">
				  취소
				</button>
			</div>
		</form>
	</div>
</div>

<script>
	$(function() {
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

