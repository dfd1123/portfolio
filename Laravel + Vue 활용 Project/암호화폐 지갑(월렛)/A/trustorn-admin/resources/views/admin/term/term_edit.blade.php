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
		<ul class="nav nav-tabs">
				<li class="{{ ($country=='kr')?'active':'' }}"><a href="{{route('admin.term_service', 'kr')}}">{{ __('term.ko') }}</a></li>
				<li class="{{ ($country=='jp')?'active':'' }}"><a href="{{route('admin.term_service', 'jp')}}">{{ __('term.jp') }}</a></li>
				<li class="{{ ($country=='ch')?'active':'' }}"><a href="{{route('admin.term_service', 'ch')}}">{{ __('term.ch') }}</a></li>
				<li class="{{ ($country=='th')?'active':'' }}"><a href="{{route('admin.term_service', 'th')}}">{{ __('term.th') }}</a></li>
				<li class="{{ ($country=='en')?'active':'' }}"><a href="{{route('admin.term_service', 'en')}}">{{ __('term.en') }}</a></li>
				<li class="{{ ($country=='spain')?'active':'' }}"><a href="{{route('admin.term_service', 'spain')}}">{{ __('term.spain') }}</a></li>
		</ul>
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
	$('.note-editor').css('height', '40vmin');
	$('.note-editing-area').css('word-break', 'break-all');
	$('.note-group-select-from-files').css('display', 'none');

    $('#private_infor_term_{{$country}}')
		.summernote({
			height: 350,
			lang: 'ko-KR',
			disableDragAndDrop: true
		});
	$('.note-editor').css('height', '40vmin');
	$('.note-editing-area').css('word-break', 'break-all');
	$('.note-group-select-from-files').css('display', 'none');
</script>

@endsection

