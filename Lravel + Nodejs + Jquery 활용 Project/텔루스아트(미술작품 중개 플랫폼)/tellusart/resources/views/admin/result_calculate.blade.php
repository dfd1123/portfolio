@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
          <ol class="breadcrumb tsa-top-tit">
            <li class="breadcrumb-item active">정산</li>
          </ol>

          <!-- DataTables Example -->
          <div class="card mb-3 tsa-card">
            <div class="card-header">
              	정산리스트
            </div>
            <div class="card-body">
            	<ul class="nav nav-tabs">
					  <li class="{{($status == 0)?'active':''}}"><a href="{{route('admin.result_calculate',0)}}">전체</a></li>
					  <li class="{{($status == 1)?'active':''}}"><a href="{{route('admin.result_calculate',1)}}">미정산</a></li>
					  <li class="{{($status == 2)?'active':''}}"><a href="{{route('admin.result_calculate',2)}}">정산완료</a></li>
				</ul>
            	<div class="usr_search_box_multi tsa-sch-box">
                    <form method="get" action="">
                        <div class="line tsa-line">
                            <span class="tsa-label-st"><i class="fas fa-angle-right"></i> 누적 매출액</span>
                            <span>{{number_format($total_magin)}} 원</span>
                        </div>
                        <div class="line tsa-line">
                            <span class="tsa-label-st"><i class="fas fa-angle-right"></i> 기간내 매출액</span>
                            <span>{{number_format($date_in_price)}} 원</span>
                        </div>
                        <div class="line tsa-line">
                            <span class="tsa-label-st"><i class="fas fa-angle-right"></i> 주문일자</span>
                            <input class="datepicker tsa-input-st" type="text" value="{{$start_time}}" name="start_time" id="startTime" />
                            <span class="tsa-input-st">~</span>
                            <input class="datepicker tsa-input-st" type="text" value="{{$end_time}}" name="end_time" id="endTime" />
                            <button class="date-range tsa-date-range tsa-list-st" id="date-today" type="button">오늘</button>
                            <button class="date-range tsa-date-range tsa-list-st" id="date-yesterday" type="button">어제</button>
                            <button class="date-range tsa-date-range tsa-list-st" id="date-week" type="button">이번주</button>
                            <button class="date-range tsa-date-range tsa-list-st" id="date-month" type="button">이번달</button>
                            <button class="date-range tsa-date-range tsa-list-st" id="date-last-week" type="button">지난주</button>
                            <button class="date-range tsa-date-range tsa-list-st" id="date-last-month" type="button">지난달</button>
                            <button type="submit" class="org_btn">검색</button>
                        </div>
                    </form>
              </div>
              <div class="table-responsive tsa-table-wrap">
                <table class="table table-bordered prd_adm_table" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                        <th rowspan="2" style="width:20px;"><input type="checkbox" onclick="CheckAll()" /></th>
                        <th rowspan="1" colspan="1">주문번호</th>
	                    <th rowspan="1">판매자</th>
	                    <th rowspan="1" colspan="1">은행명</th>
                        <th rowspan="1" colspan="1">예금주</th>
                        <th rowspan="1">판매금액</th>
                        <th rowspan="2">정산금액</th>
                        <th rowspan="2">처리</th>
                    </tr>
                    <tr>
                        <th rowspan="1" colspan="1">작품제목</th>
                        <th rowspan="1">아이디/연락처</th>
                        <th rowspan="1" colspan="2">계좌번호</th>
                        <th rowspan="1">수수료</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($result_cals as $result_cal)
                        <tr>
                            <td rowspan="2" style="width:20px;"><input type="checkbox" name="confirm[]" {{($result_cal->complete == 1)?'disabled=disabled':''}} /></td>
                            <td rowspan="1" colspan="1">{{sprintf('%09d',$result_cal->order_id)}}</td>
                            <td rowspan="1">{{$result_cal->seller_name}}</td>
                            <td rowspan="1" colspan="1">{{$result_cal->bank_name}}</td>
                            <td rowspan="1" colspan="1">{{$result_cal->bank_holder}}</td>
                            <td rowspan="1">{{number_format($result_cal->sale_price)}}</td>
                            <td rowspan="2">{{number_format($result_cal->result_price)}}</td>
                            <td rowspan="2">
                                @if($result_cal->complete == 0)
                                    <button type="button" class="result_confirm" data-id="{{$result_cal->id}}">정산처리</button>
                                @else
                                    정산완료
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="1" colspan="1">{{$result_cal->product_name}}</td>
                            <td rowspan="1">{{$result_cal->seller_email}}<br>{{$result_cal->seller_phone}}</td>
                            <td rowspan="1" colspan="2">{{$result_cal->bank_number}}</td>
                            <td rowspan="1">{{number_format($result_cal->fee)}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">데이터가 없습니다.</td>
                        </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              <div>
                <form method="post" action="{{route('admin.result_all_confirm')}}"  id="all_result_confirm">
                    @csrf
                    <input type="hidden" name="is_confirm_id" />
                    <input type="hidden" name="status" value="{{$status}}" />
                    <button type="submit">일괄처리</button>
                </form>
              </div>
                <div>
                @if($result_cals)
                    {!! $result_cals->render() !!}
                @endif
                </div>
            </div>
            <div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
          </div>
          
		  
<script>
	$.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd',
        prevText: '이전 달',
        nextText: '다음 달',
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        dayNames: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        showMonthAfterYear: true,
        yearSuffix: '년'
    });
    
	$('#startTime').datepicker();
	$('#endTime').datepicker();
	
	var dateFormat = 'YYYY-MM-DD';
	var changeDate = function(fromDate, endDate){
		$('#startTime').val(fromDate.format(dateFormat));
		$('#endTime').val(endDate.format(dateFormat));
	};
	
	$('#date-today').click(function(e){
		var today = moment();
		changeDate(today, today);
	});
	
	$('#date-yesterday').click(function(e){
		var yesterday = moment().subtract(1, 'days');
		changeDate(
			yesterday,
			yesterday
		);
	});
	
	$('#date-week').click(function(e){
		changeDate(
			moment().startOf('week'),
			moment()
		);
	});
	
	$('#date-month').click(function(e){
		changeDate(
			moment().startOf('month'),
			moment()
		);
	});
	
	$('#date-last-week').click(function(e){
		changeDate(
			moment().subtract(1, 'week').startOf('week'),
			moment().subtract(1, 'week').endOf('week')
		);
	});
	
	$('#date-last-month').click(function(e){
		changeDate(
			moment().subtract(1, 'month').startOf('month'),
			moment().subtract(1, 'month').endOf('month')
		);
	});
	
</script>
@endsection
