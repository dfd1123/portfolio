@extends('user_ver.layouts.app') 
@section('content')

<div class="estimate_request_wrap">

    @include('user_ver.estimate_request.include.step_bar')
    <input type="hidden" name="trd_no" value="{{$trd_no}}" />
    <div class="upstream_wrap">
        @for($i=0;$i<9;$i++)
        <div class="upstream_li empty_box">
            <div class="box">
                <div class="content">
                    <button type="button" data-id="1">
                        <img src="" alt="">
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>


<template id="upstream_li" class="hide">
    <div class="upstream_li">
        <div class="box">
            <div class="content">
                <button type="button" class="li_click_btn" data-id="">
                    <img src="" alt="">
                    <span></span>
                </button>
            </div>
        </div>
    </div>
</template>

@include('user_ver.ft_button.ft_button')

<script>
    $(function() {

        let upstream_id;

        var trd_no = $('input[name="trd_no"]').val();

         $.ajax({
            type : "GET",
            dataType: "json",
            url : "/api/estimate_request/list?trd_no="+trd_no+"",
            success : function(data) {
                var upstream_wrap = $('.upstream_wrap');
                upstream_wrap.html("");
                data.business_lists.forEach(function(item) {
                    var templete = $($('#upstream_li').html());
                    templete.find('.li_click_btn').data('id',item.bl_no);
                    if(String(data.bl_no) == String(item.bl_no)){
                        templete.find('.li_click_btn').addClass('active');
                        $('.ft_button button').addClass('active');
                        upstream_id = item.bl_no;
                    }
                    templete.find('img').attr('src','/images/'+item.bl_thumb);
                    templete.find('span').text(item.bl_name);
                    upstream_wrap.append(templete);
                });

            },
            error : function(data){
                swal({
                    title: "네트워크 오류",
                    text: "잠시 후 다시 시도해주세요.",
                    button: "확인",
                });
            }
        });


        $(document).on('click', '.upstream_wrap .upstream_li .box .content button', function(){
            $('.upstream_wrap .upstream_li .box .content button').removeClass('active');
            $(this).addClass('active');

            upstream_id = $(this).data('id');
            $('.ft_button button').addClass('active');
        });

        $('.ft_button button').on('click', function(){
            var trd_no = $('input[name="trd_no"]').val(); 
            if($(this).hasClass('active')){
                // 업종 id인 upstream_id를 Ajax의 data 값으로 전달하여 API 전달

                $.ajax({
                    type : "POST",
                    dataType: "json",
                    data : {step : 2, upstream_id : upstream_id, trd_no : trd_no},
                    url : "/api/estimate_request",
                    success : function(data) {
                        if(data.verify){
                            location.href="/user_ver/estimate_request/3?id="+data.id+"";
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