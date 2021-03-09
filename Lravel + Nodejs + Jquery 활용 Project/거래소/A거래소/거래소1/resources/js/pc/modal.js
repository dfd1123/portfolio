//   var modalLayer = $("#modal_layer");
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