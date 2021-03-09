

function mobile_mypage_my_info() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
	$('#mypage_load').show();
    $.ajax({
        url: "/mypage/mobile/mypage_my_info",
        type: "POST",
        data: { _token: CSRF_TOKEN },
        dataType: "JSON",
        success: function(data) {
			$('#mypage_load').hide();
            $('#mb_id').val(data.name);
            $('#nickname').val(data.nickname);
            $('#mb_hp').val(data.mobile_number);
            $('#post_num').val(data.post_num);
            $('#mb_addr1').val(data.addr1);
            $('#mb_addr2').val(data.addr2);
        }
    });
}

function mobile_mypage_my_info_update() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    
    var mb_nickname = $('#nickname').val();
    var mb_hp = $('#mb_hp').val();
    var post_num = $('#post_num').val();
    var mb_addr1 = $('#mb_addr1').val();
    var mb_addr2 = $('#mb_addr2').val();
    
	$('#mypage_load').show();
    $.ajax({
        url: "/mypage/mobile/mypage_my_info_update",
        type: "POST",
        data: { _token: CSRF_TOKEN, mb_nickname : mb_nickname, mb_hp : mb_hp, post_num : post_num, mb_addr1 : mb_addr1, mb_addr2 : mb_addr2 },
        dataType: "JSON",
        success: function(data) {
			$('#mypage_load').hide();
            $('#mb_id').val(data.name);
            $('#nickname').val(data.nickname);
            $('#mb_hp').val(data.mobile_number);
            $('#post_num').val(data.post_num);
            $('#mb_addr1').val(data.addr1);
            $('#mb_addr2').val(data.addr2);
            
            alert('정보를 수정하셨습니다.');
        }
    });
}

function mobile_mypage_product() {
	var list = $("#myart_product_lists");
    var itemCount = list.children().length;
    if (list.data("isLoading")) {
        return;
    }
    list.data("isLoading", true);

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

	var coin_price;
	var cash_price;
	
	var str;
	$('#mypage_load').show();
    $.ajax({
        url: "/mypage/mobile/mypage_product",
        type: "POST",
        data: { _token: CSRF_TOKEN, start: itemCount, limit: 5 },
        dataType: "JSON",
    }).done(function(data) {
		$('#myart_product_cnt').text(data.product_cnt + '개');

        data.products.forEach(function(item) {
			coin_price = NumberWithComma(item.coin_price);
            cash_price = NumberWithComma(item.cash_price);
            
            str = '<div class="obox">';
			str += '<div class="oinfo">';
			str += '<p><a href="/products/' + item.id + '"><img src="/storage/image/product/' + item.image1 + '" alt=""/></a></p>';
			str += '<ul>';
			str += '<li><span class="cate">' + item.ca_name + '</span>';
			if(item.sell_yn == 0){
				str += '<span class="cate" style="float:right;background:#aaa;">판매대기</span></li>';
			}else if(item.sell_yn == 1){
				str += '<span class="cate" style="float:right;background:royalblue;">판매승인</span></li>';
			}else if(item.sell_yn == 2){
				str += '<span class="cate" style="float:right;background:orangered;">판매거절</span></li>';
			}else{
				str += '<span class="cate" style="float:right;background:green;">판매완료</span></li>';
			}
			
			str += '<li>' + item.title + '</li>';
			str += '<li style="font-size:12px;color:#888;">작가명 : ' + item.artist_name + '</li>';
			str += '<li style="font-size:12px;color:#888;">등록날짜  ' + item.created_at + '</li>';
			if(item.batting_yn == 1){
				if(item.batting_status == 0){
					str += '<li style="font-size:12px;color:#888;">베팅상태 : <em class="ready">예정</em></li>';
				}else if(item.batting_status == 1){
					str += '<li style="font-size:12px;color:#888;">베팅상태 : <em class="ing">진행중</em></li>';
				}else{
					str += '<li style="font-size:12px;color:#888;">베팅상태 : <em class="end">완료</em></li>';
				}
				
			}
			if(item.sell_yn == 2){
				str += '<li style="font-size:12px;color:orangered;">판매거부사유 : ' + item.reject_infor + '</li>';
			}
			str += '</ul>';
			str += '</div>';
			str += '<div class="ocount">';
			str += '<ul>';
			str += '<li class="en"><img src="/storage/image/mobile/ic_comment.png" style="width: 21px;border-radius: 0;margin-right: 5px;" alt=""/> ' + item.review_count + '</li>';
			str += '<li class="en"><img src="/storage/image/mobile/ic_heart.png" style="width: 16px;border-radius: 0;margin-right: 5px;" alt=""/> ' + item.get_like + '</li>';
			str += '</ul>';
			str += '</div>';
			str += '<div class="ocash">';
			//str += '<h3>현재 최고 베팅가 <a href="" class="more">내역보기</a></h3>';
			str += '<ul>';
			str += '<li><em class="coin">c</em>' + coin_price + '</li>';
			str += '<li><em class="krw">w</em>' + cash_price + '</li>';
			str += '</ul>';
			str += '</div>';
			str += '<div class="obtn w50 kr">';
			if(item.sell_yn == 3){
				str += '<button type="submit" style="width:100%" class="gray">판매완료</button>';
			}else{
				if(item.batting_status != 1){
					str += '<button type="submit" onclick="location.href=\'/products/' + item.id + '/edit\'" class="yellow">수정하기</button>';
					str += '<button type="submit" class="gray" onclick="mobile_mypage_product_delete(' + item.id + ');">삭제하기</button>';
				}else{
					str += '<button type="submit" style="width:100%" class="gray">베팅중</button>';
				}
			}
			str += '</div>';
			str += '</div>';
            
            
            $('#myart_product_lists').append(str);    
        });
        
    	itemCount = list.children().length;
    	
        if(data.product_cnt <= itemCount){
        	$("#myart_product_more_btn").css('display','none');
		} 
		
		$('#mypage_load').hide();
    })
    .always(function() {
        list.data("isLoading", false);
    });;
}

