/* 회원가입 약관 동의팝업 start */
$('#agree_con_1').click(function(){
	
    $('#agree1_popup').show();
    
    $('.agree_check').click(function(){
        
        $('#register_agree1').prop("checked", true);
        $('#agree1_popup').hide();
        
    })
    
});

$('#agree_con_2').click(function(){
	
    $('#agree2_popup').show();
    
    $('.agree_check').click(function(){
    
        $('#register_agree2').prop("checked", true);
        $('#agree2_popup').hide();
    
    });
	
});
/* 회원가입 약관 동의팝업 end */
