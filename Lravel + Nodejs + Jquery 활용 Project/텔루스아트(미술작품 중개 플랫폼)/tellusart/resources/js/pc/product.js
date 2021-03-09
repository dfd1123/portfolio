var $brid = $('.blist').masonry({
    itemSelector: '.item',
    columnWidth: 5
  });

$brid.imagesLoaded()
    .always( function( instance ) {
        $('.blist .item a p').removeClass('is-loading');
    }).progress( function() {
        $brid.masonry('layout');
        
    });

function batting_do(art_id){
    $("#popupcux .cux_modal_dialog .footer_btn").html('<button type="button" id="batting_price_submit" class="cashgo">베팅하기</button>');
    var already_bat_price = $('#already_bat_price').text();
    $('input[name="batting_price"]').val(already_bat_price);
    $("#popupcux").show();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var bat_cnt = $('#bat_cnt'+art_id).text();
    var prvt_dbchk = true;
    
    $('#batting_price_submit').click(function(){
        var batting_price = $('input[name="batting_price"]').val();
        if(prvt_dbchk){
            prvt_dbchk = false;
            if(batting_price > 0){
                $.ajax({
                    url: '/batting/do',
                    type: 'POST',
                    /* send the csrf-token and the input to the controller */
                    data: {_token: CSRF_TOKEN, art_id: art_id, batting_price: batting_price},
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function (data) { 
                        if(data === 'balance'){
                            alert('수수료를 포함한 베팅 금액이 보유한 코인보다 적습니다.');
                        }else if(data === 'network'){
                            alert('네트워크 문제로 인해 실패하셨습니다. 잠시 후 다시 시도해주세요.');
                        }else{
                            $('#bat_cnt'+art_id).text(parseInt(bat_cnt)+1);
                            $('#batting_btn'+art_id).attr("onclick","batting_load("+art_id+")");
                            $('#batting_btn'+art_id).text('완료');
                            alert('해당 작품에 베팅이 완료 되었습니다!'); 
                            $('#bat_item'+art_id+'#already_bat_price').text(data.batting_price);
                            $("#lean-overlay").trigger("click");
                        }
                        refresh_balance();
                        prvt_dbchk = true;
                    }
                });
            }else{
                alert('베팅금액은 0보다 커야 합니다.');
            }
        }
    })
}


function batting_load(art_id){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '/batting/load',
        type: 'POST',
        /* send the csrf-token and the input to the controller */
        data: {_token: CSRF_TOKEN, art_id: art_id},
        dataType: 'JSON',
             /* remind that 'data' is the response of the AjaxController */
        success: function (data) {
            $('input[name="batting_price"]').val(data.batting_price);
            $("#popupcux .cux_modal_dialog .footer_btn").html('<button type="button" id="batting_price_submit" class="cashgo" onclick="batting_edit(' + art_id + ')">베팅수정</button>');            refresh_balance();
        }
    });

}


function batting_edit(art_id){
        var batting_price = $('input[name="batting_price"]').val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var prvt_dbchk = true;

        if(prvt_dbchk){
            prvt_dbchk = false;
            if(batting_price > 0){
                $.ajax({
                    url: '/batting/edit',
                    type: 'POST',
                    /* send the csrf-token and the input to the controller */
                    data: {_token: CSRF_TOKEN, art_id: art_id, batting_price: batting_price},
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function (data) { 
                        if(data === 'balance'){
                            alert('수수료를 포함한 베팅 금액이 보유한 코인보다 적습니다.');
                        }else if(data === 'network'){
                            alert('네트워크 문제로 인해 실패하셨습니다. 잠시 후 다시 시도해주세요.');
                        }else if(data === 'nominus'){
                            alert('기존 배팅 금액보다 적게는 수정이 불가합니다.');
                        }else{
                            $('#batting_btn'+art_id).attr("onclick","batting_load("+art_id+")");
                            $('#batting_btn'+art_id).text('완료');
                            alert('해당 작품에 베팅이 완료 되었습니다!'); 
                            $('#already_bat_price'+art_id).text(data.batting_price);
                            $("#lean-overlay").trigger("click");
                        }
                        refresh_balance();
                    }
                });
            }else{
                alert('베팅금액은 0보다 커야 합니다.');
            }
        }
}

