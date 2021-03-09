@extends('company_ver.layouts.app') 
@section('content')

<div class="estimate_request_wrap">

    @include('company_ver.company_regist.include.step_bar')

    <div class="sqm_wrap">
        <input type="hidden" name="estimate_request_id" />
        <div class="request_box">
            <p>시공 경력 선택</p>
            <ul>
                <li data-sqm="1down" name="career" data-id="1년 이하">
                    <a>1년 이하</a>
                </li>
                <li data-sqm="0205" name="career" data-id="2-5년">
                    <a>2-5년</a>
                </li>
                <li data-sqm="0510" name="career" data-id="5-10년">
                    <a>5-10년</a>
                </li>
                <li data-sqm="10up" name="career" data-id="10년 이상">
                    <a>10년 이상</a>
                </li>
            </ul>
        </div>
    </div>
</div>

@include('company_ver.ft_button.ft_button')

<script>
    $(function() {

        var sqm;

        $('.sqm_wrap ul li').on('click', function(){
            $('.sqm_wrap ul li').removeClass('active');
            $(this).addClass('active');

             sqm = $(this).data('sqm');
            $('.ft_button button').addClass('active');
            console.log(sqm);
        });

         $('.ft_button button').on('click', function(){
            if($(this).hasClass('active')){
                var user_no = findGetParameter('user_no');
                var urltype = findGetParameter('type');
                var agent_career;
                for(var i = 0; i<$('[name=career]').length;i++){
                    if($($('[name=career]')[i]).hasClass('active')){
                        agent_career = $($('[name=career]')[i]).attr('data-id');
                    }
                }
                var param = {
                'step' : 4
                ,'agent_no' : user_no
                ,'agent_career' : agent_career
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
                        }else{
                            location.href="/company_ver/company_regist/5?user_no="+data.query[0].agent_no+"";
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
                    text: "경력을 선택하여 주세요.",
                    button: "확인",
                });
            }
        });

    });
</script>

@endsection