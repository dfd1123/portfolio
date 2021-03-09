@extends('header')
@section('content')

<div class="box box-inbox full-width full-height">
    <div class="box-title" style="padding:1em 0;">
    	<div class="title_text">
    		<p>공지사항</p>
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
                        <p class="name">{{$item->notice_title}}</p>
                        <p class="user_info">{{$item->notice_content}}</p>
                    </div>
                </div>
                <div class="right">
                    <button class="deactive_btn" onclick="delete_notice({{$item->notice_id}})">삭제</button>
                </div>
            </li>
        </ul>
        @empty
        <ul id="inbox-list" class="inbox-list">
        	<p>공지사항 없음</p>
        </ul>
        @endforelse
    </div><!-- /.box-content -->
</div>
<div class="category_add">
    <button id="template_close_btn" style="position:absolute;top:1em;right:1em;"><i class="fa fal fa-times fa-2x" style="color:#fff;"></i></button>
    <div class="mb_1em">
        <p class="title">제목</p>
        <input name="notice_title" type="text"/>
    </div>
    <div class="mb_1em">
        <p class="title">내용</p>
        <textarea name="notice_content" class="content_textarea" rows="10"></textarea>
    </div>
    <button id="notice_reigst" class="regist_btn">등록</button>
</div>
<script>
menuactive('setting');
$(window).load(function() {
    setTimeout(function() {
        $('.loader').hide();
    }, 100);
});
function delete_notice(idx){
	$.ajax({
		type : "DELETE",
		data : {'notice_id' : idx, 'state' : 0},
		dataType: 'json',
		url : '/api/notice/def',
		success : function(data){
			console.log(data);
			if(data.state==1 && data.query !=null){
				alert('삭제되었습니다!');
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
});
$('#template_open_btn').click(function(){
    $('.category_add').addClass('active');
});
$('#notice_reigst').click(function(){
	var param = {
		'req' : 'reg',
		'notice_title' : $('[name=notice_title]').val(),
		'notice_content' : $('[name=notice_content]').val()
	};
	console.log(param);
	
	$.ajax({
		type : "POST",
		data : param,
		dataType: 'json',
		url : '/api/notice',
		success : function(data){
			console.log(data);
			if(data.state==1 && data.query !=null){
				alert('등록되었습니다!');
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