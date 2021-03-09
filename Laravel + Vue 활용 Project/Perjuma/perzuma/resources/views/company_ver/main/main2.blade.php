@extends('company_ver.layouts.app') 
@section('content')
<div class="corporation_status_wrap">
    <input type="hidden" name="ucc_no" value="" />
    <div class="corporation_status_content">
        <div class="corporation_hd">
            <div class="corporation_left">
                <div class="circle_bg"></div>
                <img src="{{asset('/images/perzuman_induction.svg')}}" alt="perzuman_induction">
            </div>
            <div class="corporation_right">
                <div style="display:inline-block;margin:1em 0;">
                    <div class="yellow_crown">
                        <img src="{{asset('/images/btn_comment.svg')}}" style="width:100%;height:100%;"/>
                    </div>
                    @if($is_premium ==0)
                    <p class="state_div">미사용</p>
                    @elseif($is_premium ==1)
                    <p class="state_div">제공중</p>
                    @endif
                </div>
                <h5>퍼주맨 프리미엄 서비스</h5>
                <div>
                    <p>고객명 : {{$client_name}}</p>
                    <p>연락처 : {{$client_contact}}</p>
                    <p>E-mail : {{$client_email}}</p>
                </div>
            </div>
        </div>
        <div class="corporation_body">
            <div class="corporation_body_wrap">
                <div class="corporation_box">
                    <div class="comment_hd">
                        <div class="active">나의 코멘트</div>
                        <div>유저 코멘트</div>
                    </div>
                    <div class="comment_wrap">
                        <ul class="com_list_ul company_comment">
                            @forelse($agent_comments as $agent_comment)
                                <li {{($agent_comment->confirm)?'':'class=new'}} data-ucc="{{$agent_comment->ucc_no}}">
                                    <h3>{{$agent_comment->ucc_title}}</h3>
                                    <span>{{date("Y.m.d H:i:s", strtotime($agent_comment->reg_dt))}}</span>
                                    <p>{{$agent_comment->ucc_comment}}</p>
                                </li>
                            @empty
                                <li class="non_data">등록된 업체 코멘트가 없습니다.</li>
                            @endforelse
                        </ul>
                        <ul class="com_list_ul user_comment" style="display:none;">
                            @forelse($client_comments as $client_comment)
                                <li {{($client_comment->confirm)?'':'class=new'}}  data-ucc="{{$client_comment->ucc_no}}">
                                    <h3>{{$client_comment->ucc_title}}</h3>
                                    <span>{{date("Y.m.d H:i:s", strtotime($client_comment->reg_dt))}}</span>
                                    <p>{{$client_comment->ucc_comment}}</p>
                                </li>
                            @empty
                                <li class="non_data">등록된 유저 코멘트가 없습니다.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="btn_comment">
    <img src="{{asset('/images/btn_comment.svg')}}" alt="btn_comment">
</div>
@include('company_ver.main.comm_regist')

<template id="comment_li">
    <li>
        <h3></h3>
        <span></span>
        <p></p>
    </li>
</template>

