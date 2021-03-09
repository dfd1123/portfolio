@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	유튜브관리
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	유튜브추가
	</div>
	<div class="card-body">
		<form enctype="multipart/form-data" class="event_edit_adm_form" id="youtube_add_form" method="post" action="{{route('admin.youtube_store')}}">
			@csrf
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;">목록 명칭</th>
							<td>
							<input type="text" name="sub_text" class="form-control tsa-input-st" required="required"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('event.title')}}1</th>
							<td>
							<input type="text" name="title2" class="form-control tsa-input-st" required="required"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('event.title')}}2</th>
							<td>
								<input type="text" name="title" class="form-control tsa-input-st" value="" required="required"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">youtube영상 id 값</th>
							<td>
                                <input type="text" name="url" class="form-control tsa-input-st" placeholder="예시 : nYUOw0IT_ec" value="" required="required"/>
                                반드시 영상 id값만 넣으셔야됩니다. 예시로 영상 URL https://www.youtube.com/watch?v=nYUOw0IT_ec 중 nYUOw0IT_ec 가 id 값입니다.
							</td>
						</tr>
						<tr>
							<th style="width:10%;">부제</th>
							<td>
								<input type="text" name="sub_title" class="form-control tsa-input-st" value="" required="required"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">콘텐츠 A</th>
							<td>
								<input type="text" name="contents_a" class="form-control tsa-input-st" value="" required="required"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">콘텐츠 B</th>
							<td>
								<input type="text" name="contents_b" class="form-control tsa-input-st" value="" required="required"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">콘텐츠 C</th>
							<td>
								<input type="text" name="contents_c" class="form-control tsa-input-st" value="" required="required"/>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn" onclick="return checkInputs();">
				{{ __('event.add1')}}
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{url()->previous()}}'">
				{{ __('event.gg')}}
				</button>
			</div>
		</form>
	</div>
</div>

@endsection

@section('script')
<script>

</script>
@endsection
