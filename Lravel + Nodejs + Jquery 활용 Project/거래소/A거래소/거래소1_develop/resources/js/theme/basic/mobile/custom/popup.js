/*쓰기버튼누르면 모달팝업*/
$('.write_btn').click(function(){
    
    $('#fullscreen_modal').fadeIn();

    $('.modal_popup #cancel_btn').click(function(){
        $('#fullscreen_modal').fadeOut();
        $('.modal_popup').animate({
            top: 100+'%',
            opacity: 0
        });
        
    });

});
/*//쓰기버튼누르면 모달팝업*/
