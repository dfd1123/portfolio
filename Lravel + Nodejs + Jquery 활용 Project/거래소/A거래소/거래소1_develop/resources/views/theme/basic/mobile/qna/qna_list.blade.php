@extends(session('theme').'.mobile.layouts.app') 
@section('content')
@include(session('theme').'.mobile.notice.include.sub_menu')

<!-- scrl_wrap -->
<div id="qna_wrap" class="scrl_wrap m_cs_wrap">
    
    {{-- 1:1문의 리스트--}}
    @if(Auth::user()->comunity_status == 1)
        <a href="#" id="qnaWrite" class="btn_style qna_btn write_btn">
        {{ __('support.do_one_to_one_ask') }}
        </a>
    @endif
    
    <label class="label">
    {{ __('support.ask_list') }}
    </label>

    <table id="qna_tbl" class="cs_table" data-offset="15" data-count="{{$count}}">
        <tbody>
            @forelse($qnas as $qna)
            <tr>
                <td><a href="{{route('qna_show', $qna->id)}}">{{$qna->title}}</a></td>
                <td class="status">
                    @if($qna->answered)
                    <b class="complete_ans">{{ __('support.success_answer') }}</b>
                    @else
                    <b class="wait_ans">{{ __('support.waiting_answer') }}</b>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="non_data">
                    <img src="/images/icon_notice.svg" alt="" class="icon_align">
                    {{ __('support.not') }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{-- //1:1문의 리스트--}}

</div>
<!-- //scrl_wrap -->

<div id="fullscreen_modal" class="hide"></div>

<div class="modal_popup" id="qnaWrite1">
    
    <div id="cancel_btn">
        <span></span>
    </div>
    
    <div class="form_div">
        
        <form method="post" enctype="multipart/form-data" action="{{route('qna_insert')}}" id="pna_write">
            @csrf

            <h1 class="notosans mb-4">{{ __('support.do_one_to_one_ask') }}</h1>
            
            <input class="form-control mb-3 added_line" type="text" name="title" placeholder="{{ __('support.placeholder_title') }}" required>
            
            <textarea class="form-control mb-4" name="description" placeholder="{{ __('support.placeholder_contents')}}" required>{!!__('support.textarea_contents') !!}</textarea>

            <!-- 이미지 파일 첨부 -->
            <div class="image_file">
                <input type="text" class="upload_text" readonly="readonly">
                <div class="upload-btn_wrap">
                    <button type="button" title="파일찾기">
                    <span>이미지 첨부</span>  
                    </button>
                    <input type="file" name="file1" class="input_file" title="파일찾기">
                </div>
            </div>

            <div class="register_btn">
                <button type="submit" class="btn_style" name="qna_submit" value="create">
                {{ __('support.registration') }}
                </button>
            </div>
        </form>
        
    </div>

</div>

@endsection