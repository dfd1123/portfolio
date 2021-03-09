@extends('user_ver.layouts.app') 
@section('content')

    <div class="result_confirm_wrap">
        <input type="hidden" name="trd_no" value="{{$trd_no}}" />
        
        <div class="result_confirm_content">
            <div class="result_bil_hd"></div>
            <div class="result_bil_body">
                <div class="result_small_div">
                    <div class="result_title rs_top">
                        <img src="{{asset('/images/logo_symbol.svg')}}" alt="symbol">
                        <span id="user_name_infor">
                            
                        </span>
                    </div>
                    <div class="detail_infor">
                        <table>
                            <tr>
                                <th>업종</th>
                                <td>
                                    <span id="sectors"></span>
                                    <div class="setting_edit_btn" onclick="step_back(2)">
                                        <img src="{{asset('/images/icon_setting.svg')}}" alt="">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>평수</th>
                                <td>
                                    <span id="average"></span>
                                    <div class="setting_edit_btn" onclick="step_back(3)">
                                        <img src="{{asset('/images/icon_setting.svg')}}" alt="">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>예산</th>
                                <td>
                                    <span id="budget"></span>
                                    <div class="setting_edit_btn" onclick="step_back(4)">
                                        <img src="{{asset('/images/icon_setting.svg')}}" alt="">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>시공예정장소</th>
                                <td>
                                    <span id="address"></span>
                                    <span id="detail_address"></span>
                                    <div class="setting_edit_btn" onclick="step_back(5)">
                                        <img src="{{asset('/images/icon_setting.svg')}}" alt="">
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="result_small_div">
                    <h2 class="tit">매니저 옵션</h2>
                    <div class="manager_option">
                        
                    </div>
                    <div class="setting_edit_btn" onclick="step_back(6)">
                        <img src="{{asset('/images/icon_setting.svg')}}" alt="">
                    </div>
                </div>
                <div class="result_small_div">
                    <h2 class="tit">참고 이미지 / 동영상</h2>
                </div>
                <div class="slider_wrap" style="background:#ddd;">
                    <div id="reference_slider" class="swiper-container">
                        <div class="swiper-wrapper">
                            
                        </div>
                        <!-- Add Pagination -->
                        <div class="swiper-paginate" style="position: absolute;z-index:10;"></div>
                    </div>
                </div>
                <div class="result_small_div">
                    <img src="" alt="">
                    <h2 class="tit">
                        전달 및 요청 사항
                        <img src="{{asset('/images/icon_question.svg')}}" id="alert_other" alt="other_remark">
                    </h2>
                    <div class="other_remark">
                        <textarea name="other_remark_txt" id="other_remark_txt" placeholder="시공 장소에 대한 구체적인 내용을 기재 해주시면 더욱 원활한 시공 진행을 하실 수 있습니다."></textarea>
                    </div>
                </div>
            </div>
            <div class="result_bil_ft"></div>
        </div>

    </div>

    <template id="estimate_slide_li">
        <div class="swiper-slide">
            <img />
        </div>
    </template>

    @include('user_ver.ft_button.ft_button')

    <style>
        .manager_option{
            position: relative;
            border-radius: 10px;
            background-color: #e9e9e9;
            width: 95%;
            margin: 0 auto;
            padding: 0.8rem;
            text-align: center;
            font-size: 1rem;
            color: #4f5256;
            font-weight: 700;
        }

        .manager_option img{
            width: 20px;
            margin-top: 0px;
            margin-right: 4px;
        }

        .result_small_div .setting_edit_btn{
            position: absolute;
            top: 1rem;
            right: 8px;
            z-index: 2;
        }

        .result_small_div .setting_edit_btn img{
            width:16px;
        }

        .swiper-slide>img{
            height:100%;
        }
    </style>

    <script>

        var trd_no = $('input[name="trd_no"]').val();

        $.ajax({
            type : "GET",
            dataType: "json",
            data : {trd_no : trd_no},
            url : "/api/estimate_request/step7load",
            success : function(data) {
                var swiper_wrap = $('.swiper-wrapper');

                Object.keys(data.trd_img).forEach(function(key, index, array) {
                    var templete = $($('#estimate_slide_li').html());
                    templete.find('img').attr('src','/storage/image/onsite' + data.trd_img[key]);
                    //templete.html('<img src="/storage/image/onsite' + data.trd_img[key] + '" />');
                    //templete.find('.swiper-slide').html('<img src="/storage/image/onsite' + data.trd_img[key] + '" />');
                    
                    swiper_wrap.append(templete);
                });

                new Swiper('#reference_slider', {
                    loop: true,
                    pagination: {
                        el: '.swiper-paginate',
                        dynamicBullets: true,
                    }
                });

                $('#user_name_infor').html(data.user_name+'님의<br>시공 요청 내용입니다.');
                $('#sectors').text(data.trade.bl_name);

                if(data.trade.trd_area == 0){
                    $('#average').text('10평 이하');
                }else if(data.trade.trd_area == 100){
                    $('#average').text('100평 이상');
                }else{
                    $('#average').text(data.trade.trd_area+'평대');
                }

                if(data.trade.trd_budget == 0){
                    $('#budget').text('1,000 만원 미만');
                }else if(data.trade.trd_budget == 100){
                    $('#budget').text('1억원 이상');
                }else{
                    $('#budget').text(numberWithCommas(data.trade.trd_budget)+' 만원 상당');
                }
                
                $('#address').text(data.trade.address);
                $('#detail_address').text(data.trade.detail_address);

                if(data.trade.is_premium == 0){
                    $('.manager_option').html('<span style="vertical-align: text-top;">베이직 서비스</span>');
                }else if(data.trade.is_premium == 1){
                    $('.manager_option').html('<img src="http://perzuma.local/images/icon_premium.svg" alt=""><span style="vertical-align: text-top;">프리미엄 서비스</span>');
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
        

        function step_back(index){
            
            location.href="/user_ver/estimate_request/"+index+"?id="+trd_no+"";

        }

        $('#alert_other').on('click', function(){
            swal({
                title: "전달 및 요청사항",
                text: "업종, 평수, 예산 등에 대해 정확히 기재해주셔야 정확한 견적이 가능하며 시공 장소에 대한 구체적인 내용을 기재 해주시면 더욱 원활한 시공 진행을 하실 수 있습니다!",
                button: "확인",
            });
        });

        $('#other_remark_txt').on('keyup',  function(){
            if($(this).val() != ''){
                $('.ft_button button').addClass('active');
            }else{
                $('.ft_button button').removeClass('active');
            }
        });

        $('.ft_button button').on('click', function(){

            var estimate_request_id = $('#estimate_request_id').val();

            if($(this).hasClass('active')){
                // 업종 id인 upstream_id를 Ajax의 data 값으로 전달하여 API 전달

                swal({
                    title: "재확인",
                    text: "이대로 견적을 요청하시겠습니까?",
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

                        var other_remark_txt = $('#other_remark_txt').val();
                         
                        $.ajax({
                            type : "POST",
                            dataType: "json",
                            data : {step : 7 ,trd_no : trd_no, other_remark_txt : other_remark_txt},
                            url : "/api/estimate_request",
                            success : function(data) {
                                if(data.verify){
                                    location.href="/user_ver/request_complete?id="+data.id+"";
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
                    }
                });

                // Ajax 실패하면..
                /*
                swal({
                    title: "오류",
                    text: "죄송합니다.<br>시스템 오류로 인해 업종이 저장되지 않았습니다.<br>다시 시도해주세요.",
                    button: "확인",
                });
                */
            }else{
                swal({
                    title: "알림",
                    text: "전달 및 요청 사항을 기재하여 주세요.",
                    button: "확인",
                });
            }

        });


    </script>


@endsection