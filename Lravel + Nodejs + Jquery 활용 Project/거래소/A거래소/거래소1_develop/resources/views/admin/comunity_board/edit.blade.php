@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	커뮤니티 관리
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	커뮤니티 수정
	</div>
	<div class="card-body">
		<form enctype="multipart/form-data" class="event_edit_adm_form" id="event_add_form" method="post" action="{{route('admin.comunity_manage_update', $comunity->bo_table)}}">
			@csrf
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;">게시판명</th>
							<td>
								<input type="text" name="bo_name" class="form-control tsa-input-st" value="{{$comunity->bo_name}}" required="required"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">테이블명</th>
							<td>
								<input type="text" name="bo_table" class="form-control tsa-input-st" value="{{$comunity->bo_table}}" readonly="readonly" required="required"/>
							</td>
                        </tr>
						<tr>
							<th style="width:10%;">코인종류</th>
							<td>
                                <select name="coin_type">
									<option value="sports" {{($comunity->coin_type == 'sports'?'selected=selected':'')}}>sports</option>
									<option value="public" {{($comunity->coin_type == 'public'?'selected=selected':'')}}>public</option>
								</select>
                                <span>※ 코인 커뮤니티인 경우만 선택해주세요.</span>
							</td>
						</tr>
                        <tr>
							<th style="width:10%;">코인심볼</th>
							<td>
                                <input type="text" name="coin_symbol" class="form-control tsa-input-st" value="{{$comunity->coin_symbol}}"/>
                                <span>※ 코인 커뮤니티인 경우만 입력해주세요.</span>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('event.ifuse')}}</th>
							<td>
								<select class="tsa-select" name="status">
									<option value="1" {{($comunity->status == 1)?'selected=selected':''}}>{{ __('event.use')}}</option>
									<option value="0" {{($comunity->status == 0)?'selected=selected':''}}>{{ __('event.nouse')}}</option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn" >
				{{ __('faq.edit')}}
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{url()->previous()}}'">
				{{ __('event.gg')}}
				</button>
			</div>
		</form>
	</div>
</div>

@endsection
