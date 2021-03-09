@extends('user_ver.layouts.app') 
@section('content')
<input type="hidden" name="trd_no" value="{{$trd_no}}" />
<div class="estimate_manage_wrap">
    <div class="estimate_manage_content">
        <div class="estimate_manage_box">
            <div class="request_daedline" style="margin:0 0;">
                <span>견적 마감까지</span>
                <span><b id="hours">-</b>시간 <b id="minute">-</b>분 남음</span>
            </div>
            <div class="estimate_list_wrap">
                <div class="estimate_list_li es_li1">
                    
                </div>
                <div class="estimate_list_li es_li2">
                    
                </div>
                <div class="estimate_list_li  es_li3">
                    
                </div>
            </div>
            <div>
                <a href="#" class="go_btn">견적사항 관리</a>
            </div>
        </div>
    </div>
</div>

<template id="estimate_list_li">
        <div class="estimate_li"> 
            <div class="estimate_company_img">
                <img src="" id="company_img" alt="">
            </div>
            <div class="estimate_company_infor">
                <h3 id="company_name"></h3>
                <ul>
                    <li>시공 <span id="construction_cnt">0</span>건</li>
                    <li>리뷰 <span id="review_cnt">0</span>건</li>
                    <li><span><i class="fas fa-star"></i> <em id="rating">5.0</em></span></li>
                </ul>
                <p id="estimate_price"></p>
            </div>
            <div class="contract_kind">
                <span class="ok">계약 완료</span>
                <span class="wait">계약 대기</span>
                <span class="deposit_wait">입금 대기</span>
                <span class="end">시공 완료</span>
            </div>
        </div>
</template>

<script>
    var trd_no = $('input[name="trd_no"]').val();

    $.ajax({
        type : "POST",
        dataType: "json",
        data : {trd_no : trd_no},
        url : "/api/estimate_manage",
        success : function(data) {
            data.estimate_lists.forEach(function(item, index, array) {
                var real_index = parseInt(index)+1;
                var estimate_list_wrap = $('.es_li'+real_index+'');
                estimate_list_wrap.html("");
                var templete = $($('#estimate_list_li').html());
                
                templete.attr("onclick","location.href='/user_ver/company_page/estimate_view?agent_no="+item.agt_no+"'");

                if(data.trade.agent_no == item.agt_no){
                    templete.addClass('contract');
                    if(data.trade.state == 2){
                        templete.addClass('wait');
                    }else if(data.trade.state == 3){
                        templete.addClass('deposit_wait');
                    }else if(data.trade.state == 4){
                        templete.addClass('ok');
                    }else if(data.trade.state == 5){
                        templete.addClass('end');
                    }
                }else{
                    if(data.hours == 0 && data.minute == 0){
                        templete.addClass('disabled');
                    }
                }
                
                templete.find('#company_img').attr('src','/images'+item.agent_profile_img);
                templete.find('#company_name').text(item.agent_name);
                templete.find('#construction_cnt').text(numberWithCommas(item.agent_construction_cnt));
                templete.find('#review_cnt').text(numberWithCommas(item.agent_review_cnt));
                templete.find('#rating').text(parseFloat(item.agent_rating).toFixed(1));
                templete.find('#estimate_price').text(numberWithCommas(item.asking_price) + ' 원');

                estimate_list_wrap.append(templete);
            });

            $('.estimate_li.contract').siblings().addClass('disabled');

            $('#hours').text(data.hours);
            $('#minute').text(data.minute);

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


@endsection