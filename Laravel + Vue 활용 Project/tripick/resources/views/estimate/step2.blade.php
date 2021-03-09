@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--recommend">

    <div class="hd-title hd-title--02">
        <button type="button" id="close_recommend_btn"  class="hd-title__right-btn hd-title__right-btn--close"><span class="none">닫기버튼</span></button>
        <h2 class="hd-title--02__title">여행지 추천 정보 입력</h2>
        <span class="inline inline--left">예산 선택</span>

        <div class="step-line">
            <ul class="step-line__group">
                <li class="step-line__list step-line__list--title">step</li>
                <li class="step-line__list is-complete">
                    <em>01</em>
                </li>
                <li class="step-line__list is-active">
                    <em>02</em>
                </li>
                <li class="step-line__list">
                    <em>03</em>
                </li>
                <li class="step-line__list">
                    <em>04</em>
                </li>
            </ul>
        </div>

    </div>

    <div class="wrapper--recommend__scroll-area">

        <label class="recommend-step__ask" for="ask_region">예정된 예산이 어느정도 되시나요?</label>

        <div class="budget-area">
            <span class="budget-area__title">예산범위</span>
            <p class="budget-area__option">
                <span class="budget-area__spectrum">
                    <em>0원</em>~<em id="max_budget_text">500,000원</em>
                </span>
                <input type="button" id="btn_budget_reset" value="초기화" class="budget-area__reset">
            </p>
            <div class="budget-area__input-group">
                <input type="range" class="budget-area__input-range" id="budget_range" min="0" max="5000000" step="100000" value="500000">
                <span class="progress_bar" style="width:10%;"></span>
            </div>
        </div>
        
        <input type="checkbox" id="check_budget_unrelate" class="none-input">

        <label class="button button--outline budget-area__label_unrelate" for="check_budget_unrelate">무관, 미정</label>
        <button type="button" class="button button--outline" id="budget_direct_input" data-name="budget_direct_input_popup">직접 입력</button>

    </div>

    <div class="button-bt-fixed button-bt-fixed--02">
        <button type="button" class="button button--recommend-st--prev" onClick="location.href='/estimate/step1'"><b class="none">이전</b></button>
    <button type="button" class="button button--disable button--recommend-st is-active" id="temp-confirm" data-id="{{ isset($estm_step[0]->estm_id) ? $estm_step[0]->estm_id : '' }}" >다음</button>
    </div>

</div>

<div class="popup popup--modal">

    <div class="popup__inner" id="budget_direct_input_popup">

        <h3 class="popup__inner__btn-close btn-close-popup"><i class="btn-half-circle"></i>직접입력</h3>

        <fieldset class="popup__inner__field">

            <span class="inline inline--left recommend-step__advice">'직접 입력'을 선택하셨을 경우,<br><b>원하시는 여행 예산을 직접 작성</b>해주세요.</span>
            
            <span class="input-group">
                <input type="text" id="max_budget_input" class="input-group__input" placeholder="원하시는 여행 예산을 직접 작성해주세요." onkeyup="inputNumberFormat(this)">
            </span>
            
            <button type="button" class="button button--disable button--relative mg_tb_20" id="btn_budget_confirm">작성완료</button>
            
        </fieldset>

    </div>

</div>
@endsection

@section('script')
<script>
$(function(){
	
    //예산입력
    $('#budget_range').on('input', function(){
        $('#check_budget_unrelate').prop('checked',false);
        $('#max_budget_text').text(numberWithCommas($(this).val()) + '원');
        $('.progress_bar').attr('style','width:'+($(this).val() / 5000000)*100 +'%');
    });

    $('#btn_budget_reset').click(function(){
        $('#check_budget_unrelate').prop('checked',false);
        $('#budget_range').val(500000);
        $('#max_budget_text').text('500,000원');
    });

    $('#check_budget_unrelate').click(function(){
        $('#budget_range').val(0);
        $('#max_budget_text').text('0원');
    });

    $('#max_budget_input').keyup(function(){

        var only_num = cf_getNumberOnly('"'+$(this).val()+'"');
        
        if($(this).val() !== ''){
            $('#btn_budget_confirm').addClass('is-active');
        }else{
            $('#btn_budget_confirm').removeClass('is-active');
        }

        if( only_num >= 5000000){
            alert('최대범위는 500만원까지입니다.');
            $(this).val('');
        }

    });

    $('#btn_budget_confirm').click(function(){

        $('#max_budget_text').text(numberWithCommas($('#max_budget_input').val()) + '원');
        $('#budget_range').val($('#max_budget_input').val());
        $('#check_budget_unrelate').prop('checked',false);
        $('.popup__inner').removeClass('is-active');
        $('.popup--modal').delay(200).fadeOut();
        

    });
    //예산입력 end

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
    @if(isset($estm_step[0]->estm_budget_max))
        $('#max_budget_text').text('{{ number_format($estm_step[0]->estm_budget_max,0) }}원');
        $('#budget_range').val('{{ $estm_step[0]->estm_budget_max }}');
        $('.progress_bar').attr('style','width:'+($('#budget_range').val() / 5000000)*100 +'%');
    @endif

    //다음 버튼
    $("#temp-confirm").click(function(){
        if($(this).hasClass("is-active")){
            var estm_id = $(this).data("id");
            var param = { 
                'estm_id': estm_id,
                'estm_budget_min': 0,
                'estm_budget_max': $('#budget_range').val()
            };
            $.ajax({
                method: "PUT",
                data: param,
                dataType: 'json',
                url: '/api/estimate/step2',
                success: function(data) {
                    console.log(data);
                    if(data.state ==1){
                        location.href = '/estimate/step3';
                    }else if(data.state == 100){
                        
                    }else{
                        dialog.alert({
                            title:'오류',  
                            message: data.msg,
                            button: "확인"
                        });
                    }

                },
                error: function(e) {
                    console.log(e);
                }
            });
        }else{
            dialog.alert({
                title: "알림",
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

function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}

function inputNumberFormat(obj) {
    obj.value = comma(uncomma(obj.value));
}

function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

function uncomma(str) {
    str = String(str);
    return str.replace(/[^\d]+/g, '');
}
function replaceAll(str, searchStr, replaceStr) {
    return str.split(searchStr).join(replaceStr);
}

function cf_getNumberOnly (str) {
    var len      = str.length;
    var sReturn  = "";

    for (var i=0; i<len; i++){
        if ( (str.charAt(i) >= "0") && (str.charAt(i) <= "9") ){
            sReturn += str.charAt(i);
        }
    }
    return sReturn;
}

</script>

<style lang="scss">
	.input-group:after{
		content: '원';
	    position: absolute;
	    top: 0;
	    right: 0;
	    transform: translate(-50%, 50%);
	}
	.input-group__input{
		padding-right:2em;
		text-align:right;
	}
</style>
@endsection