@extends('company_ver.layouts.app') 
@section('content')

<div class="estimate_request_wrap">

    @include('company_ver.company_regist.include.step_bar')

    <div class="upstream_wrap">
        <div class="upstream_li">
            <div class="box">
                <div class="content">
                    <button type="button" data-id="1" name="bl_list" data-name="한식">
                        <img src="{{asset('/images/icon_01.svg')}}" alt="">
                        <span>한식</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="upstream_li">
            <div class="box">
                <div class="content">
                    <button type="button" data-id="2" name="bl_list" data-name="중식">
                        <img src="{{asset('/images/icon_02.svg')}}" alt="">
                        <span>중식</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="upstream_li">
            <div class="box">
                <div class="content">
                    <button type="button" data-id="3" name="bl_list" data-name="일식">
                        <img src="{{asset('/images/icon_03.svg')}}" alt="">
                        <span>일식</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="upstream_li">
            <div class="box">
                <div class="content">
                    <button type="button" data-id="4" name="bl_list" data-name="이자카야">
                        <img src="{{asset('/images/icon_04.svg')}}" alt="">
                        <span>이자카야</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="upstream_li">
            <div class="box">
                <div class="content">
                    <button type="button" data-id="5" name="bl_list" data-name="레스토랑">
                        <img src="{{asset('/images/icon_05.svg')}}" alt="">
                        <span>레스토랑</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="upstream_li">
            <div class="box">
                <div class="content">
                    <button type="button" data-id="6" name="bl_list" data-name="고깃집">
                        <img src="{{asset('/images/icon_06.svg')}}" alt="">
                        <span>고깃집</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="upstream_li">
            <div class="box">
                <div class="content">
                    <button type="button" data-id="7" name="bl_list" data-name="닭갈비">
                        <img src="{{asset('/images/icon_07.svg')}}" alt="">
                        <span>닭갈비</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="upstream_li">
            <div class="box">
                <div class="content">
                    <button type="button" data-id="8" name="bl_list" data-name="곱창">
                        <img src="{{asset('/images/icon_08.svg')}}" alt="">
                        <span>곱창</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="upstream_li">
            <div class="box">
                <div class="content">
                    <button type="button" data-id="9" name="bl_list" data-name="기타">
                        <img src="{{asset('/images/icon_09.svg')}}" alt="">
                        <span>별도의뢰</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@include('company_ver.ft_button.ft_button')

<script>
    $(function() {

        let upstream_id;

        $('.upstream_wrap .upstream_li .box .content button').on('click', function(){
            if($(this).attr('class')=="active"){
                $(this).removeClass('active');
            }
            else{
                $(this).addClass('active');
            }

            for(var i = 0;i<$('.upstream_wrap .upstream_li .box .content button').length;i++){
                $('.ft_button button').removeClass('active');
                if($($('.upstream_wrap .upstream_li .box .content button')[i]).hasClass('active')){
                    $('.ft_button button').addClass('active');
                    return false;
                }
            }
        });

        $('.ft_button button').on('click', function(){
            if($(this).hasClass('active')){
                var user_no = findGetParameter('user_no');
                var urltype = findGetParameter('type');
                var agent_bl = new Array();
                var bl_name = '';
                for(var i = 0; i<$('[name=bl_list]').length;i++){
                    if($($('[name=bl_list]')[i]).hasClass('active')){
                        agent_bl.push($($('[name=bl_list]')[i]).attr('data-id'));
                        bl_name += $($('[name=bl_list]')[i]).attr('data-name')+', ';
                    }
                }
                bl_name = bl_name.slice(0,-2);
                var param = {
                    'step' : 2
                    ,'agent_no' : user_no
                    ,'agent_bl' : agent_bl
                    ,'bl_name' : bl_name
                }
                $.ajax({
                    type : "POST",
                    data : param,
                    dataType: "json",
                    url : "/api/agentinfo",
                    success : function(data) {
                        if(data.query!=null && data.state==1){
                            if(urltype==1){
                                location.href="/company_ver/company_myagent";
                            }
                            else{
                                location.href="/company_ver/company_regist/3?user_no="+data.query[0].agent_no+"";
                            }
                        }
                        else{
                            swal({
                                title: "오류",
                                text: "죄송합니다.<br/>시스템 오류로 인해 업종이 저장되지 않았습니다.<br/>다시 시도해주세요.",
                                button: "확인",
                            });
                        }
                    },
                    error : function(data){
                        swal({
                            title: "오류",
                            text: "죄송합니다.<br/>시스템 오류로 인해 업종이 저장되지 않았습니다.<br/>다시 시도해주세요.",
                            button: "확인",
                        });
                    }
                });
            }else{
                swal({
                    title: "알림",
                    text: "업종을 선택하여 주세요.",
                    button: "확인",
                });
            }
        });

    });
</script>

@endsection