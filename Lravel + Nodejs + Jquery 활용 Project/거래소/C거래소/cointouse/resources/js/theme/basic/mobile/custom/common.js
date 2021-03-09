	//필터 (코인목록 검색)
	function filter(){
		
		var searchstr1 = $('#txtFilter').val().toUpperCase();
		var searchstr2 = $('#txtFilter').val().toLowerCase();
		if($('#txtFilter').val()=="")
			$("#coin_list_table tr").css('display','');			
		else{
			$("#coin_list_table tr").css('display','none');
			$("#coin_list_table tr[name*='"+searchstr1+"']").css('display','');
			$("#coin_list_table tr[name*='"+searchstr2+"']").css('display','');
		}
		return false;
	}
	
	/*코인목록 검색*/
	$('#txtFilter').keyup(function(){
		filter();
		return false;
	})
	
	$('#txtFilter').keypress(function(){
		if(event.keyCode==13){
			fileter();
			return false;
		}
	})
	/*//코인목록 검색*/

	//loading 등장
$(document).ready(function(){
    
	$('.posi_wrap').css('display', 'none');
    
});
//end