function mobile_mypage_product_delete(product_id) {
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
	var list = $("#myart_product_lists");

	if(confirm('해당 작품을 삭제하시겠습니까?')){
	    $.ajax({
	        url: "/mypage/mobile/mypage_product_delete",
	        type: "POST",
	        data: { _token: CSRF_TOKEN, product_id : product_id },
	        dataType: "JSON",
	    }).done(function(data) {
	    	list.empty();
	    	mobile_mypage_product();
	    	
	    	if(data == 1){
	    		alert('해당 작품을 삭제하셨습니다.');
	    	}else{
	    		alert('일시적인 오류로 삭제에 실패하셨습니다. 잠시 후 다시 시도해 주세요.');
	    	}
	    	
	    	
	    });
    }
}

function mobile_mypage_batting(date_term, search_yn) {
	var list = $("#mybatting-list");
	
	if(search_yn == 1){
		list.empty();
		$("#myabatting_product_more_btn").css('display','block');
	}

    
    var itemCount = list.children().length;
    if (list.data("isLoading")) {
        return;
    }
    list.data("isLoading", true);
    
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    
	var str;
	
	if(date_term != 0){
		var today = moment().format('YYYY-MM-DD');
		var beforedate = moment().subtract(date_term,'days').format('YYYY-MM-DD');
		
		$('#mybatting_from_date').val(beforedate);
		$('#mybatting_to_date').val(today);
	}
    
    var from_date = $('#mybatting_from_date').val();
    var to_date = $('#mybatting_to_date').val();
    var status = $('#mybatting_status option:selected').val();
	$('#mypage_load').show();
    $.ajax({
        url: "/mypage/mobile/mypage_batting",
        type: "POST",
        data: { _token: CSRF_TOKEN, start: itemCount, limit: 5, date_term: date_term, from_date: from_date, to_date: to_date, status: status },
        dataType: "JSON",
    }).done(function(data) {
        $("#my_batting_cnt").text(data.batting_cnt + "개");
        $("#my_batting_ing_cnt").text(data.batting_ings_cnt);
        $("#my_batting_end_cnt").text(data.batting_ends_cnt);
		$("#sidebar_total_batting_cnt").text(data.batting_ings_cnt);
		
		
        data.battings.forEach(function(item) {
			str = '<div class="obox">';
			str += '<div class="oinfo">';
			str += '<p><a href="/products/' + item.art_id + '"><img src="/storage/image/product/' + item.image1 + '" alt=""/></a></p>';
			str += '<ul>';
			if(item.batting_status == 1){
				str += '<li><span class="ing">베팅중</span></li>';
			}else{
				str += '<li><span class="end">베팅마감</span></li>';
			}
			str += '<li>' + item.title + '</li>';
			str += '<li>작가명 : ' + item.artist_name + '</li>';
			str += '</ul>';
			str += '</div>';
			str += '<div class="ocash mybatting_much">';
			str += '<h3>내 베팅금액</h3>';
			str += '<ul>';
			str += '<li><em class="coin">c</em>' + NumberWithComma(item.batting_price) + '</li>';
			str += '</ul>';
			str += '<h3>보상금액</h3>';
			str += '<ul>';
			str += '<li><em class="coin">c</em>' + NumberWithComma(item.get_price) + '</li>';
			str += '</ul>';
			str += '</div>';
			str += '</div>';
			
			list.append(str);
        });
        
    	itemCount = list.children().length;
    	
        if(data.batting_cnt <= itemCount){
        	$("#myabatting_product_more_btn").css('display','none');
		}
		
		$('#mypage_load').hide();
    })
    .always(function() {
        list.data("isLoading", false);
    });
}

