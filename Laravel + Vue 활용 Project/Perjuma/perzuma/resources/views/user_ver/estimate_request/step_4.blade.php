@extends('user_ver.layouts.app') 
@section('content')

    <div class="estimate_request_wrap" style="background:#fff;">

        @include('user_ver.estimate_request.include.step_bar')
        <input type="hidden" name="trd_no" value="{{$trd_no}}" />
        <input type="hidden" id="selectDate" name="selectDate">
        <div class="dateprick_wrap">
            <div id="datepick" class="datepicker-here" data-language='en'></div>
        </div>

    </div>

    @include('user_ver.ft_button.ft_button')

    <style>
    #content{
        background:#fff;
    }
    </style>

    <script>
        // Initialization
        $('#datepick').datepicker({
            language: 'en',
            minDate: new Date((new Date()).valueOf() + 1000*3600*240),
            inline: true,
            navTitles: {
                days: 'yyyy<span style="padding-right: 5px;">년</span> MM'
            },
            onSelect: function onSelect(fd, date) {
                if(fd != ''){
                    $('#selectDate').val(fd+" 00:00:00");
                    $('.ft_button button').addClass('active');
                }else{
                    $('.ft_button button').removeClass('active');
                }
            }
        });

         var trd_no = $('input[name="trd_no"]').val();

        if(trd_no != ''){
            data_load($('#step_index').data('index'));
        }

        function data_load(step){
            $.ajax({
                type : "GET",
                dataType: "json",
                data : {trd_no : trd_no, step : step},
                url : "/api/estimate_request/load",
                success : function(data) {
                    if(data.contruct_dt != null){
                        $('#selectDate').val(data.contruct_dt);

                        var dp = $('#datepick').datepicker().data('datepicker');

                        dp.selectDate(new Date( $('#selectDate').val()));
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
        }

        console.log($('#selectDate').val());

        $('.ft_button button').on('click', function(){

            var trd_no = $('input[name="trd_no"]').val(); 
            var selectDate = $('input[name="selectDate"]').val(); 

            if($(this).hasClass('active')){
                $.ajax({
                    type : "POST",
                    dataType: "json",
                    data : {step : 4, trd_no : trd_no, selectDate : selectDate},
                    url : "/api/estimate_request",
                    success : function(data) {
                        if(data.verify){
                            location.href="/user_ver/estimate_request/5?id="+data.id+"";
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
                    text: "시공 예정 날짜를 입력하여 주세요.",
                    button: "확인",
                });
            }

        });
    </script>

@endsection