function batting_cancel(art_id){
        var batting_price = $('input[name="batting_price"]').val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $('input[name="batting_price"]').val(batting_price);
        var bat_cnt = $('#bat_cnt'+art_id).text();
        
        $.ajax({
            url: '/batting/cancel',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, art_id: art_id},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) { 
                if(data.dateover){
                    alert('베팅하신 날부터 24시간이 지나 취소가 불가합니다.'); 
                }else{
                    $('#bat_cnt'+art_id).text(parseInt(bat_cnt)-1);
                    $('#batting_btn'+art_id).attr("onclick","batting_do("+art_id+")");
                    $('#batting_btn'+art_id).text('신청');
                    alert('해당 작품에 신청한 베팅이 취소되었습니다.');
                    $('#already_bat_price'+art_id).text(data.batting_price);
                }
                $('#popupcux').hide();
                refresh_balance();
            }
        });
}


$('#search_item').submit(function(e){
    if($('.search_form form input[type="search"]').val() == ''){
        e.preventDefault();
        
        return false;
    }else{
        return true;
    }
});

$(function(){
    $(window).scroll(function() {
       if($(this).scrollTop() > 948){
            $(".pfix").css({ "position": "fixed", "top": "10px" }).addClass("back");
       }else{
            $(".pfix").css({ "position": "relative", "top": "0px" }).remove("back");
       }
    });
});
$(function(){
    $(window).scroll(function() {
       if($(this).scrollTop() > 1106){
            $(".art_txt_tab").css({ "position": "fixed", "top": "0px" });
            $(".art_txt_tab ul").addClass("fix");
       }else{
            $(".art_txt_tab").css({ "position": "relative", "top": "0px" });
            $(".art_txt_tab ul").removeClass("fix");

       }
    });
});

$(function(){
    $(".art_txt_tab ul li a").click(function(e){
        var posY = $($(this).attr("href")).position();		
        $("html,body").stop().animate({'scrollTop':posY.top + 470},600);
        $(".art_txt_tab ul li a").removeClass('on');
        $(this).addClass('on');
        return false;
    }); 
});

//모달START

$(function(){

  $('.show_product_contain .modaltrigger').leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });

});

//모달 END

$('.show_product_contain .colist .modaltrigger').click(function(){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var review_id = $(this).parent().data('id');
  
     $.ajax({
         url: '/mypage/mypage_commnet_show',
         type: 'POST',
         /* send the csrf-token and the input to the controller */
         data: {_token: CSRF_TOKEN, review_id:review_id},
         dataType: 'JSON',
         /* remind that 'data' is the response of the AjaxController */
         success: function (data) { 
             
             $('#comment_modal .comodify p img').attr("src","/storage/image/product/" + data.product_image1);
             $('#comment_modal .comodify span em').text(data.ca_name);
             $('#comment_modal .comodify span strong').text(data.title);
             $('#comment_modal .comodify span span i').text(data.artist_name);
             $('#comment_modal .comodify ul li:last-child i').text(data.rating);
             $('#comment_modal .comodify ul li em').text(data.rating);
             $('#comment_modal .comodify textarea').val(data.review_body);

             $('#expert_comment_modal .comodify p img').attr("src","/storage/image/product/" + data.product_image1);
             $('#expert_comment_modal .comodify span em').text(data.ca_name);
             $('#expert_comment_modal .comodify span strong').text(data.title);
             $('#expert_comment_modal .comodify span span i').text(data.artist_name);
             $('#expert_comment_modal .comodify ul li:last-child i').text(data.rating);
             $('#expert_comment_modal .comodify ul li em').text(data.rating);
             $('#expert_comment_modal .comodify textarea').val(data.review_body);
             
             $("#comment_modal #star_" + data.rating).addClass('active');
             
             $("#comment_modal #star_" + data.rating).parent().children('span').removeClass('on');
             $("#comment_modal #star_"+data.rating).addClass('on').prevAll('span').addClass('on');
             
             $('#comment_modal .review_btn_wrap button.wbt').attr("onclick","review_delete("+review_id+")");
             
             $('#comment_modal .review_btn_wrap button.ylbt').attr("onclick","review_edit("+review_id+")");
             
             //console.log("star_"+data.rating);
      }
   }); 
});

