@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
  회사 정보
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	회사 정보 관리
	</div>
	<div class="card-body">
		<form method="post" action="{{route('admin.company_info_update')}}">
			@csrf
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
            <tr>
                <th style="width:10%;">회사명</th>
                <td>
                  <input type="text" name="company_name" class="form-control tsa-input-st" value="{{$company->company}}" required="required"/>
                </td>
            </tr>
            <tr>
                <th style="width:10%;">대표자명</th>
                <td>
                  <input type="text" name="ceo_name" class="form-control tsa-input-st" value="{{$company->ceo}}" required="required"/>
                </td>
            </tr>
            <tr>
                <th style="width:10%;">사업자번호</th>
                <td>
                  <input type="text" name="business_number" class="form-control tsa-input-st" value="{{$company->business_num}}" required="required"/>
                </td>
            </tr>
            <tr>
                <th style="width:10%;">주소</th>
                <td>
                  <input type="text" name="address" class="form-control tsa-input-st" value="{{$company->address}}" required="required"/>
                </td>
            </tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
          수정
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="history.back()">
				  취소
				</button>
			</div>
		</form>
	</div>
</div>

@endsection

