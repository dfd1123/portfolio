@extends('company_ver.layouts.app') 
@section('content')
<div class="sub_hd2">
    <div class="center_hd">
        <div class="header">
            <img class="profile_thumb" src="/adminassets/images/users/5.jpg"/>
          
            <div class="cnt-box">
               
            </div>
            <div class="rating-box">
                <img class="agent_rating_img" src="/images/3star.svg"/>
                <p class="agent_rating_txt"><em>3.0</em> / 5</p>
            </div>
            <button class="udt-btn">{{$title}}</button>
        </div>
    </div>
    <div class="main">
        <div class="section">
            <div class="title">내정보</div>
            <div class="content">
                <div class="left">이름</div>
                <div class="right">김민석</div>
            </div>
            <div class="content">
                <div class="left">휴대폰 번호</div>
                <div class="right">010-1234-5678</div>
            </div>
            <div class="content">
                <div class="left">이메일</div>
                <div class="right">perzuma@naver.com</div>
            </div>
        </div>
        <div class="section">
            <div class="title">사업자 정보</div>
            <div class="content">
                <div class="left">사업장 전화번호</div>
                <div class="right">02-123-4567</div>
            </div>
            <div class="content">
                <div class="left">사업장 소재지</div>
                <div class="right">서울시 관악구 남부순환로 1837</div>
            </div>
            <div class="content">
                <div class="left">상세주소</div>
                <div class="right">샤론빌딩 2층, 포켓컴퍼니</div>
            </div>
            <div class="content">
                <div class="left">업종/업태</div>
                <div class="right">서비스업 / 광고대행</div>
            </div>
            <div class="content">
                <div class="left">사업자등록증</div>
                <div class="right">20190302.jpg</div>
            </div>
            <div class="content">
                <div class="left">사업자 번호</div>
                <div class="right">173-00-00123</div>
            </div>
        </div>
        <div class="section">
            <div class="title">추가 입력정보</div>
            <div class="content">
                <div class="left">주요 시공 분야</div>
                <div class="right">레스토랑, 일식, 한식</div>
            </div>
            <div class="content">
                <div class="left">이동 가능 거리</div>
                <div class="right">0-10km</div>
            </div>
            <div class="content">
                <div class="left">시공 경력</div>
                <div class="right">2-5년</div>
            </div>
            <div class="content last">
                <div class="left">포트 폴리오</div>
                <div class="add_img_wrap">
                    <div class="add_img_li">
                        <div class="box">
                            <div class="img_content">
                                <div id="first_img_addbtn" class="img_wrap">
                                    <label for="construction_img1" data-index="1">
                                        <i class="fal fa-plus"></i><br />
                                        이미지 등록
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @for($i=1;$i<=8;$i++)
                        <div class="add_img_li">
                            <div class="box">
                                <div class="img_content">
                                    <div class="img_wrap">
                                        <input type="file" name="construction_img{{$i}}" id="construction_img{{$i}}" class="hide"  data-index="{{$i}}" accept="/image/*" capture="camera" />
                                        <img src="/images/default_add_img.png" id="preview_contruct{{$i}}" class="default_img" alt="default_img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>
