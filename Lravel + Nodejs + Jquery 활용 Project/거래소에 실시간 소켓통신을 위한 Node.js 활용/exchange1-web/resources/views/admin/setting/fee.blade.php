@extends('admin.layouts.app')

@section('content')


	<ol class="breadcrumb tsa-top-tit">
		<li class="breadcrumb-item active">
		{{ __('setting.fees_set') }}
		</li>
	</ol>
	
	<form method="post" enctype="multipart/form-data" action="{{route('admin.fee_update')}}">
	
		@csrf
		<div class="tsa-out-box com">
			
			<div class="form-group tsa-w-3 feepct-group posi_rlt">
				<label class="tsa-label-st">{{ __('setting.fees') }}</label>
				<input type="number" class="form-control tsa-input-st" name="trade_fee" step="0.01" value="{{floatval($settings->buy_comission)}}"/>
				<i>%</i>
			</div>
			
			<div class="form-group tsa-w-full">
				<label class="tsa-label-st">{{ __('setting.out_fees') }}</label>
				
				<div id="withdraw_fees">
					@forelse($coins as $coin)
						<div class="flex_cdrn">
							<label class="coinfee_label">{{$coin->name}}</label><input type="number" class="coinfee_input" step="any" name="{{"send_fee_$coin->id"}}" value="{{floatval($coin->send_fee)}}" />
						</div>
					@empty
					{{ __('setting.no_coin') }}
					@endforelse
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

@section('script')
<script>
	$(function() {
		$('#btn_save').click(function() {
			var tradeFee = $('input[name=trade_fee]')[0];
			if(!validate_trade_fee(tradeFee)) {
				tradeFee.focus();
				return false;
			}

			var withdrawFees = $('#withdraw_fees input').get();
			for(var i = 0; i < withdrawFees.length; i++) {
				if(!validate(withdrawFees[i])) {
					withdrawFees[i].focus();
					return false;
				}
			}
			return true;
		});
	});

	function validate(input) {
		var fee_input = $(input).val();
		
		if(fee_input == '') {
			alert("{{ __('setting.setting_sentence_1') }}");
			return false;
		}
		
		if((fee_input + '.').split('.')[1].length > 8) {
			alert("{{ __('setting.setting_sentence_2') }}");
			return false;
		}
		
		var fee = parseFloat(fee_input);
		if(fee == NaN) {
			alert("{{ __('setting.setting_sentence_3') }}");
			return false;
		}
		
		if(fee < 0) {
			alert("{{ __('setting.setting_sentence_4') }}");
			return false;
		}
		
    	return true;
	}

	function validate_trade_fee(input) {
		var trade_fee_input = $(input).val();
		
		if(trade_fee_input == '') {
			alert("{{ __('setting.setting_sentence_1') }}");
			return false;
		}
		
		if((trade_fee_input + '.').split('.')[1].length > 2) {
			alert("{{ __('setting.setting_sentence_5') }}");
			return false;
		}
		
		var trade_fee = parseFloat(trade_fee_input);
		if(trade_fee == NaN) {
			alert("{{ __('setting.setting_sentence_3') }}");
			return false;
		}
		
		if(trade_fee < 0) {
			alert("{{ __('setting.setting_sentence_7') }}");
			return false;
		}
				
    	return true;
	}
</script>
@endsection