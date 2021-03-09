$('#register_agree').submit(function(){
	var register_agree1 = $('input[name="register_agree1"]').is(":checked");
	var register_agree2 = $('input[name="register_agree2"]').is(":checked");
	var register_agree3 = $('input[name="register_agree3"]').is(":checked");

	if(register_agree1 && register_agree2){
		$('input[name="register_agree1"]').val(1);
		$('input[name="register_agree2"]').val(1);
		if(register_agree3){
			$('input[name="register_agree3"]').val(1);
		}
		return true;
	}else{

		swal({
			text: __.message.register_agree_ments,
			icon: "warning",
			button: __.message.ok,
		});

		return false;
	}
});
