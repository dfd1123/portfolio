
$('#wrap').scroll(function() {
    if ($('#wrap').scrollTop() == $('.sub-container').height() - $('#wrap').height() + 75) {
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      var skeyword = $('#skeyword').val();
      var ca_id = $('.glist').data('caid');
      var offset = $('.glist').data('offset');
      var count = $('.glist').data('count');
      
        if(offset != count){
            $('#product_load').show();
            $.ajax({
                url : '/search_product/more',
                type : 'POST',
                /* send the csrf-token and the input to the controller */
                data : { _token : CSRF_TOKEN, offset : offset, count:count, skeyword : skeyword, ca_id : ca_id },
                dataType : 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success : function(data) {
                    //console.log(data[0].id);
                    
                    var elems = new Array;
                    var temp_elem;

                    $.each(data.products, function (index, product) { 
                        
                        temp_elem = sblist_element(product.id ,product.ca_id, product.category.ca_name, product.image1, product.user.profile_img, product.title, product.artist_name, product.art_width_size, product.art_height_size, product.get_like, product.reviews.length, product.cash_price, product.coin_price);

                        elems.push(temp_elem);
                    
                    });

                    $('.glist').data('offset',data.offset);
                    
                    var $grid = $('.grid').masonry({
                        itemSelector: '.grid-item',
                        columnWidth: 5
                    });

                    var $elems = $(elems);
                    $grid.append( $elems ).masonry( 'appended', $elems );
                    
                    $grid.imagesLoaded()
                    .always( function( instance ) {
                        $('.grid .grid-item a p').removeClass('is-loading');
                    }).progress( function() {
                        $grid.masonry('layout');
                        
                    });

                    $('#product_load').hide();
                    
                }
            });
        }
    }
});