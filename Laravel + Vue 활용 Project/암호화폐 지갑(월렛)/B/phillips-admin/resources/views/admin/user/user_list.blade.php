@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
<li class="breadcrumb-item active">{{ __('user.user_ad') }}</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
<!-- <div class="card-header">
	회원리스트</div> -->
<div class="card-body">
	<div class="usr_search_box tsa-sch-box">
		<form method="get" action="">
				<select name="keyword_srch">
					<option value="all">{{ __('trade_history.all') }}</option>
					<option value="uid" {{$keyword_srch == 'uid' ? 'selected' : ''}}>UID</option>
					<option value="fullname" {{$keyword_srch == 'name' ? 'selected' : ''}}>{{ __('user.name') }}</option>
					<option value="email" {{$keyword_srch == 'id' ? 'selected' : ''}}>{{ __('user.email') }}</option>
				</select>
				<input type="text" name="keyword" />
				<button type="submit">{{ __('user.search') }}</button>
		</form>
	</div>
	<div class="mb-2">
		<input id="startTime" type="text" class="col-sm-1 form-control form-control-sm" style="display: inline"/>
		<span> ~ </span>
		<input id="endTime" type="text" class="col-sm-1 form-control form-control-sm" style="display: inline"/>
		<button id="excel-download" type="button" class="myButton navy">Excel</button>
	</div>
	<div class="table-responsive tsa-table-wrap">

		<table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
			<thead>
				<th style="width: 5%;">UID</th>
				<th style="width: 7%;">{{ __('user.name') }}</th>
				<th style="width: 15%;">{{ __('user.email') }}</th>
				<!--<th style="width: 15%;">휴대폰 번호</th>-->
				<th style="width: 7%;">입금주소</th>
				<th style="width: 10%;">{{ __('user.bal_ad') }}</th>
				<!--<th style="width: 7%;">{{ __('user.now') }}</th>-->
				<!--<th style="width: 7%;">회원탈퇴</th>-->
			</thead>
			<tbody>
			@forelse($users as $user)
			<tr>
				<td>{{$user->uid}}</td>
				<td>{{$user->fullname}}</td>
				<td>{{str_replace(session('market_type')."_","",$user->email)}}</td>
				<!--<td>{{$user->mobile_number}}</td>-->
				<td>{{ $user->address_ppc }}</td>
				<td>
					<button type="button" data-id="{{$user->uid}}" class="availble_coin_confirm myButton navy">{{ __('user.bal_check') }}</button>
					<!--<button type="button" data-id="{{$user->uid}}" class="add_balance_confirm myButton edit">{{ __('user.bal_add') }}</button>-->
				</td>
				<!--<td>
					<select class="form-control" name="status" data-id="{{$user->uid}}">
					{{$user->status}}
						<option value="1" {{($user->status == 1)?'selected=selected':''}}>{{ __('user.good') }}</option>
						<option value="2" {{($user->status == 2)?'selected=selected':''}}>{{ __('user.stop') }}</option>
					</select>
				</td>
				<td>
					<button type="button" data-id="{{$user->uid}}" class="user_secession myButton navy">탈퇴처리</button>
				</td>-->
			</tr>
			@empty
			<tr>
				<td colspan="8" >{{ __('user.user_sentence_4') }}</td>
			</tr>
			@endforelse
			</tbody>
		</table>
	</div>
	@if($users)
		{!! $users->render() !!}
	@endif
</div>






<div class="card-footer small text-muted">{{ $datetime }} {{ __('user.user_sentence_2') }}</div>
</div>

