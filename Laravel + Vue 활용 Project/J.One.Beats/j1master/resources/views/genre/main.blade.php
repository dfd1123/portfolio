@extends('header')
@section('content')

<div class="box box-inbox full-width full-height">
    <div class="box-title" style="padding:1em 0;">
    	<div class="title_text">
    		<p>카테고리 목록</p>
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
                        <p class="name">{{$item->cate_title}}</p>
                    </div>
                </div>
                <div class="right">
                    @if($item->state == 1)
                    <button class="deactive_btn" onclick="update_cate({{$item->cate_id}}, 0)">비활성화</button>
                    @else
                    <button class="active_btn" onclick="update_cate({{$item->cate_id}}, 1)">활성화</button>
                    @endif
                </div>
                <div class="clearfix"></div>
            </li>
        </ul>
        @empty
        <ul id="inbox-list" class="inbox-list">
        	<p>카테고리 없음</p>
    	</ul>
        @endforelse
    </div><!-- /.box-content -->
</div>
<div class="category_add">
    <button id="template_close_btn" style="position:absolute;top:1em;right:1em;"><i class="fa fal fa-times fa-2x" style="color:#fff;"></i></button>
    <div class="mb_1em">
        <p class="title">카테고리 이름</p>
        <input id="cate_title" name="cate_title" type="text"/>
    </div>
    <button class="regist_btn" id="cate_regist">등록</button>
</div>
<script>
menuactive('pages');

$(window).load(function() {
    setTimeout(function() {
        $('.loader').hide();
    }, 100);
});
$('#template_close_btn').click(function(){
    $('.category_add').removeClass('active');
    $('[name=cate_title]').val('');
});
$('#template_open_btn').click(function(){
    $('.category_add').addClass('active');
    $('[name=cate_title]').val('');
});

function update_cate(idx, state){
	$.ajax({
        type : "PUT",
        data : {'cate_id' : idx, 'state' : state},
        dataType: 'json',
        url : 'api/genre/state',
        success : function(data){
            console.log(data);
            if(data.state==1 && data.query !=null){
                alert('상태가 변경되었습니다!');
                location.reload();
            }
        },
        error : function(e){
            alert(e);	
        }
    });
	
}

$('#cate_regist').click(function(){
	$.ajax({
		type : 'POST',
		dataType : 'json',
        data: {req: 'reg', cate_title: $('#cate_title').val()},
		url : 'api/genre',
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
<style>
    .waves-effect{
        cursor:inherit !important;
    }
    
    
</style>
@endsection
