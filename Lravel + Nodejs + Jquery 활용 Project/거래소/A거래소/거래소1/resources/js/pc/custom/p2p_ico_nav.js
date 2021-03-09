/* p2p_ico 플로팅네비게이션 */
var floatPosition = parseInt($('#floating_nav').css('top'));

	$(window).scroll(function() {
		
		var scrollTop = $(window).scrollTop();
		var nowscrTop = $(this).scrollTop();
		var newPosition = scrollTop + floatPosition;

		if(nowscrTop < 60){
			
			$('#floating_nav').stop().animate({
				top: 0
			},500);
			
		}else{
			
			$('#floating_nav').stop().animate({
				top: newPosition-70
			}, 500);
			
		}

	}).scroll();
