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



    var fileTarget = $('.filebox .upload-hidden');

     fileTarget.on('change', function(){
         if(window.FileReader){
             // 파일명 추출
             var filename = $(this)[0].files[0].name;
         }

         else {
             // Old IE 파일명 추출
             var filename = $(this).val().split('/').pop().split('\\').pop();
         };

         $(this).siblings('.upload-name').val(filename);
     });

     //preview image
     var imgTarget = $('.preview-image .upload-hidden');

     imgTarget.on('change', function(){
         var parent = $(this).parent();
         parent.children('.upload-display').remove();

         if(window.FileReader){
             //image 파일만
             if (!$(this)[0].files[0].type.match(/image\//)) return;

             var reader = new FileReader();
             reader.onload = function(e){
                 var src = e.target.result;
                 parent.prepend('<div class="upload-display"><div class="upload-thumb-wrap"><img src="'+src+'" class="upload-thumb"></div></div>');
             }
             reader.readAsDataURL($(this)[0].files[0]);
         }

         else {
             $(this)[0].select();
             $(this)[0].blur();
             var imgSrc = document.selection.createRange().text;
             parent.prepend('<div class="upload-display"><div class="upload-thumb-wrap"><img class="upload-thumb"></div></div>');

             var img = $(this).siblings('.upload-display').find('img');
             img[0].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(enable='true',sizingMethod='scale',src=\""+imgSrc+"\")";
         }
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
    var utcs = {
        kr: 9,
        jp: 9,
        ch: 8,
        th: 7
    };
    return moment(date)
        .utc(utcs[locale] || 2)
        .format("MM-DD HH:mm:ss");
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