<script>
    $(document).on("click", ".com_list_ul li", function(){
        var this_li = $(this);
        var ucc_no = this_li.data('ucc');

        $.ajax({
            type : "GET",
            url : "/api/user_ver/comment/"+ucc_no+"", 
            dataType: 'json',
            success : function(data) {
                if(data.status){
                    var message_box = $('#message_box');
                    var templete = $($('#message_content').html());

                    if(data.user_trd_comment.client_no == null){
                        templete.find('h5').text('업체 코멘트');
                    }else{
                        templete.find('h5').text('유저코멘트');
                    }
        
                    templete.find('#msg_title').text(data.user_trd_comment.ucc_title);
                    templete.find('#msg_send_dt').text(moment(data.user_trd_comment.reg_dt).format('YYYY-MM-DD hh:mm'));
                    templete.find('#msg_content').text(data.user_trd_comment.ucc_comment);

                    if(data.user_trd_comment.ucc_imgs !== null && data.user_trd_comment.ucc_imgs !== '[]'){
                        templete.find('#message_slider .swiper-wrapper').html('');
                        JSON.parse(data.user_trd_comment.ucc_imgs).forEach(function(ucc_img, index, array) {
                            var real_index = parseInt(index)+1;
                            templete.find('.swiper-wrapper').append('<div class="swiper-slide"><img src="/storage/fdata/trade/comment'+ucc_img+'" class="swiper-lazy" alt="" /></div>');
                            templete.find('.swiper-slide').css('background-image','url(/storage/fdata/trade/comment'+ucc_img+')');
                        });

                    }else{
                        templete.find('#message_slider').remove();
                    }

                    message_box.html(templete);

                    var message_slider = document.getElementById('message_slider');
                        console.log(message_slider);

                    var message_swiper = new Swiper(message_slider, {
                            loop: true,
                            pagination: {
                                el: '.swiper-paginate',
                                dynamicBullets: true,
                            }
                        });

                    $('.message_wrap').addClass('active');

                    this_li.removeClass('new');
                }else{
                    swal({
                        title: "네트워크 오류",
                        text: "잠시 후 다시 시도해주세요.",
                        button: "확인",
                    });
                }
            },
            error : function(data){
                swal({
                    title: "네트워크 오류",
                    text: "잠시 후 다시 시도해주세요.",
                    button: "확인",
                });
            }
        });
    });

    $('.btn_comment').on('click', function(){
        $('.re_comment_wrap').addClass('active');
    });

    $('.comment_box .comment_hd span').on('click', function(){
        $('.re_comment_wrap').removeClass('active');

        var ucc_no = $('input[name="ucc_no"]').val();

        if(ucc_no != ''){
            $.ajax({
                    type : "DELETE",
                    url : "/api/user_ver/comment/"+ucc_no+"", 
                    dataType: 'json',
                    success : function(data) {
                        if(data.status){
                            $('#title').text('');
                            $('#contents').text('');
                            $('.re_comment_wrap .img_wrap>img').attr('src','/images/default_add_img.png');
                            $('.re_comment_wrap .img_wrap>img').addClass('default_img');
                            $('input[name="ucc_no"]').val('');
                        }
                    },
                    error : function(data){
                        swal({
                            title: "네트워크 오류",
                            text: "잠시 후 다시 시도해주세요.",
                            button: "확인",
                        });
                    }
                });
        }
    });

    $('.corporation_body .corporation_body_wrap .corporation_box .comment_hd>div:first-child').on("click", function(){
        $('.comment_wrap ul').hide();
        $('.comment_wrap ul.company_comment').show();
        $('.corporation_body .corporation_body_wrap .corporation_box .comment_hd>div').removeClass('active');
        $(this).addClass('active');
    });

    $('.corporation_body .corporation_body_wrap .corporation_box .comment_hd>div:last-child').on("click", function(){
        $('.comment_wrap ul').hide();
        $('.comment_wrap ul.user_comment').show();
        $('.corporation_body .corporation_body_wrap .corporation_box .comment_hd>div').removeClass('active');
        $(this).addClass('active');
    });
</script>
@include('company_ver.layouts.footer')
<style>
    #content{
        padding:1.5em 0;
        padding-top: 4.05em;
        min-height: 100%;
        background: #fff;
    }

    .corporation_status_wrap .corporation_status_content .corporation_hd{
        position: relative;
        padding: 1em 3%;
        background:#fff;
        height: 170px;
    }

    .corporation_status_wrap .corporation_status_content .corporation_hd .corporation_left{
        position: absolute;
        left: 3%;
        top: 1em;
        z-index: 2;
        width: 140px;
        height: 140px;
        padding: 4px;
    }

    .corporation_status_wrap .corporation_status_content .corporation_hd .corporation_left img{
        width: 100%;
        padding: 0 1.3em;
        margin-top: -12px;
        box-sizing: border-box;
    }

    .corporation_status_wrap .corporation_status_content .corporation_hd .corporation_left .circle_bg{
        background-color: rgba(0, 123, 210, 0.08);
        width: 132px;
        height: 132px;
        border-radius: 50%;
        position: absolute;
        top: 4px;
        left: 4px;
        z-index: -1;
    }

    .corporation_status_wrap .corporation_status_content .corporation_hd .corporation_right{
        padding-left:150px;
    }

    .corporation_status_wrap .corporation_status_content .corporation_hd .corporation_right h5{
        font-size: 0.9em;
        font-weight: 700;
        letter-spacing: -0.5px;
        color: #4f5256;
        margin-bottom: 0.65em;
    }

    .corporation_status_wrap .corporation_status_content .corporation_hd .corporation_right>div{
        font-size: 0.8em;
        line-height: 1.34;
        letter-spacing: -0.44px;
        text-align: left;
        color: #4f5256;
    }

    .corporation_status_wrap .corporation_status_content .corporation_hd .corporation_right>div span{
        font-weight:700;
        color:#007bd2;
    }

    .corporation_status_wrap .corporation_status_content .corporation_hd .corporation_right>button{
        border-radius: 50px;
        box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.17);
        background-color: #ffffff;
        border: none;
        font-size: 0.7em;
        line-height: 2.29;
        letter-spacing: -0.9px;
        text-align: center;
        color: #007bd2;
        padding: 0 1em;
        float: right;
        margin-top: 1em;
        max-height: 25px;
    }

    .corporation_status_wrap .corporation_status_content .corporation_hd .corporation_right>button i{
        font-size: 0.6em;
        margin-left:0.3em;
    }

    .corporation_body{
        position:relative;
    }

    .corporation_body .corporation_body_wrap{
        position: relative;
        z-index: 2;
        width: 100%;
        margin-top: -2em;
        padding: 0 3%;
    }

    .corporation_body .corporation_body_wrap .corporation_box{
        border-radius:6px;
        overflow:hidden;
        background:#fff;
        box-shadow: 0px 13px 16px 0 rgba(36, 36, 36, 0.08);
    }

    .corporation_body .corporation_body_wrap .corporation_box .box_hd{
        padding:0.6em 1em;
        background-color: #007bd2;
        position:relative;
    }

    .corporation_body .corporation_body_wrap .corporation_box .box_hd h2{
        color:#fff;
    }

    .corporation_body .corporation_body_wrap .corporation_box .box_hd>div{
        position: absolute;
        top: 0.8em;
        right: 1em;
        z-index: 2;
        font-size: 0.3em;
        color: #007bd2;
        background: #fff;
        width: 2em;
        height: 2em;
        padding: 0.5em 0;
        text-align: center;
        box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.16);
        border: solid 1px rgba(0, 0, 0, 0);
    }

    .corporation_body .corporation_body_wrap .corporation_box .corporation_li{
        position: relative;
        min-height: 70px;
        border-bottom: 1px solid #d1d1d1;
    }

    .corporation_body .corporation_body_wrap .corporation_box .corporation_li:last-child{
        border-bottom:none;
    }

    .corporation_body .corporation_body_wrap .corporation_box .corporation_li .corporation_li_left{
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
        padding: 1em;
    }

    .corporation_body .corporation_body_wrap .corporation_box .corporation_li .corporation_li_left>img{
        width: 62px;
        height: 35px;
        vertical-align: middle;
    }

    .corporation_body .corporation_body_wrap .corporation_box .corporation_li .corporation_li_left>span{
        padding: 0px 11px;
        display: inline-block;
        vertical-align: middle;
        font-size: 0.7em;
    }

    .corporation_body .corporation_body_wrap .corporation_box .corporation_li .corporation_li_left>span b{
        display: block;
        font-size: 1.2em;
        margin-bottom: 4px;
    }

    .corporation_body .corporation_body_wrap .corporation_box .corporation_li .corporation_li_left>span em{
        
    }

    .corporation_body .corporation_body_wrap .corporation_box .corporation_li .corporation_li_right{
        width: 100%;
        padding-left: 149px;
        min-height: 52px;
        padding-right: 1em;
        position: relative;
    }

    .corporation_body .corporation_body_wrap .corporation_box .corporation_li .corporation_li_right .progress_box{
        height: 4px;
        width: 46%;
        border-radius: 5px;
        background-color: #e9e9e9;
        position: absolute;
        top: 32px;
        left: 158px;
    }

    .corporation_body .corporation_body_wrap .corporation_box .corporation_li .corporation_li_right .progress_box .progress_bar{
        width:0%;
        height:4px;
        border-radius: 5px;
        background-color: #00a2ff;
        transition:width 0.5s;
        transition-delay:1s;
    }

    .corporation_body .corporation_body_wrap .corporation_box .corporation_li .add_comment{
        max-width: 100%;
        margin: 0 1em;
        border-radius: 6.5px;
        background-color: #e9e9e9;
        padding: 0 0.8em;
        line-height: 1.5;
        letter-spacing: -0.4px;
        text-align: left;
        font-size: 0.8em;
        color: #5b5b5b;
    }

    .corporation_body .corporation_body_wrap .corporation_box .corporation_li .add_comment>div{
        padding: 0.5em 0;
        margin: 1em 0;
    }

    .corporation_body .corporation_body_wrap .corporation_box .corporation_li .add_comment>div p{
        line-height: 1.5;
        letter-spacing: -0.36px;
        color: #adb3bc;
        text-align: right;
    }

    .corporation_body .corporation_body_wrap .corporation_box .comment_hd{
        overflow:hidden;
    }

    .corporation_body .corporation_body_wrap .corporation_box .comment_hd>div{
        width: 50%;
        float: left;
        text-align: center;
        line-height: 1.39;
        letter-spacing: -0.56px;
        text-align: center;
        font-size: 1em;
        color: #4f5256;
        padding: 10px 0;
        border-bottom:1px solid #8e8e8e;
    }

    .corporation_body .corporation_body_wrap .corporation_box .comment_hd>div.active{
        border-bottom:2px solid #222;
        padding-bottom:9px;
        font-weight:700;
    }

    .corporation_body .corporation_body_wrap .corporation_box .comment_wrap{
        padding:1em;
        max-height:434px;
        overflow:auto;
    }

    .corporation_body .corporation_body_wrap .corporation_box .comment_wrap ul{
        
    }

    .corporation_body .corporation_body_wrap .corporation_box .comment_wrap ul li{
        padding:1.5em 1em;
        border-bottom:1px solid #ddd;
        background-color: #fff;
    }

    .corporation_body .corporation_body_wrap .corporation_box .comment_wrap ul li:nth-child(even){
        background-color: #f8f8f8;
    }

    .corporation_body .corporation_body_wrap .corporation_box .comment_wrap ul li h3{
        line-height: 1;
        letter-spacing: -0.5px;
        font-size:0.95em;
        color: #5d5d5d;
        text-overflow:ellipsis;
        white-space:nowrap;
        word-wrap:normal;
        width:100%;
        overflow:hidden;
    }

    .corporation_body .corporation_body_wrap .corporation_box .comment_wrap ul li.new h3{
        padding-left:21px;
        background:url('/images/icon_alarm_new.svg') no-repeat;
    }

    .corporation_body .corporation_body_wrap .corporation_box .comment_wrap ul li span{
        font-size: 0.8em;
        letter-spacing: -0.34px;
        color: #adadad;
        margin: 0.8em 0;
        display: block;
    }

    .corporation_body .corporation_body_wrap .corporation_box .comment_wrap ul li p{
        font-size: 0.8em;
        letter-spacing: -0.34px;
        color: #2b2b2b;
        text-overflow: ellipsis;
        white-space: nowrap;
        word-wrap: normal;
        width: 100%;
        overflow: hidden; 
    }

    .btn_comment{
        position: fixed;
        bottom: 7em;
        right: 1em;
        z-index: 4;
        width: 55px;
        height: 55px;
        box-shadow: 0 3px 14px 0 rgba(0, 0, 0, 0.23);
        background-color: #007bd2;
        border-radius: 50%;
        text-align: center;
        padding: 15px 0;
    }

    .btn_comment img{
        width: 27px;
    }
    .yellow_crown{
        width: 1.7em;
        height: 1.7em;
        background-color: #ffce00;
        border-radius: 10em;
        padding: 0.3em;
        display: inline-block;
        vertical-align:middle;
    }
    .state_div{
        display: inline-block;
        background-color: #007bd2;
        color: #fff;
        padding: 0.3em 0.5em;
        border-radius: 1em;
        font-size: 0.5em;
        vertical-align: middle;
    }
</style>
@endsection