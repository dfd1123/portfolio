@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--planner wrapper--planner-status">

    <div class="wrapper--planner-status__container">
        <!-- 플래너 신청해야할때 -->
        <div class="plnr-apply-status plnr-apply-status--01">
            @if(isset($pln_state[0]->state))
                @if($pln_state[0]->state == 0)
                <h5 id="pln-header" class="plnr-apply-status__tit">플래너 등록신청이<br>완료되었습니다.</h5>
                <p  id="pln-desc" class="plnr-apply-status__text">신청 완료 후, 관리자의 검수를 통하여<br> 등록이 완료됩니다.<br>검수 기간은 약3일~7일이 소요됩니다.<br>등록 완료 시, 프로필 관리가 가능합니다.</p>
                @elseif($pln_state[0]->state == 2)
                <h5 id="pln-header" class="plnr-apply-status__tit">플래너 등록에<br>실패하셨습니다.</h5>
                <p  id="pln-desc" class="plnr-apply-status__text">플래너 권한이 비활성화 되었습니다. <br/> 사유 : {{ $pln_state[0]->pln_state_info }}</p>
                <button id="pln-btn-reg" type="button" class="plnr-apply-status__button" onClick="location.href='/planner/reg'">플래너 재신청하기</button>
                @endif
            @else
            <h5 id="pln-header" class="plnr-apply-status__tit">플래너란 무엇인가요?</h5>
            <p  id="pln-desc" class="plnr-apply-status__text">여행자들의 여행일정, 지역과 취향에 맞게<br>나만의 맞춤형 여행을 만들어주는 트리픽<br>(Tripick)의 공식 파트너 입니다.</p>
            <button id="pln-btn-reg" type="button" class="plnr-apply-status__button" onClick="location.href='/planner/reg'">
            플래너 신청하기</button>
            @endif
        </div>
        <!-- end -->

        <!-- 플래너 신청완료했을 때 -->
        <!-- <div class="plnr-apply-status plnr-apply-status--02">
        <h5 class="plnr-apply-status__tit">플래너 등록신청이<br>완료되었습니다.</h5>
        <p class="plnr-apply-status__text">신청 완료 후, 관리자의 검수를 통하여 등록이 완료됩니다.<br>검수 기간은 약 n~n일이 소요됩니다.<br>등록 완료 시, 프로필 관리가 가능합니다.</p>
    </div> -->
        <!-- end -->

        <!-- 플래너 등록 실패할 때 -->
        <!-- <div class="plnr-apply-status plnr-apply-status--03">
        <h5 class="plnr-apply-status__tit">플래너 등록에<br>실패하셨습니다.</h5>
        <p class="plnr-apply-status__text">이미지 판별이 어렵습니다. 조금 더 명확한<br>이미지에 대해 선정해주세요,</p>
    </div> -->
        <!-- end -->

        <div class="plnr-ani__earth">
            <div class="plnr-ani__earth__point">
                <img src="/img/logo/logo-symbol-maincolor.svg" alt="logo" class="plnr-ani__earth__point-symbol">
                <span class="plnr-ani__earth__point-dot"></span>
            </div>
            <img src="/img/bg/bg-home-map01.svg" alt="map 01" class="plnr-ani__earth__img plnr-ani__earth__img01">
            <img src="/img/bg/bg-home-map02.svg" alt="map 02" class="plnr-ani__earth__img plnr-ani__earth__img02">
            <img src="/img/bg/bg-home-map03.svg" alt="map 03" class="plnr-ani__earth__img plnr-ani__earth__img03">
            <img src="/img/bg/bg-home-line.svg" alt="map 04" class="plnr-ani__earth__img plnr-ani__earth__img04">
        </div>
    </div>

</div>

@include('nav.nav_user')
@endsection

@section('script')
<script>
$(function() {
    //플래너 상태체크
    function checkStatus() {
        $.ajax({
                url: "/api/plnr/plnrstate",
                data:  null,
                method: "GET",
                dataType: "json"
            })
            .done(function(res) {
                //정상회신
                if(res.state ==1){
                    var query = res.query[0];
                    if(query != null){
                        if(query.state ==1){
                            location.href ="/planner/profile";
                        }
                        
                    }else{
                        
                    }


                }else{
                    //회신오류

                }
            }) 
            .fail(function(xhr, status, errorThrown) {
                
             console.log(xhr);
            }) // 
            .always(function(xhr, status) {
            });
    }

    checkStatus();
});
</script>
@endsection