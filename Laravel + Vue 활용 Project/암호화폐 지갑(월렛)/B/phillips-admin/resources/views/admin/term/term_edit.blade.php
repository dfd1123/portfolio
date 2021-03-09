@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	{{ __('term.admin') }}
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('term.change') }}
	</div>
	<div class="card-body">
		<form method="post" action="{{route('admin.term_service_update',$term->id)}}">
			@csrf
            <input type="hidden" name="country" value="{{$country}}">
			<div>
				<h4>{{ __('term.ser') }}</h4>
                <div>
                    <textarea name="use_term_{{$country}}" id="use_term_{{$country}}">{!! $term->{'use_term_'.$country} !!}</textarea>
                </div>
			</div>
			<div>
				<h4>{{ __('term.info') }}</h4>
                <div>
                    <textarea name="private_infor_term_{{$country}}" id="private_infor_term_{{$country}}">{!! $term->{'private_infor_term_'.$country} !!}</textarea>
                </div>
			</div>
            <div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
						{{ __('term.modify') }}
				</button>
			</div>
		</form>
	</div>
</div>

<script>
	$('#use_term_{{$country}}')
		.summernote({
			height: 350,
			lang: 'ko-KR',
			disableDragAndDrop: true
		});
	$('.note-editing-area').css('word-break', 'break-all');
	$('.note-group-select-from-files').css('display', 'none');

    $('#private_infor_term_{{$country}}')
		.summernote({
			height: 350,
			lang: 'ko-KR',
			disableDragAndDrop: true
		});
	$('.note-editing-area').css('word-break', 'break-all');
	$('.note-group-select-from-files').css('display', 'none');
</script>

@endsection

