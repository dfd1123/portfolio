var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
var locale = $('meta[name="locale"]').attr('content');
var login_yn;

alertify.buy = alertify.extend("buy");
alertify.sell = alertify.extend("sell");

//document.addEventListener('touchstart', handler, {passive: true});

$(document).ready(function(){
    login_yn = IsLogin();
    $('.posi_wrap').css('display','none');

    alertify.set({ delay: 3000 });

    $('.banner_slide').show();

    $('.stop_user_id_warning').click(function(){
        swal({
            title: '계정 정지',
            text: '관리자에 의해 정지된 계정입니다.\n고객센터로 문의주시기 바랍니다.',
            icon: "warning",
            button: __.message.ok,
        });
    });
});


function IsLogin(){
    var result;

    $.ajax({
        url : "/check/login",
        type : "POST",
        async: false,
        data: {_token: CSRF_TOKEN},
        dataType: 'JSON',
        success : function(data) {
            result = data.login_yn;
            
        }
    });
    

    return result;
}


function dateSet(date){
    var new_date = new Date(moment(date).toISOString()); //추후 Moment Timezone으로 변경 요망

    if(locale == 'kr'){
        var month = pad(new_date.getMonth()+1, 2);
        var day = pad(new_date.getDate(), 2);
        var hour = pad(new_date.getHours() + 9, 2);
        var minute = pad(new_date.getMinutes(), 2);
        var second = pad(new_date.getSeconds(), 2);
    }else if(locale == 'jp'){
        var month = pad(new_date.getMonth()+1, 2);
        var day = pad(new_date.getDate(), 2);
        var hour = pad(new_date.getHours() + 9, 2);
        var minute = pad(new_date.getMinutes(), 2);
        var second = pad(new_date.getSeconds(), 2);
    }else if(locale == 'ch'){
        var month = pad(new_date.getMonth()+1, 2);
        var day = pad(new_date.getDate(), 2);
        var hour = pad(new_date.getHours() + 8, 2);
        var minute = pad(new_date.getMinutes(), 2);
        var second = pad(new_date.getSeconds(), 2);
    }else if (locale == 'th'){
        var month = pad(new_date.getMonth()+1, 2);
        var day = pad(new_date.getDate(), 2);
        var hour = pad(new_date.getHours() + 7, 2);
        var minute = pad(new_date.getMinutes(), 2);
        var second = pad(new_date.getSeconds(), 2);
    }else{
        var month = pad(new_date.getMonth()+1, 2);
        var day = pad(new_date.getDate(), 2);
        var hour = pad(new_date.getHours(), 2);
        var minute = pad(new_date.getMinutes(), 2);
        var second = pad(new_date.getSeconds(), 2);
    }

    

    return (month + "-" + day + " " + hour + ":" + minute + ":" + second);
}

function pad(n, width) {
    n = n + '';
    return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
}

//필터 (코인목록 검색)
function filter(){

    var searchstr1 = $('#txtFilter').val().toUpperCase();
    var searchstr2 = $('#txtFilter').val().toLowerCase();
    if($('#txtFilter').val()==""){
        $("table.target tr").css('display','');	
        $("ul.target li").css('display','');	
    }else{
        $("table.target tr").css('display','none');
        $("table.target tr[name*='"+searchstr1+"']").css('display','');
        $("table.target tr[name*='"+searchstr2+"']").css('display','');
        $("ul.target li").css('display','none');
        $("ul.target li[name*='"+searchstr1+"']").css('display','');
        $("ul.target li[name*='"+searchstr2+"']").css('display','');
    }
    return false;
}

/*코인목록 검색*/
$('#txtFilter').keyup(function(){
    filter();
    return false;
})

$('#txtFilter').keypress(function(){
    if(event.keyCode==13){
        filter();
        return false;
    }
})
/*//코인목록 검색*/

function selFilter(){
    var selvalue = $('select#selectFilter').val();
    
    if(selvalue == ''){
        $("table.target tr").css('display','');
        $("ul.target li").css('display','');
    }else{
        $("table.target tr").css('display','none');
        $("table.target tr:contains("+ selvalue +")").css('display','');
        $("ul.target li").css('display','none');
        $("ul.target li:contains("+ selvalue +")").css('display','');
    }
}

$('select#selectFilter').change(function(){
    selFilter();
    return false;
});

/**
 * 중복서브밋 방지
 * 
 * @returns {Boolean}
 */

var doubleSubmitFlag = false;
function doubleSubmitCheck(){
    if(doubleSubmitFlag){
        return doubleSubmitFlag;
    }else{
        doubleSubmitFlag = true;
        return false;
    }
}
