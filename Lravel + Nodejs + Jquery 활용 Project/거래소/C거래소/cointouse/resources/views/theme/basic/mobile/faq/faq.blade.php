@extends(session('theme').'.mobile.layouts.app') 
@section('content')
    @include(session('theme').'.mobile.notice.include.sub_menu')

{{-- FAQ 타입 고르기 --}}
<div id="faq_select_wrap" class="select_wrap">
    
    <div class="select_type">
        <label for="faq_type_check">
            <span class="type_tit">{{__('faq.all')}}</span>
            <i class="fal fa-angle-down point_clr_2"></i>
        </label>
    </div>
    
    <input id="faq_type_check" type="checkbox" class="hide">
    
    <ul class="type_list">
        <li>
            <a href="#">{{__('faq.all')}}</a>
        </li>
        <li>
            <a href="#">{{__('faq.1')}}</a>
        </li>
        <li>
            <a href="#">{{__('faq.2')}}</a>
        </li>
        <li>
            <a href="#">{{__('faq.3')}}</a>
        </li>
        <li>
            <a href="#">{{__('faq.4')}}</a>
        </li>
    </ul>
    
</div>
{{-- FAQ 타입 고르기 --}}

<!-- scrl_wrap -->
<div class="scrl_wrap m_cs_wrap m_cs_wrap-faq">

    {{-- 자주 묻는 질문 --}}
    <div class="faq_tab_group">
        @forelse($faqs as $faq)
        <div class="faq_tab">
            <input type="checkbox" id="tab_{{$faq->id}}" name="tabs" class="hide">
            <label for="tab_{{$faq->id}}">
                <span class="faq_type">{{__('faq.'.$faq->faq_type)}}</span>
                <span class="faq_tit">{{$faq->question}}</span>
            </label>
            <div class="tab_content">
                <i class="fas fa-exclamation-circle"></i>
                <div class="tab_answer_box">
                    {!! $faq->answer !!}
                </div>
            </div>
        </div>
        @empty
        <div class="non_data"><i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('support.no') }}</div>
        @endforelse
    </div>
    {{-- //자주 묻는 질문 --}}

</div>
<!-- //scrl_wrap -->

@endsection