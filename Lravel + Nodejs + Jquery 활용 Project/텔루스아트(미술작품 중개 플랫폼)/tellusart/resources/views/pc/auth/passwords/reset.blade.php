@extends('pc.layouts.app')

@section('content')
<div class="sub_spot member" style="height: 278px;">
	<h2>비밀번호 재설정</h2>
</div>
<div id="container" style="margin-top: 278px;">
	<div class="loginbox" style="max-width: 500px;">
		<div class="psearch">
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
                {!! csrf_field() !!}

                <input type="hidden" name="token" value="{{$token}}">

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">자신의 아이디(이메일)을 입력해주세요.</label>

                    <div class="col-md-6">
                        <input type="email" class="form-control required kr" name="email" value="{{ $email or old('email') }}">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">비밀번호</label>

                    <div class="col-md-6">
                        <input type="password" id="password" class="form-control required kr" name="password">

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">비밀번호 확인</label>
                    <div class="col-md-6 pwd_confirm_wrap">
                        <input type="password" id="password-confirm" class="form-control required kr" name="password_confirmation">
                        <div class="pwd_confirm">
                            <span class="correct" style="display:none;">일치</span>
                            <span class="incorrect" style="display:none;">불일치</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary joinbt">
                            <i class="fa fa-btn fa-refresh"></i>비밀번호 재설정
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
	.psearch {
		display: block;
		margin: 0 auto;
		width: 100%;
		background: #efefef;
		padding: 20px;
		box-sizing: border-box;
	}
	.psearch p {
		display: block;
		text-align: center;
		padding: 10px 0;
	}

	button.joinbt {
		display: block;
		line-height: 51px;
		background: #413e70;
		border: 1px solid #413e70;
		color: #fff;
		font-size: 15px;
		width: 100%;
		border-radius: 5px;
		margin-top: 5px;
		text-align: center;
	}
	.both_btn_group button, .both_btn_group a {
		display: inline-block !important;
		vertical-align: middle;
		width: 49% !important;
	}
	.both_btn_group a.cancel_btn {
		background: #999;
		border: 1px solid #999;
		line-height: 51px;
		color: #fff;
		font-size: 15px;
		border-radius: 5px;
		margin-top: 5px;
		text-align: center;
    }
    .control-label{
        text-align: left;
        display: block;
        padding: 10px 5px;
        font-size: 14px;
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

	@media screen and (max-width: 640px) {
		.psearch {
			width: 98%;
		}
	}
</style>


@endsection
