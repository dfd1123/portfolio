@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
          <ol class="breadcrumb tsa-top-tit">
            <li class="breadcrumb-item active">{{ __('user.user_account') }}</li>
          </ol>

          <!-- DataTables Example -->
          <div class="card mb-3 tsa-card">
            <div class="card-header">{{ __('user.account') }}</div>
            <div class="card-body">
            	<ul class="nav nav-tabs">
            		@if($type == 5)
						<li class="active"><a href="{{route('admin.account_list',5)}}">{{ __('user.all') }}</a></li>
					  	<li><a href="{{route('admin.account_list',2)}}">{{ __('user.no') }}</a></li>
					  	<li><a href="{{route('admin.account_list',1)}}">{{ __('user.yes') }}</a></li>
					@elseif($type == 2)
						<li><a href="{{route('admin.account_list',5)}}">{{ __('user.all') }}</a></li>
					  	<li class="active"><a href="{{route('admin.account_list',2)}}">{{ __('user.no') }}</a></li>
					  	<li><a href="{{route('admin.account_list',1)}}">{{ __('user.yes') }}</a></li>
					@elseif($type == 1)
						<li><a href="{{route('admin.account_list',5)}}">{{ __('user.all') }}</a></li>
					  	<li><a href="{{route('admin.account_list',2)}}">{{ __('user.no') }}</a></li>
					  	<li class="active"><a href="{{route('admin.account_list',1)}}">{{ __('user.yes') }}</a></li>
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
	                    <th style="width: 9%;">{{ __('user.name') }}</th>
	                    <th style="width: 15%;">{{ __('user.email') }}</th>
	                    <th style="width:17%;">{{ __('user.acc_info') }}</th>
	                    <th style="width: 15%;">인증번호</th>
                    </tr>
                  </thead>
                  <tbody>
                  @forelse($securitys as $security)
					<tr>
						<td>{{$security->uid}}</td>
						<td>{{$security->fullname}}</td>
						<td>{{str_replace(session('market_type')."_","",$security->email)}}</td>
						<td>
						@if($security->account_num == NULL)
							-
						@else
							{{$security->account_bank}}<br />{{$security->account_num}}
						@endif
						</td>
						<td>
							{{ $security->account_certify_code }}
						</td>
                    </tr>
                    @empty
                    <tr>
                    	<td colspan="7" >{{ __('user.user_sentence_1') }}</td>
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
		
@endsection

@section('script')
<script>
	/* 신분증 자료확인 버튼 */
	loadPopup('.account_view', '#img_wrap', function(e) {
		var src = $(e.currentTarget).data('src');
		var src_arr = src.split('|');
		$("#view_document1").attr('src',src_arr[0]);
		$("#view_account1").attr('src',src_arr[1]);
		$("#view_account2").attr('src',src_arr[2]);
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