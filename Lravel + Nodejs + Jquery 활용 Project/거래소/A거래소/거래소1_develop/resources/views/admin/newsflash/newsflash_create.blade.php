@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
            속보 관리
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
		속보 추가
	</div>
	<div class="card-body">
		<form method="post" action="{{route('admin.newsflash_insert')}}" id="newsflash_form">
			@csrf
            <input type="hidden" name="country" value="{{$country}}">
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;"> {{ __('notice.tt')}}</th>
							<td>
								<input type="text" name="title" class="form-control tsa-input-st" required="required"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;"> {{ __('notice.con')}}</th>
							<td>
								<textarea name="description" id="editor" style="width:100%;min-height: 600px;border:none;resize:none; padding:10px;"></textarea>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
				{{ __('notice.add2')}}
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.newsflash_list',$country)}}'">
				{{ __('notice.cel')}}
				</button>
			</div>
		</form>
	</div>
</div>

<style>
.ck-editor__editable_inline {
    min-height: 600px;
}
</style>

<script type="text/javascript" src="{{ asset('vendor/ckeditor5/ckeditor.js') }}"></script>

<script>
	var My_editor;

	ClassicEditor
	.create( document.querySelector( '#editor' ), {
			ckfinder: {
					uploadUrl: '/Ckfinder/image_upload'
			},
			//plugins: [ Image, ImageToolbar, ImageCaption, ImageStyle, ImageResize ],
			image: {
					// You need to configure the image toolbar, too, so it uses the new style buttons.
					toolbar: [ 'imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight' ],

					styles: [
							// This option is equal to a situation where no style is applied.
							'full',

							// This represents an image aligned to the left.
							'alignLeft',

							// This represents an image aligned to the right.
							'alignRight'
					]
			},
	})
	.then( 
			editor => {
					My_editor = editor;
			} 
	)
	.catch( error => {
			//console.error( error );
	} );

	$('#newsflash_form').on('submit', function(){
			$('#editor').val(My_editor.getData());
			console.log(My_editor.getData());
			return true;
	});


</script>

@endsection

