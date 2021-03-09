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
					  	<li><a href="{{route('admin.document_list',0)}}">{{ __('user.no') }}</a></li>
					  	<li><a href="{{route('admin.document_list',1)}}">{{ __('user.yes') }}</a></li>
					@elseif($type == 0)
						<li><a href="{{route('admin.document_list',5)}}">{{ __('user.all') }}</a></li>
					  	<li class="active"><a href="{{route('admin.document_list',0)}}">{{ __('user.no') }}</a></li>
					  	<li><a href="{{route('admin.document_list',1)}}">{{ __('user.yes') }}</a></li>
					@elseif($type == 1)
						<li><a href="{{route('admin.document_list',5)}}">{{ __('user.all') }}</a></li>
					  	<li><a href="{{route('admin.document_list',0)}}">{{ __('user.no') }}</a></li>
					  	<li class="active"><a href="{{route('admin.document_list',1)}}">{{ __('user.yes') }}</a></li>
					@endif
				</ul>
	            <div class="usr_search_box tsa-sch-box">
	            	<form method="get" action="">
			            	<select name="keyword_srch">
			            		<option value="name">{{ __('user.name') }}</option>
			            		<option value="id">{{ __('user.id') }}</option>
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
	                    <th style="width: 25%;">{{ __('user.id_2') }}</th>
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
                      	@if($security->document_1 == NULL)
                      		<span>{{ __('user.not') }}</span>
                      	@else
                      		<button type="button">{{ __('user.check') }}</button>
                      	@endif
                      </td>
                      <td>
                      	@if($security->document_2 == NULL)
                      		<span>{{ __('user.not') }}</span>
                      	@else
                      		<button type="button">{{ __('user.check') }}</button>
                      	@endif
                      </td>
                      <td>
                      	@if($security->document_verified == 0)
                      		<button type="button" class="myButton navy" data-id="{{$security->uid}}" class="document_agree_btn">{{ __('user.yes') }}</button>
                      		@if($security->document_reject == NULL)
                      			<button type="button" class="myButton xbtn" data-id="{{$security->uid}}" class="document_reject_load_btn">{{ __('user.nope') }}</button>
                      		@else
                      			<button type="button" class="myButton" data-id="{{$security->uid}}" class="document_reject_load_btn">{{ __('user.reason') }}</button>
                      		@endif
                      	@else
                      		<button type="button" class="myButton xbtn" data-id="{{$security->uid}}" class="document_reject_load_btn">{{ __('user.nope') }}</button>
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
					<input type="hidden" name="temp_user_id">
				</div>
			</div>
			<div class="jw_modal_ft">
				<button type="button" class="cashgo disagree_document_btn">{{ __('user.write') }}</button>
			</div>
		</div>
	</div>
</div>


@endsection

@section('script')

@endsection