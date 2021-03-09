@extends('user_ver.layouts.app') 
@section('content')
<input type="hidden" name="agent_no" value="{{$agent_no}}" />
<input type="hidden" name="trd_no" value="{{$trd_no}}" />
<div class="company_review_wrap animated fadeIn">
    <div class="company_review_content">
        <div class="company_review_hd">
                <div class="hd_left">
                    <img src="" id="company_profile_img" alt="">
                </div>
                <div class="hd_right">
                    <h2 id="company_name"></h2>
                    <ul>
                        <li>
                            시공 <span id="construction_cnt">0</span>건
                        </li>
                        <li>
                            리뷰 <span id="review_cnt">0</span>건
                        </li>
                    </ul>
                    <div>
                        <img src="" id="star_img_rating" alt=""><span><em id="rating">0.0</em>/5</span>
                    </div>
                </div>
        </div>
    </div>
</div>

<template id="review_li">
    <div class="review_li wow fadeInUp delay-02s" data-rv="">
        <div class="review_infor">
            <img src="{{asset('/images/star/star_rating_3.0.svg')}}" class="star_rate" alt="">
            <span class="created_at"></span>
            <span class="writer_name"></span>
        </div>
        <div class="review_body">
            <h4 id="review_title"></h4>
            <div class="review_contents">
                
            </div>
            <div class="slider_wrap" style="background:#ddd;">
                <div class="review_slider" class="swiper-container">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-paginate" style="position: absolute;z-index:10;"></div>
                </div>
            </div>
            <div class="auth_update_btn">
                <button type="button" id="review_edit" data-rv="">수정</button>
                <button type="button" id="review_delete" data-rv="">삭제</button>
            </div>
        </div>
    </div>
</template>


