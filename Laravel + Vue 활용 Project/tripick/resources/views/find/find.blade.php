@extends('layouts.app')

@section('content')

<div class="wrapper wrapper--find">

	<div class="hd-title hd-title--03">
		<button type="button" class="hd-title__left-btn hd-title__left-btn--home-wh" onClick="location.href='/af_home'">
			<span class="none">홈으로 가기</span>
		</button>
		<h2 class="hd-title__center">동행객 찾기</h2>
	</div>

	<div class="find_friend_sch">
		<input type="text" placeholder="검색하실 내용을 입력하세요.">
	</div>

	<div class="wrapper--find__scroll-area">
		<ul class="find_friend_group" id="acmp-list">
			
		</ul>
	</div>
	<button class="acmp-regist-btn" onclick="location.href='/findregist';">+</button>
</div>

@endsection

@section('script')
<script style="text/javascript">
	// $('.wrapper--find__scroll-area').click(function(e){
	//     var thisclassname = $(e.target).attr('class');
	//     var cmt_write_btn == ['.comment_status_btn', '.comments_group_view_btn'];
	//     if( thisclassname == cmt_write_btn[0] ){

	//         alert('ㅎㅇ')
	//     }
	// })
	$(function() {
		var acmpstart = 0;
		var isacmpLoading = false;
		function loadList(_start) {

        if(isacmpLoading){
            return;
        }

        isacmpLoading= true;

        var params = {
            'offset': _start
        };
        $.ajax({
                url: "/api/acmp/list",
                data: params,
                method: "GET",
                dataType: "json"
            }).done(function(res) {
                //정상회신
              if (res.state == 1) {

                    for (var i = 0; i < res.query.length; i++) {
                        var acmp = res.query[i];
						var thumb = "/storage/fdata/planner/thumb";
                        var acmplist = '<li class="find_friend_list" onclick="location.href=\'/findview?acmp_id='+acmp.acmp_id+'\'">\
											<div class="before_desc" onclick="after_desc_view(this)">\
												<figure class="_thumb" style="background-image:url(/storage/fdata/user/thumb/'+acmp.user_thumb+')"></figure>\
												<h4 class="_name">'+acmp.acmp_title+'</h4>\
												<span class="_date"><em class="_date_time">'+acmp.created_at+'</em>조회수: <em class="_date_hits">'+acmp.view_cnt+'</em>댓글: <em class="_date_cmt">'+acmp.reply_qtt+'</em></span>\
												<p class="_text">'+acmp.acmp_content+'</p>\
											</div>\
										</li>';

                        $('#acmp-list').append(acmplist);
                    }
                    acmpstart += res.query.length;

                } else {
                    //회신오류

                }
            })
            .fail(function(xhr, status, errorThrown) {

                console.log(xhr);
            }) // 
            .always(function(xhr, status) {isacmpLoading  =false;});
    }
    loadList(acmpstart);
	}); 
</script>
<style lang="scss">
	.acmp-regist-btn{
		position: absolute;
	    bottom: 1em;
	    right: 1em;
	    background: #00BBDA;
	    border: 1px solid #00dbd8;
	    border-radius: 5em;
	    color: #fff;
	    width: 1.7em;
	    height: 1.7em;
	    font-size: 1.7em;
	    cursor:pointer;
	}
</style>
@endsection