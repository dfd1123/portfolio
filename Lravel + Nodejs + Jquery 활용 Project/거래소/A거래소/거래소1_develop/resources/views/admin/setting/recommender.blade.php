@extends('admin.layouts.app')

@section('content')


	<ol class="breadcrumb tsa-top-tit">
		<li class="breadcrumb-item active">
		{{ __('setting.cc_set') }}
		</li>
	</ol>
	
	<form method="post" enctype="multipart/form-data" action="{{route('admin.recommender_update')}}">
	
		@csrf
		<div class="tsa-out-box com">
			
			<div class="form-group tsa-w-full">
				<label class="tsa-label-st">{{ __('setting.cc') }}</label>
				<div>
						<label class="mr-4"><input type="radio" name="recommender_yn" class="grayCheckbox mr-2" value="1" {{$settings->recommender_yn == '1' ? 'checked' : ''}} />{{ __('setting.use') }}</label>
						<label><input type="radio" name="recommender_yn" class="grayCheckbox mr-2" value="0" {{$settings->recommender_yn == '0' ? 'checked' : ''}} />{{ __('setting.no_use') }}</label>
				</div>
			</div>
			
			<div class="form-group tsa-w-3 posi_rlt">
				<label class="tsa-label-st">{{ __('setting.cc_money') }}</label>
				<input type="number" class="form-control tsa-input-st" step="any" name="recommender_point" value="{{$settings->recommender_point}}" />
				<i>KRW</i>
			</div>
			
			<div class="form-group tsa-w-full">
			
					<button id="btn_save" type="submit" class="btn btn-default mint_btn">
						{{ __('setting.change') }}
					</button>
			</div>
			
		</div>
		
	</form>

@endsection

@section('script')
<script>
	$(function() {
		$('#btn_save').click(function() {
			var recommender = $('input[name=recommender_point]');
			if(!validate(recommender)) {
				recommender.val('0');
				recommender.focus();
				return false;
			}

			return true;
		});
	});

	function validate(input) {
		var recommender_input = input.val();
		
		if(recommender_input == '') {
			alert("{{ __('setting.setting_sentence_1') }}");
			return false;
		}
		
		if((recommender_input + '.').split('.')[1].length > 8) {
			alert("{{ __('setting.setting_sentence_2') }}");
			return false;
		}
		
		var recommender = parseFloat(recommender_input);
		if(recommender == NaN) {
			alert("{{ __('setting.setting_sentence_3') }}");
			return false;
		}
		
		if(recommender < 0) {
			alert("{{ __('setting.setting_sentence_6') }}");
			return false;
		}
		
    	return true;
	}
</script>
@endsection