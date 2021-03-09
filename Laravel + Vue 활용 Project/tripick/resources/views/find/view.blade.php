@extends('layouts.app')

@section('content')

<div class="wrapper wrapper--find">

	<div class="hd-title hd-title--03">
		<button type="button" class="hd-title__left-btn hd-title__left-btn--prev-wh" onClick="location.href='/find'">
			<span class="none">홈으로 가기</span>
		</button>
		<h2 class="hd-title__center">동행객 찾기</h2>
	</div>

	<div class="wrapper--find__scroll-area02">
		<ul class="find_friend_group">
			<li class="find_friend_list">

				<div class="before_desc" onclick="after_desc_view(this)">
					<figure class="_thumb"></figure>
					<h4 class="_name" id="name"></h4>
					<span class="_date"><em class="_date_time" id="created_at"></em>조회수: <em class="_date_hits" id="view_cnt"></em>댓글: <em class="_date_cmt" id="reply_qtt"></em></span>
					<p class="_text" id="acmp_title">
					</p>
				</div>

				<div class="after_desc">

					<h3 class="_tit">contents</h3>
					<p class="_text" id="acmp_content"></p>
					
					<div id="is-reply-exist">
						
					</div>
				</div>

			</li>
		</ul>
		<div class="comments_group" id="reply_list">
			
		</div>
	</div>
	<div class="rereply-form">
		<p id="reply_acmp_user"></p>
		<p id="reply_acmp_content"></p>
		<button class="reply-cancel" onclick="reply_cancel();">X</button>
	</div>
	<div class="bt-fixed-msg">
		<input type="hidden" id="reply_to"/>
		<input type="text" id="cmt_write_input" class="bt-fixed-msg__input" placeholder="내용을 입력하세요.">
		<button type="button" class="bt-fixed-msg__btn" onclick="sendReply();">
			<b class="none" >전송</b>
		</button>
	</div>
</div>
<template id="reply_template">
	<div class="comment_list">
		<input type="hidden" id="reply_acmp"/>
		<figure class="_thumb"></figure>
		<div class="_bubble">
			<h5 class="_name" id="name"></h5>
			<p class="_text" id="acmp_rep_content">
			
			</p>
		</div>
		<div class="_status">
			<span class="_status_time" id="created_at"></span>
			<button type="button" class="_status_recomment" id="rereply_btn" onclick="first_cmt()">
				답글달기
			</button>
		</div>
		<!-- 답글 -->
		<div class="recomment_group" id="rereply_list">
		</div>
	</div>
</template>
<template id="rereply_template">
	<div class="recomment_list">
		<figure class="_thumb"></figure>
		<div class="_bubble">
			<h5 class="_name" id="name"></h5>
			<p class="_text" id="acmp_rep_content">
			</p>
		</div>
		<div class="_status">
			<span class="_status_time" id="created_at"></span>
		</div>
	</div>
</template>
@endsection

@section('script')
<style lang="scss">
	.rereply-form{
		position:absolute;
		bottom: 5.5rem;
		margin:0 auto;
		background:#ECF1F5;
		font-size:0.8em;
		transition: all 1s;
		height: 0;
	    width: 100%;
	}
	.rereply-form.active{
		transition: all 1s;
		padding: 2rem 3em 2rem 2rem;
		height:auto;
	}
	.rereply-form p:nth-child(1){
		font-size:15px;
		font-weight:bold;
		margin-bottom:1em;
	}
	.rereply-form p:nth-child(2){
		margin-bottom:1em;
	}
	.reply-cancel{
	    position: absolute;
	    right: 1em;
	    top: 1em;
	    background: transparent;
	    font-size: 1.2em;
	    border: transparent;
	}
