@extends('company_ver.layouts.app') 
@section('content')
<script>
var start = 10;
$(function(){
    
});
function mybidding(){
        var param = {
            'offset' : start,
        };
        $.ajax({
            type : "GET",
            dataType: "json",
            data : param,
            url : "/api/trade/trdbidding",
            success : function(data) {
                console.log(data);
                if(data.state==1 && data.query!=null){
                    var items = data.query;
                    for(var i = 0; i< items.length;i++){
                        var template = $($('#reqlistitem').html());
                        if(items[i].state == 1){
                            template.find('.request_status').addClass('ing');
                            template.find('.request_status').text('견적 받는중');
                            template.attr("onclick","not_yet()");
                        }else if(items[i].state == 2){
                                template.find('.request_status').addClass('ok');
                                template.find('.request_status').text('시공요청');
                                template.attr("onclick","location.href='/company_ver/company_bidding_regist?trd_no='"+items[i].trd_no+"");
                        }
                        template.find('.request_date').text('요청일자 : '+items[i].created_at)
                        template.find('#trd_name').text(items[i].trd_name);
                        template.find('.estimate_infor').text(items[i].bl_name+' / '+items[i].trd_area+'평 / '+items[i].trd_budget+'만원');
                        $('.contruct_status_ul').append(template);
                        start++;
                    }
                }
                else{
                    alert(data.msg);
                }
            },
            error : function(data){
            }
        });
    }
function scrolling(){
    var scrollBottom = $(window).scrollTop() + window.innerHeight;
    if (scrollBottom == $(document).height()) {
        mybidding();
    }
}
$(window).scroll(function() {
    scrolling();
});
function not_yet(){
        swal({
            title: "알림",
            text: "아직 클라이언트가 업체 결정을 내리지 않았습니다.",
            button: "확인",
        });
    }
</script>
<div class="contruct_status_wrap">
    <div class="contruct_status_content">
        <div class="contruct_status_ul">
        @forelse($bidding_info as $item)
            @if($item->state == 1)
            <div class="contruct_status_li" onclick="javascript:not_yet();">
            @else
            <div class="contruct_status_li" onclick="location.href='/company_ver/company_bidding_regist?trd_no={{$item->trd_no}}';">
            @endif
                <span class="request_date">요청일자 : {{$item->created_at}}</span>
                @if($item->state == 1)
                <span class="request_status ing">견적 받는중</span>
                @elseif($item->state ==2)
                <span class="request_status ok">시공요청</span>
                @endif
                <h2>{{$item->trd_name}}</h2>
                <p class="estimate_infor">{{$item->bl_name}} / {{$item->trd_area}}평 / {{$item->trd_budget}}만원</p>
                @if($item->is_premium == 0)
                <p class="option_infor">매니저 옵션 : </p><span class="val">베이직</span>
                @else
                <p class="option_infor">매니저 옵션 : </p><span class="val">프리미엄</span>
                @endif
                <p class="asking_price">입찰가 : {{number_format($item->asking_price)}} 원</p>
            </div>
        @empty
            <div class="contruct_status_li">
                <h2>입찰 건이 없습니다</h2>
            </div>
        @endforelse
        </div>
    </div>
</div>
<template id="reqlistitem">
    <div class="contruct_status_li">
        <span class="request_date">요청일자 :</span>
        <span class="request_status"></span>
        <h2 id="trd_name"></h2>
        <p class="estimate_infor"></p>
        <p class="option_infor">매니저 옵션 : <span id="is_premium"></span></p>
    </div>
</template>
<style>
    #content{
        padding:3.3em 0 6em;
    }
    #sub_hd4{
        position:fixed;
    }

    .contruct_status_ul .contruct_status_li{
        padding:1.3em 1em;
        position:relative;
        border-bottom:1px solid #d7d7d7;
    }

    .contruct_status_ul .contruct_status_li>span.request_date{
        position:absolute;
        top:1.3em;
        right:1em;
        z-index:2;
        font-size:0.8em;
        line-height: 2.29;
        letter-spacing: -0.34px;
        text-align: left;
        color: #adadad;
    }

    .contruct_status_ul .contruct_status_li>span.request_status{
        display: block;
        width: 74px;
        height: 27px;
        font-size: 0.7em;
        line-height: 2.29;
        letter-spacing: -0.34px;
        text-align: center;
        border-radius: 58px;
        outline: none;
        margin-bottom: 5px;
    }

    .contruct_status_ul .contruct_status_li>span.request_status.wait{
        color: #007bd2;
        border: solid 1px #007bd2;
        background-color: #ffffff;
    }

    .contruct_status_ul .contruct_status_li>span.request_status.ok{
        color:#fff;
        border: solid 1px #df4900;
        background-color: #df4900;
    }

    .contruct_status_ul .contruct_status_li>span.request_status.ing{
        color:#fff;
        border: solid 1px #007bd2;
        background-color: #007bd2;
    }

    .contruct_status_ul .contruct_status_li>span.request_status.complete{
        color:#fff;
        border: solid 1px #d5dcea;
        background-color: #d5dcea;
    }

    .contruct_status_ul .contruct_status_li>h2{
        font-size:1.1em;
        font-weight:700;
        line-height: 1.39;
        letter-spacing: -0.56px;
        text-align: left;
        color: #4f5256;
        margin-bottom: 0.3em;
        width: 12em;
        display: inline-block;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .contruct_status_ul .contruct_status_li p.estimate_infor{
        font-size:0.9em;
        line-height: 1.94;
        letter-spacing: -0.4px;
        text-align: left;
        color: #4f5256;
    }

    .contruct_status_ul .contruct_status_li p.option_infor{
        font-size:0.9em;
        line-height: 1.94;
        letter-spacing: -0.4px;
        text-align: left;
        color: #4f5256;
        width:7em;
        display:inline;
    }

    .val{
        font-weight: 700;
        color: #007bd2;
    }

    .contruct_status_ul .contruct_status_li a{
        position: absolute;
        bottom: 1.4em;
        right: 1em;
        z-index: 1;
        font-size: 0.9em;
        line-height: 1.77;
        letter-spacing: -0.44px;
        text-align: right;
        color: #007bd2;
        text-decoration: none;
    }

    .contruct_status_ul .contruct_status_li a i{
        padding-left:0.5em;
    }
    .asking_price{
        font-size: 0.9em;
        line-height: 1.94;
        letter-spacing: -0.4px;
        text-align: left;
        color: #4f5256;
    }
</style>
@include('company_ver.layouts.footer')
@endsection