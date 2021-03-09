@extends(session('theme').'.pc.layouts.app')

@section('content')

<div class="password_wrap">
    <div class="password_con">
        <div class="password_box">
            <h2>비밀번호 찾기</h2>
            <form method="POST" id="password_for_reset" action="{{ route('password.email') }}">
                @csrf

				@if (session('status'))
					<p>비밀번호 초기화 이메일이 계정에 등록된 주소로 전송되었습니다.<br />받은 편지함에 나타나기까지 다소 시간이 걸릴 수 있으니,<br />재시도 전에 최소 10분 이상 기다려 주세요.</p>
				@else
					<p>비밀번호를 분실하셨나요? 사용자 아이디로 사용하는 이메일 주소를 입력하세요. 새로운 비밀번호를 만들기 위해 이메일을 통한 링크를 받게 됩니다.</p>
				@endif

                <div class="reg_inp">
                    <label for="email">이메일</label>
                    <div>
                        <input type="email" name="email" id="email" class="@error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus />
                    </div>
                </div>
                @if (session('status'))
					<button type="submit" class="bg_wh_btn">이메일 재전송</button>
				@else
					<button type="submit" class="bg_wh_btn">이메일 전송</button>
				@endif
            </form>
            <div>
                <h5 style="margin: 36px 0;font-size: 22px;color: red;">비밀번호 찾기 했는데도 메일이 안온다?</h5>
                <div>
                    <ul style="margin: 20px 10px;line-height: 1.5;">
                        <li>- 비밀번호 찾기를 했는데 메일이 오지 않는 분들</li>
                        <li>- 이메일주소가 오류 또는 허위로 가입하신분들</li>
                        <li>- 비밀번호를 모르시는분들</li>
                    </ul>

                    <ol style="margin: 20px 10px;line-height: 1.5;">
                        <li>1. 스포와이드 공식 텔레그램: <a href="https://t.me/Spowide" target="_blank">https://t.me/Spowide</a> </li>
                        <li>2. 스포와이드 공식 카카오톡 ID: spowide </li>
                        <li>3. 스포와이드 공식 메일: cs@spowide.com </li>
                    </ol>

                    <p style="margin: 40px 0;">셋중에 한가지 선택하셔서</p>

                    <p style="margin: 30px 0;line-height: 2;">
                    본인 성함<br>
                    휴대폰 번호<br>
                    기존 이메일 주소<br>
                    적어서 보내주세요<br>
                    </p>

                    <p>임시 비밀번호를 발송해드리겠습니다.</p>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="overlay" style="display:none;"></div>
<div class="sending_progress_wrap" style="display:none;">
    <div class="box">
        <div class="border one"></div>
        <div class="border two"></div>
        <div class="border three"></div>
        <div class="border four"></div>

        <div class="line one"></div>
        <div class="line two"></div>
        <div class="line three"></div>
    </div>
</div>

<script>

	$(document).ready(function(){
		@if (session('status'))
			swal({
				title: '{{ __('message.mail_send') }}',
				text: '{{ __('message.confirm_your_email') }}',
				icon: "success",
				button: '{{ __('message.ok') }}',
			});
		@endif

		@if ($errors->has('email'))
			swal({
				text: '존재하지 않는 이메일입니다.',
				icon: "warning",
				button: '{{ __('message.ok') }}',
			});
		@endif
	})

	$('#password_for_reset').submit(function(){
		$('.overlay').show();
		$('.sending_progress_wrap').show();

		return true;
	})

	$('input[type="email"]').on('keyup', function(){
        if( $('input[type="email"]').val() != '' && $('input[type="password"]').val() != '' ){
            $('.bg_blue_btn').removeClass('disable');
        }else{
            $('.bg_blue_btn').addClass('disable');
        }
    });

    $('input[type="password"]').on('keyup', function(){
        if( $('input[type="email"]').val() != '' && $('input[type="password"]').val() != '' ){
            $('.bg_blue_btn').removeClass('disable');
        }else{
            $('.bg_blue_btn').addClass('disable');
        }
    });
</script>

@endsection
