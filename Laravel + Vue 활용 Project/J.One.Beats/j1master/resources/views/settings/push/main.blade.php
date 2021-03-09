@extends('header')
@section('content')

<div class="box box-inbox full-width full-height">
    <div class="box-title" style="padding:1em 0;">
    	<div class="title_text">
    		<p>PUSH 관리</p>
    	</div>
        <div class="box-search">
            <button id="template_open_btn" style="float:right;position:relative;right:42px;"><i class="fa far fa-plus fa-2x" style="color:#fff;"></i></button>
        </div>
    </div>
    <div class="box-content">
        <ul id="inbox-list" class="inbox-list">
            <li class="waves-effect">
                <div class="left">
                    <div class="info">
                        <p class="name">J1BEATZ 오픈 이벤트 진행중! 여러분 모두 참가해보세요 </p>
                        <p class="user_info">모든 유저</p>
                    </div>
                </div>
                <div class="clearfix"></div>
            </li>
        </ul>
        <ul id="inbox-list" class="inbox-list">
            <li class="waves-effect">
                <div class="left">
                    <div class="info">
                        <p class="name">불법 이용 프로그램 감지. 반복적인 프로그램 사용시 이용을 하실 수 없습니다</p>
                        <p class="user_info">wnstjr101@naver.com</p>
                    </div>
                </div>
                <div class="clearfix"></div>
            </li>
        </ul>
    </div><!-- /.box-content -->
</div>
<div class="category_add">
    <button id="template_close_btn" style="position:absolute;top:1em;right:1em;"><i class="fa fal fa-times fa-2x" style="color:#fff;"></i></button>
    <div class="mb_1em">
    	<div class="title">
    		<label><input type="radio" name="type" value="0" checked=""/>모든 유저</label>
    		<label><input type="radio" name="type" value="1"/>특정 유저</label>
    	</div>
    	<div class="user_send">
    		<p class="content">유저 이메일</p>
	        <input type="text"/>
    	</div>
    </div>
    <div class="mb_1em">
        <p class="title">보낼 메세지</p>
        <textarea class="content_textarea" rows="10"></textarea>
    </div>
    <button class="regist_btn">보내기</button>
</div>
<script>
menuactive('setting');
$(window).load(function() {
    setTimeout(function() {
        $('.loader').hide();
    }, 100);
});
function activebtn(idx){
    event.stopPropagation();
    console.log('active...');
}
function deactivebtn(idx){
    event.stopPropagation();
    console.log('deactive...');
}

$('#template_close_btn').click(function(){
    $('.category_add').removeClass('active');
});
$('#template_open_btn').click(function(){
    $('.category_add').addClass('active');
});
$('[name=type]').change(function(){
	if($(this).val()==1){
		$('.user_send').addClass('active');
	}else{
		$('.user_send').removeClass('active');
	}
});
</script>
<style>
    
    .mb_1em .title{
    	padding:1em 0;
    }
    .mb_1em .title label{
    	width:49.9%;
    	display:inline-block;
    	padding:0.5em 0;
    	font-size:1.2em;
    }
    .mb_1em .title label input[type="radio"]{
    	margin-right:1em;
    }
    .category_add .content{
        font-family: 'Oswald';
        margin-bottom:1em;
        font-size:1.2em;
        position: relative;
        left: 0.5em;
    }
    .category_add input{
        padding: 0.5em 1em;
        font-size: 1.2em;
    }
    .category_add.active{
        position:absolute;
        display:block;
    }
    .user_list{
    	border:1px solid #2f3136;
    	height:10em;
    	overflow-y:auto;
    	padding:0.5em;
    }
    .user_list label{
    	width:100%;
    }
    .user_list label input{
    	margin:0 1em;
    }
    .user_send{
    	display:none;
    }
    .user_send.active{
    	display:block;
    }
</style>
@endsection