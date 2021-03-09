@extends('user_ver.layouts.app') 
@section('content')

    <div class="estimate_request_wrap">

        @include('user_ver.estimate_request.include.step_bar')
        <input type="hidden" name="trd_no" value="{{$trd_no}}" />
        <form>
        <div class="supervision_wrap">
            <div class="supervision_li">
                <div class="box">
                    <div class="content">
                        <label for="basic" class="active">
                            <div class="service_tab">
                                <h2>베이직 서비스</h2>
                                <span>
                                    퍼주마 앱을 통하여<br>
                                    모든 시공을 완료합니다.<br>
                                    (추가금액 없음)
                                </span>
                            </div>
                        </label>
                        <input type="radio" name="supervision" id="basic" class="hide" value="0" checked="checked" />
                    </div>
                </div>
            </div>
            <div class="supervision_li">
                <div class="box">
                    <div class="content">
                        <label for="premium">
                            <div class="service_icon">
                                <img src="{{asset('/images/icon_premium.svg')}}" alt="icon_premium">
                            </div>
                            <div class="service_tab">
                                <h2>프리미엄 서비스</h2>
                                <span>
                                    퍼주맨을 통한 프리미엄<br>
                                    감리 시공 관리 서비스.<br>
                                    (추가금액 50만원)
                                </span>
                            </div>
                        </label>
                        <input type="radio" name="supervision" id="premium" class="hide" value="1" />
                    </div>
                </div>
            </div>
        </div>
        </form>
        <div class="premium_service_infor">
            <h3><img src="{{asset('/images/icon_question_blue.svg')}}" alt="icon_question_blue"> 프리미엄 서비스</h3>
            <p>퍼주마가 제공하는 프리미엄 서비스는 견적받은 시공에 추가로 50만원의 금액을 결제하여 퍼주맨의 [AS 1년 / 3D도면 / 전문컨설팅]을 받을 수 있는 서비스 입니다.</p>
        </div>

    </div>

    @include('user_ver.ft_button.ft_button')

    <style>
        .estimate_request_wrap .supervision_wrap{
            width: 100%;
            padding: 1.8rem 0.8em;
            overflow: hidden;
            margin-top: 3rem;
            padding-top: 7rem;
            background: url(/images/character_option.svg) no-repeat;
            background-size: 0px;
            background-position-x: 3rem;
            background-position-y: 8rem;
            transition:background-size 0.5s,background-position-x 0.5s, background-position-y 0.5s;
        }

        .estimate_request_wrap .supervision_wrap.animate{
            background-position-x: 1rem;
            background-position-y: 0rem;
            background-size: 311px;
        }

        .estimate_request_wrap .supervision_wrap .supervision_li{
            float:left;
            width:50%;
            padding: 0.19rem 0.5rem;
        }

        .estimate_request_wrap .supervision_wrap .supervision_li .box{
            position: relative;
            width: 100%;		/* desired width */
        }

        .estimate_request_wrap .supervision_wrap .supervision_li .box:before{
            content: "";
            display: block;
            padding-top: 100%; 	/* initial ratio of 1:1*/
        }

        .estimate_request_wrap .supervision_wrap .supervision_li .box .content{
            position:  absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        .estimate_request_wrap .supervision_wrap .supervision_li .box .content label{
            position:relative;
            display:block;
            width:100%;
            height:100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align:center;
            background:#fff;
            box-shadow: 0 4px 9px 0 rgba(198, 198, 198, 0.36);
            border-radius: 13px;
            transition:background 0.3s, color 0.3s;
        }

        .estimate_request_wrap .supervision_wrap .supervision_li .box .content label.active{
            background: #007bd2;
            color:#fff;
        }

        .estimate_request_wrap .supervision_wrap .supervision_li .box .content label .service_icon {
            position: absolute;
            top: -18px;
            left: 0;
            z-index: 1;
            width: 100%;
        }

        .estimate_request_wrap .supervision_wrap .supervision_li .box .content label .service_icon img{
            width: 2.3rem;
        }

        .estimate_request_wrap .supervision_wrap .supervision_li .box .content label div.service_tab{

        }

        .estimate_request_wrap .supervision_wrap .supervision_li .box .content label div.service_tab h2{
            font-size: 1em;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .estimate_request_wrap .supervision_wrap .supervision_li .box .content label div.service_tab span{
            font-size:0.7em;
        }

        .premium_service_infor{
            padding: 0 1.3rem;
        }

        .premium_service_infor h3{
            font-size:1rem;
            color:#007bd2;
            line-height: 1.39;
            letter-spacing: -0.6px;
            margin-bottom:0.8rem;
        }

        .premium_service_infor h3 img{
            width: 1.2rem;
            vertical-align: bottom;
            margin-right: 5px;
        }

        .premium_service_infor p{
            color: #5d5d5d;
            line-height: 1.5;
            letter-spacing: -0.4px;
            font-size:0.7em;
        }
    </style>

    <script>
        $(function() {

            $('.ft_button button').addClass('active');

            var trd_no = $('input[name="trd_no"]').val();

            if(trd_no != ''){
                data_load($('#step_index').data('index'));
            }

            function data_load(step){
                $.ajax({
                    type : "GET",
                    dataType: "json",
                    data : {trd_no : trd_no, step : step},
                    url : "/api/estimate_request/load",
                    success : function(data) {
                        if(data.is_premium == 0){
                            $('.estimate_request_wrap .supervision_wrap .supervision_li .box .content label').removeClass('active');
                            $('#basic').siblings('label').addClass('active');
                        }else if(data.is_premium == 1){
                            $('.estimate_request_wrap .supervision_wrap .supervision_li .box .content label').removeClass('active');
                            $('#premium').siblings('label').addClass('active');
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

            setTimeout(function() {
                $('.supervision_wrap').addClass('animate');
            }, 300);

            $('.supervision_li .box .content label').on('click', function(){

                $('.supervision_li .box .content label').removeClass('active');
                $(this).addClass('active');
                var check_id = $(this).attr('for');
            });

            $('.ft_button button').on('click', function(){

                var trd_no = $('input[name="trd_no"]').val(); 
                var supervision = $('input[name="supervision"]:checked').val(); 

                if($(this).hasClass('active')){
                    $.ajax({
                        type : "POST",
                        dataType: "json",
                        data : {step : 6, trd_no : trd_no, supervision : supervision},
                        url : "/api/estimate_request",
                        success : function(data) {
                            if(data.verify){
                                location.href ="/user_ver/result_confirm?id="+data.id;
                            }else{
                                swal({
                                    title: "인증 필요",
                                    text: "이메일 인증을 하신 후에\n이용이 가능합니다.\n가입하신 이메일 주소의\n메일함을 확인하세요.",
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
                }else{
                    swal({
                        title: "알림",
                        text: "매니저 옵션을 선택하여 주세요.",
                        button: "확인",
                    });
                }

            });

        });
    </script>


@endsection