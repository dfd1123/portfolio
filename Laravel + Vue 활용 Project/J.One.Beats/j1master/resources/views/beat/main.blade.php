@extends('header')
@section('content')

<div class="box box-inbox full-width full-height">
    <div class="box-title" style="padding:1em 0;">
        <div class="box-search" style="display:flex;">
        	<div class="select_div">
        		<select class="search_select" id="search_type" name="search_type">
	        		<option value="1">음악 명으로 검색</option>
	        		<option value="2">프로듀서 명으로 검색</option>
	        	</select>
        	</div>
        	
            <input type="text" id="search_keyword" name="search_keyword" placeholder="키워드(2글자 이상)" name="Search" style="width:auto;flex:auto;">
            <button id="search_btn" style="margin:0 1em;"><img src="{{asset('vendor/images/icon/search.png')}}" alt=""></button>
        </div>
    </div>
    <div class="box-content" id="beatlist">
        @forelse($query as $item)
        <ul id="inbox-list" class="inbox-list" onclick="location.href='/beat/detail?beat_id={{$item->beat_id}}';">
            <li class="waves-effect">
                <div class="left">
                    <div class="info">
                        <p class="name">{{$item->beat_title}}<em class="pd_name"> - {{$item->prdc_nick}}</em></p>
                        <p>{{$item->beat_time}}</p>
                    </div>
                </div>
                <div class="right">
                	@if($item->state == 0)
                    <button class="active_btn" onclick="activebtn({{$item->beat_id}} , 1)">활성화 하기</button>
                    @else
                    <button class="deactive_btn" onclick="activebtn({{$item->beat_id}} , 0)">비활성화 하기</button>
                    @endif
                </div>
                <div class="clearfix"></div>
            </li>
        </ul>
        @empty
        <h2>비트가 없습니다!</h2>
        @endforelse
    </div><!-- /.box-content -->
</div>
<template id="beats">
	<ul id="inbox-list" class="inbox-list">
        <li class="waves-effect">
            <div class="left">
                <div class="info">
                    <p id="title" class="name"><em class="pd_name"></em></p>
                    <p id="playtime"></p>
                </div>
            </div>
            <div class="right">
                <button id="beat_state"></button>
            </div>
            <div class="clearfix"></div>
        </li>
    </ul>
</template>
<template id="nothings">
	<ul id="inbox-list" class="inbox-list" style="cursor:inherit;">
		<p>일치하는 곡이 없습니다</p>
	</ul>
</template>
<script>
var search_type = 0; //0 : 전체 검색, 1: 음악명으로 검색, 2: 프로듀서 명으로 검색
var start0 = 20 , start1 = 0, start2 = 0;
var bf_search = '';
$(window).load(function() {
    setTimeout(function() {
        $('.loader').hide();
    }, 100);
});
function activebtn(idx, state){
    event.stopPropagation();
    var param = {
    	'beat_id' : idx,
    	'state' : state
    }
    $.ajax({
    	type : "PUT",
    	data : param,
    	dataType : 'json',
    	url : 'api/beat/state',
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
//GET파라미터 찾기
function findGetParameter(parameterName) {
	var result = null,
		tmp = [];
	location.search
		.substr(1)
		.split("&")
		.forEach(function (item) {
			tmp = item.split("=");
			if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
		});
	return result;
}
//검색링크타고 넘어오면 처리하는 로직 - 구현중
var prdc_nick = findGetParameter('prdc_nick');
if(prdc_nick != null){
	search_type = 2;
	document.getElementById("search_type").value = "2";
	$('#search_keyword').val(prdc_nick);
	bf_search = prdc_nick;
	$('#beatlist').html('');
	beatlist_ajax_prdc();
}


//검색 기능 구현
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
	start0 = 0; start1 = 0; start2 = 0;
	bf_search = $('#search_keyword').val();
	search_type= document.getElementById("search_type").value;
	$('#beatlist').html('');
	if(search_type == 2){
		beatlist_ajax_prdc();
	}else{
		beatlist_ajax();
	}
	console.log('검색타입 : '+search_type+'/ 키워드 : '+bf_search);
});
function beatlist_ajax(){
	var param = '';
	if(search_type == 0){
		//전체 검색(스크롤 할 시)
		param = {'offset' : start0}
	}else {
		//음악명으로 검색 and 스크롤
		param = {'offset' : start1 ,'beat_title' : bf_search}
	}
	$.ajax({
    	type : "GET",
    	data : param,
    	dataType : 'json',
    	url : 'api/beat/list',
    	success : function(data){
    		console.log(data);
			if(data.state==1 && data.query !=null && data.query.length > 0){
				var items = data.query;
				for(var i = 0; i < items.length; i++){
					var template = $($('#beats').html());
					template.attr('onclick','location.href=\'/beat/detail?beat_id='+items[i].beat_id+'\';');
					template.find('#title').html(items[i].beat_title+'<em class="pd_name"> - '+items[i].prdc_nick+'</em>');
					template.find('#playtime').text(items[i].beat_time);
					if(items[i].state ==1){
						template.find('#beat_state').text('비활성화 하기');
						template.find('#beat_state').addClass('deactive_btn');
						template.find('#beat_state').attr('onclick','activebtn('+items[i].beat_id+' , 0)');
					}else{
						template.find('#beat_state').text('활성화 하기');
						template.find('#beat_state').addClass('active_btn');
						template.find('#beat_state').attr('onclick','activebtn('+items[i].beat_id+' , 1)');
					}
					$('#beatlist').append(template);
					if(search_type ==0)
						start0++;
					else if(search_type ==1)
						start1++;
					else
						start2++;
				}
			}else if(data.state==1 && data.query.length == 0){
				var template = $($('#nothings').html());
				$('#beatlist').append(template);
			}
			else{
				alert(data.msg);
			}
    	},
    	error : function(e){
    		alert(e);
    	}
    });
}
function beatlist_ajax_prdc(){
	var param = {
		'offset' : start2,
		'prdc_nick' : bf_search
	}
	$.ajax({
    	type : "GET",
    	data : param,
    	dataType : 'json',
    	url : 'api/beat/byprdc',
    	success : function(data){
    		console.log(data);
			if(data.state==1 && data.query !=null && data.query.length > 0){
				var items = data.query;
				for(var i = 0; i < items.length; i++){
					var template = $($('#beats').html());
					template.attr('onclick','location.href=\'/beat/detail?beat_id='+items[i].beat_id+'\';');
					template.find('#title').html(items[i].beat_title+'<em class="pd_name"> - '+items[i].prdc_nick+'</em>');
					template.find('#playtime').text(items[i].beat_time);
					if(items[i].state ==1){
						template.find('#beat_state').text('비활성화 하기');
						template.find('#beat_state').addClass('deactive_btn');
						template.find('#beat_state').attr('onclick','activebtn('+items[i].beat_id+' , 0)');
					}else{
						template.find('#beat_state').text('활성화 하기');
						template.find('#beat_state').addClass('active_btn');
						template.find('#beat_state').attr('onclick','activebtn('+items[i].beat_id+' , 1)');
					}
					$('#beatlist').append(template);
					start2++;
				}
			}else if(data.state==1 && data.query.length == 0){
				var template = $($('#nothings').html());
				$('#beatlist').append(template);
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
        if(search_type==2){
        	beatlist_ajax_prdc();
        }else{
        	beatlist_ajax();
        }
    }
});
</script>
@endsection