	/*공지사항 자동넘기기*/
	$('#ntc_next_btn').click(function(){
		$('.ntc_bar .ntc_ul').stop().animate({
			top: -18
		},function(){
			$('.ntc_bar .ntc_ul').css({
				top: 0
			}).find('li').first().appendTo('.ntc_bar .ntc_ul');
		})
	})
	
	var auto = setInterval(function(){
		$('#ntc_next_btn').trigger('click')
	},3000);
	/*//공지사항 자동넘기기*/

	/*이벤트 자동넘기기*/
	$('#event_next_btn').click(function(){
		$('.event_bar .event_ul').stop().animate({
			top: -18
		},function(){
			$('.event_bar .event_ul').css({
				top: 0
			}).find('li').first().appendTo('.event_bar .event_ul');
		})
	})
	
	var auto = setInterval(function(){
		$('#event_next_btn').trigger('click')
	},3000);
	/*//이벤트 자동넘기기*/
	
	/* 코인목록 탭버튼 */
	$('.market_list_tab li').click(function(){
		$(this).addClass('active');
		$('.market_list_tab li').not(this).removeClass('active');

		var kind = $(this).data('kind');

        if(kind == 'all'){
            $('.my_coin_list_con').removeClass('hide');
        }else{
            $('.my_coin_list_con').addClass('hide');
            $('.my_coin_list_con[data-kind="'+kind+'"]').removeClass('hide');
            $('.my_coin_list_con[data-kind="currency"]').removeClass('hide');
        }
	})
	/* //코인목록 탭버튼 */	