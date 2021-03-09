@extends('admin.layouts.app')

@section('content')


	<ol class="breadcrumb tsa-top-tit">
		<li class="breadcrumb-item active">
		    {{__('coin_name.'.$auto->cointype)}} 자동 거래 설정
		</li>
	</ol>
	
	<form method="post" action="{{route('admin.auto_bot_update', $auto->id)}}">
	
		@csrf
		<div class="tsa-out-box">
			
			<div class="form-group">
                <label class="tsa-label-st">1분간 딜레이 시간 간격</label>
                <div class="controll_box">
                    <input type="number" class="form-control" name="time_min" step="1" value="{{($auto->time_min)}}"/>초 ~ 
                    <input type="number" class="form-control" name="time_max" step="1" value="{{($auto->time_max)}}"/>초
                </div>
			</div>
			
			<div class="form-group">
                <label class="tsa-label-st">매물 양 조절</label>
                <div class="controll_box">
                    <input type="number" class="form-control" name="amt_min" step="1" value="{{($auto->amt_min)}}"/>개 ~ 
                    <input type="number" class="form-control" name="amt_max" step="1" value="{{($auto->amt_max)}}"/>개
                </div>
            </div>
            <div class="form-group">
                <label class="tsa-label-st">소수점 조절</label>
                <div class="controll_box">
                    <input type="number" class="form-control alone_inp" name="amt_decimal" step="0.00000001" value="{{($auto->amt_decimal)}}"/>
            
                </div>
            </div>
            <div class="form-group">
                <label class="tsa-label-st">거래할 시세 범위</label>
                <div class="controll_box">
                    <input type="number" class="form-control" name="range_min" step="1" value="{{($auto->range_min)}}"/>% ~ 
                    <input type="number" class="form-control" name="range_max" step="1" value="{{($auto->range_max)}}"/>%
                </div>
            </div>

			<!--
			<h5>KRW출금 수수료</h5>
			<input type="number" name="withdrawal_fee" step="0.01" />
			-->
				<button id="btn_save" type="submit" class="btn btn-default mint_btn">
				{{ __('setting.modify') }}
				</button>
			
		</div>
	</form>

@endsection