$('.show_product_contain .columlist .modaltrigger').click(function(){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var review_id = $(this).parent().data('id');
    var str = '';
    var std;
  
     $.ajax({
         url: '/mypage/mypage_expert_commnet_show',
         type: 'POST',
         /* send the csrf-token and the input to the controller */
         data: {_token: CSRF_TOKEN, review_id:review_id},
         dataType: 'JSON',
         /* remind that 'data' is the response of the AjaxController */
         success: function (data) { 

             $('#expert_comment_modal .comodify p img').attr("src","/storage/image/product/" + data.product_image1);
             $('#expert_comment_modal .comodify span em').text(data.ca_name);
             $('#expert_comment_modal .comodify span strong').text(data.title);
             $('#expert_comment_modal .comodify span span i').text(data.artist_name);
             $('#expert_comment_modal .comodify ul li:last-child i').text(data.rating);
             $('#expert_comment_modal .comodify ul li em').text(data.rating);
             $('#expert_comment_modal .comodify textarea').val(data.review_body);
             
             std = $('#expert_comment_modal .starRev span[data-rating="'+data.rating+'"]');
             std.parent().children('span').removeClass('on');
             std.addClass('on').prevAll('span').addClass('on');

             $("#expert_comment_modal #star_" + data.rating).parent().children('span').removeClass('on');
             $("#expert_comment_modal #star_"+data.rating).addClass('on').prevAll('span').addClass('on');
             
             $('#expert_comment_modal .review_btn_wrap button.wbt').attr("onclick","expert_review_delete("+review_id+")");
             
             $('#expert_comment_modal .review_btn_wrap button.ylbt').attr("onclick","expert_review_edit("+review_id+")");
             
             //console.log("star_"+data.rating);
      }
   }); 
});


function cart_insert(art_id){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    
    $.ajax({
        url: '/cart/insert',
        type: 'POST',
        /* send the csrf-token and the input to the controller */
        data: {_token: CSRF_TOKEN, art_id: art_id },
        dataType: 'JSON',
        /* remind that 'data' is the response of the AjaxController */
        success: function (data) { 
            if(data.cart_yn == 1){
                    alert('성공적으로 장바구니에 담았습니다.');
            }else{
                    alert('이미 장바구니에 담겨있습니다.');
            }
        }
    });
}

function cart_delete(art_id){
    
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    
    $.ajax({
        url: '/cart/delete',
        type: 'POST',
        /* send the csrf-token and the input to the controller */
        data: {_token: CSRF_TOKEN, art_id: art_id},
        dataType: 'JSON',
        /* remind that 'data' is the response of the AjaxController */
        success: function (data) { 
            alert('해당작품을 장바구니에서 뺐습니다.');
            $('.about_cart_btn').text('장바구니 담기');
            $('.about_cart_btn').attr("onclick","cart_insert("+art_id+")"); 
        }
    });
}

