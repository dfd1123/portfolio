@extends('admin.layouts.app')

@section('content')


<form enctype="multipart/form-data" class="event_edit_adm_form" id="event_add_form" method="post" action="{{isset($event) ? route('admin.event_update', $event->id) : route('admin.event_store')}}">

	@csrf
	  
  	<div class="card mb-3 tsa-card">
		<div class="card-header">
			{{isset($event) ? '이벤트편집' : '이벤트추가'}}
        </div>
		<div class="card-body">
		  <div class="table-responsive tsa-event-table">
		    <table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
		        <tbody>
		        	<tr>
			        	<th style="width:20%;">이벤트명</th>
			        	<td style="width:80%;"><input type="text" name="title"  class="form-control tsa-input-st" required="required" value="{{isset($event) ? $event->title : ''}}"/></td>
			        </tr>
			        <tr>
			        	<th>이벤트기간</th>
			        	<td>
			        		<input id="startTime" type="text" name="start_time" class="form-control datepicker tsa-input-st" required="required" value="{{isset($event) ? explode(' ', $event->start_time)[0] : ''}}" /> 
			        		~ 
			        		<input id="endTime" type="text" name="end_time" class="form-control datepicker tsa-input-st" required="required" value="{{isset($event) ? explode(' ', $event->end_time)[0] : ''}}" />
			        	</td>
			        </tr>
			        <tr>
			        	<td colspan="2">
			        		<textarea id="body" name="body" class="form-control" style="word-break: break-all;"/>{{isset($event) ? $event->body : ''}}</textarea>
			         	</td>
					</tr>
			        <tr>
			        	<th>첨부파일</th>
			        	<td>
			        		<div class="filebox">
						  		<label for="file1" class="myButton use">업로드</label> 
						  		<input type="file" id="file1" name="file1"/>
						  		<span class="filename">
						  			@if(isset($event))
						  				@if($event->file1 == NULL)
										    (첨부파일없음)
										@else
											<a href="{{asset('/storage/event/'.$event->file1)}}" target="_blank" class="myButton xbtn">Download</a>
										@endif	
						  			@endif
						  		</span>
						  	</div>
			        	</td>
			        </tr>
			        <tr>
			        	<th>PC배너이미지 (297 X 162 px 권장)</th>
			        	<td>
			        		<div class="filebox">
						  		<label for="pc_banner"  class="myButton use">업로드</label> 
								@if(isset($event))
						  			<input type="file" id="pc_banner" name="pc_banner" {{($event->pc_banner == NULL)?'required=required':''}}/>
								@else
									<input type="file" id="pc_banner" name="pc_banner"  required="required"/>
								@endif
						  		<span class="filename">
						  			@if(isset($event))
							  			@if($event->pc_banner == NULL)
											<span>(첨부이미지없음)</span>
							  			    <img src="" class="edit-banner-img" title="banner" style="display: none">
										@else
											<img src="{{asset('/storage/event/'.$event->pc_banner)}}" class="edit-banner-img" title="banner">
										@endif
									@else
										<span>(첨부이미지없음)</span>
							  			<img src="" class="edit-banner-img" title="banner" style="display: none">
						  			@endif
						  		</span>
						  	</div>
			        	</td>
			        </tr>
			        <tr>
			        	<th>모바일배너이미지</th>
			        	<td>
			        		<div class="filebox">
						  		<label for="mobile_banner"  class="myButton use">업로드</label>
								@if(isset($event))
						  			<input type="file" id="mobile_banner" name="mobile_banner" {{($event->mobile_banner == NULL)?'required=required':''}}/>
								@else
									<input type="file" id="mobile_banner" name="mobile_banner"  required="required"/>
								@endif
						  		<span class="filename">
						  			@if(isset($event))
							  			@if($event->mobile_banner == NULL)
											<span>(첨부이미지없음)</span>
							  			    <img src="" class="edit-banner-img" title="banner" style="display: none">
										@else
											<img src="{{asset('/storage/event/'.$event->mobile_banner)}}" class="edit-banner-img" title="banner">
										@endif
									@else
										<span>(첨부이미지없음)</span>
							  			<img src="" class="edit-banner-img" title="banner" style="display: none">
						  			@endif
						  		</span>
						  	</div>
			        	</td>
			        </tr>
		        </tbody>
		    </table>
		  </div>
		  	<div class="org_btn_group">
				<button type="submit" class="btn btn-default org_btn" onclick="return checkInputs();">{{isset($event) ? '변경' : '추가'}}</button>
				<button type="button" class="btn btn-default org_btn" onclick="location.href='{{route('admin.event_list', 0)}}'">목록</button>
			</div>
		</div>
	</div>
</form>

@endsection

@section('script')

<script>
	$('#file1').change(function(e){
		var input = this;
		if (input.files && input.files[0]) {
			$(input).parent().find('.filename').text(input.files[0].name);
		}
	});
	
	$('#pc_banner').change(function(e){
		var input = this;
		if (input.files && input.files[0]) {
			var type = input.files[0].type;
			var type_reg = /^image\/(jpg|png|jpeg|bmp|gif)$/;
			
			var filename = input.files[0].name;
			if (!type_reg.test(type)) {
		    	input.value = '';
		    	alert('이미지 파일 형식만 업로드 할 수 있습니다');
		    	filename = "";
		    }
		    
			var reader = new FileReader();
			
			reader.onload = function(e) {
				var showFileState = $(input).parent().find('.filename');
				showFileState.find('span').hide();
				showFileState.find('img').attr('src', e.target.result).show();
			}
			
			reader.readAsDataURL(input.files[0]);
		}
	});
  	
  	$('#mobile_banner').change(function(e){
		var input = this;
		if (input.files && input.files[0]) {
			var type = input.files[0].type;
			var type_reg = /^image\/(jpg|png|jpeg|bmp|gif)$/;
			
			var filename = input.files[0].name;
			if (!type_reg.test(type)) {
		    	input.value = '';
		    	alert('이미지 파일 형식만 업로드 할 수 있습니다');
		    	filename = "";
		    }
		    
			var reader = new FileReader();
			
			reader.onload = function(e) {
				var showFileState = $(input).parent().find('.filename');
				showFileState.find('span').hide();
				showFileState.find('img').attr('src', e.target.result).show();
			}
			
			reader.readAsDataURL(input.files[0]);
		}
	});
  	
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
	    		alert('잘못된 날짜 형식입니다');
	    		$(datepicker).val('');
	    	}
	    }
	});
	
	$('#startTime').datepicker();
	$('#endTime').datepicker();
	
	$('#body')
		.summernote({
			height: 300,
			lang: 'ko-KR',
			disableDragAndDrop: true
		});
	$('.note-editing-area').css('word-break', 'break-all');
	// $('.note-group-select-from-files').css('display', 'none');
	
	function checkInputs(){
		if(moment($('#startTime').val()).isAfter($('#endTime').val())){
			alert('시작일자가 종료일자보다 클 수 없습니다.');
			$('#startTime').focus();
			return false;
		}
		return true;
	}
</script>

@endsection