$(window).scroll(function() {

    
	if ($(window).scrollTop() == $(document).height() - $(window).height()) {
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var status = $('.blist').data('status');
        var skeyword = $('#skeyword').val();
        var ca_id = $('.blist').data('caid');
        var offset = $('.blist').data('offset');
        var count = $('.blist').data('count');

        setTimeout(function() {
            offset = $('.blist').data('offset');
            if(offset != count){
                $('#product_load').show();
                $.ajax({
                    url : '/bat_product/more',
                    type : 'POST',
                    /* send the csrf-token and the input to the controller */
                    data : { _token : CSRF_TOKEN, offset : offset, count:count, skeyword : skeyword, ca_id : ca_id, status : status },
                    dataType : 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success : function(data) {
                        //console.log(data[0].id);
                        $('.blist').data('offset',data.offset);
                        var elems = new Array;
                        var temp_elem;

                        console.log(data.products);
                        
                        $.each(data.products, function (index, product) { 
                                
                            temp_elem = blist_element(product.id, product.ca_id, product.category.ca_name, product.image1, product.user.profile_img, product.title, product.artist_name, product.art_width_size, product.art_height_size, product.get_like, product.reviews.length, data.status, product.cash_price, product.coin_price, product.battings.length, data.login_yn);

                            elems.push(temp_elem);
                        
                        });

                        var $brid = $('.blist').masonry({
                            itemSelector: '.item',
                            columnWidth: 5
                        });
                        
                        var $elems = $(elems);
                        $brid.append( $elems ).masonry( 'appended', $elems );
                        
                        $brid.imagesLoaded()
                        .always( function( instance ) {
                            $('.blist .item a p').removeClass('is-loading');
                        }).progress( function() {
                            $brid.masonry('layout');
                            
                        });
                        $('#product_load').hide();
                    }
                });
            }
      }, 500);
	}
});