function review_create(art_id){
        var str = '';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var review_cnt = $('#section_3>h3>strong').text().replace('개','');

        $.ajax({
                url: '/review/store',
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: {_token: CSRF_TOKEN, art_id: art_id, review_body:$("textarea[name='review_body']").val()},
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) { 
                alert('댓글작성완료!'); 
                review_cnt = parseInt(review_cnt)+1;
                
                str += '<div class="colist"><p><img src="/storage/image/'+data.profile_img+'" alt=""></p>';
                str += '<ul><li class="none"></li><li class="leftnone">'+data.nickname+'</li><li>'+data.date+'</li><li><button type="button" class="on"><i class="fal fa-thumbs-up"></i> 0</button></li><li><button type="button" ><i class="fal fa-thumbs-down"></i> 0</button></li>';
                str += '<li><a href="javascript:void(0);" class="delete" onclick="review_delete('+data.review_id+')"><i class="far fa-times"></i></a></li><li id="commnet_'+data.review_id+'" style="display: none;"><a href="#comment_modal" class="modaltrigger  modify"><i class="fal fa-pencil"></i></a></li></ul>';
                str += '<ul><li class="contxt kr">'+data.review_body+'</li></ul></div>';
                
                $('#section_3>h3>strong').text(review_cnt+'개');
                $('.action_rv ul li .comment_cnt').text(review_cnt);
                $('#normal_review').append(str);
                $('#normal_review').show();
                $('.non_review').hide();
            }
        }); 
    }
    
    function review_create2(art_id){
        var str = '';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var review_cnt = $('#section_4>h3>strong').text().replace('개','');
        var rating = $('input[name="rating"]').val();

        $.ajax({
                url: '/review/store2',
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: {_token: CSRF_TOKEN, art_id: art_id, review_body:$("textarea[name='review_body2']").val(), rating:rating},
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) { 
                alert('댓글작성완료!'); 
                review_cnt = parseInt(review_cnt)+1;
                
                str += '<div class="columlist"><p><img src="/storage/image/'+data.profile_img+'" alt=""></p>';
                str += '<ul><li>칼럼니스트 : '+data.name+'</li><li id="expert_review_body'+data.id+'">'+data.review_body+'</li><li class="star">';
                if(data.rating <= 0.5){
                    str += '<i class="fas fa-star-half-alt"></i><i class="fal fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i>';
                }else if(data.rating == 1){
                    str += '<i class="fas fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i>';	                    	
                }else if(data.rating == 1.5){
                    str += '<i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="fal fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i>';	                    	
                }else if(data.rating == 2){
                    str += '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i>';	                    	
                }else if(data.rating == 2.5){
                    str += '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="fal fa-star"></i><i class="fal fa-star"></i>';	                    	
                }else if(data.rating == 3){
                    str += '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i>';	                    	
                }else if(data.rating == 3.5){
                    str += '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="fal fa-star"></i>';	                    	
                }else if(data.rating == 4){
                    str += '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fal fa-star"></i>';	                    	
                }else if(data.rating == 4.5){
                    str += '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>';	                    	
                }else if(data.rating == 5){
                    str += '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';	                    	
                }
                
                str += '<span class="en">'+ data.rating +'</span><li></ul></div>';
                
                $('#section_4>h3>strong').text(review_cnt+'개');
                $('.colum').append(str);
                //$('#normal_review').show();
                $('.non_review').hide();
            }
        }); 
    }


