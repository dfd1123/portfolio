@extends('admin.layouts.app')

@section('content')


<form enctype="multipart/form-data" class="event_edit_adm_form" id="event_add_form" method="post" action="{{route('admin.privacy_update')}}">

	@csrf
	  
  	<div class="card mb-3 tsa-card">
		<div class="card-header">
			{{ $privacy->title }}
        </div>
		<div class="card-body">
		  <div class="table-responsive tsa-event-table">
		    <table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
		        <tbody>
		        	<tr>
		        		<th>
		        			PC 버전용
		        		</th>
		        	</tr>
		        	<tr>
		        		<td>
		        			<textarea class="body" name="pc_contents">
		        				@if($privacy->pc_contents != NULL)
		        					{{$privacy->pc_contents}}
		        				@endif
		        			</textarea>
		        		</td>
		        	</tr>
		        	<tr>
		        		<th>
		        			Mobile 버전용
		        		</th>
		        	</tr>
		        	<tr>
		        		<td>
		        			<textarea class="body" name="mobile_contents">
		        				@if($privacy->mobile_contents != NULL)
		        					{{$privacy->mobile_contents}}
		        				@endif
		        			</textarea>
		        		</td>
		        	</tr>
		        </tbody>
		    </table>
		  </div>
		  	<div class="org_btn_group">
				<button type="submit" class="btn btn-default org_btn">수정</button>
			</div>
		</div>
	</div>
</form>

@endsection

@section('script')

<script>

	$('.body')
		.summernote({
			height: 300,
			lang: 'ko-KR'
		});

</script>

@endsection