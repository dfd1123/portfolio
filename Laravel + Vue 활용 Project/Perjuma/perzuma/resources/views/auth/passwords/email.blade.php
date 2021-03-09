@extends('auth.passwords.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            <div>
                                이메일 전송을 완료했습니다.<br/>
                                전송된 이메일 링크를 클릭하여 <br/>
                                비밀번호를 변경해 주세요
                            </div>
                            <button class="homebtn" onclick="location.href='/login';">
                                홈으로
                            </button>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right title_text">{{ __('비밀번호 찾기') }}</label>
                            <p class="info_text">* 찾고자 하는 계정 이메일(아이디)를 입력해주세요</p>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                placeholder="계정 이메일">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('이메일 전송') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .title_text{
        margin: 1em 0;
        font-size: 1.3em;
        font-weight: 600;
    }
    .info_text{
        padding: 0 15px;
        color: #007bd2;
        font-size: 0.8em;
    }
    .alert{
        margin-bottom:inherit !important;
        margin: 1em 0;
        text-align:center;
    }
    .homebtn{
        margin-top: 1em;
        padding: 0.8em 1.5em;
        background-color: #fff;
        border: none;
        color: #000;
        border-radius: 0.5em;
    }
</style>
@endsection
