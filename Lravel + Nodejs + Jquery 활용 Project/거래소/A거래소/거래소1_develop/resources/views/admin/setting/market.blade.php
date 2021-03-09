@extends('admin.layouts.app')

@section('content')


	<ol class="breadcrumb tsa-top-tit">
		<li class="breadcrumb-item active">
		{{ __('setting.mark_info') }}
		</li>
	</ol>
	
	<form method="post" enctype="multipart/form-data" action="{{route('admin.market_update')}}">
	
		@csrf
		<div class="tsa-out-box com">
			<div class="form-group icon_inp" style="width:100%; display:none;">
				<label class="tsa-label-st advc_ment">{{ __('setting.icon') }}
					<span class="font_sz11">{{ __('setting.down') }}</span>
				</label>
				<div class="review_img_group">
					<div class="review_col">
						<label class="tit">{{ __('setting.see') }}</label>
						
							@if(empty($trademarket->service_icon))
							<span class="review_thum bg_trs">
								<i>+</i>{{ __('setting.img') }}
							@else
							<span class="review_thum">
								<img src="{{asset('/storage/image/homepage/'.$trademarket->service_icon)}}" />
							@endif
						</span>
					</div>
				</div>
				<div>
					<input type="file" id="service_icon" name="service_icon" class="form-control tsa-input-st hidden" {{ ($trademarket->service_icon != NULL)? 'value='.$trademarket->service_icon :'' }}>
					<input type="text" class="filename_input tsa-input-st form-control float-left tsa-w-2 mr-2" readonly="readonly" placeholder="{{ __('setting.file') }}"> 
					<label for="service_icon" class="attach_btn">{{ __('setting.img_add') }}</label>
				</div>
			</div>
			
			<div class="form-group logo_inp" style="width:100%; display:none;">
				<label class="tsa-label-st advc_ment">{{ __('setting.logo') }}
						<span class="font_sz11">{{ __('setting.up') }}</span>
				</label>
				<div class="review_img_group">
					<div class="review_col">
						<label class="tit">{{ __('setting.see') }}</label>
						<span class="review_thum">
							@if(empty($trademarket->logo))
								<img src="{{asset('/storage/image/homepage/aicdss_logo_1.svg')}}" />
							@else
								<img src="{{asset('/storage/image/homepage/'.$trademarket->logo)}}" />
							@endif
						</span>
					</div>
				</div>
				<div>
					<input type="file" id="logo" name="logo" class="form-control tsa-input-st hidden" {{ ($trademarket->logo != NULL)? 'value='.$trademarket->logo :'' }} >
					<input type="text" class="filename_input tsa-input-st form-control float-left tsa-w-2 mr-2" readonly="readonly" placeholder="{{ __('setting.file') }}"> 
					<label for="logo" class="attach_btn">{{ __('setting.img_add') }}</label>
				</div>
			</div>
			<div class="form-group" style="width:100%;">
				<label for="service_name" class="tsa-label-st">{{ __('setting.site') }}</label>
				<input type="text" id="service_name" name="title" class="form-control tsa-input-st" {{ ($trademarket->title != NULL)? 'value='.$trademarket->title :'' }} >
			</div>
			<div class="form-group tsa-w-2">
				<label for="service_name" class="tsa-label-st">{{ __('setting.service_name') }}</label>
				<input type="text" id="service_name" name="name" class="form-control tsa-input-st" {{ ($trademarket->name != NULL)? 'value='.$trademarket->name :'' }} >
			</div>
			<div class="form-group tsa-w-2">
				<label for="company_name" class="tsa-label-st">{{ __('setting.company') }}</label>
				<input type="text" id="company_name" name="company" class="form-control tsa-input-st" {{ ($trademarket->company != NULL)? 'value='.$trademarket->company :'' }} >
			</div>
			<div class="form-group tsa-w-2">
				<label for="ceo_name" class="tsa-label-st">{{ __('setting.ceo') }}</label>
				<input type="text" id="ceo_name" name="ceo" class="form-control tsa-input-st" {{ ($trademarket->ceo != NULL)? 'value='.$trademarket->ceo :'' }} >
			</div>
			<div class="form-group tsa-w-2">
				<label for="company_email" class="tsa-label-st">{{ __('setting.ceo_email') }}</label>
				<input type="text" id="company_email" name="infoemail" class="form-control tsa-input-st" {{ ($trademarket->infoemail != NULL)? 'value='.$trademarket->infoemail :'' }} >
			</div>
			<div class="form-group tsa-w-3">
				<label for="business_number" class="tsa-label-st">{{ __('setting.com_number') }}</label>
				<input type="text" id="business_number" name="business_num" class="form-control tsa-input-st" placeholder="ex)582-33-00530(-{{ __('setting.include') }})" {{ ($trademarket->business_num != NULL)? 'value='.$trademarket->business_num :'' }} >
			</div>
			<div class="form-group tsa-w-3">
				<label for="tellsell_number" class="tsa-label-st">{{ __('setting.ts') }}</label>
				<input type="text" id="tellsell_number" name="sybersell_num" class="form-control tsa-input-st" placeholder="ex)제2018-부산부산진-0548호  (-{{ __('setting.include') }})" {{ ($trademarket->sybersell_num != NULL)? 'value='.$trademarket->sybersell_num :'' }} >
			</div>
			<div class="form-group tsa-w-3">
				<label for="phone_num" class="tsa-label-st">{{ __('setting.home_num') }}</label>
				<input type="text" id="phone_num" name="phone_num" class="form-control tsa-input-st" placeholder="ex)051-978-2000 (-{{ __('setting.include') }})" {{ ($trademarket->phone_num != NULL)? 'value='.$trademarket->phone_num :'' }} >
			</div>
			<div class="form-group tsa-w-2">
				<label for="address" class="tsa-label-st">{{ __('setting.address') }}</label>
				<input type="text" id="address" name="address" class="form-control tsa-input-st" value="{{ ($trademarket->address != NULL)? $trademarket->address :'' }}" >
			</div>
			<div class="form-group tsa-w-2">
				<label for="address_detail" class="tsa-label-st">상세주소</label>
				<input type="text" id="address_detail" name="address_detail" class="form-control tsa-input-st" value="{{ ($trademarket->address_detail != NULL)?$trademarket->address_detail :'' }}" >
			</div>
			<div class="form-group tsa-w-2">
				<label for="fax_num" class="tsa-label-st">{{ __('setting.fax') }}</label>
				<input type="text" id="fax_num" name="fax_num" class="form-control tsa-input-st" placeholder="ex)051-978-2001 (-{{ __('setting.include') }})" {{ ($trademarket->fax_num != NULL)? 'value='.$trademarket->fax_num :'' }} >
			</div>
			<div class="form-group tsa-w-2">
				<label for="infor_admin" class="tsa-label-st">{{ __('setting.info_ad') }}</label>
				<input type="text" id="infor_admin" name="infor_manager" class="form-control tsa-input-st" {{ ($trademarket->infor_manager != NULL)? 'value='.$trademarket->infor_manager :'' }} >
			</div>
			<button type="submit" class="btn btn-default mint_btn">
			{{ __('setting.changing') }}
			</button>
		</div>
	</form>

@endsection

@section('script')

@endsection