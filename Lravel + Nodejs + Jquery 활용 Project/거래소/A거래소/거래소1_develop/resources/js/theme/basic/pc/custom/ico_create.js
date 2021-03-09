/* 혜택 추가버튼 */

var bnf_i = 3;

$(document).ready(function(){
    var coin_p = $('#get_ico_coin_p').text();

    $('#up_btn').click(function(){
       var this_var = parseFloat($(this).siblings('input').val());
       var price = parseFloat(value_up_btn(this_var));
       $(this).siblings('input').val(price);
       $(this).siblings('input').attr('value',price);
       $('#change_price').text(parseFloat(price/coin_p).toFixed(8));
    });
    $('#down_btn').click(function(){
        if($(this).siblings('input').val()>0){
            var this_var = parseFloat($(this).siblings('input').val());
            var price = parseFloat(value_down_btn(this_var));
            $(this).siblings('input').val(price);
            $(this).siblings('input').attr('value',price);
            $('#change_price').text(parseFloat(price/coin_p).toFixed(8));

        }else{
            swal({
                text: __.message.is_over_zero,
                icon: "warning",
                button: __.message.ok,
            });
        }
    });
    
    $('#ico_total_buy').on("change",function(){
        $('#change_price').val(($('#ico_total_buy').val()/coin_p).toFixed(8));
        $('#change_price').text(($('#ico_total_buy').val()/coin_p).toFixed(8));
    });

    $('.not_active_btn').prop("onclick",null).off("click");
})

$('#bnf_plus_btn').click(function(){
    
    bnf_i++;
    
    var plusP = '<p class="form_align benefit_p">'+'<label class="tit"></label>'+
    '<input type="text" name="benefit[]" id="benefit_'+bnf_i+'" class="form-control" placeholder="'+__.icoo.benefits_msg_n+bnf_i+__.icoo.benefits_msg_v+'"></p>'
    
    var pHtml = $(' .form_align.benefit_p:last ');
    
    pHtml.after(plusP);
    
    if(bnf_i==10){
        
        $(this).hide();
        
    }
    
})

/* //혜택 추가버튼 */

/* 관련기사 및 인터뷰 추가버튼 */

var news_i = 3;

$('#news_plus_btn').click(function(){
    
    news_i++;
    
    var plusTr =  '<tr class="subject_form">'+
    '<th>'+__.icoo.Arti_inter+news_i+'</th>'+
    '<td><input type="text" name="news_name[]" id="subject_'+news_i+'" class="form-control" placeholder="'+__.icoo.inter_title+'"></td>'+
    '</tr>'+
    '<tr class="url_form" name="newsline">'+
    '<th></th>'+
    '<td><input type="text" name="news_url[]" id="url_'+news_i+'" class="form-control" placeholder="URL '+__.icoo.aff+'"></td>'+
    '</tr>';
    
    var trHtml = $( "tr[name=newsline]:last" );
    
    trHtml.after(plusTr);
    
    if(news_i==10){
        
        $(this).hide();
        
    }
    
});

/* //관련기사 및 인터뷰 추가버튼 */

var tp_title = document.getElementById('tp_tit');
/* 제목 입력 Byte 수 제한 */
if(tp_title){
    tp_title.addEventListener('keyup', checkByte);

    var countSpan = document.getElementById('pre_char');
    var message = '';
    var MAX_MESSAGE_BYTE = 40;
    document.getElementById('max_char').innerHTML = MAX_MESSAGE_BYTE.toString();

    function count(message) {
        var totalByte = 0;

        for (var index = 0, length = message.length; index < length; index++) {
            var currentByte = message.charCodeAt(index);
            (currentByte > 128) ? totalByte += 2 : totalByte++;
        }
        return totalByte;
    }

    function checkByte(event) {
        const totalByte = count(event.target.value);

        if (totalByte <= MAX_MESSAGE_BYTE) {
            countSpan.innerText = totalByte.toString();
            message = event.target.value;
        } else {
            swal({
                text: MAX_MESSAGE_BYTE + __.message.is_available_byte,
                icon: "warning",
                button: __.message.ok,
            });
            countSpan.innerText = count(message).toString();
            event.target.value = message;
        }
    }
}
/* 제목 입력 Byte 수 제한 */


