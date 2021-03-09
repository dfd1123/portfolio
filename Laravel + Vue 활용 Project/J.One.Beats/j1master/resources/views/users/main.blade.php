@extends('header')
@section('content')

<div class="box box-inbox full-width full-height">
    <div class="box-title" style="padding:1em 0;">
        <div class="box-search" style="display:flex;">
            <input type="text" id="search_keyword" placeholder="유저 명으로 검색 (2글자 이상)" name="Search" style="width:auto;flex:auto;">
            <button id="search_btn" style="margin:0 1em;"><img src="{{asset('vendor/images/icon/search.png')}}" alt=""></button>
        </div>
    </div>
    <div class="box-content" id="userlist">
    	@forelse($query as $item)
        <ul id="inbox-list" class="inbox-list" onclick="location.href='/users/detail?user_id={{$item->user_id}}';">
            <li class="waves-effect">
                <div class="left">
                    <div class="info">
                        <p class="name">{{$item->user_nick}}</p>
                        <p class="user_info">{{$item->user_email}}</p>
												<p class="user_info">{{$item->user_name}}</p>
												@if($item->user_agr_email_prom)
												<p style="color:blueviolet" class="user_info">광고 허용</p>
												@endif
                        <p class="reg_dt">{{$item->created_at}}</p>
                    </div>
                </div>
                <div class="right">
                	@if($item->state == 0)
                    <button class="active_btn" onclick="activebtn({{$item->user_id}}, 1)">활성화 하기</button>
                    @elseif($item->state ==1)
                    <button class="deactive_btn" onclick="activebtn({{$item->user_id}}, 0)">비활성화 하기</button>
                    @else
                    <button class="active_btn">탈퇴대기</p>
                    @endif
                </div>
                <div class="clearfix"></div>
            </li>
        </ul>
        @empty
        <ul id="inbox-list" class="inbox-list">
        	<p>유저가 없습니다</p>
        </ul>
        @endforelse
    </div><!-- /.box-content -->
</div>
<template id="users">
	<ul id="inbox-list" class="inbox-list">
        <li class="waves-effect">
            <div class="left">
                <div class="info">
                    <p class="name" id="user_nick"></p>
                    <p class="user_info" id="user_email"></p>
                    <p class="user_info" id="user_name"></p>
										<p class="user_info" id="user_agr_email_prom" style="color:blueviolet">광고 허용</p>
										<p class="reg_dt" id="created_at"></p>
                </div>
            </div>
            <div class="right" id="state_btn">
            </div>
        </li>
    </ul>
</template>
<template id="nothings">
	<ul id="inbox-list" class="inbox-list" style="cursor:inherit;">
		<p>일치하는 사용자가 없습니다</p>
	</ul>
</template>
<script>
menuactive('calendar');

var bf_search = '';
var type = 0; //0 : 전체, 1 : 검색
var start0 = 20;
var start1 = 0;

$(window).load(function() {
    setTimeout(function() {
        $('.loader').hide();
    }, 100);
});
function activebtn(idx, state){
    event.stopPropagation();
    var param = {
    	'user_id' : idx,
    	'state' : state
    }
    $.ajax({
    	type : "PUT",
    	data : param,
    	dataType : 'json',
    	url : 'api/user/state',
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
	$('#userlist').html('');
	type = 1;
	start1 = 0;
	user_ajax();
});
function user_ajax(){
	var param = '';
	var url = '';
	if(type==0){
		param = {
			'offset' : start0,
		}
		url = 'api/user/list';
	}else{
		param = {
			'offset' : start1,
			'user_nick' : bf_search
		}
		url = 'api/user/search';
	}
	$.ajax({
			type : "GET",
			data : param,
			dataType : 'json',
			url : url,
			success : function(data){
				if(data.state==1 && data.query !=null && data.query.length > 0){
					var items = data.query;
					for(var i = 0; i < items.length; i++){
						
						var template = $($('#users').html());
						template.attr('onclick','location.href=\'/users/detail?user_id='+items[i].user_id+'\';');
						template.find('#user_nick').text(items[i].user_nick);
						template.find('#user_email').text(items[i].user_email);
						template.find('#user_name').text(items[i].user_name);
						template.find('#created_at').text(items[i].created_at);
						if(!items[i].user_agr_email_prom) {
							template.find('#user_agr_email_prom').hide();
						}
						if(items[i].state ==0){
							template.find('#state_btn').html('<button class="active_btn" onclick="activebtn('+items[i].user_id+', 1)">활성화 하기</button>');
						}else if(items[i].state==1){
							template.find('#state_btn').html('<button class="deactive_btn" onclick="activebtn('+items[i].user_id+', 0)">비활성화 하기</button>');
						}else{
							template.find('#state_btn').html('<button class="active_btn">탈퇴대기</p>');
						}
						$('#userlist').append(template);
						if(type==0)
							start0++;
						else
							start1++;
					}
				}else if(data.state==1 && data.query.length == 0){
					var template = $($('#nothings').html());
					$('#userlist').append(template);
				}else{
					alert(data.msg);
				}
			},
			error : function(e){
				alert(e);
			}
    });
}
$(window).scroll(function() {
    var scrollBottom = $(window).scrollTop() + $(window).height();
    if (scrollBottom == $(document).height()) {
			user_ajax();
    }
});
</script>
@endsection
