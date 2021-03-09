$('button#deposit').click(function(){
		
    var coin_api = $(this).data('api');
    
    $('input[name="coin_api"]').val(coin_api);
    
    //AJAX 코인 입금 정보 로드
    
    $('#deposite_modal').removeClass('hidden');
        
    setTimeout(function() {
        $('#deposite_modal').addClass('active');
    }, 300);
    
});

$('#deposite_modal .jw_overlay, #deposite_modal .jw_modal_hd>div').click(function(){
    $('#deposite_modal').removeClass('active');
    
    setTimeout(function() {
        $('#deposite_modal').addClass('hidden');
    }, 300);
});


$('button#withraw').click(function(){
		
    var coin_api = $(this).data('api');
    
    $('input[name="coin_api"]').val(coin_api);
    
    //AJAX 코인 입금 정보 로드
    
    $('#withdraw_modal').removeClass('hidden');
        
    setTimeout(function() {
        $('#withdraw_modal').addClass('active');
    }, 300);
    
});

$('#withdraw_modal .jw_overlay, #withdraw_modal .jw_modal_hd>div').click(function(){
    $('#withdraw_modal').removeClass('active');
    
    setTimeout(function() {
        $('#withdraw_modal').addClass('hidden');
    }, 300);
});