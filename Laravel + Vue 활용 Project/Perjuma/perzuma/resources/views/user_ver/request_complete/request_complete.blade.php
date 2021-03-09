@extends('user_ver.layouts.app') 
@section('content')

<div class="request_complete">
    <input type="hidden" name="trd_no" value="{{$trd_no}}" />
    <div class="request_content">
        <div class="request_box">
            <div class="success-checkmark">
                <div class="check-icon">
                    <span class="icon-line line-tip"></span>
                    <span class="icon-line line-long"></span>
                    <div class="icon-circle"></div>
                    <div class="icon-fix"></div>
                </div>
            </div>
            <h2>주방 시공 견적서 요청을<span>완료</span>하였습니다.</h2>
            <p>지금부터 6시간 이내, 최대 3개 업체 견적서를 받아보실 수 있습니다.</p>
            <div>
                <div class="loader10"></div>
            </div>
            <div class="request_daedline">
                <span>견적 마감까지</span>
                <span id="timer_wrap"><b class="hour">-</b>시간 <b class="minute">-</b>분 남음</span>
            </div>
            <div>
                <a href="{{route('user_ver.estimate_manage')}}?id={{$trd_no}}" class="go_btn">견적사항 관리</a>
            </div>
        </div>
    </div>
</div>

<div class="advertise_layout">
    <p style="text-align: center;color: #fff;padding: 2.5rem 0;">광고영역</p>
</div>

    <script>
        var trd_no = $('input[name="trd_no"]').val();
        
        $.ajax({
            type : "POST",
            dataType: "json",
            data : {trd_no : trd_no},
            url : "/api/result_complete",
            success : function(data) {
                $('#timer_wrap .hour').text(data.hour);
                $('#timer_wrap .minute').text(data.minute);
            },
            error : function(data){
                swal({
                    title: "네트워크 오류",
                    text: "잠시 후 다시 시도해주세요.",
                    button: "확인",
                });
            }
        });
    </script>


@endsection