@extends('header')
@section('content')

<div class="box box-inbox full-width full-height">
    <div class="box-title" style="padding:1em 0;">
        <div class="box-search" style="display:flex;">
            <input type="text" id="search_keyword" placeholder="프로듀서 닉네임으로 검색(2글자 이상)" name="Search" style="width:auto;flex:auto;">
            <button id="search_btn" style="margin:0 1em;"><img src="{{asset('vendor/images/icon/search.png')}}" alt=""></button>
        </div>
    </div>
    <div class="box-content" id="producerlist">
    	@forelse($query as $item)
        <ul id="inbox-list" class="inbox-list" onclick="location.href='/maker/detail?prdc_id={{$item->prdc_id}}';">
            <li class="waves-effect">
                <div class="left">
                    <div class="info">
                        <p class="name">{{$item->prdc_nick}}</p>
                        <p class="category_text">
                        	@if($item->cate_info=='[]')
                        	선택한 카테고리 없음
                        	@else
                        		@forelse(json_decode($item->cate_info) as $cate)
                        		{{$cate->cate_title}},
                        		@empty
                        		선택한 카테고리 없음
                        		@endforelse
                        	@endif
                        </p>
                        <p class="atmosphere_text">
                        	@if($item->atmo_info=='[]')
                        	선택한 분위기 없음
                        	@else
                        		@forelse(json_decode($item->atmo_info) as $atmo)
                        		{{$atmo->mood_title}},
                        		@empty
                        		선택한 분위기 없음
                        		@endforelse
                        	@endif
                        </p>
                        <p class="reg_dt">{{$item->created_at}}</p>
                    </div>
                </div>
                <div class="right">
                	@if($item->state == 0)
                	<button class="deactive_btn" onclick="activebtn({{$item->prdc_id}}, 2)">거절</button>
                    <button class="active_btn" onclick="activebtn({{$item->prdc_id}}, 1)">승인</button>
                    @elseif($item->state == 1)
                    <button class="deactive_btn" onclick="activebtn({{$item->prdc_id}}, 2)">비활성화 하기</button>
                    @else
                    <button class="active_btn" onclick="activebtn({{$item->prdc_id}}, 1)">활성화 하기</button>
                    @endif
                </div>
                <div class="clearfix"></div>
            </li>
        </ul>
        @empty
        <ul id="inbox-list" class="inbox-list">
        	<h2>프로듀서 정보가 없습니다</h2>
        </ul>
        @endforelse
    </div><!-- /.box-content -->
</div>
<template id="producers">
	<ul id="inbox-list" class="inbox-list">
        <li class="waves-effect">
            <div class="left">
                <div class="info">
                    <p class="name" id="prdc_nick"></p>
                    <p class="category_text" id="prdc_cate">
                    	카테고리
                    </p>
                    <p class="atmosphere_text" id="prdc_mood">
                    	분위기
                    </p>
                    <p class="reg_dt" id="created_at"></p>
                </div>
            </div>
            <div class="right">
                <button id="state_btn" class=""></button>
            </div>
            <div class="clearfix"></div>
        </li>
    </ul>
</template>
<template id="nothings">
	<ul id="inbox-list" class="inbox-list" style="cursor:inherit;">
		<p>일치하는 프로듀서가 없습니다</p>
	</ul>
</template>
<script>
menuactive('message');

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
    	'prdc_id' : idx,
    	'state' : state
    }
    $.ajax({
    	type : "PUT",
    	data : param,
    	dataType : 'json',
    	url : 'api/producers/state',
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
	bf_search = $('#search_keyword').val();
	$('#producerlist').html('');
	type = 1;
	start1 = 0;
	producer_ajax();
});
function producer_ajax(){
	var param = '';
	var url = '';
	if(type==0){
		param = {
			'offset' : start0,
		}
		url = 'api/producers/list';
	}else{
		param = {
			'offset' : start1,
			'prdc_nick' : bf_search
		}
		url = 'api/producers/search';
	}
	$.ajax({
    	type : "GET",
    	data : param,
    	dataType : 'json',
    	url : url,
    	success : function(data){
    		console.log(data);
			if(data.state==1 && data.query !=null && data.query.length > 0){
				var items = data.query;
				for(var i = 0; i < items.length; i++){
					var cate = '';
					var atmo = '';
					if(items[i].cate_info !='[]'){
						var cate_info = JSON.parse(items[i].cate_info);
						if(cate_info.length != 0){
							for(var j = 0; j < cate_info.length; j++){
								if((j+1)==cate_info.length)
									cate += cate_info[j].cate_title;
								else
									cate += cate_info[j].cate_title+',';
							}
						}else{
							cate+='선택한 카테고리 없음';
						}
					}else{
						cate+='선택한 카테고리 없음';
					}
					
					if(items[i].atmo_info !='[]'){
						var atmo_info = JSON.parse(items[i].atmo_info);
						if(atmo_info.length != 0){
							for(var j = 0; j < atmo_info.length; j++){
								if((j+1)==atmo_info.length)
									atmo += atmo_info[j].mood_title;
								else
									atmo += atmo_info[j].mood_title+',';
							}
						}else{
							atmo+='선택한 분위기 없음';
						}
					}else{
						atmo+='선택한 분위기 없음';
					}
					
					
					var template = $($('#producers').html());
					template.attr('onclick','location.href=\'/maker/detail?prdc_id='+items[i].prdc_id+'\';');
					template.find('#prdc_nick').text(items[i].prdc_nick);
					
					
					template.find('#prdc_cate').text(cate);
					template.find('#prdc_mood').text(atmo);
					template.find('#created_at').text(items[i].created_at);
					if(items[i].state ==0){
						template.find('#state_btn').text('승인');
						template.find('#state_btn').addClass('active_btn');
						template.find('#state_btn').attr('onclick','activebtn('+items[i].prdc_id+' , 1)');
					}else if(items[i].state==1){
						template.find('#state_btn').text('비활성화 하기');
						template.find('#state_btn').addClass('deactive_btn');
						template.find('#state_btn').attr('onclick','activebtn('+items[i].prdc_id+' , 2)');
					}else{
						template.find('#state_btn').text('활성화 하기');
						template.find('#state_btn').addClass('active_btn');
						template.find('#state_btn').attr('onclick','activebtn('+items[i].prdc_id+' , 1)');
					}
					$('#producerlist').append(template);
					if(type==0)
						start0++;
					else
						start1++;
				}
			}else if(data.state==1 && data.query.length == 0){
				var template = $($('#nothings').html());
				$('#producerlist').append(template);
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
$(window).scroll(function() {
    var scrollBottom = $(window).scrollTop() + $(window).height();
    if (scrollBottom == $(document).height()) {
    	producer_ajax();
    }
});
</script>
@endsection