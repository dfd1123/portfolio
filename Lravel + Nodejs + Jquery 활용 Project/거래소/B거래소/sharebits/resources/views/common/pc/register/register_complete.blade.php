@extends('common.'.config('device.device').'.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center auth_card register_auth_card">
        <div class="col-md-8">
            <div class="card ">
            	
                <div class="card-body">

            		<h1 class="logo">
	            		<img src="{{ secure_asset('/storage/image/homepage/sharebits-logo-02.svg')}}" alt="logo">
            		</h1>
            		
            		<p class="card_title">JOIN US</p>
            		
					<div class="in_register_wrapper">
            		
            		<p class="ment">회원가입이 완료되었습니다. <br>다음 계정으로 쉐어비츠의 모든 서비스를 이용하실 수 있습니다.</p>
                        <div class="form-group row">
                            <div class="col-md-12">
                            	
                            	<div class="form-control register_complete_form">
                            		고객이 가입한 이메일주소
                            		<img class="chk_icon" src="{{ secure_asset('/storage/image/homepage/icon/graycheckbox-02.svg')}}"alt="complete">
                            	</div>
                            </div>
                            
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-12 auth_btn_group">
                                <button type="submit" class="btn_style">
                                    OK
                                </button>
                                    <a class="btn btn-link" href="{{ url('/') }}">
                                        Go sharebits
                                    </a>
                            </div>
                        </div>
                    
                     </div>
            		
            		
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
