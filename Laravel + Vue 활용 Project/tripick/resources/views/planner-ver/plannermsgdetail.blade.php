@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--msg wrapper--msg-view">
    <div class="user-msg-alarm">
        <p class="user-msg-alarm__text">메세지가 전송되었습니다. 답변을 기다려주세요.</p>
        <span class="user-msg-alarm__btn"><b class="none">닫기버튼</b></span>
    </div>
    <div class="hd-title hd-title--01">
        <button type="button" onClick="history.back()" class="hd-title__left-btn hd-title__left-btn--prev"
            onClick="history.back();"><span class="none">이전버튼</span></button>
        <button type="button" onClick="popupOpen('msg_store_view_popup')"
            class="hd-title__right-btn hd-title__right-btn--store"><span class="none">보관함버튼</span></button>
        <h2 class="hd-title__center">
            <b id= "usr-name" class="user-message__name"></b>
        </h2>
    </div>

    <div class="wrapper--msg__scroll-area">
        <div class="user-msg-view">
            <ul id="msg-list" class="user-msg-view__group">

            <li style="display :none;" class="user-msg-view__list user-msg-view__list--send">
                    <figure class="user-list__thum"></figure>
                    <div class="user-msg-view__bubble user-msg-view__bubble--final">
                        <div class="final-card">
                            <h4 class="final-card__title">최종 결제 요청</h4>
                            <ul class="final-card__info">
                                <li class="final-card__info__list">
                                    <h5 class="final-card__info__label">상품 내용</h5>
                                    <span class="final-card__info__line"><br></span>
                                    <span class="final-card__info__line">총 원 </span>
                                </li>
                                <li class="final-card__info__list">
                                    <h5 class="final-card__info__label">최종 금액</h5>
                                    <span class="final-card__info__price">225,000원</span>
                                </li>
                                <li class="final-card__btns">
                                    <button type="button" class="final-card__btn">결제하기</button>
                                </li>
                            </ul>
                        </div>
                        <div class="user-msg-view__date">
                            <span></span>
                        </div>
                    </div>
                </li>
                
            </ul>
        </div>
    </div>

    <div class="rsrv-regist-section">
    	
    </div>
    <div class="bt-fixed-msg">
    <form method="POST" action="/api/msg" id="msgform" style="height: 100%;">
            <input id="receiver" name="msg_users" type="hidden">
            <input id="req" name="req" type="hidden">
            <input id="prd_id" name="prd_id" value="0" type="hidden">
            <input id="eb_id" name="eb_id" value="0" type="hidden">
            <input id="input-msg" name="msg_content" type="text" class="bt-fixed-msg__input" placeholder="내용을 입력하세요.">

        </form>
        <button type="button" class="bt-fixed-msg__btn"><b class="none">전송</b></button>
    </div>

</div>

<div class="popup popup--msg wrapper--msg-store" id="msg_store_view_popup">

    <div class="hd-title hd-title--01">
        <button type="button" onClick="popupClose('msg_store_view_popup')"
            class="hd-title__left-btn hd-title__left-btn--prev is-not-edit"><span class="none">이전버튼</span></button>
        <!-- 편집모드 누르면 wrapper--msg-store에 is-edit-mode 클래스추가 -->
        <button type="button" onClick="editMode('msg_store_view_popup')"
            class="hd-title__right-btn hd-title__right-btn--edit is-not-edit"><span class="none">편집모드</span></button>
        <button type="button" onClick="editModeExit('msg_store_view_popup')"
            class="hd-title__right-btn hd-title__right-btn--close is-edit"><span class="none">편집나가기</span></button>
        <h2 class="hd-title__center is-not-edit">보관함</h2>
        <h2 class="hd-title__center is-edit">보관함 편집</h2>
    </div>

    <div class="wrapper--msg__scroll-area">
        <ul class="user-message__group" id="saved-list">

        </ul>
        <div class="user-message__btns is-edit">
            <span class="user-message__btns__amount">총 <em>1</em>개</span>
            <button type="button" class="button">삭제하기</button>
        </div>
    </div>

</div>

<div class="popup popup--modal">

    <div class="popup__inner" id="request_pay_popup">

        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>결제 요청</h3>
		<input type="hidden" id="user_id"/>
        <fieldset class="popup__inner__field">

            <span class="inline inline--left">추천 상품 금액을 입력해주세요.</span>

            <span class="request-pay__input-group">
                <input type="text" class="request-pay__input" placeholder="금액을 입력해주세요." id="rsrv_price" onkeyup="inputNumberFormat(this)">
                <span class="request-pay__unit">원</span>
            </span>

            <button type="button" class="button button--disable is-active button--relative mg_tb_20" id="rsrvc_regist">입력완료</button>

        </fieldset>

    </div>

