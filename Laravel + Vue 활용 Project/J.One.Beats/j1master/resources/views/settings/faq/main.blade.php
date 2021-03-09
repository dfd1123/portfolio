@extends('header')
@section('content')

<div class="box box-inbox full-width full-height">
    <div class="box-title" style="padding:1em 0;">
    	<div class="title_text">
    		<p>FAQ</p>
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
                        <p class="name">{{$item->faq_question}}</p>
                        <p class="user_info">{{$item->faq_answer}}</p>
                    </div>
                </div>
                <div class="right">
                    <button class="deactive_btn" onclick="delete_faq({{$item->faq_id}})">삭제</button>
                </div>
            </li>
        </ul>
        @empty
        <ul id="inbox-list" class="inbox-list">
        	<p>FAQ 없음</p>
        </ul>
        @endforelse
    </div><!-- /.box-content -->
</div>
<div class="category_add">
    <button id="template_close_btn" style="position:absolute;top:1em;right:1em;"><i class="fa fal fa-times fa-2x" style="color:#fff;"></i></button>
    <div class="mb_1em">
        <p class="title">질문</p>
        <input name="faq_question" type="text"/>
    </div>
    <div class="mb_1em">
        <p class="title">답변</p>
        <textarea name="faq_answer" class="content_textarea" rows="10"></textarea>
    </div>
    <button id="faq_regist" class="regist_btn">등록</button>
</div>
<script>
menuactive('setting');
$(window).load(function() {
    setTimeout(function() {
        $('.loader').hide();
    }, 100);
});
function delete_faq(idx){
	$.ajax({
		type : "DELETE",
		data : {'faq_id' : idx, 'state' : 0},
		dataType: 'json',
		url : '/api/faq/def',
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
	$('[name=faq_question]').val('');
    $('[name=faq_answer]').val('');
    $('.category_add').removeClass('active');
});
$('#template_open_btn').click(function(){
	$('[name=faq_question]').val('');
    $('[name=faq_answer]').val('');
    $('.category_add').addClass('active');
});
$('#faq_regist').click(function(){
	var param = {
		'req' : 'reg',
		'faq_question' : $('[name=faq_question]').val(),
		'faq_answer' : $('[name=faq_answer]').val()
	};
	$.ajax({
		type : "POST",
		dataType: 'json',
		url : '/api/faq',
		data : param,
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
<style>
    
    .waves-effect{
        cursor:inherit !important;
    }

</style>
@endsection