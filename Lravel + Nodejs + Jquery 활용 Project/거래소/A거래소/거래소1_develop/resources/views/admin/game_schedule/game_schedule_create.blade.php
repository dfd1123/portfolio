@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
      경기일정 관리
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
		경기일정 생성
	</div>
	<div class="card-body">
		<form method="post" action="{{route('admin.game_schedule_store')}}">
			@csrf
			<div class="game_date">
				<label for="game_date">경기날짜</label>
				<input type="text" id="game_date" name="game_date"  class="form-control datepicker tsa-input-st" required="required" autocomplete="off"  />
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
				생성
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.game_schedule_list')}}'">
				{{ __('notice.cel')}}
				</button>
			</div>
		</form>
	</div>
</div>

<script>
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

  $('#game_date').datepicker();
</script>

@endsection

