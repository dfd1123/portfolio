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
                    title: "???????????? ??????",
                    text: "?????? ??? ?????? ??????????????????.",
                    button: "??????",
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
                // ?????? id??? upstream_id??? Ajax??? data ????????? ???????????? API ??????

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
                                title: "?????? ??????",
                                text: "????????? ????????? ?????? ??????\n????????? ???????????????.\n???????????? ????????? ?????????\n???????????? ???????????????.",
                                button: "??????",
                            });
                        }
                    },
                    error : function(data){
                        swal({
                            title: "???????????? ??????",
                            text: "?????? ??? ?????? ??????????????????.",
                            button: "??????",
                        });
                    }
                });
            }else{
                swal({
                    title: "??????",
                    text: "????????? ???????????? ?????????.",
                    button: "??????",
                });
            }
        });

    });
</script>

@endsection