function recomend(review_id){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var recomend_cnt = $('#recomend_btn'+review_id).text();
    var unrecomend_cnt = $('#unrecomend_btn'+review_id).text();
    
    $.ajax({
                url: '/review/recomend',
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: {_token: CSRF_TOKEN, review_id: review_id},
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) { 
                    if(data.change == 1){
                        recomend_cnt = parseInt(recomend_cnt) - 1;
                        $('#recomend_btn'+review_id).html('<i class="fal fa-thumbs-up"></i>'+recomend_cnt);
                        $('#trecomend_btn'+review_id).html('<i class="fal fa-thumbs-up"></i>'+recomend_cnt);
                    }else if(data.change == 2){
                        recomend_cnt = parseInt(recomend_cnt) + 1;
                        unrecomend_cnt = parseInt(unrecomend_cnt) - 1;
                        $('#recomend_btn'+review_id).html('<i class="fal fa-thumbs-up"></i>'+ recomend_cnt);
                        $('#unrecomend_btn'+review_id).html('<i class="fal fa-thumbs-down"></i>' + unrecomend_cnt);
                        $('#trecomend_btn'+review_id).html('<i class="fal fa-thumbs-up"></i>'+ recomend_cnt);
                        $('#tunrecomend_btn'+review_id).html('<i class="fal fa-thumbs-down"></i>' + unrecomend_cnt);
                    }else{
                        recomend_cnt = parseInt(recomend_cnt) + 1;
                        $('#recomend_btn'+review_id).html('<i class="fal fa-thumbs-up"></i>' + recomend_cnt);
                        $('#trecomend_btn'+review_id).html('<i class="fal fa-thumbs-up"></i>' + recomend_cnt);
                    }
                    alert(data.msg);
                    
                }
        });
}

function unrecomend(review_id){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var recomend_cnt = $('#recomend_btn'+review_id).text();
    var unrecomend_cnt = $('#unrecomend_btn'+review_id).text();
    
    $.ajax({
                url: '/review/unrecomend',
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: {_token: CSRF_TOKEN, review_id: review_id},
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) { 
                    if(data.change == 1){
                        unrecomend_cnt = parseInt(unrecomend_cnt) - 1;
                        $('#unrecomend_btn'+review_id).html('<i class="fal fa-thumbs-down"></i>'+unrecomend_cnt);
                        $('#tunrecomend_btn'+review_id).html('<i class="fal fa-thumbs-down"></i>'+unrecomend_cnt);
                    }else if(data.change == 2){
                        unrecomend_cnt = parseInt(unrecomend_cnt) + 1;
                        recomend_cnt = parseInt(recomend_cnt) - 1;
                        $('#unrecomend_btn'+review_id).html('<i class="fal fa-thumbs-down"></i>'+ unrecomend_cnt);
                        $('#recomend_btn'+review_id).html('<i class="fal fa-thumbs-up"></i>' + recomend_cnt);
                        $('#tunrecomend_btn'+review_id).html('<i class="fal fa-thumbs-down"></i>'+ unrecomend_cnt);
                        $('#trecomend_btn'+review_id).html('<i class="fal fa-thumbs-up"></i>' + recomend_cnt);
                    }else{
                        unrecomend_cnt = parseInt(unrecomend_cnt) + 1;
                        $('#unrecomend_btn'+review_id).html('<i class="fal fa-thumbs-down"></i>' + unrecomend_cnt);
                        $('#tunrecomend_btn'+review_id).html('<i class="fal fa-thumbs-down"></i>' + unrecomend_cnt);
                    }
                    alert(data.msg);
                    
                }
        });
}
    
function review_edit(review_id){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var review_body = $('#comment_modal .textarea_wrap textarea[name="review_body"]').val();
    $.ajax({
           url: '/mypage/mypage_comment_edit',
           type: 'POST',
           /* send the csrf-token and the input to the controller */
           data: {_token: CSRF_TOKEN, review_id:review_id, review_body:review_body },
           dataType: 'JSON',
           /* remind that 'data' is the response of the AjaxController */
           success: function (data) { 
               
                $('#comment_modal').css("display","none");
                $("#lean-overlay").css("display","none");
               
               $('#review_body'+review_id).text(data.review_body);
               $('#treview_body'+review_id).text(data.review_body);
        }
     });
}