function mobile_mypage_cart() {
	var list = $("#mycart-list");
    var itemCount = list.children().length;
    if (list.data("isLoading")) {
        return;
    }
    list.data("isLoading", true);
    
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    
    var str;
    $('#mypage_load').show();
    $.ajax({
        url: "/mypage/mobile/mypage_cart",
        type: "POST",
        data: { _token: CSRF_TOKEN, start: itemCount, limit: 5 },
        dataType: "JSON",
    }).done(function(data) {
        $("#mycart_cnt").text(data.cart_cnt + "개");
        $("#sidebar_total_cart_cnt").text(data.cart_cnt);
		
        data.carts.forEach(function(item) {
        	str = '<div class="obox">';
			str += '<div class="check">';
			str += '<input type="checkbox" id="check' + item.id + '" name="del_unit[]" value="' + item.id + '">';
			str += '<label for="check' + item.id + '"><span></span></label>';
			str += '</div>';
			str += '<div class="oinfo">';
			str += '<p><a href="/products/' + item.product_id + '"><img src="/storage/image/product/' + item.image1 + '" alt=""/></a></p>';
			str += '<ul>';
			str += '<li><span class="cate">' + item.ca_name + '</span></li>';
			str += '<li>' + item.title + '</li>';
			str += '<li>작가명 : ' + item.artist_name + '</li>';
			str += '</ul>';
			str += '</div>';
			str += '<div class="ocash">';
			str += '<ul>';
			str += '<li><em class="coin">c</em>' + NumberWithComma(item.coin_price) + '</li>';
			str += '<li><em class="krw">w</em>' + NumberWithComma(item.cash_price) + '</li>';
			str += '</ul>';
			str += '</div>';	
			str += '<div class="obtn kr">';
			if(item.batting_status == 0){
				str += '<button type="submit" onclick="location.href=\'/orders/' + item.product_id + '\'" class="darkgray" style="width:50%;">구매하기</button>';
				str += '<button type="submit" onclick="location.href=\'/products/' + item.product_id + '\'" class="gray" style="width:50%;">보러가기</button>';
			}else if(item.batting_status == 1){
				str += '<button type="submit" onclick="location.href=\'/products/' + item.product_id + '\'" class="yellow">베팅하기</button>';
				str += '<button type="submit" onclick="location.href=\'/orders/' + item.product_id + '\'" class="darkgray">구매하기</button>';
				str += '<button type="submit" onclick="location.href=\'/products/' + item.product_id + '\'" class="gray">보러가기</button>';
			}else{
				str += '<button type="submit" onclick="location.href=\'/orders/' + item.product_id + '\'" class="darkgray" style="width:50%;">구매하기</button>';
				str += '<button type="submit" onclick="location.href=\'/products/' + item.product_id + '\'" class="gray" style="width:50%;">보러가기</button>';
			}
			str += '</div>';
			str += '</div>';
			list.append(str);
        });
        
    	itemCount = list.children().length;
    	
        if(data.cart_cnt <= itemCount){
        	$("#my_cart_more_btn").css('display','none');
		}
		
		$('#mypage_load').hide();
    })
    .always(function() {
        list.data("isLoading", false);
    });
}

function selectDelRow() {

    var chk = document.getElementsByName("del_unit[]"); // 체크박스객체를 담는다
    var len = chk.length;    //체크박스의 전체 개수
    var checkRow = '';      //체크된 체크박스의 value를 담기위한 변수
    var checkCnt = 0;        //체크된 체크박스의 개수
    var checkLast = '';      //체크된 체크박스 중 마지막 체크박스의 인덱스를 담기위한 변수
    var rowid = '';             //체크된 체크박스의 모든 value 값을 담는다
    var cnt = 0;                 

    for(var i=0; i<len; i++){
        if(chk[i].checked == true){
            checkCnt++;        //체크된 체크박스의 개수	
            checkLast = i;     //체크된 체크박스의 인덱스
        }
    } 



    for(var i=0; i<len; i++){
        if(chk[i].checked == true){  //체크가 되어있는 값 구분
            checkRow = chk[i].value;

            if(checkCnt == 1){                            //체크된 체크박스의 개수가 한 개 일때,
                rowid += checkRow;        //'value'의 형태 (뒤에 ,(콤마)가 붙지않게)
            }else{                                            //체크된 체크박스의 개수가 여러 개 일때,
                if(i == checkLast){                     //체크된 체크박스 중 마지막 체크박스일 때,
                    rowid += checkRow;  //'value'의 형태 (뒤에 ,(콤마)가 붙지않게)
                }else{
                    rowid += checkRow+"|";	 //'value',의 형태 (뒤에 ,(콤마)가 붙게)         			
                }
            }
        
            cnt++;
            checkRow = '';    //checkRow초기화.
        }
    }

    return rowid;    //'value1', 'value2', 'value3' 의 형태로 출력된다.

}

function mobile_cart_delete(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
	var list = $("#mycart-list");
	var delete_id = selectDelRow();

    $.ajax({
        url: "/mypage/mobile/mypage_cart_delete",
        type: "POST",
        data: { _token: CSRF_TOKEN, delete_id : delete_id },
        dataType: "JSON",
    }).done(function(data) {
    	list.empty();
    	mobile_mypage_cart();
    	
    	if(data.messages == 'success'){
    		alert('선택하신 장바구니 목록을 삭제하셨습니다.');
    	}else{
    		alert('일시적인 오류로 장바구니 목록삭제에 실패하셨습니다. 잠시 후 다시 시도해 주세요.');
    	}
    	
    	
    });
    
}

