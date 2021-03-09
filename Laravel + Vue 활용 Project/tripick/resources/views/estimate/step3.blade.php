@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--recommend">

    <div class="hd-title hd-title--02">
        <button type="button" id="close_recommend_btn" class="hd-title__right-btn hd-title__right-btn--close"><span class="none">닫기버튼</span></button>
        <h2 class="hd-title--02__title">여행지 추천 정보 입력</h2>
        <span class="inline inline--left">테마 선택</span>

        <div class="step-line">
            <ul class="step-line__group">
                <li class="step-line__list step-line__list--title">step</li>
                <li class="step-line__list is-complete">
                    <em>01</em>
                </li>
                <li class="step-line__list is-complete">
                    <em>02</em>
                </li>
                <li class="step-line__list is-active">
                    <em>03</em>
                </li>
                <li class="step-line__list">
                    <em>04</em>
                </li>
            </ul>
        </div>

    </div>

    <div class="wrapper--recommend__scroll-area">
        <label class="recommend-step__ask">어떤 테마의 여행을 원하시나요?</label>
        <p class="sub_text">(2개 선택 가능)</p>
        @forelse($themes as $theme)
        <input type="checkbox" name="estm_theme" class="none-input theme-type-input" value="{{ $theme->theme_title }}" id="check_theme_type_{{ $theme->theme_id }}" {{ $theme->theme_title == $estm_step[0]->estm_theme ? 'checked' : '' }} >
        <label class="button button--outline" for="check_theme_type_{{ $theme->theme_id }}">{{ $theme->theme_title }}</label>
        @empty
        @endforelse
        <input type="checkbox" name="estm_theme" class="none-input theme-type-input" value="기타" id="check_with_type_etc" {{ $estm_step[0]->estm_theme == '기타' ? 'checked' : '' }} >
        <label class="button button--outline" for="check_with_type_etc" id="option_B_airline" data-name="option_B_airline_popup">기타</label>
    </div>

    <div class="button-bt-fixed button-bt-fixed--02">
        <button type="button" class="button button--recommend-st--prev" onClick="location.href='/estimate/step2';"><b class="none">이전</b></button>
        <button type="button" class="button button--disable button--recommend-st" id="temp-confirm" data-id="{{ isset($estm_step[0]->estm_id) ? $estm_step[0]->estm_id : '' }}">다음</button>
    </div>

</div>
<div class="popup popup--modal">

    <div class="popup__inner" id="option_B_airline_popup">

        <h3 class="popup__inner__btn-close btn-close-popup" ><i class="btn-half-circle"></i>직접입력</h3>

        <fieldset class="popup__inner__field">

            <span class="inline inline--left recommend-step__advice">'기타'를 선택하셨을 경우,<br>원하시는 항목을 <b>2자에서 16자사이로 직접 작성</b>해주세요.</span>
            
            <span class="input-group">
                <input type="text" class="input-group__input" id="etc_text" maxlength="16" placeholder="원하는 항목들을 모두 적어주세요">
            </span>
            
            <button type="button" class="button button--disable button--relative mg_tb_20" id="btn_etc_text">작성완료</button>
            
        </fieldset> 

    </div>

</div>
@endsection

@section('script')
<script>
function replaceAll(str, searchStr, replaceStr) {
  return str.split(searchStr).join(replaceStr);
}
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
    	var estm_theme = '{{ $estm_step[0]->estm_theme }}';
    	estm_theme = estm_theme.split(',');
    	for(var i = 0; i< estm_theme.length; i++){
    		$("input:checkbox[name='estm_theme'][value="+estm_theme[i]+"]").prop('checked',true);
    	}
        $("#temp-confirm").addClass('is-active');
    @endif
	
	$('[name=estm_theme]').click(function(){
		var checklist = $('input:checkbox[name=estm_theme]:checked');
		if(checklist.length >0)
			$("#temp-confirm").addClass('is-active');
		else
			$("#temp-confirm").removeClass('is-active');
		if(checklist.length > 2){
			$(this).prop('checked',false);
			dialog.alert({
				title:'안내',  
	            message: '최대 2개까지 선택할 수 있습니다',
	            button: "확인"
			});
			$("#temp-confirm").addClass('is-active');
		}
	});
    //다음 버튼
    $("#temp-confirm").click(function(){
        if($(this).hasClass("is-active")){
            var estm_id = $(this).data("id");
            var estm_theme = '';
            var checklist = $('input:checkbox[name=estm_theme]:checked')
            for(var i = 0; i < checklist.length; i++){
            	if(i != checklist.length -1)
            		estm_theme += $(checklist[i]).val() + ',';
            	else
            		estm_theme += $(checklist[i]).val();
			}
            var param = { 
                'estm_id': estm_id,
                'estm_theme': estm_theme
            };
            $.ajax({
                method: "PUT",
                data: param,
                dataType: 'json',
                url: '/api/estimate/step3',
                success: function(data) {
                    if(data.state ==1){
                        location.href = '/estimate/step4';
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
    
    //기타 입력
    $("#etc_text").keyup(function(){
        var etc_text = $(this).val();
        if(etc_text.length > 1){
            $('#btn_etc_text').addClass('is-active');
        }else{
            $('#btn_etc_text').removeClass('is-active');
        }
    });

    $("#btn_etc_text").click(function(){
        var etc_text = $('#etc_text').val();
        $('#check_with_type_etc').val(etc_text);
        $('#option_B_airline').text(etc_text + ' (직접입력)');
        $('#check_with_type_etc').prop('checked',true);
        $('.popup__inner').removeClass('is-active');
        $('.popup--modal').delay(200).fadeOut();
    });//end
    $('#option_B_airline').click(function(){
    	if($('#check_with_type_etc').prop('checked')){
    		$('#option_B_airline_popup').removeClass('is-active');
    		$('.popup.popup--modal').attr('style','display:none;')
    	}
    });
});
</script>
<style lang="scss">
	.recommend-step__ask{
		margin-bottom:0;
	}
	.sub_text{
	    font-size: 0.7em;
    	color: #00DBD8;
		margin-bottom:1em;
	}
</style>
@endsection