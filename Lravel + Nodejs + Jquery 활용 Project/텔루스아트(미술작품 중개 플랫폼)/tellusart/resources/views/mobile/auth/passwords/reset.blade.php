@extends('mobile.layouts.app')

@section('content')
<div class="sub-container">
    <div class="loginbox">
        <div class="psearch">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">자신의 아이디(이메일)을 입력해주세요.</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} required kr" name="email" value="{{ $email ?? old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">비밀번호</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} required kr" name="password" required>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">비밀번호 확인</label>

                    <div class="col-md-6 pwd_confirm_wrap">
                        <input id="password-confirm" type="password" class="form-control required kr" name="password_confirmation" required>
                        <div class="pwd_confirm">
                            <span class="correct" style="display:none;">일치</span>
                            <span class="incorrect" style="display:none;">불일치</span>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary joinbt">
                            비밀번호 재설정
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
     @if($errors->has('email'))
        alert('입력하신 메일 주소를 다시 한번 확인해주세요.');
     @endif

     @if ($errors->has('password'))
        alert('비밀번호가 맞지 않습니다. \n 다시 확인하고 입력해주세요.');
     @endif

     $('#password').keyup(function(){
         if($(this).val() == $('#password-confirm').val()){
             $('.pwd_confirm span').hide();
             $('.pwd_confirm span.correct').show();
         }else{
             $('.pwd_confirm span').hide();
             $('.pwd_confirm span.incorrect').show();
         }
     });

     $('#password-confirm').keyup(function(){
         if($(this).val() == $('#password').val()){
             $('.pwd_confirm span').hide();
             $('.pwd_confirm span.correct').show();
         }else{
             $('.pwd_confirm span').hide();
             $('.pwd_confirm span.incorrect').show();
         }
     });
</script>

<style>
    label{
        text-align: left;
        display: block;
        padding: 1px 5px;
        font-size: 13px;
        font-weight: 600;
    }
    .form-group{
        padding-bottom:10px;
    }
    .pwd_confirm_wrap{
        position:relative;
    }
    .pwd_confirm_wrap .pwd_confirm{
        position: absolute;
        top: 14px;
        right: 15px;
    }
    .pwd_confirm_wrap .pwd_confirm span{
        font-size: 14px;
    }

    .pwd_confirm_wrap .pwd_confirm .correct{
        color:blue;
    }

    .pwd_confirm_wrap .pwd_confirm .incorrect{
        color:red;
    }
</style>
@endsection
