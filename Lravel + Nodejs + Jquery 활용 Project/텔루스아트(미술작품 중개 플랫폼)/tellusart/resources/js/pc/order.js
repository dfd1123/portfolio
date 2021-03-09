//$(".mutong").hide();
$('input[name="payment_kind"]').change(function(){
    if($(this).val() == 0){
        $(".mutong").show();
        $(this).parent().addClass('active');
        $(this).parent().parent().siblings().children().removeClass('active');
    }else if($(this).val() == 10){
        $(".mutong").hide();
        $(this).parent().addClass('active');
        $(this).parent().parent().siblings().children().removeClass('active');
    }
});
$("#hide").click(function(){
    $("div").hide(200);
    $(this).parent().addClass('active');
    $(this).parent().siblings().children().removeClass('active');
});


$('#same_order').change(function(){
    if($(this).is(":checked")){
        $('input[name="user_name"]').val($('input[name="uname"]').val());
        $('input[name="user_mobile_number"]').val($('input[name="mobile_number"]').val());
        $('input[name="user_email"]').val($('input[name="uemail"]').val());
    }else{
        $('input[name="user_name"]').val('');
        $('input[name="user_mobile_number"]').val('');
        $('input[name="user_email"]').val('');
        
    }
    
});

$('.payment_kind_sel label input').change(function(){
    $(this).parent().addClass('active');
    $(this).parent().siblings().removeClass('active');
    if($(this).val()==0){
        $('.payment_kind_infor>div').hide();
        $('.payment_kind_infor .deposit_pay_wrap').show();
    }else if($(this).val()==10){
        $('.payment_kind_infor>div').hide();
        $('.payment_kind_infor .allcoin_pay_wrap').show();
    }
});

$('.bilkind_wrap input').change(function(){
    if($(this).val()==1){
        $('.bilkind2_wrap>div').hide();
        $('.otxt dl dd div.receipt_list ul').show();
        $('.bilkind2_wrap .personal_bil').show();
        $('.bilcard_number_wrap').hide();
        $('.business_bil').hide();
        $('.mobile_number_wrap').show();
        $('select[name="individual_kind"]').html('<option value="0" selected="selected">휴대폰번호</option><option value="1">현금영수증카드번호</option>');
    }else if($(this).val()==2){
        $('.bilkind2_wrap>div').hide();
        $('.otxt dl dd div.receipt_list ul').show();
        $('.bilkind2_wrap .business_bil').show();
        $('.bilcard_number_wrap').hide();
        $('.business_bil').show();
        $('.mobile_number_wrap').hide();
        $('select[name="individual_kind"]').html('<option value="2" selected="selected">사업자번호</option><option value="1">현금영수증카드번호</option>');
    }
    else if($(this).val()==0){
        $('.otxt dl dd div.receipt_list ul').hide();
    }else{
        $('.bilkind2_wrap>div').hide();
        
    }
});

$('select[name="individual_kind"]').change(function(){
    if($(this).val()==0){
        $('.bilcard_number_wrap').hide();
        $('.business_bil').hide();
        $('.mobile_number_wrap').show();
    }else if($(this).val()==1){
        $('.bilcard_number_wrap').show();
        $('.business_bil').hide();
        $('.mobile_number_wrap').hide();
    }else{
        $('.bilcard_number_wrap').hide();
        $('.business_bil').show();
        $('.mobile_number_wrap').hide();
    }
});

$('#order_target').submit(function(){
    if(!$('input[name="payment_kind"]:checked').val()) {
        alert("결제방식을 선택하지 않으셨습니다.");
        return false;
    }

    if(!$("input:checkbox[id='check1']").is(":checked")){
        alert("상품구매에 동의를 하지 않으셨습니다.");
        return false;
    }
    
    return true;
})