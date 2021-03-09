$('form.ico_cancel_form button').click(function(){
	
	var obj = $(this);
	var id = obj.data('id');
	var reject = prompt('승인취소 및 거부 사유를 작성하여 주세요.');
	obj.siblings('input[name="reject"]').val(reject);
	
	$('#cancel_form_'+id).submit();
	
});

$('.ico_reject').click(function(){
	swal($(this).data('reject'));
});