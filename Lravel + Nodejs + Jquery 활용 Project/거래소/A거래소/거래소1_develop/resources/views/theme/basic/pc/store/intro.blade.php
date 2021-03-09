@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div id="store_wrapper">

    <div class="hd_container">
        <div class="tit_group">
            <h2 class="_tit">{{ __('support.title_store') }}</h2>
            <p class="_text">{!! __('support.title_store_bt_ment') !!}</p>
        </div>
    </div>

    <div class="store_intro_section">
        <div class="_inner">
            <img src="/images/ticket1.gif" alt="ex_store_img_01">
            <div class="text_group">
                <h4 class="_tit">{{ __('support.title_store_section_01') }}</h4>
                <p class="_text">{!! __('support.title_store_section_01_txt') !!}</p>
            </div>
        </div>
    </div>
    
    <div class="store_intro_section store_intro_section--bg">
        <div class="_inner">
            <img src="/images/ticket2.gif" alt="ex_store_img_02">
            <div class="text_group">
                <h4 class="_tit">{{ __('support.title_store_section_02') }}</h4>
                <p class="_text">{!! __('support.title_store_section_02_txt') !!}</p>
            </div>
        </div>
    </div>

    <div class="store_intro_section">
        <div class="_inner">
            <img src="/images/official-store.gif" alt="ex_store_img_01">
            <div class="text_group">
                <h4 class="_tit">{{ __('support.title_store_section_03') }}</h4>
                <p class="_text">{!! __('support.title_store_section_03_txt') !!}</p>
            </div>
        </div>
    </div>


</div>

@endsection