function mobile_mypage_buy_list(date_term, search_yn) {
	var list = $("#mybuy-list");
	if(search_yn == 1){
		list.empty();
		$("#mybuy_product_more_btn").css('display','block');
	}
	
	
    var itemCount = list.children().length;
    if (list.data("isLoading")) {
        return;
    }
    list.data("isLoading", true);

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
	
	if(date_term != 0){
		var today = moment().format('YYYY-MM-DD');
		var beforedate = moment().subtract(date_term,'days').format('YYYY-MM-DD');
		
		$('#mybuy_from_date').val(beforedate);
		$('#mybuy_to_date').val(today);
	}
	

    var from_date = $('#mybuy_from_date').val();
    var to_date = $('#mybuy_to_date').val();
    var status = $('#mybuy_status option:selected').val();
    
    var str;
	$('#mypage_load').show();
    $.ajax({
        url: "/mypage/mobile/mypage_buy_list",
        type: "POST",
        data: { _token: CSRF_TOKEN, start: itemCount, limit: 5, date_term: date_term, from_date: from_date, to_date: to_date, status: status},
        dataType: "JSON",
    }).done(function(data) {
		//console.log(data);
		
		$("#mybuy_cnt").text(data.buy_cnt + "개");
        $("#mybuy_request").text(data.buy_request_cnt);
        $("#mybuy_ready").text(data.buy_ready_cnt);
        $("#mybuy_ing").text(data.buy_ing_cnt);
        $("#mybuy_end").text(data.buy_end_cnt);
        $("#mybuy_cancel").text(data.buy_cancel_cnt);
        $("#mybuy_finish").text(data.buy_finish_cnt);
		
		$("#sidebar_total_order_cnt").text(data.buy_sidebar_cnt);
		
        data.buys.forEach(function(item) {
			
			str = '<div>';
			str += '<div class="o_date kr">';
			str += '<span>주문번호 : <strong>' + pad(item.id,9) + '</strong></span>';
			str += '<em class="en">' + item.created_at + '</em>';
			str += '</div>';
			str += '<div class="obox">';
			str += '<div class="oinfo">';
			str += '<p><a href="/order/bil/' + item.id + '"><img src="/storage/image/product/' + item.image1 + '" alt=""/></a></p>';
			str += '<ul>';
			if(item.order_cancel === 0){
				if(item.order_state === 0){
					str += '<li><span class="ing">주문신청</span></li>';
				}else if(item.order_state === 1){
					str += '<li><span class="ing">배송대기</span></li>';
				}else if(item.order_state === 2){
					str += '<li><span class="ing">배송중</span></li>';
				}else if(item.order_state === 3){
					str += '<li><span class="ing">배송완료</span></li>';
				}else if(item.order_state === 4){
					str += '<li><span class="stop">취소/환불</span></li>';
				}else if(item.order_state === 5){
					str += '<li><span class="stop">주문확정</span></li>';
				} 
			}else if(item.order_cancel === 1){
				str += '<li><span class="stop">주문취소</span></li>';
			}else{
				if(item.order_state !== 4){
					str += '<li><span class="ing">환불요청중</span></li>';
				}else{
					str += '<li><span class="stop">환불</span></li>';
				}
			}
			
			str += '<li>' + item.title + '</li>';
			str += '<li>작가명 : ' + item.artist_name + '</li>';
			str += '</ul>';
			str += '</div>';
			str += '<div class="ocash">';
			str += '<ul>';
			str += '<li><em class="coin">c</em>' + NumberWithComma(item.coin_price) + '</li>';
			str += '<li><em class="krw">w</em>' + NumberWithComma(item.cash_price) + '</li>';
			str += '</ul>';
			str += '</div>';
			str += '<div class="oseller">';
			str += '<dl>';
			str += '<dt>판매자  :</dt>';
			str += '<dd><strong>' + item.name + '</strong>(' + item.mobile_number + ')<a href="tel:' + item.mobile_number + '">문의하기</a></dd>';
			str += '</dl>';
			str += '</div>';
			str += '<div class="obtn kr">';
			if(item.order_cancel === 0){
				if(item.order_state === 0){
					str += '<a href="#popupcux" class="yellow buy_cancel_modal" style="width:100%">주문취소</a>';
				}else if(item.order_state === 1){
					str += '<a href="#popupcux" class="yellow buy_refund_modal" style="width:100%">환불신청</a>';
				}else if(item.order_state === 2){
					str += '<a href="#popupcux1" class="darkgray view_delivery" style="width:100%">배송내역보기</a>';
				}else if(item.order_state === 3){
					str += '<a class="yellow order_complete" style="width:50%">주문확정</a>';
					str += '<a href="#popupcux" class="gray buy_refund_modal" style="width:50%">환불신청</a>';
				}else if(item.order_state === 4){
					str += '<a class="darkgray" style="width:100%">취소/환불 완료</a>';
				}else if(item.order_state === 5){
					str += '<a  class="darkgray" style="width:100%">주문 완료</a>';
				} 
			}else if(item.order_cancel === 1){
				str += '<a class="darkgray" style="width:100%">취소 완료</a>';
			}else{
				if(item.order_state !== 4){
					str += '<a class="darkgray" style="width:100%">환불 요청중</a>';
				}else{
					str += '<a class="darkgray" style="width:100%">환불 완료</a>';
				}
			}
			str += '</div>';
			str += '</div>';
			str += '</div>';

			var instance = $(str);

			instance.find('.buy_cancel_modal, .buy_refund_modal')
				.click(function() {
					var modal = $("#popupcux .cux_modal_dialog");
					modal.find('.cux_title').text('취소·환불 사유작성');
					modal.find('.cux_info').text('취소·환불 사유');
					modal.find('.cux_button').text('작성').attr('onclick', 'mobile_buy_cancel(' + item.id + ')');
				})
				.leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
			
			instance.find('.order_complete')
				.click(function() {
					mobile_order_complete(item.id);
				});

			instance.find('.view_delivery')
				.click(function() {
					var modal = $("#popupcux1 .cux_modal_dialog");
					modal.find('.cux_title').text('배송내역');
					modal.find('.cux_info').text('');
					modal.find('.cux_button').text('확인').attr('onclick', '$("#lean-overlay").trigger("click");');
					modal.find('.can-reset').text('');
					mobile_view_delivery(item.id);
				})
				.leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
				
			list.append(instance);
        });
        
		itemCount = list.children().length;
		
		if(data.buy_cnt <= itemCount){
			$("#mybuy_more_btn").css('display','none');
		}

		$('#mypage_load').hide();
		
    }).always(function() {
        list.data("isLoading", false);
    });
}

