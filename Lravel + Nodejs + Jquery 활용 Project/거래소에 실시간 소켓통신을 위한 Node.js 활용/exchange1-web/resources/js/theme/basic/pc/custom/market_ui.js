/*----------------거래소 js start----------------*/
	/*공지사항 자동넘기기*/
	$('#ntc_next_btn').click(function(){
		$('.ntc_bar .ntc_ul').stop().animate({
			top: -15
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
	
	/*공지사항 닫기버튼*/
	$('#ntc_x_btn').click(function(){
		$('.ntc_bar').toggleClass('active');
	})
	/*//공지사항 닫기버튼*/
	
	/*호가창 화살표*/
	$('#arrow_both_btn').click(function(){
		$('.trans_wrap .left_con .wait_wrap').css({ height: 355 });
	});
	
	$('#arrow_up_btn').click(function(){
		$('.trans_wrap .left_con .up_wrap').css({ height: 710 });
		$('.trans_wrap .left_con .down_wrap').css({ height: 0 });
	});
	
	$('#arrow_down_btn').click(function(){
		$('.trans_wrap .left_con .up_wrap').css({ height: 0 });
		$('.trans_wrap .left_con .down_wrap').css({ height: 710 });
	});
	/*//호가창 화살표*/
	
	/*거래기록 더보기 버튼*/
	$('#toggle_but').click(function(){
		$('.trans_wrap .right_con_inbox-2').toggleClass('active');
		$('.trans_wrap .right_con_inbox-1').toggleClass('active');
	})
	/*//거래기록 더보기 버튼*/
	
	/* 코인목록 탭버튼 */
	$('.market_list_tab li').click(function(){
		$(this).addClass('active');
		$('.market_list_tab li').not(this).removeClass('active');
	})
	/* //코인목록 탭버튼 */	
	
	/*반응형 1580 이하일 때, 구매 - 판매 둘중 하나 고르기*/
	$('#option_buy_btn').addClass('active');
	
	$('#option_sell_btn').click(function(){
		$(this).addClass('active');
		$('.option_li').not(this).removeClass('active');
		$('.trans_wrap .center_con .deal_wrap .deal_con.coin_buy_con').hide();
		$('.trans_wrap .center_con .deal_wrap .deal_con.coin_sell_con').show();
	})
	
	$('#option_buy_btn').click(function(){
		$(this).addClass('active');
		$('.option_li').not(this).removeClass('active');
		$('.trans_wrap .center_con .deal_wrap .deal_con.coin_buy_con').show();
		$('.trans_wrap .center_con .deal_wrap .deal_con.coin_sell_con').hide();
	})
	/*//반응형 1580 이하일 때, 구매 - 판매 둘중 하나 고르기*/
	/*----------------거래소 js end----------------*/