/* ICO소개 입력 Byte 수 제한 */
var ico_intro = document.getElementById('ico_intro');
if(ico_intro){
    ico_intro.addEventListener('keyup', ico_checkByte);
    var ico_countSpan = document.getElementById('ico_pre');
    var ico_message = '';
    var ICO_MAX_MESSAGE_BYTE = 100;
    document.getElementById('ico_max').innerHTML = ICO_MAX_MESSAGE_BYTE.toString();

    function count(ico_message) {
        var ico_totalByte = 0;

        for (var index = 0, length = ico_message.length; index < length; index++) {
            var currentByte = ico_message.charCodeAt(index);
            (currentByte > 128) ? ico_totalByte += 2 : ico_totalByte++;
        }
        return ico_totalByte;
    }

    function ico_checkByte(ico_event) {
        const ico_totalByte = count(ico_event.target.value);

        if (ico_totalByte <= ICO_MAX_MESSAGE_BYTE) {
            ico_countSpan.innerText = ico_totalByte.toString();
            ico_message = ico_event.target.value;
        } else {
            swal({
                text: ICO_MAX_MESSAGE_BYTE + __.message.is_available_byte,
                icon: "warning",
                button: __.message.ok,
            })
            ico_countSpan.innerText = count(ico_message).toString();
            ico_event.target.value = ico_message;
        }
    }
}

/* input box 작성 시 코인가격 변경 */
$('#ico_coin_p').on("change",function(){
    $('#c_price').val($('#ico_coin_p').val());
    $('#c_price').text($('#ico_coin_p').val().toUpperCase());
});
/**/

/*  input box 작성 시 코인명 변경 */
$('#coin_symbol').on("change",function(){
    $('.this_symbol').val($('#coin_symbol').val());
    $('.this_symbol').text($('#coin_symbol').val().toUpperCase());
});
/*  input box 작성 시 코인명 변경 */


/* select 시 코인명 변경 */
$('#collect_coin').on("change",function(){
    $('.collect_symbol').val($('#collect_coin').val());
    $('.collect_symbol').text($('#collect_coin').val());
})
/* select 시 코인명 변경 */

/* 파일명 추출 */
$('.only_pdf').change(function(){
    if($(this).hasClass('pdf_up')){
        var $check = $(this);
        var thumbext = this.value.slice(this.value.indexOf(".")+1).toLowerCase();
        if(thumbext != "pdf"){ //확장자를 확인합니다.
            swal({
                text: __.message.is_pdf,
                icon: "warning",
                button: __.message.ok,
            });
            $check.val('');
            return false;
        }
    
    }else if($(this).hasClass('img_up')){
            
        var $check = $(this);
        var thumbext = this.value.slice(this.value.indexOf(".")+1).toLowerCase();
        if(thumbext != "jpg" && thumbext != "png" ){ //확장자를 확인합니다.
            swal({
                text: __.message.is_img,
                icon: "warning",
                button: __.message.ok,
            });
            $check.val('');
            return false;
        }
            
    }else{
        swal({
            text: __.message.is_wrong,
            icon: "warning",
            button: __.message.ok,
        });
    }
    FileName(this, 0);
    
})

function FileName(x,y){
    
    if(window.FileReader){ // modern browser 
        
        var thisVal = $(x)[y].files[y].name; 
        
    }else { // old IE 
        
        var thisVal = $(x).val().split('/').pop().split('\\').pop(); // 파일명만 추출 
        
    }
    
    $(x).siblings('.filename_input').val(thisVal);
    
}

/* 시간설정 플러그인 */

