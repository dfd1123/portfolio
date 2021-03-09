$(document).ready(function(){
	
	
	/*모바일버전의 swiper*/
	var swiper = new Swiper('.swiper-container', {
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
	
	/*현재 윈도우창에 맞춘 높이 설정*/
	
	var wh = $(window).height();
	
	/*스크롤에 따라서 왼쪽 네비 표시*/
	$('.nav_wrap .middle_nav .main_nav .main_nav_ul li:nth-child(1)').addClass('active',500);
	
	$('.nav_wrap .middle_nav .main_nav .main_nav_ul li').each(function(index){
		
		$(this).attr('data-index',index);
		
	}).click(function(){
		
		var i = $(this).attr('data-index');
		wh = $(window).height();
	
		$('html,body').stop().animate({
			scrollTop:wh*i
		},800)
		
	})
	
	$(window).scroll(function(){
		
		sct = $(window).scrollTop();
		
		if(sct>=0 && sct<wh){
			
			$('.nav_wrap .middle_nav .main_nav .main_nav_ul li').eq(0).addClass('active',500);
			$('.nav_wrap .middle_nav .main_nav .main_nav_ul li').eq(1).css({
				boxShadow: '0 10px 25px rgba(57, 0, 112, 0.18)'
			}).removeClass('active');
			$('.nav_wrap .middle_nav .main_nav .main_nav_ul li').eq(2).css({
				boxShadow: '0 10px 25px rgba(57, 0, 112, 0.18)'
			}).removeClass('active');
			
		}else if(sct>=wh && sct<wh*2){
			
			$('.nav_wrap .middle_nav .main_nav .main_nav_ul li').eq(0).css({
				boxShadow: '0 -10px 25px rgba(57, 0, 112, 0.18)'
			}).removeClass('active');
			$('.nav_wrap .middle_nav .main_nav .main_nav_ul li').eq(1).addClass('active',500);
			$('.nav_wrap .middle_nav .main_nav .main_nav_ul li').eq(2).removeClass('active');
			
		}else if(sct>=wh*2 && sct<wh*3){
			
			$('.nav_wrap .middle_nav .main_nav .main_nav_ul li').eq(0).css({
				boxShadow: '0 -10px 25px rgba(57, 0, 112, 0.18)'
			}).removeClass('active');
			$('.nav_wrap .middle_nav .main_nav .main_nav_ul li').eq(1).css({
				boxShadow: '0 -10px 25px rgba(57, 0, 112, 0.18)'
			}).removeClass('active');
			$('.nav_wrap .middle_nav .main_nav .main_nav_ul li').eq(2).addClass('active',500); 
			
		}
		
	})
	
	
	/*section_1 넘기면 올코인페이 장점 나열*/
	$('.section_1 b.next_btn').click(function(){
		
		$('.slide_wrapper.next_advan').stop().animate({
			left: -100+'%'
		},1000)
		
	})
	
		//다시 돌아가기
	$('.back_btn_wrap').click(function(){
		
		$('.slide_wrapper.next_advan').stop().animate({
			left: 0
		},1000)
		
	})
		
	
	//온라인 결제시스템 보기버튼
	$('.tap_ver.online .con_box.order_1 .next_btn_wrap,.pc_ver .con_box.order_1 .next_btn_wrap').click(function(){
		
		$(this).parent().fadeOut(500);
		$(this).parent().next().delay(100).fadeIn(600);
		$('.moniter_view .view_darker').fadeIn(500);
		$('.allcoin_popup').fadeIn(500).css({
			animation: 'allcoin_popup_show 1s 1s linear forwards 1'
		})
		$('.phone_view_1_alert').fadeOut();
		$('.phone_view_1').attr({
			src: 'image/section_2/phone_view_2.svg'
		})
		
	})
	
	$('.tap_ver.online .con_box.order_2 .next_btn_wrap,.pc_ver .con_box.order_2 .next_btn_wrap').click(function(){
		
		$(this).parent().fadeOut(500);
		$(this).parent().next().delay(100).fadeIn(600);
		$('.online_hand').fadeIn(500).css({
			animation: 'online_hand_show .7s 1s linear forwards 1'
		})
		
		
	})

	$('.tap_ver.online .con_box.order_3 .next_btn_wrap,.pc_ver .con_box.order_3 .next_btn_wrap').click(function(){
		
		$(this).parent().fadeOut(500);
		$(this).parent().next().delay(100).fadeIn(600);
		$('.allcoin_popup, .online_hand').css({
			animation: 'show_hide .2s linear forwards 1'
		});
		$('.popup_end').delay(500).fadeIn();
		$('.phone_popup_end').delay(500).fadeIn();
		$('.phone_view .view_darker').fadeIn(500);
		
	})
	
	$('.tap_ver.online .con_box.order_4 .next_btn_wrap, .pc_ver .con_box.order_4 .next_btn_wrap').click(function(){
		
		$(this).parent().fadeOut(500);
		$(this).parent().siblings('.con_box.order_1').delay(100).fadeIn(600);
		$('.popup_end,.view_darker,.phone_popup_end').fadeOut();
		$('.phone_view_1').attr({
			src: 'image/section_2/phone_view_1.svg'
		})
		$('.phone_view_1_alert').fadeIn();
		
	})
	
	//오프라인 결제시스템 보기버튼
	$('.tap_ver.offline .con_box.order_1 .next_btn_wrap').click(function(){
		
		$(this).parent().fadeOut(500);
		$(this).parent().next().delay(100).fadeIn(600);
		$('.tap_offline_box_1').stop().animate({
			opacity: 0
		});
		$('.tap_offline_box_2').delay(100).fadeIn(500);
		
	})
	
	$('.tap_ver.offline .con_box.order_2 .next_btn_wrap').click(function(){
		
		$(this).parent().fadeOut(500);
		$(this).parent().siblings('.con_box.order_1').delay(100).fadeIn(600);
		$('.tap_offline_box_1').stop().animate({
			opacity: 1
		});
		$('.tap_offline_box_2').fadeOut(500);
		
	})
	
	/*온라인 결제시스템 or 오프라인 결제시스템 보기*/
	
	$('.online_or_offline.pc_ver li:nth-child(1),.online_or_offline.tap_ver p.online_btn').click(function(){
		
		$('.online_or_offline li:nth-child(2)').removeClass('active');
		$(this).addClass('active');
		$('.slide_wrapper.online_wrap').stop().animate({
			left: 0
		},1000)
		
	})
	
	$('.online_or_offline.pc_ver li:nth-child(2),.online_or_offline.tap_ver p.offline_btn').click(function(){
		
		$('.online_or_offline li:nth-child(1)').removeClass('active');
		$(this).addClass('active');
		$('.slide_wrapper.online_wrap').stop().animate({
			left: -100+'%'
		},1000)
		
	})
	
	//모바일 버전 높이
	$('.m_section_1').css({
		height: wh
	})
	
	
	//모바일 버전 네비버튼
	$('.m_nav p').click(function(){
		$(this).toggleClass('active',500);
		$('.trigger_box').slideToggle();
	})
	
	//모바일 버전 네비버튼 누를 때 스크롤 이동
	$('.m_nav h1').click(function(){
		
		$('html,body').animate({
			scrollTop:0
		})
		
		$('.trigger_box').slideUp();
		$('.m_nav p').removeClass('active',300);
		
	})
	
	$('.trigger_box .first_nav li').eq(0).click(function(){
		
		var first_move = $('#m_container .m_section_2').offset().top;
		
		$('html,body').animate({
			scrollTop: first_move
		})
		$('.trigger_box').slideUp();
		$('.m_nav p').removeClass('active',300);
		
	})
	
	$('.trigger_box .first_nav li').eq(1).click(function(){
		
		var second_move = $('#m_container .m_section_3').offset().top;
		
		$('html,body').animate({
			scrollTop: second_move
		})
		
		$('.trigger_box').slideUp();
		$('.m_nav p').removeClass('active',300);
		
	})
	
	$('.trigger_box .first_nav li').eq(2).click(function(){
		
		var third_move = $('#m_container .m_section_5').offset().top;
		
		$('html,body').animate({
			scrollTop: third_move
		})
		$('.trigger_box').slideUp();
		$('.m_nav p').removeClass('active',300);
		
	})

	
	/*창 크기 조절했을 때*/
	$(window).resize(function(){
		
	$('.nav_wrap .middle_nav .main_nav .main_nav_ul li').each(function(index){
		
		$(this).attr('data-index',index);
		
	}).click(function(){
		
		var i = $(this).attr('data-index');
		
		$('html,body').stop().animate({
			scrollTop:wh*i
		},800)
		
	})
	

		
	})
	
})