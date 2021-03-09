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
			                    <th style="width: 10%;">{{ __('user.gb') }}</th>
			                    <th style="width: 10%;">{{ __('user.now') }}</th>
			                    <th style="width: 10%;">{{ __('user.price') }}</th>
			                    <th style="width: 10%;">{{ __('user.fees') }}</th>
			                    <th style="width: 10%;">승인 전 잔액</th>
			                    <th style="width: 10%;">{{ __('user.acc_info') }}</th>
			                    <th style="width: 15%;">동작</th>
		                    </tr>
						</thead>
                  		<tbody>
                  		@forelse($krw_ios as $krw_io)
						<tr style="{{ strpos($krw_io->status,'cancel') !== false || strpos($krw_io->status,'reject') !== false ? 'text-decoration:line-through;' : '' }}">
							<td>{{$krw_io->id}}</td>
							<td>{{date("Y-m-d H:i:s", $krw_io->created)}}</td>
							<td>{{$krw_io->fullname}}</td>
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
							
							<td>{{ number_format($krw_io->plus,0) }}</td>
							<td>{{ number_format($krw_io->minus,0) }}</td>
							<td>{{ number_format($krw_io->balance_before,0) }}</td>
							<td>{{ $krw_io->memo }}</td>
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