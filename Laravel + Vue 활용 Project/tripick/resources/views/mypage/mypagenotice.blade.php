@extends('layouts.app')

@section('content')

<div class="wrapper wrapper--mypage">
    
    <div class="hd-title hd-title--01">
        <button type="button" onClick="history.back()" class="hd-title__left-btn hd-title__left-btn--prev"><span class="none">이전버튼</span></button>
        <h2 class="hd-title__center">트리픽 공지사항</h2>
    </div>

    <div class="wrapper--mypage__scroll-area">
        <ul class="notice_group" id="notice_group">
            @forelse($notices as $notice)
            <li class="notice_list">
                <div class="_panel">
                    <h5 class="_tit">{{ $notice->notice_title }}</h5>
                    <span class="_date">{{ $notice->created_at }}</span>
                </div>
                <div class="_contents">
                    <p>{{ $notice->notice_content }}</p>
                </div>
            </li>
            @empty
            <li class="notice_list">
                공지사항이 없습니다.
            </li>
            @endforelse
            
        </ul>
    </div>
</div>

<template id="tplt_notice_list">
    <li class="notice_list">
        <div class="_panel">
            <h5 class="_tit" name="notice_title">{{ $notice->notice_title }}</h5>
            <span class="_date" name="notice_created_at">{{ $notice->created_at }}</span>
        </div>
        <div class="_contents" name="notice_content">
            <p>{{ $notice->notice_content }}</p>
        </div>
    </li>
</template>

@include('nav.nav_user')

@endsection

@section('script')
<script>
    $(function(){
        $('.wrapper--mypage__scroll-area').bind('scroll',function(){
            if($(this).scrollTop() + $(this).innerHeight() == $(this)[0].scrollHeight){
                loadNotices(NoticeStart);
                console.log('test');
            }
        });
        // 공지사항 리스트 클릭할때 화살표 변하고, 안에 내용 보이기
        $('#notice_group .notice_list').click(function(){

            var contents = $(this).find('._contents').css('display');

            $(this).find('._contents').slideDown(200);
            $(this).addClass('is-show');

            if( contents == 'block' ){
                $(this).find('._contents').slideUp(200);
                $(this).removeClass('is-show');
            }

        });
        //end
    });

var NoticeStart = 10;
function loadNotices(_start) {
    $.ajax({
        url: "/api/notice/list",
        data: {
            offset: _start,
        },
        method: "GET",
        dataType: "json",
        async:false
    })
    .done(function(res) {
        if (res.state == 1) {
            
            for (var i = 0; i < res.query.length; i++) {
                var tplt_clone = $($('#tplt_notice_list').html());
                var notice = res.query[i];

                tplt_clone.find('[name=notice_title]').html(notice.notice_title);
                tplt_clone.find('[name=notice_created_at]').html(notice.created_at);
                tplt_clone.find('[name=notice_content]').html(notice.content);
                

                $('#notice_group').append(tplt_clone);
            }
            NoticeStart += res.query.length;
            $('#notice_group .notice_list').unbind();
            $('#notice_group .notice_list').bind('click',function(){

                var contents = $(this).find('._contents').css('display');

                $(this).find('._contents').slideDown(200);
                $(this).addClass('is-show');

                if( contents == 'block' ){
                    $(this).find('._contents').slideUp(200);
                    $(this).removeClass('is-show');
                }

            });
            
        } else {
            //회신오류
        }
    })
    .fail(function(xhr, status, errorThrown) {
        console.log(xhr);
    }) // 
    .always(function(xhr, status) {});
}
</script>
@endsection