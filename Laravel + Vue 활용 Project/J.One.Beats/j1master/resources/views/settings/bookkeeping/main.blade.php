@extends('header')
@section('content')

<div class="box box-inbox full-width full-height">
    <div class="box-title" style="padding:1em 0;">
    	<div class="title_text">
    		<p>수익 및 정산</p>
    	</div>
    	<div class="box-search" style="display:flex;">
            <input type="text" id="search_keyword" placeholder="프로듀서 명으로 검색 (2글자 이상)" name="Search" style="width:auto;flex:auto;">
            <button id="search_btn" style="margin:0 1em;"><img src="{{asset('vendor/images/icon/search.png')}}" alt=""></button>
        </div>
    </div>
    <div class="box-content" id="polist">
        @forelse($query as $item)
        <ul id="inbox-list" class="inbox-list">
            <li class="waves-effect">
                <div class="left">
                    <div class="info">
                        <p class="name">{{$item->prdc_nick}} (#{{$item->user_id}} {{$item->user_name}})</p>
                        <p class="user_info">판매번호: #{{$item->po_id}} ({{['0' => '신용카드', '1' => '무통장', '2' => '휴대폰'][$item->po_pg_type]}}결제)</p>
                        <p class="user_info">판매비트: {{$item->beat_title}} (#{{$item->beat_id}})</p>
                        <p class="user_info">구매자명: {{$item->buy_user_nick}} (#{{$item->buy_user_id}} {{$item->buy_user_name}})</p>
                        <p class="user_info">판매금액: {{number_format($item->beat_price)}}원</p>
                        <p class="user_info">수수료: {{number_format($item->fee)}}원</p>
                        <p class="user_info">정산금액: {{number_format($item->total)}}원</p>
                        <p class="user_info">계좌정보: {{$item->prdc_bnk_accnt}}</p>
                        <p class="reg_dt">신청일: {{date('Y-m-d H:i:s', strtotime($item->po_reg_dt))}}</p>
                        <p class="reg_dt">정산일: {{$item->po_cpl_dt ? date('Y-m-d H:i:s', strtotime($item->po_cpl_dt)) : ''}}</p>
                    </div>
                </div>
                <div class="right">
                    @if($item->po_state == 1)
                    <button class="active_btn" onclick="activebtn({{$item->po_id}}, {{$item->user_id}})">정산승인</button>
                    @elseif($item->po_state == 2)
                    <span>정산완료</span>
                    @endif
                </div>
                <div class="clearfix"></div>
            </li>
        </ul>
        @empty
        <ul id="inbox-list" class="inbox-list">
            <p>정산내역이 없습니다</p>
        </ul>
        @endforelse
    </div><!-- /.box-content -->
</div>

<script src="{{ asset('vendor/js/moment.min.js')}}"></script>
<script>
menuactive('settings');

$(window).load(function() {
    setTimeout(function() {
        $('.loader').hide();
    }, 100);
});
function activebtn(po_id, prdc_id){
    event.stopPropagation();
    var param = {
		'req' : 'state',
        'po_id' : po_id,
        'prdc_id' : prdc_id
	};
    $.ajax({
        type : "PUT",
        data : param,
        dataType : 'json',
        url : '/api/bookkeeping/state',
        success : function(data){
            console.log(data);
            if(data.state==1 && data.query !=null){
                alert('변경 되었습니다!');
                location.reload();
            }else{
                alert(data.msg);
            }
        },
        error : function(e){
            alert(e);
        }
    });
}

//검색기능 구현
$(document).on('keypress', '#search_keyword', function(e) {
    if (e.which == 13) {
        $('#search_btn').click();
    }
});

$('#search_btn').click(function(){
	if($('#search_keyword').val() == ''){
		alert('검색 키워드를 입력해주세요');
		return;
	}else if($('#search_keyword').val().length < 3){
		alert('글자수를 정확히 입력하세요\n(2글자 이상)');
		return;
	}
	bf_search = $('#search_keyword').val();
	$('#polist').html('');
	type = 1;
	start1 = 0;
	user_ajax();
});

var isAjaxRunning = false;
function user_ajax(){
    if(isAjaxRunning) {
        return;
    }
    isAjaxRunning = true;

	var param = '';
	var url = '';
	if(type==0){
		param = {
			'offset' : $('#polist').children().length,
		}
		url = '/api/bookkeeping/list';
	}else{
		param = {
			'offset' : start1,
			'prdc_nick': bf_search
		}
		url = '/api/bookkeeping/search';
	}
	$.ajax({
        type : "GET",
        data : param,
        dataType : 'json',
        url : url,
        success : function(data){

            if(data.state==1 && data.query !=null && data.query.length > 0){
                var items = data.query;
                var list = items.map(function(item){
                    var str = '';
                    str += '<ul id="inbox-list" class="inbox-list">';
                    str += '    <li class="waves-effect">';
                    str += '        <div class="left">';
                    str += '            <div class="info">';
                    str += '                <p class="name">'+item.prdc_nick+' (#'+item.user_id+' '+item.user_name+')</p>';
                    str += '                <p class="user_info">판매번호: #'+item.po_id+' ('+ {0: "신용카드", 1: "무통장", 2: "휴대폰"}[item.po_pg_type]+'결제)</p>';
                    str += '                <p class="user_info">판매비트: '+item.beat_title+' (#'+item.beat_id+')</p>';
                    str += '                <p class="user_info">구매자명: '+item.buy_user_nick+' (#'+item.buy_user_id+' '+item.buy_user_name+')</p>';
                    str += '                <p class="user_info">판매금액: '+item.beat_price.toLocaleString()+'원</p>';
                    str += '                <p class="user_info">수수료: '+item.fee.toLocaleString()+'원</p>';
                    str += '                <p class="user_info">정산금액: '+item.total+'원</p>';
                    str += '                <p class="user_info">계좌정보: '+(item.prdc_bnk_accnt || '')+'</p>';
                    str += '                <p class="reg_dt">신청일: '+moment(item.po_reg_dt).format("YYYY-MM-DD HH:mm:ss")+'</p>';
                    str += '                <p class="reg_dt">정산일: '+(item.po_cpl_dt ? moment(item.po_cpl_dt).format("YYYY-MM-DD HH:mm:ss") : '')+'</p>';
                    str += '            </div>';
                    str += '        </div>';
                    str += '        <div class="right">';
                    if(item.po_state === 1) {
                        str += '    <button class="active_btn" onclick="activebtn(' + item.po_id + ',' + item.user_id + ')">정산승인</button>';
                    } else if(item.po_state === 2) {
                        str += '    <span>정산완료</span>';
                    }
                    str += '        </div>';
                    str += '    </li>';
                    str += '</ul>';
                    return str;
                }).join('');

                $('#polist').append(list);
                
            }else if(data.state==1 && data.query.length == 0){
                if(type == 1) {
                    var str = '';
                    str += '<ul id="inbox-list" class="inbox-list">';
                    str += '    <p>일치하는 정산내역이 없습니다</p>';
                    str += '</ul>';

                    $('#polist').append(str);
                }
            }
            else{
                alert(data.msg);
            }
        },
        error : function(e){
            alert(e);
        },
        complete : function() {
            isAjaxRunning = false;
        }
    });
}
$(window).scroll(function() {
    var scrollBottom = $(window).scrollTop() + $(window).height();
    if (scrollBottom == $(document).height()) {
        start1 = 
        type = 0;
        user_ajax();
    }
});
</script>
<style>
	.title_text{
		margin-bottom:1em;
	}
    .name{
        color:#fff !important;
        font-size:1.6em !important;
    }
    .user_info{
        font-size:1.3em !important;
    }
    .reg_dt{
        font-size:1.45em !important;
        margin-top:0.5em;
        color:#7c7c7c;
    }
    .pd_name{
        color:#898989;
        font-size:1em;
    }
    .active_btn{
        background-color: #04aec6;
        text-align: center;
        line-height: 42px;
        border-radius: 20px;
        color: #fff;
        font-size: 1.3em;
        margin: 0.5em 0;
        padding: 0 4em;
        width:12em;
    }
    .active_btn:hover{
        background-color:#3DB7CC;
    }
    .deactive_btn{
        background-color: #CC3D3D;
        text-align: center;
        line-height: 42px;
        border-radius: 20px;
        color: #fff;
        font-size: 1.3em;
        margin: 0.5em 0;
        padding: 0 4em;
        width:12em;
    }
    .deactive_btn:hover{
        background-color:#F15F5F;
    }
    .waves-effect{
    	cursor:inherit;
    }
</style>
@endsection