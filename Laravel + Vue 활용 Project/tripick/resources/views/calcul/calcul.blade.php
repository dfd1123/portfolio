@extends('layouts.app')

@section('content')

<div class="wrapper wrapper--calcul">
    
    <div class="hd-title hd-title--01">
        <h2 class="hd-title__center">정산</h2>
    </div>

    <div class="category-tab" id="calcul_tab">
        <label for="matching_tab_01" class="category-tab__list is-active">정산지급요청</label>
        <label for="matching_tab_02" class="category-tab__list ">정산내역</label>
        <span class="category-tab__indicator"></span>
    </div>

    <div class="wrapper--calcul__area">
        <div class="calcul_container calcul_container--inquiry">
            <div class="calcul_card">

                <h3 class="calcul_card_tit">정산 요청 금액:</h3>
                <div class="calcul_card_amt">
                    <input type="text" value="0 원" disabled="" id="reserve-text">
                </div>
				<span class="calcul_card_caution">* 정산할 상품을 아래에서 체크해주세요<br/>(체크시, 요청금액에 표시됩니다)</span><br/>
                
                <div class="calcul_bank_act_line">
                    <span class="calcul_card_caution">* 입금계좌 : </span>
                    <div class="before_edit_act" id="final_account_info">
                        <!-- <b class="_bank">신한</b><em class="_act_num">110-078080030101</em>  -->
                    </div>
                </div>

                <span class="calcul_card_caution last">* 정산 가능 최소 금액은 200,000원 이상입니다.</span>
                <button type="button" class="button calcul_card_btn" id="appRsrvbtn">지급 요청</button>
            </div>
            <div class="posible-list">
            	<table class="calcul_history_tbl">
	                <thead class="calcul_history_thead">
	                    <tr>
	                    	<th> </th>
	                        <th>상품명</th>
	                        <th>금액</th>
	                    </tr>
	                </thead>
	                <tbody id="rsrv_bf_list">
	                	
	                </tbody>
	            </table>
            </div>

        </div>
        <div class="calcul_container calcul_container--history">
            <table class="calcul_history_tbl">
                <thead class="calcul_history_thead">
                    <tr>
                        <th>지급 요청일</th>
                        <th>지급 상태</th>
                        <th>금액</th>
                    </tr>
                </thead>
            </table>
            <div class="calcul_history_scrl">
                <table class="calcul_history_tbl">
                    <tbody class="calcul_history_tbody" id="rsrv_af_list">
                    	
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

@include('nav.nav_planner')

@endsection

@section('script')

