

var $grid = $('.grid').masonry({
    itemSelector: '.grid-item',
    fitWidth: true
});

$grid.imagesLoaded()
    .always( function( instance ) {
        $('.grid .grid-item a p').removeClass('is-loading');
    }).progress( function() {
        $grid.masonry('layout');
        
    });

function batting_do(art_id){
    $("#popupcux .cux_modal_dialog .footer_btn").html('<button type="button" id="batting_price_submit" class="cashgo">베팅하기</button>');
    $("#popupcux").data('state', 'batting_do');
    $('input[name="batting_price"]').val('').attr('disabled', false);

    var already_bat_price = $('#already_bat_price').text();
    $('input[name="batting_price"]').val(already_bat_price);
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
                        data: {_token: CSRF_TOKEN, art_id: art_id, batting_price: batting_price},
                        dataType: 'JSON',
                        async: false,
                        success: function (data) {
                            if(data == 'balance'){
                                alert('수수료를 포함한 베팅 금액이 보유한 코인보다 적습니다.');
                            }else if(data == 'network'){
                                alert('네트워크 문제로 인해 실패하셨습니다. 잠시 후 다시 시도해주세요.');
                            }else{
                                $('#bat_cnt'+art_id).text(parseInt(bat_cnt)+1);
                                $('#batting_btn'+art_id).attr("onclick","batting_load("+art_id+")");
                                $('#batting_btn'+art_id).text('완료');
                                alert('해당 작품에 베팅이 완료 되었습니다!'); 
                                $('#bat_item'+art_id+'#already_bat_price').text(data.batting_price);
                                $("#lean-overlay").trigger("click");
                            }
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
    $("#popupcux .cux_modal_dialog .footer_btn").html('<button type="button" id="batting_price_submit" class="cashgo" onclick="batting_edit(' + art_id + ')">베팅수정</button>');
    $("#popupcux").data('state', 'batting_load');
    $('input[name="batting_price"]').val('').attr('disabled', true);

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '/batting/load',
        type: 'POST',
        data: {_token: CSRF_TOKEN, art_id: art_id},
        dataType: 'JSON',
        success: function (data) {
            if($("#popupcux").data('state') !== 'batting_load') {
                return;
            }

            $('input[name="batting_price"]').attr('disabled', false).val(data.batting_price);
        }
    });
}

