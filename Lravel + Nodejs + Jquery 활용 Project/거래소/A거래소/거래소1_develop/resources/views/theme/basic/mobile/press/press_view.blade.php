@extends(session('theme').'.mobile.layouts.app') 
@section('content')
@include(session('theme').'.mobile.notice.include.sub_menu')

<div class="m_cs_wrap m_cs_wrap-view">

    <div class="cs_table_view ios-scroll">

        <div class="panel_subject">
            <span class="subjt">예시) 보도기사 자료 제목입니다.</span>
            <span class="reporting_date">
                <span class="date">2019-08-21</span>
            </span>
        </div>

        <div class="panel_content">
            내용입니다~
        </div>

        <button class="btn_style_next btn_fix" onclick="location.href='#'">{{ __('support.gotolist') }}</button>

    </div>

</div>
@endsection