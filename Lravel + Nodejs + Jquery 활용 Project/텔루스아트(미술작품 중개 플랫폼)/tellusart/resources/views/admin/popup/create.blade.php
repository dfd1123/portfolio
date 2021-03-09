@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
		팝업관리
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	팝업추가
	</div>
	<div class="card-body">
		<form method="post" action="{{route('admin.popup_insert')}}" enctype="multipart/form-data">
			@csrf
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;"> 팝업제목</th>
							<td>
								<input type="text" name="title" class="form-control tsa-input-st" required="required"/>
							</td>
						</tr>
						<tr>
							<th>PC이미지</th>
							<td>
								<input type="file" id="pc_img" name="pc_img" style="display:none;"/>
								<img src="" alt="" id="foo" />
								<label for="pc_img" class="myButton use">업로드</label>
							</td>
						</tr>
						<tr>
							<th>MOBILE이미지</th>
							<td>
								<input type="file" id="mb_img" name="mb_img" style="display:none;"/>
								<img src="" alt="" id="foo2" />
								<label for="pc_img" class="myButton use">업로드</label>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">팝업 기간</th>
							<td>
								<input id="startTime" type="text" name="start_time" class="form-control datepicker tsa-input-st" style="width: 105px; display: inline-block;" required="required" value="" />
								~
								<input id="endTime" type="text" name="end_time" class="form-control datepicker tsa-input-st" style="width: 105px; display: inline-block;" required="required" value="" />
							</td>
						</tr>
						<tr>
							<th style="width:10%;">내용</th>
							<td>
								<span>**이미지를 업로드 하시면 텍스트 내용은 표출되지 않습니다.**</span>
								<textarea rows="15" name="body" id="editor" class="form-control"></textarea>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">링크</th>
							<td>
								<input type="text" name="link" class="form-control tsa-input-st" required="required"/>
							</td>
						</tr>
					</tbody>
				</table>
            </div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
				추가
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.popup_list')}}'">
				취소
				</button>
			</div>
		</form>
	</div>
</div>

<script>
	$('#editor')
		.summernote({
			height: 350,
			lang: 'ko-KR',
			disableDragAndDrop: true
		});
	$('.note-editing-area').css('word-break', 'break-all');
	$('.note-group-select-from-files').css('display', 'none');

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
        yearSuffix: '년',
		beforeShow: function() {
			setTimeout(function(){
				$('.ui-datepicker').css('z-index', 999);
			}, 0);
		},
		onClose: function(value) {
			var datepicker = this;
			if(value && !moment(value, 'YYYY-MM-DD',true).isValid()){
				alert('잘못된 날짜 형식입니다.');
				$(datepicker).val('');
			}
		}
	});

	$('#startTime').datepicker();
	$('#endTime').datepicker();

	function checkInputs(){
		if(moment($('#startTime').val()).isAfter($('#endTime').val())){
			alert('시작일자가 종료일자보다 클 수 없습니다.');
			$('#startTime').focus();
			return false;
		}
		return true;
	}

	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#foo').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
	}
	
	function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#foo2').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#pc_img").change(function() {
        readURL(this);
	});

	$("#mb_img").change(function() {
        readURL2(this);
	});
	
	
</script>

@endsection

