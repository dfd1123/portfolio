@extends('company_ver.layouts.app') 
@section('content')

<div class="estimate_request_wrap">

    @include('company_ver.company_regist.include.step_bar')
    
    <div class="sqm_wrap">
        <input type="hidden" name="estimate_request_id" />
        <div class="request_box">
            <p>시공 포트폴리오</p>
            <div class="add_img_wrap">
                <div class="add_img_li">
                    <div class="box">
                        <div class="content">
                            <div id="first_img_addbtn" class="img_wrap">
                                <label for="construction_img1" data-index="1">
                                    <i class="fal fa-plus"></i><br />
                                    이미지 등록
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="agent_popol" enctype="multipart/form-data">
                <input type="hidden" id="agent_no" name="agent_no"/>
                <input type="hidden" name="step" value="5"/>
                @for($i=1;$i<=5;$i++)
                    <div class="add_img_li">
                        <div class="box">
                            <div class="content">
                                <div class="img_wrap">
                                    <input type="file" name="construction_img{{$i}}" id="construction_img{{$i}}" class="hide"  data-index="{{$i}}" accept="/image/*" capture="camera" />
                                    <img src="/images/default_add_img.png" id="preview_contruct{{$i}}" class="default_img" alt="default_img">
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
                </form>
            </div>
        </div>
    </div>
        
</div>

@include('company_ver.ft_button.ft_button')

<script>
    var user_no = findGetParameter('user_no');
    $('#agent_no').val(user_no);
    function readURL(input, index) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // 성공하면 실행
                $('#preview_contruct'+index).removeClass('default_img');
                $('#preview_contruct'+index).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".img_wrap input[type='file']").change(function() {
        var index = $(this).data('index');
        var next_idx = index + 1;
        $('#first_img_addbtn label').attr("for", "construction_img" + next_idx + "");

        if(next_idx >= 2){
            $('.ft_button button').addClass('active');
        }else{
            $('.ft_button button').removeClass('active');
        }
        readURL(this, index);
    });
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
            /* var user_no = findGetParameter('user_no');
            var imgarray = new Array();
            var length = $('#'+$('#first_img_addbtn label').attr("for")).attr('data-index');
            for(var i = 1; i< length; i++){
                imgarray.push($('#construction_img'+i)[0].files[0]);
            }
            var param = {
                'step' : 5
                ,'user_no' : user_no
                ,'agent_popol' : imgarray
            } */
            $('#agent_popol').ajaxForm({
                type : "POST",
                dataType: "json",
                url : "/api/agentinfo",
                processData : false,
		    	contentType : false,
                success : function(data) {
                    if(data.query!=null && data.state==1){
                        //location.href="/company_ver/company_regist/6";
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
            $('#agent_popol').submit();
        }else{
            swal({
                title: "알림",
                text: "경력을 선택하여 주세요.",
                button: "확인",
            });
        }
    });

    $(function() {
    });
</script>

@endsection