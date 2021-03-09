@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
          <ol class="breadcrumb tsa-top-tit">
            <li class="breadcrumb-item active">카테고리 관리</li>
          </ol>

          <!-- DataTables Example -->
          <div class="card mb-3 tsa-card">
            <!-- <div class="card-header">
              	카테고리 리스트</div> -->
            <div class="card-body">
              <div class="table-responsive tsa-table-wrap">
                <table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                    	<th>이미지</th>
	                    <th>카테고리명</th>
	                    <th>카테고리부제명</th>
	                    <th>카테고리설명</th>
	                    <th>상태</th>
	                    <th>설정</th>
                    </tr>
                  </thead>
                  <tbody>
                  @forelse($categorys as $category)
                    <tr id="categoryh_{{$category->id}}">
                      <td style="width:17%;">
                      	<form enctype="multipart/form-data" name="ca_icon{{$category->id}}">
                      		<label for="ca_icon{{$category->id}}"><img src="{{asset('storage/image/'.$category->ca_icon)}}" class="ca_icon_img{{$category->id}}" alt="{{$category->ca_name}}" /></label>
                      		<input type="file" id="ca_icon{{$category->id}}" name="ca_icon" class="hide" />	
						</form>
                      </td>
                      <td style="width:8.5%;"><input type="text" name="ca_name" value="{{$category->ca_name}}" class="form-control tsa-input-st" style="text-align: center;" /></td>
                      <td style="width:8.5%;"><input type="text" name="ca_sm_name" value="{{$category->ca_sm_name}}" class="form-control tsa-input-st" style="text-align: center;" /></td>
                      <td><textarea name="ca_discript" class="tsa-textarea">{{$category->ca_discript}}</textarea></td>
                      <td style="width:17%;">
                      	<select class="tsa-select" name="ca_use">
                      		@if($category->ca_use)
                      			<option value="1" selected="selected">사용중</option>
                      			<option value="0">사용안함</option>
                      		@else
                      			<option value="1">사용중</option>
                      			<option value="0" selected="selected">사용안함</option>
                      		@endif
                      	</select>
                      </td>
                      <td style="width:9%;">
                      	<a href="{{route('admin.category_delete',$category->id)}}" class="myButton xbtn">삭제</a>
                      </td>
                    </tr>
                    @empty
                    <tr>
                    	<td colspan="5" >카테고리가 존재하지 않습니다.</td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              <div>
              	<button type="button" onclick="location.href='{{route('admin.category_create')}}'" class="org_btn">추가</button>
              </div>
	            @if($categorys_page)
					{!! $categorys_page->render() !!}
				@endif
            </div>
            <div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
          </div>
		

@endsection

@section('script')

<script>
	
	
			
	$("input[name='ca_icon']").change(function(){
		var id = $(this).parent().parent().parent().attr('id');
		id = id.replace('categoryh_','');
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		
		var file_data = $(this).prop('files')[0];
        var supplier_name = $('#supplier_name').val();


        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('_token', CSRF_TOKEN);
        form_data.append('id', id);
        form_data.append('supplier_name', supplier_name);

        $.ajax({
            url: "/adm/category/image/change", // point to server-side PHP script
            data: form_data,
            type: 'POST',
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function(data) {
				 $('.ca_icon_img'+id).attr('src', data.path);
				alert('카테고리 이미지 변경을 완료하였습니다.');
            }
        });
	});

	$("input[name='ca_name']").change(function(){
		var name = $(this).val();
		var id = $(this).parent().parent().attr('id');
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		
		id = id.replace('categoryh_','');
		
		if(confirm("정말 카테고리명을 '"+name+"'으로 변경하시겠습니까?")){
			$.ajax({
                    url: '/adm/category/name/change',
                    type: 'POST',
                    /* send the csrf-token and the input to the controller */
                    data: {_token: CSRF_TOKEN, name: name, id:id},
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function (data) { 
                    alert('카테고리명 변경완료!'); 
                }
            }); 
		}
		
	});
	
	$("input[name='ca_sm_name']").change(function(){
		var sm_name = $(this).val();
		var id = $(this).parent().parent().attr('id');
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		
		id = id.replace('categoryh_','');
		
		if(confirm("정말 카테고리부제명을 '"+sm_name+"'으로 변경하시겠습니까?")){
			$.ajax({
                    url: '/adm/category/sm_name/change',
                    type: 'POST',
                    /* send the csrf-token and the input to the controller */
                    data: {_token: CSRF_TOKEN, sm_name: sm_name, id:id},
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function (data) { 
                    alert('카테고리부제명 변경완료!'); 
                }
            }); 
		}
		
	});
	
	$("textarea[name='ca_discript']").change(function(){
		var ca_discript = $(this).val();
		var id = $(this).parent().parent().attr('id');
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		
		id = id.replace('categoryh_','');
		
		if(confirm("정말 카테고리 설명을 '"+ca_discript+"'으로 변경하시겠습니까?")){
			$.ajax({
                    url: '/adm/category/discript/change',
                    type: 'POST',
                    /* send the csrf-token and the input to the controller */
                    data: {_token: CSRF_TOKEN, ca_discript: ca_discript, id:id},
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function (data) { 
                    alert('카테고리 설명 변경완료!'); 
                }
            }); 
		}
		
	});
	
	$("select[name='ca_use']").change(function(){
		var ca_use = $(this).val();
		var id = $(this).parent().parent().attr('id');
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		
		id = id.replace('categoryh_','');
		
		if(confirm("해당 카테고리의 상태를 정말 변경하시겠습니까?")){
			$.ajax({
                    url: '/adm/category/status/change',
                    type: 'POST',
                    /* send the csrf-token and the input to the controller */
                    data: {_token: CSRF_TOKEN, ca_use: ca_use, id:id},
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function (data) { 
                    alert('카테고리 상태 변경완료!'); 
                }
            }); 
		}
		
	});
	
	
</script>

@endsection