@include('company_ver.layouts.footer')
<script>
    function readURL(input, index) {
            var dataImg = new FormData();

            $.each(input.files, function(i, file) {
                //dataImg.append('file', file);
            });

            dataImg.append('images', $('#construction_img'+index)[0].files[0]);
            dataImg.append('index', index);
            dataImg.append('trd_no', $('input[name="trd_no"]').val());
            
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    
                    $('#preview_contruct'+index).removeClass('default_img');
                    $('#preview_contruct'+index).attr('src', e.target.result);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(".img_wrap input[type='file']").change(function() {
            var index = $(this).data('index');
            var next_idx = index + 1;
            $('#first_img_addbtn label').attr("for", "construction_img" + next_idx + "");

            if(next_idx >= 2){
                $('.ft_button button').addClass('active');
            }else{
                $('.ft_button button').removeClass('active');
            }
            readURL(this, index);
        });

</script>
<style>
    #content {
        padding:0 0 3.3em 0;
        background: url(/images/bg_main_tile.png);
        background-size: 100% auto;
        background-repeat-x: repeat;
        background-repeat-y: repeat;
    }
    .sub_hd2{
        background: rgba(11, 115, 210,0.7);
    }


    .sub_hd2 .center_hd{
        text-align: center;
        color: #fff;
        box-sizing: content-box;
    }

    .sub_hd2 .center_hd h1{
        padding: 0.75em 0;
        font-size: 1.05em;
        font-weight: bold;
    }
    .header{
        text-align:center;
        padding:3em 0;
        margin:0 6em;
        position:relative;
    }
    .header .profile_thumb{
        border-radius:100%;
        width:7em;
        height:7em;
    }
    .header h6{
        margin-top:1em;
        font-size:1.2em;
        font-weight:bold;
    }
    p em{
        font-weight:bold;
    }
    .header .cnt-box{
        display:flex;
        margin-top:1em;
    }
    .header .cnt-box .agent_construction_cnt, .agent_review_cnt{
        width:50%;
        padding:0 1em;
        font-size:0.75em;
    }
    .header .cnt-box p:nth-child(1){
        border-right:solid 1px #fff;
    }
    .header .rating-box{
        display:inline-flex;
        margin-top:1em;
        align-items:center;
    }
    .header .agent_rating_img{
        width:65%;
    }
    .header .agent_rating_txt{
        width:35%;
        margin-left:1em;
        font-size:0.75em;
    }
    .udt-btn{
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translate(-50%, 50%);
        border-radius: 38px;
        box-shadow: 0 11px 11px 0 rgba(0, 0, 0, 0.06);
        background-color: #ffffff;
        border: none;
        height: 2.7em;
        width: 11em;
        color: #1284d5;
    }
    .main{
        width:100%;
        height:100%;
        background-color:#fff;
        padding:3em 2em;
    }
    .title{
        background: rgba(0, 140, 213,0.11);
        color:#008cd6;
        font-weight:bold;
        text-align:left;
        padding:0.7em;
        margin-bottom:1em;
    }
    .main .section{
        margin:1em 0;
    }
    .section .content{
        display:inline-flex;
        width:100%;
        padding:0.8em 0;
        border-bottom:solid 1px #eaeaea;
    }
    .section .content.last{
        display:block;
    }
    .main .section .content:last-child{
        border:none;
    }
    .section .content .left{
        width:30%;
        color:#a0a5af;
        text-align:left;
        font-size:0.7em;
    }
    .section .content .right{
        width:70%;
        color:#000;
        text-align:left;
        font-size:0.7em;
    }

    .add_img_wrap{
    width: 100%;
    overflow: hidden;
    padding-top:1em;
    }

    .add_img_wrap .add_img_p{
        text-align:right;
        font-size:0.85em;
        line-height: 1.5;
        letter-spacing: -0.4px;
        color:#5d5d5d;
        padding-top:0.1em;
        padding:0.3em 0;
    }

     .add_img_wrap .top_estimate_box{
        overflow: hidden;
    }

     .add_img_wrap .add_img_li{
        float:left;
        width:33.3%;
        padding: 0.19rem 0.13rem;
    }

     .add_img_wrap .add_img_li .box{
        position: relative;
        width: 100%;		/* desired width */
    }

     .add_img_wrap .add_img_li .box:before{
        content: "";
        display: block;
        padding-top: 100%; 	/* initial ratio of 1:1*/
    }

     .add_img_wrap .add_img_li .box .img_content{
        position:  absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }

     .add_img_wrap .add_img_li .box .img_content #first_img_addbtn{
        background-color: #fff;
        color: #adb3bc;
        line-height: 2;
        letter-spacing: -0.6px;
        font-size: 0.8rem;
    }

     .add_img_wrap .add_img_li .box .img_content #first_img_addbtn i{
        color: #237cdc;
        font-size: 1.7rem;
    }

     .add_img_wrap .add_img_li .box .img_content .img_wrap{
        width: 100%;
        height: 100%;
        border-radius: 10px;
        border: solid 1px #d9d9d9;
        background-color: #efefef;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        overflow: hidden;
    }

     .add_img_wrap .add_img_li .box .img_content .img_wrap img{
        width:100%;
        height:100%;
    }

     .add_img_wrap .add_img_li .box .img_content .img_wrap img.default_img{
        width:2rem;
        height:auto;
    }

     .bottom_estimate_box{
        padding-top:2em;
    }

     .bottom_estimate_box>h3{
        font-size: 1.1em;
        font-weight: 700;
        line-height: 1.22;
        letter-spacing: -0.64px;
        color: #17334a;
        padding: 0 1em;
    }

     .bottom_estimate_box>p{
        font-size: 0.75em;
        color: #5d5d5d;
        letter-spacing: -0.4px;
        line-height: 1.4;
        padding: 0.7em 1.5em;
        padding-bottom: 1.3em;
    }

     .bottom_estimate_box>div{
        background: #f2f2f2;
        padding: 1.5em 1em;
    }

     .bottom_estimate_box .estimate_file_wrap .estimate_file_top{
        overflow:hidden;
        margin-bottom: 1.1em;
    }

     .bottom_estimate_box .estimate_file_wrap .estimate_file_top .estimate_file_top_left{
        float:left;
    }
</style>
@endsection