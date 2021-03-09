/*자동으로 넘겨지는 배너영역 슬라이드 스크립트입니당!*/
$('.autoplay').slick({
	slidesToShow : 4, //몇개가 보일지(현재 4개의 배너가 보이고있음)
	slidesToScroll : 1, //몇개씩 넘겨지는지 (현재 하나씩만 넘어가고있음)
	autoplay : true, //자동재생하기
	autoplaySpeed : 3000, //넘겨지는 속도입니당
	dots : true, //위에 동그라미 리스트들
	infinite : true, //계속 무한대로 돌아가는 걸 상징합니당
	responsive : [{//반응형에 따라서
		breakpoint : 1199, //해상도 가로길이가 1199px일때,
		settings : {
			slidesToShow : 3, //3개가 보이고
			slidesToScroll : 1
		}
	}, {
		breakpoint : 770, //해상도 가로길이가 770px일때
		settings : {
			slidesToShow : 2, //2개가 보임
			slidesToScroll : 1
		}
	}, {
		breakpoint : 580, //해상도 가로길이가 580px(모바일)일때
		settings : {
			slidesToShow : 1, //1개가 보임
			slidesToScroll : 1
		}
	}] //responsive
});
//slick

/*메인 공지사항 넘기기*/
$('.ntc_li .ntc_span').click(function(){
	slideNotice(this,0);
});

function slideNotice(x,y){
	
	var thisSlidegroup = $(x).next('.slide_group');
	var slideHeight = thisSlidegroup.children('.ntc_p').outerHeight();
	
	thisSlidegroup.stop().animate({
		top: -slideHeight
	},function(){
		thisSlidegroup.css({top:y}).find('.ntc_p').first().appendTo(thisSlidegroup);
	});
	
}

noticeTimer = setInterval(function(){
	$('.ntc_li .ntc_span').trigger('click');
},2000);


/* 메인 USDC마켓-BTC마켓-ETH마켓 탭메뉴 */
$('.main_chart_top_tab ul li').click(function(){
	$(this).addClass('active');
	$('.main_chart_top_tab ul li').not(this).removeClass('active');
})//end