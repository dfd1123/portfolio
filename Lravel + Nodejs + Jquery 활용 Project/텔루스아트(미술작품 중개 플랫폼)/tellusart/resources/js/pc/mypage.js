$('input[name="profile_img"]').change(function(){
    $('#profile_img_change').submit();
});

$('#cart_delete').submit(function(){
    $('input[name="delete_num"]').val(selectDelRow());
    return true;
});

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

$(function(){
	  
    $('.mypage_comment_contain .modaltrigger').leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
    
    $('.mypage_comment_contain .modaltrigger').click(function(){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var review_id = $(this).parent().attr('id');
      review_id = review_id.replace("commnet_","");
      
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
                 
                 $("#star_" + data.rating).addClass('active');
                 
                 $("#star_" + data.rating).parent().children('span').removeClass('on');
                 $("#star_"+data.rating).addClass('on').prevAll('span').addClass('on');
                 
                 $('.review_btn_wrap button.wbt').attr("onclick","review_delete("+review_id+")");
                 
                 $('.review_btn_wrap button.ylbt').attr("onclick","review_edit("+review_id+")");
                 
                 //console.log("star_"+data.rating);
          }
       }); 
    });
  });
  
  function mypage_review_edit(review_id){
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      var review_body = $('textarea[name="review_body"]').val();
      $.ajax({
             url: '/mypage/mypage_comment_edit',
             type: 'POST',
             /* send the csrf-token and the input to the controller */
             data: {_token: CSRF_TOKEN, review_id:review_id, review_body:review_body },
             dataType: 'JSON',
             /* remind that 'data' is the response of the AjaxController */
             success: function (data) { 
                 
                  $('#comment_modal').css("display","none");
                  $("#lean_overlay").css("display","none");
                 
                 $('#review_body'+review_id).text(data.review_body);
          }
       });
  }
  
  
  function mypage_review_delete(review_id){
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
                      location.href="/mypage/my_comment_list";
              }
           });
      }
  }


  $('button.order_cancel_btn').click(function(e){
	$('input[name="temp_order_id"]').val(e.target.dataset.id);
	
	$('#order_cancel_reason_modal').removeClass('hidden');
	setTimeout(function() {
		$('#order_cancel_reason_modal').addClass('active');
	}, 300);
});

$('.order_cancel_reason_submit').click(function(){
	$('input[name="order_id"]').val($('input[name="temp_order_id"]').val());
	$('input[name="cancel_reason"]').val($('input[name="temp_cancel_reason"]').val());
	
	$('#order_cancel_reason_modal').removeClass('hidden');
	setTimeout(function() {
		$('#order_cancel_reason_modal').addClass('active');
	}, 100);
	
	$('#order_cancel_reason_form').submit();
});



$('#order_cancel_reason_modal .jw_overlay, #order_cancel_reason_modal .jw_modal_hd>div').click(function(){
	$('#order_cancel_reason_modal').removeClass('active');
	
	setTimeout(function() {
		$('#order_cancel_reason_modal').addClass('hidden');
	}, 300);
});

function order_before_cancel(order_id) {
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	
	if(confirm('정말 주문을 취소 하시겠습니까?')){
		$.ajax({
			url : '/order/before_cancel',
			type : 'POST',
			/* send the csrf-token and the input to the controller */
			data : { _token : CSRF_TOKEN, order_id : order_id},
			dataType : 'JSON',
			/* remind that 'data' is the response of the AjaxController */
			success : function(data) {
				
			}
		});
	}
}

$('button.view_cancel_reason').click(function(e){

	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var order_id = e.target.dataset.id;

	$.ajax({
		url : '/mypage/order/view_cancel_reason',
		type : 'POST',
		/* send the csrf-token and the input to the controller */
		data : { _token : CSRF_TOKEN, order_id : order_id},
		dataType : 'JSON',
		/* remind that 'data' is the response of the AjaxController */
		success : function(data) {
			$('input[name="temp_cancel_reason"]').val(data.reason);
			$('#view_cancel_reason_modal').removeClass('hidden');
			setTimeout(function() {
				$('#view_cancel_reason_modal').addClass('active');
			}, 300);
		}
	});
});



$('#view_cancel_reason_modal .jw_overlay, #view_cancel_reason_modal .jw_modal_hd>div, #view_cancel_reason_modal input[type=button]').click(function(){
	$('#view_cancel_reason_modal').removeClass('active');
	
	setTimeout(function() {
		$('#view_cancel_reason_modal').addClass('hidden');
	}, 300);
});

$('button.insert_delivery').click(function(e){
	var order_id = e.target.dataset.id;
	
	$("#modal_order_id").val(order_id);
	
	$('#insert_delivery_modal').removeClass('hidden');
	setTimeout(function() {
		$('#insert_delivery_modal').addClass('active');
	}, 300);
});

$('#insert_delivery_modal .jw_overlay, #insert_delivery_modal .jw_modal_hd>div').click(function(){
	$('#insert_delivery_modal').removeClass('active');
	
	setTimeout(function() {
		$('#insert_delivery_modal').addClass('hidden');
	}, 300);
});
  
$('button.view_delivery').click(function(e){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var order_id = e.target.dataset.id;

	$.ajax({
		url : '/mypage/order/view_delivery',
		type : 'POST',
		/* send the csrf-token and the input to the controller */
		data : { _token : CSRF_TOKEN, order_id : order_id},
		dataType : 'JSON',
		/* remind that 'data' is the response of the AjaxController */
		success : function(data) {
			$('#modal_view_order_id').val(data.order_id);
			$('#modal_delivery_company').val(data.delivery_company_code);
			$('#modal_send_post_num').val(data.send_post_num);
			
			$('#view_delivery_modal').removeClass('hidden');
			setTimeout(function() {
				$('#view_delivery_modal').addClass('active');
			}, 300);
		}
	});
});

$('#view_delivery_modal .jw_overlay, #insert_delivery_modal .jw_modal_hd>div').click(function(){
	$('#view_delivery_modal').removeClass('active');
	
	setTimeout(function() {
		$('#view_delivery_modal').addClass('hidden');
	}, 300);
});

$('#view_delivery_modal .jw_overlay, #view_delivery_modal .jw_modal_hd>div, #view_delivery_modal input[type=button]').click(function () {
	$('#view_delivery_modal').removeClass('active');

	setTimeout(function () {
		$('#view_delivery_modal').addClass('hidden');
	}, 300);
});

$('#my_order_list input, #my_order_list select').change(function(){
    $('#my_order_list').submit();
});

$('#my_sale_list input, #my_sale_list select').change(function(){
    $('#my_sale_list').submit();
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

$('#password_change_form').submit(function(){
    if($('#password_edit #password').val() != $('#password_edit #password_confirm').val()){
        alert('비밀번호가 맞지 않습니다.\n 다시 확인 후 시도해주세요.');
        return false;
    }
});

$('.order_complete').click(function(){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var order_id = $(this).data('id');
    
    if(confirm('주문을 확정하시겠습니까?')){
        $.ajax({
            url : '/order/confirm',
            type : 'POST',
            /* send the csrf-token and the input to the controller */
            data : { _token : CSRF_TOKEN, order_id : order_id},
            dataType : 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success : function(data) {
                location.reload();
            }
        });
    }
});