<div id="security_edit_wrap" class="jw_modal_wrap hidden">
	<div class="jw_overlay"></div>
	<div class="jw_modal_content_wrap">
		<div class="jw_modal_content">
			<div class="jw_modal_hd">
				<h5>{{ __('user.sec_check') }}</h5>
				<div><i class="fal fa-chevron-down"></i></div>
			</div>
			<div class="jw_modal_bd">
				<div class="content_box">
					<div class="section_div">
						<label class="tsa-label-st"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.email_cer') }}</label>
						<div class="text-right">
							<label for="email_verified_y" class="mr-3"><input type="radio" name="email_verified" id="email_verified_y" value="1" class="grayCheckbox mr-2"/>{{ __('user.cer') }}</label>
							<label for="email_verified_n"><input type="radio" name="email_verified" id="email_verified_n" value="0" class="grayCheckbox mr-2"/>{{ __('user.no_cer') }}</label>
						</div>
					</div>
					<div class="section_div">
						<label class="tsa-label-st"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.phone_cer') }}</label>
						<div class="text-right">
							<label for="mobile_verified_y" class="mr-3"><input type="radio" name="mobile_verified" id="mobile_verified_y" value="1" class="grayCheckbox mr-2"/>{{ __('user.cer') }}</label>
							<label for="mobile_verified_n"><input type="radio" name="mobile_verified" id="mobile_verified_n" value="0" class="grayCheckbox mr-2"/>{{ __('user.no_cer') }}</label>
						</div>
					</div>
					<div class="section_div">
						<label class="tsa-label-st"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.id_cer') }}</label>
						<div class="text-right">
							<label class="mr-3"><input type="radio" name="document_verified" id="document_verified_y" value="1" readonly="readonly" disabled class="grayCheckbox mr-2"/>{{ __('user.cer') }}</label>
							<label><input type="radio" name="document_verified" id="document_verified_n" value="0" readonly="readonly" disabled class="grayCheckbox mr-2"/>{{ __('user.no_cer') }}</label>
						</div>
					</div>
					<div class="section_div">
						<label class="tsa-label-st"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.account') }}</label>
						<div class="text-right">
							<label class="mr-3"><input type="radio" name="account_verified" id="account_verified_y" value="1" readonly="readonly" disabled class="grayCheckbox mr-2"/>{{ __('user.cer') }}</label>
							<label><input type="radio" name="account_verified" id="account_verified_n" value="0" readonly="readonly" disabled class="grayCheckbox mr-2"/>{{ __('user.no_cer') }}</label>
						</div>
					</div>
					<div class="section_div">
						<label class="tsa-label-st"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.otp_cer') }}</label>
						<div class="text-right">
							<label for="google_verified_y" class="mr-3"><input type="radio" name="google_verified" id="google_verified_y" value="1" class="grayCheckbox mr-2"/>{{ __('user.cer') }}</label>
							<label for="google_verified_y"><input type="radio" name="google_verified" id="google_verified_n" value="0" class="grayCheckbox mr-2"/>{{ __('user.no_cer') }}</label>
						</div>
					</div>
					<input type="hidden" name="ver_temp_user_id">
				</div>
			</div>
			<div class="jw_modal_ft">
			</div>
		</div>
	</div>
</div>

<div id="availble_confirm_wrap" class="jw_modal_wrap hidden">
	<div class="jw_overlay"></div>
	<div class="jw_modal_content_wrap">
		<div class="jw_modal_content">
			<div class="jw_modal_hd">
				<h5>{{ __('user.bal_ad') }}</h5>
				<div>X</div>
			</div>
			<div class="jw_modal_bd">
				<div class="content_box">
					@forelse($coins as $coin)
					<div class="section_div">
						<h5>*</i>{{ ($coin->name == 'US Dollor') ? 'USDC' : $coin->name }}</h5>
						<label class="tsa-label-st">{{ __('user.bal') }}</label>
						<div class="mb-2">
							<input type="text" name="available_balance_{{ $coin->api }}" readonly="readonly" class="form-control tsa-input-st"/>
						</div>
						<label class="tsa-label-st">{{ __('user.wait_price') }}</label>
						<div>
							<input type="text" name="pending_balance_{{ $coin->api }}" readonly="readonly" class="form-control tsa-input-st" />
						</div>
					</div>
					@empty
						<p>{{ __('user.user_sentence_11') }}</p>
					@endforelse
					<input type="hidden" name="temp_user_id">
				</div>
			</div>
			<div class="jw_modal_ft">
			</div>
		</div>
	</div>
</div>

<div id="add_balance_edit_wrap" class="jw_modal_wrap hidden">
		<div class="jw_overlay"></div>
		<div class="jw_modal_content_wrap">
			<div class="jw_modal_content">
				<div class="jw_modal_hd">
					<h5>{{ __('user.price_add') }}</h5>
					<div><i class="fal fa-chevron-down"></i></div>
				</div>
				<div class="jw_modal_bd">
					<div class="content_box">
						<div class="section_div">
							<label class="tsa-label-st"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.money') }}</label>
							<div class="mb-3">
								<select class="form-control tsa-input-st" id="cointype" name="cointype">
									@foreach($coins as $coin)
									<option value="{{$coin->api}}">{{$coin->symbol}}</option>
									@endforeach
								</select>
							</div>
							<label class="tsa-label-st"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.list') }}</label>
							<div>
								<input class="form-control tsa-input-st" type="text" name="reason" id="add_balance_reason" />
							</div>
						</div>
						<label class="tsa-label-st mt-3"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.add_price') }}</label>
						<div>
							<input class="form-control tsa-input-st" type="number" step="any" name="amount" id="add_balance_amount" required />
						</div>
						<button id="add_balance_save" class="btn btn-default mint_btn mt-3">{{ __('user.add') }}</button>
					</div>
				</div>
				<div class="jw_modal_ft">
				</div>
			</div>
		</div>
	</div>
@endsection

