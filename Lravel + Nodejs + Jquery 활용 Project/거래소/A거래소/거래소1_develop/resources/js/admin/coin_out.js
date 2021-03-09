$('#notauto_confirm_wrap .jw_overlay, #notauto_confirm_wrap .jw_modal_hd>div').click(function(){
    $('#notauto_confirm_wrap').removeClass('active');
    
    setTimeout(function() {
        $('#notauto_confirm_wrap').addClass('hidden');
    }, 300);
});

$('button.notauto_withdraw_confirm').click(function(){
    
    var id = $(this).data('id');
    $('input[name="id"]').val(id);

    $('#notauto_confirm_wrap').removeClass('hidden');
			
    setTimeout(function() {
        $('#notauto_confirm_wrap').addClass('active');
    }, 300);

});

$('button.notauto_confirm_submit').click(function(){
    var tx_id = $('input[name="tx_id"]').val();

    $.ajax({
        url: '/admin/coin/manual_confirm',
        type: 'POST',
        data: {_token: CSRF_TOKEN, tx_id:tx_id, request_id: id},
        dataType: 'JSON',
        success: function (data) { 

            $('input[name="id"]').val('');

            $('input[name="tx_id"]').val('');

            $('#notauto_confirm_wrap').removeClass('active');
    
            setTimeout(function() {
                $('#notauto_confirm_wrap').addClass('hidden');
            }, 300);

            alert(data.message);
            $('#co_button_wrap_'+request_id).html('<span class="withdraw_confirm">'+data.status+'</span>');
        }
    });
});

function auto_withdraw_confirm(request_id){
    if(confirm("정말 출금을 승인 하시겠습니까?")){
        $.ajax({
            url: '/admin/coin/external_withdraw_confirm',
            type: 'POST',
            data: {_token: CSRF_TOKEN, request_id: request_id},
            dataType: 'JSON',
            success: function (data) { 

                alert(data.message);
                $('#co_button_wrap_'+request_id).html('<span class="withdraw_wait">'+data.status+'</span>');
            }
        });
    }
}

function cancel_co_io(request_id, status){
    if(confirm("정말 출금을 거부 하시겠습니까?")){
        $.ajax({
            url: '/admin/coin/cancel_coin_io',
            type: 'POST',
            data: {_token: CSRF_TOKEN, request_id: request_id, status: status},
            dataType: 'JSON',
            success: function (data) { 
    
                alert(data.message);
                $('#co_button_wrap_'+request_id).html('<span class="withdraw_reject">'+data.status+'</span>');
    
            }
        });
    }
}

function internal_withdraw_confirm(request_id){
    if(confirm("정말 출금을 승인 하시겠습니까?")){
        $.ajax({
            url: '/admin/coin/internal_withdraw_confirm',
            type: 'POST',
            data: {_token: CSRF_TOKEN, request_id: request_id},
            dataType: 'JSON',
            success: function (data) { 

                alert(data.message);
                $('#co_button_wrap_'+request_id).html('<span class="withdraw_confirm">'+data.status+'</span>');
            }
        });
    }
}