@extends('theme.basic.pc.layouts.app')

@section('content')
<div class="register_wrap">
    <div class="register_con">
        <div class="register_box">
            <h2>회원가입</h2>
            <ul>
                <li class="active" data-register="personal">개인회원</li>
                <li data-register="company">기업회원</li>
            </ul>
            <div class="form_wrap">
                <form method="POST" id="register_form" action="{{route('register')}}">
                    @csrf
                    <input type="hidden" name="register_type" value="1">
                    <h4 class="blue_label">로그인 정보</h4>
                    <div class="reg_inp">
                        <label for="g_email">이메일</label>
                        <div>
                            <input type="email" id="g_email" required="required" />
                        </div>
                        <span>※정확한 이메일 주소를 입력하세요. 허위 계정일 경우 추후 인증메일을 받아볼 수 없으며, 거래 제한 및 입출금이 금지됩니다.</span>
                    </div>
                    <div class="reg_inp">
                        <label for="g_password">비밀번호 입력</label>
                        <div>
                            <input type="password" id="g_password" required="required" />
                        </div>
                    </div>
                    <div class="reg_inp">
                        <label for="g_password_confirm">비밀번호 확인</label>
                        <div>
                            <input type="password" id="g_password_confirm" required="required" />
                        </div>
                        <div class="password_correct_status">
                            <span class="correct" style="display:none;">비밀번호가 일치합니다.</span>
                            <span class="incorrect" style="display:none;">비밀번호가 일치하지 않습니다.</span>
                        </div>
                    </div>
                    <div class="reg_inp">
                        <label for="g_nickname">닉네임</label>
                        <div class="check_btn_div">
                            <input type="text" id="g_nickname" required="required" />
                            <button type="button">중복확인</button>
                        </div>
                    </div>
                    <div class="reg_inp">
                        <label for="g_mobile_number">휴대폰 본인인증</label>
                        <div class="check_btn_div">
                            <input type="text" id="g_mobile_number" required="required" />
                            <button type="button">본인인증</button>
                        </div>
                    </div>
                    <div class="reg_inp">
                        <label for="g_name">이름</label>
                        <div>
                            <input type="text" id="g_name" required="required" />
                        </div>
                    </div>
                    <div class="company_register_form">

                    </div>
                    
                    <div class="register_agree_box">
                        <div class="register_agree_inp agree_check_box">
                            <p>이용약관</p>
                            <input type="checkbox" id="agree_1"><label for="agree_1">스포와이드 거래소 서비스 이용약관에 동의합니다.(필수)</label> <a>전문보기 ></a>
                        </div>
                        <div class="register_agree_inp agree_check_box">
                            <p>개인정보처리방침</p>
                            <input type="checkbox" id="agree_2"><label for="agree_2">스포와이드 거래소 개인정보 수집 및 이용에 동의합니다.(필수)</label> <a>전문보기 ></a>
                        </div>
                        <div class="register_agree_inp agree_check_box">
                            <p>마케팅 수신 동의</p>
                            <input type="checkbox" id="agree_3"><label for="agree_3">스포와이드 거래소 마케팅 수신에 동의합니다.(선택)</label>
                        </div>
                    </div>
                    <button type="submit">회원가입 완료</button>
                </form>
            </div>
        </div>
    </div>
</div>

<template id="company_register_form">
    <h4 class="blue_label">기업 정보</h4>
    <div class="reg_inp">
        <label for="company_name">회사명</label>
        <div>
            <input type="text" name="company_name" id="company_name" required="required" />
        </div>
    </div>
    <div class="reg_inp">
        <label for="ceo_name">대표자명</label>
        <div>
            <input type="text" name="ceo_name" id="ceo_name" required="required" />
        </div>
    </div>
    <div class="reg_inp">
        <label for="business_number">사업자 등록번호</label>
        <div>
            <input type="text" name="business_number" id="business_number" required="required" />
        </div>
    </div>
    <div class="reg_inp">
        <label for="company_phone_number">사업장 전화번호</label>
        <div>
            <input type="text" name="company_phone_number" id="company_phone_number" required="required" />
        </div>
    </div>
    <div class="reg_inp">
        <label for="company_address">사업장 주소</label>
        <div class="check_btn_div">
            <input type="text" name="company_address" id="company_address" required="required" />
            <button type="button">주소찾기</button>
        </div>
    </div>
    <div class="reg_inp">
        <label for="company_detail_address">상세 주소</label>
        <div>
            <input type="text" name="company_detail_address" id="company_detail_address" required="required" />
        </div>
    </div>
</template>

<script>
    $('.register_wrap .register_con .register_box>ul li').on('click', function(){
        $('.register_wrap .register_con .register_box>ul li').removeClass('active');
        $(this).addClass('active');

        var company_register_form = $('.company_register_form');

        if( $(this).data('register') == 'personal' ){
            company_register_form.html('');
            $('input[name="register_type"]').val(1);
        }else if( $(this).data('register') == 'company' ){
            var templete = $($('#company_register_form').html());
            company_register_form.append(templete);
            $('input[name="register_type"]').val(2);
        }
    });

    $('input[type="password"]').on('keyup', function(){
        if( $('#g_password').val() == $('#g_password_confirm').val() ){
            $('.password_correct_status .incorrect').hide();
            $('.password_correct_status .correct').show();
        }else{
            $('.password_correct_status .correct').hide();
            $('.password_correct_status .incorrect').show();
        }
    });
</script>
@endsection
