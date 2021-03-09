/* 거래소 페이지 고정 네비게이션 */
$('.m_tab_menu_con').each(function(index){
    $(this).attr('data-index',index);
});

$('.m_tab_menu ul li').each(function(index){
    $(this).attr('data-index',index);
}).click(function(){
	$(this).addClass('active');
	$('.m_tab_menu ul li').not(this).removeClass('active');
	
	var i = $(this).data('index');
	$('.m_tab_menu_con[data-index='+i+']').show();
	$('.m_tab_menu_con[data-index!='+i+']').hide();
	
	// 주문이랑 차트 아닌 부분에서 정보영역 숨김
	// if( i !== 0 && i !== 2 ){
	// 	$('#top_ticker').hide();
	// }else{
	// 	// 주문이랑 차트인 부분에서 정보영역 보임
	// 	$('#top_ticker').show();
	// }

});
/* //거래소 페이지 고정 네비게이션 */

/* 1.주문 매도-매수 탭 */
$('.deal_con').each(function(index){
    $(this).attr('data-index',index);
});

$('.trade_hd ul li').each(function(index){

    $(this).attr('data-index',index);

}).click(function(){

	$(this).addClass('active');
	$('.trade_hd ul li').not(this).removeClass('active');
	
	var i = $(this).data('index');
	$('.trade_wrap .right_con .deal_con[data-index='+i+']').show();
	$('.trade_wrap .right_con .deal_con[data-index!='+i+']').hide();

});
/* // 1.주문 매도-매수 탭 */

/* 5. 거래내역 대기주문-24시간 주문내역 start */
$('.trans_history_con .bottom_wrap').each(function(index){
    $(this).attr('data-index',index);
});
$('#trans_history_tab ul li').each(function(index){
    $(this).attr('data-index',index);
}).click(function(){
	
	$(this).addClass('active');
	$('#trans_history_tab ul li').not(this).removeClass('active');
	
	var i = $(this).data('index');
	$('.trans_history_con .bottom_wrap[data-index='+i+']').show();
	$('.trans_history_con .bottom_wrap[data-index!='+i+']').hide();
	
});
/* 5. 거래내역 대기주문-24시간 주문내역 end */

/* 2. 호가 누른 부분만 start */
$('#asking_price_tab ul li').click(function(){
	$(this).addClass('active');
	$('#asking_price_tab ul li').not(this).removeClass('active');
})
/* 2. 호가 누른 부분만 end */