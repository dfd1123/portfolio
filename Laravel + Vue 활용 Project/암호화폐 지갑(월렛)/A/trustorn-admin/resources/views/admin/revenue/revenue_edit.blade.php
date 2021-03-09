@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
  이번달 수익률 등록
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{$date}} 수익률 등록/수정
	</div>
	<div class="card-body">
		<form id="revenue_edit_submit" method="post" action="{{route('admin.monthly_revenue_update')}}">
			@csrf
      <input type="hidden" name="date" value="{{$date}}" />
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
            <tr>
                <th style="width:10%;">{{$date}} 투자수익(USD)</th>
                <td>
                  <input type="text" name="revenue" class="form-control tsa-input-st" value="{{isset($revenue->revenue) ? $revenue->revenue : 0 }}" required="required"/>
                </td>
            </tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button id="revenue_edit_submit_button" type="submit" class="btn btn-default mint_btn">
          확인
				</button>
			</div>
		</form>
	</div>
</div>

@endsection

@section('script')

<script>
	$(function() {
		$('#revenue_edit_submit').on('submit', function(e) {
			if(confirm('확인버튼을 누르면 해당 월의 투자수익이 결정되며 회원들에게 비율만큼의 수익금이 현시세 기준 이더로 분배됩니다. 한번 실행하면 되돌릴 수 없습니다. 정말로 진행하시겠습니까?')){
				$('#revenue_edit_submit_button').attr('disabled', true).text('진행중...');
				return true;
			}

			return false;
		});
	});
</script>

@endsection