</div>
<template id="rsrv_is_not_exist">
	<button id="rsrv_regist_btn"><img src="/img/btn/btn-overview.svg"/>최종 결제 요청</button>\
	<p>* 여행객에게 맞춤가격으로 견적을 요청할 수 있습니다</p>
</template>
<template id="rsrv_is_exist">
	<div class="user-msg-view__bubble user-msg-view__bubble--final">
        <div class="final-card">
            <h4 class="final-card__title">최종 결제 요청</h4>
            <ul class="final-card__info">
            	<li class="final-card__info__list">
                    <h5 class="final-card__info__label">상품 내용</h5>
                    <span class="final-card__info__line" id="prd_title"></span>
                </li>
                <li class="final-card__info__list">
                    <h5 class="final-card__info__label">최종 금액</h5>
                    <span class="final-card__info__price" id="rsrv_price"></span>
                </li>
            </ul>
        </div>
    </div>
</template>
<template id="rsrv_is_complete">
	<div class="user-msg-view__bubble user-msg-view__bubble--final">
        <div class="final-card">
            <h4 class="final-card__title">결제 완료</h4>
            <ul class="final-card__info">
            	<li class="final-card__info__list">
                    <h5 class="final-card__info__label">상품 내용</h5>
                    <span class="final-card__info__line" id="prd_title"></span>
                </li>
                <li class="final-card__info__list">
                    <h5 class="final-card__info__label">최종 금액</h5>
                    <span class="final-card__info__price" id="rsrv_price"></span>
                </li>
            </ul>
        </div>
    </div>
</template>
@endsection

@section('script')
<script>
function inputNumberFormat(obj) {
    obj.value = comma(uncomma(obj.value));
}

function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

function uncomma(str) {
    str = String(str);
    return str.replace(/[^\d]+/g, '');
}
function replaceAll(str, searchStr, replaceStr) {
  return str.split(searchStr).join(replaceStr);
}

function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
};

var msgStart = 0;


var params = {};
if (getUrlParameter('prd_id') > 0) {
    params.prd_id = getUrlParameter('prd_id');
    $('#req').val('plnrprd');
    $('#prd_id').val(getUrlParameter('prd_id'));
} else if (getUrlParameter('eb_id') > 0) {
    params.eb_id = getUrlParameter('eb_id');
    $('#req').val('plnrestimate');
    $('#eb_id').val(getUrlParameter('eb_id'));
}
$('#receiver').val(getUrlParameter('sender'));

