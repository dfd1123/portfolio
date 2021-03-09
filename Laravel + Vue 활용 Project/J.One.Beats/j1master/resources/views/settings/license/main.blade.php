@extends('header')
@section('content')

<div class="box box-inbox full-width full-height">
    <div class="box-title" style="padding:1em 0;">
    	<div class="title_text">
    		<p>라이센스 상품</p>
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
                        <p class="name">{{$item->lcens_name}}</p>
                        <p class="user_info">{{number_format($item->lcens_price)}}원</p>
                        <p class="user_info">이용기간 : {{$item->lcens_days}}일</p>
                        <p class="user_info">이용권 타입 : {{$item->lcens_type}}</p>
                        <p class="reg_dt">{{$item->lcens_desc}}</p>
                    </div>
                </div>
                <div class="right">
                	@if($item->state ==1)
                    <button class="a_btn" onclick="lcens_state({{$item->lcens_id}} , 0)">비활성화</button>
                    @else
                    <button class="de_btn" onclick="lcens_state({{$item->lcens_id}} , 1)">활성화</button>
                    @endif
                    <button class="deactive_btn" onclick="lcens_delete({{$item->lcens_id}})">삭제</button>
                </div>
                <div class="clearfix"></div>
            </li>
        </ul>
        @empty
        <ul id="inbox-list" class="inbox-list">
            <li class="waves-effect">
            	<p>등록된 이용권 없음</p>
            </li>
        </ul>
        @endforelse
    </div><!-- /.box-content -->
</div>
<div class="category_add">
    <button id="template_close_btn" style="position:absolute;top:1em;right:1em;"><i class="fa fal fa-times fa-2x" style="color:#fff;"></i></button>
    <div class="mb_1em">
        <p class="title">상품 이름</p>
        <input name="lcens_name" type="text"/>
    </div>
    <div class="mb_1em">
        <p class="title">상품 가격(원)</p>
        <input name="lcens_price" type="number" min="0" style="border: 1px solid #2f3136;"/>
    </div>
    <div class="mb_1em">
        <p class="title">상품 기간(일)</p>
        <input name="lcens_days" type="number" min="0" style="border: 1px solid #2f3136;"/>
    </div>
    <div class="mb_1em">
        <p class="title">상품 설명</p>
        <textarea name="lcens_desc" class="content_textarea" rows="10"></textarea>
    </div>
    <div class="mb_1em">
    	<p class="title">상품 타입</p>
    	<div class="select_div">
	    	<select name="lcens_type" class="type_select">
	    		<option value="0">0</option>
	    		<option value="1">1</option>
	    	</select>
	    </div>
    </div>
    <button id="license_regist" class="regist_btn">등록</button>
</div>
<script>
menuactive('setting');
$(window).load(function() {
    setTimeout(function() {
        $('.loader').hide();
    }, 100);
});
function lcens_state(idx, state){
	$.ajax({
		type : "PUT",
		data : {
			'lcens_id' : idx, 
			'state' : state
		},
		dataType: 'json',
		url : '/api/license/state',
		success : function(data){
			console.log(data);
			if(data.state==1 && data.query !=null){
				alert('변경 되었습니다!');
				location.reload();
			}
		},
		error : function(e){
			alert(e);	
		}
	});
}
function lcens_delete(idx){
	console.log('idx : '+idx+' /삭제 로직 고민중..');
}
$('#license_regist').click(function(){
	var param = {
		'req' : 'reg',
		'lcens_name' : $('[name=lcens_name]').val(),
		'lcens_price' : $('[name=lcens_price]').val(),
		'lcens_days' : $('[name=lcens_days]').val(),
		'lcens_desc' : $('[name=lcens_desc]').val(),
		'lcens_type' : $('[name=lcens_type]').val()
	}
	$.ajax({
		type : "POST",
		data : param,
		dataType: 'json',
		url : '/api/license',
		success : function(data){
			console.log(data);
			if(data.state==1 && data.query !=null){
				alert('등록 되었습니다!');
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
$('#template_close_btn').click(function(){
	$('[name=lcens_name]').val('');
	$('[name=lcens_price]').val('');
	$('[name=lcens_days]').val('');
	$('[name=lcens_desc]').val('');
    $('.category_add').removeClass('active');
});
$('#template_open_btn').click(function(){
	$('[name=lcens_name]').val('');
	$('[name=lcens_price]').val('');
	$('[name=lcens_days]').val('');
	$('[name=lcens_desc]').val('');
    $('.category_add').addClass('active');
});
</script>
<style>
@media only screen and (max-width: 767px) {
	.category_add{
		margin-top:10em;
	}
}
    .a_btn{
    	background-color: transparent;
    	border:solid 1px #8041D9;
        text-align: center;
        line-height: 42px;
        border-radius: 20px;
        color: #fff;
        font-size: 1.3em;
        margin: 0.5em 0;
        padding: 0 4em;
        width:12.5em;
        margin-right:0.5em;
    }
    .a_btn:hover{
    	border:solid 1px #A566FF;
    }
    .de_btn{
    	border: #2F9D27 solid 1px;
        text-align: center;
        line-height: 42px;
        border-radius: 20px;
        color: #fff;
        font-size: 1.3em;
        margin: 0.5em 0;
        padding: 0 4em;
        width:12em;
        margin-right:0.5em;
    }
    .de_btn:hover{
    	border:solid 1px #47C83E;
    }
    .waves-effect{
        cursor:inherit !important;
    }

    .type_select{
	    height: inherit;
	    border: 1px solid #2f3136;
	    padding:1em;
	    position: relative;
	    text-align:center;
    }
    .select_div{
		position:relative;
	}
	.select_div:after{
	    content: "▼";
	    padding: 12px 8px;
	    position: absolute;
	    right: 1em;
	    top: 0;
	    z-index: 1;
	    text-align: center;
	    width: 10%;
	    height: 100%;
	    pointer-events: none;
	}
</style>
@endsection