@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">입출금내역</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
		{{$title}}
	</div>
	<div class="card-body">
		<div class="usr_search_box_multi tsa-sch-box">
			<form method="get" action="{{route('admin.io_list')}}">
				@csrf
				<div class="line tsa-line">
						<span class="tsa-label-st"><i class="fas fa-angle-right"></i> 입출금</span>
						<label class="tsa-list-st"><input type="radio" name="request_type" value="0" {{($request_type == 0 )?'checked':''}} class="tsa-hide"/><span class="tsa-indicator"></span> 전체</label>
						<label class="tsa-list-st"><input type="radio" name="request_type" value="1" {{($request_type == 1 )?'checked':''}} class="tsa-hide"/><span class="tsa-indicator"></span> 입금</label>
						<label class="tsa-list-st"><input type="radio" name="request_type" value="2" {{($request_type == 2)?'checked':''}} class="tsa-hide"/><span class="tsa-indicator"></span> 출금</label>
				</div>
				<div class="line tsa-line">
					<span class="tsa-label-st"><i class="fas fa-angle-right"></i> 항목</span>
					<label class="tsa-list-st"><input type="radio" name="request_status" value="0" {{($request_status == 0)?'checked':''}} class="tsa-hide" /><span class="tsa-indicator"></span> 전체</label>
					<label class="tsa-list-st"><input type="radio" name="request_status" value="1" {{($request_status == 1)?'checked':''}} class="tsa-hide" /><span class="tsa-indicator"></span> 코인입출금</label>
					<label class="tsa-list-st"><input type="radio" name="request_status" value="2" {{($request_status == 2)?'checked':''}} class="tsa-hide" /><span class="tsa-indicator"></span> 수수료</label>
					<label class="tsa-list-st"><input type="radio" name="request_status" value="3" {{($request_status == 3)?'checked':''}} class="tsa-hide" /><span class="tsa-indicator"></span> 베팅</label>
					<label class="tsa-list-st"><input type="radio" name="request_status" value="4" {{($request_status == 4)?'checked':''}} class="tsa-hide" /><span class="tsa-indicator"></span> 보상</label>
					<label class="tsa-list-st"><input type="radio" name="request_status" value="5" {{($request_status == 5)?'checked':''}} class="tsa-hide" /><span class="tsa-indicator"></span> 구매</label>
					<label class="tsa-list-st"><input type="radio" name="request_status" value="6" {{($request_status == 6)?'checked':''}} class="tsa-hide" /><span class="tsa-indicator"></span> 판매</label>
					<label class="tsa-list-st"><input type="radio" name="request_status" value="7" {{($request_status == 7)?'checked':''}} class="tsa-hide" /><span class="tsa-indicator"></span> 환불</label>
				</div>
				<div class="line tsa-line">
					<span class="tsa-label-st"><i class="fas fa-angle-right"></i> 상세</span>
					<label class="tsa-list-st">
					<select name="keyword_srch">
						<option value="0" {{($keyword_srch == 0)?'selected':''}}>전체</option>
						<option value="1" {{($keyword_srch == 1)?'selected':''}}>사용자</option>
						<option value="2" {{($keyword_srch == 2)?'selected':''}}>입출금주소</option>
						<option value="3" {{($keyword_srch == 3)?'selected':''}}>TXID</option>
						<option value="4" {{($keyword_srch == 4)?'selected':''}}>날짜</option>
					</select>
					</label>
					<label class="tsa-list-st"><input type="text" name="keyword" value="{{$keyword}}" style="width:480px"/></label>
				</div>
				<button type="submit" class="org_btn">검색</button>
			</form>
		</div>
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>사용자</th>
						<th>입출금</th>
						<th>항목</th>
						<th>수량</th>
						<th>입출금주소</th>
						<th>TXID</th>
						<th>날짜</th>
					</tr>
				</thead>
				<tbody>
					@forelse($ios as $io)
					<tr>
						<td>
							{{$io->request_user_id}}
						</td>
						<td>
							@if($io->request_type == 'deposit')
							입금
							@else
							출금
							@endif
						</td>
						<td>
							@if($io->request_type == 'deposit')
								@if($io->request_status == 'sell')
								판매
								@elseif($io->request_status == 'refund')
								환불
								@elseif($io->request_status == 'batting')
								베팅
								@elseif($io->request_status == 'fee')
								수수료
								@elseif($io->request_status == 'reward')
								보상
								@else
									@if($io->request_status == 'deposit_confirmed')
									입금완료
									@else
									입금대기
									@endif
								@endif
							@else
								@if($io->request_status == 'buy')
								구매
								@elseif($io->request_status == 'batting')
								베팅
								@elseif($io->request_status == 'fee')
								수수료
								@else
									@if($io->request_status == 'withdraw_confirmed')
									출금완료
									@else
									출금대기
									@endif
								@endif
							@endif
						</td>
						<td>{{$io->request_amount}}</td>
						<td>{{empty($io->request_address) ? '(없음)' : $io->request_address}}</td>
						<td>{{empty($io->confirm_tx) ? '(없음)' : $io->confirm_tx}}</td>
						<td>{{$io->updated}}</td>
					</tr>
                    @empty
                    <tr>
						<td colspan="9">입출금이 존재하지 않습니다.</td>
                    </tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@if($ios_page)
		{!! $ios_page->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
</div>

@endsection

@section('script')
@if(session()->has('jsAlert'))
<script>
    alert('{{ session()->get('jsAlert') }}');
</script>
@endif 
<script>
	$('[name=keyword_srch]')
		.change(function() {
			$('[name=keyword]').attr('disabled', this.value === '0');
		})
		.change();
</script>

@endsection