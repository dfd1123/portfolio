@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--recommend">

    <div class="hd-title hd-title--02">
        <button type="button" id="close_recommend_btn" class="hd-title__right-btn title__right-btn--close"><span class="none">닫기버튼</span></button>
        <h2 class="hd-title--02__title">선택사항 입력</h2>
        <span class="inline inline--left">{{ $estm_step[0]->estm_step4 }}
        선택</span>

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
                @for($i=0; $i<=$sort; $i++)
                <li class="step-line-etc__list is-active"><em></em></li>
                @endfor
                @for($i=0; $i<(count($groups) - $sort - 1); $i++)
                <li class="step-line-etc__list"><em></em></li>
                @endfor
            </ul>
        </div>

    </div>

    <div class="wrapper--recommend__scroll-area">
        <label class="recommend-step__ask">{{ $groups[$sort]->step_group }}</label>
        <p class="sub_text">(복수 선택 가능, 해당사항없을 때는 그냥 넘어가세요)</p>
        @forelse($step5_lists as $step5_list)
        <input type="checkbox" name="radio_step5" class="none-input theme-type-input" id="check_with_type_{{ $step5_list->step_id }}" value="{{ $step5_list->step_title }}">
        <label class="button button--outline" for="check_with_type_{{ $step5_list->step_id }}">{{ $step5_list->step_title }}</label>
        @empty

        @endforelse
        <input type="checkbox" name="radio_step5" class="none-input theme-type-input" id="check_with_type_etc" value="etc">
        <label class="button button--outline" for="check_with_type_etc" id="option_B_airline" data-name="option_B_airline_popup">기타</label>
        <!-- <label class="button button--outline" id="option_B_airline" data-name="option_B_airline_popup">기타</label> -->
    </div>

    <div class="button-bt-fixed button-bt-fixed--02">
        <button type="button" class="button button--recommend-st--prev" onClick="history.back();"><b class="none">이전</b></button>
        <button type="button" class="button button--disable button--recommend-st is-active" id="temp-confirm" data-id="{{ isset($estm_step[0]->estm_id) ? $estm_step[0]->estm_id : '' }}">다음</button>
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
    
    @if(isset($step5_detail[$sort]))
    	var estm_title = '{{ $step5_detail[$sort]->estm_title }}';
    	estm_title = estm_title.split(',');
    	console.log(estm_title);
    	for(var i = 0; i< estm_title.length; i++){
    		$("input:checkbox[name='radio_step5'][value='"+estm_title[i]+"']").prop('checked',true);
    	}
    @endif

	@if(count($groups) - $sort - 1 == 0)
        var next_page = "/estimate/step5_add";
    @else
        var next_page = "/estimate/step5/{{ $parent }}/{{ $sort + 1 }}";
    @endif
    // @if($parent == 0)
        // @if(count($groups) - $sort - 1 == 0)
            // var next_page = "/estimate/step5_add";
        // @else
            // var next_page = "/estimate/step5/{{ $parent }}/{{ $sort + 1 }}";
        // @endif
    // @else
        // @if(count($groups) - $sort - 1 == 0)
            // var next_page = "/estimate/step_finish";
        // @else
            // var next_page = "/estimate/step5/{{ $parent }}/{{ $sort + 1 }}";
        // @endif
    // @endif
        
    
	$('[name=radio_step5]').click(function(){
		var checklist = $('input:checkbox[name=radio_step5]:checked');
		if(checklist.length >0)
			$("#temp-confirm").addClass('is-active');
		else
			$("#temp-confirm").removeClass('is-active');
		if(checklist.length > 3){
			$(this).prop('checked',false);
			dialog.alert({
				title:'안내',  
	            message: '최대 3개까지 선택할 수 있습니다',
	            button: "확인"
			});
			$("#temp-confirm").addClass('is-active');
		}
	});
	
    //다음 버튼
    $("#temp-confirm").click(function(){
        if($(this).hasClass("is-active")){
            var estm_id = $(this).data("id");
            var estm_parent = {{ $parent }};
            var estm_sort = {{ $sort }};
            var estm_group = '{{ $groups[$sort]->step_group }}';
            
            var estm_title = new Array;
            var checklist = $('input:checkbox[name=radio_step5]:checked')
            for(var i = 0; i < checklist.length; i++){
            	estm_title.push($(checklist[i]).val());
			}
            //var estm_title = $("input:checkbox[name='radio_step5']:checked").val();
            if(estm_title == null){
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
                var param = { 
                    'estm_id': estm_id,
                    'estm_parent': estm_parent,
                    'estm_sort': estm_sort,
                    'estm_group' : estm_group,
                    'estm_title': JSON.stringify(estm_title)
                };
                $.ajax({
                    method: "PUT",
                    data: param,
                    dataType: 'json',
                    url: '/api/estimate/step5',
                    success: function(data) {
                        if(data.state ==1){
                            if(next_page == "/estimate/step_finish"){
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
                                location.href = next_page;
                            }
                            
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