@section('script')
<script>
	/* \resources\js\admin\user.js 사용*/

	/* 보안인증확인 */
	loadPopup('.securty_edit_btn', '#security_edit_wrap', function(e) {
		var id = $(e.currentTarget).data('id');

		$.ajax({
			url : "/admin/user_security_load",
			type : "POST",
			data : {_token : CSRF_TOKEN, id : id},
			dataType : "JSON",
			success : function(data) {
				if(data.email_verified == 0){
					$('#email_verified_n').prop("checked",true);
					$('#email_verified_y').prop("checked",false);
				}else{
					$('#email_verified_y').prop("checked",true);
					$('#email_verified_n').prop("checked",false);
				}

				if(data.mobile_verified == 0){
					$('#mobile_verified_n').prop("checked",true);
					$('#mobile_verified_y').prop("checked",false);
				}else{
					$('#mobile_verified_y').prop("checked",true);
					$('#mobile_verified_n').prop("checked",false);
				}

				if(data.document_verified == 0){
					$('#document_verified_n').prop("checked",true);
					$('#document_verified_y').prop("checked",false);
				}else{
					$('#document_verified_y').prop("checked",true);
					$('#document_verified_n').prop("checked",false);
				}

				if(data.account_verified == 0){
					$('#account_verified_n').prop("checked",true);
					$('#account_verified_y').prop("checked",false);
				}else{
					$('#account_verified_y').prop("checked",true);
					$('#account_verified_n').prop("checked",false);
				}

				if(data.google_verified == 0){
					$('#google_verified_n').prop("checked",true);
					$('#google_verified_y').prop("checked",false);
				}else{
					$('#google_verified_y').prop("checked",true);
					$('#google_verified_n').prop("checked",false);
				}

				$('input[name="ver_temp_user_id"]').val(id);
			}
		});
	});

	/* 잔액확인 */
	loadPopup('.availble_coin_confirm', '#availble_confirm_wrap', function(e) {
		var id = $(e.currentTarget).data('id');

		@foreach($coins as $coin)
			$('input[name="available_balance_{{$coin->api}}"]').val('');
			$('input[name="pending_balance_{{$coin->api}}"]').val('');
		@endforeach

		$.ajax({
			url : "/admin/user_available_load",
			type : "POST",
			data : {_token : CSRF_TOKEN, id : id},
			dataType : "JSON",
			success : function(data) {
				console.log(data);
				@foreach($coins as $coin)
					$('input[name="pending_balance_{{$coin->api}}"]').val(data.pending_received_balance_{{$coin->api}});
					$('input[name="available_balance_{{$coin->api}}"]').val(parseFloat(data.available_balance_{{$coin->api}}) + parseFloat(data.pending_received_balance_{{$coin->api}}));
				@endforeach
			}
		});
	});

	/* 잔액추가 */
	loadPopup('.add_balance_confirm', '#add_balance_edit_wrap', function(e) {
		$("#cointype").prop("selectedIndex", 0);
		$('#add_balance_reason').val('');
		$('#add_balance_amount').val('');
		$('#add_balance_save').attr('disabled', false).data('id', $(e.currentTarget).data('id'));
	});

	$('#add_balance_save').click(function(e){
		var cointype = $('#cointype').val();
		var reason = $('#add_balance_reason').val();
		var amount_input =  $('#add_balance_amount').val();

		var button = $(e.currentTarget);
		var id = button.data('id');

		if(reason === '') {
			alert("{{ __('user.user_sentence_5') }}");
			return;
		}

		if(amount_input === '') {
			alert("{{ __('user.user_sentence_6') }}");
			return;
		}

		var amount = parseFloat(amount_input);
		if(amount == NaN) {
			alert("{{ __('user.user_sentence_7') }}");
			return false;
		}

		if(amount == 0) {
			alert("{{ __('user.user_sentence_8') }}");
			return false;
		}

		if(confirm('{{ __('user.user_sentence_9') }}')){
			button.attr('disabled', true);
			$.ajax({
				url : "/admin/add_balance_change",
				type : "POST",
				data : {
					_token : CSRF_TOKEN,
					id : id,
					cointype : cointype,
					reason : reason,
					amount : amount_input
				},
				dataType : "JSON",
				success : function(data) {
					if(data.status === 1) {
						button.attr('disabled', false);
						alert("{{ __('user.user_sentence_10') }}");
					}
				}
			});
		}
	});

	function loadPopup(button, popup, onload){
		$(button).click(function(e){
				$(popup).removeClass('hidden');
				setTimeout(function() { $(popup).addClass('active'); }, 300);
				onload(e);
			});

			$(popup + ' .jw_overlay, ' + popup + ' .jw_modal_hd>div').click(function(){
			$(popup).removeClass('active');
			setTimeout(function() { $(popup).addClass('hidden');}, 300);
		});
	}

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

		$('#excel-download').click(function(e){
			window.location = '/admin/user_excel?from=' + from.val() + '&to=' + to.val();
		});
	});
</script>
@endsection