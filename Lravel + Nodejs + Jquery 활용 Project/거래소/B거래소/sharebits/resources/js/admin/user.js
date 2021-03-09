$( document ).ready(function() {

	$("select[name='status']").change(function(){
		var status = $(this).val();
		var id = $(this).data('id');
		
		if(confirm('정말 해당 회원의 상태를 수정하시겠습니까?')){
			$.ajax({
				url: '/admin/user_status_change',
				type: 'POST',
				/* send the csrf-token and the input to the controller */
				data: {_token: CSRF_TOKEN, status: status, id:id},
				dataType: 'JSON',
				/* remind that 'data' is the response of the AjaxController */
				success: function (data) { 
					if(data.status == 1){
						alert('해당 회원의 계정 사용을 허용하셨습니다.');
						$(this).val($.data(this, 'current'));
					}else if(data.status == 2){
						alert('해당 회원의 계정 사용을 정지하셨습니다.');
						$(this).val($.data(this, 'current'));
					}
				}
			});
		}
		
		$(this).val(status);
		
	});

	$('input[name="email_verified"]').change(function(){
		var email_verified = $(this).val();
		var id = $('input[name="ver_temp_user_id"]').val();
		
		if(confirm('이메일 인증상태를 변경하시겠습니까?')){
			$.ajax({
				url: '/admin/email_security_change',
				type: 'POST',
				data: {_token: CSRF_TOKEN, email_verified: email_verified, id:id},
				dataType: 'JSON',
				success: function (data) { 
					$.alert('변경 완료');
				}
			});
		}else{
			
			if(email_verified == 0){
				$('#email_verified_y').prop("checked",true);
				$('#email_verified_n').prop("checked",false);
			}else{
				$('#email_verified_n').prop("checked",true);
				$('#email_verified_y').prop("checked",false);
			}
		}
		
	});

	$('input[name="mobile_verified"]').change(function(){
		var mobile_verified = $(this).val();
		var id = $('input[name="ver_temp_user_id"]').val();
		
		if(confirm('휴대폰 인증 상태를 변경하시겠습니까?')){
			$.ajax({
				url: '/admin/mobile_security_change',
				type: 'POST',
				data: {_token: CSRF_TOKEN, mobile_verified: mobile_verified, id:id},
				dataType: 'JSON',
				success: function (data) { 
					$.alert('변경 완료');
				}
			});
		}else{
			
			if(mobile_verified == 0){
				$('#mobile_verified_y').prop("checked",true);
				$('#mobile_verified_n').prop("checked",false);
			}else{
				$('#mobile_verified_n').prop("checked",true);
				$('#mobile_verified_y').prop("checked",false);
			}
		}
		
	});

	$('input[name="google_verified"]').change(function(){
		var google_verified = $(this).val();
		var id = $('input[name="ver_temp_user_id"]').val();
		
		if(confirm('OTP 인증상태를 변경하시겠습니까?')){
			$.ajax({
				url: '/admin/google_security_change',
				type: 'POST',
				data: {_token: CSRF_TOKEN, google_verified: google_verified, id:id},
				dataType: 'JSON',
				success: function (data) { 
					$.alert('변경 완료');
				}
			});
		}else{
			
			if(google_verified == 0){
				$('#google_verified_y').prop("checked",true);
				$('#google_verified_n').prop("checked",false);
			}else{
				$('#google_verified_n').prop("checked",true);
				$('#google_verified_y').prop("checked",false);
			}
		}
		
	});

	$('button.document_agree_btn').click(function(){
		
		var id = $(this).data('id');
		
		if(confirm('정말 승인하시겠습니까?')){
			$.ajax({
				url: '/admin/document_agree',
				type: 'POST',
				data: {_token: CSRF_TOKEN, id:id},
				dataType: 'JSON',
				success: function (data) { 
					if(data.status == 1){
						alert('정상적으로 승인 처리 되었습니다.');
						$(this).parent().html('<span class="success_status">승인완료!</span>');
					}else{
						alert('신분증 자료가 모두 제출되지 않았습니다.\n신분증 자료가 모두 제출되어야만 승인/거절을 할 수 있습니다.');
					}
				}
			});
		}
	});

	$('button.document_reject_load_btn').click(function(){
		
		var id = $(this).data('id');
		
		$('input[name="temp_user_id"]').val(id);
		
		$.ajax({
			url: '/admin/document_reject_load',
			type: 'POST',
			data: {_token: CSRF_TOKEN, id:id},
			dataType: 'JSON',
			success: function (data) { 
				if(data.reject_reason == ''){
					$('input[name="document_reject"]').val(data.reject_reason);
					$('.jw_modal_ft button').text('사유 등록');	
				}else{
					$('.jw_modal_ft button').text('사유 변경');
				}
			}
		});
		
		$('#reject_wrap').removeClass('hidden');
			
		setTimeout(function() {
			$('#reject_wrap').addClass('active');
		}, 300);
		
	});

	$('#reject_wrap .jw_overlay, #reject_wrap .jw_modal_hd>div').click(function(){
		$('#reject_wrap').removeClass('active');
		
		setTimeout(function() {
			$('#reject_wrap').addClass('hidden');
		}, 300);
	});

	$('button.disagree_document_btn').click(function(){
		
		var id = $('input[name="temp_user_id"]').val();
		var document_reject = $('input[name="document_reject"]').val(); 
		
		$.ajax({
			url: '/admin/document_disagree',
			type: 'POST',
			data: {_token: CSRF_TOKEN, id:id, document_reject: document_reject},
			dataType: 'JSON',
			success: function (data) { 
				if(data.status == 1){
					alert('정상적으로 거절 처리 되었습니다.');
					$(this).parent().html('<span class="fail_status">승인거절</span>');
				}else{
					alert('신분증 자료가 모두 제출되지 않았습니다.\n신분증 자료가 모두 제출되어야만 승인/거절을 할 수 있습니다.');
				}
			}
		});
		
		$('#reject_wrap').removeClass('hidden');
			
		setTimeout(function() {
			$('#reject_wrap').addClass('active');
		}, 300);
		
	});

	$('button.account_agree_btn').click(function(){
		
		var id = $(this).data('id');
		
		if(confirm('정말 승인하시겠습니까?')){
			$.ajax({
				url: '/admin/account_agree',
				type: 'POST',
				data: {_token: CSRF_TOKEN, id:id},
				dataType: 'JSON',
				success: function (data) { 
					if(data.status == 1){
						alert('정상적으로 승인 처리 되었습니다.');
						$(this).parent().html('<span class="success_status">승인완료!</span>');
					}else{
						alert('계좌 자료가 모두 제출되지 않았습니다.\n계좌 자료가 모두 제출되어야만 승인/거절을 할 수 있습니다.');
					}
				}
			});
		}
	});

	$('button.account_reject_load_btn').click(function(){
		
		var id = $(this).data('id');
		
		$('input[name="temp_user_id"]').val(id);
		
		$.ajax({
			url: '/admin/account_reject_load',
			type: 'POST',
			data: {_token: CSRF_TOKEN, id:id},
			dataType: 'JSON',
			success: function (data) { 
				if(data.reject_reason == ''){
					$('input[name="account_reject"]').val(data.reject_reason);
					$('.jw_modal_ft button').text('사유 등록');	
				}else{
					$('.jw_modal_ft button').text('사유 변경');
				}
			}
		});
		
		$('#reject_wrap').removeClass('hidden');
			
		setTimeout(function() {
			$('#reject_wrap').addClass('active');
		}, 300);
		
	});

	$('#reject_wrap .jw_overlay, #reject_wrap .jw_modal_hd>div').click(function(){
		$('#reject_wrap').removeClass('active');
		
		setTimeout(function() {
			$('#reject_wrap').addClass('hidden');
		}, 300);
	});

	$('button.disagree_account_btn').click(function(){
		
		var id = $('input[name="temp_user_id"]').val();
		var document_reject = $('input[name="account_reject"]').val(); 
		
		$.ajax({
			url: '/admin/account_disagree',
			type: 'POST',
			data: {_token: CSRF_TOKEN, id:id, document_reject: document_reject},
			dataType: 'JSON',
			success: function (data) { 
				if(data.status == 1){
					alert('정상적으로 거절 처리 되었습니다.');
					$(this).parent().html('<span class="fail_status">승인거절</span>');
				}else{
					alert('계좌 자료가 모두 제출되지 않았습니다.\n계좌 자료가 모두 제출되어야만 승인/거절을 할 수 있습니다.');
				}
			}
		});
		
		$('#reject_wrap').removeClass('hidden');
			
		setTimeout(function() {
			$('#reject_wrap').addClass('active');
		}, 300);
		
	});

	$('.user_secession').click(function(e){
		var button = $(e.currentTarget);
		var id = button.data('id');

		button.attr('disabled', true);
		$.confirm({
			title: '확인',
			content: '정말 해당 회원을 탈퇴처리 하시겠습니까?',
			buttons: {
				예: function () {
					$.ajax({
						url: '/admin/user_secession',
						type: 'POST',
						data: {_token: CSRF_TOKEN, status: status, id: id},
						dataType: 'JSON',
						success: function (data) { 
							if(data.status == 1){
								$.alert('해당 회원의 계정을 탈퇴처리 했습니다.');
							}

							button.attr('disabled', false);
						}
					});
				},
				아니오: function () {
					$.alert('회원 탈퇴 처리를 취소 하셨습니다.');
					button.attr('disabled', false);
				}
			}
		});
	});
});
