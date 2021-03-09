@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--recommend">

    <div class="hd-title hd-title--02">
        <button type="button" id="close_recommend_btn" class="hd-title__right-btn hd-title__right-btn--close"><span class="none">닫기버튼</span></button>
        <h2 class="hd-title--02__title">선택사항 입력</h2>
        <span class="inline inline--left">동행객 선택</span>

        <div class="step-line">
            <ul class="step-line__group">
                <li class="step-line__list step-line__list--title">step</li>
                <li class="step-line__list is-complete">
                    <em>01</em>
                </li>
                <li class="step-line__list is-complete">
                    <em>02</em>
                </li>
                <li class="step-line__list is-complete">
                    <em>03</em>
                </li>
                <li class="step-line__list is-active">
                    <em>04</em>
                </li>
            </ul>
            <ul class="step-line-etc__group">
                <!-- <li class="step-line-etc__list is-active"><em></em></li>
                <li class="step-line-etc__list is-active"><em></em></li> -->
            </ul>
        </div>

    </div>

    <div class="wrapper--recommend__scroll-area">
        <label class="recommend-step__ask">추가 요청 사항</label>

        <div class="trip-info-detail__textarea">
            <textarea cols="30" rows="10" id="estm_step5_add" placeholder="여행 국가와 동행 장소, 동행의 내용, 기간, 방법등을 자세히 적어 주세요."></textarea>
        </div>
    </div>

    <div class="button-bt-fixed button-bt-fixed--02">
        <button type="button" class="button button--recommend-st--prev" onClick="history.back();"><b class="none">이전</b></button>
        <button type="button" class="button button--disable button--recommend-st is-active" id="temp-confirm" data-id="{{ isset($estm_step[0]->estm_id) ? $estm_step[0]->estm_id : '' }}">다음</button>
    </div>

</div>
@endsection

@section('script')
<script>
$(function(){
    @if(isset($estm_step[0]->estm_area) && isset($estm_step[0]->estm_area_type) && isset($estm_step[0]->estm_period)  && isset($estm_step[0]->estm_group_qtt))
    @else
        dialog.alert({
            title:'오류',  
            message: '1단계에 누락된 데이터가 있습니다. 입력해주세요.',
            button: "확인",
            callback: function(value){
                location.href='/estimate/step1';
            }
        });
    @endif
    @if(isset($estm_step[0]->estm_budget_min) && isset($estm_step[0]->estm_budget_max))
    @else
        dialog.alert({
            title:'오류',  
            message: '2단계에 누락된 데이터가 있습니다. 입력해주세요.',
            button: "확인",
            callback: function(value){
                location.href='/estimate/step2';
            }
        });
    @endif
    @if(isset($estm_step[0]->estm_theme))
    @else
        dialog.alert({
            title:'오류',  
            message: '3단계에 누락된 데이터가 있습니다. 입력해주세요.',
            button: "확인",
            callback: function(value){
                location.href='/estimate/step3';
            }
        });
    @endif
    @if(isset($estm_step[0]->estm_step4))
    @else
        dialog.alert({
            title:'오류',  
            message: '4단계에 누락된 데이터가 있습니다. 입력해주세요.',
            button: "확인",
            callback: function(value){
                location.href='/estimate/step4';
            }
        });
    @endif

    //다음 버튼
    $("#temp-confirm").click(function(){
        if($(this).hasClass("is-active")){
            var estm_id = $(this).data("id");
            var estm_step5_add = $("#estm_step5_add").val();
            if(estm_step5_add == null || estm_step5_add == ''){
                dialog.confirm({
                    title:'알림',  
                    message: '입력하신 정보로<br>여행 추천을 받으시겠습니까?',
                    button: "예",
                    cancel: "아니오",
                    callback: function(value){
                        if(value){
                            var param = { 
                                'estm_id': estm_id,
                                'state': 1
                            };
                            $.ajax({
                                method: "PUT",
                                data: param,
                                dataType: 'json',
                                url: '/api/estimate/state',
                                success: function(data) {
                                    if(data.state ==1){
                                        location.href = '/estimate/step_finish/'+estm_id;
                                    }else if(data.state == 100){
                                        //refresh token 있을시 재로그인, 없을시 login창으로 이동
                                    }else{
                                        dialog.alert({
                                            title:'알림',  
                                            message: data.msg,
                                            button: "확인"
                                        });
                                    }
                                },
                                error: function(e) {
                                    console.log(e);
                                }
                            });
                        }
                    }
                });
            }else{
                dialog.confirm({
                    title:'알림',  
                    message: '입력하신 정보로<br>여행 추천을 받으시겠습니까?',
                    button: "예",
                    cancel: "아니오",
                    callback: function(value){
                        if(value){
                            var param = { 
                                'estm_id': estm_id,
                                'estm_step5_add': estm_step5_add
                            };
                            $.ajax({
                                method: "PUT",
                                data: param,
                                dataType: 'json',
                                url: '/api/estimate/step5_add',
                                success: function(data) {
                                    if(data.state ==1){
                                        location.href = '/estimate/step_finish/'+estm_id;
                                    }else if(data.state == 100){
                                        //refresh token 있을시 재로그인, 없을시 login창으로 이동
                                    }else{
                                        dialog.alert({
                                            title:'알림',  
                                            message: data.msg,
                                            button: "확인"
                                        });
                                    }
                                },
                                error: function(e) {
                                    console.log(e);
                                }
                            });
                            
                        }
                    }
                });
                
            }
        }else{
            dialog.alert({
                title: "오류",
                message: "모든 사항이 입력되어야 합니다.",
                button: "확인"
            });
        }
    });

    //입력도중에 취소버튼 누르면 팝업
    $("#close_recommend_btn").click(function(e) {
        dialog.confirm({
            title: "알림",
            message:
            '<p class="single-msg">나를 가장 잘 아는 여행을 추천받는<br><b>정보입력을 중단</b>하시겠습니까?</p>',
            button: "네, 중단합니다.",
            cancel: "아니오, 계속 입력합니다.",
            callback: function(value){
                if(value){
                    location.href='/af_home';
                }
            }
        });
    });//end
});
</script>
@endsection