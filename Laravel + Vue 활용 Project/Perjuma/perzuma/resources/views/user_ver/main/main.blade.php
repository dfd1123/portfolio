@extends('user_ver.layouts.app') 
@section('content')
<div class="main_btn_li_wrap">
    <div class="btn_li_content">
        <div class="main_btn_li fadeInUp animated delay-02s" onclick="location.href='/user_ver/estimate_request/1'">
            <div>
                <div class="icon_btn_check_off">
                    <b>시공 견적 요청</b><span>새로운 견적 요청</span>
                </div>
            </div>
        </div>
        <div class="main_btn_li fadeInUp animated delay-04s" onclick="location.href='/user_ver/ask_estimate'">
            <div>
                <div class="icon_btn_request_off">
                    <b>요청 견적 리스트</b><span>요청한 견적 내용 확인</span>
                </div>
            </div>
        </div>
        <div class="main_btn_li fadeInUp animated delay-06s" onclick="location.href='/user_ver/construct_status'">
            <div>
                <div class="icon_btn_estimate_off">
                    <b>시공 현황 보기</b><span>진행중인 시공현황 확인</span>
                </div>
            </div>
        </div>
    </div>
    <div class="deco_bottom_img">
        <img src="{{asset('/images/bg_main_kitchen.svg')}}" class="bg_main_kitchen" alt="">
        <div class="perzuman_icon">
            <div>
                <img src="{{asset('/images/perzuman_basic.svg')}}" class="perzuman_basic bounceIn animated delay-1s" alt="">
                <img src="{{asset('/images/perzuman_name.svg')}}" class="perzuman_name fadeInLeft animated delay-1_3s" alt="">
            </div>
        </div>
        <div class="empty_bottom"></div>
    </div>
</div>

<div class="recomend_company" style="display:none;">
    <h2>퍼주마 Manager 업체 추천</h2>
    <div id="recommend_slider" class="swiper-container">
        <ul class="swiper-wrapper">

        </ul>
    </div>
</div>

<template id="recomend_company_li">
    <li class="swiper-slide">
        <img src="" class="swiper-lazy" alt="">
        <p></p>
    </li>
</template>

<script>

    $.ajax({
            type : "GET",
            dataType: "json",
            url : "/api/recomend_company",
            success : function(data) {

                var wrapper = $('#recommend_slider>ul');
                wrapper.html('');
                if(data.recommend_agents.length != 0){
                    data.recommend_agents.forEach(function(item){
                        var templete = $($('#recomend_company_li').html());

                        if(item.agent_profile_img != null){
                            var profile_img = JSON.parse(item.agent_profile_img);
                            templete.find('img').attr('src', '/storage/fdata/user/thumb'+profile_img.profile_img);
                            templete.css('background-image', 'url(/storage/fdata/user/thumb'+profile_img.profile_img+')');
                        }
                        
                        templete.attr("onclick", "location.href='/user_ver/company_page/estimate_view?agent_no="+item.agent_no+"'");
                        templete.find('p').text(item.agent_name);
                        wrapper.append(templete);
                    });
                    
                    if(data.recommend_agents.length < 3){
                        var swiper = new Swiper('#recommend_slider', {
                            pagination: '.swiper-pagination',
                            slidesPerView: 3,
                            centeredSlides: false,
                            paginationClickable: false,
                            spaceBetween: 10,
                            initialSlide: 1,
                        });
                    }else{
                        var swiper = new Swiper('#recommend_slider', {
                            pagination: '.swiper-pagination',
                            slidesPerView: 3,
                            centeredSlides: true,
                            paginationClickable: false,
                            spaceBetween: 10,
                            initialSlide: 2,
                        });
                    }

                    $('.recomend_company').show();
                }else{
                    $('.recomend_company').hide();
                }
                

            },
            error:function(request,status,error){
                
            }
        });

        $.ajax({
            type : "GET",
            dataType: "json",
            url : "/api/user/as",
            success : function(data) {
                

            },
            error : function(data){
                swal({
                    title: "네트워크 오류",
                    text: "잠시 후 다시 시도해주세요.",
                    button: "확인",
                });
            }
        });

    

</script>

<style>
    #content{
        padding: 3.2em 0;
        padding-bottom:0;
        min-height: 100%;
    }
</style>

@endsection