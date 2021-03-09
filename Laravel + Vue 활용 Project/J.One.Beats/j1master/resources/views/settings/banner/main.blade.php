@extends('header')
@section('content')

<div class="box box-inbox full-width full-height">
    <div class="box-title" style="padding:1em 0;">
    	<div class="title_text">
    		<p>배너 / 이벤트</p>
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
                        <p class="name">{{$item->banner_title}}</p>
                        <p class="user_info" style="display: inline;">{{$item->banner_content}}</p>
                        <img style="border-radius: unset;" src="/fdata/banner/{{$item->banner_img}}" alt="배너 사진"/>
                    </div>
                </div>
                <div class="right">
                    <button class="deactive_btn" onclick="delete_cate({{$item->banner_id}})">삭제</button>
                </div>
            </li>
        </ul>
        @empty
        <ul id="inbox-list" class="inbox-list">
        	<p>배너 없음</p>
        </ul>
        @endforelse
    </div><!-- /.box-content -->
</div>
<div class="category_add">
	<form id="banner_form" enctype="multipart/form-data">
		<input type="hidden" name="req" value="reg"/>
	    <button type="button" id="template_close_btn" style="position:absolute;top:1em;right:1em;"><i class="fa fal fa-times fa-2x" style="color:#fff;"></i></button>
	    <div class="mb_1em">
	        <p class="title">배너 이름</p>
	        <input name="banner_title" type="text"/>
	    </div>
	    <div class="mb_1em">
	        <p class="title">배너 내용</p>
	        <input name="banner_content" type="text"/>
	    </div>
	    <div class="mb_1em">
	        <p class="title">이미지 선택</p>
	        <input name="banner" type="file" accept="images/*"/>
	    </div>
	    <button id="banner_reigst" class="regist_btn">등록</button>
	</form>
</div>
<script>
menuactive('setting');
$(window).load(function() {
    setTimeout(function() {
        $('.loader').hide();
    }, 100);
});
function delete_cate(idx){
	$.ajax({
		type : "DELETE",
		data : {'banner_id' : idx, 'state' : 0},
		dataType: 'json',
		url : '/api/banner/def',
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
	$('[name=banner_title]').val('');
    $('[name=banner_content]').val('');
    $('[name=banner]').val('');
    $('.category_add').removeClass('active');
});
$('#template_open_btn').click(function(){
	$('[name=banner_title]').val('');
    $('[name=banner_content]').val('');
    $('[name=banner]').val('');
    $('.category_add').addClass('active');
});
$('#banner_reigst').click(function(){
	$('#banner_form').ajaxForm({
		type : "POST",
		dataType: 'json',
		url : '/api/banner',
		success : function(data){
			console.log(data);
			if(data.state==1 && data.query !=null){
				alert('등록되었습니다');
				location.reload();
			}
		},
		error : function(e){
			alert(e);
		}
	});
});
</script>
@endsection