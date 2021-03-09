@extends(session('theme').'.mobile.layouts.app') 
@section('content')
@include(session('theme').'.mobile.notice.include.sub_menu')

<div class="m_cs_wrap m_cs_wrap-view">

    <div class="cs_table_view ios-scroll mobile_guide_wrap" id="limit_guide_text_con">

        @include(session('theme').'.mobile.cs_etc.include.limit_guide')

    </div>

</div>

@endsection