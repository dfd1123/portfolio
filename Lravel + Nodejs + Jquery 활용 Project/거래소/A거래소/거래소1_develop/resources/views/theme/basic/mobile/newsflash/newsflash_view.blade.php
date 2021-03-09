@extends(session('theme').'.mobile.layouts.app') 
@section('content')
@include(session('theme').'.mobile.notice.include.sub_menu')

<div class="m_cs_wrap m_cs_wrap-view">

    <div class="cs_table_view ios-scroll">

        <div class="panel_subject">
            <span class="subjt">{{$newsflash->title}}</span>
            <span class="reporting_date">
                <span class="point_clr_qna">{{date("Y-m-d", $newsflash->created)}}</span>
            </span>
        </div>

        <div class="panel_content">
            {!! $newsflash->description !!}
        </div>

        <button class="btn_style_next btn_fix" onclick="location.href='{{route('newsflash')}}'">{{ __('support.gotolist') }}</button>

    </div>

</div>
@endsection