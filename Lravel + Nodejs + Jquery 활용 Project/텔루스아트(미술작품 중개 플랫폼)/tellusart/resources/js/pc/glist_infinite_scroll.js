
$(window).scroll(function() {
    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
      
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      var skeyword = $('#skeyword').val();
      var ca_id = $('.glist').data('caid');
      var offset = $('.glist').data('offset');
      var count = $('.glist').data('count');
      setTimeout(function() {
        offset = $('.glist').data('offset');
        if(offset != count){
            $('#product_load').show();
            $.ajax({
                url : '/sel_product/more',
                type : 'POST',
                /* send the csrf-token and the input to the controller */
                data : { _token : CSRF_TOKEN, offset : offset, count:count, skeyword : skeyword, ca_id : ca_id },
                dataType : 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success : function(data) {
                    //console.log(data[0].id);
                    $('.glist').data('offset',data.offset);
                    var elems = new Array;
                    var temp_elem;

                    $.each(data.products, function (index, product) { 
                        
                        temp_elem = glist_element(product.id ,product.ca_id, product.category.ca_name, product.image1, product.user.profile_img, product.title, product.artist_name, product.art_width_size, product.art_height_size, product.get_like, product.reviews.length, product.cash_price, product.coin_price);

                        elems.push(temp_elem);
                    
                    });
                    
                    var $grid = $('.glist').masonry({
                        itemSelector: '.item',
                        columnWidth: 5
                    });

                    var $elems = $(elems);
                    $grid.append( $elems ).masonry( 'appended', $elems );
                    
                    $grid.imagesLoaded()
                    .always( function( instance ) {
                        $('.glist .item a p').removeClass('is-loading');
                    }).progress( function() {
                        $grid.masonry('layout');
                        
                    });

                    $('#product_load').hide();
                    
                }
            });
        }
      }, 500);
    }
});