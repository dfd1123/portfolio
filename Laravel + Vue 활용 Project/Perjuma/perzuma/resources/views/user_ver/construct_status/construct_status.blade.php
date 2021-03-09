@extends('user_ver.layouts.app') 
@section('content')

<div class="contruct_status_wrap">
    <div class="contruct_status_content">
        <div class="contruct_status_ul">
            
        </div>
    </div>
</div>

<template id="contruct_status_li">
    <div class="contruct_status_li wow fadeInUp delay-04s">
        <span class="request_date"></span>
        <h2></h2>
        <p class="estimate_infor"></p>
        <p class="option_infor">매니저 옵션 : <span></span></p>
        <a href="">상세보기<i class="fal fa-chevron-right"></i></a>
    </div>
</template>



<script>
    var contruct_status_ul = $('.contruct_status_ul');

    $.ajax({
        type : "GET",
        dataType: "json",
        url : "/api/construct_status/default/",
        success : function(data) {

            contruct_status_ul.data('offset', data.offset);
            contruct_status_ul.data('count', data.trades_cnt);


            if(data.trades.length != 0){

                data.trades.forEach(function(trade, index, array) {
                    var templete = $($('#contruct_status_li').html());
                    templete.find('.request_date').text('요청일자 : ' + moment(trade.created_at).format('YYYY.MM.DD'));

                    templete.find('.request_status').addClass('wait');
                    templete.find('h2').text(trade.trd_name);

                    if(trade.trd_area == 0){
                        trade.trd_area = '10평 미만';
                    }else if(trade.trd_area >= 100){
                        trade.trd_area = '100평 이상';
                    }else{
                        trade.trd_area += '평대';
                    }

                    if(trade.trd_budget == 0){
                        trade.trd_budget = '1,000만원 미만';
                    }else if(trade.trd_budget >= 10000){
                        trade.trd_budget = '1억원 이상';
                    }else{
                        trade.trd_budget = numberWithCommas(trade.trd_budget) + '만원 상당';
                    }

                    templete.find('.estimate_infor').text(trade.bl_name + " / " + trade.trd_area + " / " + trade.trd_budget);

                    if(trade.is_premium == 0){
                        trade.is_premium = '베이직';
                    }else if(trade.is_premium == 1){
                        trade.is_premium = '프리미엄';
                    }

                    templete.find('.option_infor').text("매니저 옵션 : " + trade.is_premium);
                    templete.find('a').attr('href','/user_ver/corporation_status/'+trade.trd_no+'');

                    contruct_status_ul.append(templete);
                });
            }else{
                $('.contruct_status_ul').html('<div class="non_data wow fadeIn">시공중인 리스트가 없습니다.</div>');
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

    $('#app').scroll(function() {
        var ceil = Math.ceil($('#content').outerHeight() - $('#app').height());
        var round = Math.round($('#content').outerHeight() - $('#app').height());
        if ($('#app').scrollTop() == ceil || $('#app').scrollTop() == round) {
            if(contruct_status_ul.data('offset') != contruct_status_ul.data('count')){
                $.ajax({
                    type : "GET",
                    dataType: "json",
                    url : "/api/construct_status/more/",
                    success : function(data) {

                        contruct_status_ul.data('offset', data.offset);

                        data.trades.forEach(function(trade, index, array) {
                            var templete = $($('#contruct_status_li').html());
                            templete.find('.request_date').text('요청일자 : ' + moment(trade.created_at).format('YYYY.MM.DD'));

                            templete.find('h2').text(trade.trd_name);

                            if(trade.trd_area == 0){
                                trade.trd_area = '10평 미만';
                            }else if(trade.trd_area >= 100){
                                trade.trd_area = '100평 이상';
                            }else{
                                trade.trd_area += '평대';
                            }

                            if(trade.trd_budget == 0){
                                trade.trd_budget = '1,000만원 미만';
                            }else if(trade.trd_budget >= 10000){
                                trade.trd_budget = '1억원 이상';
                            }else{
                                trade.trd_budget = numberWithCommas(trade.trd_budget) + '만원 상당';
                            }

                            templete.find('.estimate_infor').text(trade.bl_name + " / " + trade.trd_area + " / " + trade.trd_budget);

                            if(trade.is_premium == 0){
                                trade.is_premium = '베이직';
                            }else if(trade.is_premium == 1){
                                trade.is_premium = '프리미엄';
                            }

                            templete.find('.option_infor').html("매니저 옵션 : " + "<span>" + trade.is_premium + "</span>");
                            templete.find('a').attr('href','/user_ver/estimate_manage?id='+trade.trd_no+'');

                            contruct_status_ul.append(templete);
                        });
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
        }
    });
</script>

@endsection