function batting_edit(art_id){
    var batting_price = $('input[name="batting_price"]').val();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    if(batting_price > 0){
        $.ajax({
            url: '/batting/edit',
            type: 'POST',
            data: {_token: CSRF_TOKEN, art_id: art_id, batting_price: batting_price},
            dataType: 'JSON',
            async: false,
            success: function (data) { 
                if(data == 'balance'){
                    alert('수수료를 포함한 베팅 금액이 보유한 코인보다 적습니다.');
                }else if(data == 'network'){
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
            }
        });
    }else{
        alert('베팅금액은 0보다 커야 합니다.');
    }
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
            if(data.change === 1){
                recomend_cnt = parseInt(recomend_cnt) - 1;
                $('#recomend_btn'+review_id).html('<i><img src="/storage/image/mobile/ic_up.png" alt=""/></i> '+recomend_cnt);
                $('#trecomend_btn'+review_id).html('<i><img src="/storage/image/mobile/ic_down.png" alt=""/></i> '+recomend_cnt);
            }else if(data.change === 2){
                recomend_cnt = parseInt(recomend_cnt) + 1;
                unrecomend_cnt = parseInt(unrecomend_cnt) - 1;
                $('#recomend_btn'+review_id).html('<i><img src="/storage/image/mobile/ic_up.png" alt=""/></i> '+ recomend_cnt);
                $('#unrecomend_btn'+review_id).html('<i><img src="/storage/image/mobile/ic_down.png" alt=""/></i> ' + unrecomend_cnt);
                $('#trecomend_btn'+review_id).html('<i><img src="/storage/image/mobile/ic_up.png" alt=""/></i> '+ recomend_cnt);
                $('#tunrecomend_btn'+review_id).html('<i><img src="/storage/image/mobile/ic_down.png" alt=""/></i> ' + unrecomend_cnt);
            }else{
                recomend_cnt = parseInt(recomend_cnt) + 1;
                $('#recomend_btn'+review_id).html('<i><img src="/storage/image/mobile/ic_up.png" alt=""/></i> ' + recomend_cnt);
                $('#trecomend_btn'+review_id).html('<i><img src="/storage/image/mobile/ic_up.png" alt=""/></i> ' + recomend_cnt);
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
            if(data.change === 1){
                unrecomend_cnt = parseInt(unrecomend_cnt) - 1;
                $('#unrecomend_btn'+review_id).html('<i><img src="/storage/image/mobile/ic_down.png" alt=""/></i> '+unrecomend_cnt);
                $('#tunrecomend_btn'+review_id).html('<i><img src="/storage/image/mobile/ic_down.png" alt=""/></i> '+unrecomend_cnt);
            }else if(data.change === 2){
                unrecomend_cnt = parseInt(unrecomend_cnt) + 1;
                recomend_cnt = parseInt(recomend_cnt) - 1;
                $('#unrecomend_btn'+review_id).html('<i><img src="/storage/image/mobile/ic_down.png" alt=""/></i> '+ unrecomend_cnt);
                $('#recomend_btn'+review_id).html('<i><img src="/storage/image/mobile/ic_up.png" alt=""/></i> ' + recomend_cnt);
                $('#tunrecomend_btn'+review_id).html('<i><img src="/storage/image/mobile/ic_down.png" alt=""/></i> '+ unrecomend_cnt);
                $('#trecomend_btn'+review_id).html('<i><img src="/storage/image/mobile/ic_up.png" alt=""/></i> ' + recomend_cnt);
            }else{
                unrecomend_cnt = parseInt(unrecomend_cnt) + 1;
                $('#unrecomend_btn'+review_id).html('<i><img src="/storage/image/mobile/ic_down.png" alt=""/></i> ' + unrecomend_cnt);
                $('#tunrecomend_btn'+review_id).html('<i><img src="/storage/image/mobile/ic_down.png" alt=""/></i> ' + unrecomend_cnt);
            }
            alert(data.msg);
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

            var template = $($('#template-colist').html());
            template.find('.colist-profile-img').attr('src', '/storage/image/' + data.profile_img);
            template.find('.colist-nickname').text(data.nickname);
            template.find('.colist-date').text(data.date);
            template.find('.delete').attr('onclick', 'review_delete(' + data.review_id + ')');
            template.find('.modify')
                .attr('onclick', 'review_load(' + data.review_id + ')')
                .leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
            template.find('.colist-review').text(data.review_body).attr('id', 'review_body' + data.review_id);
            
            $('#section_3>h3>strong').text(review_cnt+'개');
            $('.action_rv ul li .comment_cnt').text(review_cnt);
            $('#section_3 .cobox').prepend(template).find('.non_review').remove();
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

function review_load(review_id){
    $("#popupcux2 .cux_modal_dialog .footer_btn").html('<button type="button" id="batting_price_submit" class="cashgo" onclick="review_edit(' + review_id + ')">수정</button>');
    $('#popupcux2 textarea[name="review_body"]').text($('#review_body'+review_id).text());
}

function review_edit(review_id){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var review_body = $('#popupcux2 textarea[name="review_body"]').val();
    $.ajax({
        url: '/mypage/mypage_comment_edit',
        type: 'POST',
        /* send the csrf-token and the input to the controller */
        data: {_token: CSRF_TOKEN, review_id:review_id, review_body:review_body },
        dataType: 'JSON',
        /* remind that 'data' is the response of the AjaxController */
        success: function (data) { 
            $('#review_body'+review_id).text(data.review_body);
            $('#treview_body'+review_id).text(data.review_body);
            $("#lean-overlay").trigger("click");
        }
    });
}

function review_create2(art_id){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var review_cnt = $('#section_4>h3>strong').text().replace('개','');
    var rating = $('input[name="rating"]').val();
    var review_body = $("textarea[name='review_body2']").val();

    $.ajax({
            url: '/review/store2',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, art_id: art_id, review_body: review_body, rating:rating},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) {
            alert('댓글작성완료!'); 
            review_cnt = parseInt(review_cnt)+1;

            var template = $($('#template-columlist').html());
            template.find('.columlist-profile-img').attr('src', '/storage/image/' + data.profile_img);
            template.find('.columlist-nickname').text(data.name);
            template.find('.columlist-review').text(data.review_body).attr('id', 'expert_review_body' + data.review_id);
            template.find('.columlist-star' + Number(data.rating).toString().replace('.', '')).show();
            template.find('.columlist-star-num').text(data.rating);
            
            $('#section_4>h3>strong').text(review_cnt+'개');
            $('.colum').prepend(template);
        }
    });
}

function refresh_products(uId, caId, status, start, limit) {
    var keyword = $('#skeyword').val();
    
    var list = $("#product_list");
    if (list.data("isLoading")) {
        return;
    }
    list.data("isLoading", true);
	
	
	
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: "/mypage/mobile/product_list",
        type: "POST",
        data: {
            _token: CSRF_TOKEN,
            ca_id: caId,
            status: status,
            start: start,
            limit: limit,
            keyword: keyword
        },
        dataType: "JSON"
    })
        .done(function(data) {
            if(data.product_cnt === 0) {
                list.text('해당되는 작품이 없습니다.').height('initial');
                $('#product-show-more').hide();
                return;
            }

            data.products.forEach(function(item) {
                var template = $($("#grid-item-template").html());
                template.find('.item-show').attr('href', '/products/' + item.id);
                template
                    .find(".image")
                    .attr("src", "/storage/image/product/" + item.image1);
                template.find(".product-category").text(item.category.ca_name);
                template.find(".title").text(item.title);
                template.find(".coinic-value").text(numberWithCommas(item.coin_price));
                template.find(".kric-value").text(numberWithCommas(item.cash_price));

                var button = template.find(".betbt");
                button.attr('id', "batting_btn" + item.id);
                if (status === "1") {
                    if (uId === "") {
                        button
                            .text("베팅")
                            .click(function() {
                                alert("로그인을 하셔야 베팅이 가능합니다.");
                                location.href = "/login";
                            });
                    } else if (item.battings.length === 0) {
                        button
                            .text("베팅")
                            .click(function() {
                                batting_do(item.id);
                            })
                            .leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
                    } else {
                        button
                            .text("완료")
                            .click(function() {
                                batting_load(item.id);
                            })
                            .leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
                    }
                } else if (status === "0") {
                    button
                        .text('예정');
                } else if (status === "2") {
                    button
                        .text('종료');
                }

                $grid.masonry()
                    .append(template)
                    .masonry('appended', template)
                    .masonry();
            });

            $grid.imagesLoaded().always(function(instance) {
                $('.grid .grid-item a p').removeClass('is-loading');
            }).progress( function() {
                $grid.masonry('layout');
            });
            
            var itemCount = list.children().length;
            if(itemCount >= data.product_cnt) {
                $('#product-show-more').hide();
            } else {
                $('#product-show-more').show();
            }

            setTimeout(function(){
                $grid.masonry();
            }, 250);
        })
        .error(function() {
            console.log("error");
        })
        .always(function() {
            list.data("isLoading", false);
        });
}

if($('.product_img_slide>div').length > 1){

$('.product_img_slide').bxSlider({
    autoControls: true,
    infiniteLoop: true,
    minSlides: 1,
    maxSlides: 1,
    speed : 1000,
    pause: 3000,
    touchEnabled:true,
    controls : true,
    pager : false,
    nextText: '<i class="fal fa-chevron-right"></i>',
    prevText: '<i class="fal fa-chevron-left"></i>'
});
}

function cart_insert(art_id){

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    
    console.log(CSRF_TOKEN);
    
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

$('.cate-slide ul').each(function(){  
    var liItems = $(this);
    var Sum = 0;
    if(liItems.children('li').length >= 1){
        $(this).children('li').each(function(i, e){
            Sum += $(e).outerWidth(true);
        });
        $(this).width(Sum+1);
    } 
});

$('.cate-slide ul').each(function (){
    var width = $(this).width(),
        length = $(this).length,
        index = $(this).find('.active').index();
                
    $('.cate-slide').scrollLeft((width/length) * index);
});

$('#search_popup_btn').click(function() {
    var modal = $("#search_popup .cux_modal_dialog");
})
.leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });