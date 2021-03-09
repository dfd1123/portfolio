@extends('user_ver.layouts.app') 
@section('content')
<div class="corporation_status_wrap">
    <input type="hidden" name="ucc_no" value="" />
    <div class="corporation_status_content">
        <div class="corporation_hd">
            <div class="corporation_left">
                <div class="circle_bg"></div>
                <img src="{{asset('/images/perzuman_induction.svg')}}" alt="perzuman_induction">
            </div>
            @if($trade->is_premium)
                <div class="corporation_right">
                    <h5>퍼주맨 프리미엄 서비스</h5>
                        <div>
                            더 전문적인 시공이 필요할 때,<br>
                            <span>퍼주맨 프리미엄 서비스</span>를<br>
                            신청해보세요!
                        </div>
                        <button type="button">프리미엄 서비스 신청하기 <i class="fal fa-chevron-right"></i></button>
                </div>
            @else
                <div class="corporation_right">
                    <div class="ing_service_box">
                        <img src="http://perzuma.local/images/icon_premium.svg" alt="">
                        <span>이용중</span>
                    </div>
                    <h5 style="margin-top: 0.8em;">퍼주맨 프리미엄 서비스</h5>
                    <ul>
                        <li>
                            <b>퍼주맨:</b> <em>{{($trade->sp_name != NULL)?$trade->sp_name:'미배정'}}</em>
                        </li>
                        <li>
                            <b>연락처:</b> {{($trade->sp_name != NULL)?$trade->sp_name:'-'}}
                        </li>
                        <li>
                            <b style="letter-spacing: -0.6px;">E-Mail:</b> {{($trade->sp_email != NULL)?$trade->sp_email:'-'}}
                        </li>
                    </ul>
                </div>
            @endif
        </div>
        <div class="corporation_body">
            <div class="corporation_body_wrap">
                <div class="corporation_box">
                    <div class="comment_hd">
                        <div class="active">업체 코멘트</div>
                        <div>나의 코멘트</div>
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

@include('user_ver.comm_review.comment_write')

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

@endsection