function review_delete(review_id){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    
    if(confirm("정말로 삭제하시겠습니까?")){
        $.ajax({
               url: '/mypage/mypage_comment_delete',
               type: 'POST',
               /* send the csrf-token and the input to the controller */
               data: {_token: CSRF_TOKEN, review_id:review_id},
               dataType: 'JSON',
               /* remind that 'data' is the response of the AjaxController */
               success: function (data) { 
                   
                    alert("삭제하였습니다.");
                    history.go(0);
                    location.href="#section_3";
            }
         });
    }
}

    function expert_review_edit(review_id){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var review_body = $('#expert_comment_modal .textarea_wrap textarea[name="review_body"]').val();
    var review_rating = $('.modal_rating i').text();
    $.ajax({
            url: '/mypage/mypage_expert_comment_edit',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, review_id:review_id, review_body:review_body, review_rating:review_rating},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) { 
                
                $('#expert_comment_modal').css("display","none");
                $("#lean-overlay").css("display","none");
                
                $('#expert_review_body'+review_id).text(data.review_body);
                $('#expert_treview_body'+review_id).text(data.review_body);
                
                var stars = $('#expert_review_body'+review_id).parent().find('li.star');
                stars.find('.en').text(review_rating);
                stars.find('i').removeClass().addClass('fal fa-star');
                if(Number(review_rating) <= 0.5) {
                    stars.find('i').eq(0).removeClass().addClass('fas fa-star-half-alt');
                } else if(Number(review_rating) == 1) {
                    stars.find('i:lt(1)').removeClass().addClass('fas fa-star');
                } else if(Number(review_rating) == 1.5) {
                    stars.find('i:lt(1)').removeClass().addClass('fas fa-star');
                    stars.find('i').eq(1).removeClass().addClass('fas fa-star-half-alt');
                } else if(Number(review_rating) == 2) {
                    stars.find('i:lt(1)').removeClass().addClass('fas fa-star');
                    stars.find('i').eq(1).removeClass().addClass('fas fa-star-half-alt');
                } else if(Number(review_rating) == 2.5) {
                    stars.find('i:lt(2)').removeClass().addClass('fas fa-star');
                    stars.find('i').eq(2).removeClass().addClass('fas fa-star-half-alt');
                } else if(Number(review_rating) == 3) {
                    stars.find('i:lt(3)').removeClass().addClass('fas fa-star');
                } else if(Number(review_rating) == 3.5) {
                    stars.find('i:lt(3)').removeClass().addClass('fas fa-star');
                    stars.find('i').eq(3).removeClass().addClass('fas fa-star-half-alt');
                } else if(Number(review_rating) == 4) {
                    stars.find('i:lt(4)').removeClass().addClass('fas fa-star');
                } else if(Number(review_rating) == 4.5) {
                    stars.find('i:lt(4)').removeClass().addClass('fas fa-star');
                    stars.find('i').eq(4).removeClass().addClass('fas fa-star-half-alt');
                } else if(Number(review_rating) == 5) {
                    stars.find('i:lt(5)').removeClass().addClass('fas fa-star');
                }
        }
        });
    }


function expert_review_delete(review_id){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    
    if(confirm("정말로 삭제하시겠습니까?")){
        $.ajax({
               url: '/mypage/mypage_expert_comment_delete',
               type: 'POST',
               /* send the csrf-token and the input to the controller */
               data: {_token: CSRF_TOKEN, review_id:review_id},
               dataType: 'JSON',
               /* remind that 'data' is the response of the AjaxController */
               success: function (data) { 
                   
                    alert("삭제하였습니다.");
                    history.go(0);
                    location.href="#section_4";
            }
         });
    }
}

function readURLartist(input) {
 
    if (input.files && input.files[0]) {
        var reader = new FileReader();
 
        reader.onload = function (e) {
            $('img.artist_img').attr('src', e.target.result);
        }
 
        reader.readAsDataURL(input.files[0]);
    }
}
 
$("#artist_img").change(function(){
    readURLartist(this);
});

$('.product_img_slide').bxSlider({
    auto: true,
    speed : 1000,
    autoHover: true,
    controls : true,
    pager : false,
    nextText: '<i class="fal fa-chevron-right"></i>',
    prevText: '<i class="fal fa-chevron-left"></i>'
});


$(function(){
    $('.batting_modal_wrap .modaltrigger').leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
});
