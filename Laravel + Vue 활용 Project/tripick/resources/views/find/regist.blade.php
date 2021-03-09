@extends('layouts.app')

@section('content')

<div class="wrapper wrapper--find">

	<div class="hd-title hd-title--03">
		<button type="button" class="hd-title__left-btn hd-title__left-btn--prev-wh" onClick="location.href='/find'">
			<span class="none">홈으로 가기</span>
		</button>
		<h2 class="hd-title__center">동행객 찾기 등록</h2>
	</div>

	<div class="regist-form">
		<input id="acmp_title" type="text" placeholder="제목"/>
		<textarea id="acmp_content" rows="15" placeholder="내용"></textarea>
	</div>
	<button class="regist-btn" id="acmp_regist_btn">
		전송
	</button>
</div>
@endsection

@section('script')
<script>
	function getUrlParameter(name) {
	    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
	    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
	    var results = regex.exec(location.search);
	    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
	};
	
	$(function() {
		$('#acmp_regist_btn').click(function(){
			var params = {
				'req' : 'acmp',
				'acmp_title' : $('#acmp_title').val(),
				'acmp_content' : $('#acmp_content').val()
			}
			$.ajax({
				url : "/api/acmp",
				data : params,
				method : "POST",
				dataType : "json"
			}).done(function(res) {
				//정상회신
				if (res.state == 1&& res.query !=null) {
					dialog.alert({
			            title:'시스템',  
			            message: '등록되었습니다',
			            button: "확인",
			            callback: function(value){
			                location.href='/find';
			            }
			        });
					
				} else {
					//회신오류

				}
			}).fail(function(xhr, status, errorThrown) {

				console.log(xhr);
			})
		});
	}); 
</script>
<style lang="scss">
	.regist-form{
		width: 90%;
	    padding: 2rem 0 0;
	    height: calc(100% - 5.5rem);
	    margin:0 auto;
	}
	.regist-form input{
		width:100%;
		padding:0.5em;
		margin-bottom:1em;
		border-radius:0.5em;
		border:1px solid #8c8c8c;
		font-size:0.8em;
	}
	.regist-form textarea{
		width:100%;
		padding:0.5em;
		border-radius:0.5em;
		border:1px solid #8c8c8c;
		font-size:0.8em;
		resize:none;
	}
	.regist-btn{
	    max-width: 1024px;
	    width: 100%;
	    height: 5.5rem;
	    background-color: #00BBDA;
	    color:#fff;
	    position: fixed;
	    bottom: 0;
	    z-index: 5;
	    font-size: 1.6rem;
	    border:none;
	}
	
</style>
@endsection