// popup btn
function popup_close(){
    $('.main-popup').removeClass('active');
}
function popup_open(){
    $('.main-popup').addClass('active');
}

$(function(){
    $('.customar-write-wrap ._date').kronos({
        format:'yyyy.mm.dd'
    });
})