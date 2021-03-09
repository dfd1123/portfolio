@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
          <ol class="breadcrumb tsa-top-tit">
            <li class="breadcrumb-item active">현금 입출금 관리</li>
          </ol>

          <!-- DataTables Example -->
          <div class="card mb-3 tsa-card">
            <div class="card-header">현금 입출금</div>
            <div class="card-body">
	            <div class="usr_search_box tsa-sch-box">
	            	<form method="get" action="">
			            	<select name="keyword_srch">
			            		<option value="">전체</option>
			            		<option value="uid">UID</option>
			            		<option value="name">{{ __('user.name') }}</option>
			            		<option value="email">{{ __('user.email') }}</option>
			            		<option value="mobile">{{ __('user.phone') }}</option>
			            	</select>
			            	<input type="text" name="keyword" />
			            	<button type="submit">{{ __('user.search') }}</button>
		            </form>
	            </div>
				<div class="table-responsive tsa-table-wrap">
                	<table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
						<thead>
		                    <tr>
			                    <th style="width: 5%;">ID</th>
			                    <th style="width: 10%;">Date</th>
			                    <th style="width: 10%;">{{ __('user.name') }}</th>
			                    <th style="width: 10%;">회사이름</th>
			                    <th style="width: 10%;">사업자 번호</th>
			                    <th style="width: 10%;">사업자 종류</th>
			                    <th style="width: 10%;">업종</th>
								<th style="width: 10%;">계좌은행</th>
								<th style="width: 10%;">계좌번호</th>
								<th style="width: 10%;">자료확인</th>
			                    <th style="width: 15%;">동작</th>
		                    </tr>
						</thead>
                  		<tbody>
                  		@forelse($company_lists as $company_list)
						<tr>
							<td>{{$company_list->id}}</td>
							<td>{{$company_list->created_at }}</td>
							<td>{{$company_list->fullname }}</td>
							<td>{{$company_list->company_name }}</td> 
							<td>{{$company_list->company_number }}</td>
							@if($company_list->company_type == 1)
							<td>개인사업자</td>
							@elseif($company_list->company_type == 2)
							<td>법인사업자</td>
							@else
							<td>비영리단체</td>
							@endif
							<td>{{$company_list->company_sector }}</td>
							<td>{{$company_list->account_bank }}</td>
							<td>{{$company_list->account_num }}</td>
							<td>
								<button type="button" class="file_view" data-src="{{asset($company_list->company_file)}}|{{asset($company_list->document_file)}}|{{asset($company_list->account_file)}}">{{ __('user.check') }}</button>
							</td>
							@if($company_list->company_confirm == 0)
							<td>
								<button type="button" class="myButton navy company_confirm" data-id="{{$company_list->id}}">{{ __('user.yes') }}</button>
								<button type="button" class="myButton xbtn company_reject" data-id="{{$company_list->id}}">{{ __('user.no') }}</button>
							</td>
							@elseif($company_list->company_confirm == 2)
							<td>사유 : {{$company_list->company_reject }}</td>
							@else
							<td>승인됨</td>
							@endif
						</tr>
	                    @empty
	                    <tr>
	                    	<td colspan="11" >{{ __('user.user_sentence_1') }}</td>
	                    </tr>
	                    @endforelse
                  </tbody>
                </table>
              </div>
	            @if($company_lists)
					{!! $company_lists->render() !!}
				@endif
			</div>
		<div class="card-footer small text-muted">{{ $datetime }} {{ __('user.user_sentence_2') }}</div>
	</div>
<div id="reject_wrap" class="jw_modal_wrap hidden">
	<div class="jw_overlay"></div>
	<div class="jw_modal_content_wrap like_cux">
		<div class="jw_modal_content">
			<div class="jw_modal_hd">
				<h5>{{ __('user.cer_no') }}</h5>
				<div><i class="fal fa-chevron-down"></i></div>
			</div>
			<div class="jw_modal_bd">
				<div class="content_box">
					<h5><i class="fal fa-chevron-circle-right"></i>{{ __('user.reason') }}</h5>
					<input type="text" name="company_reject" class="form-control" />
					<input type="hidden" name="temp_user_id" id="temp_user_id" />
				</div>
			</div>
			<div class="jw_modal_ft">
				<button type="submit" class="cashgo disagree_company_btn">{{ __('user.write') }}</button>
			</div>
		</div>
	</div>
</div>
<div id="img_wrap" class="jw_modal_wrap hidden">
	<div class="jw_overlay"></div>
		<div class="jw_modal_content_wrap like_cux" style="width:1000px !important;height:300px;max-width:none;">
			<div class="jw_modal_content">
				<div class="jw_modal_hd">
					<h5>자료확인</h5>
					<div><i class="fal fa-chevron-down"></i></div>
				</div>
				<div class="jw_modal_bd">
					<div class="content_box">
						<div style="width:33%;float:left;margin-right:0.3%;">
							사업자등록증
							<img id="view_company_file" src="" style= "width:100%;">
						</div>
						<div style="width:33%;float:left;margin-right:0.3%;">
							신분증
							<img id="view_document_file" src="" style= "width:100%;">
						</div>
						<div style="width:33%;float:left;margin-right:0.3%;">
							통장사본
							<img id="view_account_file" src="" style= "width:100%;">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	loadPopup('.file_view', '#img_wrap', function(e) {
		var src = $(e.currentTarget).data('src');
		var src_arr = src.split('|');
		$("#view_company_file").attr('src',src_arr[0]);
		$("#view_document_file").attr('src',src_arr[1]);
		$("#view_account_file").attr('src',src_arr[2]);
	});
	function loadPopup(button, popup, onload){
		$(button).click(function(e){
				$(popup).removeClass('hidden');
				setTimeout(function() { $(popup).addClass('active'); }, 300);
				onload(e);
			});

			$(popup + ' .jw_overlay, ' + popup + ' .jw_modal_hd>div').click(function(){
			$(popup).removeClass('active');
			setTimeout(function() { $(popup).addClass('hidden');}, 300);
		});
	}
</script>
@endsection