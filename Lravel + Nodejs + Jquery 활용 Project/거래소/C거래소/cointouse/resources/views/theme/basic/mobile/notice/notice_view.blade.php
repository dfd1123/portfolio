@extends(session('theme').'.mobile.layouts.app') 
@section('content')
@include(session('theme').'.mobile.notice.include.sub_menu')

<div class="m_cs_wrap m_cs_wrap-view">

    <div class="cs_table_view ios-scroll">

        <div class="panel_subject">
            <span class="subjt">{{$notice->title}}</span>
            <span class="reporting_date">
                <b>{{ __('support.date_created') }}</b> <span class="pl-2">{{date("Y-m-d", $notice->created)}}</span>
            </span>
        </div>

        <div class="panel_content">
            {!! $notice->description !!}
        </div>

        <button class="abslt_btn" onclick="location.href='{{route('notice')}}'">{{ __('support.gotolist') }}</button>

    </div>

</div>
@endsection