function mobile_buy_cancel(order_id){
	var cancel_reason = $('input[name=modal-text]').val();
	
	var list = $("#mybuy-list");
	
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: "/mypage/mobile/mypage_buy_cancel",
        type: "POST",
        data: { _token: CSRF_TOKEN, order_id : order_id, cancel_reason : cancel_reason},
        dataType: "JSON"
    }).done(function(data) {
    
    	list.empty();
		mobile_mypage_buy_list(1,0);
		if(data.messages === 'cancel_success'){
			$("#lean-overlay").trigger("click");
			alert('해당주문을 취소하셨습니다.');
		}else if(data.messages === 'refund_success'){
			$("#lean-overlay").trigger("click");
			alert('해당주문의 환불요청이 되었습니다.');
		}else{
			$("#lean-overlay").trigger("click");
			alert('일시적인 오류로 실패하셨습니다. 잠시 후 다시 시도해 주세요.');
		}
    });
    
}

function mobile_order_complete(order_id){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    if(confirm('주문을 확정하시겠습니까?')){
        $.ajax({
            url : '/order/confirm',
            type : 'POST',
            data : { _token : CSRF_TOKEN, order_id : order_id},
			dataType : 'JSON',
			async: false
        }).done(function(data) {
        	var list = $("#mybuy-list");
        	list.empty();
			mobile_mypage_buy_list(1,0);
		});
    }
}

function mobile_view_delivery(order_id) {
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url : '/mypage/order/view_delivery',
		type : 'POST',
		data : { _token : CSRF_TOKEN, order_id : order_id},
		dataType : 'JSON',
		success : function(data) {
			var modal = $("#popupcux1 .cux_modal_dialog");
			modal.find('.modal_view_order_id').text(data.order_id);
			modal.find('.modal_delivery_company').text(data.delivery_company);
			modal.find('.modal_send_post_num').text(data.send_post_num);
		}
	});
}

