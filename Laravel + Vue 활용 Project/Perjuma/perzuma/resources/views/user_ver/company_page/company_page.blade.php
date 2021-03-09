@extends('user_ver.layouts.app') 
@section('content')
<input type="hidden" name="agent_no" value="{{$agent_no}}" />
<input type="hidden" name="trd_no" value="{{$trd_no}}" />
<div class="company_page_wrap  animated fadeIn">
    <div class="company_page_content">
        <div class="company_page_box">
            <div class="company_profile_img">
                @if($agent_profile_img == '/default_profile.png')
                    <img src="/images/default_profile.png" alt="">
                @else
                    <img src="/storage/fdata/agent/thumb{{$agent_profile_img}}" alt="">
                @endif
            </div>
            <div class="company_infor_div">
                <div>
                    <h2 id="company_name">{{$agent->agent_name}}</h2>
                    <ul>
                        <li>
                            <b id="construction_cnt">{{$agent->agent_construction_cnt}}</b>
                            <span>시공횟수</span>
                        </li>
                        <li>
                            <b id="review_cnt">{{$agent->agent_review_cnt}}</b>
                            <span>전체리뷰</span>
                        </li>
                        <li>
                            <b id="rating"><i class="fas fa-star"></i>{{$agent->agent_rating}}</b>
                            <span>평점</span>
                        </li>
                    </ul>
                    @if($agent->agent_tel_number)
                        <a href="tel:{{$agent->agent_tel_number}}" id="agent_tel_number"><i class="fas fa-phone-alt"></i>시공업체와의 전화</a>
                    @else
                        <a href="#" onclick="no_tel_number()" id="agent_tel_number"><i class="fas fa-phone-alt"></i>시공업체와의 전화</a>
                    @endif
                </div>
            </div>
            @if($trd_no != null)
                <div class="estimate_price">
                    <span>시공업체 견적</span>
                    <span id="estimate_price">{{number_format($agent->asking_price)}}원</span>
                </div>
                <div class="information_div">
                    <h3>전달 사항</h3>
                    @if($agent->agt_others == NULL)
                        <p id="others">전달 사항이 없습니다.</p>
                    @else
                        <p id="others">{{$agent->agt_others}}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<template id="company_page_content">
    <div class="company_page_content">
        <div class="company_page_box">
            <div class="company_profile_img">
                {{$agent_profile_img.'awd'}}
                @if($agent_profile_img != NULL)
                    <img src="/images/default_profile.png" alt="">
                @else
                    <img src="/storage/fdata/agent/thumb{{$agent_profile_img}}" alt="">
                @endif
            </div>
            <div class="company_infor_div">
                <div>
                    <h2 id="company_name">{{$agent->agent_name}}</h2>
                    <ul>
                        <li>
                            <b id="construction_cnt">{{$agent->agent_construction_cnt}}</b>
                            <span>시공횟수</span>
                        </li>
                        <li>
                            <b id="review_cnt">{{$agent->agent_review_cnt}}</b>
                            <span>전체리뷰</span>
                        </li>
                        <li>
                            <b id="rating"><i class="fas fa-star"></i>{{$agent->agent_rating}}</b>
                            <span>평점</span>
                        </li>
                    </ul>
                    @if($agent->agent_tel_number)
                        <a href="tel:{{$agent->agent_tel_number}}" id="agent_tel_number"><i class="fas fa-phone-alt"></i>시공업체와의 전화</a>
                    @else
                        <a href="#" onclick="no_tel_number()" id="agent_tel_number"><i class="fas fa-phone-alt"></i>시공업체와의 전화</a>
                    @endif
                </div>
            </div>
            @if($trd_no != null)
                <div class="estimate_price">
                    <span>시공업체 견적</span>
                    <span id="estimate_price">{{number_format($agent->asking_price)}}원</span>
                </div>
                <div class="information_div">
                    <h3>전달 사항</h3>
                    @if($agent->agt_others == NULL)
                        <p id="others">전달 사항이 없습니다.</p>
                    @else
                        <p id="others">{{$agent->agt_others}}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</template>

@if($ft_btn_yn)
    <div class="ft_button">
        <button type="button" class="{{($state == 1 || $state == 4)?'active':''}} {{($state == 4)?'complete_req':''}}">{{$ft_btn_name}}</button>
    </div>
@endif



<style>
    #content{
        padding-top: 5.8em;
        background: #f6f6f6;
    }
</style>

<script>

var agent_no = $('input[name="agent_no"]').val();
var trd_no = $('input[name="trd_no"]').val();

$('.ft_button>button').on("click", function(){
    if($(this).hasClass('active')){
        if($(this).hasClass('complete_req')){
            swal({
                title: "완료 신청",
                text: "시공 완료 처리를 하시겠습니까?",
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
                        type : "PUT",
                        dataType: "json",
                        data : { agent_no : agent_no, trd_no : trd_no, req : 'complete_req' },
                        url : "/api/construction_contract",
                        success : function(data) {
                            if(data.status){
                                location.href="/user_ver/estimate_manage?id="+trd_no+"";
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
                }
            });
        }else{
            swal({
                title: "시공계약",
                text: "해당 업체와 시공계약을 하시겠습니까?",
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
                        type : "PUT",
                        dataType: "json",
                        data : { agent_no : agent_no, trd_no : trd_no, req : 'construct_req' },
                        url : "/api/construction_contract",
                        success : function(data) {
                            if(data.status){
                                location.href="/user_ver/estimate_manage?id="+trd_no+"";
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
                }
            });
        }
    }
});
/*
$.ajax({
    type : "POST",
    dataType: "json",
    data : {agent_no : agent_no, trd_no : trd_no},
    url : "/api/estimate_view",
    success : function(data) {
        var template = $($('#company_page_content').html());
        var company_page_wrap = $('.company_page_wrap');
        template.find('#review_view em').text(data.agent.agent_review_cnt);
        $('#review_view em').text(data.agent.agent_review_cnt);
        
        if(data.agent.agent_profile_img == null){
            template.find('.company_profile_img>img').attr('src', '/images/default_profile.png');
        }else{
            template.find('.company_profile_img>img').attr('src', '/storage/image/company'+data.agent.agent_profile_img+'');
        }

        template.find('#company_name').text(data.agent.agent_name);
        template.find('#construction_cnt').text(data.agent.agent_construction_cnt);
        template.find('#review_cnt').text(data.agent.agent_review_cnt);
        template.find('#rating').html('<i class="fas fa-star"></i>'+parseFloat(data.agent.agent_rating).toFixed(1));
        if(data.agent.agent_tel_number != null){
            template.find('#agent_tel_number').attr('href','tel:'+data.agent.agent_tel_number+'');
            template.find('#agent_tel_number').attr('onclick', false);
        }
        template.find('#estimate_price').text(numberWithCommas(data.agent.asking_price)+'원');

        if(data.agt_others == null){
            template.find('#others').html('전달 사항이 없습니다.');
        }else{
            template.find('#others').html(data.agt_others);
        }

        company_page_wrap.append(template);

    },
    error : function(data){
        swal({
            title: "네트워크 오류",
            text: "잠시 후 다시 시도해주세요.",
            button: "확인",
        });
    }
});
*/

function no_tel_number(){
    swal({
        title: "알림",
        text: "업체 전화번호가 등록되어 있지 않습니다.",
        button: "확인",
    });
}
</script>


@endsection