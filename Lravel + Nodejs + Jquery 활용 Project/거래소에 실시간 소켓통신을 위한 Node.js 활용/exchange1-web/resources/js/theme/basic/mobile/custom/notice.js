
/* 자주묻는문의 유형별로 보여주는 버튼 */
$('#faq_select_wrap .type_list li').each(function(index){
    
    $(this).attr('data-index',index);
    
})

$('#faq_select_wrap .type_list li').click(function(){
    
    var thisTit = $(this).children('a').text();
    
    $('#faq_select_wrap .type_tit').text(thisTit);
    
    $('#faq_type_check').prop("checked", false);
    
    var i = $(this).data('index');
    
    if( i == 0 ){
        
        $(".faq_tab").show();
        
    }else{
        
        $(".faq_tab").hide();
        $(".faq_tab:contains('"+ thisTit +"')").show();
        
    }
    
});
/* //자주묻는문의 유형별로 보여주는 버튼 */

/* 1:1문의 팝업 */

$('#qnaWrite').click(function(){
    
    $('#qnaWrite1').animate({
        opacity: 1,
        top:0
    });
    
})

$('#qnaModify').click(function(){
    
    $('#qnaModify1').animate({
        opacity: 1,
        top:0
    });
    
})
$('#pna_write').submit(function(){
    var is_title = $('input[name="title"]').val();
    var is_cont = $('textarea[name="description"]').val();

    if(is_title == ''){
        swal({
            text: __.message.is_title,
            icon: "warning",
            buttons: {
                yes: {
                    text: __.message.yes,
                    value: true,
                },
            },
        })
        return false;
    }
    if(is_cont == ''){
        swal({
            text: __.message.is_cont,
            icon: "warning",
            buttons: {
                yes: {
                    text: __.message.yes,
                    value: true,
                },
            },
        })
        return false;
    }
    return true;
})