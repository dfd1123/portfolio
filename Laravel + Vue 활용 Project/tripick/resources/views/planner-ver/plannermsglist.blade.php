@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--msg">
    <div class="hd-title hd-title--04">
        <button type="button" onClick="history.back()" class="hd-title__left-btn hd-title__left-btn--prev"><span
                class="none">이전버튼</span></button>
        <h2 class="hd-title__center">메세지</h2>
        <div class="hd-title__sch">
            <div class="hd-title__sch__bar user-msg-sch-bar">
                <input type="text" placeholder="플래너 검색">
                <div class="user-msg-sch__btns">
                    <button type="button" class="user-msg-sch__btn user-msg-sch__btn--msg"><b
                            class="none">메세지함</b></button>
                    <button type="button" onClick="popupOpen('msg_store_view_popup')"
                        class="user-msg-sch__btn user-msg-sch__btn--store"><b class="none">보관함</b></button>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper--msg__scroll-area">
        <ul class="user-message__group" id="msg-list">
        </ul>
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

@include('nav.nav_planner')
@endsection

@section('script')
<script>
// 편집모드 설정
function editMode(name){
    $('#'+name).addClass('is-edit-mode');
}
//end

//편집모드 해제
function editModeExit(name){
    $('#'+name).removeClass('is-edit-mode');
}

$(function() {
    
    var msgstart = 0;
    var ismsgLoading = false;
    //메세지목록 
    function loadList(_start) {

        if(ismsgLoading){
            return;
        }

        ismsgLoading= true;

        var params = {
            'offset': _start
        };
        $.ajax({
                url: "/api/msg/plnrinbox",
                data: params,
                method: "GET",
                dataType: "json"
            }).done(function(res) {
                //정상회신
                if (res.state == 1) {

                    for (var i = 0; i < res.query.length; i++) {
                        var message = res.query[i];

                        var content = (message.content);
                        var msgdetail = "/pln_ver/msg/detail?eb_id=" + message.eb_id;
                        if(message.eb_id ==null){
                            msgdetail = "/pln_ver/msg/detail?prd_id=" + message.prd_id;
                        }
                        
                        //msgdetail+="&sender="+message.msg_sender;

                        var msg = ' <a href ="'+msgdetail+'" ><li  class="user-list user-message__list">\
                        <div class="user-message__card">\
                        <figure class="user-list__thum user-message__thum is-active" style="background-image: url(/storage/fdata/user/thumb/'+message.user_thumb+');"></figure>\
                        <h5 class="user-list__name">\
                        <b>' + message.name + '</b>\
                        </h5>\
                        <span class="user-list__date">' + message.created_at + '</span>\
                        <p class="user-list__msg">'+message.content+'</p>';

                        if (message.msgunread > 0) {
                            msg += '<span class="user-list__badge">' + message.msgunread + '</span>';
                        }
                        msg += '</div> </li> </a>';

                        $('#msg-list').append(msg);
                    }
                    msgstart += res.query.length;

                } else {
                    //회신오류

                }
            })
            .fail(function(xhr, status, errorThrown) {

                console.log(xhr);
            }) // 
            .always(function(xhr, status) {ismsgLoading  =false;});
    }
    loadList(msgstart);


    //메세지 무한스크롤
    $('.wrapper--msg__scroll-area').bind('scroll', function() {
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            loadList(msgstart);
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
            'offset': _start
        };
        $.ajax({
                url: "/api/msg/saved",
                data: params,
                method: "GET",
                dataType: "json"
            }).done(function(res) {
                //정상회신
                if (res.state == 1) {

                    for (var i = 0; i < res.query.length; i++) {
                        var message = res.query[i];

                        var judi = JSON.parse(message.judi_area);

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




});

</script>
@endsection