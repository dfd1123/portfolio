@extends('company_ver.layouts.app') 
@section('content')
<div class="request_box">
    <div class="title_div">
        <p class="const_title">시공 현황 등록</p>
        <button class="const_reg_btn">시공 리스트 추가</button>
    </div>
    <div class="list_div">
        <!-- <p class="empty_ment">시공 리스트가 없습니다. 리스트를 추가해주세요.</p> -->
        <div class="corporation_li">
            <div class="corporation_li_left">
                <img src="{{asset('/images/icon_sink.svg')}}" alt="">
                <span><b>싱크대</b>(<em>5/10</em>)</span>
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
                <span><b>덕트</b>(<em>2/10</em>)</span>
            </div>
            <div class="corporation_li_right">
                <div class="progress_box">
                    <div class="progress_bar"></div>
                </div>
            </div>
            <div class="active">
                <div class="shame_div">
                    <p>시공단계 현황</p>
                    <select class="min_select">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                    </select>
                    <select class="max_select">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                    </select>
                    <button class="fix_btn">수정</button>
                </div>
                <div class="add_comment">
                    <div>
                        덕트 시공일자 하루 앞당겨질 예정입니다. 덕트 시공일자 하루 앞당겨질 예정입니다.
                        <p>2019.00.00</p>
                    </div> 
                </div>
                <div class="coment_regist">
                    <textarea placeholder="코멘트를 작성하세요.(최대 n자)"></textarea>
                    <button class="regist_btn">등록</button>
                </div>
            </div>
        </div>
        <div class="corporation_li">
            <div class="corporation_li_left">
                <img src="{{asset('/images/icon_drainage.svg')}}" alt="">
                <span><b>배수구</b>(<em>9/10</em>)</span>
            </div>
            <div class="corporation_li_right">
                <div class="progress_box">
                    <div class="progress_bar"></div>
                </div>
            </div>
            <div class="add_comment">

            </div>
        </div>
    </div>
    <div class="regist_div" style="display:none;">
        <ul>
            <li>
                싱크대
                <i class="fal fa-check"></i>
            </li>
            <li>
                덕트
                <i class="fal fa-check"></i>
            </li>
            <li>
                화구
                <i class="fal fa-check"></i>
            </li>
            <li>
                배수구
                <i class="fal fa-check"></i>
            </li>
            <li>
                수도
                <i class="fal fa-check"></i>
            </li>
            <li>
                몰딩 마감
                <i class="fal fa-check"></i>
            </li>
        </ul>
        <div style="text-align:right">
            <button class="regist_btn">등록</button>
        </div>
    </div>
</div>
@include('company_ver.layouts.footer')
<script>
$(function(){
    $('.regist_div ul').append('<li onclick="javascript:add();">+</li>');
});
$('.const_reg_btn').on('click',function(){
    $('.const_reg_btn').attr('style','display:none;');
    $('.list_div').attr('style','display:none;');
    $('.regist_div').attr('style','display:block');
});
$('.regist_btn').on('click',function(){
    $('.const_reg_btn').attr('style','display:block;');
    $('.list_div').attr('style','display:block;');
    $('.regist_div').attr('style','display:none');
});

$('.request_box .regist_div ul li').on('click', function(){
    if($(this).attr('class')=="active"){
        $(this).removeClass('active');
    }
    else{
        $(this).addClass('active');
    }
});
function add(){
    console.log('+');
}
</script>
<style>
#content{
    background-color:#f2f2f2;
}
.request_box{
    width:90%;
    margin:1em auto;
    background:#fff;
    border-radius: 15px;
    box-shadow: 0px 3px 19px 0 rgba(122, 122, 122, 0.31);
}
.request_box .title_div{
    position:relative;
    border-bottom:1px solid #eaeaea;
    padding:1em;
    margin:0.5em 0;
}
.request_box .const_title{
    color:#5d5d5d;
    font-weight:bold;
}
.request_box .const_reg_btn{
    text-align:center;
    border:none;
    border-radius:20px;
    color:#fff;
    background-color:#17334a;
    position:absolute;
    right:2em;
    top:1.2em;
    padding: 0.5em 0.7em;
    font-size:0.7em;
}
.request_box .list_div{
    min-height:15em;
}
.request_box .list_div .empty_ment{
    color:#8c9198;
    padding:1em;
    font-size:0.8em;
}
.request_box .regist_div{
    padding:1em;
    min-height:15em;
}
.request_box .regist_div button{
    color:#fff;
    padding: 1em 1.5em;
    font-size: 0.8em;
    border-radius:15px;
    background-color:#17334a;
    border:none;
    margin-top:1em;
}
.request_box .regist_div ul li{
    background-color:#f0f9ff;
    color:#007bd2;
    border-radius:15px;
    padding:0.4em 1.3em;
    margin:0.5em 0;
}
.request_box .regist_div ul li.active{
    background-color:#007bd2;
    color:#fff;
}
.request_box .regist_div ul li i{
    display:none;
}
.request_box .regist_div ul li.active i{
    color:#fff;
    font-size:1em;
    float:right;
    display:block;
}

