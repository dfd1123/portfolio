<div class="contruct_status_wrap">
    <div class="rvdiv">
        <div id="edit_cancel" class="rvclosebtn">
            <p>닫기</p>
        </div>
    </div>
    <div style="padding-top:3.3em;">
    @if(isset($review))
        @forelse($review as $item)
        <div class="contruct_status_content">
            <div class="contruct_status_ul">
                <div class="contruct_status_li">
                    <span class="request_date">요청일자 : {{$item->reg_dt}}</span>
                    <h2>{{$item->name}}</h2>
                    @if($item->rv_point >= 0 && $item->rv_point < 1)
                    <img class="rating" src="/images/star/star_rating_0.0.svg"/>
                    @elseif($item->rv_point >= 1 && $item->rv_point < 2)
                    <img class="rating" src="/images/star/star_rating_1.0.svg"/>
                    @elseif($item->rv_point >= 2 && $item->rv_point < 3)
                    <img class="rating" src="/images/star/star_rating_2.0.svg"/>
                    @elseif($item->rv_point >= 3 && $item->rv_point < 4)
                    <img class="rating" src="/images/star/star_rating_3.0.svg"/>
                    @elseif($item->rv_point >= 4 && $item->rv_point < 5)
                    <img class="rating" src="/images/star/star_rating_4.0.svg"/>
                    @elseif($item->rv_point == 5)
                    <img class="rating" src="/images/star/star_rating_5.0.svg"/>
                    @endif
                    <p class="estimate_infor">{{$item->rv_title}}</p>
                    <p class="option_infor">{{$item->rv_content}}</p>
                    <div style="text-align:center;">
                    @if(isset($item->rv_imgs))
                        @forelse($item->rv_imgs as $rvimg)
                        <img class="rv_imgs" src="/storage/fdata/trade/review/33183a76cd5bc37c98aa8ed1f66746b1.jpg"/>
                        @empty
                        empty
                        @endforelse
                    @else
                    no
                    @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="contruct_status_content">
            <div class="contruct_status_ul">
                <div class="contruct_status_li">
                    <h2>리뷰가 없습니다!</h2>
                </div>
            </div>
        </div>
        @endforelse
    @else
    <div class="contruct_status_content">
        <div class="contruct_status_ul">
            <div class="contruct_status_li">
                <h2>리뷰가 없습니다!</h2>
            </div>
        </div>
    </div>
    @endif
    </div>
</div>
<template id="reqlistitem">
<div class="contruct_status_content">
        <div class="contruct_status_ul">
            <div class="contruct_status_li">
                <span class="request_date">요청일자 : </span>
                <h2>거래명</h2>
                <div style="text-align:center;">
                    <img class="rating" src="/images/star/star_rating_0.0.svg"/>
                </div>
                <p class="estimate_infor">제목</p>
                <p class="option_infor">내용</span>
            </div>
        </div>
    </div>
</template>
<script>
    $(function(){
        $('.rvclosebtn').click(function(){
            $('#content').off('scroll touchmove mousewheel');
            $('.contruct_status_wrap').removeClass('active');
        });
    });
    
</script>
<style>
.contruct_status_wrap{
    position: fixed;
    top: 100%;
    left: 0;
    z-index: 20;
    width: 100%;
    height: 100%;
    overflow-y: scroll;
    background: #fff;
    opacity: 0;
    transition:top 0.7s, opacity 0.3s;
}
.contruct_status_wrap.active{
    top:0;
    opacity: 1;
}
.rvdiv{
    width:100%;
    height:4em;
    background: #007bd2;
    position:fixed;
    z-index:5;
}
.rvclosebtn{
    position: absolute;
    top: 1.55em;
    right: 1.3em;
    z-index: 3;
    color:#fff;
}
.rating{
    width:40%;
    margin-bottom:1em;
}
.rv_imgs{
    width:95%;
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
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .contruct_status_ul .contruct_status_li p.estimate_infor{
        font-weight:bold;
        line-height: 1.94;
        letter-spacing: -0.4px;
        text-align: left;
        color: #4f5256;
        margin-bottom:0.3em;
    }

    .contruct_status_ul .contruct_status_li p.option_infor{
        font-size:0.9em;
        line-height: 1.94;
        letter-spacing: -0.4px;
        color: #4f5256;
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
</style>