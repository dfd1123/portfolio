@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">마케팅/홍보</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
		{{$title}}
	</div>
	<div>
		메인의 경우 1900x799 px, 서브페이지의 경우 1900x334 px 을 꼭 지켜주세요!!
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered banner_list_adm_table" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th width="50%">이미지</th>
						<th>배너위치</th>
						<th>배너파일</th>
						<!--th>시작일시</th>
						<th>종료일시</th-->
					</tr>
				</thead>
				<tbody>
					@forelse($banners as $banner)
					<tr data-id="{{$banner->id}}">
						<td>
							@if($banner->bn_file != NULL)
							<img src="/storage/image/banner/{{$banner->bn_file}}" style="height: 100px;"/>
							@else
							<img src="" style="height: 100px;"/>
							@endif
						</td>
						<td>{{$banner->bn_alt}}</td>
						<td>
							<span class="filebox">
								<label for="file{{$loop->index}}" class="myButton use">업로드</label> 
								<input id="file{{$loop->index}}" type="file" name="file"/>
							</span>
							
							<span class="banner-delete myButton" style="height: 40px; background: #ff7979; vertical-align: text-top;">삭제</span>
						</td>
						<!--td><input type="text" class="form-control datepicker begin-date tsa-input-st" required="required" value="{{$banner->bn_begin_time}}" /></td>
						<td><input type="text" class="form-control datepicker end-date tsa-input-st" required="required" value="{{$banner->bn_end_time}}" /></td-->
					</tr>
                    @empty
                    <tr>
                    	<th colspan="4">배너가 존재하지 않습니다.</th>
                    </tr>
                    @endforelse
				</tbody>
			</table>
		</div>
		@if($banners_page)
		{!! $banners_page->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
</div>

@endsection

@section('script')

<script>
    $(".filebox label")
	    .click(function(e){
	    	if($(this).data('loading')){
	    		e.preventDefault();
	    	}
	    })
	    .data('loading', false);
    
	$(".filebox input[type='file']").change(function(e){
		var input = this;
		var label = $(this).parent().find("label");
		
		if(label.data('loading')){
			return;
		}
		if (input.files && input.files[0]) {
			var type = input.files[0].type;
			var type_reg = /^image\/(jpg|png|jpeg|bmp|gif)$/;
			
			if (!type_reg.test(type)) {
				input.value = '';
				alert('이미지 파일 형식만 업로드 할 수 있습니다');
				return;
			}
			
			if(confirm(input.files[0].name + " 파일을 업로드하시겠습니까?")){
				label.data('loading', true).text('로드중');
				
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				var id = $(this).closest('tr').data('id');
				
				var formData = new FormData();
				formData.append('_token', CSRF_TOKEN);
				formData.append('file', input.files[0]);
				formData.append('id', id);
				
				$.ajax({
					url: '/banner/file/change',
					type: 'POST',
					/* send the csrf-token and the input to the controller */
					data: formData,
					processData: false,
					contentType: false,
					/* remind that 'data' is the response of the AjaxController */
					success: function (data) {
						label.data('loading', false).text('업로드');
						$(input).closest('tr').find('img').attr('src', "/storage/image/banner/" + data.file);
						alert('이미지 변경완료!'); 
					}
				});
			}
			
			input.value = "";
		}
	});

	$('.banner-delete')
		.click(function(e) {
			var deleteButton = $(e.currentTarget);
			if(confirm("이미지 파일을 삭제하시겠습니까?")){
				deleteButton.data('loading', true).text('삭제중');

				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				var id = $(this).closest('tr').data('id');

				var formData = new FormData();
				formData.append('_token', CSRF_TOKEN);
				formData.append('id', id);

				$.ajax({
					url: '/banner/file/delete',
					type: 'POST',
					/* send the csrf-token and the input to the controller */
					data: formData,
					processData: false,
					contentType: false,
					/* remind that 'data' is the response of the AjaxController */
					success: function (data) {
						deleteButton.data('loading', false).text('삭제');
						deleteButton.closest('tr').find('img').attr('src', "");
						alert('이미지 삭제완료!'); 
					}
				});
			}
		})
		.data('loading', false);
	
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
	    beforeShow: function(e) {
	    	$(e).data('current', $(e).val());
	    },
	    onClose: function(value) {
	    	var datepicker = $(this);
	    	if(value && !moment(value, 'YYYY-MM-DD',true).isValid()){
	    		alert('잘못된 날짜 형식입니다');
	    		datepicker.val(datepicker.data('current'));
	    	}
	    }
	});
    
	$('.datepicker')
		.datepicker()
		.change(function(e){
			var datepicker = $(this);
			var row = datepicker.closest('tr');
			var beginDate = $(row.find('.begin-date')).val();
			var endDate = $(row.find('.end-date')).val();
			
			if(moment(beginDate).isAfter(endDate)){
				datepicker.val(datepicker.data('current'));
				alert('시작일시가 종료일시보다 클 수 없습니다.');
				return;
			}
			
			var id = row.data('id');
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
			if(confirm("정말 기간을 " + beginDate + " ~ " + endDate + "로 변경하시겠습니까?")){
				$.ajax({
	                url: '/banner/time/change',
	                type: 'POST',
	                /* send the csrf-token and the input to the controller */
	                data: {_token: CSRF_TOKEN, id: id, bn_begin_time: beginDate, bn_end_time: endDate},
	                dataType: 'JSON',
	                /* remind that 'data' is the response of the AjaxController */
	                success: function (data) {
	                	alert('기간 변경완료!');
	                }
	            }); 
			} else {
				datepicker.val(datepicker.data('current'));
			}
		});
</script>

@endsection