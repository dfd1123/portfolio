@extends('admin.layouts.app')

@section('content')


<form enctype="multipart/form-data" id="category_add_form"  method="post" action="{{route('admin.category_update')}}">

	@csrf
	<input type="hidden" name="ca_use" value="0" />
  
  <div class="card mb-3">
       <div class="card-header">
              <i class="fas fa-table"></i>
              	카테고리추가</div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
                    <tr>
                    	<th>이미지</th>
	                    <th>카테고리명</th>
                    </tr>
                    <tr>
                    	<td rowspan="5">
                    		<div>
						    	<img src="{{asset('storage/image/no_image.svg')}}" id="category_image" alt="category_image"  />
						    </div>
						  	<div class="filebox">
						  		<label for="ca_icon">업로드</label> 
						  		<input type="file"  id="ca_icon" name="ca_icon" required="required" /> 
						  	</div>
                    	</td>
                    	<td>
                    		<input type="text" id="ca_name" name="ca_name" class="form-control" required="required" />
                    	</td>
                    </tr>
                    <tr>
                    	<th>카테고리서브명</th>
                    </tr>
                    <tr>
                    	<td><input type="text" id="ca_sm_name" name="ca_sm_name"  class="form-control" required="required" /></td>
                    </tr>
                    <tr>
                    	<th>카테고리설명</th>
                    </tr>
                     <tr>
                     	<td>
                     		<textarea id="ca_discript" name="ca_discript" class="form-control" required="required" /></textarea>
                     	</td>
                     </tr>
                </table>
              </div>
				<button type="submit" class="btn btn-default">추가</button>
            </div>
        
          </div>
</form>

@endsection

@section('script')

<script>
	function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

       reader.onload = function(e) {

          $('#category_image').attr('src', e.target.result);

       }

       reader.readAsDataURL(input.files[0]);

     }
  }
  
  $('#ca_icon').change(function(){
  		readURL(this);
  })
</script>

@endsection