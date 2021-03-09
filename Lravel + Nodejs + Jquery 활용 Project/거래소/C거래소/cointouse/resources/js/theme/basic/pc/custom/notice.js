/*1:1 문의하기 list페이지의 문의하기 버튼누르면 모달팝업*/
$('#qnaWrite').click(function(){
    $('#qnaWrite1').css({
        display: 'block'
    }).animate({
        opacity: 1,
        top:10+'%'
    });
});
/*//1:1 문의하기 list페이지의 문의하기 버튼누르면 모달팝업*/

/*1:1 문의하기 view페이지의 수정하기 버튼누르면 모달팝업*/
$('#qnaModify').click(function(){
    $('#qnaModify1').css({
        display: 'block'
    }).animate({
        opacity: 1,
        top:10+'%'
    });
})
/*//1:1 문의하기 view페이지의 수정하기 버튼누르면 모달팝업*/

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

/* 자주묻는문의 유형별로 보여주는 버튼 */
$('#faq_tab_list ul li').each(function(index){
    
    $(this).attr('data-index',index);
    
})

$('#faq_tab_list ul li').click(function(){
    
    var i = $(this).data('index');
    
    if( i == 0 ){
        
        $(".faq_tab").show();
        
    }else{
        
        var thisTit = $(this).children('a').text();
        
        $(".faq_tab").hide();
        $(".faq_tab:contains('"+ thisTit +"')").show();
        
    }
    
    $(this).addClass('active');
    $('#faq_tab_list ul li').not(this).removeClass('active');
    
});
/* //자주묻는문의 유형별로 보여주는 버튼 */