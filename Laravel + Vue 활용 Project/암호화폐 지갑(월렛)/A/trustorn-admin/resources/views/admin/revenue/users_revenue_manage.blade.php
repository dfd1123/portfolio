@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
<li class="breadcrumb-item active">회원 수익 관리</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
<!-- <div class="card-header">
	회원리스트</div> -->
<div class="card-body">
	<div class="usr_search_box tsa-sch-box">
		<form method="get" action="">
				<select name="keyword_srch">
					<option value="all">{{ __('trade_history.all') }}</option>
					<option value="uid" {{$keyword_srch == 'uid' ? 'selected' : ''}}>회원번호</option>
					<option value="fullname" {{$keyword_srch == 'name' ? 'selected' : ''}}>{{ __('user.name') }}</option>
					<option value="email" {{$keyword_srch == 'id' ? 'selected' : ''}}>{{ __('user.email') }}</option>
				</select>
				<input type="text" name="keyword" />
				<button type="submit">{{ __('user.search') }}</button>
		</form>
	</div>
	<div class="mb-2">
		<input id="sdate" name="startTime" type="text" class="col-sm-1 form-control form-control-sm" style="display: inline"/>
		<span> ~ </span>
		<input id="edate" name="endTime" type="text" class="col-sm-1 form-control form-control-sm" style="display: inline"/>
		<button type="button" id="excel-download" class="myButton navy">엑셀 다운로드</button>
	</div>
	<div class="table-responsive tsa-table-wrap">

		<table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
			<thead>
				<th style="width: 5%;">회원번호</th>
				<th style="width: 7%;">{{ __('user.name') }}</th>
				<th style="width: 15%;">{{ __('user.email') }}</th>
				<th style="width: 7%;">정산 날짜</th>
				<th style="width: 7%;">전체 수익금</th>
        <th style="width: 7%;">TRU보유율(%)</th>
				<th style="width: 7%;">수수료</th>
        <th style="width: 7%;">수익금</th>
			</thead>
			<tbody>
			@forelse($users as $user)
			<tr>
				<td>{{$user->uid}}</td>
				<td>{{$user->fullname}}</td>
				<td>{{str_replace(session('market_type')."_","",$user->email)}}</td>
				<!--<td>{{$user->mobile_number}}</td>-->
				<td>{{ $user->date }}</td>
        <td>{{ number_format($user->revenue, 8) }}</td>
        <td>{{ number_format($user->coin_retention, 8) }}</td>
        <td>{{ number_format($user->fee, 8) }}</td>
        <td>{{ number_format($user->return_invest, 8) }}</td>
			</tr>
			@empty
			<tr>
				<td colspan="8" >{{ __('user.user_sentence_4') }}</td>
			</tr>
			@endforelse
			</tbody>
		</table>
	</div>
	@if($users)
		{!! $users->render() !!}
	@endif
</div>
@endsection

@section('script')
<script>
var datepicker_default = {
            closeText : "닫기",
            prevText : "이전달",
            nextText : "다음달",
            currentText : "오늘",
            changeMonth: true,
            changeYear: true,
            monthNames : [ "1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월" ],
            monthNamesShort : [ "1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월" ],
            dayNames : [ "일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일" ],
            dayNamesShort : [ "일", "월", "화", "수", "목", "금", "토" ],
            dayNamesMin : [ "일", "월", "화", "수", "목", "금", "토" ],
            weekHeader : "주",
            firstDay : 0,
            isRTL : false,
            showMonthAfterYear : true,
            yearSuffix : '',
             
            showOn: 'both',
            buttonImage:'/resources/image/calendar.png',
            buttonImageOnly: true,
             
            showButtonPanel: true
        }
      
        datepicker_default.closeText = "선택";
        datepicker_default.dateFormat = "yy-mm";
        datepicker_default.onClose = function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker( "option", "defaultDate", new Date(year, month, 1) );
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
      
        datepicker_default.beforeShow = function () {
            var selectDate = $("#sdate").val().split("-");
            var year = Number(selectDate[0]);
            var month = Number(selectDate[1]) - 1;
            $(this).datepicker( "option", "defaultDate", new Date(year, month, 1) );
        }
 
$("#sdate").datepicker(datepicker_default);
$("#edate").datepicker(datepicker_default);

$('#excel-download').click(function(e){
			window.location = '/admin/user_revenue_excel?from=' + $("#sdate").val() + '&to=' + $("#edate").val();
		});
</script>

<style> 
table.ui-datepicker-calendar { display:none; }
.ui-datepicker-trigger{ display:none; }
</style>

@endsection
