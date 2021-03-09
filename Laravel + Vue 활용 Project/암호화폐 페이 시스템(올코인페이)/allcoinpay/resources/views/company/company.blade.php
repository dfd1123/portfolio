@extends('layouts.app')
@section('content')
<div class="container ex_padding ex_container pg_wrapper com_info_write">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default box_style">
				<div class="panel-body">
					<h3>사업자정보 작성하기</h3>
					@if(isset($company->company_confirm))
						@if($company->company_confirm == 0)
							<h3 style='color:blue;'>※상태 : 승인대기중※</h3>
						@elseif($company->company_confirm == 1)
							<h3 style='color:blue;'>※상태 : 승인완료 (현재 완료된 상태에서 수정을 하시면 다시 승인 대기로 바뀝니다.)※</h3>
						@elseif($company->company_confirm == 2)
							<h3 style='color:red;'>※거부사유 : {{ $company->company_reject }}※</h3>
							<script>alert('{{ $company->company_reject }} 와 같은 사유로 인해 거부되었습니다. 수정후 다시 제출해 주세요.');</script>
						@endif
					@endif
					<form action="{{ route('company.create') }}" method="POST"  enctype="multipart/form-data">
					@csrf
						<div class="row">
							<div class="form-group">
								<input type="hidden" name="type"  value="change_config" >
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<div class="line_align">
										<div class="form-group">
											<label class="label_st">회사명(상호명)</label>
											<input type="text" class="form-control" name="company_name"  id="com_name" value="{{ isset($company->company_name) ? $company->company_name : '' }}" required>
										</div>
									</div>
									<div class="line_align half_line">
										<div class="form-group">
											<label class="label_st">사업자등록번호</label>
											<input type="text" class="form-control" name="company_number"  id="com_enroll_num" value="{{ isset($company->company_number) ? $company->company_number : '' }}" required>
											<p class="com_type_choice">
												<input type="radio" name="company_type" id="priv_com" value="1" {{ isset($company->company_type) ? ($company->company_type == 1 ? 'checked' : '')  : '' }} required>
												<label for="priv_com">개인사업자</label>

												<input type="radio" name="company_type" id="law_com" value="2" {{ isset($company->company_type) ? ($company->company_type == 2 ? 'checked' : '')  : '' }} required>
												<label for="law_com">법인사업자</label>

												<input type="radio" name="company_type" id="nonpro_com" value="3" {{ isset($company->company_type) ? ($company->company_type == 3 ? 'checked' : '')  : '' }} required>
												<label for="nonpro_com">비영리법인</label>
											</p>
										</div>
									</div>
									<div class="line_align">
										<div class="form-group">
											<label class="label_st">업종</label>
											<input type="text" class="form-control" name="company_sector"  id="type_of_com"  value="{{ isset($company->company_sector) ? $company->company_sector : '' }}" required>
										</div>
									</div>
									<div class="line_align">
										<div class="form-group">
											<label class="label_st">사업자등록증</label>
											<input type="file" class="form-control" name="file0" style="padding:2px;" required>
											@if(isset($company->company_file))
											<button type="button" class="btn btn-primary" style = "margin: 8px 23px;" data-toggle="modal" data-target="#img_modal" data-title="사업자등록증" data-img="{{ isset($company->company_file) ? $company->company_file : '' }}">
												파일 보기
											</button>
											@endif
											
										</div>
									</div>
									<div class="line_align">
										<div class="form-group">
											<label class="label_st">신분증</label>
											<input type="file" class="form-control" name="file1" style="padding:2px;" required>
											@if(isset($company->document_file))
											<button type="button" class="btn btn-primary" style = "margin: 8px 23px;" data-toggle="modal" data-target="#img_modal" data-title="신분증" data-img="{{ isset($company->document_file) ? $company->document_file : '' }}">
												파일 보기
											</button>
											@endif
										</div>
									</div>
									<div class="line_align">
										<div class="form-group">
											<label class="label_st">계좌번호</label>
											<input type="text" class="form-control" name="account_num"  id="account_num"  value="{{ isset($company->account_num) ? $company->account_num : '' }}" required>
										</div>
									</div>
									<div class="line_align">
										<div class="form-group">
											<label class="label_st">은행이름</label>
											<input type="text" class="form-control" name="account_bank"  id="account_bank"  value="{{ isset($company->account_bank) ? $company->account_bank : '' }}" required>
										</div>
									</div>
									<div class="line_align">
										<div class="form-group">
											<label class="label_st">통장사본</label>
											<input type="file" class="form-control" name="file2" style="padding:2px;" required>
											@if(isset($company->account_file))
											<button type="button" class="btn btn-primary" style = "margin: 8px 23px;" data-toggle="modal" data-target="#img_modal" data-title="통장사본" data-img="{{ isset($company->account_file) ? $company->account_file : '' }}">
												파일 보기
											</button>
											@endif
										</div>
									</div>
									<div class="btn_center">
										<button type="submit" class="btn btn-warning" name="btc_post_ad">
											@if(isset($company))
											수정
											@else
											제출
											@endif
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="img_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal_title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<img style="width:100%;"/>
			</div>
		</div>
	</div>
</div>


	

@endsection

@section('script')
	<script>
		$('#img_modal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget); // Button that triggered the modal
			var title = button.data('title'); // Extract info from data-* attributes
			var img = button.data('img');
			// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
			// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			var modal = $(this);
			modal.find('.modal-title').text(title);
			modal.find('.modal-body img').attr('src',img);
		});
		
	</script>
@endsection