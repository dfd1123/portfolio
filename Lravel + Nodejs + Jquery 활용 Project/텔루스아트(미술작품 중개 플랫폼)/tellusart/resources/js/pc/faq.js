$(".sub_notice").hide();
$("#navi .title").click(function () {
    if ( $(this).next().css("display") == "none" ) {
        $(".sub_notice").slideUp();
    };
    $(this).next().slideToggle();
});