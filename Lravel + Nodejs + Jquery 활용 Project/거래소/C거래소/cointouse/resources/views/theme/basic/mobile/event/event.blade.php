@extends(session('theme').'.mobile.layouts.app') 
@section('content')
    @include(session('theme').'.mobile.notice.include.sub_menu')

<!-- scrl_wrap -->
<div id="event_wrap" class="scrl_wrap m_cs_wrap">

    <table id="event_tbl" class="cs_table cs_event_table" data-offset="15" data-count="{{$count}}">
        <tbody>
            @forelse($events as $event)
            <tr>
                <td>
                    @if(strtotime($today) < strtotime($event->start_time))
                        <b class="red">{{ __('event.soon')}}</b> 
                    @elseif(strtotime($today) <= strtotime($event->end_time))
                        <b class="blue">{{ __('event.ing')}}</b> 
                    @elseif(strtotime($today) > strtotime($event->end_time))
                        <b>{{ __('event.exit')}}</b> 
                    @endif
                </td>
                <td>
                    {{$event->start_time}} ~ {{$event->end_time}}
                </td>
            </tr>
            <tr>
                <td colspan="2" class="list_tit">
                    <a href="{{route('event_view',$event->id)}}">
                            <span>{{$event->title}}</span>
                        </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="non_data">
                    <i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('event.noevent')}}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>
<!-- //scrl_wrap -->

@endsection