@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
          <ol class="breadcrumb tsa-top-tit">
            <li class="breadcrumb-item active">올코인페이 결제대금 정산관리</li>
          </ol>

          <!-- DataTables Example -->
          <div class="card mb-3 tsa-card">
            <div class="card-header">결제대금 정산관리</div>
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
			                    <th style="width: 10%;">미정산된 월</th>
			                    <th style="width: 10%;">정산해야될 가격</th>
			                    <th style="width: 10%;">유저이름</th>
			                    <th style="width: 10%;">회사이름</th>
                                <th style="width: 10%;">입금계좌</th>
                                <th style="width: 10%;">상태</th>
			                    <th style="width: 15%;">동작</th>
		                    </tr>
						</thead>
                  		<tbody>
                  		@forelse($payment_lists as $payment_list)
						<tr>
							<td>{{ $payment_list->calcul_month }}월</td>
                            <td>{{ number_format($payment_list->calcul_cash) }}원</td>
                            <td>{{ $payment_list->seller_fullname }}</td>
                            <td>{{ $payment_list->company_name }}</td>
                            <td>{{ $payment_list->account_num }} {{ $payment_list->account_bank }} {{ $payment_list->seller_fullname }}</td>
                            <td>{{ $payment_list->status == 'complete' ? '정산전' : '정산완료' }}</td>
                            <td>
                                @if($payment_list->status == 'complete')
                                <button class="payment_calculate" data-label = "{{ $payment_list->username }}|{{ $payment_list->calcul_month }}">정산하기</button>
                                @else
                                -
                                @endif
                            </td>
						</tr>
	                    @empty
	                    <tr>
	                    	<td colspan="11" >{{ __('user.user_sentence_1') }}</td>
	                    </tr>
	                    @endforelse
                  </tbody>
                </table>
              </div>
	            @if($payment_lists)
					{!! $payment_lists->render() !!}
				@endif
			</div>
		<div class="card-footer small text-muted">{{ $datetime }} {{ __('user.user_sentence_2') }}</div>
	</div>

@endsection
@section('script')
<script>
    
</script>
@endsection