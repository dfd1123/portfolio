@extends(session('theme').'.mobile.layouts.app') 
@section('content')
    @include(session('theme').'.mobile.mypage.include.mypage_hd')

<div class="m_mypage_wrap scrl_wrap m_mypage_wrap-1">

    <p class="bb-f0f0 mypage_msg">{{ __('myp.mypage_sentence1') }}</p>

    <form action="{{route('mypage.alarm_setting_update')}}" method="post">

        @csrf

        <div class="bb-f0f0 alarm_div">

            <span>{{ __('myp.event_marketing') }}</span>

            <div class="alarm_group">
                <label for="event_alarm_email" class="mr-3">{{ __('myp.email') }} <input id="event_alarm_email" class="ml-1 grayCheckbox" type="checkbox" name="alarm_email" {{$user->alarm_email == 1 ? 'checked' : ''}}></label>
                <label for="event_alarm_sms">SMS <input id="event_alarm_sms" class="ml-1 grayCheckbox" type="checkbox" name="alarm_sms" {{$user->alarm_sms == 1 ? 'checked' : ''}}></label>
            </div>

        </div>

        <div class="bb-f0f0 alarm_div">

            <span>{{ __('myp.outalr')}}</span>

            <div class="alarm_group">
                <label for="out_alarm_email" class="mr-3">{{ __('myp.email') }} <input id="out_alarm_email" class="ml-1 grayCheckbox" type="checkbox" name="alarm_io_email" {{$user->alarm_io_email == 1 ? 'checked' : ''}}></label>
                <label for="out_alarm_sms">SMS <input id="out_alarm_sms" class="ml-1 grayCheckbox" type="checkbox" name="alarm_io_sms" {{$user->alarm_io_sms == 1 ? 'checked' : ''}}></label>
            </div>

        </div>

        <button class="abslt_btn">
            {{ __('myp.change_notice_setting') }}
        </button>

    </form>

</div>

@endsection