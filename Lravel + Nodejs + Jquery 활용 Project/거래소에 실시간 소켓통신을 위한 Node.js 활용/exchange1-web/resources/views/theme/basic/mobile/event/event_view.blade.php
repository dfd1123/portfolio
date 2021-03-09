@extends(session('theme').'.mobile.layouts.app') 
@section('content')
    @include(session('theme').'.mobile.notice.include.sub_menu')

<div class="m_cs_wrap m_cs_wrap-view">

    <div class="cs_table_view ios-scroll">

        <div class="panel_subject">
            <span class="subjt">{{$event->title}}</span>
            <p class="reporting_date">
                <b>{{ __('event.active')}}</b>
                <span class="pl-2 pr-2">
                    @if(strtotime($today) < strtotime($event->start_time))
                    <span class="red">{{ __('event.soon')}}</span>
                    @elseif(strtotime($today) <= strtotime($event->end_time))
                    <span class="blue">{{ __('event.ing')}}</span>
                    @elseif(strtotime($today) > strtotime($event->end_time))
                    <span>{{ __('event.exit')}}</span>
                    @endif
                </span>
            </p>
            <p class="reporting_date">
                <b class="pr-2">{{ __('event.start')}}</b><span>{{$event->start_time}}&nbsp;&nbsp; ~ </span>
                <b class="pl-1 pr-2">{{ __('event.end2')}}</b><span>{{$event->end_time}}</span>
            </p>
        </div>

        <div class="panel_content">
            <img src="{{asset('/storage/image/event/' . $event->image1)}}" /><br />
            <img src="{{asset('/storage/image/event/' . $event->image2)}}" /><br />
            <img src="{{asset('/storage/image/event/' . $event->image3)}}" /><br /> {!! $event->description !!}
        </div>

        <button class="abslt_btn" onclick="location.href='{{route('event')}}'">{{ __('event.gotolist')}}</button>

    </div>

</div>

@endsection