.corporation_li{
        position: relative;
        min-height: 70px;
        border-bottom: 1px solid #d1d1d1;
    }

.corporation_li .btn{
        border-radius: 43px;
        box-shadow: 0px 13px 16px 0 rgba(36, 36, 36, 0.07);
        background-color: #17334a;
        border:none;
        color:#fff;
        width:90%;
        padding: 0.7em;
        margin: 1em 0;
    }

.corporation_li:last-child{
        border-bottom:none;
    }

.corporation_li .corporation_li_left{
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
        padding: 1em;
    }

.corporation_li .corporation_li_left>img{
        width: 62px;
        height: 35px;
        vertical-align: middle;
    }

.corporation_li .corporation_li_left>span{
        padding: 0px 11px;
        display: inline-block;
        vertical-align: middle;
        font-size: 0.7em;
    }

.corporation_li .corporation_li_left>span b{
        display: block;
        font-size: 1.2em;
        margin-bottom: 4px;
    }

.corporation_li .corporation_li_left>span em{
        
    }

.corporation_li .corporation_li_right{
        width: 100%;
        padding-left: 149px;
        min-height: 52px;
        padding-right: 1em;
        position: relative;
    }

.corporation_li .corporation_li_right .progress_box{
        height: 4px;
        width: 46%;
        border-radius: 5px;
        background-color: #e9e9e9;
        position: absolute;
        top: 32px;
        left: 158px;
    }

.corporation_li .corporation_li_right .progress_box .progress_bar{
        width:0%;
        height:4px;
        border-radius: 5px;
        background-color: #00a2ff;
        transition:width 0.5s;
        transition-delay:1s;
    }
.corporation_li .shame_div{
    max-width: 100%;
    margin: 1em 0.5em;
    padding: 0 0.8em;
    line-height: 1.5;
    letter-spacing: -0.4px;
    text-align: left;
    font-size: 0.8em;
    color: #5b5b5b;
    display:table;
}
.corporation_li .shame_div p{
    display:table-cell;
    width:6em;
}

.corporation_li .shame_div .min_select{
    padding: 0.3em 1.5em;
    border-radius: 1.1em;
    border: 1px solid #d1d1d1;
}
.corporation_li .shame_div .max_select{
    padding: 0.3em 1.5em;
    border-radius: 1.1em;
    border: 1px solid #d1d1d1;
    margin: 0 0.6em;
}
.corporation_li .shame_div .fix_btn{
    padding: 0.6em 1.5em;
    border-radius: 1.1em;
    background-color:#17334a;
    color:#fff;
    text-align:center;
    border: none;
}
.corporation_li .add_comment{
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

.corporation_li .add_comment>div{
        padding: 0.5em 0;
        margin: 1em 0;
    }

.corporation_li .add_comment>div p{
        line-height: 1.5;
        letter-spacing: -0.36px;
        color: #adb3bc;
        text-align: right;
    }
.corporation_li .coment_regist{
    width: 100%;
    margin: 1em 0;
    padding: 0 1em;
    line-height: 1.5;
    letter-spacing: -0.4px;
    text-align: left;
    font-size: 0.8em;
    color: #5b5b5b;
    display:table;
}
.corporation_li .coment_regist textarea{
    display: table-cell;
    padding: 0.5em;
    border: 1px solid #5b5b5b;
    border-radius: 0.5em;
    margin-right: 0.8em;
    color:#5b5b5b;
    width:70%;
}
.corporation_li .coment_regist textarea:focus{
    border: 1px solid #00a2ff;
}
.corporation_li .coment_regist button{
    color: #fff;
    padding: 1.2em 1.7em;
    font-size: 0.9em;
    border-radius: 0.8em;
    background-color: #17334a;
    border: none;
    display: table-cell;
    float: right;
}
</style>
@endsection