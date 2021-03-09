@extends('company_ver.layouts.app') 
@section('content')
<div class="corporation_status_wrap">
    <div class="corporation_status_content">
        <div class="corporation_body">
            <div class="corporation_body_wrap">
                <div class="corporation_box">
                    <div class="box_hd">
                        <h2>시공 현황 확인</h2>
                        <div><i class="far fa-chevron-up"></i></div>
                    </div>
                    <div class="corporation_li">
                        <div class="corporation_li_left">
                            <img src="{{asset('/images/icon_sink.svg')}}" alt="">
                            <span><b>싱크대</b>(<em>10</em>%)</span>
                        </div>
                        <div class="corporation_li_right">
                            <div class="progress_box">
                                <div class="progress_bar"></div>
                            </div>
                        </div>
                        <div class="add_comment">

                        </div>
                    </div>
                    <div class="corporation_li">
                        <div class="corporation_li_left">
                            <img src="{{asset('/images/icon_duct.svg')}}" alt="">
                            <span><b>덕트</b>(<em>10</em>%)</span>
                        </div>
                        <div class="corporation_li_right">
                            <div class="progress_box">
                                <div class="progress_bar"></div>
                            </div>
                        </div>
                        <div class="add_comment">
                            <div>
                                덕트 시공일자 하루 앞당겨질 예정입니다. 덕트 시공일자 하루 앞당겨질 예정입니다.
                                <p>2019.00.00</p>
                            </div> 
                        </div>
                    </div>
                    <div class="corporation_li">
                        <div class="corporation_li_left">
                            <img src="{{asset('/images/icon_drainage.svg')}}" alt="">
                            <span><b>배수구</b>(<em>10</em>%)</span>
                        </div>
                        <div class="corporation_li_right">
                            <div class="progress_box">
                                <div class="progress_bar"></div>
                            </div>
                        </div>
                        <div class="add_comment">
                            
                        </div>
                    </div>
                    <div class="corporation_li" style="text-align:center;">
                        <button class="btn">시공현황 관리</button>
                    </div>
                </div>
                <div class="corporation_box" style="margin-top:1.5em;">
                    <div class="comment_hd">
                        <div class="active">업체 코멘트</div>
                        <div>나의 코멘트</div>
                    </div>
                    <div class="comment_wrap">
                        <ul class="company_comment">
                            <li class="new">
                                <h3>배관공사가 오늘 마무리 되는지 확인 요청드립니다.</h3>
                                <span>2019.06.01 15:20:24</span>
                                <p>24일 전기공사 한다고 해서 오늘까지는 주방 마무리가 얼추 되어야할 것 같아서 24일 전기공사 한다고해서</p>
                            </li>
                            <li>
                                <h3>배관공사가 오늘 마무리 되는지 확인 요청드립니다.</h3>
                                <span>2019.06.01 15:20:24</span>
                                <p>24일 전기공사 한다고 해서 오늘까지는 주방 마무리가 얼추 되어야할 것 같아서 24일 전기공사 한다고해서</p>
                            </li>
                            <li>
                                <h3>배관공사가 오늘 마무리 되는지 확인 요청드립니다.</h3>
                                <span>2019.06.01 15:20:24</span>
                                <p>24일 전기공사 한다고 해서 오늘까지는 주방 마무리가 얼추 되어야할 것 같아서 24일 전기공사 한다고해서</p>
                            </li>
                        </ul>
                        <ul class="user_comment" style="display:none;">
                            <li>
                                <h3>배관공사가 오늘 마무리 되는지 확인 요청드립니다.</h3>
                                <span>2019.06.01 15:20:24</span>
                                <p>24일 전기공사 한다고 해서 오늘까지는 주방 마무리가 얼추 되어야할 것 같아서 24일 전기공사 한다고해서</p>
                            </li>
                            <li>
                                <h3>배관공사가 오늘 마무리 되는지 확인 요청드립니다.</h3>
                                <span>2019.06.01 15:20:24</span>
                                <p>24일 전기공사 한다고 해서 오늘까지는 주방 마무리가 얼추 되어야할 것 같아서 24일 전기공사 한다고해서</p>
                            </li>
                            <li>
                                <h3>배관공사가 오늘 마무리 되는지 확인 요청드립니다.</h3>
                                <span>2019.06.01 15:20:24</span>
                                <p>24일 전기공사 한다고 해서 오늘까지는 주방 마무리가 얼추 되어야할 것 같아서 24일 전기공사 한다고해서</p>
                            </li>
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
<script>
$(function() {
    $('.btn').on('click',function(){
        location.href='/company_ver/company_const_manage';
    });

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
<style>
    #content{
        padding:1.5em 0;
        padding-top: 4.05em;
        min-height: 100%;
        background: #f6f6f6;
    }

    .corporation_status_wrap .corporation_status_content .corporation_hd{
        position: relative;
        padding: 1em 3%;
        background:#fff;
        height: 30px;
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
        margin: 1em 0 5em;
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

    .corporation_body .corporation_body_wrap .corporation_box .corporation_li .btn{
        border-radius: 43px;
        box-shadow: 0px 13px 16px 0 rgba(36, 36, 36, 0.07);
        background-color: #17334a;
        border:none;
        color:#fff;
        width:90%;
        padding: 0.7em;
        margin: 1em 0;
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
</style>
@include('company_ver.layouts.footer')



@endsection