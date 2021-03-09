@extends('header')
@section('content')

<div class="box box-inbox full-width full-height">
    <div class="box-title" style="padding:1em 0;">
    	<div class="title_text">
    		<p>분위기 목록</p>
    	</div>
        <div class="box-search">
            <button id="template_open_btn" style="float:right;position:relative;right:42px;"><i class="fa far fa-plus fa-2x" style="color:#fff;"></i></button>
        </div>
    </div>
    <div class="box-content">
    	@forelse($query as $item)
        <ul id="inbox-list" class="inbox-list">
            <li class="waves-effect">
                <div class="left">
                    <div class="info">
                        <p class="name">{{$item->mood_title}}</p>
                        <img src="/fdata/mood/{{$item->mood_thumb}}" alt="분위기 배경사진"/>
                    </div>
                </div>
                <div class="right">
										@if($item->state == 1)
                    <button class="deactive_btn" onclick="delete_mood({{$item->mood_id}}, 0)">비활성화</button>
                    @else
                    <button class="active_btn" onclick="delete_mood({{$item->mood_id}}, 1)">활성화</button>
										@endif
                </div>
                <div class="clearfix"></div>
            </li>
        </ul>
        @empty
        <ul id="inbox-list" class="inbox-list">
        	<p>분위기 없음</p>
        </ul>
        @endforelse
    </div><!-- /.box-content -->
</div>
<div class="category_add">
	<form id="mood_form" enctype="multipart/form-data">
		<input type="hidden" name="req" value="reg"/>
	    <button type="button" id="template_close_btn" style="position:absolute;top:1em;right:1em;"><i class="fa fal fa-times fa-2x" style="color:#fff;"></i></button>
	    <div class="mb_1em">
	        <p class="title">분위기 이름</p>
	        <input name="mood_title" type="text"/>
	    </div>
	    <div class="mb_1em">
	        <p class="title">이미지 선택</p>
	        <input name="thumb" type="file" accept="image/*"/>
	    </div>
	    <button id="mood_regist" class="regist_btn">등록</button>
    </form>
</div>
<script>
menuactive('apps');

$(window).load(function() {
    setTimeout(function() {
        $('.loader').hide();
    }, 100);
});
function delete_mood(idx, state){
	$.ajax({
		type : "PUT",
		data : {'mood_id' : idx, 'state' : state},
		dataType: 'json',
		url : 'api/mood/state',
		success : function(data){
			console.log(data);
			if(data.state==1 && data.query !=null){
				alert('상태가 변경되었습니다!');
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
$('#template_close_btn').click(function(){
    $('.category_add').removeClass('active');
    $('[name=mood_title]').val('');
    $('[name=thumb]').val('');
});
$('#template_open_btn').click(function(){
    $('.category_add').addClass('active');
    $('[name=mood_title]').val('');
    $('[name=thumb]').val('');
});
$('#mood_regist').click(function(){
	$('#mood_form').ajaxForm({
		type : "POST",
		dataType: 'json',
		url : 'api/mood',
		success : function(data){
			console.log(data);
			if(data.state==1 && data.query !=null){
				alert('등록되었습니다');
				location.reload();
			}else{
				alert(data.msg);
			}
		},
		error : function(e){
			alert(e);
		}
	});
});
</script>
@endsection
