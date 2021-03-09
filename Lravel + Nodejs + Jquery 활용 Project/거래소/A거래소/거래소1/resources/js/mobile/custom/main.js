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
	
	/* 코인목록 탭버튼 */
	$('.market_list_tab li').click(function(){
		$(this).addClass('active');
		$('.market_list_tab li').not(this).removeClass('active');
	})
	/* //코인목록 탭버튼 */	