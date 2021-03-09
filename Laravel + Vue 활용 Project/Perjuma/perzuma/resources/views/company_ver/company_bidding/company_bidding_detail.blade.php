@extends('company_ver.layouts.app') 
@section('content')
<script>

</script>
    <div class="result_confirm_wrap">
        <input type="hidden" name="estimate_request_id" id="estimate_request_id" />
        
        <div class="result_confirm_content">
            <div class="result_bil_hd"></div>
            <div class="result_bil_body">
                <div class="result_small_div">
                    <div class="result_title rs_top">
                        <img src="{{asset('/images/logo_symbol.svg')}}" alt="symbol">
                        <span>
                            {{$info->name}}님의<br>시공 요청 내용입니다.
                        </span>
                    </div>
                    <div class="detail_infor">
                        <table>
                            <tr>
                                <th>업종</th>
                                <td>
                                    {{$info->bl_name}}
                                </td>
                            </tr>
                            <tr>
                                <th>평수</th>
                                <td>
                                    {{$info->trd_area}}평
                                </td>
                            </tr>
                            <tr>
                                <th>예산</th>
                                <td>
                                 {{$info->trd_budget}} 만원
                                </td>
                            </tr>
                            <tr>
                                <th>시공예정장소</th>
                                <td>
                                    {{$info->address}}
                                </td>
                            </tr>
                            <tr>
                                <th>요청 사항</th>
                                <td>
                                    {{$info->other_remark_txt}}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="result_small_div">
                    <h2 class="tit">매니저 옵션</h2>
                    @if($info->is_premium ==0)
                    <div class="manager_option">
                        <span style="vertical-align: text-top;">베이직 서비스</span>
                    </div>
                    @elseif($info->is_premium ==1)
                    <div class="manager_option">
                        <img src="{{asset('/images/icon_premium.svg')}}" alt=""><span style="vertical-align: text-top;">프리미엄 서비스</span>
                    </div>
                    @endif
                </div>
                <div class="result_small_div">
                    <h2 class="tit">참고 이미지</h2>
                </div>
                <div class="slider_wrap" style="background:#ddd;">
                    <div id="reference_slider" class="swiper-container">
                        <div class="swiper-wrapper">
                            @if(!empty($info->trd_img))
                                @forelse((json_decode($info->trd_img)) as $img)
                                <div class="swiper-slide" style="background-image : url(/storage/fdata/trade/estimate{{$img}})">
                                    <img class="swiper-lazy" />
                                </div>
                                @empty
                                <h3 class="no_img_ment">사진이 없습니다</h3>
                                @endforelse
                            @else
                            <h3 class="no_img_ment">사진이 없습니다</h3>
                            @endif
                            
                        </div>
                        <!-- Add Pagination -->
                        <div class="swiper-paginate" style="position: absolute;z-index:10;"></div>
                    </div>
                </div>
                <div class="result_small_div">
                    <h2 class="tit">
                        전달 및 요청 사항
                        <img src="{{asset('/images/icon_question.svg')}}" id="alert_other" alt="other_remark">
                    </h2>
                    <div class="other_remark">
                        <textarea name="other_remark_txt" id="other_remark_txt" placeholder="시공 장소에 대한 구체적인 내용을 기재 해주시면 더욱 원활한 시공 진행을 하실 수 있습니다."></textarea>
                    </div>
                </div>
                <div class="result_small_div">
                    <h2 class="tit">
                        입찰가
                        <img src="{{asset('/images/icon_question.svg')}}" id="alert_other" alt="other_remark">
                    </h2>
                    <div class="other_remark">
                        <input class="bidding_price" name="bidding_price" id="bidding_price" type="number" placeholder="입찰가를 입력해주세요(숫자)" />
                    </div>
                </div>
                <div style="text-align:center;">
                    <button class="btn">입찰 신청</button>
                </div>
                
            </div>
            <div class="result_bil_ft"></div>
        </div>

    </div>
    <template id="estimate_slide_li">
        <div class="swiper-slide">
            <img class="swiper-lazy" />
            <div class="swiper-lazy-preloader"></div>
        </div>
    </template>
    @include('company_ver.layouts.footer')
    <style>
    #content{
        padding:3.3em 0;
    }
    .result_confirm_wrap{
        padding:1em 0 3em;
    }
        .bidding_price{
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: none;
            background: #f5f5f5;
            font-size: 0.85rem;
            color: #4f5256;
            line-height: 1.9;
        }
        .no_img_ment{
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            position: absolute;
        }
    </style>
    <script>

        function step_back(index){
            var estimate_request_id = $('#estimate_request_id').val();
            
            location.href="/estimate_request/"+index+"?id="+estimate_request_id+"";

        }

        new Swiper('#reference_slider', {
            loop: true,
            pagination: {
                el: '.swiper-paginate',
                dynamicBullets: true,
            }
        });

        $('#alert_other').on('click', function(){
            swal({
                title: "전달 및 요청사항",
                text: "업종, 평수, 예산 등에 대해 정확히 기재해주셔야 정확한 견적이 가능하며 시공 장소에 대한 구체적인 내용을 기재 해주시면 더욱 원활한 시공 진행을 하실 수 있습니다!",
                button: "확인",
            });
        });

        var trd_no = findGetParameter('trd_no');
        $('.btn').click(function(){
            if($('#bidding_price').val()==null || $('#bidding_price').val() ==''){
                swal({
                    title: "오류",
                    text: "입찰가를 기재해 주세요",
                    button: "확인",
                });
                return;
            }
            var param = {
                'type' : 'tradebidding',
                'trd_no' : trd_no,
                'agt_no' : {{auth()->user()->user_no}},
                'agt_others' : $('#other_remark_txt').val(),
                'asking_price' : $('#bidding_price').val()
            };
            $.ajax({
                type : "POST",
                dataType: "json",
                data : param,
                url : "/api/trade",
                success : function(data) {
                    console.log(data);
                    if(data.state==1 && data.query!=null){
                        swal({
                            title: "완료",
                            text: "입찰신청이 완료되었습니다",
                            buttons: {
                                yes: {
                                    text: "확인",
                                    value: true,
                                },
                            },
                        })
                        .then((value) => {
                            if(value){
                                // Ajax 성공하면..
                                location.href="/company_ver";
                            }
                        });
                    }
                    else{
                        swal({
                            title: "시스템",
                            text: "이미 입찰신청을 하였습니다",
                            buttons: "확인",
                        })
                    }
                },
                error : function(data){
                }
            });
        });


    </script>


@endsection