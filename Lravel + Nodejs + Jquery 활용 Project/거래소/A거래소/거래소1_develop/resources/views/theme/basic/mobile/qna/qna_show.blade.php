@extends(session('theme').'.mobile.layouts.app') 
@section('content')
@include(session('theme').'.mobile.notice.include.sub_menu')
    
<div class="m_cs_wrap m_cs_wrap-view">

    <div class="cs_table_view ios-scroll">

        <div class="panel_subject">
            <span class="subjt">{{$qna->title}}</span>
            <span class="reporting_date">
                <span class="point_clr_qna">{{date("Y-m-d",$qna->created)}}</span>
            </span>
        </div>

        <div class="panel_content question_content">
            @if(!empty($qna->image_url))
            <img src="{{asset('/storage/image/qna/'.$qna->image_url)}}" alt="" style="width: 100%;"/>
            @else
            <img src="" alt="" />
            @endif

            {!! $qna->description !!}
            
            {{-- 답변 대기 중일 때 수정버튼 나타남 (완료일 때 사라짐) --}}
            @if(!$qna_answer)
            <div class="text-right mt-4">
                <button id="qnaModify" class="mini_btn_st write_btn rounded">{{ __('support.modify') }}</button>
            </div>
            @endif
            {{-- //답변 대기 중일 때 수정버튼 나타남 (완료일 때 사라짐) --}}
        </div>

        <div class="panel_content answer_content">
            <div class="answer_box">
                @if($qna_answer)
                <p class="answer_p">
                    <i class="fas fa-exclamation-circle red"></i> 
                    {!! $qna_answer->description !!}
                </p>
                @else
                <p class="non_data">
                    <img src="/images/icon_notice.svg" alt="" class="btn_notice"> {{ __('support.surpport_sentence1') }}
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
            
            <form method="post" enctype="multipart/form-data" action="{{route('qna_insert')}}">
                @csrf
                
                <input type="hidden" name="id" value="{{$id}}" />
                    
                <h1 class="notosans mb-4">{{ __('support.change_one_to_one_ask') }}</h1>
                
                <input class="form-control mb-3 form_line" type="text" name="title" value="{{$qna->title}}" placeholder="{{ __('support.input_title') }}">
                
                <textarea class="form-control mb-3" name="description" placeholder="{{ __('support.input_contents') }}">{!! $qna->description !!}</textarea>
    
                <!-- 이미지 첨부 파일 -->
                <div class="image_file">
                    <input type="text" class="upload_text" readonly="readonly">
                    <div class="upload-btn_wrap">
                        <button type="button" title="파일찾기">
                            <span>이미지 첨부</span>  
                        </button>
                        <input type="file" name="file1" class="input_file" title="파일찾기">
                    </div>
                </div>

                <button type="submit" class="btn_style w-100" name="qna_submit" value="edit">
                {{ __('support.registration') }}
                </button>
                
            </form>
            
        </div>
		
	</div>
@endif


@endsection