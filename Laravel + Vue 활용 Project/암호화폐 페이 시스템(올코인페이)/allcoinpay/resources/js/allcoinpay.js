$(document).ready(function(){
	
	
	//주소 보는거
	var click_num=0;
	
	$('.text_clip span').click(function(){
		
		if(click_num==0){
				
				$(this).siblings('.full_url_box').fadeIn();
				
				click_num++;		
				
		}else if(click_num==1){
			
				$('.full_url_box').fadeOut();
		
				click_num--;			
		
		}
		
		
	})
	
	$('.in_2nd_box').hide();
	$('.withdraw_panel ul li:first-child').addClass('active');
	
	$('.withdraw_panel ul li').eq(0).click(function(){
		$('.in_1st_box').show();
		$('.in_2nd_box').hide();
		$('.withdraw_panel ul li').removeClass('active');
		$(this).addClass('active');
	})

	$('.withdraw_panel ul li').eq(1).click(function(){
		$('.in_2nd_box').show();
		$('.in_1st_box').hide();
		$('.withdraw_panel ul li').removeClass('active');
		$(this).addClass('active');
	})
	
	var wh=$(window).height();
	
	$('.m_section_1').css({
		height: wh
	})
	
})
