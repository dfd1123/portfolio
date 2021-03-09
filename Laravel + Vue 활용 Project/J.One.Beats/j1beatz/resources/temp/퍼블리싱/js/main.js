$(document).ready(function(){
    
    //분위기 및 장르
    var mood = ['격렬','웅장한','긴장','몽환적인','신비','신나는','기쁜','행복','사랑스러운','귀여운','섹시','고독한','경건한','우울한','슬픈','불안한','무서운','차분한'];
    var genre = ['trap','boombap','old school','jazz','soul','r&b','pop','edm','reggae','rock'];

    for(i=0;i<mood.length;i++){

        $('#lnb_mood').append('<li><a href="#">'+mood[i]+'</a></li>');

    }

    for(i=0;i<genre.length;i++){

        $('#lnb_genre').append('<li><a href="#">'+genre[i]+'</a></li>');

    }//end


    //gnb1 navigation
    $('.gnb01-list').each(function(index){
        $(this).attr('data-index',index);
    });

    $('.gnb01-lnb-con').each(function(index){
        $(this).attr('data-index',index)
    });

    $('.gnb01-list').mouseenter(function(){

        var i =  $(this).data('index');
        $(this).addClass('active');
        $('.gnb01-list').not(this).removeClass('active');
        $('.gnb01-lnb-con[data-index='+i+']').stop().slideDown(); //hide
        $('.gnb01-lnb-con[data-index!='+i+']').stop().hide();
          
    })

    $('#gnb01').mouseleave(function(){

        $('.gnb01-lnb-con').stop().slideUp(); //show 
        $('.gnb01-list').removeClass('active');

    })
    //end

    //playbar control-button
    $('#playbar button').click(function(){

        $(this).toggleClass('active');
        
    })
    //end

    // jQuery Scroll Plugin 적용
    var headerScrollArea = $('.gnb02-lnb');
    var cartScrollArea = $('.tab-view-order_list');
    var commentScrollArea = $('.beat-info-comment-panel ._scroll-area');
    
    scrollPlugin(headerScrollArea, "minimal");
    scrollPlugin(cartScrollArea, "minimal-dark");
    scrollPlugin(commentScrollArea, "minimal-dark");

    function scrollPlugin(scrollArea, style) {

        scrollArea.mCustomScrollbar({
            theme : style, // 테마 적용
            mouseWheelPixels : 300, // 마우스휠 속도
            scrollInertia : 400 // 부드러운 스크롤 효과 적용
        });

    }
    //end

});