$(document).on('click','#rsrv_regist_btn',function(){
	$('.popup--modal').delay(200).fadeIn();
	$('.popup__inner').addClass('is-active');
});
$(document).on('click','#rsrvc_regist',function(){
	$('.popup--modal').delay(200).fadeOut();
	$('.popup__inner').removeClass('is-active');
	var params = {
		type : getUrlParameter('prd_id')>0 ? getUrlParameter('prd_id') : getUrlParameter('eb_id'),
		reg_type : getUrlParameter('prd_id')>0 ? 0 : 1,
		rsrv_price : replaceAll($('#rsrv_price').val(),',',''),
		user_id : $('#user_id').val() 
	}
	$.ajax({
        url: "/api/reserve",
        data: params,
        method: "POST",
        dataType: "json"
    }).done(function(res) {
        //정상회신
        if (res.state == 1) {
			dialog.alert({
                title:'시스템',  
                message: '최종견적을 보냈습니다',
                button: "확인"
            });
			location.reload();
          } else {
          	dialog.alert({
                title:'서버오류',  
                message: '최종견적 전송오류',
                button: "확인"
            });
            //회신오류
        }
    }).fail(function(xhr, status, errorThrown) {
    }).always(function(xhr, status) {
        isSavedLoading = false;
    });
});
function loadMsg(_start) {
    params.offset = _start;

    $.ajax({
            url: "/api/msg/msglist",
            data: params,
            method: "GET",
            dataType: "json"
        }).done(function(res) {
            //정상회신
            if (res.state == 1) {
				$('#user_id').val(res.query[0].user_id);
				$('#receiver').val(res.query[0].user_id);
                for (var i = 0; i < res.query.length; i++) {
                    var message = res.query[i];

                    var where = message.me === message.msg_sender ? "send" : "receive";
					var thumb = message.me === message.msg_sender ? "/storage/fdata/planner/thumb/"+message.pln_thumb : "/storage/fdata/user/thumb/"+message.user_thumb;
                    var msg = '<li class="user-msg-view__list user-msg-view__list--' + where + '">\
                    <figure class="user-list__thum" style="background-image: url('+thumb+');"></figure>\
                    <div class="user-msg-view__bubble">\
                        <p class="user-msg-view__bubble-text">' + message.msg_content + ' </p>\
                        <div class="user-msg-view__date">';
                    //받은메세지만 저장가능
                    if (where === 'receive') {
                        msg += '<button type="button" data-msg="'+message.msg_id+'" name="msg-store-btn" class="user-msg-view__store">\
                                <b class="none">보관</b> </button>\
                            <em class="_caution">보관함에 저장되었습니다.</em>';
                    }
                    msg += '<span>'+message.created_at+'</span> </div>  </div> </li>';
                    $('#msg-list').append(msg);
                }
                
                msgStart += res.query.length;

              } else {
                //회신오류
            }
        })
        .fail(function(xhr, status, errorThrown) {
        }) // 
        .always(function(xhr, status) {
            isSavedLoading = false;
        });
}

    loadMsg(msgStart);
    //아래로 내리면 추가로딩
    $('.wrapper--msg__scroll-area').bind('scroll', function() {
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
        loadMsg(msgStart);

        }
    });



 //저장메세지 목록
 var savedStart =0;
    var isSavedLoading = false;
    function loadSaved(_start) {
        if(isSavedLoading){
            return;
        }
        isSavedLoading= true;

        var params = {
            'start': _start
        };
        $.ajax({
                url: "/api/msg/savedplnr",
                data: params,
                method: "GET",
                dataType: "json"
            }).done(function(res) {
                //정상회신
                console.log(res);
                if (res.state == 1) {

                    for (var i = 0; i < res.query.length; i++) {
                        var message = res.query[i];

                        var msg = ' <li class="user-list user-message__list">\
                        <div class="user-message__card">\
                        <figure class="user-list__thum user-message__thum is-active" style="background-image: url(img/example/profile-jq.jpg);"></figure>\
                        <h5 class="user-list__name">\
                        <b>' + message.pln_name + '</b>\
                        </h5>\
                        <span class="user-list__date">' + message.created_at + '</span>\
                        <p class="user-list__msg">' + message.msg_content + '</p>';

                        if (message.msgunread > 0) {
                            msg += '<span class="user-list__badge">' + message.msgunread + '</span>';
                        }
                        msg += '</div> </li>';

                        $('#saved-list').append(msg);
                    }
                    savedStart += res.query.length;

                } else {
                    //회신오류
                }
            })
            .fail(function(xhr, status, errorThrown) {

                console.log(xhr);
            }) // 
            .always(function(xhr, status) {isSavedLoading  =false;} );
        
    }
    //저장메세지 무한스크롤
    $('.wrapper--msg__scroll-area').bind('scroll', function() {
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            loadSaved(savedStart);
        }
    });
    
    loadSaved(savedStart);





// 편집모드 설정
function editMode(name) {
    $('#' + name).addClass('is-edit-mode');
}
//end

//편집모드 해제
function editModeExit(name) {
    $('#' + name).removeClass('is-edit-mode');
}
//end

