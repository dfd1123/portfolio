@extends('common.'.config('device.device').'.layouts.app')

@section('content')


<div class="container">
    <div class="row justify-content-center auth_card register_auth_card">
        <div class="col-md-7">
            <div class="card ">
            	
                <div class="card-body">

            		<h1 class="logo">
	            		<!--img src="{{ secure_asset('/storage/image/homepage/sharebits-logo-02.svg')}}" alt="logo"-->
            		</h1>
            		
            		<p class="card_title">JOIN US</p>
            		
            		<p class="ment">서비스 약관 및 개인정보 수집·이용에 동의해주세요.</p>
            		
					<form method="get" action="{{route('register')}}" id="register_agree">
						<div class="in_register_wrapper">
							<div class="agree_con">
								<div class="top_tit">
									<label for="register_agree1">Exchange1 거래소 서비스 이용약관 동의 (필수)</label><input class="grayCheckbox" type="checkbox" id="register_agree1" name="register_agree1" value="0" />
								</div>
								<textarea>약관내용1</textarea>
							</div>
							
							
							<div class="agree_con">
								<div class="top_tit">
									<label for="register_agree2">Exchange1 거래소 개인정보 수집 및 이용동의 (필수)</label><input class="grayCheckbox" type="checkbox"  id="register_agree2" name="register_agree2" value="0" />
								</div>
								<textarea>약관내용2</textarea>
							</div>
							
							
							<div class="top_tit last_top_tit">
								<label for="register_agree3">Exchange1 거래소 마케팅 수신에 동의합니다. (선택)</label><input class="grayCheckbox" type="checkbox" id="register_agree3" name="register_agree3" value="0" />
							</div>
							
							<button type="submit" class="btn_style register_btn_st">NEXT</button>
							
						</div>
					</form>
					
                </div>
            </div>
        </div>
    </div>
</div>



<script>

	@if(session()->has('jsAlert'))
        
        $.alert({
		    title: "알림",
		    content: "{{ session()->get('jsAlert') }}",
		});

	@endif
	
	
		 	
</script>
@endsection
