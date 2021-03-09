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
				<ul class="nav nav-tabs">
					<li class="{{ ($type=='all')?'active':'' }}"><a href="{{route('admin.cash_list', 'type=all')}}">{{__('admin_coin.all')}}</a></li>
					<li class="{{ ($type=='deposite')?'active':'' }}"><a href="{{route('admin.cash_list', 'type=deposite')}}">입금</a></li>
					<li class="{{ ($type=='withdraw')?'active':'' }}"><a href="{{route('admin.cash_list', 'type=withdraw')}}">출금</a></li>
				</ul>
				<button id="excel-download" type="button" class="myButton navy">출금 대기 Excel(출금용)</button>
				<button id="excel-download2" type="button" class="myButton navy">입출금 내역 Excel(내역)</button>
	            <div class="usr_search_box tsa-sch-box">
	            	<form method="get" action="">
			            	<select name="keyword_srch">
			            		<option value="">전체</option>
			            		<option value="uid">UID</option>
			            		<option value="name">{{ __('user.name') }}</option>
			            		<option value="email">{{ __('user.email') }}</option>
			            		<option value="mobile">{{ __('user.phone') }}</option>
			            	</select>
			            	<input type="text" name="keyword" value="{{ $keyword }}"/>
			            	<button type="submit">{{ __('user.search') }}</button>
		            </form>
	            </div>
				<div class="table-responsive tsa-table-wrap">
                	<table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
						<thead>
		                    <tr>
			                    <th style="width: 5%;">ID</th>
			                    <th style="width: 7%;">Date</th>
								<th style="width: 7%;">{{ __('user.name') }}</th>
								<th style="width: 10%;">이메일</th>
								<th style="width: 10%;">전화번호</th>
			                    <th style="width: 5%;">{{ __('user.gb') }}</th>
			                    <th style="width: 7%;">{{ __('user.now') }}</th>
			                    <th style="width: 10%;">{{ __('user.price') }}</th>
			                    <th style="width: 10%;">{{ __('user.fees') }}</th>
			                    
								<th style="width: 10%;">{{ __('user.acc_info') }}</th>
								<th style="width: 10%;">인증번호</th>
			                    <th style="width: 15%;">동작</th>
		                    </tr>
						</thead>
                  		<tbody>
                  		@forelse($krw_ios as $krw_io)
						<tr style="{{ strpos($krw_io->status,'cancel') !== false || strpos($krw_io->status,'reject') !== false ? 'text-decoration:line-through;' : '' }}">
							<td>{{$krw_io->id}}</td>
							<td>{{date("Y-m-d H:i:s", $krw_io->created)}}</td>
							<td>{{$krw_io->fullname}}</td>
							<td>{{ $krw_io->email }}</td>
							<td>{{ $krw_io->mobile_number }}</td>
							<td>{{$krw_io->type == 'withdraw' ? __('trans.out') : __('trans.in') }}</td> 
							@if($krw_io->status == 'withdraw_request')
								<td>{{ __('message.withdraw_request') }}</td>      
							@elseif($krw_io->status == 'withdraw_cancel')
								<td>{{ __('message.withdraw_cancel') }}</td>
							@elseif($krw_io->status == 'withdraw_reject')
								<td>{{ __('message.withdraw_reject') }}</td>
							@elseif($krw_io->status == 'deposite_request')
								<td>{{ __('message.deposite_request') }}</td>
							@elseif($krw_io->status == 'deposite_cancel')
								<td>{{ __('message.deposite_cancel') }}</td>
							@elseif($krw_io->status == 'deposite_reject')
								<td>{{ __('message.deposite_reject') }}</td>
							@elseif($krw_io->status == 'confirm')
								<td>{{ __('message.trans_complete') }}</td>
							@endif
							
							<td>{{ $krw_io->plus }}</td>
							<td>{{ $krw_io->minus }}</td>
							
							<td>{{ $krw_io->memo }}</td>
							<td>{{ $krw_io->verification_code }}</td>
							@if($krw_io->status == 'withdraw_request' || $krw_io->status == 'deposite_request')
								<td>
									<button type="button" class="myButton navy cash_confirm" data-id="{{$krw_io->id}}">{{ __('user.yes') }}</button>
									<button type="button" class="myButton xbtn cash_reject" data-id="{{$krw_io->id}}">{{ __('user.no') }}</button>
								</td>
							@else
								<td>-</td>
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
	            @if($krw_ios)
					{!! $krw_ios->render() !!}
				@endif
            </div>
            <div class="card-footer small text-muted">{{ $datetime }} {{ __('user.user_sentence_2') }}</div>
          </div>
@endsection

@section('script')
<script>
	$('#excel-download').click(function(e){
		window.location = '/admin/cashwithdraw_excel';
	});

	$('#excel-download2').click(function(e){
		var srch = '{{ $keyword }}';
		window.location = '/admin/cashwithdraw_excel_history?srch=' + srch;
	});
</script>
@endsection