//보관함으로 보내는 버튼 눌렀을 때 ui
$(document).on('click', '[name=msg-store-btn]',function() {

    var params  = {'msg_id' : $(this).data('msg')};
    var button  =$(this);
    $.ajax({
            url: "/api/msg/save",
            data: params,
            method: "PUT",
            dataType: "json"
        }).done(function(res) {
            //정상회신
            console.log(res);
            if (res.state == 1) {

                if(res.query.length>0){
                    button.siblings('._caution').show().delay(800).fadeOut(200);
                }
              
            } else {
                //회신오류
            }
        })
        .fail(function(xhr, status, errorThrown) {
        }) // 
        .always(function(xhr, status) {
        });
});
//end
function leadingZeros(n, digits) {
  var zero = '';
  n = n.toString();

  if (n.length < digits) {
    for (i = 0; i < digits - n.length; i++)
      zero += '0';
  }
  return zero + n;
}
function getTimeStamp() {
  var d = new Date();
  var s =
    leadingZeros(d.getDate(), 2) + '일' +
    leadingZeros(d.getHours(), 2) + ':' +
    leadingZeros(d.getMinutes(), 2); 

  return s;
}
$(function() {
	//메세지 읽음 처리
	$.ajax({
		url: "/api/msg/read",
		data: params,
		method: "PUT",
		dataType: "json"
	}).done(function(res) {
	  if (res.state == 1) {
		}
	});
	
	$.ajax({
		url: "/api/msg/whoBypln",
		data: params,
		method: "GET",
		dataType: "json"
	}).done(function(res) {
	  if (res.state == 1) {
	  		$('#usr-name').text(res.query[0].name);
		}
	});
});
//전송 버튼 눌렀을 때 ui
$('.bt-fixed-msg__btn').click(function(e) {

    $("#msgform").ajaxForm({
        url: "/api/msg",
        //contentType : 'application/x-www-form-urlencoded'
        dataType: "json",
        error: function(p1, p2, p3) {
            alert('서버통신 오류발생');
        },
        success: function(res) {
            var msg = '<li class="user-msg-view__list user-msg-view__list--send"> <figure class="user-list__thum"></figure>\
                <div class="user-msg-view__bubble"><p class="user-msg-view__bubble-text">'+$('#input-msg').val()+'</p> \
                <div class="user-msg-view__date"><span>'+getTimeStamp()+'</span></div></div> </li>';

            $('#msg-list').append(msg);

            if (res.state ==1 && res.query[0].msg_id > 0) {
                $('.user-msg-alarm').stop().animate({
                    top: 4.5 + 'rem',
                    opacity: 1
                }).delay(800).animate({
                    top: 0,
                    opacity: 0
                })
                $('#input-msg').empty();
            }else{
                dialog.alert({
                    title:'서버오류',  
                    message: res.msg,
                    button: "확인"
                });
            }

        }
    });
    $("#msgform").submit();

});

function sendMsg(_content, done, fail) {
    params.msg_content = _content;
    $.ajax({
            url: "/api/msg",
            data: params,
            method: "POST",
            dataType: "json"
        }).done(done)
        .fail(fail) // 
        .always(function(xhr, status) {
            isSavedLoading = false;
        });
    }

//end
function rsrv_is_exist(){
	var params = {
		type : getUrlParameter('prd_id')>0 ? getUrlParameter('prd_id') : getUrlParameter('eb_id'),
		reg_type : getUrlParameter('prd_id')>0 ? 0 : 1
	}
	$.ajax({
        url: "/api/reserve/pln_is_exist",
        data: params,
        method: "GET",
        dataType: "json"
    }).done(function(res) {
        //정상회신
        if (res.state == 1) {
			console.log(res);
			if(res.state == 1 && res.query.length > 0){
				if(res.query[0].state == 0){
					var template = $($('#rsrv_is_exist').html());
					template.find('#rsrv_price').text(res.query[0].rsrv_price+' 원');
					if(res.query[0].prd_title != undefined){
						template.find('#prd_title').text(res.query[0].prd_title);
					}else{
						template.find('#prd_title').text('입찰건');
					}
					$('.rsrv-regist-section').append(template);
				}else{
					var template = $($('#rsrv_is_complete').html());
					template.find('#rsrv_price').text(res.query[0].rsrv_price);
					if(res.query[0].prd_title != undefined){
						template.find('#prd_title').text(res.query[0].prd_title);
					}else{
						template.find('#prd_title').text('입찰건');
					}
					$('.rsrv-regist-section').append(template);
				}
				
			}
			else if(res.state == 1 && res.query.length == 0){
				var template = $($('#rsrv_is_not_exist').html());
				$('.rsrv-regist-section').append(template);
			}
          } else {
          	dialog.alert({
                title:'서버오류',  
                message: '최종견적 로드오류',
                button: "확인"
            });
        }
    })
    .fail(function(xhr, status, errorThrown) {
    }) // 
    .always(function(xhr, status) {
        isSavedLoading = false;
    });
}
rsrv_is_exist();
</script>
<style lang="scss">
	.rsrv-regist-section{
		max-width:1024px;
		width:100%;
		position:fixed;
		bottom:7em;
		text-align:center;
		width:inherit;
	}
	.rsrv-regist-section button{
		padding:1em;
		background-color:#fff;
		border-radius:3em;
		box-shadow: 0px 3px 8px 0px #8c8c8c;
    	border: none;
	}
	.rsrv-regist-section img{
		width:2em;
		margin-right:1em;
	}
	.rsrv-regist-section p{
		margin-top:1em;
		font-size:0.8em;
		color:#4E4B40;
	}
	.rsrv-regist-section .user-msg-view__bubble{
		width:100%;
		margin-bottom:0;
	}
	.rsrv-regist-section .user-msg-view__bubble .final-card{
		background-color:#fff;
	}
</style>
@endsection