<script>
	var start1 = 0;
	var start2 = 0;
	function rsrv_before_Load(){
		$.ajax({
			data: {'offset' : start1},
			type: 'GET',
			dataType: 'json',
			url: '/api/bokp/list'
		}).done(function(res){
			console.log(res);
			for(var i = 0; i < res.query.length; i++){
				var rsrv = res.query[i];
				var rsrvitem = '<tr>\
			                		<td>\
			                			<input type="checkbox" name="selectedReserve" value="'+rsrv.rsrv_price+'" data-id="'+rsrv.rsrv_id+'"/>\
			                		</td>\
			                		<td>'+rsrv.prd_name+'</td>\
			                		<td>'+rsrv.rsrv_price+' 원</td>\
			                	</tr>';
			    $('#rsrv_bf_list').append(rsrvitem);
			    start1++;
			}
			
		}).fail(function(xhr, status, errorThrown) {
            console.log(xhr);
    	});
	}
	function rsrv_after_Load1(){
		$.ajax({
			data: {'offset' : start2, 'state': 4},
			type: 'GET',
			dataType: 'json',
			url: '/api/bokp/list'
		}).done(function(res){
			console.log(res);
			for(var i = 0; i < res.query.length; i++){
				var rsrv = res.query[i];
				var rsrvitem = '<tr class="ing">\
			                		<td>'+rsrv.calc_req_at+'</td>\
			                		<td>요청</td>\
			                		<td class="amt_td">'+rsrv.rsrv_price+' 원</td>\
			                	</tr>';
			    $('#rsrv_af_list').append(rsrvitem);
			    start2++;
			}
			
		}).fail(function(xhr, status, errorThrown) {
            console.log(xhr);
    	});
	}
	function rsrv_after_Load2(){
		$.ajax({
			data: {'offset' : start2, 'state': 5},
			type: 'GET',
			dataType: 'json',
			url: '/api/bokp/list'
		}).done(function(res){
			console.log(res);
			for(var i = 0; i < res.query.length; i++){
				var rsrv = res.query[i];
				var rsrvitem = '<tr>\
			                		<td>'+rsrv.calc_req_at+'</td>\
			                		<td>완료</td>\
			                		<td class="amt_td">'+rsrv.rsrv_price+' 원</td>\
			                	</tr>';
			    $('#rsrv_af_list').append(rsrvitem);
			    start2++;
			}
			
		}).fail(function(xhr, status, errorThrown) {
            console.log(xhr);
    	});
	}
	rsrv_before_Load();
	rsrv_after_Load1();
	rsrv_after_Load2();
	
    $(function(){
    	
		$('[name=selectedReserve]').change(function(){
			var reservePrice = 0;
			var chkBox = $('[name=selectedReserve]');
		
			$(chkBox).each(function(){
				if($(this).prop('checked')){
					reservePrice+= parseInt($(this).val());
				}
			});
			$('#reserve-text').val(reservePrice+' 원');
		});
		
		$('#appRsrvbtn').click(function(){
			var checked = new Array();
			var chkBox = $('[name=selectedReserve]');
		
			$(chkBox).each(function(){
				if($(this).prop('checked')){
					checked.push($(this).attr('data-id'));
				}
			});
			if(checked.length==0){
				dialog.alert({
		            title:'알림',  
		            message: '정산할 상품이 없습니다',
		            button: "확인"
		        });
				return;
			}
			
			if(parseInt($('#reserve-text').val()) < 200000){
				dialog.alert({
		            title:'알림',  
		            message: '최소 정산금액을 맞춰주세요',
		            button: "확인"
		        });
				return;
			}
			$.ajax({
				data: {'req':'app', 'rsrvs' : checked},
				type: 'POST',
				dataType: 'json',
				url: '/api/bokp'
			}).done(function(res){
				console.log(res);
				if(res.state == 1 && res.query!= null){
					dialog.alert({
			            title:'알림',  
			            message: '해당 상품의 정산이 신청되었습니다',
			            button: "확인",
			            callback: function(value){
			                location.reload();
			            }
			        });
				}else if(res.state == 1 && res.query ==null){
					dialog.alert({
			            title:'알림',  
			            message: '정산 실패',
			            button: "확인"
			        });
				}else{
					dialog.alert({
			            title:'알림',  
			            message: '서버 통신 실패',
			            button: "확인"
			        });
				}
				
			}).fail(function(xhr, status, errorThrown) {
	            console.log(xhr);
	    	});
			
		});
        //카테고리 탭메뉴 움직임효과
        var indicatorWidth = $('.category-tab__list.is-active').innerWidth();

        $('.category-tab__indicator').css({
            width: indicatorWidth
        })

        function categoryIndicatorMove() {

            var indicatorposi = $('.category-tab__list.is-active').position();

            $(".category-tab__indicator").stop().css({
                left: indicatorposi.left
            })

        }

        $('.category-tab__list').click(function(){
            $(this).addClass('is-active');
            $(".category-tab__list")
                .not(this)
                .removeClass("is-active");
            categoryIndicatorMove();

        });
        //end

        $('.before_edit_act_btn').click(function(){

            $('.before_edit_act').css('display', 'none');
            $('.after_edit_act').css('display', 'flex');

        })

        $('#edit_confirm').click(function(){

            var bank_name = $('#edit_bank option:selected').val();
            var account_num = $('#edit_account').val();

            $('#final_account_info ._bank').html(bank_name);
            $('#final_account_info ._act_num').html(account_num);

            $('.before_edit_act').css('display', 'inline-block');
            $('.after_edit_act').css('display', 'none');

        })

        $('.calcul_container--inquiry').addClass('active');

        $('#calcul_tab .category-tab__list').each(function(index){
            $(this).attr('data-index',index);
        }).click(function(){

            var i = $(this).attr('data-index');

            $('.calcul_container[data-index='+i+']').addClass('active');
            $('.calcul_container[data-index!='+i+']').removeClass('active');

        })

        $('.calcul_container').each(function(index){
            $(this).attr('data-index',index);
        })

    })

</script>

<style lang="scss">
	.posible-list{
		margin-top:1em;
		background-color:#fff;
		font-size: 14px;
	    font-weight: 400;
	    color: #00244C;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.07);
	    border-radius: 5px;
    	padding: 1rem;
    	opacity:0;
	    -webkit-animation: cardUp 0.3s 1;
	    animation: cardUp 0.3s 1;
	    -webkit-animation-fill-mode: forwards;
	    animation-fill-mode: forwards;
	    -webkit-animation-timing-function: ease-in-out;
	    animation-timing-function: ease-in-out;
	}
	.posible-list table{
		width: 100%;
	}
	.posible-list table thead tr th:nth-child(1), .posible-list table tbody tr td:nth-child(1){
		width:15%;
		text-align:center;
	}
	.posible-list table thead tr th:nth-child(2), .posible-list table tbody tr td:nth-child(2){
		width:55%;
		text-align:center;
	}
	.posible-list table thead tr th:nth-child(3), .posible-list table tbody tr td:nth-child(3){
		width:30%;
		text-align:center;
	}
	.calcul_card_amt input:disabled{
		background-color:#fff;
	}
</style>
@endsection