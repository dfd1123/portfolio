$(document).ready(function () {
  $('.slick_items').slick({
    centerMode: true,
    centerPadding: '60px',
    slidesToShow: 5,
    autoplay: true,
    autoplaySpeed: 2000,
    responsive: [{
      breakpoint: 1300,
      settings: {
        arrows: false,
        centerMode: true,
        slidesToShow: 3
      }
    }, {
      breakpoint: 480,
      settings: {
        arrows: false,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 1
      }
    }]
  });
  $('.slick_team').slick({
    dots: false,
    infinite: false,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 4,
    arrows: true,
    responsive: [{
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true
      }
    }, {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    }, {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }]
  });
  $('.slick_news').slick({
    infinite: true,
    slidesToShow: 4,
    slidesToScroll: 3,
    arrows: true,
    responsive: [{
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true
      }
    }, {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    }, {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }]
  });
});
$(document).ready(function () {
  $(window).bind('scroll', function () {
    if ($(window).scrollTop() > 100) {
      $('.header').addClass('header_background');
    } else {
      $('.header').removeClass('header_background');
    }
  });
}); //   var modalLayer = $("#modal_layer");
//   var modalLink = $(".modal_link");
//   var modalCont = $(".modal_content");
//   var marginLeft = modalCont.outerWidth()/2;
//   var marginTop = modalCont.outerHeight()/2; 
//   modalLink.click(function(){
//     modalLayer.fadeIn("slow");
//     modalCont.css({"margin-top" : -marginTop, "margin-left" : -marginLeft});
//     $(this).blur();
//     $(".modal_content > a").focus(); 
//     return false;
//   });
//   $(".modal_content > button").click(function(){
//     modalLayer.fadeOut("slow");
//     modalLink.focus();
//   });
