@extends('layouts.app')

@section('content')
<div class="container ex_padding">
	
	<div class="background_wrap">
		
		<div class="bg_img"></div>
		
		<div class="overlay_effect"></div>
		
	</div>
	
	<div class="row main_wrap">
		<div class="login_container">
			
			<div class="info_box">
				
				<img src="{{ asset('/image/logo/allcoin_login_logo.png') }}" alt="logo"/>
				
				<p>
					암호화폐 사용자의 코인결제 서비스,<br>올코인페이 서비스가 지원합니다.
				</p>
				
				<span><a href="{{ route('register') }}">회원가입 하기</a></span>
			
			</div>
			
			<div class="panel panel-default main_panel">
				
				<div class="panel-body">
					
					<img src="{{ asset('/image/logo/main_logo.svg') }}" alt="logo" class="m_login_logo"/>
					
					<span class="text text-muted">로그인</span>
					
					<br><br>
					<form method="POST" action="{{ route('login') }}">
						@csrf
						<div class="form-group login_form_group">
							<label class="text text-muted">이메일 주소</label>
							<input id="email" type="email"class="form-control input-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
							@error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
						</div>
						<div class="form-group login_form_group">
							<label class="text text-muted">비밀번호</label>
							<input id="password" type="password" class="form-control input-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
							@error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
						</div>
						<div class="login_btn_wrap">
							<div class="login_keep_wrap">
								<div class="checkbox">
									<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        	로그인상태 유지
                                    </label>
								</div>
							</div>
							<div class="center_btn">
								<button type="submit" class="btn btn-warning">로그인</button>
							</div>
						</div>
						<div class="add_btn">
							 @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
									비밀번호를 잊어버리셨나요?
                                </a>
                             @endif
						</div>
					</form>

				</div>
			</div>	
		</div>
	</div>
</div>
@endsection