<script>
    var agent_no = $('input[name="agent_no"]').val();
    var trd_no = $('input[name="trd_no"]').val();

    $.ajax({
        type : "POST",
        dataType: "json",
        data : {agent_no : agent_no, trd_no : trd_no},
        url : "/api/review_view",
        success : function(data) {

            if(data.write_yn){
                $('#content').append('<div class="btn_review"><img src="/images/btn_comment.svg" alt="btn_comment"></div>');
            }

            var profile_img = JSON.parse(data.agent.agent_profile_img);

            console.log(profile_img);

            var rating = Math.round(data.agent.agent_rating).toFixed(1);

            $('#company_name').text(data.agent.agent_name);
            if(data.agent.agent_profile_img == null){
                $('#company_profile_img').attr("src", "/images/agent_default_profile.png");
            }else{
                $('#company_profile_img').attr("src", "/storage/fdata/agent/thumb"+profile_img.profile_img+"");
            }
            
            $('#construction_cnt').text(numberWithCommas(data.agent.agent_construction_cnt));
            $('#review_cnt').text(numberWithCommas(data.agent.agent_review_cnt));

            $('#star_img_rating').attr("src","/images/star/star_rating_"+rating+".svg");
            $('#rating').text(rating);

            if(data.reviews.length > 0){
                $('.company_review_content').append('<div class="company_review_box" data-offset="'+data.offset+'" data-count="'+data.reviews_cnt+'"></div>');

                var company_review_box = $('.company_review_box');

                data.reviews.some(function(review, index) {
                    if(index >= 9) return false;
                    var reivew_rating = parseFloat(review.rv_point).toFixed(1);;
                    var templete = $($('#review_li').html());
                    templete.data('rv', review.rv_no);
                    templete.find('.star_rate').attr('src', '/images/star/star_rating_'+reivew_rating+'.svg');
                    templete.find('.created_at').text(moment(review.reg_dt).format('YYYY.MM.DD hh:mm'));
                    templete.find('.writer_name').text(review.name);
                    templete.find('#review_title').text(review.rv_title);
                    templete.find('.review_contents').html(review.rv_content);
                    if(review.auth_no == review.client_no){
                        templete.find('#review_edit').data('rv', review.rv_no);
                        templete.find('#review_delete').data('rv', review.rv_no);
                    }else{
                        templete.find('.auth_update_btn').remove();
                    }
                    
                    if(review.rv_imgs == null){
                        templete.find('.slider_wrap').remove();
                    }else{
                        templete.find('.swiper-wrapper').html('');
                        var rv_imgs = JSON.parse(review.rv_imgs);
                        rv_imgs.forEach(function(rv_img, index, array) {
                            templete.find('.swiper-wrapper').append('<div class="swiper-slide"><img src="/storage/fdata/trade/review'+rv_img+'" class="swiper-lazy" alt="" /></div>');
                            templete.find('.swiper-slide').css('background-image','url(/storage/fdata/trade/review'+rv_img+')');
                        });
                        

                        new Swiper('.review_slider', {
                            loop: true,
                            pagination: {
                                el: '.swiper-paginate',
                                dynamicBullets: true,
                            }
                        });

                    }
                    company_review_box.append(templete);
                });
            }else{
                $('.company_review_content').append('<div class="non_data wow fadeIn">등록된 리뷰가 없습니다.</div>');
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

    $(document).on('click', '#review_edit', function(){
        var this_elem = $(this);
        var rv_no = this_elem.data('rv');

        $.ajax({
            type : "GET",
            url : "/api/user_ver/review/"+rv_no+"", 
            dataType: 'json',
            success : function(data) {

                var rating = parseFloat(data.review.rv_point).toFixed(1);

                var img_index = 0;
                if(data.rv_imgs != null){
                    Object.keys(data.rv_imgs).forEach(function(key, index, array) {
                        var real_index = parseInt(index)+1;
                        $('#preview_comment'+real_index).removeClass('default_img');
                        $('#preview_comment'+real_index).attr('src', '/storage/fdata/trade/review'+data.rv_imgs[key]);
                        img_index = real_index;
                    });

                    img_index += 1;
                    $('#first_img_addbtn label').data('index',img_index);
                    $('#first_img_addbtn label').attr('for', 'commnet_img'+img_index);
                }

                $('#rating_zip .starRev span').removeClass('on');
                $('#rating_zip .starRev span[data-rating="'+rating+'"]').addClass('on').prevAll('span').addClass('on');
                
                $('#title').text(data.review.rv_title);
                $('#contents').text(data.review.rv_content);

                $('input[name="rating"]').val(rating);
                $('.rating_score_box em').text(rating);

                $('#review_hd').text('리뷰 수정');
                $('#review_ft').text('수정');

                $('input[name="rv_no"]').val(rv_no);

                $('.re_comment_wrap').addClass('active');
                $('#content').css('z-index','50');
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

    $(document).on('click', '#review_delete', function(){
        var this_elem = $(this);
        var rv_no = this_elem.data('rv');

        swal({
            title: "리뷰 삭제",
            text: "해당 리뷰를 삭제하시겠습니까?",
            buttons: {
                yes: {
                    text: "예",
                    value: true,
                },
                no: {
                    text: "아니오",
                    value: false,
                },
            },
        })
        .then((value) => {
            if(value){
                $.ajax({
                    type : "DELETE",
                    url : "/api/user_ver/review/"+rv_no+"", 
                    dataType: 'json',
                    success : function(data) {
                        if(data.status){
                            history.go(0);
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
    });

    
    $(document).on('click', '.btn_review', function(){
        $('.re_comment_wrap').addClass('active');
        $('#content').css('z-index','50');
    });

    $('.comment_box .comment_hd span').on('click', function(){
        $('.re_comment_wrap').removeClass('active');
        $('#content').css('z-index','1');

        var rv_no = $('input[name="rv_no"]').val();

        if(rv_no != ''){
            $.ajax({
                type : "DELETE",
                url : "/api/user_ver/review/"+rv_no+"", 
                dataType: 'json',
                success : function(data) {
                    if(data.status){
                        $('#title').text('');
                        $('#contents').text('');
                        $('.re_comment_wrap .img_wrap>img').attr('src','/images/default_add_img.png');
                        $('.re_comment_wrap .img_wrap>img').addClass('default_img');
                        $('input[name="rv_no"]').val('');
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

    $('#app').scroll(function() {
        var ceil = Math.ceil($('#content').outerHeight() - $('#app').height());
        var round = Math.round($('#content').outerHeight() - $('#app').height());
        if ($('#app').scrollTop() == ceil || $('#app').scrollTop() == round) {
            var offset = $('.company_review_box').data('offset');
            var count = $('.company_review_box').data('count');

            if(offset != count){
                $.ajax({
                    type : "GET",
                    url : "/api/user_ver/review?agent_no="+agent_no+"&offset="+offset+"&req=more_list", 
                    dataType: 'json',
                    success : function(data) {
                        var company_review_box = $('.company_review_box');

                        data.reviews.some(function(review, index) {
                            var reivew_rating = parseFloat(review.rv_point).toFixed(1);;
                            var templete = $($('#review_li').html());
                            templete.data('rv', review.rv_no);
                            templete.find('.star_rate').attr('src', '/images/star/star_rating_'+reivew_rating+'.svg');
                            templete.find('.created_at').text(moment(review.reg_dt).format('YYYY.MM.DD hh:mm'));
                            templete.find('.writer_name').text(review.name);
                            templete.find('#review_title').text(review.rv_title);
                            templete.find('.review_contents').html(review.rv_content);
                            if(review.auth_no == review.client_no){
                                templete.find('#review_edit').data('rv', review.rv_no);
                                templete.find('#review_delete').data('rv', review.rv_no);
                            }else{
                                templete.find('.auth_update_btn').remove();
                            }
                            
                            if(review.rv_imgs == null){
                                templete.find('.slider_wrap').remove();
                            }else{
                                templete.find('.swiper-wrapper').html('');
                                var rv_imgs = JSON.parse(review.rv_imgs);
                                rv_imgs.forEach(function(rv_img, index, array) {
                                    templete.find('.swiper-wrapper').append('<div class="swiper-slide"><img src="/storage/fdata/trade/review'+rv_img+'" class="swiper-lazy" alt="" /></div>');
                                    templete.find('.swiper-slide').css('background-image','url(/storage/fdata/trade/review'+rv_img+')');
                                });
                                

                                new Swiper('.review_slider', {
                                    loop: true,
                                    pagination: {
                                        el: '.swiper-paginate',
                                        dynamicBullets: true,
                                    }
                                });

                            }
                            company_review_box.data('offset', data.offset);

                            company_review_box.append(templete);
                        });
                    },
                    error : function(data){
                        
                    }
                });
            }
        }
    });

    
</script>


@endsection