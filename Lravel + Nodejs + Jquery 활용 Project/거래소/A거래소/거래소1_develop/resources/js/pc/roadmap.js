$(function(){

    $('.ex-roadmap-list').each(function(index){

        $(this).attr('data-index',index);

    }).click(function(){

        $(this).addClass('active');
        $('.ex-roadmap-list').not(this).removeClass('active');

        var i = $(this).attr('data-index');

        $('.ex-roadmap-desc-list[data-index='+i+']').fadeIn(700);
        $('.ex-roadmap-desc-list[data-index!='+i+']').hide();

        if ( i < 4 ){

            $('.map_year:first-child').addClass('active');
            $('.map_year:last-child').removeClass('active');

        }else{
            
            $('.map_year:first-child').removeClass('active');
            $('.map_year:last-child').addClass('active');

        }

    });

    $('.ex-roadmap-desc-list').each(function(index){

        $(this).attr('data-index',index);

    });

    $('.this_year').click(function(){
       $('.ex-roadmap-list').eq(0).click(); 
    });

    $('.next_year').click(function(){
        $('.ex-roadmap-list').eq(4).click(); 
    });

});