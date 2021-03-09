@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
          <ol class="breadcrumb tsa-top-tit">
            <li class="breadcrumb-item active">{{ __('user.user_cer') }}</li>
          </ol>

          <!-- DataTables Example -->
          <div class="card mb-3 tsa-card">
            <div class="card-header">{{ __('user.id_cer') }}</div>
            <div class="card-body">
            	<ul class="nav nav-tabs">
            		@if($type == 5)
						<li class="active"><a href="{{route('admin.document_list',5)}}">{{ __('user.all') }}</a></li>
					  	<li><a href="{{route('admin.document_list',2)}}">{{ __('user.no') }}</a></li>
					  	<li><a href="{{route('admin.document_list',1)}}">{{ __('user.yes') }}</a></li>
					@elseif($type == 2)
						<li><a href="{{route('admin.document_list',5)}}">{{ __('user.all') }}</a></li>
					  	<li class="active"><a href="{{route('admin.document_list',2)}}">{{ __('user.no') }}</a></li>
					  	<li><a href="{{route('admin.document_list',1)}}">{{ __('user.yes') }}</a></li>
					@elseif($type == 1)
						<li><a href="{{route('admin.document_list',5)}}">{{ __('user.all') }}</a></li>
					  	<li><a href="{{route('admin.document_list',2)}}">{{ __('user.no') }}</a></li>
					  	<li class="active"><a href="{{route('admin.document_list',1)}}">{{ __('user.yes') }}</a></li>
					@endif
				</ul>
	            <div class="usr_search_box tsa-sch-box">
	            	<form method="get" action="">
			            	<select name="keyword_srch">
			            		<option value="all">전체</option>
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
	                    <th style="width: 10%;">{{ __('user.name') }}</th>
	                    <th style="width: 20%;">{{ __('user.email') }}</th>
	                    <th style="width: 25%;">{{ __('user.id_1') }}</th>
	                    <th style="width: 15%;">상태</th>
                    </tr>
                  </thead>
                  <tbody>
                  @forelse($securitys as $security)
                    <tr>
                      <td>{{$security->uid}}</td>
                      <td>{{$security->fullname}}</td>
                      <td>{{str_replace(session('market_type')."_","",$security->email)}}</td>
                      <td>
                      	@if($security->document_1 == NULL || $security->document_2 == NULL)
                      		<span>{{ __('user.not') }}</span>
                      	@else
                      		<button type="button" class="document_view" data-src="{{asset($security->document_1)}}|{{asset($security->document_2)}}">{{ __('user.check') }}</button>
                      	@endif
                      </td>
                      
                      <td>
                      	@if($security->document_verified == 0 && $security->document_reject != NULL)
                      		<button type="button" class="myButton document_reject_load_btn" data-id="{{$security->uid}}">{{ __('user.reason') }}</button>
                      	@elseif($security->document_verified == 2)
                      		<button type="button" class="myButton navy document_agree_btn" data-id="{{$security->uid}}">{{ __('user.yes') }}</button>
                      		<button type="button" class="myButton xbtn document_reject_load_btn" data-id="{{$security->uid}}">{{ __('user.nope') }}</button>
                      	@elseif($security->document_verified == 1)
                      		<button type="button" class="myButton xbtn document_reject_load_btn" data-id="{{$security->uid}}">{{ __('user.nope') }}</button>
                      	@endif
                      </td>
                    </tr>
                    @empty
                    <tr>
                    	<td colspan="6" >{{ __('user.user_sentence_1') }}</td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
	            @if($securitys)
					{!! $securitys->render() !!}
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
					<input type="text" name="document_reject" class="form-control" />
					<input type="hidden" name="temp_user_id" id="temp_user_id" />
				</div>
			</div>
			<div class="jw_modal_ft">
				<button type="submit" class="cashgo disagree_document_btn">{{ __('user.write') }}</button>
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
							신분증
							<img id="view_document1" src="" style= "width:100%;">
						</div>
						<div style="width:33%;float:left;margin-right:0.3%;">
							신분증 들고있는 사진
							<img id="view_document2" src="" style= "width:100%;">
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
	
	/* 신분증 자료확인 버튼 */
	loadPopup('.document_view', '#img_wrap', function(e) {
		var src = $(e.currentTarget).data('src');
		var src_arr = src.split('|');
		$("#view_document1").attr('src',src_arr[0]);
		$("#view_document2").attr('src',src_arr[1]);
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