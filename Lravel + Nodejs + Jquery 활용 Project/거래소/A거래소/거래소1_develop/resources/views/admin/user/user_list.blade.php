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
					<option value="mobile" {{$keyword_srch == 'mobile' ? 'selected' : ''}}>{{ __('user.phone') }}</option>
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
				<th style="width: 7%;">닉네임</th>
				<th style="width: 10%;">{{ __('user.email') }}</th>
				<th style="width: 8%;">휴대폰 번호</th>
				<th style="width: 7%;">생년월일</th>
				<th style="width: 7%;">{{ __('user.bal_ad') }}</th>
				<th style="width: 7%;">{{ __('user.sec') }}</th>
				<th style="width: 7%;">{{ __('user.now') }}</th>
				<th style="width: 7%;">회원탈퇴</th>
			</thead>
			<tbody>
			@forelse($users as $user)
			<tr>
				<td>{{$user->uid}}</td>
				<td>
					<a href = "{{ route('admin.user_detail',['uid' => $user->uid,'tab' => 1 ]) }}" id="fullname_atag">{{$user->fullname}}</a><br />
					@if(Auth::guard('admin')->user()->level <= 3)
						<button type="button" data-id="{{$user->uid}}" class="fullname_change_btn">변경</button>
					@endif
				</td>
				<td>
					<span href = "{{ route('admin.user_detail',['uid' => $user->uid,'tab' => 1 ]) }}" id="nickname_atag">{{$user->nickname}}</span><br />
					@if(Auth::guard('admin')->user()->level <= 3)
						<button type="button" data-id="{{$user->uid}}" class="nickname_change_btn">변경</button>
					@endif
				</td>
				<td>
					{{str_replace(session('market_type')."_","",$user->email)}}<br />
					@if(Auth::guard('admin')->user()->level <= 3)
						<button type="button" data-id="{{$user->uid}}" class="email_change_btn">변경</button>
					@endif
				</td>
				<td>
					{{$user->mobile_number}}<br />
					@if(Auth::guard('admin')->user()->level <= 3)
						<button type="button" data-id="{{$user->uid}}" class="mobile_change_btn">변경</button>
					@endif
				</td>
				<td>
					{{$user->birthdate}}<br />
				</td>
				<td>
					<button type="button" data-id="{{$user->uid}}" class="availble_coin_confirm myButton navy">{{ __('user.bal_check') }}</button>
					@if(Auth::guard('admin')->user()->level <= 1)
						<button type="button" data-id="{{$user->uid}}" class="add_balance_confirm myButton edit">{{ __('user.bal_add') }}</button>
					@endif
				</td>
				<td><button type="button" data-id="{{$user->uid}}" class="securty_edit_btn myButton xbtn">{{ __('user.sec_check') }}</button></td>
				<td>
					@if(Auth::guard('admin')->user()->level <= 4)
					커뮤니티
					<select class="form-control" name="comunity_status" data-id="{{$user->uid}}">
					{{$user->comunity_status}}
							<option value="0" {{($user->comunity_status == 0)?'selected=selected':''}}>{{ __('user.stop') }}</option>
							<option value="1" {{($user->comunity_status == 1)?'selected=selected':''}}>{{ __('user.good') }}</option>
							<option value="2" {{($user->comunity_status == 2)?'selected=selected':''}}>{{ __('user.qnastop') }}</option>
					</select>
					<br/>
					거래/입출금
					<select class="form-control" name="status" data-id="{{$user->uid}}">
					{{$user->status}}
							<option value="1" {{($user->status == 1)?'selected=selected':''}}>{{ __('user.good') }}</option>
							<option value="2" {{($user->status == 2)?'selected=selected':''}}>{{ __('user.stop') }}</option>
							<option value="3" {{($user->status == 3)?'selected=selected':''}}>회원탈퇴</option>
					</select>
					@else
							{{($user->comunity_status == 1)?'커뮤니티 허용':(($user->comunity_status == 2)?'1:1 문의 정지':'커뮤니티 정지')}}<br/>
							{{($user->status == 1)?'거래/입출금 허용':'거래/입출금 정지'}}
					@endif
				</td>
				<td>
					<button type="button" data-id="{{$user->uid}}" class="password_change_btn">임시 비밀번호</button>
					@if(Auth::guard('admin')->user()->level <= 4)
						<button type="button" data-id="{{$user->uid}}" class="user_secession myButton navy">탈퇴처리</button>
					@endif
				</td>
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
							<label for="email_verified_n"><input type="radio" name="email_verified" id="email_verified_n" value="0" {{(Auth::guard('admin')->user()->level <= 4)?'':'readonly=readonly'}} {{(Auth::guard('admin')->user()->level <= 4)?'':'disabled'}} class="grayCheckbox mr-2"/>{{ __('user.no_cer') }}</label>
						</div>
					</div>
					<div class="section_div">
						<label class="tsa-label-st"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.phone_cer') }}</label>
						<div class="text-right">
							<label for="mobile_verified_y" class="mr-3"><input type="radio" name="mobile_verified" id="mobile_verified_y" value="1" class="grayCheckbox mr-2"/>{{ __('user.cer') }}</label>
							<label for="mobile_verified_n"><input type="radio" name="mobile_verified" id="mobile_verified_n" value="0" {{(Auth::guard('admin')->user()->level <= 4)?'':'readonly=readonly'}} {{(Auth::guard('admin')->user()->level <= 4)?'':'disabled'}} class="grayCheckbox mr-2"/>{{ __('user.no_cer') }}</label>
						</div>
					</div>
					<!--
					<div class="section_div">
						<label class="tsa-label-st"><i class="fal fa-chevron-circle-right pr-2"></i>{{ __('user.id_cer') }}</label>
						<div class="text-right">
							<label class="mr-3"><input type="radio" name="document_verified" id="document_verified_y" value="1" readonly="readonly" disabled class="grayCheckbox mr-2"/>{{ __('user.cer') }}</label>
							<label><input type="radio" name="document_verified" id="document_verified_n" value="0" readonly="readonly" disabled class="grayCheckbox mr-2"/>{{ __('user.no_cer') }}</label>
						</div>
					</div>
					-->
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
				<div><i class="fal fa-chevron-down"></i></div>
			</div>
			<div class="jw_modal_bd">
				<div class="content_box">
					@forelse($coins as $coin)
					<div class="section_div">
						<h5><i class="fal fa-chevron-circle-right pr-2"></i>{{ ($coin->name == 'US Dollor') ? 'USDC' : $coin->name }}</h5>
						<label class="tsa-label-st">{{ __('user.bal') }}</label>
						<div class="mb-2">
							<input type="text" name="available_balance_{{ $coin->api }}" readonly="readonly" class="form-control tsa-input-st"/>
						</div>
						@if($coin->api != 'krw')
						<label class="tsa-label-st">매수 대기</label>
						<div>
							<input type="text" name="buy_ads_balance_{{ $coin->api }}" readonly="readonly" class="form-control tsa-input-st" />
						</div>
						<label class="tsa-label-st">출금대기, {{ __('user.wait_price') }}</label>
						<div>
							<input type="text" name="pending_balance_{{ $coin->api }}" readonly="readonly" class="form-control tsa-input-st" />
						</div>
						@endif
						<label class="tsa-label-st">{{ $coin->api == 'krw' ? '거래대기 금액' : '락업 금액' }}</label>
						<div>
							<input type="text" name="lock_balance_{{ $coin->api }}" readonly="readonly" class="form-control tsa-input-st" />
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
			$('input[name="lock_balance_{{$coin->api}}"]').val('');
			$('input[name="buy_ads_balance_{{$coin->api}}"]').val('');
		@endforeach

		$.ajax({
			url : "/admin/user_available_load",
			type : "POST",
			data : {_token : CSRF_TOKEN, id : id},
			dataType : "JSON",
			success : function(data) {
				$.each(data,function(key, value){
					$('input[name="available_balance_'+value.api+'"]').val(value.available_balance);
					$('input[name="pending_balance_'+value.api+'"]').val(value.trading_pending);
					$('input[name="lock_balance_'+value.api+'"]').val(value.lock_pending);
					$('input[name="buy_ads_balance_'+value.api+'"]').val(value.buying_amt);
				});
				
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

	$('.password_change_btn').on('click', function(){
		var uid = $(this).data('id');

		$.ajax({
			url : "/admin/user/password/change",
			type : "POST",
			data : {
				_token : CSRF_TOKEN,
				uid : uid,
			},
			dataType : "JSON",
			success : function(data) {
				if(data.status === 1) {
					alert("임시 비밀번호가 성공적으로 전송되었습니다.");
				}else{
					alert("임시 비밀번호 전송에 실패하였습니다.");
				}
			}
		});
	});

	$('.fullname_change_btn').on('click', function(){
		var uid = $(this).data('id');
		var fullname = prompt("변경할 이름을 입력해주세요.\n\n※이름을 변경하시면 휴대폰 본인인증을 다시 하여야 합니다.※\n\n해당 내용을 꼭 고객님께 고지한 후 변경하시기 바랍니다.");
		var btn = $(this);

		$.ajax({
			url : "/admin/user/fullname/change",
			type : "POST",
			data : {
				_token : CSRF_TOKEN,
				uid : uid,
				fullname : fullname,
			},
			dataType : "JSON",
			success : function(data) {
				if(data.status === 1) {
					$('#fullname_atag').text(data.fullname);
					alert("이름이 성공적으로 변경되었습니다.");
				}else{
					alert("이름 변경에 실패하였습니다.");
				}
			}
		});
	});

	$('.nickname_change_btn').on('click', function(){
		var uid = $(this).data('id');
		var nickname = prompt("변경할 닉네임을 입력해주세요.");
		var btn = $(this);

		$.ajax({
			url : "/admin/user/nickname/change",
			type : "POST",
			data : {
				_token : CSRF_TOKEN,
				uid : uid,
				nickname : nickname,
			},
			dataType : "JSON",
			success : function(data) {
				if(data.status === 1) {
					$('#nickname_atag').text(data.nickname);
					alert("닉네임이 성공적으로 변경되었습니다.");
				}else{
					alert(data.message);
				}
			}
		});
	});

	$('.email_change_btn').on('click', function(){
		var uid = $(this).data('id');
		var email = prompt("변경할 이메일 주소를 입력해주세요.\n\n※이메일 주소를 변경하시면 변경된 이메일로 \n다시 이메일 인증을 하여야 합니다.※\n\n해당 내용을 꼭 고객님께 고지한 후 변경하시기 바랍니다.");
		var btn = $(this);

		$.ajax({
			url : "/admin/user/email/change",
			type : "POST",
			data : {
				_token : CSRF_TOKEN,
				uid : uid,
				email : email,
			},
			dataType : "JSON",
			success : function(data) {
				if(data.exist){
					alert("이미 존재하는 이메일 주소 입니다.");
				}else{
					if(data.status === 1) {
						btn.parent().text(data.email);
						alert("이메일 주소가 성공적으로 변경되었습니다.");
					}else{
						alert("이메일 주소 변경에 실패하였습니다.");
					}
				}
			}
		});
	});

	$('.mobile_change_btn').on('click', function(){
		var uid = $(this).data('id');
		var mobile_number = prompt("변경할 휴대폰 번호를 입력해주세요.\n\n※휴대폰 번호를 변경하시면 변경된 번호로 \n다시 본인 인증을 하여야 합니다.※\n\n해당 내용을 꼭 고객님께 고지한 후 변경하시기 바랍니다.");
		var btn = $(this);

		$.ajax({
			url : "/admin/user/mobile_number/change",
			type : "POST",
			data : {
				_token : CSRF_TOKEN,
				uid : uid,
				mobile_number : mobile_number,
			},
			dataType : "JSON",
			success : function(data) {
				if(data.exist){
					alert("이미 존재하는 휴대폰 번호 입니다.");
				}else{
					if(data.status === 1) {
						btn.parent().text(data.mobile_number);
						alert("휴대폰 번호가 성공적으로 변경되었습니다.");
					}else{
						alert("휴대폰 번호 변경에 실패하였습니다.");
					}
				}
			}
		});
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