</style>
<script>
	function getUrlParameter(name) {
	    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
	    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
	    var results = regex.exec(location.search);
	    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
	};
	// 댓글 달기 누르면, 입력창에 focus 감
	function first_cmt(acmp_rep_id) {

		$('.rereply_form').addClass('active');
		$('#cmt_write_input').focus();
		$('#reply_to').val(acmp_rep_id);
		$('.rereply-form').addClass('active');
		$('#reply_acmp_user').text($('#reply_acmp-'+acmp_rep_id).attr('data-name'));
		$('#reply_acmp_content').text($('#reply_acmp-'+acmp_rep_id).attr('data-content'));
		$('.rereply-form').css('height');
		
	}
	// end
	function reply_cancel(){
		$('.rereply-form').removeClass('active');
		$('#reply_to').val('');
		$('#reply_acmp_user').text('');
		$('#reply_acmp_content').text('');
		
	}

	function after_recomment_view(name) {

		$(name).css('display', 'none');
		$(name).next('.after_recmt').slideDown();

	}

	// 자세한내용 보기
	function after_desc_view(name) {
		var after_desc_status = $(name).next('.after_desc').css('display');
		$(name).next('.after_desc').stop().slideDown(200);

		if (after_desc_status == 'block') {
			$(name).next('.after_desc').stop().slideUp(200);
		}
	}
	function sendReply(){
		var params = '';
		if($('#reply_to').val() == ''){
			//대댓글을 위한 ID가 없을 때 -> 일반 댓글
			params = {
				'req' : 'acmp_rep',
				'acmp_rep_content' : $('#cmt_write_input').val(),
				'acmp_id' : getUrlParameter('acmp_id')
			}
		}else{
			params = {
				'req' : 'acmp_rep',
				'acmp_rep_parent' : $('#reply_to').val(),
				'acmp_rep_content' : $('#cmt_write_input').val(),
				'acmp_id' : getUrlParameter('acmp_id')
			}
		}
		
		$.ajax({
			url : "/api/acmp",
			data : params,
			method : "POST",
			dataType : "json"
		}).done(function(res) {
			//정상회신
			if (res.state == 1) {

				//alert('등록되었습니다');
				//location.reload();
			} else {
				//회신오류

			}
		}).fail(function(xhr, status, errorThrown) {

			console.log(xhr);
		});
	}
	$(function() {
		var isacmpLoading = false;
		var isreplyLoading = false;
		var replystart = 0;
		
		
		function loadList() {

			if (isacmpLoading) {
				return;
			}

			isacmpLoading = true;

			var params = {
				'acmp_id' : getUrlParameter('acmp_id')
			};
			$.ajax({
				url : "/api/acmp/detail",
				data : params,
				method : "GET",
				dataType : "json"
			}).done(function(res) {
				//정상회신
				if (res.state == 1) {

					var acmp = res.query[0];
					$('#name').text(acmp.name);
					$('#acmp_title').text(acmp.acmp_title);
					$('#acmp_content').text(acmp.acmp_content);
					$('#view_cnt').text(acmp.view_cnt);
					$('#reply_qtt').text(acmp.reply_qtt);
					$('#created_at').text(acmp.created_at);
					
					if(acmp.reply_qtt == 0){
						var reply_state = '<div class="comment_status">\
							<span class="comment_status_ment">아직 작성된 댓글이 없습니다.</span>\
						</div>';
						$('#is-reply-exist').append(reply_state);
					}else{
						var reply_state = '<div class="_status_recomment">\
							댓글 <em>'+acmp.reply_qtt+'</em>개\
						</div>';
						$('#is-reply-exist').append(reply_state);
					}
				} else {
					//회신오류

				}
			}).fail(function(xhr, status, errorThrown) {

				console.log(xhr);
			})
			.always(function(xhr, status) {
				isacmpLoading = false;
			});
		}
		function loadReply() {

			if (isreplyLoading) {
				return;
			}

			isreplyLoading = true;

			var params = {
				'acmp_id' : getUrlParameter('acmp_id'),
				'offset' : replystart
			};
			$.ajax({
				url : "/api/acmp/replist",
				data : params,
				method : "GET",
				dataType : "json"
			}).done(function(res) {
				//정상회신
				if (res.state == 1) {
					for (var i = 0; i < res.query.length; i++) {
						var reply = res.query[i];
						var template = $($('#reply_template').html());
						template.find('#acmp_rep_content').text(reply.acmp_rep_content);
						template.find('#name').text(reply.name);
						template.find('#created_at').text(reply.created_at);
						if(reply.rereply_qtt != 0){
							template.find('#rereply_list').html('<div class="before_recmt" onclick="after_recomment_view(this)">\
								<figure class="_thumb"></figure>\
								<span class="_sentence"><em id="rereply_qtt">'+reply.rereply_qtt+'</em>개의 댓글이 있습니다</span>\
							</div>\
							<div class="after_recmt" id="rereply_detail'+reply.acmp_rep_id+'">\
							</div>');
						}
						template.find('#rereply_detail').attr('id','rereply_detail'+reply.acmp_rep_id);
						template.find('#rereply_btn').attr('onclick','first_cmt('+reply.acmp_rep_id+')');
						template.find('#reply_acmp').attr('data-name',reply.name);
						template.find('#reply_acmp').attr('data-content',reply.acmp_rep_content);
						template.find('#reply_acmp').attr('id','reply_acmp-'+reply.acmp_rep_id);
						$('#reply_list').append(template);
						loadRereply(reply.acmp_rep_id);
					}
					replystart += res.query.length;
				} else {
					//회신오류

				}
			}).fail(function(xhr, status, errorThrown) {

				console.log(xhr);
			})
			.always(function(xhr, status) {
				isreplyLoading = false;
			});
		}
		function loadRereply(idx) {
			var params = {
				'acmp_rep_id' : idx
			};
			$.ajax({
				url : "/api/acmp/rereplist",
				data : params,
				method : "GET",
				dataType : "json"
			}).done(function(res) {
				//정상회신
				if (res.state == 1) {
					for (var i = 0; i < res.query.length; i++) {
						var rereply = res.query[i];
						var template = $($('#rereply_template').html());
						template.find('#name').text(rereply.name);
						template.find('#acmp_rep_content').text(rereply.acmp_rep_content);
						template.find('#created_at').text(rereply.created_at);
						$('#rereply_detail'+idx).append(template);
					}
				} else {
					//회신오류

				}
			}).fail(function(xhr, status, errorThrown) {

				console.log(xhr);
			})
			.always(function(xhr, status) {
			});
		}
		loadList();
		loadReply();
	}); 
</script>
@endsection