function mobile_mypage_sell_list(date_term, search_yn) {
	var list = $("#mysell-list");
	
	if(search_yn === 1){
		list.empty();
		$("#mysell_product_more_btn").css('display','block');
	}

	
    var itemCount = list.children().length;
    if (list.data("isLoading")) {
        return;
    }
    list.data("isLoading", true);

	if(date_term != 0){
		var today = moment().format('YYYY-MM-DD');
		var beforedate = moment().subtract(date_term,'days').format('YYYY-MM-DD');
		
		$('#mysell_from_date').val(beforedate);
		$('#mysell_to_date').val(today);
	}
	

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    
    var from_date = $('#mysell_from_date').val();
    var to_date = $('#mysell_to_date').val();
    var status = $('#mysell_status option:selected').val();
    
	var str;
	
	$('#mypage_load').show();

    $.ajax({
        url: "/mypage/mobile/mypage_sell_list",
        type: "POST",
        data: { _token: CSRF_TOKEN, start: itemCount, limit: 5, date_term: date_term, from_date: from_date, to_date: to_date, status: status},
        dataType: "JSON",
    }).done(function(data) {
		//console.log(data);
		
		$("#mysell_cnt").text(data.sell_cnt + "개");
        $("#mysell_request").text(data.sell_request_cnt);
        $("#mysell_ready").text(data.sell_ready_cnt);
        $("#mysell_ing").text(data.sell_ing_cnt);
        $("#mysell_end").text(data.sell_end_cnt);
        $("#mysell_cancel").text(data.sell_cancel_cnt);
        $("#mysell_finish").text(data.sell_finish_cnt);
		
		
		
        data.sells.forEach(function(item) {
			str = '<div>';
			str += '<div class="o_date kr">';
			str += '<span>주문번호 : <strong>' + pad(item.id,9) + '</strong></span>';
			str += '<em class="en">' + item.created_at + '</em>';
			str += '</div>';
			str += '<div class="obox">';
			str += '<div class="oinfo">';
			str += '<p><a href="/order/bil/' + item.id + '"><img src="/storage/image/product/' + item.image1 + '" alt=""/></a></p>';
			str += '<ul>';
			if(item.order_cancel === 0){
				if(item.order_state === 0){
					str += '<li><span class="ing">입금대기</span></li>';
				}else if(item.order_state === 1){
					str += '<li><span class="ing">입금확인</span></li>';
				}else if(item.order_state === 2){
					str += '<li><span class="ing">배송중</span></li>';
				}else if(item.order_state === 3){
					str += '<li><span class="ing">배송완료</span></li>';
				}else if(item.order_state === 4){
					str += '<li><span class="stop">환불처리</span></li>';
				}else if(item.order_state === 5){
					str += '<li><span class="stop">판매확정</span></li>';
				} 
			}else if(item.order_cancel === 1){
				str += '<li><span class="stop">주문취소</span></li>';
			}else{
				if(item.order_state !== 4){
					str += '<li><span class="ing">환불요청중</span></li>';
				}else{
					str += '<li><span class="stop">환불</span></li>';
				}
			}
			str += '<li>' + item.title + '</li>';
			str += '<li>작가명 : ' + item.artist_name + '</li>';
			str += '</ul>';
			str += '</div>';
			str += '<div class="ocash">';
			str += '<ul>';
			str += '<li><em class="coin">c</em>' + NumberWithComma(item.coin_price) + '</li>';
			str += '<li><em class="krw">w</em>' + NumberWithComma(item.cash_price) + '</li>';
			str += '</ul>';
			str += '</div>';
			str += '<div class="oseller">';
			str += '<dl>';
			str += '<dt>판매자  :</dt>';
			str += '<dd><strong>' + item.name + '</strong>(' + item.mobile_number + ')<a href="tel:' + item.mobile_number + '">문의하기</a></dd>';
			str += '</dl>';
			str += '</div>';
			str += '<div class="obtn kr">';
			if(item.order_cancel === 0){
				if(item.order_state === 0){
					str += '<a href="#popupcux" class="darkgray" style="width:100%">입금대기중</a>';
				}else if(item.order_state === 1){
					str += '<a href="#popupcux3" class="yellow insert_delivery" style="width:100%">작품발송</a>';
				}else if(item.order_state === 2){
					str += '<a href="#popupcux3" class="yellow view_delivery" style="width:100%">배송내역보기</a>';
				}else if(item.order_state === 3){
					str += '<a class="darkgray" style="width:100%">판매확정대기</a>';
				}else if(item.order_state === 4){
					str += '<a href="#popupcux2" class="darkgray view_cancel_reason" style="width:100%">사유보기</a>';
				}else if(item.order_state === 5){
					str += '<a class="darkgray" style="width:100%">판매확정</a>';
				} 
			}else if(item.order_cancel === 1){
				str += '<a class="darkgray" style="width:50%">주문취소중</a>';
				str += '<a href="#popupcux2" class="darkgray view_cancel_reason" style="width:50%">사유보기</a>';
			}else{
				if(item.order_state !== 4){
					str += '<a class="darkgray" style="width:100%">환불 요청중</a>';
				}else{
					str += '<a class="darkgray" style="width:100%">환불 완료</a>';
				}
			}
			str += '</div>';
			str += '</div>';
			str += '</div>';
			
			var instance = $(str);

			instance.find('.insert_delivery')
				.click(function() {
					var modal = $("#popupcux3 .cux_modal_dialog");
					modal.find('input[name=send_post_num]').val('');
					modal.find('.cux_button').text('확인').attr('onclick', 'mobile_insert_delivery(' + item.id + ');');
					mobile_delivery_company_list(modal.find('select[name=delivery_company]'));
				})
				.leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });

			instance.find('.view_delivery')
				.click(function() {
					var modal = $("#popupcux3 .cux_modal_dialog");
					modal.find('input[name=send_post_num]').val('');
					modal.find('select[name=delivery_company]').prop("selectedIndex", -1);
					modal.find('.cux_button').text('수정').attr('onclick', 'mobile_insert_delivery(' + item.id + ');');
					mobile_view_delivery_modify(item.id);
				})
				.leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
			
			instance.find('.view_cancel_reason')
				.click(function() {
					var modal = $("#popupcux2 .cux_modal_dialog");
					modal.find('.cux_title').text('취소·환불 사유보기');
					modal.find('.cux_info').text('취소·환불 사유');
					modal.find('.cux_button').text('확인').attr('onclick', '$("#lean-overlay").trigger("click");');
					modal.find('input[name=modal-text]').val('');
					mobile_view_cancel_reason(item.id);
				})
				.leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
			
			list.append(instance);
        });
        
		itemCount = list.children().length;
		
		if(data.sell_cnt <= itemCount){
			$("#mysell_more_btn").css('display','none');
		}
		$('#mypage_load').hide();
    }).always(function() {
        list.data("isLoading", false);
    });
}

function mobile_view_delivery_modify(order_id) {
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

	var request1 = $.ajax({
		url : '/mypage/mobile/delivery_company_list',
		type : 'POST',
		data : { _token : CSRF_TOKEN},
		dataType : 'JSON'
	});

	var request2 = $.ajax({
		url : '/mypage/order/view_delivery',
		type : 'POST',
		data : { _token : CSRF_TOKEN, order_id : order_id},
		dataType : 'JSON'
	});

	$.when(request1, request2).done(function(result1, result2){
		var data1 = result1[0];
		var data2 = result2[0];

		var options = [];
		data1["Company"].forEach(function(info){
			options.push('<option value="' + info.Code + '">' + info.Name + '</option>');
		});

		var modal = $("#popupcux3 .cux_modal_dialog");
		modal.find('select[name=delivery_company]').empty().append(options.join('')).val(data2.delivery_company_code);
		modal.find('input[name=send_post_num]').val(data2.send_post_num);
	})
}

function mobile_view_cancel_reason(order_id) {
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url : '/mypage/order/view_cancel_reason',
		type : 'POST',
		data : { _token : CSRF_TOKEN, order_id : order_id},
		dataType : 'JSON',
		success : function(data) {
			$('input[name=modal-text]').val(data.reason);
		}
	});
}

