@extends(session('theme').'.mobile.layouts.app') 
@section('content')
@include(session('theme').'.mobile.notice.include.sub_menu')
    
<div class="m_cs_wrap m_cs_wrap-view">

    <div class="cs_table_view ios-scroll">

        <div class="panel_subject">
            <span class="subjt">{{$qna->title}}</span>
            <span class="reporting_date">
                <b>{{ __('support.date_created') }}</b> <span class="pl-2">{{date("Y-m-d",$qna->created)}}</span>
            </span>
        </div>

        <div class="panel_content question_content">
            {!! $qna->description !!}
            
            {{-- 답변 대기 중일 때 수정버튼 나타남 (완료일 때 사라짐) --}}
            @if(!$qna_answer)
            <div class="text-right mt-4">
                <button id="qnaModify" class="mini_btn_st write_btn">{{ __('support.modify') }}</button>
            </div>
            @endif
            {{-- //답변 대기 중일 때 수정버튼 나타남 (완료일 때 사라짐) --}}
        </div>

        <div class="panel_content answer_content">
            <div class="answer_box">
                @if($qna_answer)
                <p class="answer_p">
                    <i class="fas fa-exclamation-circle red"></i> 
                    {{-- {!! $qna_answer->description !!} --}}
                </p>
                 @else
                <p class="non_data">
                    <i class="fas fa-exclamation-circle not_i"></i> {{ __('support.surpport_sentence1') }}
                </p>
                @endif
            </div>
        </div>
        
        <button class="abslt_btn" onclick="location.href='{{route('qna_list')}}'">{{ __('support.gotolist') }}</button>

    </div>
    
</div>

@if(!$qna_answer)
	<div id="fullscreen_modal" class="hide"></div>

	<div class="modal_popup" id="qnaModify1">

        <div id="cancel_btn">
            <span></span>
        </div>
        
        <div class="form_div">
            
            <form method="post" action="{{route('qna_insert')}}">
                @csrf
                <input type="hidden" name="id" value="{{$id}}" />
                    
                <h1 class="notosans mb-4">{{ __('support.change_one_to_one_ask') }}</h1>
                
                <input class="form-control mb-3" type="text" name="title" value="{{$qna->title}}" placeholder="{{ __('support.input_title') }}">
                
                <textarea class="form-control mb-4" name="description">{!! $qna->description !!}</textarea>
    
                <button type="submit" class="btn_style w-50" name="qna_submit" value="edit">
                {{ __('support.modify_good') }}
                </button>
                
            </form>
                    
        </div>
		
	</div>
@endif


@endsection