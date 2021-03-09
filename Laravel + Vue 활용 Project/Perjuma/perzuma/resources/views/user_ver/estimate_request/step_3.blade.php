@extends('user_ver.layouts.app') 
@section('content')
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<div class="estimate_request_wrap">

    @include('user_ver.estimate_request.include.step_bar')

    <div class="sqm_wrap">
        <input type="hidden" name="trd_no" value="{{$trd_no}}" />
        <input type="hidden" name="average" id="average" readonly>
        <input type="hidden" name="budget" id="budget" readonly>
        
        <div class="range_box first">
            <h3>평수</h3>
            <span id="average_bind">10평대</span>
            <div id="slider_average_bind_range"></div>
            <ul>
                @for($i=0;$i<10;$i++)
                <li></li>
                @endfor
            </ul>
        </div>
        <div class="range_box">
            <h3>시공예산 <small>(단위:만원)</small></h3>
            <span id="budget_bind">1,000만원 상당</span>
            <div id="slider_budget_bind_range"></div>
            <ul>
                @for($i=0;$i<10;$i++)
                <li></li>
                @endfor
            </ul>
        </div>

    </div>
        
</div>

@include('user_ver.ft_button.ft_button')
<style>
    #content{
        background:#fff;
    }
</style>
<script>
    $(function() {

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
                    if(data.trd_budget != null && data.trd_area != null){
                        $('#average').val(data.trd_area);
                        $('#slider_average_bind_range > div').css("width", data.trd_area+"%");
                        $('#slider_average_bind_range > span').css("left", data.trd_area+"%");
                        if(data.trd_area == 0){
                            $('#average_bind').text('10평 이하');
                        }else if(data.trd_area == 100){
                            $('#average_bind').text('100평 이상');
                        }else{
                            $('#average_bind').text(data.trd_area+'평대');
                        }

                        $('#budget').val(data.trd_budget);
                        $('#slider_budget_bind_range > div').css("width", (parseInt(data.trd_budget)/100)+"%");
                        $('#slider_budget_bind_range > span').css("left", (parseInt(data.trd_budget)/100)+"%");
                        if(data.trd_budget == 0){
                            $('#budget_bind').text('1,000만원 미만');
                        }else if(data.trd_budget == 10000){
                            $('#budget_bind').text('1억원 이상');
                        }else{
                            $('#budget_bind').text(numberWithCommas(data.trd_budget)+'만원 상당');
                        }
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

        $( "#slider_average_bind_range" ).slider({
            range: "min",
            value: 10,
            min: 0,
            max: 100,
            step: 10,
            slide: function( event, ui ) {
                if(ui.value >= 100){
                    $( "#average_bind" ).text( ui.value + "평 이상" );
                }else if(ui.value <= 0){
                    $( "#average_bind" ).text( "10평 이하" );
                }else{
                    $( "#average_bind" ).text( ui.value + "평대" );
                }

                $( "#average" ).val( ui.value );
            }
        });
        $( "#average" ).val( $( "#slider_average_bind_range" ).slider( "value" ) );

        $( "#slider_budget_bind_range" ).slider({
            range: "min",
            value: 1000,
            min: 0,
            max: 10000,
            step: 1000,
            slide: function( event, ui ) {

                if(ui.value >= 10000){
                    $( "#budget_bind" ).text( "1억원 이상" );
                }else if(ui.value <= 0){
                    $( "#budget_bind" ).text( "1,000만원 미만" );
                }else{
                    $( "#budget_bind" ).text( numberWithCommas(ui.value) + "만원 상당" );
                }

                $( "#budget" ).val( ui.value );
            }
        });
        $( "#budget" ).val( $( "#slider_budget_bind_range" ).slider( "value" ) );

        var sqm;

        $('.ft_button button').addClass('active');

         $('.ft_button button').on('click', function(){
            var trd_no = $('input[name="trd_no"]').val(); 
            var average = $('input[name="average"]').val(); 
            var budget = $('input[name="budget"]').val(); 

            if($(this).hasClass('active')){

                $.ajax({
                    type : "POST",
                    dataType: "json",
                    data : {step : 3, trd_no : trd_no, average : average, budget : budget},
                    url : "/api/estimate_request",
                    success : function(data) {
                        if(data.verify){
                            location.href="/user_ver/estimate_request/4?id="+data.id+"";
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
                    text: "평수와 예산을 선택하여 주세요.",
                    button: "확인",
                });
            }
        });

    });
</script>

@endsection