function mobile_delivery_company_list(selectElem) {
	if(selectElem.children().length !== 0) {
		return;
	}

	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url : '/mypage/mobile/delivery_company_list',
		type : 'POST',
		data : { _token : CSRF_TOKEN},
		dataType : 'JSON'
	}).done(function(data){
		selectElem.empty();
		data["Company"].forEach(function(info){
			selectElem.append('<option value="' + info.Code + '">' + info.Name + '</option>');
		});
	});
}

function mobile_insert_delivery(order_id) {
	var delivery_company = $('select[name=delivery_company]').val();
	var send_post_num = $('input[name=send_post_num]').val();

	if(!delivery_company || !send_post_num){
		alert('배송정보를 입력하셔야 합니다.');
		return;
	}

	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url : '/mypage/mobile/mypage_insert_delivery',
		type : 'POST',
		data : { _token : CSRF_TOKEN, order_id : order_id, delivery_company: delivery_company, send_post_num: send_post_num},
		dataType : 'JSON',
		async: false
	}).done(function(data){
		if(data === 'error') {
			alert('존재하지 않는 송장번호입니다. 택배사와 송장번호를 다시 확인해 주세요.');
		} else {
			alert('주문번호 ' + data + '의 배송정보 입력이 완료되었습니다.');
			var list = $("#mysell-list");
			$("#lean-overlay").trigger("click");
			list.empty();
			mobile_mypage_sell_list(0,0);
		}
	});
}

function mobile_mypage_comment_list() {
    var list = $("#mycomment-list");
    var itemCount = list.children().length;
    if (list.data("isLoading")) {
        return;
    }
    list.data("isLoading", true);
	$('#mypage_load').show();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: "/mypage/mobile/mypage_comment_list",
        type: "POST",
        data: { _token: CSRF_TOKEN, start: itemCount, limit: 5 },
        dataType: "JSON"
    })
        .done(function(data) {		
			$("#mycomment_comment_cnt").text(data.comment_cnt + "개");

            data.comments.forEach(function(item) {
                var template = $($("#mycomment-item").html())
                    .attr('id', 'mycomment-item-' + item.id);
                template
                    .find(".o_date kr strong").text(item.created_at || "");
                template
                    .find(".oinfo a")
                    .attr(
                        "href",
                        "/products/" + item.product.id
                    );
                template
                    .find(".oinfo img")
                    .attr(
                        "src",
                        "/storage/image/product/" + (item.product.image1 || "")
                    );
                template
                    .find(".item-category")
                    .text(item.product.category.ca_name || "");
                template.find(".item-title").text(item.product.title || "");
                template
                    .find(".item-artist-name")
                    .text(item.product.artist_name || "");
                template.find(".up-count").text(item.recomend);
                template.find(".down-count").text(item.unrecomend);
                template.find(".commentbox").text(item.review_body || "");
                template.find(".commm_modify").click(function() {
				var modal = $("#popupcux4 .cux_modal_dialog");
				$("#popupcux4 .cux_modal_dialog .footer_btn").html('<button type="button" id="batting_price_submit" class="cashgo" onclick="mobile_mypage_comment_update(' + item.id + ')">수정</button>');
				$('#popupcux4 textarea[name="review_body"]').text(item.review_body || "");
                }).leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
                template.find(".commm_delete").click(function() {
                    mobile_mypage_comment_delete(item.id);
                });
                template.appendTo("#mycomment-list");
			});
			
			var itemCount = list.children().length;
            if(itemCount >= data.comment_cnt) {
                $('#mycomment-show-more').hide();
            } else {
                $('#mycomment-show-more').show();
            }

			$('#mypage_load').hide();
        })
        .always(function() {
            list.data("isLoading", false);
        });
}

function mobile_mypage_comment_delete(id) {
    if (confirm("정말로 코멘트를 삭제하시겠습니까?")) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: "/mypage/mobile/mypage_comment_delete",
            type: "POST",
            data: { _token: CSRF_TOKEN, id: id },
            dataType: "JSON"
        })
            .done(function() {
                $('#mycomment-item-' + id).remove();
				$("#mycomment_comment_cnt").text($("#mycomment-list").children().length + '개');
                alert('코멘트 삭제 완료');
            })
    }
}

function mobile_mypage_comment_update(review_id){
	var list = $("#mycomment-list");
	
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var review_body = $('#popupcux4 textarea[name="review_body"]').val();
    $.ajax({
        url: '/mypage/mypage_comment_edit',
        type: 'POST',
        /* send the csrf-token and the input to the controller */
        data: {_token: CSRF_TOKEN, review_id:review_id, review_body:review_body },
        dataType: 'JSON',
        /* remind that 'data' is the response of the AjaxController */
        success: function (data) { 
            list.empty();
            mobile_mypage_comment_list();
            
            $('#review_body'+review_id).text(data.review_body);
            $('#treview_body'+review_id).text(data.review_body);
            $("#lean-overlay").trigger("click");
            
            
        }
    });
}

