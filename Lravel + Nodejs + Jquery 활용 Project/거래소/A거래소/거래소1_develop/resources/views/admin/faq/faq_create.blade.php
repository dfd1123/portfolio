@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
        {{ __('faq.set')}}
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('faq.edit')}}
	</div>
	<div class="card-body">
		<form method="post" action="{{route('admin.faq_insert')}}" id="faq_form">
			@csrf
            <input type="hidden" name="country" value="{{$country}}">
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
                        <tr>
                            <th style="width:10%;">{{ __('faq.dv')}}</th>
                            <td>
                                <select name="faq_type" id="faq_type">
                                    <option value="1">{{__('faq.1')}}</option>
                                    <option value="2">{{__('faq.2')}}</option>
                                    <option value="3">{{__('faq.3')}}</option>
                                    <option value="4">{{__('faq.4')}}</option>
                                </select>
                            </td>
                        </tr>
						<tr>
							<th style="width:10%;">{{ __('faq.q1')}}</th>
							<td>
								<input type="text" name="title" class="form-control tsa-input-st" required="required"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('faq.an')}}</th>
							<td>
								<textarea name="description" id="editor"></textarea>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
					추가
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.faq_list',['country'=>$country, 0])}}'">
					취소
				</button>
			</div>
		</form>
	</div>
</div>


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

	$('#faq_form').on('submit', function(){
			$('#editor').val(My_editor.getData());
			console.log(My_editor.getData());
			return true;
	});


</script>


@endsection

