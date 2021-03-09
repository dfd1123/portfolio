/* p2p 구매신청/판매신청 버튼누르면 모달팝업*/
$('.p2pApply').click(function(){
    
    $('.posi_wrap').css('display','table');
        
    $('#p2pApply1').animate({
        opacity: 1,
        top:0
    });
    
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var id = $(this).data('id');
    $.ajax({
        url : "/p2p_ajax_test",
        type : "POST",
        data : {_token : CSRF_TOKEN,id : id},
        dataType : "JSON"
     }).done(function(data) {
         console.log(data);
         if(data.type=='buy'){
            $("#modal_title").html(__.ptop.apply_sell); 
         }else{
            $("#modal_title").html(__.ptop.apply_buy); 
         }
        $("input[name='p_id']").val(data.id);
        $("input[name='type']").val(data.type);
        $('#pop_name').text(data.name);
        $('#coin_name').text(data.coin_symbol);
        $('.modal_coin_type').text(data.coin_type);
        $('#modal_amount').text(data.coin_amount);
        $('#modal_price').text(data.coin_price);
        $('#country_money').text(data.country_money);
        if(data.country_money=="JPY"){
            $('.if_jp').css('display','block');
        }else{
            $('.if_jp').css('display','none');
        }
        $('.posi_wrap').css('display','none');
     }).fail(function(){
        console.log("error");
     });
     
   
    
});
/* //p2p 구매신청/판매신청 모달팝업*/

/* p2p 장외거래등록 모달팝업*/
$('#p2pWrite').click(function(){
    $('.posi_wrap').css('display','');
    $('#p2pCreate').animate({
        opacity: 1,
        top:0
    });
    $('.posi_wrap').css('display','none');
});
/* // p2p 장외거래등록 모달팝업*/

/* 코인종류 선택시 수량의 코인이름 뜨기 */
$(document.body).on('change',"#selectBoxs",function (e) {
    //doStuff
    var optVal= $("#selectBoxs option:selected").val();
    $("#this_coin").val(optVal);
 });
/* //코인종류 선택시 수량의 코인이름 뜨기 */

$(document.body).on('change','select[name="country_money"]',function(e){
    var state =  $('select[name="country_money"] option:selected').val();
    if(state=='JPY'){
        console.log(state);
        $('.if_jp').css('display','block');
    }else{
        $('.if_jp').css('display','none'); 
    }
})

$('#p2p_history_more').click(function(){
    var offset = $('#p2p_history_wrap').data('offset');
    var count = $('#p2p_history_wrap').data('count');
    var from_date = $('input[name="from_date"]').val();
    var to_date = $('input[name="to_date"]').val();

    $.ajax({
        url : "/p2p/history/more",
        type : "POST",
        data : {_token : CSRF_TOKEN, offset: offset, from_date: from_date, to_date: to_date},
        dataType : "JSON"
    }).done(function(data) {
         $('#p2p_history_tbl tbody').append(data.str);
         $('#p2p_history_wrap').attr('data-offset',data.offset);

         if(count <= data.offset){
            $('#p2p_history_more').remove();
         }
    }).fail(function(){
        console.log("error");
    });
});


function more_p2p_list(type, auth){
    var offset = $('#p2p_list_con').data('offset');
    var count = $('#p2p_list_con').data('count');
    var category = $('#select_p2p_list').val();
    var srch = $('#p2p_list_srch').val();
    if(count <= offset){
        return false;
    }

    $('#txtFilter').val('');
    filter();
    $('#selectFilter').val('');
    selFilter();

    $.ajax({
        url : "/p2p/list/more",
        type : "POST",
        data : {_token : CSRF_TOKEN, offset: offset, category: category, srch: srch, type: type, auth: auth},
        dataType : "JSON"
    }).done(function(data) {
         $('#p2p_list_con').append(data.str);
         $('#p2p_list_con').data('offset',data.offset);
    }).fail(function(){
        console.log("error");
    });
}


$("#p2p_apply").submit(function(){
    var is_addr_c = "input[name='coin_address']";
    var is_bank = "input[name='bank']";
    var is_account = "input[name='account']";

    if($(is_addr_c).val() == ''){
        swal({
            text: __.message.is_coin_addr,
            icon: "warning",
            button: __.message.ok,
        });
        $(is_addr_c).focus();
        return false;
    }
    if($(is_bank).val() == ''){
        swal({
            text: __.message.is_bank,
            icon: "warning",
            button: __.message.ok,
        });
        $(is_bank).focus();
        return false;
    }
    if($(is_account).val() == ''){
        swal({
            text: __.message.is_account,
            icon: "warning",
            button: __.message.ok,
        });
        $(is_account).focus();
        return false;
    }
    return true;
})


$("#p2p_create").submit(function(){
    var is_sel_c = "select[name='coin_type']";
    var is_amt_c = "input[name='coin_amount']";
    var is_sel_m = "select[name='country_money']";
    var is_amt_m = "input[name='coin_price']";
    var is_addr_c = "input[name='wt_coin_address']";
    var is_bank = "input[name='wt_bank']";
    var is_account = "input[name='wt_account']";
    var is_cont = "textarea[name='wt_cont']";
    
    if($(is_sel_c).val() == ''){
        swal({
            text: __.message.is_sel_c,
            icon: "warning",
            button: __.message.ok,
        });
        $(is_sel_c).focus();
        return false;
    }

    if($(is_amt_c).val() == ''){
        swal({
            text: __.message.is_amt_c,
            icon: "warning",
            button: __.message.ok,
        });
        $(is_amt_c).focus();
        return false;
    }
    if($(is_sel_m).val() == ''){
        swal({
            text: __.message.is_sel_m,
            icon: "warning",
            button: __.message.ok,
        });
        $(is_sel_m).focus();
        return false;
    }
    if($(is_amt_m).val() == ''){
        swal({
            text: __.message.is_amt_m,
            icon: "warning",
            button: __.message.ok,
        });
        $(is_amt_m).focus();
        return false;
    }
    if($(is_addr_c).val() == ''){
        swal({
            text: __.message.is_coin_addr,
            icon: "warning",
            button: __.message.ok,
        });
        $(is_addr_c).focus();
        return false;
    }
    if($(is_bank).val() == ''){
        swal({
            text: __.message.is_bank,
            icon: "warning",
            button: __.message.ok,
        });
        $(is_bank).focus();
        return false;
    }
    if($(is_account).val() == ''){
        swal({
            text: __.message.is_account,
            icon: "warning",
            button: __.message.ok,
        });
        $(is_account).focus();
        return false;
    }
    if($(is_cont).val() == ''){
        swal({
            text: __.message.is_cont,
            icon: "warning",
            button: __.message.ok,
        });
        $(is_cont).focus();
        return false;
    }
    
    return true;
})


function confirm_okay(pr_id){
    swal({
        text: __.message.is_coin_in,
        icon: "warning",
        buttons: {
            yes: {
                text: __.message.yes,
                value: true,
            },
            no: {
                text: __.message.no,
                value: false,
            },
        },
    }).then((value) => {
        if(value){
            document.location.href='/p2p_confirm/'+pr_id;
        }
    });
}

function confirm_cancel(pr_id){
    swal({
        text: 'Are you sure you want to cancel?',
        icon: "warning",
        buttons: {
            yes: {
                text: __.message.yes,
                value: true,
            },
            no: {
                text: __.message.no,
                value: false,
            },
        },
    }).then((value) => {
        if(value){
            document.location.href='/p2p_canceled/'+pr_id;
        }
    });
}