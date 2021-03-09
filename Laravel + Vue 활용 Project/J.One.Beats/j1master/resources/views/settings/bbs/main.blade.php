@extends('header')
@section('content')

<div class="box box-inbox full-width full-height">
    <div class="box-title" style="padding:1em 0;">
    	<div class="title_text">
    		<p>1:1 문의</p>
    	</div>
    </div>
    <div>
        <button class="deactive_toggle active">미답변</button>
        <button class="active_toggle">답변</button>
    </div>
    <div class="box-content">
    	<div class="no_reply_div active">
    		@forelse($query1 as $item)
	        <ul id="inbox-list" class="inbox-list">
	            <li class="waves-effect">
	                <div class="left">
	                    <div class="info">
                            <p class="name">{{$item->qna_title}}</p>
                            <p class="user_info">유저명: {{$item->user_nick}} (#{{$item->user_id}} {{$item->user_name}}), 폰번호: {{$item->user_mobile}}</p><br/>
	                        <p class="user_info">{{$item->qna_content}}</p>
	                        <p class="reg_dt">{{$item->created_at}}</p>
	                        <input type="text" name="reply{{$item->qna_id}}" placeholder="답변을 입력해주세요" style="width: 100%;"/>
	                    </div>
	                </div>
	                <div class="right">
	                    <button class="active_btn" onclick="qna_reply({{$item->qna_id}})">등록</button>
	                </div>
	                <div class="clearfix"></div>
	            </li>
	        </ul>
	        @empty
	        <ul id="inbox-list" class="inbox-list">
	        	<p>미답변 Q&A 없음</p>
	        </ul>
	        @endforelse
        </div>
        <div class="ok_reply_div">
        	@forelse($query2 as $item)
        	<ul id="inbox-list" class="inbox-list">
	            <li class="waves-effect">
	                <div class="left">
	                    <div class="info">
                            <p class="name">{{$item->qna_title}}</p>
                            <p class="user_info">유저명: {{$item->user_nick}} (#{{$item->user_id}} {{$item->user_name}}), 폰번호: {{$item->user_mobile}}</p><br/>
	                        <p class="user_info">{{$item->qna_content}}</p>
	                        <p class="reg_dt">{{$item->created_at}}</p>
	                        <input type="text" name="reply{{$item->qna_id}}" placeholder="답변을 입력해주세요" style="width: 100%;" value="{{$item->qna_answer}}"/>
	                    </div>
	                </div>
	                <div class="right">
	                    <button class="update_btn" onclick="qna_reply({{$item->qna_id}})">수정</button>
	                </div>
	                <div class="clearfix"></div>
	            </li>
	        </ul>
	        @empty
	        <ul id="inbox-list" class="inbox-list">
	        	<p>미답변 Q&A 없음</p>
	        </ul>
	        @endforelse
        </div>
    </div><!-- /.box-content -->
</div>
<script>
menuactive('setting');
$(window).load(function() {
    setTimeout(function() {
        $('.loader').hide();
    }, 100);
});
function qna_reply(idx){
    var reply = $('[name=reply'+idx+']').val();
    if(reply == ''){
    	alert('답변을 입력해주세요');
    	return;
    }else{
    	var param = {
    		'req' : 'answer',
    		'qna_id' : idx,
    		'qna_answer' : reply
    	};
    	$.ajax({
    		type : 'PUT',
    		data : param,
    		dataType : 'json',
    		url : '/api/qna/def',
    		success : function(data){
    			console.log(data);
				if(data.state==1 && data.query !=null){
					alert('완료 되었습니다');
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
}

$('.deactive_toggle').click(function(){
    if($(this).hasClass('active')){
    }else{
        $(this).addClass('active');
        $('.active_toggle').removeClass('active');
        $('.no_reply_div').addClass('active');
        $('.ok_reply_div').removeClass('active');
    }
});
$('.active_toggle').click(function(){
    if($(this).hasClass('active')){
    }else{
        $(this).addClass('active');
        $('.deactive_toggle').removeClass('active');
        $('.ok_reply_div').addClass('active');
        $('.no_reply_div').removeClass('active');
    }
});
</script>
<style>
@media only screen and (max-width: 767px) {
	.left{
		width:100%;
	}
	.right{
		float:inherit !important;
	}
	.update_btn{
		width:100% !important;
	}
}
	.deactive_toggle{
        color: #fff;
        padding: 1em;
        background-color: #8c8c8c;
        margin-bottom: 1.5em;
        border-top-left-radius: 1em;
        border-bottom-left-radius: 1em;
    }
    .deactive_toggle:hover{
        background-color: #a22321;
    }
    .deactive_toggle.active{
        background-color: #a22321;
    }
    .active_toggle{
        color: #fff;
        padding: 1em;
        background-color: #8c8c8c;
        margin-bottom: 1.5em;
        border-top-right-radius: 1em;
        border-bottom-right-radius: 1em;
    }
    .active_toggle:hover{
        background-color: #04aec6;
    }
    .active_toggle.active{
        background-color: #04aec6;
    }
    .no_reply_div{
    	display:none;
    }
    .no_reply_div.active{
    	display:block;
    }
    .ok_reply_div{
    	display:none;
    }
    .ok_reply_div.active{
    	display:block;
    }
    .reg_dt{
        font-size:1.45em !important;
        margin-top:0.5em;
        color:#7c7c7c;
        margin-bottom:1em;
    }
    .active_btn{
        width:12em;
    }
    .deactive_btn{
        width:12em;
    }
    .update_btn{
    	background-color: #2F9D27;
        text-align: center;
        line-height: 42px;
        border-radius: 20px;
        color: #fff;
        font-size: 1.3em;
        margin: 0.5em 0;
        padding: 0 4em;
        width:12em;
    }
    .update_btn:hover{
        background-color:#47C83E;
    }
    .waves-effect{
        cursor:inherit !important;
    }

    
</style>
@endsection
