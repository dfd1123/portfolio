@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
        언론보도 관리
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
        언론보도 수정
	</div>
	<div class="card-body">
		<form method="post" action="{{route('admin.news_update',$id)}}"  enctype="multipart/form-data">
			@csrf
            <input type="hidden" name="country" value="{{$country}}">
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;"> {{ __('notice.tt')}}</th>
							<td>
								<input type="text" name="title" class="form-control tsa-input-st" value="{{$news->title}}" required="required"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;"> 썸네일 이미지</th>
							<td>
								<div class="filebox bs3-primary preview-image">
									@if($news->thumb_img != NULL)
									<div class="upload-display">
										<div class="upload-thumb-wrap">
											<img src="{{asset('/storage/image/news'.$news->thumb_img)}}" class="upload-thumb">
										</div>
									</div>
									@endif
									<input class="upload-name hide" value="파일선택" disabled="disabled" style="width: 200px;">

									<label for="input_file">업로드</label> 
									<input type="file" name="image[]" id="input_file" class="upload-hidden" accept="image/*" > 
								</div>
							</td>
						</tr>
						<tr>
							<th style="width:10%;"> {{ __('notice.con')}}</th>
							<td>
								<textarea rows="15" name="description" id="editor" class="form-control" required="required">{!! $news->description !!}</textarea>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
				{{ __('notice.edit1')}}
				</button>
                <button type="button" onclick="location.href='{{route('admin.news_delete',['country' => $country, 'id' => $id])}}'">
				{{ __('notice.del')}}
                </button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.news_list',$country)}}'">
				{{ __('notice.cel')}}
				</button>
			</div>
		</form>
	</div>
</div>


<style>
	.filebox input[type="file"] {
		position: absolute;
		width: 1px;
		height: 1px;
		padding: 0;
		margin: -1px;
		overflow: hidden;
		clip:rect(0,0,0,0);
		border: 0;
	}

	.filebox label {
		display: inline-block;
		padding: .5em .75em;
		color: #999;
		font-size: inherit;
		line-height: normal;
		vertical-align: middle;
		background-color: #fdfdfd;
		cursor: pointer;
		border: 1px solid #ebebeb;
		border-bottom-color: #e2e2e2;
		border-radius: .25em;
	}

	/* named upload */
	.filebox .upload-name {
		display: inline-block;
		padding: .5em .75em;
		font-size: inherit;
		font-family: inherit;
		line-height: normal;
		vertical-align: middle;
		background-color: #f5f5f5;
	border: 1px solid #ebebeb;
	border-bottom-color: #e2e2e2;
	border-radius: .25em;
	-webkit-appearance: none; /* 네이티브 외형 감추기 */
	-moz-appearance: none;
	appearance: none;
	}

	/* imaged preview */
	.filebox .upload-display {
		margin-bottom: 5px;
	}

	@media(min-width: 768px) {
		.filebox .upload-display {
			display: inline-block;
			margin-right: 5px;
			margin-bottom: 0;
		}
	}

	.filebox .upload-thumb-wrap {
		display: inline-block;
		width: 54px;
		padding: 2px;
		vertical-align: middle;
		border: 1px solid #ddd;
		border-radius: 5px;
		background-color: #fff;
	}

	.filebox .upload-display img {
		display: block;
		max-width: 100%;
		width: 100% \9;
		height: auto;
	}

	.filebox.bs3-primary label {
		color: #fff;
		background-color: #337ab7;
		border-color: #2e6da4;
		margin:0;
	}
</style>

<script>
	var fileTarget = $('.filebox .upload-hidden');

	fileTarget.on('change', function(){
		if(window.FileReader){
			// 파일명 추출
			var filename = $(this)[0].files[0].name;
		} 

		else {
			// Old IE 파일명 추출
			var filename = $(this).val().split('/').pop().split('\\').pop();
		};

		$(this).siblings('.upload-name').val(filename);
	});

	//preview image 
	var imgTarget = $('.preview-image .upload-hidden');

	imgTarget.on('change', function(){
		var parent = $(this).parent();
		parent.children('.upload-display').remove();

		if(window.FileReader){
			//image 파일만
			if (!$(this)[0].files[0].type.match(/image\//)) return;
			
			var reader = new FileReader();
			reader.onload = function(e){
				var src = e.target.result;
				parent.prepend('<div class="upload-display"><div class="upload-thumb-wrap"><img src="'+src+'" class="upload-thumb"></div></div>');
			}
			reader.readAsDataURL($(this)[0].files[0]);
		}

		else {
			$(this)[0].select();
			$(this)[0].blur();
			var imgSrc = document.selection.createRange().text;
			parent.prepend('<div class="upload-display"><div class="upload-thumb-wrap"><img class="upload-thumb"></div></div>');

			var img = $(this).siblings('.upload-display').find('img');
			img[0].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(enable='true',sizingMethod='scale',src=\""+imgSrc+"\")";        
		}
	});

	$('#editor')
		.summernote({
			height: 350,
			lang: 'ko-KR',
			disableDragAndDrop: true
		});
	$('.note-editing-area').css('word-break', 'break-all');
	$('.note-group-select-from-files').css('display', 'none');
</script>

@endsection

