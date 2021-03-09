$(document).ready(function(){

    //팝업 배열
    var popup_array = ['#ask_region', '#ask_period', '#ask_person', '#budget_direct_input', '#option_B_airline', '#option_B_stay', 
    '#option_B_activi','#option_C_airline', '#option_C_stay', '#option_C_activi', '#option_D_reserva', 
    '#view_input_info', '#request_pay', '#check_plnr_type', '#check_plnrname', '#check_introtext', '#check_certify_phone', '#check_certify_idcard', 
    '#check_certify_doc','#review_write', '#check_prdt_name', '#check_prdt_intro', '#input_prdt_period',
    '#input_prdt_course', '#input_prdt_time', '#mypage_edit_info', '#mypage_reserva'];

    for(i=0;i<popup_array.length;i++){

        $(popup_array[i]).click(function(){

            var thisname = $(this).attr('data-name');
            modalPopup(thisname);
    
        });

    }

    var in_popup_array = ['#view_input_info_detail']; 
    
    for(i=0;i<in_popup_array.length;i++){

        $(in_popup_array[i]).click(function(){

            var thisname = $(this).attr('data-name');
            modalPopupInPopup(thisname);
    
        });

    }

    //팝업닫기
    $('.btn-close-popup').click(function(){

        $(this).parents('.popup__inner').removeClass('is-active');
        $('.popup--modal').delay(200).fadeOut();

    });
    //end

    // 팝업in팝업 닫기
    $('.btn-close-popup--02').click(function(){
        $(this).parents('.popup__inner--02').removeClass('is-active');
    })
    // end

    //팝업실행 함수
    function modalPopup( name ){

        $('.popup--modal').fadeIn();
        $('#'+name).addClass('is-active');

    }//end

    // 팝업in팝업실행 함수
    function modalPopupInPopup ( name ){

        $('#'+name).addClass('is-active');

    }//end

});
