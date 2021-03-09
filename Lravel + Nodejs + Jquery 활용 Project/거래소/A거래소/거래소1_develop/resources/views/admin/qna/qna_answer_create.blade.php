@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('vendor/ckeditor5-image/theme/textalternativeform.css')}}">
<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
        {{ __('admin_qna.inq')}}
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('admin_qna.answer')}}
	</div>
	<div class="card-body">
		<form method="post" action="{{route('admin.qna_answer_insert',$qna->id)}}" id="qna_form">
			@csrf
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;">{{ __('admin_qna.tt')}}</th>
							<td>
								{{$qna->title}}
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('admin_qna.writer')}}</th>
							<td>
								{{$qna->createdby}} <a href="{{route('admin.user_list')}}?keyword_srch=email&keyword={{$qna->createdby}}" target="_blank">상세정보보기</a>
							</td>
						</tr>
						@if($user !== NULL)
						<tr>
							<th style="width:10%;">질문자 닉네임</th>
							<td>
								{{$user->nickname}}
							</td>
						</tr>
						<tr>
							<th style="width:10%;">질문자명</th>
							<td>
								{{$user->fullname}}
							</td>
						</tr>
						@endif
						<tr>
							<th style="width:10%;">{{ __('admin_qna.con')}}</th>
							<td>
                            {!! $qna->description !!}
							</td>
						</tr>
						<tr>
							<th style="width:10%;">문의 이미지</th>
								<td>
									@if(!empty($qna->image_url))
									<img src="{{asset('/storage/image/qna/'.$qna->image_url)}}" alt="" style="width: 100%; max-width: initial;"/>
									@else
									<img src="" alt="" />
									@endif
								</td>
							</tr>
					</tbody>
				</table>
			</div>
            <div>
                <h5 class="tsa-label-st pl-2 pb-3">{{ __('admin_qna.answer2')}}</h5>
                <textarea name="description" id="editor"></textarea>
            </div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
				{{ __('admin_qna.answer3')}}
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.qna_list',$qna->country)}}?page={{ $page }}'">
				{{ __('admin_qna.can')}}
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

	$('#qna_form').on('submit', function(){
			$('textarea[name="description"]').val(My_editor.getData());
			console.log(My_editor.getData());
			return true;
	});


</script>

@endsection