function mobile_mypage_expertreview_list() {
    var list = $("#my-expertreview-list");
    var itemCount = list.children().length;
    if (list.data("isLoading")) {
        return;
    }
    list.data("isLoading", true);
	$('#mypage_load').show();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: "/mypage/mobile/mypage_expertreview_list",
        type: "POST",
        data: { _token: CSRF_TOKEN, start: itemCount, limit: 5 },
        dataType: "JSON"
    })
        .done(function(data) {
			$("#my_expertreview_expertreview_cnt").text(data.expertreivew_cnt + "개");

            data.expertreivews.forEach(function(item) {
                var template = $($("#my-expertreview-item").html())
                    .attr('id', 'my-expertreview-item-' + item.id);
                template
                    .find(".o_date kr strong").text(item.created_at || "");
                template
                    .find(".oinfo a")
                    .attr(
                        "href",
                        "/products/" + item.product.id
                    );
                template
                    .find(".oinfo img")
                    .attr(
                        "src",
                        "/storage/image/product/" + (item.product.image1 || "")
                    );
                template
                    .find(".item-category")
                    .text(item.product.category.ca_name || "");
                template.find(".item-title").text(item.product.title || "");
                template
                    .find(".item-artist-name")
                    .text(item.product.artist_name || "");
                template.find(".commentbox").text(item.review_body || "");
                template.find(".commm_modify").click(function() {
				var modal = $("#popupcux5 .cux_modal_dialog");
					$("#popupcux5 .cux_modal_dialog .footer_btn").html('<button type="button" class="cashgo" onclick="mobile_mypage_expertreview_update(' + item.id + ')">수정</button>');
					$('#popupcux5 textarea[name="review_body"]').text(item.review_body || "");
                }).leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
                template.find(".commm_delete").click(function() {
                    mobile_mypage_expertreview_delete(item.id);
				});
				template.find('.columlist-star' + Number(item.rating).toString().replace('.', '')).show();
            	template.find('.columlist-star-num').text(item.rating);
                template.appendTo("#my-expertreview-list");
			});
			
			var itemCount = list.children().length;
            if(itemCount >= data.expertreivew_cnt) {
                $('#my-expertreview-show-more').hide();
            } else {
                $('#my-expertreview-show-more').show();
			}

			$('#mypage_load').hide();
        })
        .always(function() {
            list.data("isLoading", false);
        });
}

function mobile_mypage_expertreview_update(review_id){
	var list = $("#my-expertreview-list");
	
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var review_body = $('#popupcux5 textarea[name="review_body"]').val();
	var rating = $('#popupcux5 input[name=rating]').val();
    $.ajax({
        url: '/mypage/mobile/mypage_expertreview_edit',
        type: 'POST',
        /* send the csrf-token and the input to the controller */
        data: {_token: CSRF_TOKEN, review_id:review_id, review_body:review_body, rating: rating},
        dataType: 'JSON',
        /* remind that 'data' is the response of the AjaxController */
        success: function (data) { 
            list.empty();
            mobile_mypage_expertreview_list();
            
            $('#review_body'+review_id).text(data.review_body);
            $('#treview_body'+review_id).text(data.review_body);
            $("#lean-overlay").trigger("click");
        }
    });
}

function mobile_mypage_expertreview_delete(id) {
    if (confirm("정말로 전문가 리뷰를 삭제하시겠습니까?")) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: "/mypage/mobile/mypage_expertreview_delete",
            type: "POST",
            data: { _token: CSRF_TOKEN, id: id },
            dataType: "JSON"
        })
            .done(function() {
                $('#my-expertreview-item-' + id).remove();
				$("#my_expertreview_expertreview_cnt").text($("#my-expertreview-list").children().length + '개');
                alert('전문가 리뷰 삭제 완료');
            });
    }
}

$('.product_rating_star.starRev span').click(function () {
	$(this).parent().children('span').removeClass('on');
	$(this).addClass('on').prevAll('span').addClass('on');

	var rating = $(this).data('rating');

	$('.staron em.en').text(rating);
	$('input[name="rating"]').val(rating);
	$('#comment_modal .comodify ul li em').text(rating);
	return false;
});

$('#password_edit #password').keyup(function(){
    if($('#password_edit #password_confirm').val() != ''){
        if($(this).val()==$('#password_edit #password_confirm').val()){
            $('.correct_yn .correct').show();
            $('.correct_yn .uncorrect').hide();
        }else{
            $('.correct_yn .uncorrect').show();
            $('.correct_yn .correct').hide();
        }
    }
});

$('#password_edit #password_confirm').keyup(function(){
    if($('#password_edit #password').val() != ''){
        if($(this).val()==$('#password_edit #password').val()){
            $('.correct_yn .correct').show();
            $('.correct_yn .uncorrect').hide();
        }else{
            $('.correct_yn .uncorrect').show();
            $('.correct_yn .correct').hide();
        }
    }
});

function NumberWithComma(x) {
    var parts = x.toString().split(".");

    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

    return parts.join(".");

}

var check = false;

function CheckAll(){

    var chk = document.getElementsByName("del_unit[]");
    
    if(check == false){
    
        check = true;
        
        for(var i=0; i<chk.length;i++){                                                                    
        
            chk[i].checked = true;     //모두 체크
        
        }
    
    }else{
    
        check = false;
        
        for(var i=0; i<chk.length;i++){                                                                    
        
            chk[i].checked = false;     //모두 해제
        
        }
    
    }

}

function pad(number, length) {
   
    var str = '' + number;
    while (str.length < length) {
        str = '0' + str;
    }
   
    return str;

}