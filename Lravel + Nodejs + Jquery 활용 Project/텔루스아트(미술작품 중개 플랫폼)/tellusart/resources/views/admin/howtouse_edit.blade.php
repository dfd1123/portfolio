@extends('admin.layouts.app')

@section('content')


<form enctype="multipart/form-data" class="event_edit_adm_form" id="event_add_form" method="post" action="{{route('admin.howtouse_update')}}">

	@csrf

	<div class="card mb-3 tsa-card">
		<div class="card-header">
			{{ $howtouse->title }}
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
								@if($howtouse->pc_img1 != NULL)
								<button type="button" class="myButton xbtn" style="float:right"
									onclick="window.location.href = '{{route('admin.howtouse_delete', 'pc_img1')}}'">삭제</button>
								@endif

								<label for="pc_img1">
									@if($howtouse->pc_img1 != NULL)
									<img src="{{ asset('/storage/image/howtouse/'.$howtouse->pc_img1) }}" />
									@else
									<img src="{{ asset('/storage/image/howtouse/no_image.svg') }}" />
									@endif
								</label>
								<input type="file" id="pc_img1" name="pc_imgs[]" {{ ($howtouse->pc_img1 != NULL)? 'value='.$howtouse->pc_img1:''}} class="imgInp" style="display: none;" />
							</td>
						</tr>
						<tr>
							<td>
								@if($howtouse->pc_img2 != NULL)
								<button type="button" class="myButton xbtn" style="float:right"
									onclick="window.location.href = '{{route('admin.howtouse_delete', 'pc_img2')}}'">삭제</button>
								@endif

								<label for="pc_img2">
									@if($howtouse->pc_img2 != NULL)
									<img src="{{ asset('/storage/image/howtouse/'.$howtouse->pc_img2) }}" />
									@else
									<img src="{{ asset('/storage/image/howtouse/no_image.svg') }}" />
									@endif
								</label>
								<input type="file" id="pc_img2" name="pc_imgs[]" {{ ($howtouse->pc_img2 != NULL)? 'value='.$howtouse->pc_img2:''}} class="imgInp" style="display: none;" />
							</td>
						</tr>
						<tr>
							<td>
								@if($howtouse->pc_img3 != NULL)
								<button type="button" class="myButton xbtn" style="float:right"
									onclick="window.location.href = '{{route('admin.howtouse_delete', 'pc_img3')}}'">삭제</button>
								@endif

								<label for="pc_img3">
									@if($howtouse->pc_img3 != NULL)
									<img src="{{ asset('/storage/image/howtouse/'.$howtouse->pc_img3) }}" />
									@else
									<img src="{{ asset('/storage/image/howtouse/no_image.svg') }}" />
									@endif
								</label>
								<input type="file" id="pc_img3" name="pc_imgs[]" {{ ($howtouse->pc_img3 != NULL)? 'value='.$howtouse->pc_img3:''}} class="imgInp" style="display: none;" />
							</td>
						</tr>
						<tr>
							<th>
								Mobile 버전용
							</th>
						</tr>
						<tr>
							<td>
								@if($howtouse->mb_img1 != NULL)
								<button type="button" class="myButton xbtn" style="float:right"
									onclick="window.location.href = '{{route('admin.howtouse_delete', 'mb_img1')}}'">삭제</button>
								@endif

								<label for="mb_img1">
									@if($howtouse->mb_img1 != NULL)
									<img src="{{ asset('/storage/image/howtouse/'.$howtouse->mb_img1) }}" />
									@else
									<img src="{{ asset('/storage/image/howtouse/no_image.svg') }}" />
									@endif
								</label>
								<input type="file" id="mb_img1" name="mb_imgs[]" {{ ($howtouse->mb_img1 != NULL)? 'value='.$howtouse->mb_img1:''}} class="imgInp" style="display: none;" />
							</td>
						</tr>
						<tr>
							<td>
								@if($howtouse->mb_img2 != NULL)
								<button type="button" class="myButton xbtn" style="float:right"
									onclick="window.location.href = '{{route('admin.howtouse_delete', 'mb_img2')}}'">삭제</button>
								@endif

								<label for="mb_img2">
									@if($howtouse->mb_img2 != NULL)
									<img src="{{ asset('/storage/image/howtouse/'.$howtouse->mb_img2) }}" />
									@else
									<img src="{{ asset('/storage/image/howtouse/no_image.svg') }}" />
									@endif
								</label>
								<input type="file" id="mb_img2" name="mb_imgs[]" {{ ($howtouse->mb_img2 != NULL)? 'value='.$howtouse->mb_img2:''}} class="imgInp" style="display: none;" />
							</td>
						</tr>
						<tr>
							<td>
								@if($howtouse->mb_img3 != NULL)
								<button type="button" class="myButton xbtn" style="float:right"
									onclick="window.location.href = '{{route('admin.howtouse_delete', 'mb_img3')}}'">삭제</button>
								@endif

								<label for="mb_img3">
									@if($howtouse->mb_img3 != NULL)
									<img src="{{ asset('/storage/image/howtouse/'.$howtouse->mb_img3) }}" />
									@else
									<img src="{{ asset('/storage/image/howtouse/no_image.svg') }}" />
									@endif
								</label>
								<input type="file" id="mb_img3" name="mb_imgs[]" {{ ($howtouse->mb_img3 != NULL)? 'value='.$howtouse->mb_img3:''}} class="imgInp" style="display: none;" />
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
	$(".imgInp").change(function() {
	var inpId = $(this).attr('id');
	if (this.files && this.files[0]) {
		var reader = new FileReader();

		reader.onload = function(e) {

			$('#'+inpId).siblings().children().attr('src', e.target.result);

		}

		reader.readAsDataURL(this.files[0]);

	}

});
</script>

@endsection