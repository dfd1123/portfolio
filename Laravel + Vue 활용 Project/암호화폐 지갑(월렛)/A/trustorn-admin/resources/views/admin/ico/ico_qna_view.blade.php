@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
        ICO 문의 보기
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
    ICO 문의 보기
	</div>
	<div class="card-body">
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;">문의자명</th>
							<td>
								{{$qna->name}}
							</td>
						</tr>
						<tr>
							<th style="width:10%;">문의자 이메일</th>
							<td>
								{{$qna->email}}
							</td>
            </tr>
            <tr>
							<th style="width:10%;">문의자 내용</th>
							<td>
								{{$qna->contents}}
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="button" class="btn btn-default mint_btn" onclick="history.back()">
				  이전으로
				</button>
			</div>
	</div>
</div>

@endsection

