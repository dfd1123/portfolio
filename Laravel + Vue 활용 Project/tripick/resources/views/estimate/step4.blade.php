@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--recommend">

    <div class="hd-title hd-title--02 hd-title--recommend-etc">
        <button type="button" id="close_recommend_btn" class="hd-title__right-btn hd-title__right-btn--close"><span class="none">닫기버튼</span></button>
        <h2 class="hd-title--02__title">선택사항 입력</h2>
        <span class="inline inline--left">추가 입력</span>

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
        </div>

    </div>

    <div class="wrapper--recommend__scroll-area">

        <p class="recommend-step__choice-caution">해당 스텝부터는 선택사항입니다.<br>입력해주시면 더욱 마음에 드는 상품을<br>추천받으실 수 있습니다.</p>

        <fieldset class="recommend-etc-choice">
            <ul class="recommend-etc-choice__group">
                <li class="recommend-etc-choice__list">
                	@if($stepparent[0]->state == 0)
                	<input type="radio" name="recommend-etc-choice" id="check_etc_option_01" value="{{$stepparent[0]->sp_title}}" data-parent="{{$stepparent[0]->sp_id}}" class="none-input" disabled="disabled">
                    <label for="check_etc_option_01" onclick="dialog.alert({title: '안내', message: '현재 사용불가합니다', button: '확인'})">
                    @elseif($stepparent[0]->state == 1)
                    <input type="radio" name="recommend-etc-choice" id="check_etc_option_01" value="{{$stepparent[0]->sp_title}}" data-parent="{{$stepparent[0]->sp_id}}" class="none-input">
                    <label for="check_etc_option_01">
                    @endif
                        <img src="/img/icon/icon-etc-option-01-off.svg" alt="etc option 01 off" class="recommend-etc-choice__icon recommend-etc-choice__icon--off">
                        <img src="/img/icon/icon-etc-option-01-on.svg" alt="etc option 01 on" class="recommend-etc-choice__icon recommend-etc-choice__icon--on">
                        <span class="recommend-etc-choice__span">{{$stepparent[0]->sp_title}}</span>
                    </label>
                </li>
                <li class="recommend-etc-choice__list">
                	@if($stepparent[1]->state == 0)
                	<input type="radio" name="recommend-etc-choice" id="check_etc_option_02" value="{{$stepparent[1]->sp_title}}" data-parent="{{$stepparent[1]->sp_id}}" class="none-input" disabled="disabled">
                    <label for="check_etc_option_02" onclick="dialog.alert({title: '안내', message: '현재 사용불가합니다', button: '확인'})">
                    @elseif($stepparent[1]->state == 1)
                    <input type="radio" name="recommend-etc-choice" id="check_etc_option_02" value="{{$stepparent[1]->sp_title}}" data-parent="{{$stepparent[1]->sp_id}}" class="none-input">
                    <label for="check_etc_option_02">
                    @endif
                        <img src="/img/icon/icon-etc-option-02-off.svg" alt="etc option 02 off" class="recommend-etc-choice__icon recommend-etc-choice__icon--off">
                        <img src="/img/icon/icon-etc-option-02-on.svg" alt="etc option 02 on" class="recommend-etc-choice__icon recommend-etc-choice__icon--on">
                        <span class="recommend-etc-choice__span">{{$stepparent[1]->sp_title}}</span>
                    </label>
                </li>
                <li class="recommend-etc-choice__list">
                	@if($stepparent[2]->state == 0)
                    <input type="radio" name="recommend-etc-choice" id="check_etc_option_03" value="{{$stepparent[2]->sp_title}}" data-parent="{{$stepparent[2]->sp_id}}" class="none-input" disabled="disabled">
                    <label for="check_etc_option_03" onclick="dialog.alert({title: '안내', message: '현재 사용불가합니다', button: '확인'})">
                    @elseif($stepparent[2]->state == 1)
                    <input type="radio" name="recommend-etc-choice" id="check_etc_option_03" value="{{$stepparent[2]->sp_title}}" data-parent="{{$stepparent[2]->sp_id}}" class="none-input">
                    <label for="check_etc_option_03">
                    @endif
                        <img src="/img/icon/icon-etc-option-03-off.svg" alt="etc option 03 off" class="recommend-etc-choice__icon recommend-etc-choice__icon--off">
                        <img src="/img/icon/icon-etc-option-03-on.svg" alt="etc option 03 on" class="recommend-etc-choice__icon recommend-etc-choice__icon--on">
                        <span class="recommend-etc-choice__span">{{$stepparent[2]->sp_title}}</span>
                    </label>
                </li>
                <li class="recommend-etc-choice__list">
                	@if($stepparent[3]->state == 0)
                    <input type="radio" name="recommend-etc-choice" id="check_etc_option_04" value="{{$stepparent[3]->sp_title}}" data-parent="{{$stepparent[3]->sp_id}}" class="none-input" disabled="disabled">
                    <label for="check_etc_option_04" onclick="dialog.alert({title: '안내', message: '현재 사용불가합니다', button: '확인'})">
                    @elseif($stepparent[3]->state == 1)
                    <input type="radio" name="recommend-etc-choice" id="check_etc_option_04" value="{{$stepparent[3]->sp_title}}" data-parent="{{$stepparent[3]->sp_id}}" class="none-input">
                    <label for="check_etc_option_04">
                    @endif
                        <img src="/img/icon/icon-etc-option-04-off.svg" alt="etc option 03 off" class="recommend-etc-choice__icon recommend-etc-choice__icon--off">
                        <img src="/img/icon/icon-etc-option-04-on.svg" alt="etc option 03 on" class="recommend-etc-choice__icon recommend-etc-choice__icon--on">
                        <span class="recommend-etc-choice__span">{{$stepparent[3]->sp_title}}</span>
                    </label>
                </li>
            </ul>
        </fieldset>

    </div>

    <div class="button-bt-fixed button-bt-fixed--02">
            <button type="button" class="button button--recommend-st--prev" onClick="location.href='/estimate/step3';"><b class="none">이전</b></button>
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
        $("input:radio[name='recommend-etc-choice'][value='{{ $estm_step[0]->estm_step4 }}']").prop('checked',true);
    @endif

    //다음 버튼
    $("#temp-confirm").click(function(){
        if($(this).hasClass("is-active")){
            var estm_id = $(this).data("id");
            var estm_step4 = $("input:radio[name='recommend-etc-choice']:checked").val();
            if(estm_step4 == null){
                dialog.confirm({
                    title:'알림',  
                    message: '입력하신 선택사항이 없습니다.<br>이대로 여행 추천을 받으시겠습니까?',
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
                            })
                        }
                    }
                });
            }else{
                var parent = $("input:radio[name='recommend-etc-choice']:checked").data("parent");
                var param = { 
                    'estm_id': estm_id,
                    'estm_step4': estm_step4
                };
                $.ajax({
                    method: "PUT",
                    data: param,
                    dataType: 'json',
                    url: '/api/estimate/step4',
                    success: function(data) {
                        if(data.state ==1){
                            location.href = '/estimate/step5/' + parent + '/0';
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

    $("input:radio[name='recommend-etc-choice']").click(function(){
        $("#temp-confirm").addClass('is-active');
    });

});
</script>
@endsection