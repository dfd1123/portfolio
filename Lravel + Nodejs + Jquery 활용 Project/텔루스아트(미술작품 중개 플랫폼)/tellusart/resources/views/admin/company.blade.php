@extends('admin.layouts.app')

@section('content')

<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">사업자정보 관리</li>
</ol>

<form method="post" action="{{route('admin.company_update')}}">

	@csrf
<div class="tsa-out-box com">
  <div class="form-group tsa-w-2">
    <label for="service_name" class="tsa-label-st">서비스이름</label>
    <input type="text" id="service_name" name="service_name" class="form-control tsa-input-st" value="{{ isset($company) ? $company->service_name : '' }}" required="required" />
  </div>
  <div class="form-group tsa-w-2">
    <label for="company_name" class="tsa-label-st">회사명</label>
    <input type="text" id="company_name" name="company_name" class="form-control tsa-input-st" value="{{  isset($company) ? $company->company_name : '' }}" required="required" />
  </div>
  <div class="form-group tsa-w-2">
    <label for="ceo_name" class="tsa-label-st">대표자명</label>
    <input type="text" id="ceo_name" name="ceo_name" class="form-control tsa-input-st" value="{{  isset($company) ? $company->ceo_name : '' }}" required="required" />
  </div>
  <div class="form-group tsa-w-2">
    <label for="company_email" class="tsa-label-st">대표 이메일</label>
    <input type="text" id="company_email" name="company_email" class="form-control tsa-input-st" value="{{  isset($company) ? $company->company_email : '' }}" required="required" />
  </div>
  <div class="form-group tsa-w-3">
    <label for="business_number" class="tsa-label-st">사업자등록번호</label>
    <input type="text" id="business_number" name="business_number" class="form-control tsa-input-st" placeholder="582-33-00530(-포함)" value="{{  isset($company) ? $company->business_number : '' }}" required="required" />
  </div>
  <div class="form-group tsa-w-3">
    <label for="tellsell_number" class="tsa-label-st">통신판매업신고</label>
    <input type="text" id="tellsell_number" name="tellsell_number" class="form-control tsa-input-st" placeholder="ex)제2018-부산부산진-0548호  (-포함)" value="{{  isset($company) ? $company->tellsell_number : '' }}" required="required" />
  </div>
  <div class="form-group tsa-w-3">
    <label for="phone_num" class="tsa-label-st">전화번호</label>
    <input type="text" id="phone_num" name="phone_num" class="form-control tsa-input-st" placeholder="ex)051-978-2000 (-포함)" value="{{  isset($company) ? $company->phone_num : '' }}" required="required" />
  </div>
  <div class="form-group tsa-w-3">
    <label for="fax_num" class="tsa-label-st">팩스번호</label>
    <input type="text" id="fax_num" name="fax_num" class="form-control tsa-input-st" placeholder="ex)051-978-2001 (-포함)" value="{{  isset($company) ? $company->fax_num : '' }}" required="required" />
  </div>
  <div class="form-group tsa-w-3">
    <label for="address" class="tsa-label-st">주소</label>
    <input type="text" id="address" name="address" class="form-control tsa-input-st" value="{{  isset($company) ? $company->address : '' }}"  required="required" />
  </div>
  <div class="form-group tsa-w-3">
    <label for="infor_admin" class="tsa-label-st">개인정보관리 책임자</label>
    <input type="text" id="infor_admin" name="infor_admin" class="form-control tsa-input-st" value="{{  isset($company) ? $company->infor_admin : '' }}" required="required" / >
  </div>

  <div class="form-group tsa-w-3">
    <label for="account_bank" class="tsa-label-st">무통장입금 은행</label>
    <input type="text" id="account_bank" name="account_bank" class="form-control tsa-input-st" value="{{  isset($company) ? $company->account_bank : '' }}" required="required" / >
  </div>
  <div class="form-group tsa-w-3">
    <label for="account_number" class="tsa-label-st">무통장입금 계좌 번호</label>
    <input type="text" id="account_number" name="account_number" class="form-control tsa-input-st" value="{{  isset($company) ? $company->account_number : '' }}" required="required" / >
  </div>
  <div class="form-group tsa-w-3">
    <label for="account_user" class="tsa-label-st">무통장입금 예금주 명</label>
    <input type="text" id="account_user" name="account_user" class="form-control tsa-input-st" value="{{  isset($company) ? $company->account_user : '' }}" required="required" / >
  </div>
  <button type="submit" class="btn btn-default org_btn">수정하기</button>
</div>
</form>



@endsection