$('.start_timepicker').timepicker({
    timeFormat: 'h:mm p',
    interval: 60,
    minTime: '0',
    maxTime: '11:00pm',
    defaultTime: '09',
    startTime: '10:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});

$('.end_timepicker').timepicker({
    timeFormat: 'h:mm p',
    interval: 60,
    minTime: '0',
    maxTime: '11:00pm',
    defaultTime: '00',
    startTime: '10:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});

/* //시간설정 플러그인 */
$('#create_form').submit(function(){
    //e.preventDefault();

    return func();
});

function func(){
    
    var obj = document.allform;
    
    /**/
    console.log(obj);

    if(obj.title.value == ''){
        swal({
            text: __.message.is_title,
            icon: "warning",
            button: __.message.ok,
        });
        obj.title.focus();
        return false;
    }
    if(obj.thumnail.value == ''){
        swal({
            text: __.message.is_thumnail,
            icon: "warning",
            button: __.message.ok,
        });
        obj.file_name.focus();
        return false;
    }
    if(obj.intro.value == ''){
        swal({
            text: __.message.is_intro,
            icon: "warning",
            button: __.message.ok,
        });
        obj.intro.focus();
        return false;
    }
    if(obj.coin_name.value == ''){
        swal({
            text: __.message.is_coin_name,
            icon: "warning",
            button: __.message.ok,
        });
        obj.coin_name.focus();
        return false;
    }
    if(obj.symbol.value == ''){
        swal({
            text: __.message.is_coin_symbol,
            icon: "warning",
            button: __.message.ok,
        });
        obj.symbol.focus();
        return false;
    }
    if(obj.collect.value == ''){
        swal({
            text: __.message.is_coin_collect,
            icon: "warning",
            button: __.message.ok,
        });
        obj.collect.focus();
        return false;
    }
    if(obj.ico_coin_p.value == ''){
        swal({
            text: __.message.is_coin_price,
            icon: "warning",
            button: __.message.ok,
        });
        obj.ico_coin_p.focus();
        return false;
    }
    if(obj.ico_min.value == ''){
        swal({
            text: __.message.is_coin_min,
            icon: "warning",
            button: __.message.ok,
        });
        obj.ico_min.focus();
        return false;
    }
    if(obj.ico_goal.value == ''){
        swal({
            text: __.message.is_goal_price,
            icon: "warning",
            button: __.message.ok,
        });
        obj.ico_goal.focus();
        return false;
    }
    if(obj.ico_from.value == '' || obj.ico_from_h.value == ''){
        swal({
            text: __.message.is_ico_from,
            icon: "warning",
            button: __.message.ok,
        });
        obj.ico_from.focus();
        return false;
    }
    if(obj.ico_to.value == '' || obj.ico_to_h.value == ''){
        swal({
            text: __.message.is_ico_to,
            icon: "warning",
            button: __.message.ok,
        });
        obj.ico_to.focus();
        return false;
    }

    if(obj.ico_use.value == ''){
        swal({
            text: __.message.is_ico_use,
            icon: "warning",
            button: __.message.ok,
        });
        obj.ico_use.focus();
        return false;
    }

    if(obj.ico_tech.value == ''){
        swal({
            text: __.message.is_ico_tech,
            icon: "warning",
            button: __.message.ok,
        });
        obj.ico_tech.focus();
        return false;
    }
    
    if(obj.ico_url.value == ''){
        swal({
            text: __.message.is_ico_url,
            icon: "warning",
            button: __.message.ok,
        });
        obj.ico_url.focus();
        return false;
    }
   
    return true;
}




//START 내 자산 > 거래내역 데이터 검색 pikaday plugin
var ico_startDate,
ico_endDate,
updateStartDate = function() {
    ico_startPicker.setStartRange(ico_startDate);
    ico_endPicker.setStartRange(ico_startDate);
    ico_endPicker.setMinDate(ico_startDate);
},
updateEndDate = function() {
    ico_startPicker.setEndRange(ico_endDate);
    ico_startPicker.setMaxDate(ico_endDate);
    ico_endPicker.setEndRange(ico_endDate);
},
ico_startPicker = new Pikaday({
    field: document.getElementById('start_sch_ico'),
    minDate: new Date(),
    maxDate: new Date(2020, 1, 1),
    onSelect: function() {
        ico_startDate = this.getDate();
        updateStartDate();
    },
    format: 'YYYY-MM-D',
    toString(date, format) {
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();
        return `${year}-${month}-${day}`;
    }
    
}),
ico_endPicker = new Pikaday({
    field: document.getElementById('end_sch_ico'),
    minDate: new Date(),
    maxDate: new Date(2020, 1, 1),
    onSelect: function() {
        ico_endDate = this.getDate();
        updateEndDate();
    },
    format: 'YYYY-MM-D',
    toString(date, format) {
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();
        return `${year}-${month}-${day}`;
    }
    
}),
_ico_startDate = ico_startPicker.getDate(),
_ico_endDate = ico_endPicker.getDate();

if (_ico_startDate) {
    ico_startDate = _ico_startDate;
    updateStartDate();
}

if (_ico_endDate) {
    ico_endDate = _ico_endDate;
    updateEndDate();
}

/*
$('#create_form').submit(function(e){
    e.preventDefault();

    return func();
});

function func(){
*/


function func_buy(){
    var obj = document.ico_buy;
    var check1 = $('.grayCheckbox:checked').length;
    //첫번째 변수에 동의했는지 확인
    

    
    
    
    var get_min_buy = $('#min_buy').text().split(' ');
    var get_buyer_remain = $('#my_all_asset').text().split(' ');
    var ico_seller_remain = parseFloat($('input[name="ico_remain"]').val());
    var ico_buyer_remain = parseFloat(get_buyer_remain[0]);
    var ico_total_buy = parseFloat($('#ico_total_buy').val());
    var min_buy = parseFloat(get_min_buy[0]);

    if(ico_total_buy == null){
        swal({
            text: __.message.is_total_buy,
            icon: "warning",
            button: __.message.ok,
        });
        $('#ico_total_buy').focus();
        return false; 
    }
    
    if(check1==0){
        swal({
            text: __.message.is_ico_checked,
            icon: "warning",
            button: __.message.ok,
        });
        obj.info_agree.focus();
        return false;
    }

    if(ico_total_buy=''){
        swal({
            text: __.message.is_wrong_buy0+'1',
            icon: "warning",
            button: __.message.ok,
        });
        return false; 
    }

    if(ico_total_buy > ico_buyer_remain){
        swal({
            text: __.message.is_wrong_buy0,
            icon: "warning",
            button: __.message.ok,
        });
        return false;
    }

    if(ico_seller_remain < ico_total_buy ){
        swal({
            text: __.message.is_wrong_buy1,
            icon: "warning",
            button: __.message.ok,
        });
        return false;
    }
    
    if(ico_total_buy < min_buy){
        swal({
            text: __.message.is_wrong_buy2,
            icon: "warning",
            button: __.message.ok,
        });
        return false;
    }

   
    
    $("#ico_buy_form").submit();
}


function value_up_btn(value) {
    return (parseFloat(value) + 0.01).toFixed(2);
}
function value_down_btn(value) {
    return (parseFloat(value) - 0.01).toFixed(2);
}


$('.info li button').click(function(){
    var coin_p = $('#get_ico_coin_p').text();
    var per = $(this).attr('id').split('_');
    //per[1];
    $('#is_percent').text(per[1]);
    var asset = $('#my_all_asset').text().split(' ');
    //asset[0];
    if(asset[0]!=0){
        console.log(asset[0]);
    var my_per = parseFloat(asset[0]) * parseFloat(0.01) * parseFloat(per[1]);
    $('#ico_total_buy').val(my_per);
    $('#ico_total_buy').attr('value',my_per);
    $('#change_price').text((my_per/coin_p).toFixed(8));
    }else{
        swal({
            text: __.message.is_no_money,
            icon: "warning",
            button: __.message.ok,
        });
        $('#is_percent').text(0);
    }
});

